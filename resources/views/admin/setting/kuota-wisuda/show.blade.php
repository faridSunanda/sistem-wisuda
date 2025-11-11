@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Kuota Wisuda</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Periode Wisuda</label>
                    <p class="text-base text-gray-900 font-bold">
                        {{ $kuota->jadwalPendaftaran->tahun_wisuda ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Jumlah Kuota</label>
                    <p class="text-base text-gray-900 font-bold">{{ $kuota->jumlah_kuota }} orang</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Periode Pendaftaran</label>
                    <p class="text-base text-gray-900">
                        {{ $kuota->jadwalPendaftaran->waktu_buka_pendaftaran->format('d F Y') ?? 'N/A' }}
                        s/d
                        {{ $kuota->jadwalPendaftaran->waktu_tutup_pendaftaran->format('d F Y') ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Status Pendaftaran</label>
                    <p class="text-base text-gray-900">
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $kuota->jadwalPendaftaran->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $kuota->jadwalPendaftaran->status ?? 'Tidak Aktif' }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Dibuat Pada</label>
                    <p class="text-base text-gray-900">{{ $kuota->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Diupdate Pada</label>
                    <p class="text-base text-gray-900">{{ $kuota->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-300">
                <a href="{{ route('admin.setting.kuota-wisuda.edit', $kuota->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.setting.kuota-wisuda.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
@endsection
