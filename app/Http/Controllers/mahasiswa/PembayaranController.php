<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    private const DEFAULT_AMOUNT = 1800000;
    private const INSTITUTION_CODE = '87654';
    
    private $siakuBaseUrl;
    private $siakuToken;

    public function __construct()
    {
        $this->siakuBaseUrl = config('services.siaku.base_url');
        $this->siakuToken = config('services.siaku.token');
    }

    public function index()
    {
        $mahasiswa = Auth::user();
        
        try {
            $response = $this->fetchPaymentData($mahasiswa->nim);

            if ($response->successful()) {
                $data = $response->json();
                $tagihan = $data['data']['amount'] ?? self::DEFAULT_AMOUNT;
                $brivaNumber = $data['data']['briva_number'] ?? $this->generateBrivaNumber($mahasiswa->nim);
                
                session([
                    'briva_number' => $brivaNumber,
                    'tagihan_amount' => $tagihan
                ]);
            } else {
                $tagihan = self::DEFAULT_AMOUNT;
                $brivaNumber = $this->generateBrivaNumber($mahasiswa->nim);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching payment data: ' . $e->getMessage());
            $tagihan = self::DEFAULT_AMOUNT;
            $brivaNumber = $this->generateBrivaNumber($mahasiswa->nim);
        }

        return view('mahasiswa.pembayaran.index', compact('tagihan', 'brivaNumber'));
    }

    public function checkStatus(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->siakuToken,
                'Accept' => 'application/json',
            ])->get($this->siakuBaseUrl . '/api/status-pembayaran', [
                'nim' => Auth::user()->nim,
                'briva_number' => session('briva_number')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'status' => $data['data']['status'] ?? 'unpaid',
                    'paid_at' => $data['data']['paid_at'] ?? null,
                    'message' => $this->getStatusMessage($data['data']['status'] ?? 'unpaid')
                ]);
            }

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memeriksa status pembayaran'
                ], 500);
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memeriksa status'
            ], 500);
        }
    }

    private function fetchPaymentData($nim)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->siakuToken,
            'Accept' => 'application/json',
        ])->get($this->siakuBaseUrl . '/api/tagihan-wisuda', [
            'nim' => $nim,
        ]);
    }

    private function generateBrivaNumber($nim)
    {
        return self::INSTITUTION_CODE . str_pad($nim, 13, '0', STR_PAD_LEFT);
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
