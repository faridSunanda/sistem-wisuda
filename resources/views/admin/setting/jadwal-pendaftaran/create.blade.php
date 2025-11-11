@extends('admin.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Jadwal Pendaftaran Wisuda</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <form action="{{ route('admin.setting.jadwal-pendaftaran.store') }}" method="POST">
                @csrf

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
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
                            Tahun Wisuda <span class="text-red-500">*</span>
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
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                            <option value="">Pilih Status</option>
                            <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label for="waktu_buka_pendaftaran" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Waktu Buka Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="waktu_buka_pendaftaran" name="waktu_buka_pendaftaran"
                            value="{{ old('waktu_buka_pendaftaran') }}" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label for="waktu_tutup_pendaftaran" class="block text-sm font-normal text-gray-600 mb-1.5">
                            Waktu Tutup Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="waktu_tutup_pendaftaran" name="waktu_tutup_pendaftaran"
                            value="{{ old('waktu_tutup_pendaftaran') }}" required
                            class="w-full px-4 py-2.5 text-sm bg-gray-50 border-0 rounded-lg text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:bg-white transition-all">
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-300 flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-[#435ebe] text-white hover:bg-[#3a52a8] rounded-lg transition-colors">
                        <i class="fas fa-save"></i>
                        <span>Simpan Jadwal</span>
                    </button>
                    <a href="{{ route('admin.setting.jadwal-pendaftaran.index') }}"
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
                            waktuTutup.setCustomValidity('Waktu tutup harus setelah waktu buka');
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
