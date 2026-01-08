@extends('layouts.app')

@section('title', 'Beranda - Sistem Informasi Wisuda UNWAHAS')

@push('styles')
    @vite('resources/css/portal/index.css')
@endpush

@push('scripts')
    @vite('resources/js/portal/index.js')

    <script>
        @php
            $countdownValue = $countdownTarget ? $countdownTarget->toIso8601String() : null;
        @endphp

        // Progress Bar Animation
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar) {
                    const targetWidth = progressBar.getAttribute('data-width');
                    if (targetWidth) {
                        // Start from 0 width
                        progressBar.style.width = '0%';
                        // Animate to target width using CSS transition
                        requestAnimationFrame(function() {
                            progressBar.style.width = targetWidth + '%';
                        });
                    }
                }
            });
        })();

    // Countdown Timer
    @if($countdownTarget)
        (function() {
            const countdownTarget = {!! json_encode($countdownValue) !!};

            if (!countdownTarget) return;

            const targetTime = new Date(countdownTarget).getTime();

            if (isNaN(targetTime)) {
                console.error('Countdown: Invalid date format', countdownTarget);
                return;
            }

            const daysEl = document.getElementById('countdown-days');
            const hoursEl = document.getElementById('countdown-hours');
            const minutesEl = document.getElementById('countdown-minutes');

            if (!daysEl || !hoursEl || !minutesEl) {
                console.log('Countdown: Elements not found');
                return;
            }

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetTime - now;

                if (distance < 0) {
                    daysEl.textContent = '00';
                    hoursEl.textContent = '00';
                    minutesEl.textContent = '00';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                daysEl.textContent = String(days).padStart(2, '0');
                hoursEl.textContent = String(hours).padStart(2, '0');
                minutesEl.textContent = String(minutes).padStart(2, '0');
            }

            updateCountdown();

            setInterval(updateCountdown, 1000);
        })();
    @endif

    const portalOptions = {
        persentase: {{ $totalKuota > 0 ? $persentase : 'undefined' }}
    };

    function initializePortal() {
        if (typeof window.initPortal === 'function') {
            window.initPortal(portalOptions);
        } else {
            setTimeout(initializePortal, 50);
        }
    }

    // Coba inisialisasi setelah DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePortal);
    } else {
        // DOM sudah ready, tunggu sebentar untuk memastikan file JS ter-load
        setTimeout(initializePortal, 100);
    }
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
    <div class="relative w-full max-w-2xl text-center glassmorphism-card shadow-xl -mt-6 sm:-mt-8 md:-mt-12 lg:-mt-16 rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-5 md:p-6 lg:p-8">
        {{-- Banner Status --}}
        @if (isset($statusInfo))
        <div class="mb-4 sm:mb-5">
            <span class="inline-block {{ $statusInfo['color'] }} text-white text-sm sm:text-base md:text-lg px-6 sm:px-8 md:px-10 lg:px-12 py-2 sm:py-2.5 md:py-3 rounded-full shadow-md font-bold tracking-wide">
                {{ $statusInfo['text'] }}
            </span>
        </div>
        @endif

        {{-- Icon Graduation Cap --}}
        <div class="flex flex-col items-center justify-center mb-4 sm:mb-5 md:mb-6">
            <div class="mb-3 sm:mb-4">
                <i class="fa-sharp fa-solid fa-graduation-cap text-yellow-500 text-3xl sm:text-4xl md:text-5xl lg:text-6xl drop-shadow-lg"></i>
            </div>

            {{-- Countdown Timer --}}
            @if($countdownTarget)
            <div class="mb-4 sm:mb-5">
                <div id="countdown-timer" class="flex items-center justify-center gap-2 sm:gap-3 md:gap-4 mb-2">
                    <div class="flex flex-col items-center">
                        <span id="countdown-days" class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">00</span>
                        <span class="text-xs sm:text-sm text-gray-900">HARI</span>
                    </div>
                    <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">:</span>
                    <div class="flex flex-col items-center">
                        <span id="countdown-hours" class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">00</span>
                        <span class="text-xs sm:text-sm text-gray-900">JAM</span>
                    </div>
                    <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">:</span>
                    <div class="flex flex-col items-center">
                        <span id="countdown-minutes" class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">00</span>
                        <span class="text-xs sm:text-sm text-gray-900">MENIT</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Garis Pemisah --}}
            <div class="w-full h-px bg-gray-900/30 mb-4 sm:mb-5"></div>

            {{-- Total Pendaftar --}}
            <p class="text-sm sm:text-base md:text-lg text-gray-900 mb-4 sm:mb-5">
                <span class="text-gray-900">Total Pendaftar:</span>
                <span class="font-bold text-lg sm:text-xl md:text-2xl lg:text-3xl text-gray-900 ml-2">{{ number_format($totalPendaftar) }}</span>
            </p>

            {{-- Progress Bar --}}
            @if($totalKuota > 0)
            <div class="w-full bg-white/20 h-3 sm:h-4 rounded-full mb-3 sm:mb-4 overflow-hidden shadow-inner backdrop-blur-sm">
                <div class="h-full bg-gradient-to-r from-green-500 via-green-600 to-green-700 progress-bar rounded-full shadow-sm"
                    data-width="{{ $persentase }}"></div>
            </div>

            {{-- Kuota Info --}}
            <p class="text-xs sm:text-sm md:text-base text-gray-900">
                <span>Kuota: <span class="font-bold text-gray-900">{{ number_format($totalKuota) }}</span></span>
                <span class="mx-2 text-gray-900">|</span>
                <span>Sisa: <span class="font-bold text-gray-900">{{ number_format($sisaKuota) }}</span></span>
            </p>
            @else
            <div class="w-full bg-white/20 h-3 sm:h-4 rounded-full mb-3 sm:mb-4 overflow-hidden shadow-inner backdrop-blur-sm">
                <div class="h-full bg-white/30 rounded-full"></div>
            </div>
            <p class="text-xs sm:text-sm md:text-base text-gray-900">
                Kuota belum ditentukan
            </p>
            @endif
        </div>
    </div>
