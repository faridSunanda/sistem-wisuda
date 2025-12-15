<header
    class="h-16 bg-white/80 backdrop-blur-sm flex items-center px-4 md:px-6 shrink-0 sticky top-0 z-10 shadow-sm">
    <button @click.stop="sidebarOpen = !sidebarOpen"
        class="w-10 h-10 flex items-center justify-center rounded-full text-slate-600 hover:bg-slate-200 transition-colors"
        aria-label="Toggle Sidebar">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>

    <div class="flex-1"></div>

    <div x-data="{ open: false }" class="relative">

        <button @click="open = !open"
            class="flex items-center gap-3 rounded-full p-1 pl-3 pr-2 hover:bg-slate-100 transition-colors duration-300">
            <span class="font-semibold text-slate-700 hidden sm:inline">{{ auth()->user()?->name ?? 'Admin' }}</span>
            <img src="https://sicantik.unwahas.ac.id/assets/images/Unwahas.png" alt="User Avatar" class="w-8 h-8 rounded-full object-cover" />
        </button>

        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white min-w-[200px] rounded-lg shadow-xl absolute right-0 top-full mt-2 z-50 py-2 border border-slate-200"
            style="display: none;">
            @auth
                <div class="px-4 py-2 border-b">
                    <p class="font-semibold text-slate-800">{{ Auth::user()->name_lengkap ?? Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Role: <span id="activeRoleLabel">{{ ucfirst(session('acting_role', Auth::user()->role)) }}</span></p>
                </div>
            @endauth

            @if(session('allow_role_switch'))
                <div class="px-4 py-3 border-b space-y-2">
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        <i class="fa-solid fa-shuffle" style="color: #435EBE;"></i>
                        <span>Ganti Role</span>
                    </div>
                    @php
                        $roles = [
                            'admin' => 'Admin',
                            'akademik' => 'Akademik',
                            'keuangan' => 'Keuangan',
                            'mahasiswa' => 'Mahasiswa'
                        ];
                        $current = session('acting_role', auth()->user()->role);
                    @endphp
                    <div class="relative" id="roleSwitcherWrapper">
                        <button type="button" id="roleSwitcherBtn"
                            class="w-full flex items-center justify-between text-sm font-semibold text-slate-800 px-3 py-2.5 rounded-xl border bg-gradient-to-r from-white to-[#435EBE]/5 hover:border-[#435EBE] focus:outline-none focus:ring-2 focus:ring-[#435EBE] transition"
                            style="border-color: rgba(67, 94, 190, 0.3); box-shadow: 0 8px 20px rgba(67, 94, 190, 0.12);">
                            <span id="roleSwitcherLabel">{{ $roles[$current] ?? ucfirst($current) }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px]" style="color: #435EBE;"></i>
                        </button>
                        <div id="roleSwitcherMenu"
                             class="hidden absolute left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-50">
                            @foreach($roles as $value => $label)
                                <button type="button"
                                    class="role-option w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-[#435EBE]/10 hover:text-[#435EBE] transition flex items-center gap-2 {{ $current === $value ? 'bg-[#435EBE]/10 text-[#435EBE]' : 'text-slate-700' }}"
                                    data-role="{{ $value }}">
                                    <span class="inline-block w-2 h-2 rounded-full {{ $current === $value ? 'bg-[#435EBE]' : 'bg-slate-300' }}"></span>
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('roleSwitcherBtn');
        const menu = document.getElementById('roleSwitcherMenu');
        const label = document.getElementById('roleSwitcherLabel');
        const wrapper = document.getElementById('roleSwitcherWrapper');
        const roleSection = wrapper?.closest('div');
        let globalOverlay = null;

        const closeMenu = () => {
            if (menu) menu.classList.add('hidden');
        };
        const openMenu = () => {
            if (menu) menu.classList.remove('hidden');
        };

        const showLoading = (text = 'Mengganti role...') => {
            if (!globalOverlay) {
                globalOverlay = document.createElement('div');
                globalOverlay.id = 'globalRoleSwitchOverlay';
                globalOverlay.className = 'fixed inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-[9999]';
                globalOverlay.innerHTML = `
                    <div class="flex flex-col items-center gap-3 text-sm text-slate-600">
                        <div class="w-10 h-10 border-3 border-t-transparent rounded-full animate-spin" style="border-color: #435EBE; border-top-color: transparent;"></div>
                        <span id="globalRoleSwitchText">` + text + `</span>
                    </div>
                `;
                document.body.appendChild(globalOverlay);
            } else {
                globalOverlay.classList.remove('hidden');
                const txt = document.getElementById('globalRoleSwitchText');
                if (txt) {
                    txt.textContent = text;
                }
            }

            if (roleSection) {
                let overlay = document.getElementById('roleSwitchLoader');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'roleSwitchLoader';
                    overlay.className = 'absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center rounded-xl';
                    overlay.innerHTML = '<div class="w-8 h-8 border-2 border-t-transparent rounded-full animate-spin" style="border-color: #435EBE; border-top-color: transparent;"></div>';
                    overlay.style.zIndex = '60';
                    roleSection.style.position = 'relative';
                    roleSection.appendChild(overlay);
                } else {
                    overlay.classList.remove('hidden');
                }
            }
        };

        const hideLoading = () => {
            if (globalOverlay) {
                globalOverlay.classList.add('hidden');
            }
            const overlay = document.getElementById('roleSwitchLoader');
            if (overlay) {
                overlay.classList.add('hidden');
            }
        };

        if (btn && menu && wrapper) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !menu.classList.contains('hidden');
                isOpen ? closeMenu() : openMenu();
            });
        }

        if (menu && wrapper && btn && label) {
            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    closeMenu();
                }
            });

            menu.querySelectorAll('.role-option').forEach(option => {
                option.addEventListener('click', () => {
                    const selectedRole = option.dataset.role;
                    if (!selectedRole) return;

                    showLoading();
                    fetch('{{ route('switch-role') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ role: selectedRole })
                    }).then(resp => resp.json())
                    .then(data => {
                        if (data.success) {
                            label.textContent = option.textContent.trim();
                            closeMenu();
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                location.reload();
                            }
                        } else {
                            hideLoading();
                            alert(data.message || 'Gagal mengganti role');
                        }
                    }).catch(() => {
                        hideLoading();
                        alert('Terjadi kesalahan saat mengganti role');
                    });
                });
            });
        }
    });
</script>
