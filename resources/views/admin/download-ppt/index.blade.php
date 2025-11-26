@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        {{-- Header Judul dan Tombol --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Download Slide PowerPoint</h1>
            <div class="flex items-center gap-3">
                <button id="previewPptBtn" type="button"
                    class="flex items-center gap-2 px-4 py-2 text-sm bg-white text-gray-700 rounded-lg transition-all shadow-sm hover:shadow border border-gray-200 hover:border-[#435ebe] hover:bg-[#435ebe]/10 hover:text-[#435ebe]">
                    <i class="fas fa-eye text-[#435ebe]"></i>
                    <span>Lihat Preview PPT</span>
                </button>
                <button id="downloadPptBtn" type="button"
                    class="flex items-center gap-2 px-4 py-2 text-sm bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-all">
                    <i class="fas fa-download"></i>
                    <span>Download PPT</span>
                </button>
            </div>
        </div>

        {{-- Filter dan Pindahkan Ke --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-4">Pindahkan Ke</label>
                    <div class="flex gap-3 items-end">
                        <div class="flex-1 relative">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Group:</label>
                            <div class="relative">
                                <select id="targetGroupId" class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer select-dropdown" style="pointer-events: auto;">
                                    <option value="">Pilih Group</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="toggleGroupDropdown" class="absolute right-2.5 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all pointer-events-auto z-10">
                                    <i class="fas fa-chevron-up text-xs transition-transform duration-200" id="groupChevron"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 relative">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Sesi:</label>
                            <div class="relative">
                                <select id="targetSesiId" class="w-full px-4 py-2.5 pr-10 text-sm bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-[#435ebe] transition-all appearance-none cursor-pointer select-dropdown" style="pointer-events: auto;">
                                    <option value="">Pilih Sesi</option>
                                    @foreach($sesis as $sesi)
                                        <option value="{{ $sesi->id }}">{{ $sesi->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="toggleSesiDropdown" class="absolute right-2.5 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all pointer-events-auto z-10">
                                    <i class="fas fa-chevron-up text-xs transition-transform duration-200" id="sesiChevron"></i>
                                </button>
                            </div>
                        </div>
                        <button id="pindahkanBtn" type="button"
                            class="flex-shrink-0 px-4 py-2.5 text-sm bg-[#435ebe] text-white rounded-lg hover:bg-[#3a52a8] transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
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
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-12">
                                <input type="checkbox" id="selectAllHeader" class="w-4 h-4 text-[#435ebe] border-gray-300 rounded focus:ring-[#435ebe]">
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                No Urut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Lengkap</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                NIM</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Angkatan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Group</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Sesi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Jenjang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Fakultas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Prodi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
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
    @vite('resources/css/admin/download-ppt.css')
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.downloadPptConfig = {
            getDataUrl: "{{ route('admin.download-ppt.get-data') }}",
            previewPptUrl: "{{ route('admin.download-ppt.preview') }}",
            downloadPptUrl: "{{ route('admin.download-ppt.download') }}",
            pindahkanKeUrl: "{{ route('admin.download-ppt.pindahkan-ke') }}",
            detailUrl: "{{ route('admin.download-ppt.show', ':id') }}",
            editUrl: "{{ route('admin.download-ppt.edit', ':id') }}",
            deleteUrl: "{{ route('admin.download-ppt.destroy', ':id') }}"
        };
    </script>
    @vite('resources/js/admin/download-ppt/index.js')
@endpush

