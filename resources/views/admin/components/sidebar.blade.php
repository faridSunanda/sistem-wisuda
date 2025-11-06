<aside
    class="fixed inset-y-0 left-0 z-30 flex h-screen w-64 flex-col overflow-y-auto bg-white shadow-xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-lg"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200/60">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="h-8 w-9 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center">
                    <img src="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" alt="Logo UNWAHAS" class="h-4 w-4">
                </div>
                <div class="flex flex-col">
                    <span class="text-[13px] font-semibold text-slate-800">Sistem Informasi Wisuda</span>
                    <span class="text-[11px] text-slate-500">UNWAHAS</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6">
        <ul class="space-y-1">

            <!-- Dashboard - Active -->
            <li>
                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium text-white bg-[#435EBE] shadow-md shadow-blue-500/20 transition-all duration-200 hover:bg-[#3a52a8] hover:shadow-lg hover:shadow-blue-500/30">
                    <i class="fa-solid fa-table-columns fa-fw text-lg"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>
            </li>

            <!-- Data Diri -->
            <li>
                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium text-slate-700 transition-all duration-200 hover:bg-blue-50 hover:text-[#435EBE] hover:shadow-sm border border-transparent hover:border-blue-100">
                    <i class="fa-solid fa-address-card fa-fw text-lg"></i>
                    <span>Data Diri</span>
                </a>
            </li>

            <!-- Sertifikat - Dropdown -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-3 font-medium text-slate-700 transition-all duration-200 hover:bg-blue-50 hover:text-[#435EBE] border border-transparent hover:border-blue-100">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-certificate fa-fw text-lg"></i>
                        <span>Sertifikat</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open, 'text-[#435EBE]': open }"></i>
                </button>
                <ul x-show="open" x-collapse class="mt-1 space-y-1 pl-11 border-l-2 border-blue-100 ml-4">
                    <li>
                        <a href="#"
                            class="block rounded-lg px-3 py-2 text-sm text-slate-600 transition-all duration-200 hover:bg-blue-50 hover:text-[#435EBE] hover:pl-4">
                            Sub Menu 1
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="block rounded-lg px-3 py-2 text-sm text-slate-600 transition-all duration-200 hover:bg-blue-50 hover:text-[#435EBE] hover:pl-4">
                            Sub Menu 2
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Download Formulir -->
            <li>
                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium text-slate-700 transition-all duration-200 hover:bg-blue-50 hover:text-[#435EBE] hover:shadow-sm border border-transparent hover:border-blue-100">
                    <i class="fa-solid fa-download fa-fw text-lg"></i>
                    <span>Download Formulir</span>
                </a>
            </li>

            <!-- Separator -->
            <li class="pt-4">
                <div class="border-t border-slate-100"></div>
            </li>

    </nav>
</aside>

<!-- Backdrop for Mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 z-20 bg-opacity-50 transition-opacity duration-300 lg:hidden"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>
