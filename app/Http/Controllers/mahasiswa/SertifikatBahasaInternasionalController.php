<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SertifikatBahasaInternasionalController extends Controller
{
    public function index()
    {
        $biodata = Auth::user()->biodata;
        $sertifikats = collect(); 

        if ($biodata) {
            $biodata->load('sertifikatBahasaInternasional');
            $sertifikats = $biodata->sertifikatBahasaInternasional;
        }

        return view('mahasiswa.sertifikat.sertifikat-bahasa-internasional.index', compact('sertifikats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sertifikat'   => 'required|array|min:1|max:5',
            'nama_sertifikat.*' => 'required|string|max:255',
            'penerbit'          => 'required|array|min:1|max:5',
            'penerbit.*'        => 'required|string|max:255',
            'tanggal_terbit'    => 'required|array|min:1|max:5',
            'tanggal_terbit.*'  => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $biodata = Auth::user()->biodata;
        if (!$biodata) {
            return back()->with('error', 'Profil biodata tidak ditemukan.');
        }

        $biodata->sertifikatBahasaInternasional()->forceDelete();

        foreach ($request->nama_sertifikat as $index => $nama) {
            if (!empty($nama)) {
                $biodata->sertifikatBahasaInternasional()->create([
                    'nama_sertifikat' => $nama,
                    'penerbit'        => $request->penerbit[$index],
                    'tanggal_terbit'  => $request->tanggal_terbit[$index],
                ]);
            }
        }

        return back()->with('success', 'Data sertifikat bahasa berhasil diperbarui!');
    }
}