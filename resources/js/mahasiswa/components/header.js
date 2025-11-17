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
                if (userMenuChevron) {
                    userMenuChevron.classList.remove('rotate-180');
                }
            } else {
                userMenuDropdown.classList.remove('hidden');
                if (userMenuChevron) {
                    userMenuChevron.classList.add('rotate-180');
                }
            }
        });

        document.addEventListener('click', function(e) {
            if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                userMenuDropdown.classList.add('hidden');
                if (userMenuChevron) {
                    userMenuChevron.classList.remove('rotate-180');
                }
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

