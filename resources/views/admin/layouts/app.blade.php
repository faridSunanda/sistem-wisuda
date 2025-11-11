<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Admin - Sistem Informasi Wisuda UNWAHAS' }}</title>
    <link rel="icon" href="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50" x-data="{
    sidebarOpen: false,
    showFooter: true
}">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <div class="flex flex-col flex-1 overflow-hidden lg:ml-64">
            <!-- Header -->
            @include('admin.components.header')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6"
                x-on:scroll.debounce="showFooter = $el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10">
                @yield('content')
            </main>

            <footer x-show="showFooter" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-4"
                class="bg-white border-t border-gray-200 py-3 px-4 md:px-6">
                <div class="text-center text-sm text-gray-600">
                    <p>© UPT PSID | All Rights Reserved | Powered by Universitas Wahid Hasyim</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Set up template Toast di kanan atas
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // Ini adalah 'dikanan atas'
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Cek jika ada session 'success' dari PHP
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // Cek jika ada session 'error' dari PHP
        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // Cek jika ada error validasi (opsional, tapi bagus)
        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: 'Terjadi kesalahan validasi. Silakan cek form Anda.'
            });
        @endif
    </script>
</body>

</html>
