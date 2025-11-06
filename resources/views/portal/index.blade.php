@extends('layouts.app')

@section('title', 'Beranda - Sistem Informasi Wisuda UNWAHAS')

@section('content')

    <!-- Hero Section -->
    <section
        class="w-full relative bg-cover bg-center bg-no-repeat min-h-[70vh] md:min-h-[calc(100vh-70px)] flex flex-col px-4 md:pl-12 justify-center text-center md:text-left"
        style="background-image: url('{{ asset('img/heroImage.jpeg') }}')">
        <div class="absolute inset-0 bg-gradient-to-b from-black/75 to-white/20 backdrop-blur-[2px]"></div>

        <div class="relative z-10 w-full max-w-4xl mx-auto md:mx-0 animate-fadeIn">
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

            <button onclick="window.location.href='/mahasiswa'"
                class="mt-4 flex items-center justify-center md:justify-start gap-2 bg-green-500 px-6 py-3 rounded-lg text-white font-semibold hover:bg-green-600 hover:scale-105 transition-all duration-300 shadow-lg shadow-green-500/30">
                <i class="fa-solid fa-right-to-bracket text-lg"></i>
                <span>Login SSO</span>
            </button>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="flex justify-center font-semibold w-full px-4">
        <div
            class="relative w-full max-w-md md:w-1/2 text-center bg-white shadow-xl -mt-12 rounded-xl p-6 pt-10 border border-gray-100">
            <div
                class="absolute -top-3 left-1/2 -translate-x-1/2 bg-red-500 text-white text-sm px-4 py-1 rounded-full shadow-md">
                Pendaftaran Ditutup
            </div>

            <p class="text-base md:text-xl text-gray-700">
                <i class="fas fa-graduation-cap text-indigo-500 mr-2"></i>
                <span class="font-medium">Total Pendaftar:</span>
                <span class="font-bold text-gray-900">1165</span>
                <span class="text-gray-500">/1300</span>
            </p>

            <div class="w-full bg-gray-200 h-2 rounded-full mt-4 overflow-hidden">
                <div class="h-full bg-green-500" style="width: calc(1165 / 1300 * 100%)"></div>
            </div>

            <p class="text-sm text-gray-500 mt-2">
                <span class="font-semibold text-gray-700">89.6%</span> kuota telah terpenuhi
            </p>
        </div>
    </section>

    <!-- Requirements Section -->
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
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Document Requirements -->
                    <div class="bg-blue-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fa-solid fa-file-lines text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">
                                Dokumen Persyaratan
                            </h4>
                        </div>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i class="fa-solid fa-circle-check text-blue-600 mt-1 mr-2"></i>
                                <span>Syarat-syarat yang harus dilengkapi untuk mendaftar</span>
                            </li>
                            <li class="flex items-start mt-4">
                                <a href="{{ asset('file/persyaratan.pdf') }}" target="_blank"
                                    class="inline-flex items-center gap-2 bg-blue-600 px-4 py-3 rounded-md text-white hover:bg-blue-700 transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    <span>Download Persyaratan</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Receipt -->
                    <div class="bg-green-50 rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fa-solid fa-file-invoice text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">Tanda Terima</h4>
                        </div>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i class="fa-solid fa-circle-check text-green-600 mt-1 mr-2"></i>
                                <span>Tanda terima pendaftaran ini sudah include dalam file PDF
                                    formulir pendaftaran</span>
                            </li>
                            <li class="flex items-start mt-4">
                                <a href="{{ asset('file/terima.pdf') }}" target="_blank"
                                    class="inline-flex items-center gap-2 bg-green-600 px-4 py-3 rounded-md text-white hover:bg-green-700 transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    <span>Download Tanda Terima</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Registration Flow -->
                <div class="mt-12 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 md:p-8 shadow-md">
                    <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                        Alur Pendaftaran Wisuda
                    </h4>
                    <div class="w-32 h-1 bg-green-600 mx-auto mb-8 rounded-lg"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 md:gap-6">
                        <!-- Step 1 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">1</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Mulai</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Buka sistem informasi wisuda wisuda.unwahas.ac.id
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">2</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Syarat</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Download tanda terima dan persyaratan pada laman WEB
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">3</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Daftar</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Melakukan pendaftaran wisuda melalui sistem
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">4</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Pembayaran</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Lakukan pembayaran sesuai tagihan pada Virtual Account
                            </p>
                        </div>

                        <!-- Step 5 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">5</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Isi Form</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Lengkapi formulir pendaftaran wisuda
                            </p>
                        </div>

                        <!-- Step 6 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">6</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Cetak</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Cetak form pendaftaran wisuda (Yang berisi SKPI)
                            </p>
                        </div>

                        <!-- Step 7 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">7</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">
                                Validasi Kaprodi
                            </h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Form pendaftaran yang sudah lengkap dan transkrip sementara di
                                validasi oleh prodi masing-masing
                            </p>
                        </div>

                        <!-- Step 8 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">8</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">
                                Validasi Keuangan
                            </h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Validasi pelunasan administrasi wisuda & biaya pendidikan di
                                pelayanan keuangan
                            </p>
                        </div>

                        <!-- Step 9 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">9</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">
                                Validasi Akademik
                            </h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Validasi data mahasiswa pada PDDIKTI dan pemberkasan di
                                pelayanan akademik
                            </p>
                        </div>

                        <!-- Step 10 -->
                        <div
                            class="text-center bg-white hover:shadow-lg p-4 rounded-lg transition-all hover:-translate-y-1">
                            <div
                                class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <span class="text-white font-bold text-lg md:text-xl">10</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Terdaftar</h5>
                            <p class="text-xs md:text-sm text-gray-600">
                                Selamat, Anda terdaftar sebagai calon wisudawan Universitas
                                Wahid Hasyim
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
