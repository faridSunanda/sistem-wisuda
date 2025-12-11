@extends('keuangan.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Data Wisudawan</h1>
        </div>
        <a href="{{ route('keuangan.data-wisudawan.index') }}"
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Tabel Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h2>
        </div>
        <div class="p-6">
            <table id="paymentTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Nominal</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Tanggal Transaksi</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Bank</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">VA Code</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Semester</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    @vite('resources/css/keuangan/data-wisudawan.css')
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        window.paymentDataConfig = {
            getDataUrl: "{{ route('keuangan.data-wisudawan.get-payment-data', $user->id) }}"
        };
    </script>
    @vite('resources/js/keuangan/data-wisudawan/detail.js')
@endpush
