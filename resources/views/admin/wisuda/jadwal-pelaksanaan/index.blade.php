@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        {{-- Header Judul dan Tombol Tambah --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Jadwal Wisuda</h1>
            <a href="{{ route('admin.wisuda.jadwal-pelaksanaan.create') }}"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-all">
                <i class="fas fa-plus"></i>
                <span>Tambah</span>
            </a>
        </div>

        {{-- Tombol Export --}}
        <div class="flex items-center gap-3 mb-6">
            <button id="exportExcelBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 rounded-lg transition-all shadow-sm hover:shadow btn-export btn-export-excel">
                <i class="fas fa-file-excel text-green-600"></i>
                <span>Excel</span>
            </button>
            <button id="exportPdfBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 rounded-lg transition-all shadow-sm hover:shadow btn-export btn-export-pdf">
                <i class="fas fa-file-pdf text-red-600"></i>
                <span>PDF</span>
            </button>
            <button id="printBtn" type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 rounded-lg transition-all shadow-sm hover:shadow btn-export btn-export-print">
                <i class="fas fa-print text-gray-600"></i>
                <span>Print</span>
            </button>
        </div>

        {{-- Tabel Jadwal Pelaksanaan --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Jadwal Pelaksanaan Wisuda</h2>
            </div>
            <div class="p-6">
                <table id="jadwalPelaksanaanTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                No</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Kegiatan</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Sesi</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Waktu Pelaksanaan</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Tanggal Pelaksanaan</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Tempat Pelaksanaan</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Keterangan</th>
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
    @vite('resources/css/admin/jadwal-pelaksanaan.css')
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.jadwalPelaksanaanConfig = {
            getDataUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.get-data') }}",
            exportUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.export') }}",
            exportExcelUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.export-excel') }}",
            exportPdfUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.export-pdf') }}",
            detailUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.show', ':id') }}",
            editUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.edit', ':id') }}",
            deleteUrl: "{{ route('admin.wisuda.jadwal-pelaksanaan.destroy', ':id') }}",
            exportExcelBtn: '#exportExcelBtn',
            exportPdfBtn: '#exportPdfBtn',
            printBtn: '#printBtn'
        };
    </script>
    @vite('resources/js/admin/wisuda/jadwal-pelaksanaan/index.js')
@endpush

