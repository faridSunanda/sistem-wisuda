<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Biodata;
use Carbon\Carbon;

class DataWisudawanController extends Controller
{
    private const INSTITUTION_CODE = '87654';
    private const DEFAULT_AMOUNT = 3500000;
    private const BANKS = ['BRI', 'BNI', 'Mandiri', 'BCA'];
    private const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        return view('keuangan.data-wisudawan.index');
    }

    public function getData(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', fn($row) => $this->getStatusBadge($row))
                ->filterColumn('nama', fn($q, $keyword) => $q->whereRaw('users.name_lengkap LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('nim', fn($q, $keyword) => $q->whereRaw('biodatas.nim LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('tahun_masuk', fn($q, $keyword) => $q->whereRaw('biodatas.tahun_masuk LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('fakultas', fn($q, $keyword) => $q->whereRaw('biodatas.fakultas LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('prodi', fn($q, $keyword) => $q->whereRaw('biodatas.program_studi LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('jenjang', fn($q, $keyword) => $q->whereRaw('(' . $this->getJenjangCaseStatement() . ') LIKE ?', ["%{$keyword}%"]))
                ->addColumn('kode_briva', fn($row) => $this->generateBrivaNumber($row->nim ?? ''))
                ->addColumn('pembayaran', fn($row) => $row->is_bayar ? 'Rp' . number_format(self::DEFAULT_AMOUNT, 0, ',', '.') : '-')
                ->addColumn('semester', fn($row) => ($row->is_bayar && !empty($row->tahun_masuk)) ? $this->calculateSemester($row->tahun_masuk) : '-')
                ->addColumn('bank', fn($row) => $row->is_bayar ? $this->getBankByNim($row->nim ?? '') : '-')
                ->addColumn('tanggal_transaksi', fn($row) => $this->formatTanggalTransaksi($row))
                ->addColumn('aksi', fn($row) => $this->getActionButton(
                    $row->id, 
                    $row->is_bayar ?? false, 
                    $row->is_verified_keuangan ?? false, 
                    $row->is_verified_akademik ?? false
                ))
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        } catch (\Exception $e) {
            return $this->datatableErrorResponse($request, $e);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('biodata')
                ->where('id', $id)
                ->where('role', 'mahasiswa')
                ->firstOrFail();

            if (!$user->biodata) {
                return redirect()->route('keuangan.data-wisudawan.index')
                    ->with('error', 'Biodata mahasiswa tidak ditemukan.');
            }

            $kodeBriva = $this->generateBrivaNumber($user->biodata->nim ?? '');

            return view('keuangan.data-wisudawan.detail', compact('user', 'kodeBriva'));
        } catch (\Exception $e) {
            Log::error('Error showing detail mahasiswa: ' . $e->getMessage());
            return redirect()->route('keuangan.data-wisudawan.index')
                ->with('error', 'Terjadi kesalahan saat memuat data mahasiswa.');
        }
    }

    public function getPaymentData(Request $request, $id)
    {
        try {
            $user = User::with('biodata')
                ->where('id', $id)
                ->where('role', 'mahasiswa')
                ->firstOrFail();

            if (!$user->biodata) {
                return $this->emptyDataTableResponse($request);
            }

            $biodata = $user->biodata;
            $pembayaran = $this->getPaymentHistory(
                $biodata->nim ?? '',
                $biodata->is_bayar ?? false,
                $biodata->is_verified_keuangan ?? false,
                $biodata->tahun_masuk ?? null,
                $biodata->updated_at ?? null
            );

            return DataTables::of(collect($pembayaran))
                ->addIndexColumn()
                ->addColumn('status_badge', fn($row) => $this->getPaymentStatusBadge($row))
                ->editColumn('pembayaran', fn($row) => $this->getArrayValue($row, 'pembayaran'))
                ->editColumn('nominal', fn($row) => $this->getArrayValue($row, 'nominal'))
                ->editColumn('tanggal_transaksi', fn($row) => $this->getArrayValue($row, 'tanggal_transaksi'))
                ->editColumn('bank', fn($row) => $this->getArrayValue($row, 'bank'))
                ->editColumn('va_code', fn($row) => $this->formatVaCode($this->getArrayValue($row, 'va_code')))
                ->editColumn('semester', fn($row) => $this->getArrayValue($row, 'semester'))
                ->rawColumns(['status_badge', 'va_code'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error getting payment data: ' . $e->getMessage());
            return $this->errorDataTableResponse($request, $e);
        }
    }

    public function verify(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();
            $biodata = Biodata::where('user_id', $id)->first();

            if (!$biodata) {
                return $this->jsonError('Biodata tidak ditemukan', 404);
            }

            if (!$biodata->is_bayar) {
                return $this->jsonError('Tidak dapat memverifikasi. Mahasiswa belum melakukan pembayaran.', 400);
            }

            if ($biodata->is_verified_keuangan) {
                return $this->jsonError('Pembayaran sudah terverifikasi sebelumnya.', 400);
            }

            $biodata->is_verified_keuangan = true;
            $biodata->updated_at = now();
            $biodata->save();
            $biodata->refresh();

            if (!$biodata->is_verified_keuangan) {
                Log::error('Update failed: is_verified_keuangan is still false', [
                    'user_id' => $id,
                    'biodata_id' => $biodata->id
                ]);
                return $this->jsonError('Gagal mengupdate status pembayaran. Silakan coba lagi.', 500);
            }

            Log::info('Pembayaran diverifikasi', [
                'user_id' => $id,
                'nim' => $biodata->nim,
                'verified_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi. Status berubah menjadi "Sudah Bayar".'
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying payment: ' . $e->getMessage(), [
                'user_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            return $this->jsonError('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function verifyAll(Request $request)
    {
        try {
            DB::beginTransaction();

            $count = Biodata::where('is_bayar', true)
                ->where('is_verified_keuangan', false)
                ->update([
                    'is_verified_keuangan' => true,
                    'updated_at' => now()
                ]);

            if ($count === 0) {
                DB::rollBack();
                return $this->jsonError('Tidak ada pembayaran yang menunggu konfirmasi.', 400);
            }

            DB::commit();

            Log::info('Bulk verify pembayaran', [
                'count' => $count,
                'verified_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengkonfirmasi {$count} pembayaran sekaligus."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk verifying payments: ' . $e->getMessage(), [
                'error' => $e->getTraceAsString()
            ]);
            return $this->jsonError('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function prosesWisudawan(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();
            $biodata = Biodata::where('user_id', $id)->first();

            if (!$biodata) {
                return $this->jsonError('Biodata tidak ditemukan', 404);
            }

            if (!$biodata->is_verified_keuangan) {
                return $this->jsonError('Tidak dapat memproses wisudawan. Pembayaran belum diverifikasi.', 400);
            }

            if ($biodata->is_verified_akademik) {
                return $this->jsonError('Wisudawan sudah terverifikasi sebelumnya.', 400);
            }

            $biodata->is_verified_akademik = true;
            $biodata->updated_at = now();
            $biodata->save();

            Log::info('Wisudawan diproses', [
                'user_id' => $id,
                'nim' => $biodata->nim,
                'processed_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wisudawan berhasil diproses. Status berubah menjadi "Terverifikasi".'
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing wisudawan: ' . $e->getMessage(), [
                'user_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            return $this->jsonError('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function prosesWisudawanAll(Request $request)
    {
        try {
            DB::beginTransaction();

            $count = Biodata::where('is_bayar', true)
                ->where('is_verified_keuangan', true)
                ->where('is_verified_akademik', false)
                ->update([
                    'is_verified_akademik' => true,
                    'updated_at' => now()
                ]);

            if ($count === 0) {
                DB::rollBack();
                return $this->jsonError('Tidak ada wisudawan yang dapat diproses.', 400);
            }

            DB::commit();

            Log::info('Bulk proses wisudawan', [
                'count' => $count,
                'processed_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil memproses {$count} wisudawan sekaligus."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk processing wisudawan: ' . $e->getMessage(), [
                'error' => $e->getTraceAsString()
            ]);
            return $this->jsonError('Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    private function buildBaseQuery()
    {
        return DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->select(
                'users.id',
                'users.name_lengkap as nama',
                DB::raw('COALESCE(biodatas.nim, "") as nim'),
                DB::raw('COALESCE(biodatas.tahun_masuk, "") as tahun_masuk'),
                DB::raw($this->getJenjangCaseStatement() . ' as jenjang'),
                DB::raw('COALESCE(biodatas.fakultas, "") as fakultas'),
                DB::raw('COALESCE(biodatas.program_studi, "") as prodi'),
                DB::raw('COALESCE(biodatas.is_bayar, false) as is_bayar'),
                DB::raw('COALESCE(biodatas.is_verified_keuangan, false) as is_verified_keuangan'),
                DB::raw('COALESCE(biodatas.is_verified_akademik, false) as is_verified_akademik'),
                DB::raw('biodatas.updated_at as updated_at')
            )
            ->where('users.role', 'mahasiswa');
    }

    private function applyFilters($query, Request $request)
    {
        if (!$request->filled('status')) {
            return;
        }

        $status = $request->input('status');
        $filters = [
            'belum_bayar' => fn($q) => $q->whereRaw('COALESCE(biodatas.is_bayar, false) = false'),
            'menunggu_konfirmasi' => fn($q) => $q->whereRaw('COALESCE(biodatas.is_bayar, false) = true')
                ->whereRaw('COALESCE(biodatas.is_verified_keuangan, false) = false'),
            'sudah_bayar' => fn($q) => $q->whereRaw('COALESCE(biodatas.is_bayar, false) = true')
                ->whereRaw('COALESCE(biodatas.is_verified_keuangan, false) = true')
                ->whereRaw('COALESCE(biodatas.is_verified_akademik, false) = false'),
            'terverifikasi' => fn($q) => $q->whereRaw('COALESCE(biodatas.is_verified_akademik, false) = true'),
        ];

        if (isset($filters[$status])) {
            $filters[$status]($query);
        }
    }

    private function getJenjangCaseStatement()
    {
        return "CASE 
            WHEN biodatas.program_studi IS NULL OR biodatas.program_studi = '' THEN 'S1'
            WHEN LOWER(biodatas.program_studi) LIKE '%s3%' OR LOWER(biodatas.program_studi) LIKE '%doktor%' THEN 'S3'
            WHEN LOWER(biodatas.program_studi) LIKE '%s2%' OR LOWER(biodatas.program_studi) LIKE '%magister%' THEN 'S2'
            WHEN LOWER(biodatas.program_studi) LIKE '%s1%' OR LOWER(biodatas.program_studi) LIKE '%sarjana%' THEN 'S1'
            ELSE 'S1'
        END";
    }

    private function getActionButton($id, $isBayar, $isVerified, $isVerifiedAkademik)
    {
        $detailUrl = route('keuangan.data-wisudawan.detail-mahasiswa', $id);
        $verifyUrl = route('keuangan.data-wisudawan.verify', $id);
        $prosesUrl = route('keuangan.data-wisudawan.proses-wisudawan', $id);
        
        $buttons = '<div class="flex items-center justify-center gap-2">';
        $buttons .= '<a href="' . $detailUrl . '" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-[#435ebe] hover:bg-[#3a4fa8] rounded-lg transition-colors duration-200" title="Detail Pembayaran"><i class="fas fa-eye"></i></a>';
        
        if ($isBayar && !$isVerified) {
            $buttons .= '<button onclick="konfirmasiPembayaran(\'' . $id . '\', \'' . $verifyUrl . '\')" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition-colors duration-200" title="Konfirmasi Pembayaran"><i class="fas fa-check-double"></i></button>';
        }
        
        if ($isBayar && $isVerified && !$isVerifiedAkademik) {
            $buttons .= '<button onclick="prosesWisudawan(\'' . $id . '\', \'' . $prosesUrl . '\')" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors duration-200" title="Proses Wisudawan"><i class="fas fa-check-circle"></i></button>';
        }
        
        $buttons .= '</div>';
        return $buttons;
    }

    private function getStatusBadge($row)
    {
        if ($row->is_verified_akademik) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Terverifikasi</span>';
        }
        if ($row->is_bayar && $row->is_verified_keuangan) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Bayar</span>';
        }
        if ($row->is_bayar && !$row->is_verified_keuangan) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>';
        }
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Bayar</span>';
    }

    private function getPaymentStatusBadge($row)
    {
        $status = $this->getArrayValue($row, 'status');
        $badges = [
            'Sudah Bayar' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Bayar</span>',
            'Menunggu Konfirmasi' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Konfirmasi</span>',
        ];
        return $badges[$status] ?? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Bayar</span>';
    }

    private function formatTanggalTransaksi($row)
    {
        if (!$row->is_bayar || empty($row->updated_at)) {
            return '-';
        }

        try {
            $date = Carbon::parse($row->updated_at);
            return $date->format('d') . ' ' . self::MONTHS[$date->month - 1] . ' ' . $date->format('Y');
        } catch (\Exception $e) {
            Log::error('Error parsing tanggal transaksi: ' . $e->getMessage());
            return '-';
        }
    }

    private function getBankByNim($nim)
    {
        $bankIndex = !empty($nim) ? (intval($nim) % count(self::BANKS)) : 0;
        return self::BANKS[$bankIndex];
    }

    private function getPaymentHistory($nim, $isBayar, $isVerified, $tahunMasuk, $updatedAt)
    {
        $kodeBriva = $this->generateBrivaNumber($nim);
        
        if (!$isBayar) {
            return [[
                'pembayaran' => 'Wisuda',
                'nominal' => 'Rp' . number_format(self::DEFAULT_AMOUNT, 0, ',', '.'),
                'status' => 'Belum Bayar',
                'tanggal_transaksi' => '-',
                'bank' => '-',
                'va_code' => $kodeBriva,
                'semester' => $tahunMasuk ? $this->calculateSemester($tahunMasuk) : '-'
            ]];
        }

        $status = $isVerified ? 'Sudah Bayar' : 'Menunggu Konfirmasi';
        $tanggalTransaksi = '-';
        $bank = '-';

        if ($updatedAt) {
            try {
                $date = Carbon::parse($updatedAt);
                $tanggalTransaksi = $date->format('d') . ' ' . self::MONTHS[$date->month - 1] . ' ' . $date->format('Y');
                $bank = $this->getBankByNim($nim);
            } catch (\Exception $e) {
                Log::error('Error parsing tanggal pembayaran: ' . $e->getMessage());
            }
        }

        return [[
            'pembayaran' => 'Wisuda',
            'nominal' => 'Rp' . number_format(self::DEFAULT_AMOUNT, 0, ',', '.'),
            'status' => $status,
            'tanggal_transaksi' => $tanggalTransaksi,
            'bank' => $bank,
            'va_code' => $kodeBriva,
            'semester' => $tahunMasuk ? $this->calculateSemester($tahunMasuk) : '-'
        ]];
    }

    private function getArrayValue($row, $key)
    {
        return is_array($row) ? ($row[$key] ?? '-') : ($row->$key ?? '-');
    }

    private function formatVaCode($vaCode)
    {
        return $vaCode !== '-' ? '<span class="font-mono">' . $vaCode . '</span>' : '-';
    }

    private function generateBrivaNumber($nim)
    {
        if (empty($nim)) {
            return '-';
        }
        return self::INSTITUTION_CODE . str_pad($nim, 13, '0', STR_PAD_LEFT);
    }

    private function calculateSemester($tahunMasuk)
    {
        if (empty($tahunMasuk)) {
            return '-';
        }

        try {
            $tahunMasuk = intval($tahunMasuk);
            $tahunSekarang = Carbon::now()->year;
            $bulanSekarang = Carbon::now()->month;
            $isSemesterGanjil = ($bulanSekarang >= 1 && $bulanSekarang <= 6);
            $tahunPerbedaan = $tahunSekarang - $tahunMasuk;

            if ($tahunPerbedaan < 0) {
                return '-';
            }

            $totalSemester = ($tahunPerbedaan * 2) + ($isSemesterGanjil ? 1 : 2);
            $tahunFormat = substr($tahunSekarang, -2);
            $semesterFormat = min($totalSemester, 14);

            return $tahunFormat . $semesterFormat;
        } catch (\Exception $e) {
            Log::error('Error calculating semester: ' . $e->getMessage());
            return '-';
        }
    }

    private function jsonError($message, $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    private function emptyDataTableResponse(Request $request)
    {
        return response()->json([
            'draw' => intval($request->input('draw', 0)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Biodata mahasiswa tidak ditemukan'
        ], 404);
    }

    private function errorDataTableResponse(Request $request, \Exception $e)
    {
        return response()->json([
            'draw' => intval($request->input('draw', 0)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
        ], 500);
    }

    private function datatableErrorResponse(Request $request, \Exception $e)
    {
        Log::error('DataTable error: ' . $e->getMessage(), [
            'request' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'draw' => intval($request->input('draw', 0)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
        ], 500);
    }
}
