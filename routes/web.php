<?php

// Auth
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataWisudawanController;
use App\Http\Controllers\Admin\GroupWisudawanController;

use App\Http\Controllers\Admin\Master\DokumenPersyaratanController;
use App\Http\Controllers\Admin\Master\AlurPendaftaranController;
use App\Http\Controllers\Admin\Master\SesiController;
use App\Http\Controllers\Admin\Master\GroupController;

use App\Http\Controllers\Admin\Wisuda\WisudaController;
use App\Http\Controllers\Admin\Wisuda\JadwalPelaksanaanController;

// Mahasiswa
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\SertifikatOrganisasiController;
use App\Http\Controllers\Mahasiswa\SertifikatPenghargaanController;
use App\Http\Controllers\Mahasiswa\SertifikatPendidikanKarakterController;
use App\Http\Controllers\Mahasiswa\PembayaranController;

// Mahasiswa
use App\Http\Controllers\Mahasiswa\BiodataController;
use App\Http\Controllers\Mahasiswa\SertifikatKompetensiController;
use App\Http\Controllers\Mahasiswa\SertifikatBahasaInternasionalController;
use App\Http\Controllers\Mahasiswa\SertifikatMagangController;


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
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('master')->name('master.')->group(function () {

        // 1. Alur Pendaftaran
        Route::controller(AlurPendaftaranController::class)
            ->prefix('alur-pendaftaran')
            ->name('alur-pendaftaran.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                Route::get('/data/get-data', 'getData')->name('get-data');
                Route::get('/data/export', 'exportData')->name('export');
                Route::get('/export-excel', 'exportExcel')->name('export-excel');
                Route::get('/export-pdf', 'exportPdf')->name('export-pdf');

                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}', 'show')->name('show');
            });

        // 2. Dokumen Persyaratan
        Route::controller(DokumenPersyaratanController::class)
            ->prefix('dokumen-persyaratan')
            ->name('dokumen-persyaratan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                Route::get('/data/get-data', 'getData')->name('get-data');
                Route::get('/export', 'exportData')->name('export');
                Route::get('/export-excel', 'exportExcel')->name('export-excel');
                Route::get('/export-pdf', 'exportPdf')->name('export-pdf');

                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}', 'show')->name('show');
            });

        Route::controller(GroupController::class)
            ->prefix('group')
            ->name('group.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                Route::get('/get-data', 'getData')->name('get-data');
                Route::get('/export', 'exportData')->name('export');
                Route::get('/export-excel', 'exportExcel')->name('export-excel');
                Route::get('/export-pdf', 'exportPdf')->name('export-pdf');

                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}', 'show')->name('show');
            });

        Route::controller(App\Http\Controllers\Admin\Master\SesiController::class)
            ->prefix('sesi')
            ->name('sesi.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                // Ajax & Export
                Route::get('/get-data', 'getData')->name('get-data');
                Route::get('/export', 'exportData')->name('export');
                Route::get('/export-excel', 'exportExcel')->name('export-excel');
                Route::get('/export-pdf', 'exportPdf')->name('export-pdf');

                // Dinamis
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}', 'show')->name('show');
            });
    });

    Route::prefix('wisuda')->name('wisuda.')->group(function () {

        Route::controller(WisudaController::class)
            ->prefix('wisuda')
            ->name('wisuda.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/get-data', 'getData')->name('get-data');
                Route::get('/export', 'exportData')->name('export');
                Route::get('/export-excel', 'exportExcel')->name('export-excel');
                Route::get('/export-pdf', 'exportPdf')->name('export-pdf');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}', 'show')->name('show');
            });

        Route::controller(JadwalPelaksanaanController::class)
            ->prefix('jadwal-pelaksanaan')
            ->name('jadwal-pelaksanaan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                Route::get('/get-data', 'getData')->name('get-data');
                Route::get('/export', 'exportData')->name('export');
                Route::get('/export-excel', 'exportExcel')->name('export-excel');
                Route::get('/export-pdf', 'exportPdf')->name('export-pdf');

                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::get('/{id}', 'show')->name('show');
            });

    });

    Route::controller(DataWisudawanController::class)
        ->prefix('data-wisudawan')
        ->name('data-wisudawan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/get-data', 'getData')->name('get-data');
            Route::get('/export', 'exportData')->name('export');
            Route::get('/export-excel', 'exportExcel')->name('export-excel');
            Route::get('/export-pdf', 'exportPdf')->name('export-pdf');

            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}', 'show')->name('show');
        });

    Route::controller(GroupWisudawanController::class)
        ->prefix('group-wisudawan')
        ->name('group-wisudawan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-data', 'getData')->name('get-data');
            Route::post('/download', 'downloadPpt')->name('download');
            Route::post('/pindahkan-ke', 'pindahkanKe')->name('pindahkan-ke');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}', 'show')->name('show');
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

    Route::prefix('biodata')->name('biodata.')->group(function () {
        Route::get('/', [BiodataController::class, 'edit'])->name('edit');
        Route::post('/', [BiodataController::class, 'update'])->name('update');
    });

    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::get('/kompetensi', [SertifikatKompetensiController::class, 'index'])->name('kompetensi');
        Route::post('/kompetensi', [SertifikatKompetensiController::class, 'store'])->name('kompetensi.store');
        Route::get('/bahasa-internasional', [SertifikatBahasaInternasionalController::class, 'index'])->name('bahasa-internasional');
        Route::post('/bahasa-internasional', [SertifikatBahasaInternasionalController::class, 'store'])->name('bahasa-internasional.store');
        Route::get('/magang', [SertifikatMagangController::class, 'index'])->name('magang');
        Route::post('/magang', [SertifikatMagangController::class, 'store'])->name('magang.store');
        Route::get('/pendidikan-karakter', function () {
            return view('mahasiswa.sertifikat.sertifikat-pendidikan-karakter.index');
        })->name('pendidikan-karakter');

        // Sertifikat Pendidikan Karakter Routes
        Route::get('/pendidikan-karakter', [SertifikatPendidikanKarakterController::class, 'index'])->name('pendidikan-karakter');
        Route::post('/pendidikan-karakter', [SertifikatPendidikanKarakterController::class, 'store'])->name('pendidikan-karakter.store');

        // Sertifikat Penghargaan
        Route::get('/penghargaan', [SertifikatPenghargaanController::class, 'index'])->name('penghargaan');
        Route::post('/penghargaan', [SertifikatPenghargaanController::class, 'store'])->name('penghargaan.store');

        // Sertifikat Organisasi
        Route::get('/organisasi', [SertifikatOrganisasiController::class, 'index'])->name('organisasi');
        Route::post('/organisasi', [SertifikatOrganisasiController::class, 'store'])->name('organisasi.store');
    });

    Route::get('/download-formulir', function () {
        return view('mahasiswa.download-formulir.index');
    })->name('download-formulir');

    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::post('/create', [PembayaranController::class, 'createPayment'])->name('create');
        Route::post('/check-status', [PembayaranController::class, 'checkStatus'])->name('check-status');
        Route::post('/verify', [PembayaranController::class, 'verifyPayment'])->name('verify');
    });

});
