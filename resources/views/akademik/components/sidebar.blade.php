<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-none shadow-xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">


    <div class="h-16 border-b border-gray-200 flex items-center justify-between px-4 md:px-6">
        <div class="flex items-center gap-3 min-w-0 flex-1">
            <img src="{{ asset('img/Unwahas.png') }}" alt="Logo" class="h-8 md:h-10 w-auto flex-shrink-0">
            <div class="flex flex-col min-w-0 flex-1">
                <span class="text-xs sm:text-sm font-semibold text-gray-800 truncate">Sistem Informasi Wisuda</span>
                <span class="text-[10px] sm:text-xs text-gray-500 truncate">Universitas Wahid Hasyim</span>
            </div>
        </div>

        <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 flex-shrink-0">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>


    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

        <a href="{{ route('akademik.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('akademik.dashboard') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
            <i class="fas fa-table-columns text-lg w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <p class="px-3 md:px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-4">MENU</p>

        <a href="{{ route('akademik.data-wisudawan.index') }}"
        class="mt-1 flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('akademik.data-wisudawan*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
            <i class="fas fa-user-graduate text-lg w-5 text-center"></i>
            <span class="font-medium">Data Wisudawan</span>
        </a>

    </nav>
</aside>
