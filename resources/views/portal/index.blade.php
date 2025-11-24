@extends('layouts.app')

@section('title', 'Beranda - Sistem Informasi Wisuda UNWAHAS')

@push('styles')
    @vite('resources/css/portal/index.css')
@endpush

@section('content')

    <section
        class="w-full relative bg-cover bg-center bg-no-repeat min-h-[50vh] sm:min-h-[60vh] md:min-h-[70vh] lg:min-h-[calc(100vh-70px)] flex flex-col px-4 sm:pl-6 md:pl-12 justify-center py-8 sm:py-0"
        style="background-image: url('{{ asset('img/wisuda_sistem.jpg') }}'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/30"></div>

        <div class="relative z-10 w-full max-w-4xl mx-auto sm:mx-0 text-left animate-fadeIn">
            <div class="inline-block mb-4 sm:mb-6 md:mb-8">
                <h1
                    class="text-white text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight tracking-wide drop-shadow-lg mb-1.5 sm:mb-2">
                    Sistem Informasi Wisuda
                </h1>
                <h2
                    class="text-green-400 text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight tracking-wide drop-shadow-lg">
                    Universitas Wahid Hasyim
                </h2>
                <div class="w-full h-0.5 bg-green-400 mt-1.5 sm:mt-2"></div>
            </div>

            <div class="mt-3 sm:mt-4 md:mt-6">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 px-4 py-2 sm:px-5 sm:py-2.5 md:px-6 md:py-3 rounded-lg text-white font-semibold text-xs sm:text-sm md:text-base hover:scale-105 transition-all duration-300 shadow-lg shadow-green-500/40">
                    <i class="fa-sharp fa-solid fa-right-to-bracket text-sm sm:text-base"></i>
                    <span>Login SSO</span>
                </a>
            </div>
        </div>
    </section>

    <section class="flex justify-center font-semibold w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 md:py-6 lg:py-8">
        <div
            class="relative w-full max-w-2xl text-center bg-white shadow-xl -mt-6 sm:-mt-8 md:-mt-12 lg:-mt-16 rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-5 md:p-6 lg:p-8 pt-8 sm:pt-10 md:pt-12 lg:pt-14 border border-gray-200/60 backdrop-blur-sm">
            {{-- <div
                class="absolute -top-2.5 sm:-top-3 md:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] sm:text-xs md:text-sm font-bold px-3 sm:px-4 md:px-5 py-1 sm:py-1.5 md:py-2 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                Pendaftaran Ditutup
            </div> --}}
            @if (isset($statusInfo))
                <div
                    class="absolute -top-3 left-1/2 -translate-x-1/2 {{ $statusInfo['color'] }} text-white text-sm px-4 py-1 rounded-full shadow-md">
                    {{ $statusInfo['text'] }}
                </div>
            @endif

            <div class="flex flex-col items-center justify-center mb-4 sm:mb-5 md:mb-6">
                <div class="mb-2.5 sm:mb-3 md:mb-4">
                    <i
                        class="fa-sharp fa-solid fa-graduation-cap text-purple-500 text-2xl sm:text-3xl md:text-4xl lg:text-5xl animate-bounce-smooth drop-shadow-lg"></i>
                </div>
                <p
                    class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700 flex flex-wrap justify-center items-center gap-1 sm:gap-1.5 md:gap-2">
                    <span class="font-semibold text-gray-600">Total Pendaftar:</span>
                    <span class="font-bold text-lg sm:text-xl md:text-2xl lg:text-3xl text-gray-900">1165</span>
                    <span class="text-gray-500 text-sm sm:text-base md:text-lg lg:text-xl">/1300</span>
                </p>
            </div>

            <div
                class="w-full bg-gray-100 h-2 sm:h-2.5 md:h-3 rounded-full mt-3 sm:mt-4 md:mt-6 overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-green-400 via-green-500 to-green-600 progress-bar rounded-full shadow-sm"
                    style="width: calc(1165 / 1300 * 100%)"></div>
            </div>

            <p id="info-text"
                class="text-[10px] sm:text-xs md:text-sm lg:text-base text-gray-700 mt-2.5 sm:mt-3 md:mt-4 transition-opacity duration-700 opacity-100 font-semibold">
                <span class="text-gray-800">Segera Daftar!</span>
            </p>
        </div>
    </section>

    <section id="persyaratan" class="py-10 sm:py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 sm:mb-10 md:mb-12">
                <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                    Persyaratan Wisuda
                </h3>
                <div class="w-24 h-1 bg-green-600 mx-auto mb-4"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base">
                    Dokumen dan persyaratan yang harus dipenuhi untuk mengikuti wisuda
                </p>
            </div>

            <div class="max-w-5xl mx-auto">
                {{-- Grid Container --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">

                    {{-- Loop Dokumen secara Dinamis --}}
                    @forelse ($dokumenPersyaratan as $dokumen)
                        @php
                            // Logika selang-seling warna (Ganjil: Biru, Genap: Hijau)
                            $isEven = $loop->iteration % 2 == 0;
                            $bgClass = $isEven ? 'bg-green-50' : 'bg-blue-50';
                            $iconBgClass = $isEven ? 'bg-green-600' : 'bg-blue-600';
                            $btnClass = $isEven ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700';
                            $iconClass = $isEven ? 'fa-receipt' : 'fa-folder-open';
                        @endphp

                        <div
                            class="{{ $bgClass }} rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 {{ $iconBgClass }} rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fa-sharp fa-solid {{ $iconClass }} text-white text-xl"></i>
                                </div>
                                <h4 class="text-xl font-bold text-gray-800">
                                    {{ $dokumen->nama_dokumen }}
                                </h4>
                            </div>

                            <p class="text-gray-700 mb-4 text-sm leading-relaxed">
                                {{ $dokumen->keterangan }}
                            </p>

                            @if ($dokumen->berkas)
                                <a href="{{ asset('storage/' . $dokumen->berkas) }}" target="_blank"
                                    class="inline-flex items-center gap-2 {{ $btnClass }} px-4 py-2.5 rounded-lg text-white font-medium transition-colors duration-300">
                                    <i class="fa-sharp fa-solid fa-download"></i>
                                    <span>Download</span>
                                </a>
                            @else
                                <span class="text-gray-500 text-sm italic">File belum diunggah</span>
                            @endif
                        </div>

                    @empty
                        {{-- Tampilan jika tidak ada dokumen sama sekali --}}
                        <div
                            class="col-span-1 md:col-span-2 text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <i class="fa-solid fa-file-circle-xmark text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">Belum ada dokumen persyaratan yang tersedia.</p>
                        </div>
                    @endforelse

                </div>

                {{-- Bagian Alur Pendaftaran tetap sama --}}
                <div
                    class="mt-6 sm:mt-8 md:mt-12 lg:mt-16 bg-gradient-to-br from-gray-50 to-white rounded-lg sm:rounded-xl md:rounded-2xl lg:rounded-3xl p-4 sm:p-5 md:p-6 lg:p-8 xl:p-12 shadow-lg border border-gray-200/50">
                    {{-- ... Konten Alur Pendaftaran ... --}}
                    {{-- (Copy paste bagian alur dari kode lama Anda disini) --}}
                    <div class="text-center mb-6 sm:mb-8 md:mb-12 lg:mb-16">
                        <h4
                            class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-2 sm:mb-3 md:mb-4">
                            Alur Pendaftaran Wisuda
                        </h4>
                        {{-- ... dst ... --}}
                        {{-- Pastikan Anda menutup div dengan benar --}}
                    </div>

                    {{-- Masukkan loop alur disini seperti kode asli --}}
                    <div class="relative">
                        <div class="alur-container">
                            @foreach ($alurPendaftaran as $index => $alur)
                                {{-- ... --}}
                                <div class="relative alur-step" data-step="{{ $index + 1 }}">
                                    <div
                                        class="relative bg-white rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-5 md:p-6 shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-gray-200/60 h-full flex flex-col group z-10">
                                        {{-- ... Isian Alur ... --}}
                                        <div class="flex items-center justify-center mb-3 sm:mb-4 flex-shrink-0">
                                            <div
                                                class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center shadow-lg group-hover:from-blue-700 group-hover:to-blue-800 transition-all duration-300">
                                                <span class="text-white text-lg sm:text-xl md:text-2xl font-bold">
                                                    {{ $alur->no_urut }}
                                                </span>
                                            </div>
                                        </div>

                                        <h5
                                            class="font-bold text-gray-800 mb-2 sm:mb-3 flex-shrink-0 text-sm sm:text-base md:text-lg text-center group-hover:text-blue-600 transition-colors duration-300">
                                            {{ $alur->judul }}
                                        </h5>
                                        <p
                                            class="text-xs sm:text-sm md:text-base text-gray-600 leading-relaxed text-center flex-grow min-h-0">
                                            {{ $alur->keterangan }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const infoText = document.getElementById('info-text');
            if (infoText) {
                const messages = [
                    '<span class="font-semibold text-gray-800">89.6%</span> kuota telah terpenuhi',
                    '<span class="font-semibold text-gray-800">Lengkapi syarat pendaftaran</span>',
                    '<span class="font-semibold text-gray-800">Segera Daftar!</span>'
                ];
                let index = 0;

                setInterval(() => {
                    infoText.classList.add('fade-out');
                    setTimeout(() => {
                        index = (index + 1) % messages.length;
                        infoText.innerHTML = messages[index];
                        infoText.classList.remove('fade-out');
                        infoText.classList.add('fade-in');
                    }, 500);
                }, 3000);
            }
        });
    </script>
@endsection
