<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Biodata;

class DataWisudawanController extends Controller
{
    public function index()
    {
        return view('admin.data-wisudawan.index');
    }

    public function getData(Request $request)
    {
        $query = DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->select(
                'users.id',
                'users.name_lengkap as nama',
                DB::raw('COALESCE(biodatas.nim, "") as nim'),
                DB::raw('COALESCE(biodatas.tahun_masuk, "") as tahun_masuk'),
                DB::raw('COALESCE("", "") as jenjang'),
                DB::raw('COALESCE(biodatas.fakultas, "") as fakultas'),
                DB::raw('COALESCE(biodatas.program_studi, "") as prodi')
            )
            ->where('users.role', 'mahasiswa');

        if ($request->filled('fakultas')) {
            $query->where('biodatas.fakultas', $request->fakultas);
        }

        if ($request->filled('prodi')) {
            $query->where('biodatas.program_studi', $request->prodi);
        }

        if ($request->filled('tahun_masuk')) {
            $query->where('biodatas.tahun_masuk', $request->tahun_masuk);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $btn = '<div class="flex items-center justify-center gap-2">';
                $btn .= '<button class="btn-action btn-view" onclick="lihatData(\''.$row->id.'\')" title="Lihat">';
                $btn .= '<i class="fas fa-eye"></i>';
                $btn .= '</button>';
                $btn .= '<button class="btn-action btn-edit" onclick="editData(\''.$row->id.'\')" title="Edit">';
                $btn .= '<i class="fas fa-pencil-alt"></i>';
                $btn .= '</button>';
                $btn .= '<button class="btn-action btn-delete" onclick="hapusData(\''.$row->id.'\')" title="Hapus">';
                $btn .= '<i class="fas fa-trash"></i>';
                $btn .= '</button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function exportData(Request $request)
    {
        $query = DB::table('users')
            ->leftJoin('biodatas', 'users.id', '=', 'biodatas.user_id')
            ->select(
                'users.name_lengkap as nama',
                DB::raw('COALESCE(biodatas.nim, "") as nim'),
                DB::raw('COALESCE(biodatas.tahun_masuk, "") as tahun_masuk'),
                DB::raw('COALESCE("", "") as jenjang'),
                DB::raw('COALESCE(biodatas.fakultas, "") as fakultas'),
                DB::raw('COALESCE(biodatas.program_studi, "") as prodi')
            )
            ->where('users.role', 'mahasiswa');

        if ($request->filled('fakultas')) {
            $query->where('biodatas.fakultas', $request->fakultas);
        }

        if ($request->filled('prodi')) {
            $query->where('biodatas.program_studi', $request->prodi);
        }

        if ($request->filled('tahun_masuk')) {
            $query->where('biodatas.tahun_masuk', $request->tahun_masuk);
        }

        if ($request->filled('jenjang')) {
            // Filter jenjang jika ada di database nanti
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $user = User::with('biodata')->where('id', $id)->where('role', 'mahasiswa')->firstOrFail();
        return view('admin.data-wisudawan.detail', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('biodata')->where('id', $id)->where('role', 'mahasiswa')->firstOrFail();
        return view('admin.data-wisudawan.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        $request->validate([
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
                'name_lengkap' => $request->name_lengkap,
                'email' => $request->email,
            ]);

            $biodata = Biodata::where('user_id', $id)->first();
            if ($biodata) {
                $biodata->update([
                    'nim' => $request->nim,
                    'tahun_masuk' => $request->tahun_masuk,
                    'fakultas' => $request->fakultas,
                    'program_studi' => $request->program_studi,
                ]);
            } else {
                Biodata::create([
                    'user_id' => $id,
                    'nim' => $request->nim,
                    'tahun_masuk' => $request->tahun_masuk,
                    'fakultas' => $request->fakultas,
                    'program_studi' => $request->program_studi,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.data-wisudawan.index')->with('success', 'Data wisudawan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->where('role', 'mahasiswa')->firstOrFail();

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data wisudawan berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}

