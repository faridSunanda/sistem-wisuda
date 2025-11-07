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
            'password' => '123',
            'role' => 'admin',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name_lengkap' => 'Mahasiswa Wisuda',
            'email' => 'mahasiswa@wisuda.com',
            'password' => '123',
            'role' => 'mahasiswa',
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}
