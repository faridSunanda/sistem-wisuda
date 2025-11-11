@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Jadwal Wisuda</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Nama Kegiatan</label>
                    <p class="text-base text-gray-900 font-bold">{{ $jadwal->nama_kegiatan }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Waktu Pelaksanaan</label>
                    <p class="text-base text-gray-900 font-bold">
                        {{ \Carbon\Carbon::parse($jadwal->waktu_pelaksanaan)->format('d F Y H:i') }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Tempat Pelaksanaan</label>
                    <p class="text-base text-gray-900 font-bold">{{ $jadwal->tempat_pelaksanaan }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Keterangan</label>
                    <p class="text-base text-gray-900 whitespace-pre-line">{{ $jadwal->keterangan }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Dibuat Pada</label>
                    <p class="text-base text-gray-900">{{ $jadwal->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Diupdate Pada</label>
                    <p class="text-base text-gray-900">{{ $jadwal->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-300">
                <a href="{{ route('admin.setting.jadwal-wisuda.edit', $jadwal->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.setting.jadwal-wisuda.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
@endsection
