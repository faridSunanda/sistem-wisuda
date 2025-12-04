@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Data Sesi</h1>
            <a href="{{ route('admin.master.sesi.create') }}"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="fas fa-plus"></i>
                <span>Tambah</span>
            </a>
        </div>

        <div class="flex items-center gap-3 mb-6">
            <button id="exportExcelBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-all shadow-sm hover:shadow border border-gray-200 hover:border-green-200">
                <i class="fas fa-file-excel text-green-600"></i>
                <span>Excel</span>
            </button>
            <button id="exportPdfBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition-all shadow-sm hover:shadow border border-gray-200 hover:border-red-200">
                <i class="fas fa-file-pdf text-red-600"></i>
                <span>PDF</span>
            </button>
            <button id="printBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 hover:bg-gray-50 rounded-lg transition-all shadow-sm hover:shadow border border-gray-200">
                <i class="fas fa-print text-gray-600"></i>
                <span>Print</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Sesi Wisuda</h2>
            </div>
            <div class="p-6">
                <table id="sesiTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                No</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Sesi</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    @vite('resources/css/admin/sesi.css')
@endpush

@push('scripts')
    <script>
        window.sesiConfig = {
            getDataUrl: "{{ route('admin.master.sesi.get-data') }}",
            exportUrl: "{{ route('admin.master.sesi.export') }}",
            exportExcelUrl: "{{ route('admin.master.sesi.export-excel') }}",
            exportPdfUrl: "{{ route('admin.master.sesi.export-pdf') }}",
            detailUrl: "{{ route('admin.master.sesi.show', ':id') }}",
            editUrl: "{{ route('admin.master.sesi.edit', ':id') }}",
            deleteUrl: "{{ route('admin.master.sesi.destroy', ':id') }}",
            resetFiltersBtn: '#resetFiltersBtn',
            exportExcelBtn: '#exportExcelBtn',
            exportPdfBtn: '#exportPdfBtn',
            printBtn: '#printBtn'
        };
    </script>
    @vite('resources/js/admin/master/sesi/index.js')
@endpush
