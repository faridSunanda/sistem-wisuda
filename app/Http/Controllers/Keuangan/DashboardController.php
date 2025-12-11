<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $sudahBayar = Biodata::where('is_bayar', true)
            ->where('is_verified_keuangan', true)
            ->count();

        $belumBayar = Biodata::where(function($query) {
                $query->where('is_bayar', false)
                      ->orWhereNull('is_bayar');
            })
            ->count();

        $menungguKonfirmasi = Biodata::where('is_bayar', true)
            ->where('is_verified_keuangan', false)
            ->count();

        $stats = [
            'sudah_bayar' => $sudahBayar,
            'belum_bayar' => $belumBayar,
            'menunggu_konfirmasi' => $menungguKonfirmasi,
            'total' => Biodata::count()
        ];

        return view('keuangan.dashboard', compact('stats'));
    }
}
