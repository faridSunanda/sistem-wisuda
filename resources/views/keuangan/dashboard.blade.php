@extends('keuangan.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Keuangan</h1>
        <p class="mt-2 text-sm md:text-base text-gray-600">Selamat datang di dashboard keuangan. Berikut adalah ringkasan statistik pembayaran wisuda wisudawan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                    <span>Belum Bayar</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['belum_bayar'] ?? 0 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $belumBayarPercent = (($stats['belum_bayar'] ?? 0) / (($stats['total'] ?? 0) > 0 ? ($stats['total'] ?? 1) : 1)) * 100;
                    @endphp
                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $belumBayarPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-clock text-yellow-600"></i>
                    <span>Menunggu Konfirmasi</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['menunggu_konfirmasi'] ?? 0 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $menungguPercent = (($stats['menunggu_konfirmasi'] ?? 0) / (($stats['total'] ?? 0) > 0 ? ($stats['total'] ?? 1) : 1)) * 100;
                    @endphp
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $menungguPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-wave text-green-600"></i>
                    <span>Sudah Bayar</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['sudah_bayar'] ?? 0 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $sudahBayarPercent = (($stats['sudah_bayar'] ?? 0) / (($stats['total'] ?? 0) > 0 ? ($stats['total'] ?? 1) : 1)) * 100;
                    @endphp
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $sudahBayarPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-users text-blue-600"></i>
                    <span>Total</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="mt-3 md:mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
