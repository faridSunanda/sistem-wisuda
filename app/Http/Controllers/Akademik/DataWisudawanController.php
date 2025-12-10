<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Biodata;
use App\Exports\DataWisudawanExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class DataWisudawanController extends Controller
{
    public function index()
    {
        return view('akademik.data-wisudawan.index');
    }

    public function getData(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);

            return DataTables::of($query)
                ->addIndexColumn()

                ->filterColumn('nama', function($q, $keyword) {
                    $q->whereRaw('LOWER(users.name_lengkap) LIKE ?', ["%".strtolower($keyword)."%"]);
                })

                ->filterColumn('nim', function($q, $keyword) {
                    $q->where('biodatas.nim', 'LIKE', "%{$keyword}%");
                })

                ->filterColumn('tahun_masuk', function($q, $keyword) {
                    $q->where('biodatas.tahun_masuk', 'LIKE', "%{$keyword}%");
                })

                ->filterColumn('fakultas', function($q, $keyword) {
                    $q->whereRaw('LOWER(biodatas.fakultas) LIKE ?', ["%".strtolower($keyword)."%"]);
                })

                ->filterColumn('prodi', function($q, $keyword) {
                    $q->whereRaw('LOWER(biodatas.program_studi) LIKE ?', ["%".strtolower($keyword)."%"]);
                })

                ->filterColumn('jenjang', function($q, $keyword) {
                    $q->whereRaw('(' . $this->getJenjangCaseStatement() . ') LIKE ?', ["%{$keyword}%"]);
                })

                ->addColumn('is_verified', function($row) {
                    return $row->is_verified_akademik;
                })
                ->addColumn('aksi', function($row) {
                    return $this->getActionButtons($row);
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            return $this->datatableErrorResponse($request, $e);
        }
    }
    public function show($id)
    {

        $biodata = Biodata::with('user')->findOrFail($id);
        return view('akademik.data-wisudawan.detail', compact('biodata'));
    }


    public function verify(Request $request, $id)
    {
        try {
            $biodata = Biodata::findOrFail($id);
            
            $newState = !$biodata->is_verified_akademik;
            
            $biodata->update([
                'is_verified_akademik' => $newState
            ]);

            $statusText = $newState ? 'terverifikasi' : 'dibatalkan verifikasinya';
            $message = "Data wisudawan berhasil {$statusText}.";

            if ($request->ajax() || $request->wantsJson()) {
                return $this->jsonResponse(true, $message);
            } else {
                return redirect()->back()->with('success', $message);
            }

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->jsonResponse(false, 'Gagal mengubah status verifikasi.', 500);
            } else {
                return redirect()->back()->with('error', 'Gagal mengubah status verifikasi.');
            }
        }
    }


    public function exportData(Request $request)
    {
        $query = $this->buildBaseQuery();
        $this->applyFilters($query, $request);
        
        return response()->json($query->get());
    }

    public function exportExcel(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);
            
            $data = $query->get();
            $fileName = 'Data_Wisudawan_' . date('Y-m-d') . '.xlsx';

            return Excel::download(new DataWisudawanExport($data), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);
            
            $data = $query->get();
            $fileName = 'Data_Wisudawan_' . date('Y-m-d') . '.pdf';

            $html = view('akademik.data-wisudawan.pdf', [
                'data' => $data,
                'title' => 'Data Wisudawan'
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
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }


    private function buildBaseQuery()
    {
        return DB::table('biodatas')
            ->join('users', 'biodatas.user_id', '=', 'users.id')
            ->select(
                'biodatas.id',
                'users.name_lengkap as nama',
                'biodatas.nim',
                'biodatas.tahun_masuk',
                'biodatas.fakultas',
                'biodatas.program_studi as prodi',
                'biodatas.is_verified_akademik', 
                DB::raw($this->getJenjangCaseStatement() . ' as jenjang')
            )
            ->whereNull('biodatas.deleted_at')
            ->whereNull('users.deleted_at');
    }

    private function applyFilters($query, Request $request)
    {
        $filters = [
            'fakultas' => 'biodatas.fakultas',
            'prodi' => 'biodatas.program_studi',
            'tahun_masuk' => 'biodatas.tahun_masuk'
        ];

        foreach ($filters as $key => $column) {
            if ($request->filled($key)) {
                $query->where($column, $request->input($key));
            }
        }

        if ($request->filled('jenjang')) {
            $jenjang = $request->input('jenjang');
            $query->whereRaw('(' . $this->getJenjangCaseStatement() . ') = ?', [$jenjang]);
        }
    }

    private function getJenjangCaseStatement()
    {
        return "CASE 
            WHEN biodatas.program_studi IS NULL OR biodatas.program_studi = '' THEN 'S1'
            WHEN LOWER(biodatas.program_studi) LIKE '%s3%' OR LOWER(biodatas.program_studi) LIKE '%doktor%' THEN 'S3'
            WHEN LOWER(biodatas.program_studi) LIKE '%s2%' OR LOWER(biodatas.program_studi) LIKE '%magister%' THEN 'S2'
            WHEN LOWER(biodatas.program_studi) LIKE '%s1%' OR LOWER(biodatas.program_studi) LIKE '%sarjana%' THEN 'S1'
            WHEN LOWER(biodatas.program_studi) LIKE '%d3%' OR LOWER(biodatas.program_studi) LIKE '%diploma%' THEN 'D3'
            ELSE 'S1'
        END";
    }

    private function getActionButtons($row)
    {
        $isVerified = $row->is_verified_akademik;
        
        $verifyBtnClass = $isVerified ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700';
        $verifyIcon = $isVerified ? 'fa-times-circle' : 'fa-check-circle';
        $verifyTitle = $isVerified ? 'Batalkan Verifikasi' : 'Verifikasi Data';
        
        $jsVerifyCall = "verifikasiData('{$row->id}', " . ($isVerified ? 'true' : 'false') . ")";

        return '<div class="flex items-center justify-center gap-2">' .
            '<button class="btn-action btn-view" onclick="lihatData(\'' . $row->id . '\')" title="Lihat Detail">' .
            '<i class="fas fa-eye"></i>' .
            '</button>' .
            '<button class="btn-action ' . $verifyBtnClass . '" onclick="' . $jsVerifyCall . '" title="' . $verifyTitle . '">' .
            '<i class="fas ' . $verifyIcon . '"></i>' .
            '</button>' .
            '</div>';
    }


    private function jsonResponse(bool $success, string $message, int $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message
        ], $status);
    }


    private function datatableErrorResponse(Request $request, \Exception $e)
    {
        return response()->json([
            'draw' => intval($request->input('draw', 0)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}