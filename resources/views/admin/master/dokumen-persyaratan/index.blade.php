@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dokumen Persyaratan</h1>
            <a href="{{ route('admin.master.dokumen-persyaratan.create') }}"
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
                <h2 class="text-lg font-semibold text-gray-900">Daftar Dokumen Persyaratan</h2>
            </div>
            <div class="p-6">
                <table id="dokumenPersyaratanTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-16">
                                No</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Dokumen</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Berkas</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
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
    @vite('resources/css/admin/dokumen-persyaratan.css')
@endpush

@push('scripts')
    <script>
        window.dokumenPersyaratanConfig = {
            getDataUrl: "{{ route('admin.master.dokumen-persyaratan.get-data') }}",
            exportUrl: "{{ route('admin.master.dokumen-persyaratan.export') }}",
            exportExcelUrl: "{{ route('admin.master.dokumen-persyaratan.export-excel') }}",
            exportPdfUrl: "{{ route('admin.master.dokumen-persyaratan.export-pdf') }}",
            detailUrl: "{{ route('admin.master.dokumen-persyaratan.show', ':id') }}",
            editUrl: "{{ route('admin.master.dokumen-persyaratan.edit', ':id') }}",
            deleteUrl: "{{ route('admin.master.dokumen-persyaratan.destroy', ':id') }}",
            resetFiltersBtn: '#resetFiltersBtn',
            exportExcelBtn: '#exportExcelBtn',
            exportPdfBtn: '#exportPdfBtn',
            printBtn: '#printBtn'
        };
    </script>
    @vite('resources/js/admin/master/dokumen-persyaratan/index.js')
@endpush
