<header
    class="h-16 bg-white/80 backdrop-blur-sm flex justify-between items-center px-4 md:px-6 shrink-0 sticky top-0 z-10 shadow-sm">
    <button @click.stop="sidebarOpen = !sidebarOpen"
        class="lg:hidden w-10 h-10 flex items-center justify-center rounded-full text-slate-600 hover:bg-slate-200 transition-colors"
        aria-label="Toggle Sidebar">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>

    <div class="hidden lg:block"></div>

    <div x-data="{ open: false }" class="relative">

        <button @click="open = !open"
            class="flex items-center gap-3 rounded-full p-1 pl-3 pr-2 hover:bg-slate-100 transition-colors duration-300">
            <span class="font-semibold text-slate-700 hidden sm:inline">{{ auth()->user()?->name }}</span>
            <img src="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" alt="User Avatar"
                class="w-8 h-8 rounded-full object-cover" />
        </button>

        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white min-w-[200px] rounded-lg shadow-xl absolute right-0 top-full mt-2 z-50 py-2"
            style="display: none;">
            @auth
                <div class="px-4 py-2 border-b">
                    <p class="font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
            @endauth

            {{--            @auth --}}
            {{--                <div class="px-4 py-2 border-b"> --}}
            {{--                    <p class="font-semibold text-slate-800">{{ Auth::user()->name }}</p> --}}
            {{--                    <p class="text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</p> --}}
            {{--                </div> --}}
            {{--            @endauth --}}

            <a href="#"
                class="flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                <i class="fa-solid fa-right-left w-5 text-center text-slate-400"></i>
                <span>Back to SSO</span>
            </a>
            <a href="/"
                class="flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                <i class="fa-solid fa-globe w-5 text-center text-slate-400"></i>
                <span>Lihat Halaman Depan</span>
            </a>

            <form>
                {{-- <form method="#" action="#"> --}}
                {{-- @csrf --}}
                <button type="submit"
                    class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center text-slate-400"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>
