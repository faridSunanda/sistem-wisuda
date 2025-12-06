<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DownloadFormulirController extends Controller
{
    public function index()
    {
        // Ambil biodata mahasiswa yang sedang login
        $biodata = Biodata::with([
            'sertifikatKompetensi',
            'sertifikatBahasaInternasional',
            'sertifikatMagang',
            'sertifikatPendidikanKarakter',
            'sertifikatPenghargaan',
            'sertifikatOrganisasi',
            'user',
            'wisuda',
            'dosenPembimbings'
        ])->where('user_id', auth()->id())->first();

        if (!$biodata) {
            return redirect()->route('mahasiswa.biodata.index')
                ->with('error', 'Data biodata tidak ditemukan. Silakan lengkapi biodata terlebih dahulu.');
        }

        return view('mahasiswa.download-formulir.index', compact('biodata'));
    }

    public function downloadFormulirWisuda()
    {
        $biodata = Biodata::with([
            'sertifikatKompetensi',
            'sertifikatBahasaInternasional',
            'sertifikatMagang',
            'sertifikatPendidikanKarakter',
            'sertifikatPenghargaan',
            'sertifikatOrganisasi',
            'user',
            'wisuda',
            'dosenPembimbings'
        ])->where('user_id', auth()->id())->firstOrFail();

        // Validasi kelengkapan data
        if (!$this->validateDataCompleteness($biodata)) {
            return redirect()->route('mahasiswa.download-formulir.index')
                ->with('error', 'Data belum lengkap. Silakan lengkapi semua data terlebih dahulu.');
        }

        $pdf = Pdf::loadView('pdf.formulir-wisuda', compact('biodata'));
        
        $filename = "Formulir_Wisuda_{$biodata->nim}_{$biodata->user->name}.pdf";
        
        return $pdf->download($filename);
    }

    private function validateDataCompleteness($biodata)
    {
        $requiredFields = [
            'nik', 'nim', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'status_mahasiswa', 'tahun_masuk', 'fakultas',
            'program_studi', 'alamat_rumah', 'no_telepon', 'judul_skripsi'
        ];

        foreach ($requiredFields as $field) {
            if (empty($biodata->$field)) {
                return false;
            }
        }

        return true;
    }
}