<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DataWisudawanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $wisudaId = DB::table('wisudas')->value('id');

        if (!$wisudaId) {
            $wisudaId = Str::uuid();
            DB::table('wisudas')->insert([
                'id' => $wisudaId,
                'angkatan' => 'Wisuda ke-1',
                'tanggal_pendaftaran' => now(),
                'tanggal_penutupan' => now()->addDays(7),
                'kuota_wisudawan' => 500,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $fakultas = [
            'Teknik' => ['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer', 'Teknik Elektro'],
            'Hukum' => ['Hukum', 'Hukum Pidana', 'Hukum Perdata'],
            'Ekonomi' => ['Manajemen', 'Akuntansi', 'Ekonomi Pembangunan'],
            'Kedokteran' => ['Kedokteran', 'Kedokteran Gigi', 'Farmasi']
        ];

        $tempatLahir = ['Semarang', 'Jakarta', 'Surabaya', 'Yogyakarta', 'Bandung', 'Malang', 'Solo', 'Medan', 'Makassar', 'Palembang'];

        $tahunMasuk = ['2018', '2019', '2020', '2021'];
        $jenisKelamin = ['Laki-laki', 'Perempuan'];
        $statusMahasiswa = ['Baru', 'Transfer'];

        $usedNims = [];

        for ($i = 1; $i <= 20; $i++) {
            $tahun = $faker->randomElement($tahunMasuk);
            $fakultasKey = $faker->randomElement(array_keys($fakultas));
            $programStudi = $faker->randomElement($fakultas[$fakultasKey]);
            $jenisKelaminValue = $faker->randomElement($jenisKelamin);

            $nimPrefix = substr($tahun, -2);
            $nimSuffix = str_pad($i, 5, '0', STR_PAD_LEFT);
            $nim = $nimPrefix . '1030' . $nimSuffix;

            while (in_array($nim, $usedNims)) {
                $nimSuffix = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
                $nim = $nimPrefix . '1030' . $nimSuffix;
            }
            $usedNims[] = $nim;

            $nameLengkap = $faker->name();

            $email = $nim . '@unwahas.ac.id';

            $tahunLahir = (int)$tahun - 18 - rand(0, 2);
            $tanggalLahir = $faker->dateTimeBetween("{$tahunLahir}-01-01", "{$tahunLahir}-12-31")->format('Y-m-d');

            $userId = Str::uuid();

            DB::table('users')->insert([
                'id' => $userId,
                'name_lengkap' => $nameLengkap,
                'email' => $email,
                'role' => 'mahasiswa',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('biodatas')->insert([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'wisuda_id' => $wisudaId,
                'nim' => $nim,
                'tahun_masuk' => $tahun,
                'fakultas' => $fakultasKey,
                'program_studi' => $programStudi,
                'tempat_lahir' => $faker->randomElement($tempatLahir),
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $jenisKelaminValue,
                'status_mahasiswa' => $faker->randomElement($statusMahasiswa),
                'alamat_rumah' => $faker->address(),
                'no_telepon' => $faker->phoneNumber(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

