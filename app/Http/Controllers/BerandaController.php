<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlurPendaftaran;
use App\Models\DokumenPersyaratan;
use App\Models\JadwalPendaftaran;
use App\Models\KuotaWisudawan;
use App\Models\Biodata;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        $alurPendaftaran = AlurPendaftaran::orderBy('no_urut', 'asc')->get();

        $dokumenPersyaratan = DokumenPersyaratan::latest()->get();

        $now = now();
        // Ambil jadwal dengan eager load kuota untuk performa lebih baik
        // Gunakan fresh() untuk memastikan data terbaru dari database
        $jadwal = JadwalPendaftaran::with('kuotaWisudawan')
                                    ->where('waktu_tutup_pendaftaran', '>', $now)
                                    ->orderBy('waktu_buka_pendaftaran', 'asc')
                                    ->first();

        if (!$jadwal) {
            $jadwal = JadwalPendaftaran::with('kuotaWisudawan')
                                        ->orderBy('waktu_tutup_pendaftaran', 'desc')
                                        ->first();
        }
        
        // Refresh relasi kuota jika sudah ada untuk memastikan data terbaru
        if ($jadwal && $jadwal->relationLoaded('kuotaWisudawan') && $jadwal->kuotaWisudawan) {
            $jadwal->kuotaWisudawan->refresh();
        }

        $statusInfo = ['text' => 'Belum Dibuka', 'color' => 'bg-gray-500'];
        $totalPendaftar = 0;
        $totalKuota = 0;
        $persentase = 0;

        if ($jadwal) {
            $statusInfo = $this->getJadwalStatusInfo($jadwal);
            
            // Ambil kuota untuk jadwal aktif menggunakan relasi yang sudah di-eager load
            // Jika belum di-load, ambil langsung dari database
            if ($jadwal->relationLoaded('kuotaWisudawan')) {
                $kuota = $jadwal->kuotaWisudawan;
            } else {
                // Reload relasi untuk memastikan data terbaru
                $jadwal->load('kuotaWisudawan');
                $kuota = $jadwal->kuotaWisudawan;
            }
            
            if ($kuota) {
                $totalKuota = $kuota->jumlah_kuota;
            }

            // Hitung total pendaftar yang benar-benar terdaftar untuk wisuda ini
            // Pendaftar yang sudah verified atau sudah bayar dianggap sudah terdaftar
            $totalPendaftar = Biodata::whereNotNull('user_id')
                                    ->whereHas('user', function($query) {
                                        $query->where('role', 'mahasiswa');
                                    })
                                    ->where(function($query) {
                                        $query->where('is_verified', true)
                                              ->orWhere('is_bayar', true);
                                    })
                                    ->count();

            // Hitung persentase
            if ($totalKuota > 0) {
                $persentase = min(100, round(($totalPendaftar / $totalKuota) * 100, 1));
            }
        }

        return view('portal.index', [
            'alurPendaftaran' => $alurPendaftaran,
            'dokumenPersyaratan' => $dokumenPersyaratan,
            'statusInfo' => $statusInfo,
            'totalPendaftar' => $totalPendaftar,
            'totalKuota' => $totalKuota,
            'persentase' => $persentase,
            'jadwal' => $jadwal
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

        if ($jadwal->status == 'Dibuka') {
             return ['text' => 'Pendaftaran Dibuka', 'color' => 'bg-green-500'];
        }

        if ($jadwal->status == 'Ditutup') {
             return ['text' => 'Pendaftaran Ditutup', 'color' => 'bg-red-500'];
        }

        return ['text' => 'Belum Dibuka', 'color' => 'bg-gray-500'];
    }
}
