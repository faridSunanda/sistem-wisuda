@extends('mahasiswa.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <form action="{{ route('mahasiswa.sertifikat.organisasi.store') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
            
            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-6 pb-2 border-b-4 border-[#435ebe] inline-block">Sertifikat Magang</h2>
            
            <div class="flex items-center bg-orange-50 border border-orange-200 rounded-lg px-4 py-3 mb-6">
                <svg class="w-5 h-5 text-orange-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-orange-800">Isi minimal 1 sertifikat dan maksimal 5 sertifikat.</p>
            </div>
            
            <div id="sertifikat-container" class="space-y-6">
                
                {{-- LOOP DATA EXISTING (JIKA ADA ERROR VALIDASI / EDIT) --}}
                @forelse($sertifikats as $index => $sertifikat)
                    <div class="sertifikat-entry p-8 bg-white border border-gray-200 rounded-lg relative transition-shadow duration-200 hover:shadow-md">
                        
                        <button type="button" class="remove-sertifikat absolute -top-2 -right-2 w-8 h-8 bg-white border border-red-300 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="nama_sertifikat_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">Nama Sertifikat</label>
                                <input type="text" name="nama_sertifikat[]" id="nama_sertifikat_{{ $index }}"
                                       value="{{ old('nama_sertifikat.' . $index, $sertifikat->nama_sertifikat) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" 
                                       placeholder="Contoh: Sertifikat Kepanitiaan" required>
                            </div>
                            
                            <div>
                                <label for="tanggal_mulai_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                {{-- PERBAIKAN: Hapus SVG manual --}}
                                <input type="date" name="tanggal_mulai[]" id="tanggal_mulai_{{ $index }}"
                                       value="{{ old('tanggal_mulai.' . $index, $sertifikat->tanggal_mulai instanceof \DateTime ? $sertifikat->tanggal_mulai->format('Y-m-d') : $sertifikat->tanggal_mulai) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label for="tanggal_selesai_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                {{-- PERBAIKAN: Hapus SVG manual --}}
                                <input type="date" name="tanggal_selesai[]" id="tanggal_selesai_{{ $index }}"
                                       value="{{ old('tanggal_selesai.' . $index, $sertifikat->tanggal_selesai instanceof \DateTime ? $sertifikat->tanggal_selesai->format('Y-m-d') : $sertifikat->tanggal_selesai) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                            </div>
                        </div>
                    </div>

                @empty
                    {{-- DEFAULT FORM (INPUT KOSONG PERTAMA) --}}
                    <div class="sertifikat-entry p-8 bg-white border border-gray-200 rounded-lg relative transition-shadow duration-200 hover:shadow-md">
                        
                        <button type="button" class="remove-sertifikat absolute -top-2 -right-2 w-8 h-8 bg-white border border-red-300 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="nama_sertifikat_0" class="block text-sm font-medium text-gray-700 mb-1">Nama Sertifikat</label>
                                <input type="text" name="nama_sertifikat[]" id="nama_sertifikat_0"
                                       value="{{ old('nama_sertifikat.0') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" 
                                       placeholder="Contoh: Sertifikat Kepanitiaan" required>
                            </div>
                            
                            <div>
                                <label for="tanggal_mulai_0" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                {{-- PERBAIKAN: Hapus SVG manual --}}
                                <input type="date" name="tanggal_mulai[]" id="tanggal_mulai_0"
                                       value="{{ old('tanggal_mulai.0') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label for="tanggal_selesai_0" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                {{-- PERBAIKAN: Hapus SVG manual --}}
                                <input type="date" name="tanggal_selesai[]" id="tanggal_selesai_0"
                                       value="{{ old('tanggal_selesai.0') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
                <button type="button" id="add-sertifikat" 
                        class="inline-flex items-center gap-x-2 px-4 py-2 bg-[#435ebe] text-white text-sm font-medium rounded-md shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#435ebe] disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Tambah Sertifikat
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Simpan Data
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    @vite('resources/js/mahasiswa/sertifikat/sertifikat-organisasi/index.js')
@endpush