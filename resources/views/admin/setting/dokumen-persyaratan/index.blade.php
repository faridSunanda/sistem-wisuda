@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        {{-- @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif --}}

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dokumen Persyaratan</h1>
            <a href="{{ route('admin.setting.dokumen-persyaratan.create') }}"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah Dokumen</span>
            </a>
        </div>

        <div class="flex items-center gap-3 mb-6">
            <button id="exportExcelBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-all shadow-sm hover:shadow">
                <i class="fas fa-file-excel text-green-600"></i>
                <span>Excel</span>
            </button>
            <button id="exportPdfBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition-all shadow-sm hover:shadow">
                <i class="fas fa-file-pdf text-red-600"></i>
                <span>PDF</span>
            </button>
            <button id="printBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 hover:bg-gray-50 rounded-lg transition-all shadow-sm hover:shadow">
                <i class="fas fa-print text-gray-600"></i>
                <span>Print</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Dokumen Persyaratan</h2>
            </div>
            <div class="p-6">
                <table id="dokumenPersyaratanTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Tipe Dokumen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Dokumen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Berkas</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
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
            getDataUrl: "{{ route('admin.setting.dokumen-persyaratan.get-data') }}",
            exportUrl: "{{ route('admin.setting.dokumen-persyaratan.export') }}",
            detailUrl: "{{ route('admin.setting.dokumen-persyaratan.show', ':id') }}",
            editUrl: "{{ route('admin.setting.dokumen-persyaratan.edit', ':id') }}",
            deleteUrl: "{{ route('admin.setting.dokumen-persyaratan.destroy', ':id') }}",
            resetFiltersBtn: '#resetFiltersBtn',
            exportExcelBtn: '#exportExcelBtn',
            exportPdfBtn: '#exportPdfBtn',
            printBtn: '#printBtn'
        };
    </script>
    @vite('resources/js/admin/setting/dokumen-persyaratan/index.js')
@endpush
