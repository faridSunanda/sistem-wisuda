<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KuotaWisudawan;
use App\Models\JadwalPendaftaran;
use Illuminate\Support\Str;

class KuotaWisudawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $jadwalPendaftarans = JadwalPendaftaran::aktif()->get();

        if ($jadwalPendaftarans->isEmpty()) {
            $jadwalPendaftaran = JadwalPendaftaran::create([
                'tahun_wisuda' => '2024',
                'status' => 'Aktif',
                'waktu_buka_pendaftaran' => now()->subDays(10),
                'waktu_tutup_pendaftaran' => now()->addDays(20),
            ]);
            $jadwalPendaftarans = collect([$jadwalPendaftaran]);
        }

        $kuotaData = [
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[0]->id,
                'jumlah_kuota' => 500,
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans->count() > 1 ? $jadwalPendaftarans[1]->id : $jadwalPendaftarans[0]->id,
                'jumlah_kuota' => 300,
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans->count() > 2 ? $jadwalPendaftarans[2]->id : $jadwalPendaftarans[0]->id,
                'jumlah_kuota' => 750,
            ]
        ];

        foreach ($kuotaData as $data) {
            KuotaWisudawan::create($data);
        }

        $this->command->info('Sample data kuota wisuda berhasil ditambahkan!');
    }
}
