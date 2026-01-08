@extends('keuangan.layouts.app')

@section('content')
<div class="space-y-4 md:space-y-6 pb-10">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 md:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Detail Data Wisudawan</h1>
        </div>
        <a href="{{ route('keuangan.data-wisudawan.index') }}"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-all">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Biodata Mahasiswa -->
    @if($user->biodata)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Biodata Mahasiswa</h2>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->name_lengkap ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">NIM</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->nim ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">NIK</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->nik ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tempat Lahir</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->tempat_lahir ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                    <p class="text-sm font-semibold text-gray-900">
                        @if($user->biodata->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($user->biodata->tanggal_lahir)->format('d F Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->jenis_kelamin ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">No. Telepon</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->no_telepon ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Rumah</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->alamat_rumah ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fakultas</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->fakultas ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Program Studi</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->program_studi ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tahun Masuk</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->tahun_masuk ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status Mahasiswa</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->biodata->status_mahasiswa ?? '-' }}</p>
                </div>
                @if($user->biodata->kesan_pesan)
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Kesan & Pesan</label>
                    <p class="text-sm text-gray-900 leading-relaxed">{{ $user->biodata->kesan_pesan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Tombol Verifikasi -->
    @if($user->biodata)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                @if($user->biodata->is_bayar && !$user->biodata->is_verified_keuangan)
                {{-- Status: Menunggu Konfirmasi (Kuning) --}}
                <form id="verifyForm" action="{{ route('keuangan.data-wisudawan.verify', $user->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-yellow-300 focus:outline-none">
                        <i class="fas fa-check-double"></i>
                        <span>Verifikasi Pembayaran</span>
                    </button>
                </form>
                @elseif($user->biodata->is_verified_keuangan && !$user->biodata->is_verified_akademik)
                {{-- Status: Sudah Terverifikasi Keuangan, Bisa Proses Wisudawan (Hijau) --}}
                <div class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-white bg-green-600 rounded-lg">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-center">Pembayaran Terverifikasi</span>
                </div>
                <form id="prosesWisudawanForm" action="{{ route('keuangan.data-wisudawan.proses-wisudawan', $user->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-blue-300 focus:outline-none">
                        <i class="fas fa-check-circle"></i>
                        <span>Proses Wisudawan</span>
                    </button>
                </form>
                @elseif($user->biodata->is_verified_akademik)
                {{-- Status: Sudah Terverifikasi Akademik (Biru) --}}
                <div class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-white bg-blue-600 rounded-lg cursor-default">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-center">Wisudawan Sudah Terverifikasi</span>
                </div>
                @elseif(!$user->biodata->is_bayar)
                {{-- Status: Belum Bayar (Merah/Abu-abu) --}}
                <div class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-600 bg-gray-200 rounded-lg cursor-not-allowed">
                    <i class="fas fa-info-circle"></i>
                    <span class="text-center">Menunggu Pembayaran</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Tabel Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Riwayat Pembayaran</h2>
        </div>
        <div class="p-2 sm:p-4 md:p-6 overflow-x-auto">
            <div class="min-w-full">
                <table id="paymentTable" class="w-full min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Nominal</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Tanggal Transaksi</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Bank</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">VA Code</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Semester</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    @vite('resources/css/keuangan/data-wisudawan.css')
@endpush

@push('scripts')
    <script>
        window.paymentDataConfig = {
            getDataUrl: "{{ route('keuangan.data-wisudawan.get-payment-data', $user->id) }}",
            verifyUrl: "{{ route('keuangan.data-wisudawan.verify', $user->id) }}",
            prosesWisudawanUrl: "{{ route('keuangan.data-wisudawan.proses-wisudawan', $user->id) }}"
        };
    </script>
    @vite('resources/js/keuangan/data-wisudawan/detail.js')
@endpush
