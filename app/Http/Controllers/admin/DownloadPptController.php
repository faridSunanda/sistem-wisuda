<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Sesi;

class DownloadPptController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        $sesis = Sesi::all();
        
        return view('admin.download-ppt.index', compact('groups', 'sesis'));
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

    public function show(string $id)
    {
        $user = DB::table('users')
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

        if (!$user) {
            abort(404);
        }

        return view('admin.download-ppt.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = DB::table('users')
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

        if (!$user) {
            abort(404);
        }

        $groups = Group::all();
        $sesis = Sesi::all();

        return view('admin.download-ppt.edit', compact('user', 'groups', 'sesis'));
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
                if ($biodata->wisuda_id) {
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

            DB::commit();

            return redirect()->route('admin.download-ppt.index')
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
                return response()->json([
                    'success' => false,
                    'message' => 'Biodata tidak ditemukan.'
                ], 404);
            }

            DB::table('group_wisudawans')
                ->where('biodata_id', $biodata->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data wisudawan berhasil dihapus dari group.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getData(Request $request)
    {
        try {
            $query = DB::table('users')
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

            // Filter jika ada
            if ($request->filled('fakultas')) {
                $query->where('biodatas.fakultas', $request->fakultas);
            }

            if ($request->filled('prodi')) {
                $query->where('biodatas.program_studi', $request->prodi);
            }

            if ($request->filled('angkatan')) {
                $query->where('biodatas.tahun_masuk', $request->angkatan);
            }

            if ($request->filled('group_id')) {
                $query->where('group_wisudawans.group_id', $request->group_id);
            }

            if ($request->filled('sesi_id')) {
                $query->where('group_wisudawans.sesi_id', $request->sesi_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('no_urut', function($row) {
                    return $row->nomor_urut ?? '-';
                })
                ->addColumn('nama_lengkap', function($row) {
                    return $row->name_lengkap ?? '-';
                })
                ->addColumn('nim', function($row) {
                    return $row->nim ?? '-';
                })
                ->addColumn('angkatan', function($row) {
                    return $row->angkatan ?? '-';
                })
                ->addColumn('group', function($row) {
                    return $row->group_name ?? '-';
                })
                ->addColumn('sesi', function($row) {
                    return $row->sesi_name ?? '-';
                })
                ->addColumn('jenjang', function($row) {
                    return $row->jenjang ?? 'S1';
                })
                ->addColumn('fakultas', function($row) {
                    return $row->fakultas ?? '-';
                })
                ->addColumn('prodi', function($row) {
                    return $row->prodi ?? '-';
                })
                ->addColumn('checkbox', function($row) {
                    return '<input type="checkbox" class="wisudawan-checkbox" value="' . $row->id . '" data-group-wisudawan-id="' . ($row->group_wisudawan_id ?? '') . '">';
                })
                ->addColumn('aksi', function($row) {
                    return $this->getActionButtons($row->id);
                })
                ->rawColumns(['checkbox', 'aksi'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function previewPpt(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu wisudawan untuk preview.');
        }

        // Logic untuk preview PPT akan diimplementasikan nanti
        return redirect()->back()->with('info', 'Fitur preview PPT akan segera tersedia.');
    }

    public function downloadPpt(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu wisudawan untuk download.');
        }

        // Logic untuk download PPT akan diimplementasikan nanti
        return redirect()->back()->with('info', 'Fitur download PPT akan segera tersedia.');
    }

    public function pindahkanKe(Request $request)
    {
        $selectedIds = $request->input('ids', []);
        $targetGroupId = $request->input('target_group_id');
        $targetSesiId = $request->input('target_sesi_id');

        if (empty($selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal satu wisudawan untuk dipindahkan.'
            ], 400);
        }

        if (!$targetGroupId || !$targetSesiId) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih group dan sesi tujuan.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // selectedIds adalah user_id, perlu ambil biodata_id dulu
            $biodataIds = DB::table('biodatas')
                ->whereIn('user_id', $selectedIds)
                ->pluck('id')
                ->toArray();

            if (empty($biodataIds)) {
                throw new \Exception('Biodata tidak ditemukan untuk user yang dipilih.');
            }

            foreach ($biodataIds as $biodataId) {
                // Update atau create group_wisudawan
                $existing = DB::table('group_wisudawans')
                    ->where('biodata_id', $biodataId)
                    ->first();

                if ($existing) {
                    DB::table('group_wisudawans')
                        ->where('biodata_id', $biodataId)
                        ->update([
                            'group_id' => $targetGroupId,
                            'sesi_id' => $targetSesiId,
                            'updated_at' => now()
                        ]);
                } else {
                    // Jika belum ada, perlu wisuda_id juga
                    $biodata = DB::table('biodatas')->where('id', $biodataId)->first();
                    if ($biodata && $biodata->wisuda_id) {
                        DB::table('group_wisudawans')->insert([
                            'id' => \Illuminate\Support\Str::uuid(),
                            'wisuda_id' => $biodata->wisuda_id,
                            'biodata_id' => $biodataId,
                            'group_id' => $targetGroupId,
                            'sesi_id' => $targetSesiId,
                            'nomor_urut' => 0,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data wisudawan berhasil dipindahkan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memindahkan data: ' . $e->getMessage()
            ], 500);
        }
    }
}

