@extends('keuangan.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Data Wisudawan</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Filter Data</h3>
        <div class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-auto">
                <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="relative">
                    <select id="filterStatus"
                        class="w-full sm:w-48 px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                        <option value="sudah_bayar">Sudah Bayar</option>
                        <option value="terverifikasi">Terverifikasi</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>
            </div>
            <div id="konfirmasiSemuaContainer" class="w-full sm:w-auto flex-shrink-0 hidden">
                <button id="konfirmasiSemuaBtn" type="button" onclick="konfirmasiSemuaPembayaran('{{ route('keuangan.data-wisudawan.verify-all') }}')"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium bg-yellow-600 text-white hover:bg-yellow-700 rounded-lg transition-all shadow-sm hover:shadow">
                    <i class="fas fa-check-double"></i>
                    <span>Konfirmasi Semua</span>
                </button>
            </div>
            <div id="prosesSemuaContainer" class="w-full sm:w-auto flex-shrink-0 hidden">
                <button id="prosesSemuaBtn" type="button" onclick="prosesSemuaWisudawan('{{ route('keuangan.data-wisudawan.proses-wisudawan-all') }}')"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium bg-green-600 text-white hover:bg-green-700 rounded-lg transition-all shadow-sm hover:shadow">
                    <i class="fas fa-check-double"></i>
                    <span>Proses Wisudawan Semua</span>
                </button>
            </div>
            <div class="w-full sm:w-auto flex-shrink-0">
                <button id="resetFiltersBtn" type="button"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow">
                    <i class="fas fa-rotate-right"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Wisudawan</h2>
        </div>
        <div class="p-6">
            <table id="dataWisudawanTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            No</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Nama Lengkap</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            NIM</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Tahun Masuk</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Jenjang</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Fakultas</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Prodi</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Kode Briva</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Pembayaran</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Semester</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Bank</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Tanggal Transaksi</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi</th>
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
        window.dataWisudawanConfig = {
            getDataUrl: "{{ route('keuangan.data-wisudawan.get-data') }}",
            resetFiltersBtn: '#resetFiltersBtn',
            filterSelectors: '#filterStatus',
            konfirmasiSemuaUrl: "{{ route('keuangan.data-wisudawan.verify-all') }}"
        };
    </script>
    @vite('resources/js/keuangan/data-wisudawan/index.js')
@endpush

