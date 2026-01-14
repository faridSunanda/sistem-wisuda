@extends('mahasiswa.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Pembayaran Wisuda</h1>
        <p class="text-sm md:text-base text-gray-600 mt-2">Lakukan pembayaran untuk melanjutkan proses pendaftaran wisuda</p>
    </div>

    <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-lg p-4 md:p-5 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-orange-900 mb-1">Informasi Pembayaran</h3>
                <p class="text-sm text-orange-800">Lakukan pembayaran sebesar <strong class="text-lg">Rp{{ number_format($tagihan, 0, ',', '.') }}</strong> untuk melanjutkan tahap pendaftaran wisuda.</p>
            </div>
        </div>
                    </div>
                    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-[#435ebe] px-4 md:px-6 py-4">
            <h2 class="text-lg md:text-xl font-semibold text-white">Ringkasan Pembayaran</h2>
                    </div>
                    
        <div id="payment-data" 
             data-briva-number="{{ $brivaNumber }}"
             data-check-status-url="{{ route('mahasiswa.pembayaran.check-status') }}"
             data-csrf-token="{{ csrf_token() }}"
             style="display: none;"></div>
        
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-xs md:text-sm font-medium text-gray-600 mb-2">Total Tagihan</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-900">Rp{{ number_format($tagihan, 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-xs md:text-sm font-medium text-gray-600 mb-2">Nomor BRIVA</p>
                    <div class="flex items-center gap-2">
                        <p class="text-lg md:text-xl font-mono font-semibold text-gray-900">{{ $brivaNumber }}</p>
                        <button id="copy-briva-btn" class="flex items-center gap-1 px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors">
                            <i class="fas fa-copy text-xs"></i>
                            <span>Copy</span>
                        </button>
                    </div>
                    <div id="copy-success" class="mt-2 text-xs text-green-600 hidden">
                        <i class="fas fa-check-circle mr-1"></i>
                        Kode BRIVA berhasil disalin!
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex items-center justify-center">
                    <button id="check-status-btn" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        <span>Cek Status</span>
                    </button>
                </div>
            </div>
                        </div>
                    </div>
                    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-[#435ebe] px-4 md:px-6 py-4">
            <h2 class="text-lg md:text-xl font-semibold text-white">Tutorial Pembayaran BRIVA</h2>
            <p class="text-sm text-indigo-100 mt-1">Pilih metode pembayaran, lalu ikuti langkah-langkahnya. Simpan bukti/struk setelah bayar.</p>
                        </div>
        
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 mb-6">
                <div class="metode-pembayaran border-2 border-gray-300 rounded-lg p-3 md:p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group" data-method="brimo">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                        <i class="fas fa-mobile-alt text-blue-600 text-base md:text-lg"></i>
                    </div>
                    <p class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-blue-700">BRImo</p>
                    <p class="text-xs text-gray-500 mt-1">m-banking BRI</p>
                    </div>
                    
                <div class="metode-pembayaran border-2 border-gray-300 rounded-lg p-3 md:p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group" data-method="atm">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                        <i class="fas fa-credit-card text-blue-600 text-base md:text-lg"></i>
                        </div>
                    <p class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-blue-700">ATM BRI</p>
                    </div>
                    
                <div class="metode-pembayaran border-2 border-gray-300 rounded-lg p-3 md:p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group" data-method="ibanking">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                        <i class="fas fa-globe text-blue-600 text-base md:text-lg"></i>
                        </div>
                    <p class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-blue-700">Internet Banking</p>
                    <p class="text-xs text-gray-500 mt-1">BRI</p>
                    </div>
                    
                <div class="metode-pembayaran border-2 border-gray-300 rounded-lg p-3 md:p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group" data-method="bank-lain">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                        <i class="fas fa-university text-blue-600 text-base md:text-lg"></i>
                    </div>
                    <p class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-blue-700">Bank Lain</p>
                    <p class="text-xs text-gray-500 mt-1">m-banking/ATM</p>
                </div>
                
                <div class="metode-pembayaran border-2 border-gray-300 rounded-lg p-3 md:p-4 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group" data-method="teller">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors">
                        <i class="fas fa-user-tie text-blue-600 text-base md:text-lg"></i>
                    </div>
                    <p class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-blue-700">Teller</p>
                    <p class="text-xs text-gray-500 mt-1">Bank</p>
                </div>
            </div>
            
            <div id="tutorial-content" class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-lg p-4 md:p-6">
                <h3 id="tutorial-title" class="text-base md:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    <span>Pilih Metode Pembayaran</span>
                </h3>
                <div id="tutorial-steps" class="text-gray-700 space-y-3">
                    <p class="text-center text-gray-500 py-4">Klik salah satu metode pembayaran di atas untuk melihat panduan lengkap</p>
                </div>
                <div id="tutorial-notes" class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg hidden">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                        <div class="flex-1">
                        <h4 class="font-semibold text-yellow-800 mb-2">Catatan Penting:</h4>
                            <ul id="notes-list" class="list-disc list-inside text-sm text-yellow-700 space-y-1"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="toast-container" class="fixed top-4 right-2 md:right-4 z-50 space-y-2 max-w-sm w-full md:max-w-md"></div>
@endsection

@push('styles')
<style>
    .metode-pembayaran.active {
        border-color: #3b82f6;
        background-color: #eff6ff;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
    }
    .metode-pembayaran.active .text-gray-700 {
        color: #1e40af;
    }
</style>
@endpush

@push('scripts')
@vite('resources/js/mahasiswa/pembayaran/index.js')
@endpush
