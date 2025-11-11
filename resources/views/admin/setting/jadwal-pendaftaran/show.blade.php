@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Jadwal Pendaftaran Wisuda</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Tahun Wisuda</label>
                    <p class="text-base text-gray-900 font-bold">{{ $jadwal->tahun_wisuda }}</p>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Status</label>
                    <span
                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full
                        @if ($jadwal->status == 'Aktif') bg-green-100 text-green-800
                        @elseif($jadwal->status == 'Draft') bg-yellow-100 text-yellow-800
                        @elseif($jadwal->status == 'Nonaktif') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ $jadwal->status }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Waktu Buka Pendaftaran</label>
                    <p class="text-base text-gray-900 font-bold">
                        {{ \Carbon\Carbon::parse($jadwal->waktu_buka_pendaftaran)->format('d F Y H:i') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Waktu Tutup Pendaftaran</label>
                    <p class="text-base text-gray-900 font-bold">
                        {{ \Carbon\Carbon::parse($jadwal->waktu_tutup_pendaftaran)->format('d F Y H:i') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Durasi Pendaftaran</label>
                    @php
                        $buka = \Carbon\Carbon::parse($jadwal->waktu_buka_pendaftaran);
                        $tutup = \Carbon\Carbon::parse($jadwal->waktu_tutup_pendaftaran);
                        $durasi = $buka->diff($tutup);
                    @endphp
                    <p class="text-base text-gray-900">{{ $durasi->days }} hari, {{ $durasi->h }} jam</p>
                </div>

                <div>
                    <label class="block text-sm font-normal text-gray-600 mb-1.5">Status Waktu</label>
                    @php
                        $sekarang = now();
                        $buka = \Carbon\Carbon::parse($jadwal->waktu_buka_pendaftaran);
                        $tutup = \Carbon\Carbon::parse($jadwal->waktu_tutup_pendaftaran);
                    @endphp
                    @if ($sekarang < $buka)
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Belum Dimulai
                        </span>
                    @elseif($sekarang >= $buka && $sekarang <= $tutup)
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Sedang Berlangsung
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Sudah Berakhir
                        </span>
                    @endif
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
                <a href="{{ route('admin.setting.jadwal-pendaftaran.edit', $jadwal->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit Jadwal</span>
                </a>

                <a href="{{ route('admin.setting.jadwal-pendaftaran.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
@endsection
