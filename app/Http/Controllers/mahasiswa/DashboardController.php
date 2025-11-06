<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'verified' => 128,
            'not_verified' => 98,
            'graduated' => 1020,
            'total' => 226
        ];

        $charts = [
            'fakultas' => [35, 30, 25, 10],
            'tahun_masuk' => [45, 25, 20, 10],
            'jenjang' => [70, 20, 10]
        ];

        return view('mahasiswa.dashboard.index', compact('stats', 'charts'));
    }
}

