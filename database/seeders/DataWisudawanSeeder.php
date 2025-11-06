<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataWisudawanSeeder extends Seeder
{
    public function run(): void
    {
        $wisudawan = [
            [
                'user' => [
                    'name_lengkap' => 'Hairudin Farid Sunanda',
                    'email' => '21103041039@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '21103041039',
                    'tahun_masuk' => '2021',
                    'fakultas' => 'Teknik',
                    'program_studi' => 'Teknik Informatika',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '2000-05-15',
                    'jenis_kelamin' => 'Laki-laki',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Farid Sunanda',
                    'email' => '21103041009@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '21103041009',
                    'tahun_masuk' => '2021',
                    'fakultas' => 'Teknik',
                    'program_studi' => 'Teknik Informatika',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '2001-03-20',
                    'jenis_kelamin' => 'Laki-laki',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Ahmad Rizki Pratama',
                    'email' => '21103041010@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '21103041010',
                    'tahun_masuk' => '2021',
                    'fakultas' => 'Teknik',
                    'program_studi' => 'Sistem Informasi',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '2000-07-10',
                    'jenis_kelamin' => 'Laki-laki',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Siti Nurhaliza',
                    'email' => '20103041001@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '20103041001',
                    'tahun_masuk' => '2020',
                    'fakultas' => 'Hukum',
                    'program_studi' => 'Hukum',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '1999-11-25',
                    'jenis_kelamin' => 'Perempuan',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Budi Santoso',
                    'email' => '20103041002@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '20103041002',
                    'tahun_masuk' => '2020',
                    'fakultas' => 'Ekonomi',
                    'program_studi' => 'Manajemen',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '1999-09-12',
                    'jenis_kelamin' => 'Laki-laki',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Dewi Sartika',
                    'email' => '19103041001@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '19103041001',
                    'tahun_masuk' => '2019',
                    'fakultas' => 'Kedokteran',
                    'program_studi' => 'Kedokteran',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '1998-04-08',
                    'jenis_kelamin' => 'Perempuan',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Muhammad Fajar',
                    'email' => '21103041011@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '21103041011',
                    'tahun_masuk' => '2021',
                    'fakultas' => 'Teknik',
                    'program_studi' => 'Teknik Komputer',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '2000-12-30',
                    'jenis_kelamin' => 'Laki-laki',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Rina Wati',
                    'email' => '20103041003@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '20103041003',
                    'tahun_masuk' => '2020',
                    'fakultas' => 'Hukum',
                    'program_studi' => 'Hukum',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '1999-08-15',
                    'jenis_kelamin' => 'Perempuan',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Andi Wijaya',
                    'email' => '18103041001@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '18103041001',
                    'tahun_masuk' => '2018',
                    'fakultas' => 'Ekonomi',
                    'program_studi' => 'Akuntansi',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '1997-06-22',
                    'jenis_kelamin' => 'Laki-laki',
                    'status_mahasiswa' => 'Aktif'
                ]
            ],
            [
                'user' => [
                    'name_lengkap' => 'Putri Indah',
                    'email' => '21103041012@unwahas.ac.id',
                    'role' => 'mahasiswa'
                ],
                'biodata' => [
                    'nim' => '21103041012',
                    'tahun_masuk' => '2021',
                    'fakultas' => 'Teknik',
                    'program_studi' => 'Teknik Informatika',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '2001-01-18',
                    'jenis_kelamin' => 'Perempuan',
                    'status_mahasiswa' => 'Aktif'
                ]
            ]
        ];

        foreach ($wisudawan as $data) {
            $userId = Str::uuid();
            
            DB::table('users')->insert([
                'id' => $userId,
                'name_lengkap' => $data['user']['name_lengkap'],
                'email' => $data['user']['email'],
                'role' => $data['user']['role'],
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('biodatas')->insert([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'nim' => $data['biodata']['nim'],
                'tahun_masuk' => $data['biodata']['tahun_masuk'],
                'fakultas' => $data['biodata']['fakultas'],
                'program_studi' => $data['biodata']['program_studi'],
                'tempat_lahir' => $data['biodata']['tempat_lahir'],
                'tanggal_lahir' => $data['biodata']['tanggal_lahir'],
                'jenis_kelamin' => $data['biodata']['jenis_kelamin'],
                'status_mahasiswa' => $data['biodata']['status_mahasiswa'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

