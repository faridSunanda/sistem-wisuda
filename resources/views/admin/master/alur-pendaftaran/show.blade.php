@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Alur Pendaftaran</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">No. Urut</label>
                    <p class="text-base text-gray-900 font-bold">{{ $alurPendaftaran->no_urut }}</p>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Judul</label>
                    <p class="text-base text-gray-900 font-bold">{{ $alurPendaftaran->judul }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Keterangan</label>
                    <p class="text-base text-gray-900 whitespace-pre-line">{{ $alurPendaftaran->keterangan }}</p>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Dibuat Pada</label>
                    <p class="text-base text-gray-900">{{ $alurPendaftaran->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Diupdate Pada</label>
                    <p class="text-base text-gray-900">{{ $alurPendaftaran->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-300">
                <a href="{{ route('admin.master.alur-pendaftaran.edit', $alurPendaftaran->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.master.alur-pendaftaran.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
@endsection
