<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('portal.index');
})->name('portal');


Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

//
//          ADMIN ROUTES
//
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});


//
//          MAHASISWA ROUTES
//
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth', 'role:mahasiswa'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('mahasiswa.dashboard');
    });

    Route::get('/dashboard', [App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

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

});
