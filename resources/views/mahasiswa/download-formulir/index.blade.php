@extends('mahasiswa.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Formulir Wisuda</h1>
                <p class="text-sm md:text-base text-gray-600 mt-2">Kelola data dan download formulir pendaftaran wisuda</p>
            </div>
        </div>

        <!-- Status Info -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Ubah Data Diri -->
            <a href="{{ route('mahasiswa.biodata.index') }}"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow group hover:border-blue-500">
                <div
                    class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-user-edit text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ubah Data Diri</h3>
                <p class="text-sm text-gray-600">Perbarui data pribadi dan akademik</p>
            </a>

            <!-- Ubah Sertifikat -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center relative group hover:border-green-500">
                <div
                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-certificate text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ubah Sertifikat</h3>
                <p class="text-sm text-gray-600">Kelola semua jenis sertifikat</p>

                <!-- Dropdown Menu -->
                <div
                    class="absolute top-full left-0 mt-2 w-full bg-white shadow-lg rounded-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-2">
                        <a href="{{ route('mahasiswa.sertifikat.kompetensi') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-code mr-2"></i>Sertifikat Kompetensi
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.bahasa-internasional') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-language mr-2"></i>Bahasa Internasional
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.magang') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-briefcase mr-2"></i>Magang/KP
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.pendidikan-karakter') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-user-graduate mr-2"></i>Pendidikan Karakter
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.penghargaan') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-trophy mr-2"></i>Penghargaan
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.organisasi') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-users mr-2"></i>Organisasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Download PDF -->
            <button id="download-btn"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow group hover:border-orange-500">
                <div
                    class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                    <i class="fas fa-file-pdf text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Download PDF</h3>
                <p class="text-sm text-gray-600">Unduh formulir dalam format PDF</p>
            </button>
        </div>

        <!-- Preview Section dengan desain mirip admin -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Preview Formulir Wisuda</h2>
                <p class="text-sm text-gray-600 mt-1">Formulir akan terupdate otomatis ketika data diubah</p>
            </div>
            
            <div class="p-6">
                <!-- Container untuk preview formulir dengan scroll -->
                <div class="border border-gray-300 rounded-lg overflow-auto max-h-[600px] p-4 bg-gray-50">
                    <div class="bg-white p-6 shadow-inner max-w-4xl mx-auto text-gray-800"
                        style="font-family: 'Cambria', serif; font-size: 12pt;">

                        <!-- Kop Surat -->
                        <div class="flex items-center justify-between pb-3 mb-4 border-b-2 border-black">
                            <img src="{{ asset('img/unwahas_putih.jpg') }}" alt="Logo UNWAHAS" class="w-20 h-20">
                            <div class="text-center flex-grow mx-4 leading-snug">
                                <h3 class="font-extrabold text-xl">UNIVERSITAS WAHID HASYIM</h3>
                                <p class="text-base font-semibold">PANITIA WISUDA KE 44 TAHUN 2025</p>
                                <p class="text-sm text-gray-600">Jl. Menoreh Tengah X/22 Sampangan Semarang. Telp.
                                    024-8505680/81</p>
                            </div>
                            <div class="w-20 h-20 flex-shrink-0"></div>
                        </div>

                        <!-- Judul Formulir -->
                        <div class="text-center mb-6">
                            <h2 class="text-xl font-bold uppercase underline">FORMULIR PENDAFTARAN WISUDA</h2>
                        </div>

                        <!-- Data Pribadi dalam format tabel -->
                        <div class="mb-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-400 table-auto text-xs">
                                    <tbody>
                                        @php
                                            $dataPribadi = [
                                                ['Nama Lengkap', $biodata->user->name_lengkap ?? '-'],
                                                ['Tempat, Tgl Lahir', ($biodata->tempat_lahir ?? '-') . ', ' . ($biodata->tanggal_lahir ? \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y') : '-')],
                                                ['NIK', $biodata->nik ?? '-'],
                                                ['NIM', $biodata->nim ?? '-'],
                                                ['NIRM', $biodata->nirm ?? '-'],
                                                ['NIRL', $biodata->nirl ?? '-'],
                                                ['Jenis Kelamin', $biodata->jenis_kelamin ?? '-'],
                                                ['Status Mahasiswa', $biodata->status_mahasiswa ?? '-'],
                                                ['Tahun Masuk', $biodata->tahun_masuk ?? '-'],
                                                ['Fakultas', $biodata->fakultas ?? '-'],
                                                ['Program Studi', $biodata->program_studi ?? '-'],
                                                ['Alamat Rumah', $biodata->alamat_rumah ?? '-'],
                                                ['Alamat Kantor', $biodata->alamat_kantor ?? '-'],
                                                ['Nomor Telp/HP', $biodata->no_telepon ?? '-'],
                                                ['Email', $biodata->email ?? '-'],
                                            ];
                                        @endphp
                                        
                                        @foreach($dataPribadi as $index => $item)
                                            <tr>
                                                <td class="border border-gray-400 px-3 py-2 text-center font-semibold w-10 bg-gray-50">
                                                    {{ $index + 1 }}.
                                                </td>
                                                <td class="border border-gray-400 px-3 py-2 font-semibold w-40 bg-gray-50">
                                                    {{ $item[0] }}
                                                </td>
                                                <td class="border border-gray-400 px-3 py-2">
                                                    {{ $item[1] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Data Akademik dalam format tabel -->
                        <div class="mb-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-400 table-auto text-xs">
                                    <tbody>
                                        <!-- Dosen Pembimbing -->
                                        <tr>
                                            <td class="border border-gray-400 px-3 py-2 text-center font-semibold w-10 bg-gray-50">
                                                16.
                                            </td>
                                            <td class="border border-gray-400 px-3 py-2 font-semibold bg-gray-50">
                                                Dosen Pembimbing
                                            </td>
                                            <td class="border border-gray-400 px-3 py-2">
                                                @php $i = 1; @endphp
                                                @if ($biodata->dosenPembimbings && $biodata->dosenPembimbings->count() > 0)
                                                    @foreach ($biodata->dosenPembimbings as $dosen)
                                                        <div>
                                                            {{ $i++ }}. {{ $dosen->nama ?? '-' }}
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div>1. -</div>
                                                    <div>2. -</div>
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <!-- Judul Skripsi/Tesis/TA -->
                                        <tr>
                                            <td class="border border-gray-400 px-3 py-2 text-center font-semibold w-10 bg-gray-50">
                                                17.
                                            </td>
                                            <td class="border border-gray-400 px-3 py-2 font-semibold bg-gray-50">
                                                Judul Skripsi/Tesis/TA
                                            </td>
                                            <td class="border border-gray-400 px-3 py-2">
                                                {{ $biodata->judul_skripsi ?? '-' }}
                                            </td>
                                        </tr>
                                        
                                        <!-- Kesan dan Pesan -->
                                        <tr>
                                            <td class="border border-gray-400 px-3 py-2 text-center font-semibold w-10 bg-gray-50">
                                                18.
                                            </td>
                                            <td class="border border-gray-400 px-3 py-2 font-semibold bg-gray-50">
                                                Kesan dan Pesan
                                            </td>
                                            <td class="border border-gray-400 px-3 py-2">
                                                {{ $biodata->kesan_pesan ?? '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sertifikat Tables -->
                        <div class="space-y-6">
                            @foreach([
                                'Sertifikat Kompetensi' => [
                                    'data' => $biodata->sertifikatKompetensi,
                                    'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal']
                                ],
                                'Sertifikat Bahasa Internasional' => [
                                    'data' => $biodata->sertifikatBahasaInternasional,
                                    'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal']
                                ],
                                'Sertifikat Magang/Kerja Praktek/PPL' => [
                                    'data' => $biodata->sertifikatMagang,
                                    'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh']
                                ],
                                'Sertifikat Pendidikan Karakter' => [
                                    'data' => $biodata->sertifikatPendidikanKarakter,
                                    'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal']
                                ],
                                'Sertifikat Penghargaan' => [
                                    'data' => $biodata->sertifikatPenghargaan,
                                    'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal']
                                ],
                                'Sertifikat Organisasi' => [
                                    'data' => $biodata->sertifikatOrganisasi,
                                    'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Tanggal Mulai', 'Tanggal Selesai']
                                ]
                            ] as $title => $config)
                                @if($config['data'] && $config['data']->count() > 0)
                                    <div>
                                        <h4 class="font-bold mb-2 underline">{{ $title }}:</h4>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full border border-gray-400 table-auto text-xs">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        @foreach($config['kolom'] as $kolom)
                                                            <th class="border border-gray-400 px-2 py-1 text-left">
                                                                {{ $kolom }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($config['data'] as $index => $sertifikat)
                                                        <tr>
                                                            <td class="border border-gray-400 px-2 py-1 text-center">
                                                                {{ $index + 1 }}
                                                            </td>
                                                            <td class="border border-gray-400 px-2 py-1">
                                                                {{ $sertifikat->nama_sertifikat }}
                                                            </td>
                                                            <td class="border border-gray-400 px-2 py-1">
                                                                {{ $sertifikat->nama_inggris }}
                                                            </td>
                                                            
                                                            @if(in_array($title, ['Sertifikat Kompetensi', 'Sertifikat Bahasa Internasional', 'Sertifikat Pendidikan Karakter', 'Sertifikat Penghargaan']))
                                                                <td class="border border-gray-400 px-2 py-1">
                                                                    {{ $sertifikat->penerbit }}
                                                                </td>
                                                                <td class="border border-gray-400 px-2 py-1 text-center">
                                                                    {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}
                                                                </td>
                                                            @elseif($title === 'Sertifikat Magang/Kerja Praktek/PPL')
                                                                <td class="border border-gray-400 px-2 py-1">
                                                                    {{ $sertifikat->penerbit }}
                                                                </td>
                                                            @elseif($title === 'Sertifikat Organisasi')
                                                                <td class="border border-gray-400 px-2 py-1 text-center">
                                                                    {{ $sertifikat->tanggal_mulai ? \Carbon\Carbon::parse($sertifikat->tanggal_mulai)->format('d-m-Y') : '-' }}
                                                                </td>
                                                                <td class="border border-gray-400 px-2 py-1 text-center">
                                                                    {{ $sertifikat->tanggal_selesai ? \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->format('d-m-Y') : '-' }}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Validasi Sertifikat -->
                        <div class="mt-8">
                            <h4 class="font-bold mb-2">Ceklist Ke-validan Sertifikat (Diisi Kaprodi/TU/Delegasi {{ $biodata->fakultas ?? 'Fakultas' }})</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-400 table-auto text-xs">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-400 px-2 py-1 w-8">No.</th>
                                            <th class="border border-gray-400 px-2 py-1 text-left">Jenis Sertifikat</th>
                                            <th class="border border-gray-400 px-2 py-1 w-16">Cek</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach([
                                            'Sertifikat Kompetensi',
                                            'Sertifikat Bahasa Internasional',
                                            'Sertifikat Magang/KP/PPL',
                                            'Sertifikat Pendidikan Karakter',
                                            'Sertifikat Penghargaan',
                                            'Sertifikat Organisasi'
                                        ] as $index => $jenis)
                                            <tr>
                                                <td class="border border-gray-400 px-2 py-1 text-center">{{ $index + 1 }}.</td>
                                                <td class="border border-gray-400 px-2 py-1">{{ $jenis }}</td>
                                                <td class="border border-gray-400 px-2 py-1"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="flex justify-between mt-8 pt-4 border-t border-gray-300">
                            <div class="w-1/3 text-left space-y-1">
                                <p>Kajur/Kaprodi/Delegasi</p>
                                <p>Fakultas {{ $biodata->fakultas ?? 'Fakultas' }}</p>
                                <div class="h-16"></div>
                                <p class="border-t border-black pt-1">(................................)</p>
                                <p class="text-xs">Tanda Tangan dan Stempel</p>
                            </div>

                            <div class="w-2/3 flex justify-end space-x-6">
                                <div class="flex-shrink-0">
                                    <div class="w-28 h-36 border border-gray-500 bg-gray-100 flex items-center justify-center shadow-md">
                                        @if ($biodata->foto_profile)
                                            <img src="{{ asset('storage/' . $biodata->foto_profile) }}"
                                                alt="Foto Profil" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-gray-500 text-center">Pas Foto 4x6<br>(Latar Merah)</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-shrink-0 text-left space-y-1">
                                    <p>Semarang, {{ date('d F Y') }}</p>
                                    <p>Mahasiswa (Calon wisudawan),</p>
                                    <div class="h-16"></div>
                                    <p class="border-t border-black pt-1">{{ $biodata->user->name_lengkap ?? '-' }}</p>
                                    <p>NIM. {{ $biodata->nim ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Info Preview -->
                <div class="mt-4 text-center text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                    Ini adalah preview. Formulir PDF yang didownload akan memiliki format lengkap dengan kop surat resmi (diharapkan berukuran A4).
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>
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