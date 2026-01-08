<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transform transition-transform duration-300 ease-in-out lg:shadow-none shadow-xl"
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

            <div class="space-y-2">
                @php
                    $biodata = Auth::user()->biodata;
                    $isPaid = $biodata && $biodata->is_bayar;
                    // Unlock menus only if ACADEMIC verification is true (as requested)
                    $isUnlocked = $biodata && $biodata->is_verified_akademik;
                    // Download formulir harus terkunci sampai data diri divalidasi oleh keuangan
                    // Jadi perlu is_verified_keuangan (pembayaran) DAN is_verified_akademik (data diri)
                    $canDownloadFormulir = $biodata && $biodata->is_verified_keuangan && $biodata->is_verified_akademik;
                    
                    $disabledClass = 'opacity-50 cursor-not-allowed pointer-events-none bg-gray-100';
                @endphp

                <a href="{{ route('mahasiswa.pembayaran.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.pembayaran*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                    <i class="fas fa-credit-card text-lg w-5 text-center"></i>
                    <span class="font-medium">Pembayaran</span>
                </a>
                
                <a href="{{ $isUnlocked ? route('mahasiswa.biodata.index') : '#' }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.biodata.*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }} {{ !$isUnlocked ? $disabledClass : '' }}">
                    <i class="fas fa-id-card text-lg w-5 text-center"></i>
                    <span class="font-medium">Data Diri</span>
                    @if(!$isUnlocked)
                        <i class="fas fa-lock ml-auto text-xs text-gray-400"></i>
                    @endif
                </a>

                <div x-data="{ sertifikatOpen: {{ request()->routeIs('mahasiswa.sertifikat.*') ? 'true' : 'false' }} }" class="{{ !$isUnlocked ? $disabledClass . ' rounded-lg' : '' }}">
                    <button @click="sertifikatOpen = !sertifikatOpen" {{ !$isUnlocked ? 'disabled' : '' }}
                        class="flex w-full items-center justify-between gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.sertifikat.*') ? 'text-[#435ebe] bg-[#435ebe]/10 border border-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }}">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-scroll text-lg w-5 text-center"></i>
                            <span class="font-medium">Sertifikat</span>
                        </span>
                        @if(!$isUnlocked)
                             <i class="fas fa-lock text-xs text-gray-400"></i>
                        @else
                             <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                :class="{ 'rotate-180': sertifikatOpen, 'text-[#435ebe]': sertifikatOpen }"></i>
                        @endif
                    </button>

                    <div x-show="sertifikatOpen" x-collapse
                        class="mt-1 space-y-1 pl-11 border-l-2 border-[#435ebe]/20 ml-4">
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
                
                <a href="{{ $canDownloadFormulir ? route('mahasiswa.download-formulir.index') : '#' }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('mahasiswa.download-formulir*') ? 'text-white bg-[#435ebe] shadow-md shadow-[#435ebe]/20' : 'text-gray-700 hover:bg-[#435ebe]/10 hover:text-[#435ebe] border border-transparent hover:border-[#435ebe]/20' }} {{ !$canDownloadFormulir ? $disabledClass : '' }}">
                    <i class="fas fa-file-arrow-down text-lg w-5 text-center"></i>
                    <span class="font-medium">Download Formulir</span>
                    @if(!$canDownloadFormulir)
                        <i class="fas fa-lock ml-auto text-xs text-gray-400"></i>
                    @endif
                </a>
            </div>
    </nav>
</aside>

@push('scripts')
    @vite('resources/js/mahasiswa/components/sidebar.js')
@endpush
