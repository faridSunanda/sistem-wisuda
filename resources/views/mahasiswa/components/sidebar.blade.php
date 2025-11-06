<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:shadow-none shadow-xl">
    <div class="p-4 md:p-6 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo_wisuda.jpg') }}" alt="Logo" class="h-8 md:h-10 w-auto">
        </div>
        <button id="close-sidebar" class="lg:hidden p-2 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 p-2 md:p-4 space-y-1 overflow-y-auto">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="flex items-center gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="font-medium text-sm md:text-base">Dashboard</span>
        </a>

        <div class="pt-4">
            <p class="px-3 md:px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pendaftaran</p>

            <a href="{{ route('mahasiswa.data-diri') }}"
               class="flex items-center gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ request()->routeIs('mahasiswa.data-diri') ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-sm md:text-base">Data Diri</span>
            </a>

            <div>
                @php
                    $isSertifikatActive = request()->routeIs('mahasiswa.sertifikat.*');
                @endphp
                <button id="sertifikat-toggle"
                        class="w-full flex items-center justify-between gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ $isSertifikatActive ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm md:text-base">Sertifikat</span>
                    </div>
                    <svg id="sertifikat-chevron" class="w-4 h-4 flex-shrink-0 transition-transform duration-200 {{ $isSertifikatActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="sertifikat-menu" class="mt-1 space-y-1 {{ $isSertifikatActive ? '' : 'hidden' }}">
                    <a href="{{ route('mahasiswa.sertifikat.kompetensi') }}"
                       class="flex items-center gap-3 px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 ml-6 md:ml-8 {{ request()->routeIs('mahasiswa.sertifikat.kompetensi') ? 'bg-blue-50 text-blue-900' : '' }}">
                        <span class="text-xs md:text-sm">Sertifikat Kompetensi</span>
                    </a>
                    <a href="{{ route('mahasiswa.sertifikat.bahasa-internasional') }}"
                       class="flex items-center gap-3 px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 ml-6 md:ml-8 {{ request()->routeIs('mahasiswa.sertifikat.bahasa-internasional') ? 'bg-blue-50 text-blue-900' : '' }}">
                        <span class="text-xs md:text-sm">Sertifikat Bahasa Internasional</span>
                    </a>
                    <a href="{{ route('mahasiswa.sertifikat.magang') }}"
                       class="flex items-center gap-3 px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 ml-6 md:ml-8 {{ request()->routeIs('mahasiswa.sertifikat.magang') ? 'bg-blue-50 text-blue-900' : '' }}">
                        <span class="text-xs md:text-sm">Sertifikat Magang</span>
                    </a>
                    <a href="{{ route('mahasiswa.sertifikat.pendidikan-karakter') }}"
                       class="flex items-center gap-3 px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 ml-6 md:ml-8 {{ request()->routeIs('mahasiswa.sertifikat.pendidikan-karakter') ? 'bg-blue-50 text-blue-900' : '' }}">
                        <span class="text-xs md:text-sm">Sertifikat Pendidikan Karakter</span>
                    </a>
                    <a href="{{ route('mahasiswa.sertifikat.penghargaan') }}"
                       class="flex items-center gap-3 px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 ml-6 md:ml-8 {{ request()->routeIs('mahasiswa.sertifikat.penghargaan') ? 'bg-blue-50 text-blue-900' : '' }}">
                        <span class="text-xs md:text-sm">Sertifikat Penghargaan</span>
                    </a>
                    <a href="{{ route('mahasiswa.sertifikat.organisasi') }}"
                       class="flex items-center gap-3 px-3 md:px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 ml-6 md:ml-8 {{ request()->routeIs('mahasiswa.sertifikat.organisasi') ? 'bg-blue-50 text-blue-900' : '' }}">
                        <span class="text-xs md:text-sm">Sertifikat Organisasi</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('mahasiswa.download-formulir') }}"
               class="flex items-center gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ request()->routeIs('mahasiswa.download-formulir') ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <span class="text-sm md:text-base">Download Formulir</span>
            </a>
        </div>
    </nav>
</aside>

@push('scripts')
<script>
    (function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const openSidebarBtn = document.getElementById('open-sidebar');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        const sertifikatToggle = document.getElementById('sertifikat-toggle');
        const sertifikatMenu = document.getElementById('sertifikat-menu');
        const sertifikatChevron = document.getElementById('sertifikat-chevron');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (openSidebarBtn) {
            openSidebarBtn.addEventListener('click', openSidebar);
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        const isActive = sertifikatMenu.querySelector('a.bg-blue-50');
        if (isActive || sertifikatMenu.classList.contains('block')) {
            sertifikatChevron.classList.add('rotate-180');
        }

        sertifikatToggle.addEventListener('click', function() {
            const isOpen = !sertifikatMenu.classList.contains('hidden');
            sertifikatMenu.classList.toggle('hidden');
            sertifikatChevron.classList.toggle('rotate-180');

            if (!sertifikatMenu.classList.contains('hidden')) {
                sertifikatToggle.classList.add('bg-blue-900', 'text-white');
                sertifikatToggle.classList.remove('text-gray-700', 'hover:bg-gray-100');
            } else {
                if (!isActive) {
                    sertifikatToggle.classList.remove('bg-blue-900', 'text-white');
                    sertifikatToggle.classList.add('text-gray-700', 'hover:bg-gray-100');
                }
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    })();
</script>
@endpush

