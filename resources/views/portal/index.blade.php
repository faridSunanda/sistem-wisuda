@extends('layouts.app')

{{-- Mengatur judul halaman (opsional, jika layout Anda memiliki @yield('title')) --}}
@section('title', 'Beranda - Sistem Informasi Wisuda UNWAHAS')

{{-- Ini adalah konten utama halaman --}}
@section('content')

    <section
        class="w-full relative bg-cover bg-center bg-no-repeat min-h-[70vh] md:min-h-[calc(100vh-70px)] flex flex-col pl-4 md:pl-12 justify-center text-center"
        style="background-image: url('{{ asset('img/heroImage.jpeg') }}')">
        <div class="absolute inset-0 bg-gradient-to-b from-black/75 to-white/20 backdrop-blur-[2px]"></div>

        <div class="relative z-10 w-[100%] max-w-4xl text-left animate-fadeIn">
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
                <a href="#" target="_blank"
                    class="inline-flex items-center gap-2 bg-green-500 px-6 py-3 rounded-lg text-white font-semibold hover:bg-green-600 hover:scale-105 transition-all duration-300 shadow-lg shadow-green-500/30">
                    <i class="fa-solid fa-right-to-bracket text-lg"></i>
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
                <span class="font-semibold text-gray-700">89.6%</span> kuota telah
                terpenuhi kuota telah terpenuhi
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
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-blue-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fa-solid fa-check-to-slot text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">
                                Dokumen Persyaratan
                            </h4>
                        </div>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span>Syarat-syarat yang harus dilengkapi untuk mendaftar</span>
                            </li>
                            <li class="flex items-start">
                                <a href="https://wisuda.unwahas.ac.id/document/persyaratan.pdf" target="_blank"
                                    class="flex items-center gap-1 bg-blue-600 p-3 rounded-md text-white">
                                    <i class="fa-solid fa-download"></i>Download
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-green-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-file-alt text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">Tanda terima</h4>
                        </div>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span>Tanda terima pendaftaran ini sudah include dalam file pdf
                                    formulir pendaftaran</span>
                            </li>
                            <li class="flex items-start">
                                <a href="https://wisuda.unwahas.ac.id/document/terima.pdf" target="_blank"
                                    class="flex items-center gap-1 bg-green-600 p-3 rounded-md text-white">
                                    <i class="fa-solid fa-download"></i>Download
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 bg-gray-50 rounded-xl p-8">
                    <h4 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                        Alur Pendaftaran Wisuda
                    </h4>
                    <div class="w-32 h-1 bg-green-600 mx-auto mb-4 rounded-lg"></div>

                    <div class="grid md:grid-cols-5 gap-6">
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">1</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Mulai</h5>
                            <p class="text-sm text-gray-600">
                                Buka sistem informasi wisuda wisuda.unwahas.ac.id
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">2</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Syarat</h5>
                            <p class="text-sm text-gray-600">
                                Download tanda terima dan persyaratan pada laman WEB
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">3</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Daftar</h5>
                            <p class="text-sm text-gray-600">
                                Melakukan pembayaran biaya wisuda
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">4</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Pembayaran</h5>
                            <p class="text-sm text-gray-600">
                                Lakukan pembayaran sesuai tagihan pada Virtual Account
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">5</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Isi Form</h5>
                            <p class="text-sm text-gray-600">
                                Lengkapi formulir pendaftaran wisuda
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">6</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Cetak</h5>
                            <p class="text-sm text-gray-600">
                                Cetak form pendaftaran wisuda (Yang berisi SKPI)
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">7</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">
                                Validasi Kaprodi
                            </h5>
                            <p class="text-sm text-gray-600">
                                Form pendaftaran yang sudah lengkap dan transkrip sementara di
                                validasi oleh prodi masing masing
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">8</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">
                                Validasi Keuangan
                            </h5>
                            <p class="text-sm text-gray-600">
                                Validasi pelunasan administrasi wisuda & biaya pendidikan di
                                pelayanan keuangan
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">9</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">
                                Validasi Akademik
                            </h5>
                            <p class="text-sm text-gray-600">
                                Validasi data mahasiswa pada PDDIKTI dan pemberkasan di
                                pelayanan akademik
                            </p>
                        </div>
                        <div class="text-center hover:shadow-md p-2 rounded-md transition-all">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-xl">10</span>
                            </div>
                            <h5 class="font-semibold text-gray-800 mb-2">Terdaftar</h5>
                            <p class="text-sm text-gray-600">
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
