<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalPendaftaran;
use App\Models\KuotaWisudawan;
use App\Models\PelaksanaanWisuda;
use App\Models\Sesi;
use Carbon\Carbon;

class WisudaSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Sesi sudah ada
        $this->command->info('Memastikan data Sesi tersedia...');
        if (Sesi::count() === 0) {
            $this->call(SesiSeeder::class);
        }

        $sesis = Sesi::all();
        if ($sesis->isEmpty()) {
            $this->command->error('Data Sesi tidak ditemukan. Silakan jalankan SesiSeeder terlebih dahulu.');
            return;
        }

        // Data Jadwal Pendaftaran Wisuda
        $jadwalData = [
            [
                'tahun_wisuda' => 2024,
                'status' => 'Aktif',
                'waktu_buka_pendaftaran' => Carbon::now()->subDays(30),
                'waktu_tutup_pendaftaran' => Carbon::now()->addDays(60),
            ],
            [
                'tahun_wisuda' => 2025,
                'status' => 'Buka',
                'waktu_buka_pendaftaran' => Carbon::now()->subDays(10),
                'waktu_tutup_pendaftaran' => Carbon::now()->addDays(50),
            ],
            [
                'tahun_wisuda' => 2023,
                'status' => 'Tutup',
                'waktu_buka_pendaftaran' => Carbon::now()->subDays(365),
                'waktu_tutup_pendaftaran' => Carbon::now()->subDays(300),
            ],
        ];

        $this->command->info('Membuat data Jadwal Pendaftaran Wisuda...');
        $jadwalPendaftarans = [];

        foreach ($jadwalData as $data) {
            $jadwal = JadwalPendaftaran::firstOrCreate(
                ['tahun_wisuda' => $data['tahun_wisuda']],
                $data
            );
            $jadwalPendaftarans[] = $jadwal;
            $this->command->info("  ✓ Jadwal Pendaftaran {$data['tahun_wisuda']} - Status: {$data['status']}");
        }

        // Data Kuota Wisudawan
        $this->command->info('Membuat data Kuota Wisudawan...');
        $kuotaData = [
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[0]->id,
                'jumlah_kuota' => 500,
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[1]->id,
                'jumlah_kuota' => 750,
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[2]->id,
                'jumlah_kuota' => 300,
            ],
        ];

        foreach ($kuotaData as $index => $data) {
            $kuota = KuotaWisudawan::firstOrCreate(
                ['pendaftaran_wisuda_id' => $data['pendaftaran_wisuda_id']],
                $data
            );
            $this->command->info("  ✓ Kuota untuk tahun {$jadwalPendaftarans[$index]->tahun_wisuda}: {$data['jumlah_kuota']} wisudawan");
        }

        // Data Pelaksanaan Wisuda
        $this->command->info('Membuat data Pelaksanaan Wisuda...');
        $pelaksanaanData = [
            // Untuk tahun 2024
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[0]->id,
                'nama_kegiatan' => 'Upacara Wisuda',
                'sesi_id' => $sesis[0]->id,
                'waktu_pelaksanaan' => Carbon::now()->addDays(45)->setTime(8, 0),
                'tempat_pelaksanaan' => 'Auditorium Universitas',
                'keterangan' => 'Upacara wisuda untuk wisudawan tahun 2024 sesi pertama',
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[0]->id,
                'nama_kegiatan' => 'Upacara Wisuda',
                'sesi_id' => $sesis->count() > 1 ? $sesis[1]->id : $sesis[0]->id,
                'waktu_pelaksanaan' => Carbon::now()->addDays(45)->setTime(13, 0),
                'tempat_pelaksanaan' => 'Auditorium Universitas',
                'keterangan' => 'Upacara wisuda untuk wisudawan tahun 2024 sesi kedua',
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[0]->id,
                'nama_kegiatan' => 'Rehearsal Wisuda',
                'sesi_id' => $sesis[0]->id,
                'waktu_pelaksanaan' => Carbon::now()->addDays(44)->setTime(14, 0),
                'tempat_pelaksanaan' => 'Auditorium Universitas',
                'keterangan' => 'Gladi resik untuk wisudawan tahun 2024',
            ],
            // Untuk tahun 2025
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[1]->id,
                'nama_kegiatan' => 'Upacara Wisuda',
                'sesi_id' => $sesis[0]->id,
                'waktu_pelaksanaan' => Carbon::now()->addDays(55)->setTime(8, 0),
                'tempat_pelaksanaan' => 'Auditorium Universitas',
                'keterangan' => 'Upacara wisuda untuk wisudawan tahun 2025 sesi pertama',
            ],
            [
                'pendaftaran_wisuda_id' => $jadwalPendaftarans[1]->id,
                'nama_kegiatan' => 'Upacara Wisuda',
                'sesi_id' => $sesis->count() > 1 ? $sesis[1]->id : $sesis[0]->id,
                'waktu_pelaksanaan' => Carbon::now()->addDays(55)->setTime(13, 0),
                'tempat_pelaksanaan' => 'Auditorium Universitas',
                'keterangan' => 'Upacara wisuda untuk wisudawan tahun 2025 sesi kedua',
            ],
        ];

        foreach ($pelaksanaanData as $data) {
            PelaksanaanWisuda::updateOrCreate(
                [
                    'pendaftaran_wisuda_id' => $data['pendaftaran_wisuda_id'],
                    'nama_kegiatan' => $data['nama_kegiatan'],
                    'sesi_id' => $data['sesi_id'],
                    'waktu_pelaksanaan' => $data['waktu_pelaksanaan'],
                ],
                [
                    'tempat_pelaksanaan' => $data['tempat_pelaksanaan'],
                    'keterangan' => $data['keterangan'],
                ]
            );
            $this->command->info("  ✓ {$data['nama_kegiatan']} - " . Carbon::parse($data['waktu_pelaksanaan'])->format('d M Y H:i'));
        }

        $this->command->info('');
        $this->command->info('✓ Seeder Wisuda berhasil dijalankan!');
        $this->command->info('  - ' . count($jadwalPendaftarans) . ' Jadwal Pendaftaran');
        $this->command->info('  - ' . count($kuotaData) . ' Kuota Wisudawan');
        $this->command->info('  - ' . count($pelaksanaanData) . ' Pelaksanaan Wisuda');
    }
}

