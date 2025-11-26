<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wisuda;
use App\Models\PelaksanaanWisuda;
use App\Models\Sesi;
use Carbon\Carbon;

class WisudaSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (Sesi::count() === 0) {
            $this->call(SesiSeeder::class);
        }

        $sesis = Sesi::all();
        if ($sesis->isEmpty()) {
            $this->command->error('Data Sesi tidak ditemukan.');
            return;
        }

        $sesiPagi = $sesis->first();
        $sesiSiang = $sesis->count() > 1 ? $sesis->skip(1)->first() : $sesis->first();

        $this->command->info('Membuat data Wisuda...');

        $dataWisuda = [
            [
                'angkatan'            => 'Wisuda Angkatan 101 (Periode Sekarang)',
                'tanggal_pendaftaran' => Carbon::now()->subDays(10),
                'tanggal_penutupan'   => Carbon::now()->addDays(20),
                'kuota_wisudawan'     => 500,
                'status'              => 'dibuka',
            ],

            [
                'angkatan'            => 'Wisuda Angkatan 102 (Tahun Depan)',
                'tanggal_pendaftaran' => Carbon::now()->addMonths(5),
                'tanggal_penutupan'   => Carbon::now()->addMonths(6),
                'kuota_wisudawan'     => 700,
                'status'              => 'ditutup',
            ],

            [
                'angkatan'            => 'Wisuda Angkatan 100 (Tahun Lalu)',
                'tanggal_pendaftaran' => Carbon::now()->subYear()->subMonth(),
                'tanggal_penutupan'   => Carbon::now()->subYear(),
                'kuota_wisudawan'     => 450,
                'status'              => 'ditutup',
            ],
        ];

        foreach ($dataWisuda as $item) {
            $wisuda = Wisuda::create($item);

            $this->command->info("  ✓ Data {$wisuda->angkatan} ({$wisuda->status}) berhasil dibuat.");

            $tglPelaksanaan = Carbon::parse($item['tanggal_penutupan'])->addMonth();

            PelaksanaanWisuda::create([
                'wisuda_id'          => $wisuda->id,
                'sesi_id'            => $sesiPagi->id,
                'nama_kegiatan'      => 'Upacara Wisuda Sesi Pagi',
                'waktu_pelaksanaan'  => $tglPelaksanaan->copy()->setTime(8, 0),
                'tempat_pelaksanaan' => 'Auditorium Utama',
                'keterangan'         => 'Wajib hadir pukul 07.00 WIB',
            ]);

            if ($item['status'] !== 'Selesai') {
                PelaksanaanWisuda::create([
                    'wisuda_id'          => $wisuda->id,
                    'sesi_id'            => $sesiSiang->id,
                    'nama_kegiatan'      => 'Upacara Wisuda Sesi Siang',
                    'waktu_pelaksanaan'  => $tglPelaksanaan->copy()->setTime(13, 0),
                    'tempat_pelaksanaan' => 'Auditorium Utama',
                    'keterangan'         => 'Wajib hadir pukul 12.00 WIB',
                ]);
            }

            PelaksanaanWisuda::create([
                'wisuda_id'          => $wisuda->id,
                'sesi_id'            => $sesiPagi->id,
                'nama_kegiatan'      => 'Gladi Resik',
                'waktu_pelaksanaan'  => $tglPelaksanaan->copy()->subDay()->setTime(14, 0),
                'tempat_pelaksanaan' => 'Auditorium Utama',
                'keterangan'         => 'Pengambilan toga dan undangan',
            ]);
        }

        $this->command->info('✓ Seeder Wisuda selesai.');
    }
}
