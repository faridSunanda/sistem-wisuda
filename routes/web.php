<?php

// Auth
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataWisudawanController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;

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