</section>

{{-- Jadwal Pelaksanaan Wisuda --}}
@if($jadwalPelaksanaan && $jadwalPelaksanaan->count() > 0)
<section id="jadwal-pelaksanaan" class="py-8 sm:py-10 md:py-12 lg:py-16 bg-gradient-to-br from-green-700 via-green-800 to-green-900 relative overflow-hidden">
    {{-- Decorative Elements --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-3 sm:px-4 md:px-6 relative z-10">
        <div class="text-center mb-6 sm:mb-8 md:mb-10 lg:mb-12">
            <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-2 sm:mb-3 drop-shadow-lg">
                Jadwal Pelaksanaan Wisuda
            </h3>
            <div class="w-20 sm:w-24 h-0.5 sm:h-1 bg-white mx-auto mb-3 sm:mb-4"></div>
            <p class="text-green-50 max-w-2xl mx-auto text-xs sm:text-sm md:text-base px-2 drop-shadow-md">
                Informasi jadwal pelaksanaan wisuda yang akan dilaksanakan
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 md:gap-6">
                @foreach ($jadwalPelaksanaan as $jadwal)
                <div class="bg-white/15 backdrop-blur-md rounded-lg sm:rounded-xl p-5 sm:p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-white/25">
                    <div class="mb-4">
                        <h4 class="text-base sm:text-lg font-bold text-white mb-2">
                            {{ $jadwal->nama_kegiatan }}
                        </h4>
                        @if($jadwal->sesi)
                        <span class="inline-block px-2.5 py-1 bg-white/20 text-white rounded text-xs font-medium">
                            {{ $jadwal->sesi->name }}
                        </span>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-white/80 mb-1">Tanggal & Waktu</p>
                            <p class="text-sm sm:text-base font-semibold text-white">
                                {{ \Carbon\Carbon::parse($jadwal->waktu_pelaksanaan)->format('d F Y') }}
                            </p>
                            <p class="text-xs sm:text-sm text-white/90">
                                {{ \Carbon\Carbon::parse($jadwal->waktu_pelaksanaan)->format('H:i') }} WIB
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-white/80 mb-1">Tempat</p>
                            <p class="text-sm sm:text-base text-white">
                                {{ $jadwal->tempat_pelaksanaan }}
                            </p>
                        </div>

                        @if($jadwal->keterangan)
                        <div class="pt-3 border-t border-white/20">
                            <p class="text-xs text-white/80 mb-1">Keterangan</p>
                            <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                                {{ $jadwal->keterangan }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

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
        </div>
    </div>
</section>

{{-- Alur Pendaftaran --}}
<section class="py-10 sm:py-12 md:py-16 bg-white">
    <div class="w-full px-4 sm:px-6 md:px-8 lg:px-12">
        <div class="max-w-7xl mx-auto bg-gradient-to-br from-green-700 via-green-800 to-green-900 rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-10 lg:p-12 relative overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 left-10 w-80 h-80 bg-white rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <div class="text-center mb-6 sm:mb-8 md:mb-10 lg:mb-12">
                    <h4 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-2 sm:mb-3 md:mb-4 drop-shadow-lg">
                        Alur Pendaftaran Wisuda
                    </h4>
                    <div class="w-20 sm:w-24 h-0.5 sm:h-1 bg-white mx-auto mb-3 sm:mb-4"></div>
                    <p class="text-green-50 max-w-2xl mx-auto text-xs sm:text-sm md:text-base px-2 drop-shadow-md">
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

