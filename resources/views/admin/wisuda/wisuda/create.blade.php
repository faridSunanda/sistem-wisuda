@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Wisuda</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <form action="{{ route('admin.wisuda.wisuda.store') }}" method="POST">
                @csrf

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                            <div>
                                <p class="font-semibold">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                            <div>
                                <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                    <div>
                        <label for="tahun_wisuda" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Angkatan (Tahun Wisuda) <span class="text-red-500">*</span>
                        </label>
                        <select id="tahun_wisuda" name="tahun_wisuda" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                            <option value="">Pilih Tahun Wisuda</option>
                            @for ($year = date('Y'); $year <= date('Y') + 5; $year++)
                                <option value="{{ $year }}" {{ old('tahun_wisuda') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('tahun_wisuda')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                            <option value="">Pilih Status</option>
                            <option value="Dibuka" {{ old('status') == 'Dibuka' ? 'selected' : '' }}>Dibuka</option>
                            <option value="Ditutup" {{ old('status') == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="waktu_buka_pendaftaran" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Tanggal Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="waktu_buka_pendaftaran" name="waktu_buka_pendaftaran"
                            value="{{ old('waktu_buka_pendaftaran') }}" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                        @error('waktu_buka_pendaftaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="waktu_tutup_pendaftaran" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Tanggal Penutupan <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="waktu_tutup_pendaftaran" name="waktu_tutup_pendaftaran"
                            value="{{ old('waktu_tutup_pendaftaran') }}" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                        @error('waktu_tutup_pendaftaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_kuota" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Kuota Wisudawan
                        </label>
                        <input type="number" id="jumlah_kuota" name="jumlah_kuota" value="{{ old('jumlah_kuota') }}"
                            min="1" step="1"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all"
                            placeholder="Masukkan jumlah kuota (opsional)">
                        @error('jumlah_kuota')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin menambahkan kuota sekarang</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-300 flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                        <i class="fas fa-save"></i>
                        <span>Simpan Data</span>
                    </button>
                    <a href="{{ route('admin.wisuda.wisuda.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all">
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const waktuBuka = document.getElementById('waktu_buka_pendaftaran');
                const waktuTutup = document.getElementById('waktu_tutup_pendaftaran');

                function validateDates() {
                    if (waktuBuka.value && waktuTutup.value) {
                        const buka = new Date(waktuBuka.value);
                        const tutup = new Date(waktuTutup.value);

                        if (tutup <= buka) {
                            waktuTutup.setCustomValidity('Tanggal penutupan harus setelah tanggal pendaftaran');
                        } else {
                            waktuTutup.setCustomValidity('');
                        }
                    }
                }

                waktuBuka.addEventListener('change', validateDates);
                waktuTutup.addEventListener('change', validateDates);
            });
        </script>
    @endpush
@endsection

