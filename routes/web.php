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
use App\Http\Controllers\Mahasiswa\DownloadFormulirController;

// Akademik
use App\Http\Controllers\Akademik\DataWisudawanController as AkademikController;


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

    Route::get('/data-diri', [BiodataController::class, 'index'])->name('biodata.index');
    Route::put('/biodata/update', [BiodataController::class, 'update'])->name('biodata.update');
    Route::post('/biodata/sync', [BiodataController::class, 'syncFromApi'])->name('biodata.sync');

    Route::get('/download-formulir', [DownloadFormulirController::class, 'index'])->name('download-formulir.index');
    Route::get('/download-formulir/download', [DownloadFormulirController::class, 'downloadFormulirWisuda'])->name('download-formulir.download');
    Route::get('/download-formulir/preview', [DownloadFormulirController::class, 'previewFormulirWisuda'])->name('download-formulir.preview');

    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        
        $sertifikatTypes = [
            'kompetensi'          => 'kompetensi',
            'bahasa-internasional'=> 'bahasa',
            'magang'              => 'magang',
            'pendidikan-karakter' => 'karakter',
            'penghargaan'         => 'penghargaan',
            'organisasi'          => 'organisasi',
        ];

        foreach ($sertifikatTypes as $url => $dbJenis) {
            Route::get("/$url", [App\Http\Controllers\Mahasiswa\SertifikatController::class, 'index'])
                ->defaults('jenis', $dbJenis)
                ->name($url);
                
            Route::post("/$url", [App\Http\Controllers\Mahasiswa\SertifikatController::class, 'store'])
                ->defaults('jenis', $dbJenis)
                ->name("$url.store");
        }
    });


    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::post('/create', [PembayaranController::class, 'createPayment'])->name('create');
        Route::post('/check-status', [PembayaranController::class, 'checkStatus'])->name('check-status');
        Route::post('/verify', [PembayaranController::class, 'verifyPayment'])->name('verify');
    });

});

//
//          KEUANGAN ROUTES
//
Route::prefix('keuangan')->name('keuangan.')->middleware(['auth', 'role:keuangan'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('keuangan.dashboard');
    });
    Route::get('/dashboard', [App\Http\Controllers\Keuangan\DashboardController::class, 'index'])->name('dashboard');
});

//
//          AKADEMIK ROUTES
//
Route::prefix('akademik')->name('akademik.')->middleware(['auth', 'role:akademik'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('akademik.dashboard');
    });
    Route::get('/dashboard', [App\Http\Controllers\Akademik\DashboardController::class, 'index'])->name('dashboard');

    Route::controller(AkademikController::class)->prefix('data-wisudawan')->name('data-wisudawan.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-data', 'getData')->name('get-data');

            Route::get('/{id}/show', 'show')->name('show');

            Route::post('/{id}/verify', 'verify')->name('verify'); // Menggunakan POST

            Route::get('/export', 'exportData')->name('export');
            Route::get('/export-excel', 'exportExcel')->name('export-excel');
            Route::get('/export-pdf', 'exportPdf')->name('export-pdf');
        });

    Route::resource('data-wisudawan', AkademikController::class);
});
