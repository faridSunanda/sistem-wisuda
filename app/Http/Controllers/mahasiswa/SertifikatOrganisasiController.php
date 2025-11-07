<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SertifikatOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SertifikatOrganisasiController extends Controller
{
    public function index()
    {
        $biodata = auth()->user()->biodata;
        $sertifikatOrganisasis = $biodata->sertifikatOrganisasis ?? collect();
        
        return view('mahasiswa.sertifikat-organisasi', compact('sertifikatOrganisasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sertifikat_organisasi' => 'required|array|min:1|max:5',
            'sertifikat_organisasi.*.nama_sertifikat' => 'required|string|max:255',
            'sertifikat_organisasi.*.tanggal_mulai' => 'required|date',
            'sertifikat_organisasi.*.tanggal_selesai' => 'required|date|after_or_equal:sertifikat_organisasi.*.tanggal_mulai',
        ]);

        $biodata = auth()->user()->biodata;

        // Delete existing
        SertifikatOrganisasi::where('biodata_id', $biodata->id)->delete();

        // Create new
        foreach ($request->sertifikat_organisasi as $sertifikat) {
            if (!empty($sertifikat['nama_sertifikat'])) {
                SertifikatOrganisasi::create([
                    'id' => Str::uuid(),
                    'biodata_id' => $biodata->id,
                    'nama_sertifikat' => $sertifikat['nama_sertifikat'],
                    'tanggal_mulai' => $sertifikat['tanggal_mulai'],
                    'tanggal_selesai' => $sertifikat['tanggal_selesai'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data sertifikat organisasi berhasil disimpan.');
    }
}