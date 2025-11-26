@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Wisuda</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <form action="{{ route('admin.wisuda.wisuda.store') }}" method="POST">
                @csrf

                {{-- Alert Success --}}
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                {{-- Alert Error --}}
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

                {{-- Validation Errors --}}
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
                        <label for="angkatan" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Nama Angkatan / Wisuda <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" required
                            placeholder="Contoh: Wisuda Periode I Tahun 2024"
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                        @error('angkatan')
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
                            <option value="dibuka" {{ old('status') == 'dibuka' ? 'selected' : '' }}>Dibuka</option>
                            <option value="ditutup" {{ old('status') == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_pendaftaran" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Tanggal Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="tanggal_pendaftaran" name="tanggal_pendaftaran"
                            value="{{ old('tanggal_pendaftaran') }}" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                        @error('tanggal_pendaftaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_penutupan" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Tanggal Penutupan <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="tanggal_penutupan" name="tanggal_penutupan"
                            value="{{ old('tanggal_penutupan') }}" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                        @error('tanggal_penutupan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kuota_wisudawan" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Kuota Wisudawan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="kuota_wisudawan" name="kuota_wisudawan"
                            value="{{ old('kuota_wisudawan') }}" min="1" step="1" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all"
                            placeholder="Masukkan jumlah kuota">
                        @error('kuota_wisudawan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                const waktuBuka = document.getElementById('tanggal_pendaftaran');
                const waktuTutup = document.getElementById('tanggal_penutupan');

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
