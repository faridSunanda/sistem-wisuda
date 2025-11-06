@extends('layouts.app')

@section('title', 'Beranda - Sistem Informasi Wisuda UNWAHAS')

@section('content')

    <section
        class="w-full relative bg-cover bg-center bg-no-repeat min-h-[70vh] md:min-h-[calc(100vh-70px)] flex flex-col pl-4 md:pl-12 justify-center text-center"
        style="background-image: url('{{ asset('img/wisuda_sistem.jpg') }}')">
        <div class="absolute inset-0 bg-gradient-to-b from-black/75 to-white/20 backdrop-blur-[2px]"></div>

        <div class="relative z-10 w-full max-w-4xl text-left animate-fadeIn">
            <div class="inline-block md:border-b-2 border-green-400 pb-2 mb-6">
                <h1
                    class="text-white text-2xl sm:text-3xl md:text-4xl font-semibold leading-tight tracking-wide drop-shadow-md">
                    Sistem Informasi Wisuda
                </h1>
                <h2
                    class="text-green-300 text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mt-2 tracking-widest drop-shadow-lg">
                    Universitas Wahid Hasyim
                </h2>
            </div>

            <div class="mt-6">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 bg-green-500 px-6 py-3 rounded-lg text-white font-semibold hover:bg-green-600 hover:scale-105 transition-all duration-300 shadow-lg shadow-green-500/30">
                    <i class="fa-sharp fa-solid fa-right-to-bracket text-lg"></i>
                    <span>Login SSO</span>
                </a>
            </div>
        </div>
    </section>

    <section class="flex justify-center font-semibold w-full px-4">
        <div
            class="relative w-full md:w-1/2 text-center bg-white shadow-xl -mt-12 rounded-xl p-6 pt-10 border border-gray-100">
            <div
                class="absolute -top-3 left-1/2 -translate-x-1/2 bg-red-500 text-white text-sm px-4 py-1 rounded-full shadow-md">
                Pendaftaran Ditutup
            </div>

            <p class="text-base md:text-xl text-gray-700 flex flex-wrap justify-center items-center gap-1">
                <i class="fa-sharp fa-solid fa-graduation-cap text-indigo-500 text-lg animate-bounce-smooth"></i>
                <span class="font-medium">Total Pendaftar:</span>
                <span class="font-bold text-gray-900">1165</span>
                <span class="text-gray-500">/1300</span>
            </p>

            <div class="w-full bg-gray-200 h-2 rounded-full mt-4 overflow-hidden">
                <div class="h-full bg-green-500 progress-bar" style="width: calc(1165 / 1300 * 100%)"></div>
            </div>

            <p id="info-text" class="text-sm text-gray-500 mt-2 transition-opacity duration-700 opacity-100">
                <span class="font-semibold text-gray-700">89.6%</span> kuota telah terpenuhi
            </p>
        </div>
    </section>

    <section id="persyaratan" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">
                    Persyaratan Wisuda
                </h3>
                <div class="w-24 h-1 bg-green-600 mx-auto mb-4 rounded-lg"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Dokumen dan persyaratan yang harus dipenuhi untuk mengikuti wisuda
                </p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kartu Persyaratan -->
                    <div class="bg-blue-50 rounded-xl p-6 hover:shadow-lg transition-all">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fa-sharp fa-solid fa-folder-open text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">
                                Dokumen Persyaratan
                            </h4>
                        </div>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-2">
                                <i class="fa-sharp fa-regular fa-file-circle-check text-blue-600 mt-1"></i>
                                <span>Syarat-syarat yang harus dilengkapi untuk mendaftar</span>
                            </li>
                            <li class="flex items-start">
                                <a href="https://wisuda.unwahas.ac.id/document/persyaratan.pdf" target="_blank"
                                    class="flex items-center gap-2 bg-blue-600 p-3 rounded-md text-white hover:bg-blue-700 transition-all duration-300">
                                    <i class="fa-sharp fa-solid fa-download"></i>
                                    <span>Download</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Kartu Tanda Terima -->
                    <div class="bg-green-50 rounded-xl p-6 hover:shadow-lg transition-all">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fa-sharp fa-solid fa-receipt text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">Tanda Terima</h4>
                        </div>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-2">
                                <i class="fa-sharp fa-regular fa-file-lines text-green-600 mt-1"></i>
                                <span>Tanda terima pendaftaran sudah termasuk dalam file PDF formulir pendaftaran</span>
                            </li>
                            <li class="flex items-start">
                                <a href="https://wisuda.unwahas.ac.id/document/terima.pdf" target="_blank"
                                    class="flex items-center gap-2 bg-green-600 p-3 rounded-md text-white hover:bg-green-700 transition-all duration-300">
                                    <i class="fa-sharp fa-solid fa-download"></i>
                                    <span>Download</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Alur Pendaftaran -->
                <div class="mt-12 bg-gray-50 rounded-xl p-8">
                    <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                        Alur Pendaftaran Wisuda
                    </h4>
                    <div class="w-32 h-1 bg-green-600 mx-auto mb-8 rounded-lg"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                        @foreach ([['fa-house-laptop', 'Mulai', 'Buka sistem informasi wisuda wisuda.unwahas.ac.id'], ['fa-file-circle-check', 'Syarat', 'Download tanda terima dan persyaratan pada laman web'], ['fa-credit-card', 'Daftar', 'Melakukan pembayaran biaya wisuda'], ['fa-building-columns', 'Pembayaran', 'Lakukan pembayaran sesuai tagihan pada Virtual Account'], ['fa-pen-to-square', 'Isi Form', 'Lengkapi formulir pendaftaran wisuda'], ['fa-print', 'Cetak', 'Cetak form pendaftaran wisuda (yang berisi SKPI)'], ['fa-user-check', 'Validasi Kaprodi', 'Form pendaftaran & transkrip sementara divalidasi prodi'], ['fa-sack-dollar', 'Validasi Keuangan', 'Validasi pelunasan administrasi wisuda & biaya pendidikan'], ['fa-book-open-reader', 'Validasi Akademik', 'Validasi data mahasiswa pada PDDIKTI dan pelayanan akademik'], ['fa-circle-check', 'Terdaftar', 'Selamat, Anda terdaftar sebagai calon wisudawan Universitas Wahid Hasyim']] as $step)
                            <div class="text-center hover:shadow-md p-4 rounded-md transition-all">
                                <div
                                    class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-sharp fa-solid {{ $step[0] }} text-white text-2xl"></i>
                                </div>
                                <h5 class="font-semibold text-gray-800 mb-2">{{ $step[1] }}</h5>
                                <p class="text-sm text-gray-600">
                                    {{ $step[2] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* --- Smooth bounce untuk icon --- */
        @keyframes bounceSmooth {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .animate-bounce-smooth {
            animation: bounceSmooth 1.8s ease-in-out infinite;
        }

        /* --- Transisi teks berganti --- */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        .fade-in {
            opacity: 1;
            transition: opacity 0.5s ease-in;
        }

        /* --- Animasi progress bar berjalan --- */
        @keyframes growBar {
            from {
                width: 0;
            }

            to {
                width: calc(1165 / 1300 * 100%);
            }
        }

        .progress-bar {
            animation: growBar 1.2s ease-out forwards;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const infoText = document.getElementById('info-text');
            const messages = [
                '<span class="font-semibold text-gray-700">89.6%</span> kuota telah terpenuhi',
                '<span class="font-semibold text-gray-700">Lengkapi syarat pendaftaran</span>',
                '<span class="font-semibold text-gray-700">Segera Daftar!</span>'
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
        });
    </script>
@endsection
