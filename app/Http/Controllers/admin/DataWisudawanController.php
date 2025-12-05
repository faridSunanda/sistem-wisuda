<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Biodata;
use App\Exports\DataWisudawanExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class DataWisudawanController extends Controller
{
    public function index()
    {
        return view('admin.data-wisudawan.index');
    }

    public function getData(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->filterColumn('nama', fn($q, $keyword) => $q->whereRaw('users.name_lengkap LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('nim', fn($q, $keyword) => $q->whereRaw('biodatas.nim LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('tahun_masuk', fn($q, $keyword) => $q->whereRaw('biodatas.tahun_masuk LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('fakultas', fn($q, $keyword) => $q->whereRaw('biodatas.fakultas LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('prodi', fn($q, $keyword) => $q->whereRaw('biodatas.program_studi LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('jenjang', fn($q, $keyword) => $q->whereRaw('(' . $this->getJenjangCaseStatement() . ') LIKE ?', ["%{$keyword}%"]))
                ->addColumn('aksi', fn($row) => $this->getActionButtons($row->id))
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            return $this->datatableErrorResponse($request, $e);
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
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);
            
            $data = $query->get();
            $fileName = 'Data_Wisudawan_' . date('Y-m-d') . '.pdf';

            $html = view('admin.data-wisudawan.pdf', [
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
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export PDF: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = $this->getUserWithBiodata($id);
        return view('admin.data-wisudawan.detail', compact('user'));
    }

    public function edit($id)
    {
        $user = $this->getUserWithBiodata($id);
        return view('admin.data-wisudawan.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        $validated = $request->validate([
            'name_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'nim' => 'nullable|string|max:50',
            'tahun_masuk' => 'nullable|integer|min:2000|max:' . date('Y'),
            'fakultas' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name_lengkap' => $validated['name_lengkap'],
                'email' => $validated['email'],
            ]);

            $this->updateOrCreateBiodata($id, $validated);

            DB::commit();
            return redirect()->route('admin.data-wisudawan.index')
                ->with('success', 'Data wisudawan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

            DB::beginTransaction();
            $user->delete();
            DB::commit();

            return $this->jsonResponse(true, 'Data wisudawan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse(false, 'Terjadi kesalahan saat menghapus data.', 500);
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
                DB::raw('COALESCE(biodatas.program_studi, "") as prodi')
            )
            ->where('users.role', 'mahasiswa');
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
            $query->whereRaw($this->getJenjangCaseStatement() . ' = ?', [$jenjang]);
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

    private function getUserWithBiodata($id)
    {
        return User::with('biodata')
            ->where('id', $id)
            ->where('role', 'mahasiswa')
            ->firstOrFail();
    }

    private function updateOrCreateBiodata($userId, array $validated)
    {
        $biodata = Biodata::where('user_id', $userId)->first();
        
        $biodataData = [
            'nim' => $validated['nim'] ?? null,
            'tahun_masuk' => $validated['tahun_masuk'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'program_studi' => $validated['program_studi'] ?? null,
        ];

        if ($biodata) {
            $biodata->update($biodataData);
        } else {
            Biodata::create(array_merge(['user_id' => $userId], $biodataData));
        }
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
            'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
        ], 500);
    }
}
