<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBiodata = Biodata::count();
        
        $stats = [
            'verified' => Biodata::whereHas('user', function($query) {
                $query->where('role', 'mahasiswa');
            })->count(),
            'not_verified' => 0,
            'graduated' => 0,
            'total' => $totalBiodata
        ];

        $fakultasData = Biodata::select('fakultas', DB::raw('count(*) as total'))
            ->whereNotNull('fakultas')
            ->where('fakultas', '!=', '')
            ->groupBy('fakultas')
            ->orderBy('total', 'desc')
            ->limit(4)
            ->pluck('total', 'fakultas')
            ->toArray();

        $fakultasLabels = array_keys($fakultasData);
        $fakultasValues = array_values($fakultasData);
        
        while (count($fakultasLabels) < 4) {
            $fakultasLabels[] = '';
            $fakultasValues[] = 0;
        }

        $tahunMasukData = Biodata::select('tahun_masuk', DB::raw('count(*) as total'))
            ->whereNotNull('tahun_masuk')
            ->where('tahun_masuk', '!=', '')
            ->groupBy('tahun_masuk')
            ->orderBy('tahun_masuk', 'desc')
            ->limit(4)
            ->pluck('total', 'tahun_masuk')
            ->toArray();

        $tahunMasukLabels = array_keys($tahunMasukData);
        $tahunMasukValues = array_values($tahunMasukData);
        
        while (count($tahunMasukLabels) < 4) {
            $tahunMasukLabels[] = '';
            $tahunMasukValues[] = 0;
        }

        $jenjangData = Biodata::select('program_studi')
            ->whereNotNull('program_studi')
            ->where('program_studi', '!=', '')
            ->get()
            ->groupBy(function($item) {
                if (stripos($item->program_studi, 'S1') !== false || stripos($item->program_studi, 'Sarjana') !== false) {
                    return 'S1';
                } elseif (stripos($item->program_studi, 'S2') !== false || stripos($item->program_studi, 'Magister') !== false) {
                    return 'S2';
                } elseif (stripos($item->program_studi, 'S3') !== false || stripos($item->program_studi, 'Doktor') !== false) {
                    return 'S3';
                }
                return 'S1';
            })
            ->map(function($group) {
                return $group->count();
            })
            ->toArray();

        $jenjangValues = [
            $jenjangData['S1'] ?? 0,
            $jenjangData['S2'] ?? 0,
            $jenjangData['S3'] ?? 0,
        ];

        $charts = [
            'fakultas' => [
                'labels' => $fakultasLabels,
                'data' => $fakultasValues
            ],
            'tahun_masuk' => [
                'labels' => $tahunMasukLabels,
                'data' => $tahunMasukValues
            ],
            'jenjang' => [
                'labels' => ['S1', 'S2', 'S3'],
                'data' => $jenjangValues
            ]
        ];

        return view('admin.dashboard.index', compact('stats', 'charts'));
    }
}

