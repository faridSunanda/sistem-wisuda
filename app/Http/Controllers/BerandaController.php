<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AlurPendaftaran;
use App\Models\DokumenPersyaratan;
use App\Models\Biodata;
use App\Models\PelaksanaanWisuda;
use App\Models\Wisuda;
use Carbon\Carbon;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda portal
     */
    public function index()
    {
        // Data alur pendaftaran dan dokumen persyaratan
        $alurPendaftaran = AlurPendaftaran::orderBy('no_urut', 'asc')->get();
        $dokumenPersyaratan = DokumenPersyaratan::latest()->get();

        // Ambil data wisuda aktif
        $wisuda = $this->getWisudaAktif();

        // Inisialisasi variabel default
        $statusInfo = ['text' => 'Belum Dibuka', 'color' => 'bg-gray-500'];
        $totalPendaftar = 0;
        $totalKuota = 0;
        $persentase = 0;
        $countdownTarget = null;
        $sisaKuota = 0;

        if ($wisuda) {
            $statusInfo = $this->getWisudaStatusInfo($wisuda);
            $totalKuota = $wisuda->kuota_wisudawan ?? 0;
            $countdownTarget = Carbon::parse($wisuda->tanggal_penutupan);
            $totalPendaftar = $this->hitungTotalPendaftar($wisuda->id);

            if ($totalKuota > 0) {
                $persentase = min(100, round(($totalPendaftar / $totalKuota) * 100, 1));
                $sisaKuota = max(0, $totalKuota - $totalPendaftar);
            }
        }

        // Jadwal pelaksanaan wisuda
        $jadwalPelaksanaan = PelaksanaanWisuda::with(['sesi'])
            ->where('waktu_pelaksanaan', '>=', now())
            ->orderBy('waktu_pelaksanaan', 'asc')
            ->get();

        return view('portal.index', [
            'alurPendaftaran' => $alurPendaftaran,
            'dokumenPersyaratan' => $dokumenPersyaratan,
            'statusInfo' => $statusInfo,
            'totalPendaftar' => $totalPendaftar,
            'totalKuota' => $totalKuota,
            'persentase' => $persentase,
            'wisuda' => $wisuda,
            'jadwalPelaksanaan' => $jadwalPelaksanaan,
            'countdownTarget' => $countdownTarget,
            'sisaKuota' => $sisaKuota
        ]);
    }

    //Mengambil wisuda aktif dengan prioritas status dibuka
    private function getWisudaAktif()
    {
        $wisuda = Wisuda::whereRaw('LOWER(status) = ?', ['dibuka'])
            ->orderBy('tanggal_pendaftaran', 'desc')
            ->first();

        if (!$wisuda) {
            $wisuda = Wisuda::orderBy('tanggal_pendaftaran', 'desc')->first();
        }

        return $wisuda;
    }

    // Menghitung total pendaftar yang sudah terverifikasi atau sudah bayar
    private function hitungTotalPendaftar($wisudaId)
    {
        return Biodata::whereNotNull('user_id')
            ->where('wisuda_id', $wisudaId)
            ->whereHas('user', function($query) {
                $query->where('role', 'mahasiswa');
            })
            ->where(function($query) {
                $query->where('is_verified_akademik', true)
                      ->orWhere('is_verified_keuangan', true)
                      ->orWhere('is_bayar', true);
            })
            ->count();
    }

    // Menentukan status dan warna badge berdasarkan kondisi wisuda
    private function getWisudaStatusInfo($wisuda)
    {
        $now = now();
        $buka = Carbon::parse($wisuda->tanggal_pendaftaran);
        $tutup = Carbon::parse($wisuda->tanggal_penutupan);
        $status = strtolower($wisuda->status);

        if ($now > $tutup) {
            return ['text' => 'Pendaftaran Ditutup', 'color' => 'bg-red-500'];
        }

        if ($now < $buka) {
            return ['text' => 'Segera Dibuka', 'color' => 'bg-yellow-500'];
        }

        if (in_array($status, ['dibuka', 'aktif']) && $now >= $buka && $now <= $tutup) {
            return ['text' => 'Pendaftaran Dibuka', 'color' => 'bg-green-500'];
        }

        if ($status == 'ditutup') {
            return ['text' => 'Pendaftaran Ditutup', 'color' => 'bg-red-500'];
        }

        if ($status == 'selesai') {
            return ['text' => 'Pendaftaran Selesai', 'color' => 'bg-gray-500'];
        }

        return ['text' => 'Belum Dibuka', 'color' => 'bg-gray-500'];
    }
}
