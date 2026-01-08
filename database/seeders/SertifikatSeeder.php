<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class SertifikatSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua biodata yang ada
        $biodatas = DB::table('biodatas')->whereNull('deleted_at')->get();

        if ($biodatas->isEmpty()) {
            $this->command->warn('Tidak ada biodata yang ditemukan. Jalankan BiodataSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat data sertifikat...');

        // Data sertifikat berdasarkan jenis
        $sertifikatData = [
            'kompetensi' => [
                'nama' => [
                    'Sertifikat Kompetensi Programming',
                    'Sertifikat Kompetensi Database Management',
                    'Sertifikat Kompetensi Web Development',
                    'Sertifikat Kompetensi Mobile Development',
                    'Sertifikat Kompetensi Network Security',
                    'Sertifikat Kompetensi Cloud Computing',
                    'Sertifikat Kompetensi Data Science',
                    'Sertifikat Kompetensi UI/UX Design'
                ],
                'penerbit' => [
                    'Google',
                    'Microsoft',
                    'Oracle',
                    'AWS',
                    'Cisco',
                    'IBM',
                    'Meta',
                    'Adobe'
                ]
            ],
            'bahasa' => [
                'nama' => [
                    'TOEFL ITP',
                    'IELTS',
                    'TOEIC',
                    'Cambridge English',
                    'Japanese Language Proficiency Test (JLPT)',
                    'Test of Chinese as Foreign Language (TOCFL)',
                    'DELE (Spanish)',
                    'DELF (French)'
                ],
                'penerbit' => [
                    'ETS',
                    'British Council',
                    'Cambridge Assessment',
                    'Japan Foundation',
                    'Confucius Institute',
                    'Instituto Cervantes',
                    'Alliance Française'
                ]
            ],
            'magang' => [
                'nama' => [
                    'Sertifikat Magang Software Development',
                    'Sertifikat Magang Data Analyst',
                    'Sertifikat Magang Digital Marketing',
                    'Sertifikat Magang Human Resources',
                    'Sertifikat Magang Finance',
                    'Sertifikat Magang Operations',
                    'Sertifikat Magang Research & Development'
                ],
                'penerbit' => [
                    'PT. Telkom Indonesia',
                    'PT. Bank Mandiri',
                    'PT. Astra International',
                    'PT. Unilever Indonesia',
                    'PT. Indofood Sukses Makmur',
                    'PT. Kalbe Farma',
                    'PT. Gudang Garam'
                ]
            ],
            'karakter' => [
                'nama' => [
                    'Sertifikat Pendidikan Karakter Kepemimpinan',
                    'Sertifikat Pendidikan Karakter Integritas',
                    'Sertifikat Pendidikan Karakter Kerjasama',
                    'Sertifikat Pendidikan Karakter Disiplin',
                    'Sertifikat Pendidikan Karakter Kreativitas',
                    'Sertifikat Pendidikan Karakter Tanggung Jawab'
                ],
                'penerbit' => [
                    'Kementerian Pendidikan dan Kebudayaan',
                    'Universitas Wahid Hasyim',
                    'Lembaga Pengembangan Karakter',
                    'Pusat Pendidikan Karakter Nasional'
                ]
            ],
            'organisasi' => [
                'nama' => [
                    'Sertifikat Organisasi BEM',
                    'Sertifikat Organisasi Himpunan Mahasiswa',
                    'Sertifikat Organisasi UKM',
                    'Sertifikat Organisasi Pramuka',
                    'Sertifikat Organisasi PMR',
                    'Sertifikat Organisasi Paskibra',
                    'Sertifikat Organisasi Seni Budaya'
                ],
                'penerbit' => [
                    'Badan Eksekutif Mahasiswa',
                    'Himpunan Mahasiswa',
                    'Unit Kegiatan Mahasiswa',
                    'Gerakan Pramuka',
                    'Palang Merah Remaja',
                    'Paskibra Indonesia'
                ]
            ]
        ];

        $totalCreated = 0;

        foreach ($biodatas as $biodata) {
            // Setiap biodata akan memiliki 5 sertifikat dengan jenis yang berbeda
            $jenisTerpilih = $faker->randomElements(
                array_keys($sertifikatData),
                5
            );

            foreach ($jenisTerpilih as $jenis) {
                $namaSertifikat = $faker->randomElement($sertifikatData[$jenis]['nama']);
                $penerbit = $faker->randomElement($sertifikatData[$jenis]['penerbit']);

                // Generate tanggal
                $tanggalMulai = $faker->dateTimeBetween('-2 years', '-6 months');
                $tanggalSelesai = $faker->dateTimeBetween($tanggalMulai, '-3 months');
                $tanggalTerbit = $faker->dateTimeBetween($tanggalSelesai, 'now');

                // Generate nama file berkas
                $berkas = 'sertifikat/' . Str::slug($namaSertifikat) . '_' . Str::random(10) . '.pdf';

                DB::table('sertifikats')->insert([
                    'id' => Str::uuid(),
                    'biodata_id' => $biodata->id,
                    'jenis' => $jenis,
                    'nama_sertifikat' => $namaSertifikat,
                    'penerbit' => $penerbit,
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                    'tanggal_terbit' => $tanggalTerbit->format('Y-m-d'),
                    'berkas' => $berkas,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $totalCreated++;
            }
        }

        $this->command->info("Berhasil membuat {$totalCreated} sertifikat untuk " . count($biodatas) . " biodata.");
    }
}

