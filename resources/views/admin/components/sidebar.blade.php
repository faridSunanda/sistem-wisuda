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
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="font-medium text-sm md:text-base">Dashboard</span>
        </a>
        <div>
            <p class="px-3 md:px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</p>
        </div>
        <a href="{{ route('admin.data-wisudawan') }}"
           class="flex items-center gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ request()->routeIs('admin.data-wisudawan*') ? 'bg-blue-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-medium text-sm md:text-base">Data Wisudawan</span>
        </a>

        <div>
            <button id="setting-toggle" class="w-full flex items-center justify-between gap-3 px-3 md:px-4 py-2 md:py-3 rounded-lg transition-colors {{ request()->routeIs('admin.setting*') ? 'bg-blue-900 text-white hover:bg-blue-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium text-sm md:text-base">Setting</span>
                </div>
                <svg id="setting-chevron" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div id="setting-menu" class="mt-1 space-y-1 {{ request()->routeIs('admin.setting*') ? '' : 'hidden' }}">
                <a href="{{ route('admin.setting.alur-pendaftaran') }}" class="block px-3 md:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.setting.alur-pendaftaran') ? 'bg-gray-100 font-medium' : '' }}">
                    Alur Pendaftaran
                </a>
                <a href="{{ route('admin.setting.dokumen-persyaratan') }}" class="block px-3 md:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.setting.dokumen-persyaratan') ? 'bg-gray-100 font-medium' : '' }}">
                    Dokumen Persyaratan
                </a>
                <a href="{{ route('admin.setting.jadwal-pendaftaran') }}" class="block px-3 md:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.setting.jadwal-pendaftaran') ? 'bg-gray-100 font-medium' : '' }}">
                    Jadwal Pendaftaran
                </a>
                <a href="{{ route('admin.setting.jadwal-wisuda') }}" class="block px-3 md:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.setting.jadwal-wisuda') ? 'bg-gray-100 font-medium' : '' }}">
                    Jadwal Wisuda
                </a>
                <a href="{{ route('admin.setting.kuota-wisudawan') }}" class="block px-3 md:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.setting.kuota-wisudawan') ? 'bg-gray-100 font-medium' : '' }}">
                    Kuota Wisudawan
                </a>
            </div>
        </div>
    </nav>
</aside>

@push('scripts')
<script>
(function() {
    'use strict';

    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const openSidebarBtn = document.getElementById('open-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar');

    function openSidebar() {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }
    }

    function closeSidebar() {
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
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

    const settingToggle = document.getElementById('setting-toggle');
    const settingMenu = document.getElementById('setting-menu');
    const settingChevron = document.getElementById('setting-chevron');

    if (settingMenu && settingChevron) {
        const isSettingOpen = !settingMenu.classList.contains('hidden');

        if (isSettingOpen) {
            settingChevron.classList.add('rotate-180');
        }

        if (settingToggle) {
            let isOpen = isSettingOpen;

            settingToggle.addEventListener('click', function() {
                isOpen = !isOpen;
                settingMenu.classList.toggle('hidden', !isOpen);
                settingChevron.classList.toggle('rotate-180', isOpen);
            });
        }
    }
})();
</script>
@endpush