<?php

use Illuminate\Support\Facades\Route;

// Arahkan Ke Portal Utama
Route::get('/', function () {
    return view('welcome');
});
