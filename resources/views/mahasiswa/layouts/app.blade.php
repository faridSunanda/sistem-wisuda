<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Mahasiswa - Sistem Informasi Wisuda UNWAHAS' }}</title>
    <link rel="icon" href="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">

    <div x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        showFooter: true,
        init() {
            // Set default sidebar state based on screen size
            this.sidebarOpen = window.innerWidth >= 1024;
        }
    }" class="flex h-screen overflow-hidden">
        <!-- Backdrop for Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-500/20 backdrop-blur-sm transition-opacity duration-300 lg:hidden"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        @include('mahasiswa.components.sidebar')
        
        <div class="flex flex-col flex-1 overflow-hidden w-full transition-all duration-300"
             :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-0'">
            
            @include('mahasiswa.components.header')
            
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6 pb-28"
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
    
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', 
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: 'Terjadi kesalahan validasi. Silakan cek form Anda.'
            });
        @endif
    </script>
</body>
</html>