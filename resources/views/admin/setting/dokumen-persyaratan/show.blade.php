@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Dokumen Persyaratan</h1>

        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Tipe Dokumen</label>
                    <p class="text-base text-gray-900 font-bold">{{ $dokumen->tipe_dokumen }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Nama Dokumen</label>
                    <p class="text-base text-gray-900 font-bold">{{ $dokumen->nama_dokumen }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Keterangan</label>
                    <p class="text-base text-gray-900 whitespace-pre-line">{{ $dokumen->keterangan }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Berkas</label>
                    @if ($dokumen->berkas)
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-file text-[#435ebe] text-xl"></i>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">File: {{ basename($dokumen->berkas) }}</p>
                                <a href="{{ asset('storage/' . $dokumen->berkas) }}" target="_blank"
                                    class="text-[#435ebe] hover:underline text-sm font-medium">
                                    Lihat/Download Berkas
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-base text-gray-500">-</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Dibuat Pada</label>
                    <p class="text-base text-gray-900">{{ $dokumen->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Diupdate Pada</label>
                    <p class="text-base text-gray-900">{{ $dokumen->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-300">
                <a href="{{ route('admin.setting.dokumen-persyaratan.edit', $dokumen->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.setting.dokumen-persyaratan.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
@endsection
