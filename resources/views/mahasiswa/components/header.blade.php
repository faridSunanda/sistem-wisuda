<header id="main-header"
    class="bg-white border-b border-gray-200 px-4 md:px-6 py-3 md:py-4 sticky top-0 z-30 transition-all duration-300">
    <div class="flex items-center justify-between">
        <button id="open-sidebar"
            class="lg:hidden p-2 text-gray-700 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="flex items-center gap-2 ml-auto">
            <div
                class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold text-sm md:text-base shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
            </div>
            <div class="relative">
                <button id="user-menu-button"
                    class="flex items-center gap-2 text-gray-700 hover:text-gray-900 px-2 py-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <span
                        class="font-medium text-sm md:text-base hidden sm:inline">{{ auth()->user()->name ?? 'Mahasiswa' }}</span>
                    <svg id="user-menu-chevron" class="w-4 h-4 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="user-menu-dropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>Ganti Role</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to SSO</span>
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 transition-colors rounded-b-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        (function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuChevron = document.getElementById('user-menu-chevron');
            const mainHeader = document.getElementById('main-header');

            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = !userMenuDropdown.classList.contains('hidden');

                    if (isOpen) {
                        userMenuDropdown.classList.add('hidden');
                        userMenuChevron.classList.remove('rotate-180');
                    } else {
                        userMenuDropdown.classList.remove('hidden');
                        userMenuChevron.classList.add('rotate-180');
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                        userMenuChevron.classList.remove('rotate-180');
                    }
                });
            }

            if (mainHeader) {
                window.addEventListener('scroll', function() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                    if (scrollTop > 10) {
                        mainHeader.classList.remove('bg-white');
                        mainHeader.classList.add('bg-white/60', 'backdrop-blur-2xl');
                    } else {
                        mainHeader.classList.remove('bg-white/60', 'backdrop-blur-2xl');
                        mainHeader.classList.add('bg-white');
                    }
                });
            }
        })();
    </script>
@endpush
