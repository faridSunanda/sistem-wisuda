`@extends('layouts.app')

@section('title', 'Beranda - Sistem Informasi Wisuda UNWAHAS')

@push('styles')
    @vite('resources/css/portal/index.css')
@endpush

@push('scripts')
    @vite('resources/js/portal/index.js')
    
    @if($totalKuota > 0)
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    if (typeof window.initInfoTextAnimation === 'function') {
                        window.initInfoTextAnimation({{ $persentase }});
                    }
                }, 100);
            });
        </script>
    @endif

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (typeof window.initAlurAutoScroll === 'function') {
                    window.initAlurAutoScroll();
                } else {
                    // Retry jika function belum tersedia
                    setTimeout(function() {
                        if (typeof window.initAlurAutoScroll === 'function') {
                            window.initAlurAutoScroll();
                        }
                    }, 500);
                }
            }, 100);
        });
    </script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section
        class="w-full relative bg-cover bg-center bg-no-repeat min-h-[50vh] sm:min-h-[60vh] md:min-h-[70vh] lg:min-h-[calc(100vh-70px)] flex flex-col px-4 sm:pl-6 md:pl-12 justify-center py-8 sm:py-0"
        style="background-image: url('{{ asset('img/wisuda_sistem.jpg') }}'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/30"></div>

        <div class="relative z-10 w-full max-w-4xl mx-auto sm:mx-0 text-left animate-fadeIn">
            <div class="inline-block mb-4 sm:mb-6 md:mb-8">
                <h1 class="text-white text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-semibold leading-tight tracking-wide drop-shadow-lg mb-1.5 sm:mb-2">
                    Sistem Informasi Wisuda
                </h1>
                <h2 class="text-green-400 text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight tracking-wide drop-shadow-lg">
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

    {{-- Statistik Pendaftar --}}
    <section class="flex justify-center font-semibold w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 md:py-6 lg:py-8">
        <div class="relative w-full max-w-2xl text-center bg-white shadow-xl -mt-6 sm:-mt-8 md:-mt-12 lg:-mt-16 rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-5 md:p-6 lg:p-8 pt-8 sm:pt-10 md:pt-12 lg:pt-14 border border-gray-200/60 backdrop-blur-sm">
            @if (isset($statusInfo))
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 {{ $statusInfo['color'] }} text-white text-sm px-4 py-1 rounded-full shadow-md">
                    {{ $statusInfo['text'] }}
                </div>
            @endif

            <div class="flex flex-col items-center justify-center mb-4 sm:mb-5 md:mb-6">
                <div class="mb-2.5 sm:mb-3 md:mb-4">
                    <i class="fa-sharp fa-solid fa-graduation-cap text-purple-500 text-2xl sm:text-3xl md:text-4xl lg:text-5xl animate-bounce-smooth drop-shadow-lg"></i>
                </div>
                <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700 flex flex-wrap justify-center items-center gap-1 sm:gap-1.5 md:gap-2">
                    <span class="font-semibold text-gray-600">Total Pendaftar:</span>
                    <span class="font-bold text-lg sm:text-xl md:text-2xl lg:text-3xl text-gray-900">{{ number_format($totalPendaftar) }}</span>
                    @if($totalKuota > 0)
                        <span class="text-gray-500 text-sm sm:text-base md:text-lg lg:text-xl">/{{ number_format($totalKuota) }}</span>
                    @endif
                </p>
            </div>

            @if($totalKuota > 0)
                <div class="w-full bg-gray-100 h-2 sm:h-2.5 md:h-3 rounded-full mt-3 sm:mt-4 md:mt-6 overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-green-400 via-green-500 to-green-600 progress-bar rounded-full shadow-sm transition-all duration-500"
                    style="width: {{ $persentase }}%"></div>
            </div>

                <p id="info-text" class="text-[10px] sm:text-xs md:text-sm lg:text-base text-gray-700 mt-2.5 sm:mt-3 md:mt-4 transition-opacity duration-700 opacity-100 font-semibold">
                @if($persentase >= 100)
                    <span class="text-red-600 font-bold">Kuota Penuh!</span>
                @elseif($persentase >= 80)
                    <span class="text-orange-600 font-bold">Kuota Hampir Penuh!</span>
                @else
                    <span class="text-gray-800">Segera Daftar!</span>
                @endif
            </p>
            @else
            <div class="w-full bg-gray-100 h-2 sm:h-2.5 md:h-3 rounded-full mt-3 sm:mt-4 md:mt-6 overflow-hidden shadow-inner">
                <div class="h-full bg-gray-300 rounded-full"></div>
            </div>
            <p class="text-[10px] sm:text-xs md:text-sm lg:text-base text-gray-500 mt-2.5 sm:mt-3 md:mt-4 font-semibold">
                Kuota belum ditentukan
            </p>
            @endif
        </div>
    </section>

    {{-- Persyaratan & Alur Pendaftaran --}}
    <section id="persyaratan" class="py-10 sm:py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            {{-- Header Persyaratan --}}
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
                {{-- Dokumen Persyaratan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    @forelse ($dokumenPersyaratan as $dokumen)
                        @php
                            $isEven = $loop->iteration % 2 == 0;
                            $bgClass = $isEven ? 'bg-green-50' : 'bg-blue-50';
                            $iconBgClass = $isEven ? 'bg-green-600' : 'bg-blue-600';
                            $btnClass = $isEven ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700';
                            $iconClass = $isEven ? 'fa-receipt' : 'fa-folder-open';
                        @endphp

                        <div class="{{ $bgClass }} rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 {{ $iconBgClass }} rounded-full flex items-center justify-center mr-4 flex-shrink-0">
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
                        <div class="col-span-1 md:col-span-2 text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <i class="fa-solid fa-file-circle-xmark text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">Belum ada dokumen persyaratan yang tersedia.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Alur Pendaftaran --}}
                <div class="mt-8 sm:mt-10 md:mt-12 lg:mt-16">
                    <div class="text-center mb-6 sm:mb-8 md:mb-10 lg:mb-12">
                        <h4 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-2 sm:mb-3 md:mb-4">
                            Alur Pendaftaran Wisuda
                        </h4>
                        <div class="w-20 sm:w-24 h-0.5 sm:h-1 bg-green-600 mx-auto mb-3 sm:mb-4"></div>
                        <p class="text-gray-600 max-w-2xl mx-auto text-xs sm:text-sm md:text-base px-2">
                            Ikuti langkah-langkah berikut untuk menyelesaikan pendaftaran wisuda
                        </p>
                    </div>

                    <div class="alur-wrapper" id="alurWrapper">
                        <div class="alur-container">
                            @foreach ($alurPendaftaran as $alur)
                                <div class="alur-step" data-step="{{ $loop->iteration }}">
                                    <div class="alur-card">
                                        <div class="alur-badge">
                                            <span class="alur-number">{{ $alur->no_urut }}</span>
                                        </div>

                                        <div class="alur-content">
                                            <h5 class="alur-title">{{ $alur->judul }}</h5>
                                            <p class="alur-description">{{ $alur->keterangan }}</p>
                                        </div>
                                    </div>

                                    @if (!$loop->last)
                                        <div class="alur-connector">
                                            <div class="alur-line"></div>
                                            <div class="alur-arrow"></div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
