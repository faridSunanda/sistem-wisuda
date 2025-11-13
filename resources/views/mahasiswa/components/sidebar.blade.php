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
        <a href="{{ route('mahasiswa.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.dashboard') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
            <i class="fas fa-table-columns text-lg w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <div class="pt-4">
            <p class="px-3 md:px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pendaftaran</p>
        
            {{-- [PERUBAHAN]: Menambahkan <div> dengan space-y-2 di sini --}}
            <div class="space-y-2">

                <a href="{{ route('mahasiswa.pembayaran') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.pembayaran*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                    <i class="fas fa-credit-card text-lg w-5 text-center"></i>
                    <span class="font-medium">Pembayaran</span>
                </a>
                
                <a href="{{ route('mahasiswa.biodata.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.biodata.*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                    <i class="fas fa-id-card text-lg w-5 text-center"></i>
                    <span class="font-medium">Data Diri</span>
                </a>

                <div x-data="{ sertifikatOpen: {{ request()->routeIs('mahasiswa.sertifikat.*') ? 'true' : 'false' }} }">
                    <button @click="sertifikatOpen = !sertifikatOpen"
                        class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.*') ? 'text-[#435ebe] bg-[#435ebe]/10 border border-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-scroll text-lg w-5 text-center"></i>
                            <span class="font-medium">Sertifikat</span>
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                            :class="{ 'rotate-180': sertifikatOpen, 'text-[#435ebe]': sertifikatOpen }"></i>
                    </button>

                    <div x-show="sertifikatOpen" x-collapse class="mt-1 space-y-1 pl-11 border-l-2 border-[#435ebe]/20 ml-4">
                        <a href="{{ route('mahasiswa.sertifikat.kompetensi') }}"
                            class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.kompetensi') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                            Sertifikat Kompetensi
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.bahasa-internasional') }}"
                            class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.bahasa-internasional') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                            Sertifikat Bahasa Internasional
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.magang') }}"
                            class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.magang') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                            Sertifikat Magang
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.pendidikan-karakter') }}"
                            class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.pendidikan-karakter') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                            Sertifikat Pendidikan Karakter
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.penghargaan') }}"
                            class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.penghargaan') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                            Sertifikat Penghargaan
                        </a>
                        <a href="{{ route('mahasiswa.sertifikat.organisasi') }}"
                            class="block rounded-lg px-3 py-2 text-sm transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.organisasi') ? 'text-[#435ebe] bg-[#435ebe]/10 font-medium' : 'text-gray-600 hover:bg-[#435ebe]/10 hover:text-[#435ebe]' }} hover:pl-4">
                            Sertifikat Organisasi
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('mahasiswa.download-formulir') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.download-formulir') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                    <i class="fas fa-file-arrow-down text-lg w-5 text-center"></i>
                    <span class="font-medium">Download Formulir</span>
                </a>

            </div> {{-- [PERUBAHAN]: Penutup <div> --}}

        </div>
    </nav>
</aside>

<div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-gray-500/20 backdrop-blur-sm transition-opacity duration-300 lg:hidden"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>