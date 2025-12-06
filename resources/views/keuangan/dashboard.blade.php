@extends('keuangan.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Keuangan</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-wave text-green-600"></i>
                    <span>Sudah Bayar</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h3 class="text-xs md:text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                    <span>Belum Bayar</span>
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
                    <span>Menunggu Konfirmasi</span>
                </h3>
            </div>
            <div class="flex items-baseline">
                <p class="text-2xl md:text-3xl font-bold text-gray-900">-</p>
            </div>
        </div>
    </div>
</div>
@endsection
