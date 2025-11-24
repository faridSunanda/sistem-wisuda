<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sesi;

class SesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSesi = [
            [
                'name' => 'Pertama',
                'keterangan' => 'Sesi untuk wisudawan pagi',
            ],
            [
                'name' => 'Kedua',
                'keterangan' => 'Sesi untuk wisudawan siang',
            ],
        ];

        foreach ($dataSesi as $sesi) {
            Sesi::firstOrCreate(
                ['name' => $sesi['name']],
                ['keterangan' => $sesi['keterangan']]
            );
        }
    }
}
