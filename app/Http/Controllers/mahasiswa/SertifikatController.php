<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->route()->parameter('jenis');
        $biodata = Auth::user()->biodata;
        $sertifikats = collect();

        if ($biodata) {
            // Load sertifikats filtered by jenis
            // We can use the relationship method if we want, but since we have the string 'jenis',
            // we can just query the relationship or the model directly.
            // Using the relationship from Biodata model is cleaner if we had dynamic relationship names,
            // but here we have a generic 'jenis'.
            // Let's use the generic hasMany(Sertifikat::class) we defined? 
            // Actually in Biodata we defined specific methods.
            // We can just query:
            $sertifikats = $biodata->hasMany(Sertifikat::class)->where('jenis', $jenis)->get();
        }

        // Determine view based on route name or URL?
        // The view folder structure is: mahasiswa.sertifikat.sertifikat-{url_param}.index
        // We need to map 'jenis' (db) back to 'url_param' for the view, OR just use the route name.
        // Route name is 'mahasiswa.sertifikat.{url_param}'
        
        $routeName = $request->route()->getName(); // e.g., mahasiswa.sertifikat.kompetensi
        $urlParam = str_replace('mahasiswa.sertifikat.', '', $routeName);
        
        // Special case mapping if needed, but if we name routes same as folders:
        // kompetensi -> sertifikat-kompetensi
        // bahasa-internasional -> sertifikat-bahasa-internasional
        // magang -> sertifikat-magang
        // pendidikan-karakter -> sertifikat-pendidikan-karakter
        // penghargaan -> sertifikat-penghargaan
        // organisasi -> sertifikat-organisasi
        
        // So view name is "mahasiswa.sertifikat.sertifikat-{$urlParam}.index"
        
        return view("mahasiswa.sertifikat.sertifikat-{$urlParam}.index", compact('sertifikats'));
    }

    public function store(Request $request)
    {
        $jenis = $request->route()->parameter('jenis');
        
        // Validation Rules
        $rules = [
            'nama_sertifikat'   => 'required|array|min:1|max:5',
            'nama_sertifikat.*' => 'required|string|max:255',
        ];

        if ($jenis === 'organisasi') {
            $rules['tanggal_mulai']     = 'required|array|min:1|max:5';
            $rules['tanggal_mulai.*']   = 'required|date';
            $rules['tanggal_selesai']   = 'required|array|min:1|max:5';
            $rules['tanggal_selesai.*'] = 'required|date|after_or_equal:tanggal_mulai.*';
            // Penerbit is optional/defaulted in model, but form might have it? 
            // Organisasi form usually doesn't have penerbit based on previous code, but let's check.
            // Previous controller didn't validate penerbit for organisasi.
        } else {
            $rules['penerbit']          = 'required|array|min:1|max:5';
            $rules['penerbit.*']        = 'required|string|max:255';
            $rules['tanggal_terbit']    = 'required|array|min:1|max:5';
            $rules['tanggal_terbit.*']  = 'required|date';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $biodata = Auth::user()->biodata;
        if (!$biodata) {
            return back()->with('error', 'Profil biodata tidak ditemukan.');
        }

        // Delete existing for this jenis
        $biodata->hasMany(Sertifikat::class)->where('jenis', $jenis)->forceDelete();

        foreach ($request->nama_sertifikat as $index => $nama) {
            if (!empty($nama)) {
                $data = [
                    'jenis'           => $jenis,
                    'nama_sertifikat' => $nama,
                    'berkas'          => '-',
                ];

                if ($jenis === 'organisasi') {
                    $data['tanggal_mulai']   = $request->tanggal_mulai[$index];
                    $data['tanggal_selesai'] = $request->tanggal_selesai[$index];
                    $data['penerbit']        = '-'; 
                } else {
                    $data['penerbit']       = $request->penerbit[$index];
                    $data['tanggal_terbit'] = $request->tanggal_terbit[$index];
                }

                $biodata->hasMany(Sertifikat::class)->create($data);
            }
        }

        return back()->with('success', 'Data sertifikat berhasil diperbarui!');
    }
}
