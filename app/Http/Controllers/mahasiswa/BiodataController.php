<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Validator;

class BiodataController extends Controller
{
    public function edit()
    {
        $biodata = Auth::user()->biodata;
        return view('mahasiswa.data-diri.index', compact('biodata'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nik'               => 'nullable|string|digits:16|unique:biodatas,nik,' . ($user->biodata->id ?? 'NULL') . ',id',
            'nim'               => 'nullable|string|max:20|unique:biodatas,nim,' . ($user->biodata->id ?? 'NULL') . ',id',
            'nirm'              => 'nullable|string|max:20',
            'nirl'              => 'nullable|string|max:20',
            'foto_profile'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maks 2MB
            'tempat_lahir'      => 'nullable|string|max:100',
            'tanggal_lahir'     => 'nullable|date',
            'jenis_kelamin'     => 'nullable|string',
            'status_mahasiswa'  => 'nullable|string',
            'tahun_masuk'       => 'nullable|digits:4',
            'fakultas'          => 'nullable|string',
            'program_studi'     => 'nullable|string',
            'alamat_rumah'      => 'nullable|string',
            'no_telepon'        => 'nullable|string|max:15',
            'judul_skripsi'     => 'nullable|string',
            'kesan_pesan'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('foto_profile')) {
            
            if ($user->biodata && $user->biodata->foto_profile) {
                Storage::disk('public')->delete($user->biodata->foto_profile);
            }

            $path = $request->file('foto_profile')->store('foto_profil', 'public');
            $data['foto_profile'] = $path; // Simpan path ke array data
        }

        $user->biodata()->updateOrCreate(
            ['user_id' => $user->id], 
            $data                   
        );

        return back()->with('success', 'Biodata berhasil diperbarui!');
    }
}