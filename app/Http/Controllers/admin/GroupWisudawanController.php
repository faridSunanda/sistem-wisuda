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
        return view('admin.group-wisudawan.index', [
            'groups' => Group::all(),
            'sesis' => Sesi::all()
        ]);
    }

    public function show(string $id)
    {
        $user = $this->getUserWithGroupData($id) ?? abort(404);
        return view('admin.group-wisudawan.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = $this->getUserWithGroupDataForEdit($id) ?? abort(404);
        return view('admin.group-wisudawan.edit', [
            'user' => $user,
            'groups' => Group::all(),
            'sesis' => Sesi::all()
        ]);
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

    public function getData(Request $request)
    {
        try {
            $query = $this->buildBaseQuery();
            $this->applyFilters($query, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->filterColumn('nim', fn($q, $keyword) => $q->whereRaw('biodatas.nim LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('name_lengkap', fn($q, $keyword) => $q->whereRaw('users.name_lengkap LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('fakultas', fn($q, $keyword) => $q->whereRaw('biodatas.fakultas LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('prodi', fn($q, $keyword) => $q->whereRaw('biodatas.program_studi LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('angkatan', fn($q, $keyword) => $q->whereRaw('biodatas.tahun_masuk LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('group', fn($q, $keyword) => $q->whereRaw('groups.name LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('sesi', fn($q, $keyword) => $q->whereRaw('sesi.name LIKE ?', ["%{$keyword}%"]))
                ->filterColumn('nomor_urut', fn($q, $keyword) => $q->whereRaw('CAST(group_wisudawans.nomor_urut AS CHAR) LIKE ?', ["%{$keyword}%"]))
                ->addColumn('group_wisudawan_id', fn($row) => $row->group_wisudawan_id ?? '')
                ->addColumn('nomor_urut', fn($row) => $row->nomor_urut ?? 0)
                ->addColumn('group_id', fn($row) => $row->group_id ?? '')
                ->addColumn('sesi_id', fn($row) => $row->sesi_id ?? '')
                ->addColumn('nama_lengkap', fn($row) => $row->name_lengkap ?? '-')
                ->addColumn('nim', fn($row) => $row->nim ?? '-')
                ->addColumn('angkatan', fn($row) => $row->angkatan ?? '-')
                ->addColumn('group', fn($row) => $row->group_name ?? '-')
                ->addColumn('sesi', fn($row) => $row->sesi_name ?? '-')
                ->addColumn('jenjang', fn($row) => $row->jenjang ?? 'S1')
                ->addColumn('fakultas', fn($row) => $row->fakultas ?? '-')
                ->addColumn('prodi', fn($row) => $row->prodi ?? '-')
                ->addColumn('checkbox', fn($row) => $this->renderCheckboxWithHandle($row))
                ->addColumn('aksi', fn($row) => $this->getActionButtons($row->id))
                ->rawColumns(['checkbox', 'aksi'])
                ->make(true);

        } catch (\Exception $e) {
            return $this->datatableErrorResponse($request, $e);
        }
    }

    public function downloadPpt(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        
        if (empty($selectedIds)) {
            return $this->jsonResponse(false, 'Pilih minimal satu wisudawan untuk download.', 400);
        }

        return $this->jsonResponse(true, 'Download PPT berhasil dimulai.', 200, [
            'count' => count($selectedIds),
            'posisi' => $request->input('posisi', 'kanan'),
            'urutan' => $request->input('urutan', 'pertama')
        ]);
    }

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

    public function updateUrutan(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|uuid|exists:group_wisudawans,id',
            'orders.*.nomor_urut' => 'required|integer|min:1',
            'group_id' => 'required|uuid|exists:groups,id',
            'sesi_id' => 'required|uuid|exists:sesi,id',
        ]);

        try {
            DB::beginTransaction();

            $allGroupWisudawans = $this->getGroupWisudawansByGroupAndSesi(
                $validated['group_id'],
                $validated['sesi_id']
            );

            $reorderedMap = array_column($validated['orders'], 'nomor_urut', 'id');
            $reorderedIds = array_keys($reorderedMap);

            [$reorderedItems, $nonReorderedItems] = $this->separateItems(
                $allGroupWisudawans,
                $reorderedIds,
                $reorderedMap
            );

            $finalOrder = array_merge($reorderedItems, $nonReorderedItems);
            $this->updateNomorUrut($finalOrder);

            DB::commit();

            return $this->jsonResponse(true, 'Urutan data berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse(false, 'Gagal memperbarui urutan: ' . $e->getMessage(), 500);
        }
    }

    private function getUserWithGroupData(string $id, bool $forEdit = false)
    {
        $selects = [
            'users.*',
            'biodatas.*',
            'group_wisudawans.nomor_urut'
        ];

        if ($forEdit) {
            $selects[] = 'groups.id as group_id';
            $selects[] = 'sesi.id as sesi_id';
            $selects[] = 'group_wisudawans.id as group_wisudawan_id';
        } else {
            $selects[] = 'groups.name as group_name';
            $selects[] = 'sesi.name as sesi_name';
        }

        return DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->leftJoin('group_wisudawans', 'biodatas.id', '=', 'group_wisudawans.biodata_id')
            ->leftJoin('groups', 'group_wisudawans.group_id', '=', 'groups.id')
            ->leftJoin('sesi', 'group_wisudawans.sesi_id', '=', 'sesi.id')
            ->where('users.id', $id)
            ->select($selects)
            ->first();
    }

    private function getUserWithGroupDataForEdit(string $id)
    {
        return $this->getUserWithGroupData($id, true);
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
                throw new \Exception('Biodata tidak memiliki wisuda_id.');
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
            ->join('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->leftJoin('group_wisudawans', 'biodatas.id', '=', 'group_wisudawans.biodata_id')
            ->leftJoin('groups', 'group_wisudawans.group_id', '=', 'groups.id')
            ->leftJoin('sesi', 'group_wisudawans.sesi_id', '=', 'sesi.id')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('sertifikats')
                    ->whereColumn('sertifikats.biodata_id', 'biodatas.id')
                    ->whereNull('sertifikats.deleted_at');
            })
            ->select([
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
                'group_wisudawans.id as group_wisudawan_id',
                'group_wisudawans.group_id',
                'group_wisudawans.sesi_id'
            ])
            ->where('users.role', 'mahasiswa')
            ->where('biodatas.is_bayar', true)
            ->where('biodatas.is_verified_akademik', true)
            ->where('biodatas.is_verified_keuangan', true)
            ->whereNull('biodatas.deleted_at')
            ->orderBy('group_wisudawans.nomor_urut', 'asc');
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
            $request->whenFilled($key, fn($value) => $query->where($column, $value));
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
        $existingBiodataIds = DB::table('group_wisudawans')
            ->whereIn('biodata_id', $validBiodataIds)
            ->pluck('biodata_id')
            ->toArray();

        $toUpdate = array_intersect($validBiodataIds, $existingBiodataIds);
        $toInsert = array_diff($validBiodataIds, $existingBiodataIds);

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


    private function renderCheckboxWithHandle($row)
    {
        $checkbox = sprintf(
            '<input type="checkbox" class="wisudawan-checkbox" value="%s" data-group-wisudawan-id="%s">',
            e($row->id),
            e($row->group_wisudawan_id ?? '')
        );

        $noUrut = sprintf(
            '<span class="no-urut-text font-medium text-gray-700">%s</span>',
            $row->nomor_urut ?? '-'
        );

        $handle = '<i class="fas fa-grip-vertical text-gray-400 cursor-move drag-handle"></i>';

        return sprintf(
            '<div class="flex items-center justify-center gap-2">%s%s%s</div>',
            $checkbox,
            $noUrut,
            $handle
        );
    }

    private function getActionButtons($id)
    {
        $escapedId = e($id);
        return sprintf(
            '<div class="flex items-center justify-center gap-2">
                <button class="btn-action btn-view" onclick="lihatData(\'%s\')" title="Lihat">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-action btn-edit" onclick="editData(\'%s\')" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="btn-action btn-delete" onclick="hapusData(\'%s\', this)" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </div>',
            $escapedId,
            $escapedId,
            $escapedId
        );
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

    private function getGroupWisudawansByGroupAndSesi(string $groupId, string $sesiId)
    {
        return DB::table('group_wisudawans')
            ->where('group_id', $groupId)
            ->where('sesi_id', $sesiId)
            ->orderBy('nomor_urut', 'asc')
            ->get();
    }

    private function separateItems($allItems, array $reorderedIds, array $reorderedMap)
    {
        $reorderedItems = [];
        $nonReorderedItems = [];

        foreach ($allItems as $item) {
            if (in_array($item->id, $reorderedIds)) {
                $reorderedItems[] = $item;
            } else {
                $nonReorderedItems[] = $item;
            }
        }

        usort($reorderedItems, function($a, $b) use ($reorderedMap) {
            return $reorderedMap[$a->id] <=> $reorderedMap[$b->id];
        });

        return [$reorderedItems, $nonReorderedItems];
    }

    private function updateNomorUrut(array $items)
    {
        foreach ($items as $index => $item) {
            DB::table('group_wisudawans')
                ->where('id', $item->id)
                ->update([
                    'nomor_urut' => $index + 1,
                    'updated_at' => now()
                ]);
        }
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
