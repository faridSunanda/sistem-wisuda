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
use App\Http\Controllers\Mahasiswa\SertifikatOrganisasiController;
use App\Http\Controllers\Mahasiswa\SertifikatPenghargaanController;
use App\Http\Controllers\Mahasiswa\SertifikatPendidikanKarakterController;

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
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('data-wisudawan')->name('data-wisudawan.')->group(function () {
        Route::get('/', [DataWisudawanController::class, 'index'])->name('index');
        Route::get('/get-data', [DataWisudawanController::class, 'getData'])->name('get-data');
        Route::get('/export', [DataWisudawanController::class, 'exportData'])->name('export');
        Route::get('/export-excel', [DataWisudawanController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [DataWisudawanController::class, 'exportPdf'])->name('export-pdf');
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
            Route::get('/export-excel', [AlurPendaftaranController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [AlurPendaftaranController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/', [AlurPendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [AlurPendaftaranController::class, 'create'])->name('create');
            Route::post('/', [AlurPendaftaranController::class, 'store'])->name('store');
            Route::get('/{alur_pendaftaran}/edit', [AlurPendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{alur_pendaftaran}', [AlurPendaftaranController::class, 'update'])->name('update');
            Route::delete('/{alur_pendaftaran}', [AlurPendaftaranController::class, 'destroy'])->name('destroy');
            Route::get('/{alur_pendaftaran}', [AlurPendaftaranController::class, 'show'])->name('show');
        });

        // Dokumen Persyaratan
        Route::prefix('dokumen-persyaratan')->name('dokumen-persyaratan.')->group(function () {
            Route::get('/data/get-data', [DokumenPersyaratanController::class, 'getData'])->name('get-data');
            Route::get('/export', [DokumenPersyaratanController::class, 'exportData'])->name('export');
            Route::get('/export-excel', [DokumenPersyaratanController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [DokumenPersyaratanController::class, 'exportPdf'])->name('export-pdf');
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
            Route::get('/export-excel', [JadwalPendaftaranController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [JadwalPendaftaranController::class, 'exportPdf'])->name('export-pdf');
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
            Route::get('/export-excel', [JadwalWisudaController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [JadwalWisudaController::class, 'exportPdf'])->name('export-pdf');
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
            Route::get('/export-excel', [KuotaWisudaController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-pdf', [KuotaWisudaController::class, 'exportPdf'])->name('export-pdf');
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

    Route::prefix('biodata')->name('biodata.')->group(function () {
        // Ini mendaftarkan nama: 'mahasiswa.biodata.edit' (untuk GET)
        Route::get('/', [BiodataController::class, 'edit'])->name('edit');

        // Ini mendaftarkan nama: 'mahasiswa.biodata.update' (untuk POST)
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
