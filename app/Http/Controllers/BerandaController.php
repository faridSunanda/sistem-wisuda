<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlurPendaftaran;
use App\Models\DokumenPersyaratan;
use App\Models\JadwalPendaftaran;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        $alurPendaftaran = AlurPendaftaran::orderBy('no_urut', 'asc')->get();

        $dokumenPersyaratan = DokumenPersyaratan::latest()->get();

        $now = now();
        $jadwal = JadwalPendaftaran::where('waktu_tutup_pendaftaran', '>', $now)
                                    ->orderBy('waktu_buka_pendaftaran', 'asc')
                                    ->first();

        if (!$jadwal) {
            $jadwal = JadwalPendaftaran::orderBy('waktu_tutup_pendaftaran', 'desc')->first();
        }

        $statusInfo = ['text' => 'Belum Dibuka', 'color' => 'bg-gray-500'];

        if ($jadwal) {
            $statusInfo = $this->getJadwalStatusInfo($jadwal);
        }

        return view('portal.index', [
            'alurPendaftaran' => $alurPendaftaran,
            'dokumenPersyaratan' => $dokumenPersyaratan,
            'statusInfo' => $statusInfo
        ]);
    }

    private function getJadwalStatusInfo($jadwal)
    {
        $now = now();
        $buka = Carbon::parse($jadwal->waktu_buka_pendaftaran);
        $tutup = Carbon::parse($jadwal->waktu_tutup_pendaftaran);

        if ($now > $tutup) {
            return ['text' => 'Pendaftaran Ditutup', 'color' => 'bg-red-500'];
        }

        if ($now < $buka) {
            return ['text' => 'Segera Dibuka', 'color' => 'bg-yellow-500'];
        }

        if ($jadwal->status == 'Buka' || $jadwal->status == 'Draft') {
             return ['text' => 'Pendaftaran Dibuka', 'color' => 'bg-green-500'];
        }

        if ($jadwal->status == 'Tutup' || $jadwal->status == 'Aktif') {
             return ['text' => 'Pendaftaran Ditutup', 'color' => 'bg-red-500'];
        }

        return ['text' => 'Belum Dibuka', 'color' => 'bg-gray-500'];
    }
}
