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
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Data Wisudawan</h1>
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

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Filter Data</h3>
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full sm:w-auto">
                    <label for="filterFakultas" class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                    <div class="relative">
                        <select id="filterFakultas"
                            class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                            <option value="">Semua Fakultas</option>
                            <option value="Teknik">Fakultas Teknik</option>
                            <option value="Hukum">Fakultas Hukum</option>
                            <option value="Ekonomi">Fakultas Ekonomi</option>
                            <option value="Kedokteran">Fakultas Kedokteran</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-1 w-full sm:w-auto">
                    <label for="filterProdi" class="block text-sm font-medium text-gray-700 mb-2">Prodi</label>
                    <div class="relative">
                        <select id="filterProdi"
                            class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                            <option value="">Semua Prodi</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Teknik Komputer">Teknik Komputer</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-1 w-full sm:w-auto">
                    <label for="filterTahunMasuk" class="block text-sm font-medium text-gray-700 mb-2">Tahun Masuk</label>
                    <div class="relative">
                        <select id="filterTahunMasuk"
                            class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                            <option value="">Semua Tahun</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-1 w-full sm:w-auto">
                    <label for="filterJenjang" class="block text-sm font-medium text-gray-700 mb-2">Jenjang</label>
                    <div class="relative">
                        <select id="filterJenjang"
                            class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                            <option value="">Semua Jenjang</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
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
    @vite('resources/css/admin/data-wisudawan.css')
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        window.dataWisudawanConfig = {
            getDataUrl: "{{ route('admin.data-wisudawan.get-data') }}",
            exportUrl: "{{ route('admin.data-wisudawan.export') }}",
            exportExcelUrl: "{{ route('admin.data-wisudawan.export-excel') }}",
            exportPdfUrl: "{{ route('admin.data-wisudawan.export-pdf') }}",
            detailUrl: "{{ route('admin.data-wisudawan.show', ':id') }}",
            editUrl: "{{ route('admin.data-wisudawan.edit', ':id') }}",
            deleteUrl: "{{ route('admin.data-wisudawan.destroy', ':id') }}",
            resetFiltersBtn: '#resetFiltersBtn',
            exportExcelBtn: '#exportExcelBtn',
            exportPdfBtn: '#exportPdfBtn',
            printBtn: '#printBtn',
            filterSelectors: '#filterFakultas, #filterProdi, #filterTahunMasuk, #filterJenjang'
        };
    </script>
    @vite('resources/js/admin/data-wisudawan/index.js')
@endpush
