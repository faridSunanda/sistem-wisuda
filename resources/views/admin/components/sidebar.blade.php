<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-none shadow-xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Header Sidebar -->
    <div class="h-16 border-b border-gray-200 flex items-center justify-between px-4 md:px-6">
        <div class="flex items-center gap-3 min-w-0 flex-1">
            <img src="{{ asset('img/Unwahas.png') }}" alt="Logo" class="h-8 md:h-10 w-auto flex-shrink-0">
            <div class="flex flex-col min-w-0 flex-1">
                <span class="text-xs sm:text-sm font-semibold text-gray-800 truncate">Sistem Informasi Wisuda</span>
                <span class="text-[10px] sm:text-xs text-gray-500 truncate">Universitas Wahid Hasyim</span>
            </div>
        </div>
        <!-- Close Button for Mobile -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 flex-shrink-0">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
            <i class="fas fa-table-columns text-lg w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Menu Label -->
        <p class="px-3 md:px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-4">MENU</p>

        <!-- Master - Dropdown -->
        <div x-data="{ masterOpen: {{ request()->routeIs('admin.setting*') || request()->routeIs('admin.master*') ? 'true' : 'false' }} }">
            <button @click="masterOpen = !masterOpen"
                class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.setting*') || request()->routeIs('admin.master*') ? 'text-[#435ebe] bg-[#435ebe]/10 border border-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-layer-group text-lg w-5 text-center"></i>
                    <span class="font-medium">Master Data</span>
                </span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                    :class="{ 'rotate-180': masterOpen, 'text-[#435ebe]': masterOpen }"></i>
            </button>

            <div x-show="masterOpen" x-collapse class="mt-1 space-y-1 pl-11 border-l-2 border-[#435ebe]/20 ml-4">

                <a href="{{ route('admin.setting.alur-pendaftaran.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('admin.setting.alur-pendaftaran*') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                    Alur Pendaftaran
                </a>

                <a href="{{ route('admin.setting.dokumen-persyaratan.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('admin.setting.dokumen-persyaratan*') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                    Dokumen Syarat
                </a>

                <a href="#"
                    class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('admin.master.group*') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                    Group
                </a>

                <a href="#"
                    class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('admin.master.sesi*') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                    Sesi
                </a>
            </div>
        </div>

        <!-- Data Wisudawan -->
        <a href="{{ route('admin.data-wisudawan.index') }}"
            class="mt-1 flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.data-wisudawan*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
            <i class="fas fa-user-graduate text-lg w-5 text-center"></i>
            <span class="font-medium">Data Wisudawan</span>
        </a>

        <div x-data="{ settingOpen: {{ request()->routeIs('admin.setting*') ? 'true' : 'false' }} }">
            <button @click="settingOpen = !settingOpen"
                class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.setting*') ? 'text-[#435ebe] bg-[#435ebe]/10 border border-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-gear text-lg w-5 text-center"></i>
                    <span>Wisuda</span>
                </span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                    :class="{ 'rotate-180': settingOpen, 'text-[#435ebe]': settingOpen }"></i>
            </button>

            <!-- Submenu Setting -->
            <div x-show="settingOpen" x-collapse class="mt-1 space-y-1 pl-11 border-l-2 border-[#435ebe]/20 ml-4">
                <!-- Alur Pendaftaran -->
                <a href="#"
                    class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('admin.setting.alur-pendaftaran*') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                    Wisuda
                </a>

                <a href="#"
                    class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('admin.setting.dokumen-persyaratan*') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                    Jadwal Pelaksanaan
                </a>

            </div>
        </div>

        <!-- Download -->
        <a href="#"
            class="mt-1 flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.download-ppt*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
            <i class="fas fa-file-powerpoint text-lg w-5 text-center"></i>
            <span class="font-medium">Download PPT</span>
        </a>

    </nav>
</aside>
