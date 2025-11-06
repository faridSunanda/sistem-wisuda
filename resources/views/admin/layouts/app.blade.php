<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin - Sistem Informasi Wisuda UNWAHAS' }}</title>
    <link rel="icon" href="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 bg-opacity-60 z-40 lg:hidden hidden"></div>
        @include('admin.components.sidebar')
        
        <div class="flex flex-col flex-1 overflow-hidden w-full lg:w-auto">
            @include('admin.components.header')
            
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6">
                @yield('content')
            </main>
            
            @include('admin.components.footer')
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
