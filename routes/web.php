<?php

// Auth
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataWisudawanController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\SertifikatOrganisasiController;
use App\Http\Controllers\Mahasiswa\SertifikatPenghargaanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/', function () {
    return view('portal.index');
})->name('portal');


Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout'); // <-- CUKUP SATU INI

//
//          ADMIN ROUTES
//
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-wisudawan', [DataWisudawanController::class, 'index'])->name('data-wisudawan');
    Route::get('/data-wisudawan/get-data', [DataWisudawanController::class, 'getData'])->name('data-wisudawan.get-data');

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/alur-pendaftaran', function () {
            return view('admin.setting.alur-pendaftaran.index');
        })->name('alur-pendaftaran');
        Route::get('/dokumen-persyaratan', function () {
            return view('admin.setting.dokumen-persyaratan.index');
        })->name('dokumen-persyaratan');
        Route::get('/jadwal-pendaftaran', function () {
            return view('admin.setting.jadwal-pendaftaran.index');
        })->name('jadwal-pendaftaran');
        Route::get('/jadwal-wisuda', function () {
            return view('admin.setting.jadwal-wisuda.index');
        })->name('jadwal-wisuda');
        Route::get('/kuota-wisudawan', function () {
            return view('admin.setting.kuota-wisudawan.index');
        })->name('kuota-wisudawan');
    });
});


//
//          MAHASISWA ROUTES
//
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth', 'role:mahasiswa'])->group(function () {

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
        // Update routes for penghargaan and organisasi
        Route::get('/penghargaan', [SertifikatPenghargaanController::class, 'index'])->name('penghargaan');
        Route::post('/penghargaan', [SertifikatPenghargaanController::class, 'store'])->name('penghargaan.store');
        
        Route::get('/organisasi', [SertifikatOrganisasiController::class, 'index'])->name('organisasi');
        Route::post('/organisasi', [SertifikatOrganisasiController::class, 'store'])->name('organisasi.store');
    });

    Route::get('/download-formulir', function () {
        return view('mahasiswa.download-formulir.index');
    })->name('download-formulir');

});
