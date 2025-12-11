<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Biodata;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mengisi data pembayaran...');

        // Ambil semua mahasiswa yang memiliki biodata
        $biodatas = Biodata::with('user')
            ->whereNotNull('nim')
            ->get();

        if ($biodatas->isEmpty()) {
            $this->command->warn('Tidak ada data biodata. Jalankan DataWisudawanSeeder terlebih dahulu.');
            return;
        }

        $countLunas = 0;
        $countPending = 0;
        $countBelumBayar = 0;

        DB::beginTransaction();
        try {
            foreach ($biodatas as $biodata) {
                // Generate random number untuk distribusi status
                // 0-29: Lunas & Terverifikasi (30%)
                // 30-49: Sudah Bayar tapi belum terverifikasi (20%)
                // 50-99: Belum Bayar (50%)
                $random = rand(0, 99);

                $isBayar = false;
                $isVerified = false;
                $updatedAt = $biodata->updated_at;

                if ($random < 30) {
                    // Lunas & Terverifikasi (30%)
                    $isBayar = true;
                    $isVerified = true;
                    // Tanggal pembayaran 1-60 hari yang lalu
                    $updatedAt = Carbon::now()->subDays(rand(1, 60));
                    $countLunas++;
                } elseif ($random < 50) {
                    // Sudah Bayar tapi belum terverifikasi (20%)
                    $isBayar = true;
                    $isVerified = false;
                    // Tanggal pembayaran 1-30 hari yang lalu (baru bayar)
                    $updatedAt = Carbon::now()->subDays(rand(1, 30));
                    $countPending++;
                } else {
                    // Belum Bayar (50%)
                    $isBayar = false;
                    $isVerified = false;
                    // Tetap menggunakan updated_at yang sudah ada
                    $countBelumBayar++;
                }

                // Update biodata dengan status pembayaran
                $biodata->update([
                    'is_bayar' => $isBayar,
                    'is_verified_keuangan' => $isVerified,
                    'updated_at' => $updatedAt
                ]);
            }

            DB::commit();

            $this->command->info("✓ Data pembayaran berhasil diupdate:");
            $this->command->info("  - Lunas & Terverifikasi: {$countLunas} mahasiswa");
            $this->command->info("  - Sudah Bayar (Pending): {$countPending} mahasiswa");
            $this->command->info("  - Belum Bayar: {$countBelumBayar} mahasiswa");
            $this->command->info("✓ Total: " . $biodatas->count() . " mahasiswa");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Terjadi kesalahan saat mengisi data pembayaran: ' . $e->getMessage());
            throw $e;
        }
    }
}

