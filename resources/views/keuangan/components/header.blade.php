<header
    class="h-16 bg-white/80 backdrop-blur-sm flex justify-between items-center px-4 md:px-6 shrink-0 sticky top-0 z-10 shadow-sm">
    <!-- Hamburger Menu for Mobile -->
    <button @click.stop="sidebarOpen = !sidebarOpen"
        class="lg:hidden w-10 h-10 flex items-center justify-center rounded-full text-slate-600 hover:bg-slate-200 transition-colors"
        aria-label="Toggle Sidebar">
        <i class="fas fa-bars-staggered"></i>
    </button>

    <!-- Spacer for Desktop -->
    <div class="hidden lg:block"></div>

    <!-- User Menu -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
            class="flex items-center gap-3 rounded-full p-1 pl-3 pr-2 hover:bg-slate-100 transition-colors duration-300">
            <span class="font-semibold text-slate-700 hidden sm:inline">{{ Auth::user()->name_lengkap ?? 'Admin' }}</span>
            <img src="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" alt="User Avatar"
                class="w-8 h-8 rounded-full object-cover" />
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white min-w-[200px] rounded-lg shadow-xl absolute right-0 top-full mt-2 z-50 py-2 border border-slate-200"
            style="display: none;">

            <!-- User Info -->
            @auth
                <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#435ebe]/10 flex items-center justify-center text-[#435ebe]">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name_lengkap }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ ucfirst(Auth::user()->role ?? 'Keuangan') }}</p>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Ganti Role -->
            <a href="#"
                class="flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe] transition-colors duration-200">
                <i class="fas fa-user-friends w-5 text-center text-slate-400"></i>
                <span>Ganti Role</span>
            </a>

            <!-- Back to SSO -->
            <a href="#"
                class="flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe] transition-colors duration-200">
                <i class="fas fa-right-left w-5 text-center text-slate-400"></i>
                <span>Back to SSO</span>
            </a>

            <!-- View Front Page -->
            <a href="/"
                class="flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe] transition-colors duration-200">
                <i class="fas fa-globe w-5 text-center text-slate-400"></i>
                <span>Lihat Halaman Depan</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                    <i class="fas fa-right-from-bracket w-5 text-center text-slate-400"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>
