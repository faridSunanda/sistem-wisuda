<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Sesi;

class GroupWisudawanController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        $sesis = Sesi::all();
        
        return view('admin.group-wisudawan.index', compact('groups', 'sesis'));
    }

    public function show(string $id)
    {
        $user = $this->getUserWithGroupData($id);
        
        if (!$user) {
            abort(404);
        }

        return view('admin.group-wisudawan.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = $this->getUserWithGroupDataForEdit($id);
        
        if (!$user) {
            abort(404);
        }

        $groups = Group::all();
        $sesis = Sesi::all();

        return view('admin.group-wisudawan.edit', compact('user', 'groups', 'sesis'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'group_id' => 'required|uuid|exists:groups,id',
            'sesi_id' => 'required|uuid|exists:sesi,id',
            'nomor_urut' => 'nullable|integer|min:1',
        ]);

        try {
            $biodata = DB::table('biodatas')->where('user_id', $id)->first();
            
            if (!$biodata) {
                return redirect()->back()->with('error', 'Biodata tidak ditemukan.');
            }

            DB::beginTransaction();

            $this->updateOrCreateGroupWisudawan($biodata, $validated);

            DB::commit();

            return redirect()->route('admin.group-wisudawan.index')
                ->with('success', 'Data wisudawan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $biodata = DB::table('biodatas')->where('user_id', $id)->first();
            
            if (!$biodata) {
                return $this->jsonResponse(false, 'Biodata tidak ditemukan.', 404);
            }

            DB::table('group_wisudawans')
                ->where('biodata_id', $biodata->id)
                ->delete();

            return $this->jsonResponse(true, 'Data wisudawan berhasil dihapus dari group.');

        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Gagal menghapus data: ' . $e->getMessage(), 500);
        }
    }

    // Get data for DataTables
    public function getData(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->filterColumn('nim', fn($q, $keyword) => $q->whereRaw('biodatas.nim like ?', ["%{$keyword}%"]))
                ->filterColumn('name_lengkap', fn($q, $keyword) => $q->whereRaw('users.name_lengkap like ?', ["%{$keyword}%"]))
                ->filterColumn('fakultas', fn($q, $keyword) => $q->whereRaw('biodatas.fakultas like ?', ["%{$keyword}%"]))
                ->filterColumn('prodi', fn($q, $keyword) => $q->whereRaw('biodatas.program_studi like ?', ["%{$keyword}%"]))
                ->filterColumn('angkatan', fn($q, $keyword) => $q->whereRaw('biodatas.tahun_masuk like ?', ["%{$keyword}%"]))
                ->filterColumn('group', fn($q, $keyword) => $q->whereRaw('groups.name like ?', ["%{$keyword}%"]))
                ->filterColumn('sesi', fn($q, $keyword) => $q->whereRaw('sesi.name like ?', ["%{$keyword}%"]))
                ->filterColumn('nomor_urut', fn($q, $keyword) => $q->whereRaw('CAST(group_wisudawans.nomor_urut AS CHAR) like ?', ["%{$keyword}%"]))
                ->addColumn('no_urut', fn($row) => $row->nomor_urut ?? '-')
                ->addColumn('nama_lengkap', fn($row) => $row->name_lengkap ?? '-')
                ->addColumn('nim', fn($row) => $row->nim ?? '-')
                ->addColumn('angkatan', fn($row) => $row->angkatan ?? '-')
                ->addColumn('group', fn($row) => $row->group_name ?? '-')
                ->addColumn('sesi', fn($row) => $row->sesi_name ?? '-')
                ->addColumn('jenjang', fn($row) => $row->jenjang ?? 'S1')
                ->addColumn('fakultas', fn($row) => $row->fakultas ?? '-')
                ->addColumn('prodi', fn($row) => $row->prodi ?? '-')
                ->addColumn('checkbox', fn($row) => $this->renderCheckbox($row))
                ->addColumn('aksi', fn($row) => $this->getActionButtons($row->id))
                ->rawColumns(['checkbox', 'aksi'])
                ->make(true);

        } catch (\Exception $e) {
            return $this->datatableErrorResponse($request, $e);
        }
    }

    // Download PPT
    public function downloadPpt(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        $posisi = $request->input('posisi', 'kanan');
        $urutan = $request->input('urutan', 'pertama');
        
        if (empty($selectedIds)) {
            return $this->jsonResponse(false, 'Pilih minimal satu wisudawan untuk download.', 400);
        }

        return $this->jsonResponse(true, 'Download PPT berhasil dimulai.', 200, [
            'count' => count($selectedIds),
            'posisi' => $posisi,
            'urutan' => $urutan
        ]);
    }

    // Pindahkan ke
    public function pindahkanKe(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        $targetGroupId = $request->input('target_group_id');
        $targetSesiId = $request->input('target_sesi_id');

        if (empty($selectedIds)) {
            return $this->jsonResponse(false, 'Pilih minimal satu wisudawan untuk dipindahkan.', 400);
        }

        if (!$targetGroupId || !$targetSesiId) {
            return $this->jsonResponse(false, 'Pilih group dan sesi tujuan.', 400);
        }

        try {
            DB::beginTransaction();

            $biodataIds = $this->getBiodataIds($selectedIds);
            
            if (empty($biodataIds)) {
                throw new \Exception('Biodata tidak ditemukan untuk user yang dipilih.');
            }

            $biodatas = $this->getValidBiodatas($biodataIds);
            
            if ($biodatas->isEmpty()) {
                throw new \Exception('Tidak ada biodata dengan wisuda_id yang valid.');
            }

            $this->moveGroupWisudawans($biodatas, $targetGroupId, $targetSesiId);

            DB::commit();

            return $this->jsonResponse(true, 'Data wisudawan berhasil dipindahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse(false, 'Gagal memindahkan data: ' . $e->getMessage(), 500);
        }
    }

    // Private helper methods
    private function getUserWithGroupData(string $id)
    {
        return DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->leftJoin('group_wisudawans', 'biodatas.id', '=', 'group_wisudawans.biodata_id')
            ->leftJoin('groups', 'group_wisudawans.group_id', '=', 'groups.id')
            ->leftJoin('sesi', 'group_wisudawans.sesi_id', '=', 'sesi.id')
            ->where('users.id', $id)
            ->select(
                'users.*',
                'biodatas.*',
                'groups.name as group_name',
                'sesi.name as sesi_name',
                'group_wisudawans.nomor_urut'
            )
            ->first();
    }

    private function getUserWithGroupDataForEdit(string $id)
    {
        return DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->leftJoin('group_wisudawans', 'biodatas.id', '=', 'group_wisudawans.biodata_id')
            ->leftJoin('groups', 'group_wisudawans.group_id', '=', 'groups.id')
            ->leftJoin('sesi', 'group_wisudawans.sesi_id', '=', 'sesi.id')
            ->where('users.id', $id)
            ->select(
                'users.*',
                'biodatas.*',
                'groups.id as group_id',
                'sesi.id as sesi_id',
                'group_wisudawans.nomor_urut',
                'group_wisudawans.id as group_wisudawan_id'
            )
            ->first();
    }

    private function updateOrCreateGroupWisudawan($biodata, array $validated)
    {
        $existing = DB::table('group_wisudawans')
            ->where('biodata_id', $biodata->id)
            ->first();

        if ($existing) {
            DB::table('group_wisudawans')
                ->where('biodata_id', $biodata->id)
                ->update([
                    'group_id' => $validated['group_id'],
                    'sesi_id' => $validated['sesi_id'],
                    'nomor_urut' => $validated['nomor_urut'] ?? $existing->nomor_urut,
                    'updated_at' => now()
                ]);
        } else {
            if (!$biodata->wisuda_id) {
                throw new \Exception('Biodata tidak memiliki wisuda_id. Tidak dapat menambahkan ke group.');
            }
            
            DB::table('group_wisudawans')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'wisuda_id' => $biodata->wisuda_id,
                'biodata_id' => $biodata->id,
                'group_id' => $validated['group_id'],
                'sesi_id' => $validated['sesi_id'],
                'nomor_urut' => $validated['nomor_urut'] ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function buildBaseQuery()
    {
        return DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->leftJoin('group_wisudawans', 'biodatas.id', '=', 'group_wisudawans.biodata_id')
            ->leftJoin('groups', 'group_wisudawans.group_id', '=', 'groups.id')
            ->leftJoin('sesi', 'group_wisudawans.sesi_id', '=', 'sesi.id')
            ->select(
                'users.id',
                'users.name_lengkap',
                DB::raw('COALESCE(biodatas.nim, "") as nim'),
                DB::raw('COALESCE(biodatas.tahun_masuk, "") as angkatan'),
                DB::raw('COALESCE(groups.name, "") as group_name'),
                DB::raw('COALESCE(sesi.name, "") as sesi_name'),
                DB::raw('COALESCE("S1", "") as jenjang'),
                DB::raw('COALESCE(biodatas.fakultas, "") as fakultas'),
                DB::raw('COALESCE(biodatas.program_studi, "") as prodi'),
                DB::raw('COALESCE(group_wisudawans.nomor_urut, 0) as nomor_urut'),
                'group_wisudawans.id as group_wisudawan_id'
            )
            ->where('users.role', 'mahasiswa')
            ->whereNotNull('biodatas.id');
    }

    private function applyFilters($query, Request $request)
    {
        $filters = [
            'fakultas' => 'biodatas.fakultas',
            'prodi' => 'biodatas.program_studi',
            'angkatan' => 'biodatas.tahun_masuk',
            'group_id' => 'group_wisudawans.group_id',
            'sesi_id' => 'group_wisudawans.sesi_id'
        ];

        foreach ($filters as $key => $column) {
            if ($request->filled($key)) {
                $query->where($column, $request->input($key));
            }
        }
    }

    private function getBiodataIds(array $userIds)
    {
        return DB::table('biodatas')
            ->whereIn('user_id', $userIds)
            ->pluck('id')
            ->toArray();
    }

    private function getValidBiodatas(array $biodataIds)
    {
        return DB::table('biodatas')
            ->whereIn('id', $biodataIds)
            ->whereNotNull('wisuda_id')
            ->get()
            ->keyBy('id');
    }

    private function moveGroupWisudawans($biodatas, string $targetGroupId, string $targetSesiId)
    {
        $validBiodataIds = $biodatas->pluck('id')->toArray();
        $existingGroupWisudawans = DB::table('group_wisudawans')
            ->whereIn('biodata_id', $validBiodataIds)
            ->pluck('biodata_id')
            ->toArray();

        $toUpdate = array_intersect($validBiodataIds, $existingGroupWisudawans);
        $toInsert = array_diff($validBiodataIds, $existingGroupWisudawans);

        if (!empty($toUpdate)) {
            DB::table('group_wisudawans')
                ->whereIn('biodata_id', $toUpdate)
                ->update([
                    'group_id' => $targetGroupId,
                    'sesi_id' => $targetSesiId,
                    'updated_at' => now()
                ]);
        }

        if (!empty($toInsert)) {
            $insertData = $this->prepareInsertData($biodatas, $toInsert, $targetGroupId, $targetSesiId);
            if (!empty($insertData)) {
                DB::table('group_wisudawans')->insert($insertData);
            }
        }
    }

    private function prepareInsertData($biodatas, array $biodataIds, string $targetGroupId, string $targetSesiId)
    {
        $insertData = [];
        $now = now();
        
        foreach ($biodataIds as $biodataId) {
            $biodata = $biodatas->get($biodataId);
            if ($biodata && $biodata->wisuda_id) {
                $insertData[] = [
                    'id' => \Illuminate\Support\Str::uuid(),
                    'wisuda_id' => $biodata->wisuda_id,
                    'biodata_id' => $biodataId,
                    'group_id' => $targetGroupId,
                    'sesi_id' => $targetSesiId,
                    'nomor_urut' => 0,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        return $insertData;
    }

    private function renderCheckbox($row)
    {
        $disabled = '';
        return '<input type="checkbox" class="wisudawan-checkbox" value="' . $row->id . '" data-group-wisudawan-id="' . ($row->group_wisudawan_id ?? '') . '" ' . $disabled . '>';
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

    private function jsonResponse(bool $success, string $message, int $status = 200, array $data = [])
    {
        $response = [
            'success' => $success,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    private function datatableErrorResponse(Request $request, \Exception $e)
    {
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
        ], 500);
    }
}
