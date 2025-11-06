<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;

Route::get('/', function () {
    return view('portal.index');
});


Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('mahasiswa.dashboard');
    });
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-diri', function () {
        return view('mahasiswa.data-diri.index');
    })->name('data-diri');
    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('mahasiswa.sertifikat.kompetensi');
        })->name('index');
        Route::get('/kompetensi', function () {
            return view('mahasiswa.sertifikat.sertifikat-kompetensi.index');
        })->name('kompetensi');
        Route::get('/bahasa-internasional', function () {
            return view('mahasiswa.sertifikat.sertifikat-bahasa-internasional.index');
        })->name('bahasa-internasional');
        Route::get('/magang', function () {
            return view('mahasiswa.sertifikat.sertifikat-magang.index');
        })->name('magang');
        Route::get('/pendidikan-karakter', function () {
            return view('mahasiswa.sertifikat.sertifikat-pendidikan-karakter.index');
        })->name('pendidikan-karakter');
        Route::get('/penghargaan', function () {
            return view('mahasiswa.sertifikat.sertifikat-penghargaan.index');
        })->name('penghargaan');
        Route::get('/organisasi', function () {
            return view('mahasiswa.sertifikat.sertifikat-organisasi.index');
        })->name('organisasi');
    });
    Route::get('/download-formulir', function () {
        return view('mahasiswa.download-formulir.index');
    })->name('download-formulir');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
