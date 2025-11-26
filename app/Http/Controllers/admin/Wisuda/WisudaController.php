<?php

namespace App\Http\Controllers\Admin\Wisuda;

use App\Http\Controllers\Controller;
use App\Models\JadwalPendaftaran;
use App\Models\KuotaWisudawan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Exports\WisudaExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class WisudaController extends Controller
{
    public function index()
    {
        return view('admin.wisuda.wisuda.index');
    }

    public function create()
    {
        return view('admin.wisuda.wisuda.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_wisuda' => 'required|integer|min:2000|max:2100',
            'status' => 'required|in:Dibuka,Ditutup',
            'waktu_buka_pendaftaran' => 'required|date',
            'waktu_tutup_pendaftaran' => 'required|date|after:waktu_buka_pendaftaran',
            'jumlah_kuota' => 'nullable|integer|min:1',
        ]);

        // Cek apakah tahun wisuda sudah ada
        $existing = JadwalPendaftaran::where('tahun_wisuda', $validated['tahun_wisuda'])->exists();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal untuk tahun wisuda ' . $validated['tahun_wisuda'] . ' sudah ada.');
        }

        try {
            $jadwalPendaftaran = JadwalPendaftaran::create([
                'tahun_wisuda' => $validated['tahun_wisuda'],
                'status' => $validated['status'],
                'waktu_buka_pendaftaran' => $validated['waktu_buka_pendaftaran'],
                'waktu_tutup_pendaftaran' => $validated['waktu_tutup_pendaftaran'],
            ]);

            // Jika ada kuota, buat juga kuota wisudawan
            if (isset($validated['jumlah_kuota']) && $validated['jumlah_kuota'] > 0) {
                KuotaWisudawan::create([
                    'pendaftaran_wisuda_id' => $jadwalPendaftaran->id,
                    'jumlah_kuota' => $validated['jumlah_kuota'],
                ]);
            }

            return redirect()->route('admin.wisuda.wisuda.index')
                ->with('success', 'Data wisuda berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->with('kuotaWisudawan')
                ->orderBy('tahun_wisuda', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('angkatan', function($row) {
                    return $row->tahun_wisuda;
                })
                ->addColumn('tanggal_pendaftaran', function($row) {
                    return Carbon::parse($row->waktu_buka_pendaftaran)->format('d F Y H:i') . ' WIB';
                })
                ->addColumn('tanggal_penutupan', function($row) {
                    return Carbon::parse($row->waktu_tutup_pendaftaran)->format('d F Y H:i') . ' WIB';
                })
                ->addColumn('kuota_wisudawan', function($row) {
                    return $row->kuotaWisudawan ? number_format($row->kuotaWisudawan->jumlah_kuota, 0, ',', '.') : '-';
                })
                ->addColumn('status_badge', function($row) {
                    return $this->getStatusBadge($row->status);
                })
                ->addColumn('aksi', function($row) {
                    return $this->getActionButtons($row->id);
                })
                ->rawColumns(['status_badge', 'aksi'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
    }

    private function getStatusBadge($status)
    {
        $statusText = $status === 'Dibuka' ? 'Dibuka' : 'Ditutup';
        $badgeClass = $status === 'Dibuka' ? 'badge badge-success' : 'badge badge-secondary';

        return '<span class="' . $badgeClass . '">' . $statusText . '</span>';
    }

    private function getActionButtons($id)
    {
        return '<div class="flex items-center justify-center gap-2">' .
            '<button class="btn-action btn-view" onclick="lihatData(\'' . $id . '\')" title="Lihat">' .
            '<i class="fas fa-eye"></i>' .
            '</button>' .
            '<button class="btn-action btn-edit" onclick="editData(\'' . $id . '\')" title="Edit">' .
            '<i class="fas fa-pencil-alt"></i>' .
            '</button>' .
            '<button class="btn-action btn-delete" onclick="hapusData(\'' . $id . '\', this)" title="Hapus">' .
            '<i class="fas fa-trash"></i>' .
            '</button>' .
            '</div>';
    }

    public function exportData(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->with('kuotaWisudawan')
                ->orderBy('tahun_wisuda', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'angkatan' => $item->tahun_wisuda,
                        'tanggal_pendaftaran' => Carbon::parse($item->waktu_buka_pendaftaran)->format('d F Y H:i') . ' WIB',
                        'tanggal_penutupan' => Carbon::parse($item->waktu_tutup_pendaftaran)->format('d F Y H:i') . ' WIB',
                        'kuota_wisudawan' => $item->kuotaWisudawan ? number_format($item->kuotaWisudawan->jumlah_kuota, 0, ',', '.') : '-',
                        'status' => $item->status === 'Dibuka' ? 'Dibuka' : 'Ditutup',
                    ];
                });

            if ($data->isEmpty()) {
                return response()->json([]);
            }

            return response()->json($data->toArray());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $wisuda = JadwalPendaftaran::with('kuotaWisudawan')->findOrFail($id);

        return view('admin.wisuda.wisuda.show', [
            'wisuda' => $wisuda
        ]);
    }

    public function edit(string $id)
    {
        $wisuda = JadwalPendaftaran::with('kuotaWisudawan')->findOrFail($id);

        return view('admin.wisuda.wisuda.edit', [
            'wisuda' => $wisuda
        ]);
    }

    public function update(Request $request, string $id)
    {
        $wisuda = JadwalPendaftaran::findOrFail($id);

        $validated = $request->validate([
            'tahun_wisuda' => 'required|integer|min:2000|max:2100',
            'status' => 'required|in:Dibuka,Ditutup',
            'waktu_buka_pendaftaran' => 'required|date',
            'waktu_tutup_pendaftaran' => 'required|date|after:waktu_buka_pendaftaran',
            'jumlah_kuota' => 'nullable|integer|min:1',
        ]);

        // Cek apakah tahun wisuda sudah ada (kecuali untuk record yang sedang diedit)
        $existing = JadwalPendaftaran::where('tahun_wisuda', $validated['tahun_wisuda'])
                                    ->where('id', '!=', $id)
                                    ->exists();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jadwal untuk tahun wisuda ' . $validated['tahun_wisuda'] . ' sudah ada.');
        }

        try {
            $wisuda->update([
                'tahun_wisuda' => $validated['tahun_wisuda'],
                'status' => $validated['status'],
                'waktu_buka_pendaftaran' => $validated['waktu_buka_pendaftaran'],
                'waktu_tutup_pendaftaran' => $validated['waktu_tutup_pendaftaran'],
            ]);

            // Update atau create kuota
            if (isset($validated['jumlah_kuota']) && $validated['jumlah_kuota'] > 0) {
                if ($wisuda->kuotaWisudawan) {
                    $wisuda->kuotaWisudawan->update(['jumlah_kuota' => $validated['jumlah_kuota']]);
                    // Refresh relasi untuk memastikan data terbaru
                    $wisuda->load('kuotaWisudawan');
                } else {
                    $wisuda->kuotaWisudawan()->create(['jumlah_kuota' => $validated['jumlah_kuota']]);
                    // Refresh relasi setelah create
                    $wisuda->load('kuotaWisudawan');
                }
            }

            return redirect()->route('admin.wisuda.wisuda.index')
                ->with('success', 'Data wisuda berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $wisuda = JadwalPendaftaran::findOrFail($id);
            $wisuda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data wisuda berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->with('kuotaWisudawan')
                ->orderBy('tahun_wisuda', 'desc')
                ->get();

            $fileName = 'Data_Wisuda_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new WisudaExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = JadwalPendaftaran::query()
                ->with('kuotaWisudawan')
                ->orderBy('tahun_wisuda', 'desc')
                ->get();

            $fileName = 'Data_Wisuda_' . date('Y-m-d') . '.pdf';

            $html = view('admin.wisuda.wisuda.pdf', [
                'data' => $data,
                'title' => 'Data Wisuda'
            ])->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('a4', 'landscape');
            $dompdf->render();

            return response()->streamDownload(function() use ($dompdf) {
                echo $dompdf->output();
            }, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export PDF: ' . $e->getMessage());
        }
    }
}

