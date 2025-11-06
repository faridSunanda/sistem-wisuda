<?php

use Illuminate\Support\Facades\Route;

// Arahkan Ke Portal Utama
Route::get('/', function () {
    return view('portal.index');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
