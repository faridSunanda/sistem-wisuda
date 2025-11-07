@extends('mahasiswa.layouts.app')
@section('content')

<div class="max-w-6xl mx-auto">
    <form action="{{-- Ganti dengan route Anda, misal: route('mahasiswa.sertifikat.store') --}}" method="POST">
        @csrf
        <div class="bg-gray-40 rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-6">Sertifikat Kompetensi</h2>
            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-6" role="alert">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.168 2.307-1.168 2.98 0l1.755 3.047a.75.75 0 01-.65 1.127H7.38a.75.75 0 01-.65-1.127l1.755-3.047zM11.97 9.875a.75.75 0 01-.75.75h-.97a.75.75 0 01-.75-.75V8.125a.75.75 0 01.75-.75h.97a.75.75 0 01.75.75v1.75zm-1.74 3.75a.75.75 0 00-1.5 0v.008a.75.75 0 001.5 0v-.008z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-orange-700">

                            Isi minimal 1 sertifikat dan maksimal 5 sertifikat.

                        </p>

                    </div>

                </div>

            </div>



            <div id="sertifikat-container" class="space-y-6">



                {{-- (DIPERBARUI) Card pertama dengan style "kartu" --}}

                <div class="sertifikat-entry p-8 bg-white border border-gray-200 rounded-lg relative transition-shadow duration-200 hover:shadow-md">

                   

                    {{-- Tombol hapus ditambahkan, tapi disembunyikan (hidden) --}}

                    <button type="button" class="remove-sertifikat absolute top-3 right-3 p-1 bg-white rounded-full text-red-400 hover:text-red-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300 hidden">

                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>



                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>

                            <label for="nama_sertifikat_1" class="block text-sm font-medium text-gray-700 mb-1">Nama Sertifikat</label>

                            <input type="text" name="nama_sertifikat[]" id="nama_sertifikat_1" placeholder="Contoh: Web Programming Dasar" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" required>

                        </div>

                        <div>

                            <label for="penerbit_1" class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>

                            <input type="text" name="penerbit[]" id="penerbit_1" placeholder="Contoh: Universitas Wahid Hasyim" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" required>

                        </div>

                        <div class="relative">

                            <label for="tanggal_terbit_1" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terbit</label>

                            <input type="date" name="tanggal_terbit[]" id="tanggal_terbit_1" placeholder="dd/mm/yyyy" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" required>

                            <div class="absolute inset-y-0 right-0 top-6 pr-3 flex items-center pointer-events-none">

                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />

                                </svg>

                            </div>

                        </div>

                    </div>

                </div>

               

            </div>



            <div class="flex items-center justify-between mt-6">

                <button type="button" id="add-sertifikat" class="inline-flex items-center gap-x-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">

                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">

                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />

                    </svg>

                    Tambah Sertifikat

                </button>

               

                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">

                    Simpan Data

                </button>

            </div>



        </div>

    </form>

</div>

@endsection



@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const maxSertifikat = 5;

        let sertifikatCount = document.querySelectorAll('#sertifikat-container .sertifikat-entry').length;



        const addButton = document.getElementById('add-sertifikat');

        const container = document.getElementById('sertifikat-container');



        // --- Fungsi Cek Tombol Add ---

        function checkAddButtonState() {

            if (sertifikatCount >= maxSertifikat) {

                addButton.disabled = true;

            } else {

                addButton.disabled = false;

            }

        }



        // --- Fungsi Tampil/Sembunyikan Tombol Hapus ---

        function toggleDeleteButtons() {

            const allDeleteButtons = container.querySelectorAll('.remove-sertifikat');

            if (sertifikatCount > 1) {

                // Jika sertifikat lebih dari 1, TAMPILKAN semua tombol hapus

                allDeleteButtons.forEach(btn => btn.classList.remove('hidden'));

            } else {

                // Jika sertifikat hanya 1, SEMBUNYIKAN semua tombol hapus

                allDeleteButtons.forEach(btn => btn.classList.add('hidden'));

            }

        }



        // --- Panggil fungsi saat halaman pertama kali dimuat ---

        checkAddButtonState();

        toggleDeleteButtons(); // Ini akan menyembunyikan tombol hapus pertama saat load



        // --- Event Listener Tombol Tambah ---

        addButton.addEventListener('click', function () {

            if (sertifikatCount >= maxSertifikat) {

                alert('Anda hanya dapat menambahkan maksimal 5 sertifikat.');

                return;

            }



            sertifikatCount++;



            const newEntry = document.createElement('div');

            // (DIPERBARUI) Menambahkan kelas "kartu" untuk item baru

            newEntry.classList.add(

                'sertifikat-entry',

                'p-8',

                'bg-white',         // Background kartu

                'border',             // Border kartu

                'border-gray-200',    // Warna border

                'rounded-lg',         // Sudut melengkung

                'relative',

                'transition-shadow',  // Transisi

                'duration-200',       // Durasi transisi

                'hover:shadow-md'     // Efek shadow saat hover

            );

           

            // Template HTML untuk "kartu" baru

            const newEntryHtml = `

                <button type="button" class="remove-sertifikat absolute top-3 right-3 p-1 bg-white rounded-full text-red-400 hover:text-red-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300">

                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>



                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>

                        <label for="nama_sertifikat_${sertifikatCount}" class="block text-sm font-medium text-gray-700 mb-1">Nama Sertifikat</label>

                        <input type="text" name="nama_sertifikat[]" id="nama_sertifikat_${sertifikatCount}" placeholder="Contoh: Web Programming Dasar" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" required>

                    </div>

                    <div>

                        <label for="penerbit_${sertifikatCount}" class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>

                        <input type="text" name="penerbit[]" id="penerbit_${sertifikatCount}" placeholder="Contoh: Universitas Wahid Hasyim" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" required>

                    </div>

                    <div class="relative">

                        <label for="tanggal_terbit_${sertifikatCount}" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terbit</label>

                        <input type="date" name="tanggal_terbit[]" id="tanggal_terbit_${sertifikatCount}" placeholder="dd/mm/yyyy" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3" required>

                        <div class="absolute inset-y-0 right-0 top-6 pr-3 flex items-center pointer-events-none">

                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />

                            </svg>

                        </div>

                    </div>

                </div>

            `;

           

            newEntry.innerHTML = newEntryHtml;

            container.appendChild(newEntry);



            checkAddButtonState();

            toggleDeleteButtons(); // Panggil ini agar semua tombol (termasuk yang pertama) muncul

        });



        // --- Event Listener Tombol Hapus ---

        container.addEventListener('click', function (e) {

            const removeButton = e.target.closest('.remove-sertifikat');

           

            if (removeButton) {

                if (sertifikatCount > 1) {

                    removeButton.closest('.sertifikat-entry').remove();

                    sertifikatCount--;

                    checkAddButtonState();

                    toggleDeleteButtons(); // Cek ulang apakah tombol perlu disembunyikan

                } else {

                    alert('Minimal harus ada 1 sertifikat.');

                }

            }

        });

    });

</script>

@endpush