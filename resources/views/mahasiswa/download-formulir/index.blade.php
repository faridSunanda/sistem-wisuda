@extends('mahasiswa.layouts.app')

@section('content')
    <div class="space-y-6 pb-10">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Formulir Wisuda</h1>
            <p class="text-sm md:text-base text-gray-600 mt-2">Kelola data dan download formulir pendaftaran wisuda</p>
        </div>

        <!-- Status Info (OPSIONAL: Bisa dihapus atau diubah) -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 md:p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- HAPUS/TAMBAHKAN KETERANGAN BAHWA DOWNLOAD TANPA SYARAT -->
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-sm font-medium text-green-700">
                            Formulir Siap Didownload
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Ubah Data Diri -->
            <a href="{{ route('mahasiswa.biodata.index') }}"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow group">
                <div
                    class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-user-edit text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ubah Data Diri</h3>
                <p class="text-sm text-gray-600">Perbarui data pribadi dan akademik</p>
            </a>

            <!-- Ubah Sertifikat -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center relative group">
                <div
                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-certificate text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ubah Sertifikat</h3>
                <p class="text-sm text-gray-600">Kelola semua jenis sertifikat</p>

                <!-- Dropdown Menu -->
                <div
                    class="absolute top-full left-0 mt-2 w-full bg-white shadow-lg rounded-lg border border-gray-200 hidden group-hover:block z-10">
                    <div class="py-2">
                        <a href="{{ route('mahasiswa.sertifikat.kompetensi') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-code mr-2"></i>Sertifikat Kompetensi
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.bahasa-internasional') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-language mr-2"></i>Bahasa Internasional
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.magang') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-briefcase mr-2"></i>Magang/KP
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.pendidikan-karakter') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-user-graduate mr-2"></i>Pendidikan Karakter
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.penghargaan') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-trophy mr-2"></i>Penghargaan
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.organisasi') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-users mr-2"></i>Organisasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Download PDF - HAPUS DISABLED -->
            <button id="download-btn"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow group">
                <div
                    class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                    <i class="fas fa-file-pdf text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Download PDF</h3>
                <p class="text-sm text-gray-600">Unduh formulir dalam format PDF</p>
            </button>
        </div>

        <!-- Preview Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-4 md:px-6 py-4">
                <h2 class="text-lg md:text-xl font-semibold text-white">Preview Formulir Wisuda</h2>
                <p class="text-sm text-purple-100 mt-1">Formulir akan terupdate otomatis ketika data diubah</p>
            </div>

            <div class="p-4 md:p-6">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-white shadow-xl max-w-4xl mx-auto text-gray-800"
                    style="font-family: 'Cambria', serif; font-size: 12pt;">

                    <div class="p-4 md:p-6">
                        <!-- PERUBAHAN: Menambahkan style font dan ukuran -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-white shadow-xl max-w-4xl mx-auto text-gray-800"
                            style="font-family: 'Cambria', serif; font-size: 12pt;">

                            <div class="flex items-center justify-between pb-3 mb-4 border-b-2 border-black">
                                <img src={{ asset('img/unwahas_putih.jpg') }} alt="Logo UNWAHAS" class="w-20 h-20 ml-25">
                                <div class="text-center flex-grow mx-4 leading-snug">
                                    <h3 class="font-extrabold text-xl">UNIVERSITAS WAHID HASYIM</h3>
                                    <p class="text-base font-semibold">PANITIA WISUDA KE 44 TAHUN 2025</p>
                                    <p class="text-sm text-gray-600">Jl. Menoreh Tengah X/22 Sampangan Semarang. Telp.
                                        024-8505680/81</p>
                                </div>
                                <div class="w-20 h-20 flex-shrink-0"></div>
                            </div>

                            <div class="text-center mb-6">
                                <h2 class="text-xl font-bold uppercase underline">FORMULIR PENDAFTARAN WISUDA</h2>
                            </div>

                            <div class="mb-4 space-y-1">
                                <div class="grid grid-cols-2 gap-y-1">
                                    <p class="font-semibold">1. Nama Lengkap</p>
                                    <p>: {{ $biodata->user->name_lengkap ?? '-' }}</p>
                                    <p class="font-semibold">2. Tempat, Tgl Lahir</p>
                                    <p>: {{ $biodata->tempat_lahir ?? '-' }},
                                        {{ $biodata->tanggal_lahir ? \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y') : '-' }}
                                    </p>
                                    <p class="font-semibold">3. NIK</p>
                                    <p>: {{ $biodata->nik ?? '-' }}</p>
                                    <p class="font-semibold">4. NIM</p>
                                    <p>: {{ $biodata->nim ?? '-' }}</p>
                                    <p class="font-semibold">5. NIRM</p>
                                    <p>: {{ $biodata->nirm ?? '-' }}</p>
                                    <p class="font-semibold">6. NIRL</p>
                                    <p>: {{ $biodata->nirl ?? '-' }}</p>
                                    <p class="font-semibold">7. Jenis Kelamin</p>
                                    <p>: {{ $biodata->jenis_kelamin ?? '-' }}</p>
                                    <p class="font-semibold">8. Status Mahasiswa</p>
                                    <p>: {{ $biodata->status_mahasiswa ?? '-' }}</p>
                                    <p class="font-semibold">9. Tahun Masuk</p>
                                    <p>: {{ $biodata->tahun_masuk ?? '-' }}</p>
                                    <p class="font-semibold">10. Fakultas</p>
                                    <p>: {{ $biodata->fakultas ?? '-' }}</p>
                                    <p class="font-semibold">11. Program Studi</p>
                                    <p>: {{ $biodata->program_studi ?? '-' }}</p>
                                    <p class="font-semibold">12. Alamat Rumah</p>
                                    <p>: {{ $biodata->alamat_rumah ?? '-' }}</p>
                                    <p class="font-semibold">13. Alamat Kantor</p>
                                    <p>: {{ $biodata->alamat_kantor ?? '-' }}</p>
                                    <p class="font-semibold">14. Nomor Telp/HP</p>
                                    <p>: {{ $biodata->no_telepon ?? '-' }}</p>
                                    <p class="font-semibold">15. Email</p>
                                    <p>: {{ $biodata->email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 mt-4">

                                <div class="flex">
                                    <p class="w-1/3 flex-shrink-0 font-semibold">16. Dosen Pembimbing</p>
                                    <div class="flex-1 -mt-1">
                                        @php $i = 1; @endphp
                                        @if ($biodata->dosenPembimbings && $biodata->dosenPembimbings->count() > 0)
                                            @foreach ($biodata->dosenPembimbings as $dosen)
                                                <p class="leading-tight">
                                                    @if ($i == 1)
                                                        :
                                                    @else
                                                        &nbsp;&nbsp;&nbsp;
                                                    @endif {{ $i++ }}.
                                                    {{ $dosen->nama ?? '-' }}
                                                </p>
                                            @endforeach
                                        @else
                                            <p class="leading-tight">: 1. -</p>
                                            <p class="leading-tight ml-4">2. -</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex">
                                    <p class="w-1/3 flex-shrink-0 font-semibold">17. Judul Skripsi/Tesis/TA</p>
                                    <p class="flex-1">: {{ $biodata->judul_skripsi ?? '-' }}</p>
                                </div>

                                <div class="flex">
                                    <p class="w-1/3 flex-shrink-0 font-semibold">18. Kesan dan Pesan</p>
                                    <p class="flex-1">: {{ $biodata->kesan_pesan ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="mt-8 space-y-6">

                                <div class="break-inside-avoid">
                                    <h4 class="font-bold mb-2 underline">Sertifikat Kompetensi:</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-400 table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama Sertifikat
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama dalam
                                                        Inggris</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Dikeluarkan Oleh
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 w-20">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($biodata->sertifikatKompetensi as $sertifikat)
                                                    <tr>
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $loop->iteration }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_sertifikat }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_inggris }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->penerbit }}</td>
                                                        <!-- PERUBAHAN: Format tanggal tanpa waktu -->
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5"
                                                            class="border border-gray-400 px-2 py-1 text-center text-gray-500">
                                                            Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="break-inside-avoid">
                                    <h4 class="font-bold mb-2 underline">Sertifikat Bahasa Internasional:</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-400 table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama Sertifikat
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama dalam
                                                        Inggris</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Dikeluarkan Oleh
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 w-20">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($biodata->sertifikatBahasaInternasional as $sertifikat)
                                                    <tr>
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $loop->iteration }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_sertifikat }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_inggris }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->penerbit }}</td>
                                                        <!-- PERUBAHAN: Format tanggal tanpa waktu -->
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5"
                                                            class="border border-gray-400 px-2 py-1 text-center text-gray-500">
                                                            Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="break-inside-avoid">
                                    <h4 class="font-bold mb-2 underline">Sertifikat Magang/Kerja Praktek/PPL:</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-400 table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama Sertifikat
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama dalam
                                                        Inggris</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Dikeluarkan Oleh
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($biodata->sertifikatMagang as $sertifikat)
                                                    <tr>
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $loop->iteration }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_sertifikat }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_inggris }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->penerbit }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4"
                                                            class="border border-gray-400 px-2 py-1 text-center text-gray-500">
                                                            Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="break-inside-avoid">
                                    <h4 class="font-bold mb-2 underline">Sertifikat Pendidikan Karakter:</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-400 table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama Sertifikat
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama dalam
                                                        Inggris</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Dikeluarkan Oleh
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 w-20">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($biodata->sertifikatPendidikanKarakter as $sertifikat)
                                                    <tr>
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $loop->iteration }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_sertifikat }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_inggris }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->penerbit }}</td>
                                                        <!-- PERUBAHAN: Format tanggal tanpa waktu -->
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5"
                                                            class="border border-gray-400 px-2 py-1 text-center text-gray-500">
                                                            Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="break-inside-avoid">
                                    <h4 class="font-bold mb-2 underline">Sertifikat Penghargaan:</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-400 table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama Sertifikat
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama dalam
                                                        Inggris</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Dikeluarkan Oleh
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 w-20">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($biodata->sertifikatPenghargaan as $sertifikat)
                                                    <tr>
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $loop->iteration }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_sertifikat }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_inggris }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->penerbit }}</td>
                                                        <!-- PERUBAHAN: Format tanggal tanpa waktu -->
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5"
                                                            class="border border-gray-400 px-2 py-1 text-center text-gray-500">
                                                            Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="break-inside-avoid">
                                    <h4 class="font-bold mb-2 underline">Sertifikat Organisasi:</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-400 table-auto">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama Sertifikat
                                                    </th>
                                                    <th class="border border-gray-400 px-2 py-1 text-left">Nama dalam
                                                        Inggris</th>
                                                    <th class="border border-gray-400 px-2 py-1 w-24">Tanggal Mulai</th>
                                                    <th class="border border-gray-400 px-2 py-1 w-24">Tanggal Selesai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($biodata->sertifikatOrganisasi as $sertifikat)
                                                    <tr>
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $loop->iteration }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_sertifikat }}</td>
                                                        <td class="border border-gray-400 px-2 py-1">
                                                            {{ $sertifikat->nama_inggris }}</td>
                                                        <!-- PERUBAHAN: Format tanggal tanpa waktu -->
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $sertifikat->tanggal_mulai ? \Carbon\Carbon::parse($sertifikat->tanggal_mulai)->format('d-m-Y') : '-' }}
                                                        </td>
                                                        <!-- PERUBAHAN: Format tanggal tanpa waktu -->
                                                        <td class="border border-gray-400 px-2 py-1 text-center">
                                                            {{ $sertifikat->tanggal_selesai ? \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->format('d-m-Y') : '-' }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5"
                                                            class="border border-gray-400 px-2 py-1 text-center text-gray-500">
                                                            Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-8 break-inside-avoid">
                                <h4 class="font-bold mb-2">Ceklist Ke-validan Sertifikat (Diisi Kaprodi/TU/Delegasi
                                    Fakultas
                                    Teknik)</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-400 table-auto">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-400 px-2 py-1 w-10">No.</th>
                                                <th class="border border-gray-400 px-2 py-1 text-left">Jenis Sertifikat
                                                </th>
                                                <th class="border border-gray-400 px-2 py-1 w-20">Cek</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">1.</td>
                                                <td class="border border-gray-400 px-2 py-1">Sertifikat Kompetensi</td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">2.</td>
                                                <td class="border border-gray-400 px-2 py-1">Sertifikat Bahasa
                                                    Internasional</td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">3.</td>
                                                <td class="border border-gray-400 px-2 py-1">Sertifikat Magang/KP/PPL</td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">4.</td>
                                                <td class="border border-gray-400 px-2 py-1">Sertifikat Pendidikan Karakter
                                                </td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">5.</td>
                                                <td class="border border-gray-400 px-2 py-1">Sertifikat Penghargaan</td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">6.</td>
                                                <td class="border border-gray-400 px-2 py-1">Sertifikat Organisasi</td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex justify-between mt-10">

                                <div class="w-1/3 text-left space-y-1">
                                    <p>Kajur/Kaprodi/Delegasi Fakultas Teknik</p>
                                    <div class="h-16"></div>
                                    <p class="border-t border-black pt-1">(................................)</p>
                                    <p class="text-xs">Tanda Tangan dan Stempel</p>
                                </div>

                                <div class="w-2/3 flex justify-end space-x-6">

                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-28 h-36 border border-gray-500 bg-gray-100 flex items-center justify-center shadow-md">
                                            @if ($biodata->foto_profile)
                                                <img src="{{ asset('storage/' . $biodata->foto_profile) }}"
                                                    alt="Foto Profil" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs text-gray-500 text-center">Pas Foto 4x6<br>(Latar
                                                    Merah)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 text-left space-y-1 mt-0 pt-0">
                                        <p>Semarang, {{ date('d F Y') }}</p>
                                        <p>Mahasiswa (Calon wisudawan),</p>
                                        <div class="h-16"></div>
                                        <p class="border-t border-black pt-1">{{ $biodata->user->name_lengkap ?? '-' }}
                                        </p>
                                        <p>NIM. {{ $biodata->nim ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-center text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ini adalah preview. Formulir PDF yang didownload akan memiliki format lengkap dengan kop surat
                            resmi
                            (diharapkan berukuran A4).
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Notification Container -->
            <div id="toast-container" class="fixed top-4 right-2 md:right-4 z-50 space-y-2 max-w-sm w-full md:max-w-md">
            </div>
        @endsection

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const downloadBtn = document.getElementById('download-btn');

                    if (downloadBtn) {
                        downloadBtn.addEventListener('click', function() {
                            // Show loading state
                            const originalText = this.innerHTML;
                            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                            this.disabled = true;

                            // Trigger download
                            window.location.href = '{{ route('mahasiswa.download-formulir.download') }}';

                            // Reset button after 3 seconds
                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.disabled = false;
                            }, 3000);
                        });
                    }

                    // Toast function
                    function showToast(message, type = 'success') {
                        const toastContainer = document.getElementById('toast-container');
                        const toastId = 'toast-' + Date.now();

                        const toast = document.createElement('div');
                        toast.id = toastId;
                        toast.className = `p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out ${
    type === 'success' ? 'bg-green-500 text-white' : 
    type === 'error' ? 'bg-red-500 text-white' : 
    'bg-blue-500 text-white'
}`;

                        toast.innerHTML = `
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 
                'fa-info-circle'
            } mr-2"></i>
            <span>${message}</span>
        </div>
        <button onclick="document.getElementById('${toastId}').remove()" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
`;

                        toastContainer.appendChild(toast);

                        // Auto remove after 5 seconds
                        setTimeout(() => {
                            if (document.getElementById(toastId)) {
                                document.getElementById(toastId).remove();
                            }
                        }, 5000);
                    }

                    // Check for flash messages
                    @if (session('success'))
                        showToast('{{ session('success') }}', 'success');
                    @endif

                    @if (session('error'))
                        showToast('{{ session('error') }}', 'error');
                    @endif
                });
            </script>
        @endpush
