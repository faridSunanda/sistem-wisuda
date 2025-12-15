<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BiodataSeeder extends Seeder
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

        // Generating 20 records similar to DataWisudawanSeeder
        for ($i = 1; $i <= 20; $i++) {
            $tahun = $faker->randomElement($tahunMasuk);
            $fakultasKey = $faker->randomElement(array_keys($fakultas));
            $programStudi = $faker->randomElement($fakultas[$fakultasKey]);
            $jenisKelaminValue = $faker->randomElement($jenisKelamin);

            $nimPrefix = substr($tahun, -2);
            // Use a different range logic or just random to avoid collision with existing data easily, 
            // but the original logic tries to valid uniqueness.
            // I'll stick to similar logic but maybe randomized suffix to ensure we don't clash if run multiple times.
            $nimSuffix = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT); 
            $nim = $nimPrefix . '1030' . $nimSuffix;

            // Ensure uniqueness within this run and DB
            while (in_array($nim, $usedNims) || DB::table('biodatas')->where('nim', $nim)->exists()) {
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

        // --- SPECIFIC SEED FOR LOGIN USER ---
        $loginUser = DB::table('users')->where('email', 'mahasiswa@wisuda.com')->first();
        if ($loginUser && !DB::table('biodatas')->where('user_id', $loginUser->id)->exists()) {
             $tahun = '2020';
             $nim = '2010300001'; // Fixed NIM for test user
             
             DB::table('biodatas')->insert([
                'id' => Str::uuid(),
                'user_id' => $loginUser->id,
                'wisuda_id' => $wisudaId,
                'nim' => $nim,
                'tahun_masuk' => $tahun,
                'fakultas' => 'Teknik',
                'program_studi' => 'Teknik Informatika',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'status_mahasiswa' => 'Baru',
                'alamat_rumah' => 'Jl. Sampangan No. 1',
                'no_telepon' => '081234567890',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
