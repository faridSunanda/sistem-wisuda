<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlurPendaftaran;
use App\Models\DokumenPersyaratan;

class BerandaController extends Controller
{
    public function index()
    {
        // Dinamis portal index
        $alurPendaftaran = AlurPendaftaran::orderBy('no_urut', 'asc')->get();

        $dokumenPersyaratan = DokumenPersyaratan::where('tipe_dokumen', 'Dokumen Persyaratan')->first();
        $dokumenTandaTerima = DokumenPersyaratan::where('tipe_dokumen', 'Tanda Terima')->first();

        return view('portal.index', [
            'alurPendaftaran' => $alurPendaftaran,
            'dokumenPersyaratan' => $dokumenPersyaratan,
            'dokumenTandaTerima' => $dokumenTandaTerima,
        ]);
    }
}
