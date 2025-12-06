@extends('mahasiswa.layouts.app')

@push('styles')
    <style>
        .input-field {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto pb-10">

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <p class="font-bold">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (config('services.biodata.api_url'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-900">Sinkronisasi dengan API</p>
                        <p class="text-xs text-blue-700 mt-1">Klik tombol di bawah untuk menyinkronkan data dari API</p>
                    </div>
                    <form action="{{ route('mahasiswa.biodata.sync') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                            <i class="fa-solid fa-sync-alt mr-2"></i>Sinkronisasi
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <form action="{{ route('mahasiswa.biodata.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Hidden wisuda_id -->
            <!-- Info Wisuda Aktif -->
            @if (isset($wisudaAktif))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-blue-900">Periode Wisuda: {{ $wisudaAktif->angkatan }}</h3>
                            <p class="text-sm text-blue-700">
                                Pendaftaran: {{ \Carbon\Carbon::parse($wisudaAktif->tanggal_pendaftaran)->format('d F Y') }}
                                -
                                {{ \Carbon\Carbon::parse($wisudaAktif->tanggal_penutupan)->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-red-900">Tidak Ada Periode Wisuda yang Dibuka</h3>
                            <p class="text-sm text-red-700">Silakan hubungi admin untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            @endif


            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 md:mb-6">Foto Profil</h2>
                <div class="flex flex-col sm:flex-row items-start gap-4 md:gap-6">
                    <div class="flex-shrink-0 mx-auto sm:mx-0">
                        <div
                            class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg bg-gray-100 border-2 border-gray-300 flex items-center justify-center overflow-hidden">
                            <img id="preview-foto"
                                src="{{ $biodata && $biodata->foto_profile ? asset('storage/' . $biodata->foto_profile) : '' }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover {{ !($biodata && $biodata->foto_profile) ? 'hidden' : '' }}">

                            <svg id="placeholder-icon"
                                class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 {{ $biodata && $biodata->foto_profile ? 'hidden' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block">
                            <span class="sr-only">Upload Foto Profil</span>
                            <input type="file" id="foto-profil" name="foto_profile" accept="image/jpeg,image/png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </label>
                        <p class="mt-2 text-xs md:text-sm text-gray-500">Format yang didukung: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 md:mb-6">Biodata Diri</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name_lengkap" class="input-field"
                                value="{{ old('name_lengkap', Auth::user()->name_lengkap) }}" readonly>
                            <p class="text-xs text-gray-500 mt-1">*Nama tidak dapat diubah</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" class="input-field"
                                value="{{ old('email', Auth::user()->email) }}" readonly>
                            <p class="text-xs text-gray-500 mt-1">*Email tidak dapat diubah</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="input-field"
                                value="{{ old('tempat_lahir', $biodata?->tempat_lahir) }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="input-field"
                                value="{{ old('tanggal_lahir', $biodata?->tanggal_lahir?->format('Y-m-d')) }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                            <input type="text" name="nik" class="input-field"
                                value="{{ old('nik', $biodata?->nik) }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                            <input type="text" name="nim" class="input-field"
                                value="{{ old('nim', $biodata?->nim) }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIRM</label>
                            <input type="text" name="nirm" class="input-field"
                                value="{{ old('nirm', $biodata?->nirm) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIRL</label>
                            <input type="text" name="nirl" class="input-field"
                                value="{{ old('nirl', $biodata?->nirl) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="input-field" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" @selected(old('jenis_kelamin', $biodata?->jenis_kelamin) == 'Laki-laki')>Laki-laki</option>
                                <option value="Perempuan" @selected(old('jenis_kelamin', $biodata?->jenis_kelamin) == 'Perempuan')>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Mahasiswa</label>
                            <select name="status_mahasiswa" class="input-field" required>
                                <option value="">Pilih</option>
                                <option value="Baru" @selected(old('status_mahasiswa', $biodata?->status_mahasiswa) == 'Baru')>Baru</option>
                                <option value="Transfer" @selected(old('status_mahasiswa', $biodata?->status_mahasiswa) == 'Transfer')>Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Masuk</label>
                            <input type="text" name="tahun_masuk" class="input-field"
                                value="{{ old('tahun_masuk', $biodata?->tahun_masuk) }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fakultas</label>
                            <input type="text" name="fakultas" class="input-field"
                                value="{{ old('fakultas', $biodata?->fakultas) }}" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                            <input type="text" name="program_studi" class="input-field"
                                value="{{ old('program_studi', $biodata?->program_studi) }}" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Rumah</label>
                            <textarea rows="2" name="alamat_rumah" class="input-field" required>{{ old('alamat_rumah', $biodata?->alamat_rumah) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon / HP</label>
                            <input type="text" name="no_telepon" class="input-field"
                                value="{{ old('no_telepon', $biodata?->no_telepon) }}" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kesan dan Pesan</label>
                            <textarea rows="4" name="kesan_pesan" class="input-field">{{ old('kesan_pesan', $biodata?->kesan_pesan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 md:mb-6">Detail Skripsi/TA/Tesis</h2>
                <div class="space-y-4 md:space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dosen Pembimbing</label>
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                                <select id="dosen-select" class="input-field flex-1">
                                    <option value="">Pilih Dosen Pembimbing</option>
                                    <option value="Dr. Ahmad, M.Kom">Dr. Ahmad, M.Kom</option>
                                    <option value="Dr. Siti, M.T.">Dr. Siti, M.T.</option>
                                    <option value="Budi Santoso, M.Kom">Budi Santoso, M.Kom</option>
                                    <option value="Rina Wijaya, M.Kom">Rina Wijaya, M.Kom</option>
                                </select>
                                <button type="button" id="tambah-dosen"
                                    class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                    Tambah
                                </button>
                            </div>

                            <div id="dosen-list" class="space-y-2">
                                @php
                                    $listDosen = old(
                                        'dosen_pembimbing',
                                        $biodata?->dosenPembimbings?->pluck('nama')->toArray() ?? [],
                                    );
                                @endphp

                                @if (!empty($listDosen) && is_array($listDosen))
                                    @foreach ($listDosen as $namaDosen)
                                        @if (!empty($namaDosen))
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <span class="text-sm text-gray-700">{{ $namaDosen }}</span>
                                                <input type="hidden" name="dosen_pembimbing[]"
                                                    value="{{ $namaDosen }}">
                                                <button type="button" class="text-red-600 hover:text-red-700 text-sm"
                                                    onclick="this.parentElement.remove()">
                                                    Hapus
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Skripsi / Tesis / TA</label>
                        <textarea rows="4" name="judul_skripsi" class="input-field" required>{{ old('judul_skripsi', $biodata?->judul_skripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="w-full sm:w-auto px-8 py-3 bg-[#435ebe] text-white rounded-lg hover:opacity-90 transition-colors font-medium">
                    Simpan Semua Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Preview foto profil
                const fotoInput = document.getElementById('foto-profil');
                const previewFoto = document.getElementById('preview-foto');
                const placeholderIcon = document.getElementById('placeholder-icon');

                if (fotoInput) {
                    fotoInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewFoto.src = e.target.result;
                                previewFoto.classList.remove('hidden');
                                placeholderIcon.classList.add('hidden');
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                }

                // Tambah dosen pembimbing
                const tambahDosenBtn = document.getElementById('tambah-dosen');
                const dosenSelect = document.getElementById('dosen-select');
                const dosenList = document.getElementById('dosen-list');

                if (tambahDosenBtn && dosenSelect && dosenList) {
                    tambahDosenBtn.addEventListener('click', function() {
                        const selectedDosen = dosenSelect.value;
                        const selectedText = dosenSelect.options[dosenSelect.selectedIndex].text;

                        if (selectedDosen && selectedText) {
                            // Cek apakah sudah ada
                            const existingDosen = Array.from(dosenList.querySelectorAll('input[type="hidden"]'))
                                .some(input => input.value === selectedText);

                            if (!existingDosen) {
                                const dosenItem = document.createElement('div');
                                dosenItem.className =
                                    'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                                dosenItem.innerHTML = `
                        <span class="text-sm text-gray-700">${selectedText}</span>
                        <input type="hidden" name="dosen_pembimbing[]" value="${selectedText}">
                        <button type="button" class="text-red-600 hover:text-red-700 text-sm" onclick="this.parentElement.remove()">
                            Hapus
                        </button>
                    `;
                                dosenList.appendChild(dosenItem);
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
