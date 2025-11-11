<?php

// Auth
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataWisudawanController;
use App\Http\Controllers\Admin\Setting\AlurPendaftaranController;
use App\Http\Controllers\Admin\Setting\DokumenPersyaratanController;
use App\Http\Controllers\Admin\Setting\JadwalPendaftaranController;
use App\Http\Controllers\Admin\Setting\JadwalWisudaController;
use App\Http\Controllers\Admin\Setting\KuotaWisudaController;

// Mahasiswa
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/', [BerandaController::class, 'index'])->name('portal');


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

    Route::prefix('data-wisudawan')->name('data-wisudawan.')->group(function () {
        Route::get('/', [DataWisudawanController::class, 'index'])->name('index');
        Route::get('/get-data', [DataWisudawanController::class, 'getData'])->name('get-data');
        Route::get('/export', [DataWisudawanController::class, 'exportData'])->name('export');
        Route::get('/{id}/edit', [DataWisudawanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DataWisudawanController::class, 'update'])->name('update');
        Route::delete('/{id}', [DataWisudawanController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [DataWisudawanController::class, 'show'])->name('show');
    });

    Route::prefix('setting')->name('setting.')->group(function () {

        // Alur Pendaftaran
        Route::prefix('alur-pendaftaran')->name('alur-pendaftaran.')->group(function () {
            Route::get('/data/get-data', [AlurPendaftaranController::class, 'getData'])->name('get-data');
            Route::get('/data/export', [AlurPendaftaranController::class, 'exportData'])->name('export');
            Route::get('/', [AlurPendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [AlurPendaftaranController::class, 'create'])->name('create');
            Route::post('/', [AlurPendaftaranController::class, 'store'])->name('store');
            Route::get('/{alur_pendaftaran}/edit', [AlurPendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{alur_pendaftaran}', [AlurPendaftaranController::class, 'update'])->name('update');
            Route::delete('/{alur_pendaftaran}', [AlurPendaftaranController::class, 'destroy'])->name('destroy');
            Route::get('/{alur_pendaftaran}', [AlurPendaftaranController::class, 'show'])->name('show');
            // Route::get('/export', [AlurPendaftaranController::class, 'exportData'])->name('export');
        });

        // Dokumen Persyaratan
        Route::prefix('dokumen-persyaratan')->name('dokumen-persyaratan.')->group(function () {
            Route::get('/data/get-data', [DokumenPersyaratanController::class, 'getData'])->name('get-data');
            Route::get('/export', [DokumenPersyaratanController::class, 'exportData'])->name('export');
            Route::get('/', [DokumenPersyaratanController::class, 'index'])->name('index');
            Route::get('/create', [DokumenPersyaratanController::class, 'create'])->name('create');
            Route::post('/', [DokumenPersyaratanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DokumenPersyaratanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DokumenPersyaratanController::class, 'update'])->name('update');
            Route::delete('/{id}', [DokumenPersyaratanController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [DokumenPersyaratanController::class, 'show'])->name('show');
        });

        // Jadwal Pendaftaran
        Route::prefix('jadwal-pendaftaran')->name('jadwal-pendaftaran.')->group(function () {
            Route::get('/data/get-data', [JadwalPendaftaranController::class, 'getData'])->name('get-data');
            Route::get('/export', [JadwalPendaftaranController::class, 'exportData'])->name('export');
            Route::get('/', [JadwalPendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [JadwalPendaftaranController::class, 'create'])->name('create');
            Route::post('/', [JadwalPendaftaranController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [JadwalPendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JadwalPendaftaranController::class, 'update'])->name('update');
            Route::delete('/{id}', [JadwalPendaftaranController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [JadwalPendaftaranController::class, 'show'])->name('show');
            Route::post('/{id}/activate', [JadwalPendaftaranController::class, 'activate'])->name('activate');
        });

        // Jadwal Wisuda
         Route::prefix('jadwal-wisuda')->name('jadwal-wisuda.')->group(function () {
            Route::get('/', [JadwalWisudaController::class, 'index'])->name('index');
            Route::get('/get-data', [JadwalWisudaController::class, 'getData'])->name('get-data');
            Route::get('/export', [JadwalWisudaController::class, 'exportData'])->name('export');
            Route::get('/create', [JadwalWisudaController::class, 'create'])->name('create');
            Route::post('/', [JadwalWisudaController::class, 'store'])->name('store');
            Route::get('/{id}', [JadwalWisudaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [JadwalWisudaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JadwalWisudaController::class, 'update'])->name('update');
            Route::delete('/{id}', [JadwalWisudaController::class, 'destroy'])->name('destroy');
            Route::get('/pendaftaran/{pendaftaranId}', [JadwalWisudaController::class, 'getByPendaftaran'])->name('by-pendaftaran');
        });

        // Kuota Wisudawan
        Route::prefix('kuota-wisuda')->name('kuota-wisuda.')->group(function () {
            Route::get('/', [KuotaWisudaController::class, 'index'])->name('index');
            Route::get('/get-data', [KuotaWisudaController::class, 'getData'])->name('get-data');
            Route::get('/export', [KuotaWisudaController::class, 'exportData'])->name('export');
            Route::get('/create', [KuotaWisudaController::class, 'create'])->name('create');
            Route::post('/', [KuotaWisudaController::class, 'store'])->name('store');
            Route::get('/{id}', [KuotaWisudaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [KuotaWisudaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KuotaWisudaController::class, 'update'])->name('update');
            Route::delete('/{id}', [KuotaWisudaController::class, 'destroy'])->name('destroy');
            Route::get('/pendaftaran/{pendaftaranId}', [KuotaWisudaController::class, 'getByPendaftaran'])->name('by-pendaftaran');
        });
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
