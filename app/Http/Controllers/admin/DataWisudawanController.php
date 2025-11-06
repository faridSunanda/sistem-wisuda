<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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
                $btn = '<button class="btn-lihat" onclick="lihatData(\''.$row->id.'\')">Lihat</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}

