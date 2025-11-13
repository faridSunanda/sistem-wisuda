<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    private $siakuBaseUrl;
    private $siakuToken;

    public function __construct()
    {
        // Konfigurasi SIAKU API - sesuaikan dengan environment Anda
        $this->siakuBaseUrl = config('services.siaku.base_url');
        $this->siakuToken = config('services.siaku.token');
    }

    public function index()
    {
        $mahasiswa = Auth::user();
        $tagihan = null;
        $brivaNumber = null;
        $error = null;

        try {
            // Get data tagihan dari SIAKU API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->siakuToken,
                'Accept' => 'application/json',
            ])->get($this->siakuBaseUrl . '/api/tagihan-wisuda', [
                'nim' => $mahasiswa->nim,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $tagihan = $data['data']['amount'] ?? 1800000; // Default fallback
                $brivaNumber = $data['data']['briva_number'] ?? $this->generateBrivaNumber($mahasiswa->nim);
                
                // Simpan ke session atau database jika perlu
                session(['briva_number' => $brivaNumber]);
                session(['tagihan_amount' => $tagihan]);
            } else {
                $error = 'Gagal mengambil data tagihan dari sistem.';
                // Fallback values
                $tagihan = 1800000;
                $brivaNumber = $this->generateBrivaNumber($mahasiswa->nim);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching payment data: ' . $e->getMessage());
            $error = 'Terjadi kesalahan saat mengambil data pembayaran.';
            // Fallback values
            $tagihan = 1800000;
            $brivaNumber = $this->generateBrivaNumber($mahasiswa->nim);
        }

        return view('mahasiswa.pembayaran.index', compact('tagihan', 'brivaNumber', 'error'));
    }

    public function checkStatus(Request $request)
    {
        $mahasiswa = Auth::user();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->siakuToken,
                'Accept' => 'application/json',
            ])->get($this->siakuBaseUrl . '/api/status-pembayaran', [
                'nim' => $mahasiswa->nim,
                'briva_number' => session('briva_number')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'status' => $data['data']['status'],
                    'paid_at' => $data['data']['paid_at'] ?? null,
                    'message' => $this->getStatusMessage($data['data']['status'])
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memeriksa status pembayaran'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memeriksa status'
            ], 500);
        }
    }

    private function generateBrivaNumber($nim)
    {
        // Generate BRIVA number dari NIM + kode institusi
        $institutionCode = '87654'; // Kode institusi UNWAHAS
        return $institutionCode . str_pad($nim, 13, '0', STR_PAD_LEFT);
    }

    private function getStatusMessage($status)
    {
        $messages = [
            'paid' => 'Pembayaran telah berhasil diverifikasi',
            'pending' => 'Menunggu konfirmasi pembayaran',
            'unpaid' => 'Belum melakukan pembayaran',
            'expired' => 'Tagihan telah kedaluwarsa',
            'failed' => 'Pembayaran gagal'
        ];

        return $messages[$status] ?? 'Status tidak diketahui';
    }
}