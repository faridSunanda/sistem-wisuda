<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AlurPendaftaran;
use Illuminate\Support\Str;

class AlurPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AlurPendaftaran::query()->forceDelete();

        $data = [
            ['fa-house-laptop', 'Mulai', 'Buka sistem informasi wisuda wisuda.unwahas.ac.id'],
            ['fa-file-circle-check', 'Syarat', 'Download tanda terima dan persyaratan pada laman web'],
            ['fa-credit-card', 'Daftar', 'Melakukan pembayaran biaya wisuda'],
            ['fa-building-columns', 'Pembayaran', 'Lakukan pembayaran sesuai tagihan pada Virtual Account'],
            ['fa-pen-to-square', 'Isi Form', 'Lengkapi formulir pendaftaran wisuda'],
            ['fa-print', 'Cetak', 'Cetak form pendaftaran wisuda (yang berisi SKPI)'],
            ['fa-user-check', 'Validasi Kaprodi', 'Form pendaftaran & transkrip sementara divalidasi prodi'],
            ['fa-sack-dollar', 'Validasi Keuangan', 'Validasi pelunasan administrasi wisuda & biaya pendidikan'],
            ['fa-book-open-reader', 'Validasi Akademik', 'Validasi data mahasiswa pada PDDIKTI dan pelayanan akademik'],
            ['fa-circle-check', 'Terdaftar', 'Selamat, Anda terdaftar sebagai calon wisudawan Universitas Wahid Hasyim']
        ];

        foreach ($data as $key => $item) {
            AlurPendaftaran::create([
                'id' => Str::uuid(),
                'no_urut' => $key + 1,
                'judul' => $item[1],
                'keterangan' => $item[2],
            ]);
        }
    }
}
