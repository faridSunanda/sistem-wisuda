<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Admin' }} - WISUDA UNWAHAS</title>
    <link rel="icon" href="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png"
        alt="https://unwahas.ac.id/all-logo/" type="image/x-icon" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-slate-100 text-sm text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" @resize.window="sidebarOpen = window.innerWidth > 1024" x-cloak>

        @include('admin.components.sidebar')

        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out"
            :class="{ 'lg:ml-64': sidebarOpen }">

            @include('admin.components.header')

            <main class="flex-1 p-4 md:p-6">
                @yield('content')
            </main>

            @include('admin.components.footer')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @stack('scripts')
</body>

</html>
