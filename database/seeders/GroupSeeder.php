<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Kanan',
                'keterangan' => 'Grup wisudawan yang duduk di sebelah kanan',
            ],
            [
                'name' => 'Kiri',
                'keterangan' => 'Grup wisudawan yang duduk di sebelah kiri',
            ],
        ];

        foreach ($groups as $group) {
            Group::firstOrCreate(
                ['name' => $group['name']],
                ['keterangan' => $group['keterangan']]
            );
        }
    }
}
