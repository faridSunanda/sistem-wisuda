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
use App\Http\Controllers\Mahasiswa\SertifikatPendidikanKarakterController;

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
            Route::get('/data/get-data', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'getData'])->name('get-data');
            Route::get('/', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Setting\AlurPendaftaranController::class, 'show'])->name('show');
        });

        // Dokumen Persyaratan
        Route::prefix('dokumen-persyaratan')->name('dokumen-persyaratan.')->group(function () {
            Route::get('/data/get-data', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'getData'])->name('get-data');
            Route::get('/', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Setting\DokumenPersyaratanController::class, 'show'])->name('show');
        });

        // Jadwal Pendaftaran
        Route::prefix('jadwal-pendaftaran')->name('jadwal-pendaftaran.')->group(function () {
            Route::get('/data/get-data', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'getData'])->name('get-data');
            Route::get('/', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Setting\JadwalPendaftaranController::class, 'show'])->name('show');
        });

        // Jadwal Wisuda
        Route::prefix('jadwal-wisuda')->name('jadwal-wisuda.')->group(function () {
            Route::get('/data/get-data', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'getData'])->name('get-data');
            Route::get('/', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Setting\JadwalWisudaController::class, 'show'])->name('show');
        });

        // Kuota Wisudawan
        Route::prefix('kuota-wisudawan')->name('kuota-wisudawan.')->group(function () {
            Route::get('/data/get-data', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'getData'])->name('get-data');
            Route::get('/', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Setting\KuotaWisudawanController::class, 'show'])->name('show');
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

        // Sertifikat Pendidikan Karakter Routes
        Route::get('/pendidikan-karakter', [SertifikatPendidikanKarakterController::class, 'index'])->name('pendidikan-karakter');
        Route::post('/pendidikan-karakter', [SertifikatPendidikanKarakterController::class, 'store'])->name('pendidikan-karakter.store');

        // Sertifikat Penghargaam
        Route::get('/penghargaan', [SertifikatPenghargaanController::class, 'index'])->name('penghargaan');
        Route::post('/penghargaan', [SertifikatPenghargaanController::class, 'store'])->name('penghargaan.store');
        
        // Sertifikat Organisasi
        Route::get('/organisasi', [SertifikatOrganisasiController::class, 'index'])->name('organisasi');
        Route::post('/organisasi', [SertifikatOrganisasiController::class, 'store'])->name('organisasi.store');
    });

    Route::get('/download-formulir', function () {
        return view('mahasiswa.download-formulir.index');
    })->name('download-formulir');

});
