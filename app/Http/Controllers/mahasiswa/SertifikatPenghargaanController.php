<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SertifikatPenghargaan;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SertifikatPenghargaanController extends Controller
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
                'nim' => '20210001',
                // ... field lainnya
            ]);
        }

        $sertifikatPenghargaans = $biodata->sertifikatPenghargaans ?? collect();
        
        // PERBAIKAN: Update path view sesuai struktur folder
        return view('mahasiswa.sertifikat.sertifikat-penghargaan.index', compact('sertifikatPenghargaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sertifikat_penghargaan' => 'required|array|min:1|max:5',
            'sertifikat_penghargaan.*.nama_sertifikat' => 'required|string|max:255',
            'sertifikat_penghargaan.*.penerbit' => 'required|string|max:255',
            'sertifikat_penghargaan.*.tanggal_terbit' => 'required|date',
        ]);

        $user = auth()->user();
        $biodata = $user->biodata;

        if (!$biodata) {
            $biodata = Biodata::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'nim' => '20210001',
            ]);
        }

        // Delete existing
        SertifikatPenghargaan::where('biodata_id', $biodata->id)->delete();

        // Create new
        foreach ($request->sertifikat_penghargaan as $sertifikat) {
            if (!empty($sertifikat['nama_sertifikat'])) {
                SertifikatPenghargaan::create([
                    'id' => Str::uuid(),
                    'biodata_id' => $biodata->id,
                    'nama_sertifikat' => $sertifikat['nama_sertifikat'],
                    'penerbit' => $sertifikat['penerbit'],
                    'tanggal_terbit' => $sertifikat['tanggal_terbit'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data sertifikat penghargaan berhasil disimpan.');
    }
}