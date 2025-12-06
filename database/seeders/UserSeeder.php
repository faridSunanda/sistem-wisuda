<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name_lengkap' => 'Admin Wisuda',
            'email' => 'admin@wisuda.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name_lengkap' => 'Mahasiswa Wisuda',
            'email' => 'mahasiswa@wisuda.com',
            'password' => Hash::make('123'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name_lengkap' => 'Keuangan Wisuda',
            'email' => 'keuangan@wisuda.com',
            'password' => Hash::make('123'),
            'role' => 'keuangan',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name_lengkap' => 'Akademik Wisuda',
            'email' => 'akademik@wisuda.com',
            'password' => Hash::make('123'),
            'role' => 'akademik',
            'email_verified_at' => now(),
        ]);
    }
}
