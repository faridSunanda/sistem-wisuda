<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SertifikatPendidikanKarakter;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SertifikatPendidikanKarakterController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Cek apakah user sudah punya biodata, jika belum buat dummy
        $biodata = $user->biodata;
        
        if (!$biodata) {
            $biodata = Biodata::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'nik' => '1234567890123456',
                'nim' => '20210001',
                'nirm' => 'NIRM001',
                'nirl' => 'NIRL001',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'status_mahasiswa' => 'Baru',
                'tahun_masuk' => '2021',
                'fakultas' => 'Fakultas Teknik',
                'program_studi' => 'Teknik Informatika',
                'alamat_rumah' => 'Jl. Test No. 123',
                'no_telepon' => '081234567890',
            ]);
        }

        $sertifikatPendidikanKarakter = $biodata->sertifikatPendidikanKarakter ?? collect();
        
        return view('mahasiswa.sertifikat.sertifikat-pendidikan-karakter.index', compact('sertifikatPendidikanKarakter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sertifikat_pendidikan_karakter' => 'required|array|min:1|max:5',
            'sertifikat_pendidikan_karakter.*.nama_sertifikat' => 'required|string|max:255',
            'sertifikat_pendidikan_karakter.*.penerbit' => 'required|string|max:255',
            'sertifikat_pendidikan_karakter.*.tanggal_terbit' => 'required|date',
        ]);

        $user = auth()->user();
        $biodata = $user->biodata;

        // Jika belum ada biodata, buat dulu
        if (!$biodata) {
            $biodata = Biodata::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'nim' => '20210001',
            ]);
        }

        // Delete existing
        SertifikatPendidikanKarakter::where('biodata_id', $biodata->id)->delete();

        // Create new
        foreach ($request->sertifikat_pendidikan_karakter as $sertifikat) {
            if (!empty($sertifikat['nama_sertifikat'])) {
                SertifikatPendidikanKarakter::create([
                    'id' => Str::uuid(),
                    'biodata_id' => $biodata->id,
                    'nama_sertifikat' => $sertifikat['nama_sertifikat'],
                    'penerbit' => $sertifikat['penerbit'],
                    'tanggal_terbit' => $sertifikat['tanggal_terbit'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data sertifikat pendidikan karakter berhasil disimpan.');
    }
}