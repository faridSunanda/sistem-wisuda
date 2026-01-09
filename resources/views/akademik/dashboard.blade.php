@extends('akademik.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Akademik</h1>
        <p class="mt-2 text-sm md:text-base text-gray-600">Selamat datang di dashboard akademik. Berikut adalah ringkasan statistik mahasiswa dan proses verifikasi wisudawan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-users text-blue-600"></i>
                    <span>Total Mahasiswa</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-green-600"></i>
                    <span>Wisudawan Terverifikasi</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-clock text-yellow-600"></i>
                    <span>Menunggu Verifikasi</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>
    </div>
</div>
@endsection
