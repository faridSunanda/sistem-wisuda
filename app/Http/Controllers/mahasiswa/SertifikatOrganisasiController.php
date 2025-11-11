<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SertifikatOrganisasiController extends Controller
{
    public function index()
    {
        $biodata = Auth::user()->biodata;
        $sertifikats = collect(); 

        if ($biodata) {
            $biodata->load('sertifikatOrganisasi');
            $sertifikats = $biodata->sertifikatOrganisasi;
        }

        return view('mahasiswa.sertifikat.sertifikat-organisasi.index', compact('sertifikats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sertifikat'   => 'required|array|min:1|max:5',
            'nama_sertifikat.*' => 'required|string|max:255',
            'tanggal_mulai'     => 'required|array|min:1|max:5',
            'tanggal_mulai.*'   => 'required|date',
            'tanggal_selesai'   => 'required|array|min:1|max:5',
            'tanggal_selesai.*' => 'required|date|after_or_equal:tanggal_mulai.*',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $biodata = Auth::user()->biodata;
        if (!$biodata) {
            return back()->with('error', 'Profil biodata tidak ditemukan.');
        }

        $biodata->sertifikatOrganisasi()->forceDelete();

        foreach ($request->nama_sertifikat as $index => $nama) {
            if (!empty($nama)) {
                $biodata->sertifikatOrganisasi()->create([
                    'nama_sertifikat' => $nama,
                    'tanggal_mulai'   => $request->tanggal_mulai[$index],
                    'tanggal_selesai' => $request->tanggal_selesai[$index],
                ]);
            }
        }

        return back()->with('success', 'Data sertifikat organisasi berhasil diperbarui!');
    }
}