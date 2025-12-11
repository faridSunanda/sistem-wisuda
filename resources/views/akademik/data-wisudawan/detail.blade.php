@extends('akademik.layouts.app')

@section('content')
    <div class="space-y-4 md:space-y-6 pb-10">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Data Wisudawan</h1>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-2 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 mb-6 shadow-sm rounded-r-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium leading-relaxed">
                        Data yang ditampilkan adalah data mahasiswa yang diambil dari PDDIKTI. 
                        Pastikan data sudah benar sebelum memproses verifikasi wisudawan.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

            <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="flex-shrink-0">
                    @if($biodata->foto_profile)
                        <img src="{{ asset('storage/'.$biodata->foto_profile) }}" alt="Foto Profile" 
                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center border-4 border-white shadow-md">
                            <i class="fas fa-user text-5xl text-gray-400"></i>
                        </div>
                    @endif
                </div>

                <div class="text-center md:text-left flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $biodata->user->name_lengkap }}</h2>
                    <p class="text-lg text-gray-500 font-medium mb-4">{{ $biodata->nim ?? 'NIM Belum Diisi' }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-2">

                        <span class="px-3 py-1.5 text-sm font-medium rounded-full border flex items-center gap-1.5 transition-colors duration-300 {{ $biodata->is_verified_akademik ? 'bg-green-50 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
                            <i class="fas {{ $biodata->is_verified_akademik ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $biodata->is_verified_akademik ? 'Terverifikasi Akademik' : 'Menunggu Verifikasi Akademik' }}
                        </span>


                        <span class="px-3 py-1.5 text-sm font-medium rounded-full border flex items-center gap-1.5 {{ $biodata->is_verified_keuangan ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                            <i class="fas {{ $biodata->is_verified_keuangan ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $biodata->is_verified_keuangan ? 'Keuangan Lunas' : 'Belum Lunas' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-8">

                <div>
                    <h3 class="text-lg font-semibold text-[#435ebe] mb-4 border-b pb-2 flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">NIK</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Email Akun</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Tempat, Tanggal Lahir</label>
                            <p class="text-base text-gray-900 font-semibold">
                                {{ $biodata->tempat_lahir ?? '-' }}, 
                                {{ $biodata->tanggal_lahir ? \Carbon\Carbon::parse($biodata->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Jenis Kelamin</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->jenis_kelamin ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">No. Telepon / WA</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->no_telepon ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-normal text-gray-500 mb-1">Alamat Rumah</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->alamat_rumah ?? '-' }}</p>
                        </div>
                    </div>
                </div>


                <div>
                    <h3 class="text-lg font-semibold text-[#435ebe] mb-4 border-b pb-2 flex items-center gap-2">
                        <i class="fas fa-graduation-cap"></i> Data Akademik
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Fakultas</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->fakultas ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Program Studi</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->program_studi ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Tahun Masuk</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->tahun_masuk ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Status Mahasiswa</label>
                            <p class="text-base text-gray-900 font-semibold">{{ $biodata->status_mahasiswa ?? '-' }}</p>
                        </div>
                    </div>
                </div>


                <div>
                    <h3 class="text-lg font-semibold text-[#435ebe] mb-4 border-b pb-2 flex items-center gap-2">
                        <i class="fas fa-book"></i> Data Akhir Studi
                    </h3>
                    <div class="grid grid-cols-1 gap-y-6">
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Judul Skripsi / Tesis / Disertasi</label>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 italic text-gray-800 font-medium">
                                "{{ $biodata->judul_skripsi ?? 'Judul belum diinput' }}"
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-normal text-gray-500 mb-1">Kesan & Pesan</label>
                            <p class="text-base text-gray-900 font-medium whitespace-pre-line leading-relaxed">
                                {{ $biodata->kesan_pesan ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>


                <div class="mt-8 pt-6 border-t border-gray-300 flex flex-col sm:flex-row gap-3">

                    <form id="verificationForm" action="{{ route('akademik.data-wisudawan.verify', $biodata->id) }}" method="POST" class="inline-block w-full sm:w-auto">
                        @csrf
                        
                        @if($biodata->is_verified_akademik)

                            <button type="button" 
                                onclick="confirmAction('batal')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold bg-yellow-500 text-white hover:bg-yellow-600 rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-yellow-300 focus:outline-none">
                                <i class="fas fa-times-circle"></i>
                                <span>Batalkan Verifikasi</span>
                            </button>
                        @else

                            <button type="button" 
                                onclick="confirmAction('verifikasi')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold bg-green-600 text-white hover:bg-green-700 rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-green-300 focus:outline-none">
                                <i class="fas fa-check-circle"></i>
                                <span>Verifikasi Data Valid</span>
                            </button>
                        @endif
                    </form>


                    <a href="{{ route('akademik.data-wisudawan.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium bg-white border border-[#435ebe] text-[#435ebe] hover:bg-[#435ebe] hover:text-white rounded-lg transition-all shadow-sm">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@push('scripts')


<script>
    function confirmAction(type) {
        let title, text, confirmBtnText, btnColor;

        if (type === 'batal') {
            title = 'Batalkan Verifikasi?';
            text = "Status data akan kembali menjadi 'Menunggu Verifikasi'.";
            confirmBtnText = 'Ya, Batalkan!';
            btnColor = '#f59e0b';
        } else {
            title = 'Verifikasi Data?';
            text = "Pastikan data PDDIKTI dan Biodata sudah benar.";
            confirmBtnText = 'Ya, Verifikasi!';
            btnColor = '#10b981';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: btnColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmBtnText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                document.getElementById('verificationForm').submit();
            }
        });
    }
</script>
@endpush
@endsection