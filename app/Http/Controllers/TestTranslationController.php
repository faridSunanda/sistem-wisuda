<?php

namespace App\Http\Controllers;

use App\Services\GoogleTranslateService;
use Illuminate\Http\Request;

class TestTranslationController extends Controller
{
    public function test(GoogleTranslateService $translator)
    {
        $testCases = [
            'Sertifikat Pelatihan Laravel untuk Pemula',
            'Workshop Pengembangan Aplikasi Android',
            'Seminar Nasional Teknologi Informasi',
            'Sertifikat Juara 1 Lomba Programming',
            'Pelatihan Bahasa Inggris untuk Karyawan',
            'Sertifikat Magang di PT. Google Indonesia',
            'Penghargaan Mahasiswa Berprestasi Tingkat Nasional',
            'Sertifikat Kompetensi Jaringan Komputer',
        ];
        
        $results = [];
        
        foreach ($testCases as $indonesian) {
            $english = $translator->translateCertificate($indonesian);
            $results[] = [
                'indonesian' => $indonesian,
                'english' => $english
            ];
        }
        
        // Test connection
        $connectionTest = $translator->testConnection();
        
        return view('test-translation', compact('results', 'connectionTest'));
    }
    
    public function translateApi(Request $request, GoogleTranslateService $translator)
    {
        $request->validate([
            'text' => 'required|string|max:500'
        ]);
        
        $translated = $translator->translateCertificate($request->text);
        
        return response()->json([
            'success' => true,
            'original' => $request->text,
            'translated' => $translated,
            'timestamp' => now()->toDateTimeString()
        ]);
    }
    
    public function bulkTranslate(Request $request, GoogleTranslateService $translator)
    {
        $request->validate([
            'texts' => 'required|array',
            'texts.*' => 'string|max:500'
        ]);
        
        $results = $translator->translateBatch($request->texts);
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'count' => count($results)
        ]);
    }
}