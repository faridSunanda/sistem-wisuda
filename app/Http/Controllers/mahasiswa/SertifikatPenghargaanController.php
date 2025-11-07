<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SertifikatPenghargaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SertifikatPenghargaanController extends Controller
{
    public function index()
    {
        $biodata = auth()->user()->biodata;
        $sertifikatPenghargaans = $biodata->sertifikatPenghargaans ?? collect();
        
        return view('mahasiswa.sertifikat-penghargaan', compact('sertifikatPenghargaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sertifikat_penghargaan' => 'required|array|min:1|max:5',
            'sertifikat_penghargaan.*.nama_sertifikat' => 'required|string|max:255',
            'sertifikat_penghargaan.*.penerbit' => 'required|string|max:255',
            'sertifikat_penghargaan.*.tanggal_terbit' => 'required|date',
        ]);

        $biodata = auth()->user()->biodata;

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