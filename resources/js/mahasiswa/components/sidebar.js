(function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const openSidebarBtn = document.getElementById('open-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const sertifikatToggle = document.getElementById('sertifikat-toggle');
    const sertifikatMenu = document.getElementById('sertifikat-menu');
    const sertifikatChevron = document.getElementById('sertifikat-chevron');

    if (!sidebar) return;

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        if (sidebarOverlay) {
            sidebarOverlay.classList.remove('hidden');
        }
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        if (sidebarOverlay) {
            sidebarOverlay.classList.add('hidden');
        }
        document.body.style.overflow = '';
    }

    if (openSidebarBtn) {
        openSidebarBtn.addEventListener('click', openSidebar);
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    document.querySelectorAll('nav a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    if (sertifikatToggle && sertifikatMenu && sertifikatChevron) {
        const isActive = sertifikatMenu.querySelector('a.bg-blue-50');
        if (isActive || sertifikatMenu.classList.contains('block')) {
            sertifikatChevron.classList.add('rotate-180');
        }

        sertifikatToggle.addEventListener('click', function() {
            const isOpen = !sertifikatMenu.classList.contains('hidden');
            sertifikatMenu.classList.toggle('hidden');
            sertifikatChevron.classList.toggle('rotate-180');

            if (!sertifikatMenu.classList.contains('hidden')) {
                sertifikatToggle.classList.add('bg-blue-900', 'text-white');
                sertifikatToggle.classList.remove('text-gray-700', 'hover:bg-gray-100');
            } else {
                if (!isActive) {
                    sertifikatToggle.classList.remove('bg-blue-900', 'text-white');
                    sertifikatToggle.classList.add('text-gray-700', 'hover:bg-gray-100');
                }
            }
        });
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('hidden');
            }
            document.body.style.overflow = '';
        }
    });
})();

