<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
      public function index()
    {
        return view('mahasiswa.dashboard.index');
    }

    public function organisasi()
    {
        return view('mahasiswa.dashboard.organisasi');
    }
}
