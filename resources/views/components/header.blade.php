<header id="main-header" class="w-full sticky top-0 left-0 z-50 bg-white shadow-sm transition-shadow duration-300">
    <nav class="w-full bg-white px-4 py-3 flex items-center gap-3">
        <a href="/" class="flex items-center gap-3 group animate-fade-in">
            <img src="{{ asset('img/Unwahas.png') }}" alt="Logo"
                class="h-10 w-auto transform transition-transform duration-500 group-hover:scale-105 group-hover:rotate-3" />
            <div class="leading-tight opacity-0 animate-slide-in">
                <h1 class="text-base md:text-lg font-semibold text-slate-800">Sistem Informasi Wisuda</h1>
                <p class="text-xs md:text-sm text-slate-500">Universitas Wahid Hasyim</p>
            </div>
        </a>
    </nav>
</header>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }

    .animate-slide-in {
        animation: slideIn 1s ease-out 0.3s forwards;
    }
</style>
