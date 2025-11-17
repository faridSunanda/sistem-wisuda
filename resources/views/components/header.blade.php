<header id="main-header" class="w-full fixed top-0 left-0 z-50 bg-white/95 backdrop-blur-sm shadow-md transition-transform duration-300 ease-in-out">
    <nav class="w-full bg-white/95 backdrop-blur-sm px-4 md:px-6 lg:px-8 py-3 md:py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 md:gap-4 group animate-fade-in">
            <img src="{{ asset('img/Unwahas.png') }}" alt="Logo"
                class="h-10 md:h-12 w-auto transform transition-transform duration-300 group-hover:scale-105" />
            <div class="leading-tight opacity-0 animate-slide-in">
                <h1 class="text-sm md:text-base lg:text-lg font-bold text-slate-800">Sistem Informasi Wisuda</h1>
                <p class="text-xs md:text-sm text-slate-600">Universitas Wahid Hasyim</p>
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

    #main-header.hide-header {
        transform: translateY(-100%);
    }
</style>

<script>
    let lastScrollTop = 0;
    const header = document.getElementById('main-header');
    let scrollThreshold = 100;

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > scrollThreshold) {
            if (scrollTop > lastScrollTop) {
                // Scrolling down
                header.classList.add('hide-header');
            } else {
                // Scrolling up
                header.classList.remove('hide-header');
            }
        } else {
            // Near top, always show
            header.classList.remove('hide-header');
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
</script>
