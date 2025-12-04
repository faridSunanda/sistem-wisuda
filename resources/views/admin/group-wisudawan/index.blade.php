@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        {{-- Header Judul dan Tombol --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Group Wisudawan</h1>
            <div class="flex items-center gap-3">
                <button id="downloadPptBtn" type="button"
                    class="flex items-center gap-2 px-4 py-2 text-sm bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-all">
                    <i class="fas fa-download"></i>
                    <span>Download PPT</span>
                </button>
            </div>
        </div>

        {{-- Filter dan Pindahkan Ke --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Pindahkan Ke</h3>
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full sm:w-auto">
                    <label for="targetGroupId" class="block text-sm font-medium text-gray-700 mb-2">Group</label>
                    <div class="relative">
                        <select id="targetGroupId" class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer select-dropdown">
                            <option value="">Pilih Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="toggleGroupDropdown" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all pointer-events-auto z-10">
                            <i class="fas fa-chevron-up text-xs transition-transform duration-200" id="groupChevron"></i>
                        </button>
                    </div>
                </div>
                <div class="flex-1 w-full sm:w-auto">
                    <label for="targetSesiId" class="block text-sm font-medium text-gray-700 mb-2">Sesi</label>
                    <div class="relative">
                        <select id="targetSesiId" class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer select-dropdown">
                            <option value="">Pilih Sesi</option>
                            @foreach($sesis as $sesi)
                                <option value="{{ $sesi->id }}">{{ $sesi->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="toggleSesiDropdown" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all pointer-events-auto z-10">
                            <i class="fas fa-chevron-up text-xs transition-transform duration-200" id="sesiChevron"></i>
                        </button>
                    </div>
                </div>
                <button id="pindahkanBtn" type="button"
                    class="w-full sm:w-auto flex-shrink-0 px-6 py-2.5 text-sm font-medium bg-[#435ebe] text-white rounded-lg hover:bg-[#3a52a8] transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    <span>Pindahkan</span>
                </button>
            </div>
        </div>

        {{-- Tabel Download PPT --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Wisudawan</h2>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4 text-[#435ebe] border-gray-300 rounded focus:ring-[#435ebe]">
                        <span>Pilih Semua</span>
                    </label>
                </div>
            </div>
            <div class="p-6">
                <table id="downloadPptTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider w-12">
                                <input type="checkbox" id="selectAllHeader" class="w-4 h-4 text-[#435ebe] border-gray-300 rounded focus:ring-[#435ebe]">
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                No Urut</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Lengkap</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                NIM</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Angkatan</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Group</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Sesi</th>
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

    {{-- Modal Download PPT --}}
    <div id="downloadPptModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-md">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
            {{-- Modal Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Download Data Wisudawan</h2>
                <div class="mt-2 h-1 bg-[#435ebe] rounded"></div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-4">
                {{-- Info Box --}}
                <div id="downloadInfoBox" class="mb-6 p-4 bg-blue-50 border-l-4 border-[#435ebe] rounded flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-[#435ebe] mt-0.5"></i>
                    <span id="downloadInfoText" class="text-[#435ebe] font-medium">Download 0 data wisudawan</span>
                </div>

                {{-- Dropdowns --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="downloadPosisiKanan" class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                        <div class="relative">
                            <select id="downloadPosisiKanan" class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                                <option value="kanan">Kanan</option>
                                <option value="kiri">Kiri</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="downloadUrutan" class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <div class="relative">
                            <select id="downloadUrutan" class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer">
                                <option value="pertama">Pertama</option>
                                <option value="kedua">Kedua</option>
                                <option value="ketiga">Ketiga</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button id="closeDownloadModalBtn" type="button" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                    <i class="fas fa-times"></i>
                    <span>Close</span>
                </button>
                <button id="confirmDownloadBtn" type="button" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-[#435ebe] hover:bg-[#3a52a8] rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-cloud-download-alt"></i>
                    <span>Download</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    @vite('resources/css/admin/group-wisudawan.css')
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.downloadPptConfig = {
            getDataUrl: "{{ route('admin.group-wisudawan.get-data') }}",
            downloadPptUrl: "{{ route('admin.group-wisudawan.download') }}",
            pindahkanKeUrl: "{{ route('admin.group-wisudawan.pindahkan-ke') }}",
            detailUrl: "{{ route('admin.group-wisudawan.show', ':id') }}",
            editUrl: "{{ route('admin.group-wisudawan.edit', ':id') }}",
            deleteUrl: "{{ route('admin.group-wisudawan.destroy', ':id') }}"
        };
    </script>
    @vite('resources/js/admin/group-wisudawan/index.js')
@endpush

