//pendaftar
const INFO_TEXT_FADE_DELAY = 500;
const INFO_TEXT_MESSAGE_INTERVAL = 3000;
//Alur pendaftaran
const ALUR_SCROLL_SPEED_DESKTOP = 1.9;
const ALUR_SCROLL_SPEED_MOBILE = 1.5;
const ALUR_INIT_DELAY_DESKTOP = 800;
const ALUR_INIT_DELAY_MOBILE = 800;
const ALUR_BOUNCE_PAUSE_DELAY = 2000;
const ALUR_MOBILE_BREAKPOINT = 640;
const ALUR_RETRY_DELAY = 100;
const ALUR_MAX_RETRIES = 50;

window.initInfoTextAnimation = function(persentase) {
    const init = () => {
        const infoText = document.getElementById('info-text');
        if (!infoText) return;

        const messages = getMessagesByPercentage(persentase);
        if (messages.length <= 1) return;

        let currentIndex = 0;
        let intervalId = null;

        const updateMessage = () => {
            infoText.classList.add('fade-out');
            setTimeout(() => {
                currentIndex = (currentIndex + 1) % messages.length;
                infoText.innerHTML = messages[currentIndex];
                infoText.classList.remove('fade-out');
                infoText.classList.add('fade-in');
            }, INFO_TEXT_FADE_DELAY);
        };

        intervalId = setInterval(updateMessage, INFO_TEXT_MESSAGE_INTERVAL);

        window.addEventListener('beforeunload', () => {
            if (intervalId) clearInterval(intervalId);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
};

function getMessagesByPercentage(persentase) {
    if (persentase >= 100) {
        return [
            '<span class="text-red-600 font-bold">Kuota Penuh!</span>',
            '<span class="text-red-600 font-bold">Pendaftaran Ditutup</span>'
        ];
    }

    if (persentase >= 80) {
        return [
            `<span class="text-orange-600 font-bold">${persentase.toFixed(1)}% kuota telah terpenuhi</span>`,
            '<span class="text-orange-600 font-bold">Kuota Hampir Penuh!</span>',
            '<span class="text-orange-600 font-bold">Segera Daftar!</span>'
        ];
    }

    return [
        `<span class="font-semibold text-gray-800">${persentase.toFixed(1)}% kuota telah terpenuhi</span>`,
        '<span class="font-semibold text-gray-800">Lengkapi syarat pendaftaran</span>',
        '<span class="font-semibold text-gray-800">Segera Daftar!</span>'
    ];
}

window.initAlurAutoScroll = function() {
    let retryCount = 0;

    const init = () => {
        const alurWrapper = document.getElementById('alurWrapper');
        if (!alurWrapper) {
            if (retryCount++ < ALUR_MAX_RETRIES) {
                setTimeout(init, ALUR_RETRY_DELAY);
            }
            return;
        }

        const isMobile = window.innerWidth <= ALUR_MOBILE_BREAKPOINT;
        const waitDelay = isMobile ? ALUR_INIT_DELAY_MOBILE : ALUR_INIT_DELAY_DESKTOP;
        setTimeout(() => setupAutoScroll(alurWrapper), waitDelay);
    };

    const setupAutoScroll = (wrapper) => {
        const isMobile = window.innerWidth <= ALUR_MOBILE_BREAKPOINT;
        let scrollDirection = 1;
        let currentScroll = 0;
        let animationFrameId = null;
        let isPaused = false;
        let pauseTimeout = null;

        const container = wrapper.querySelector('.alur-container');
        if (!container) return;

        const calculateMaxScroll = () => {
            if (isMobile) {
                // Untuk mobile
                const containerHeight = container.scrollHeight;
                const wrapperHeight = wrapper.clientHeight;
                const maxScroll = Math.max(0, containerHeight - wrapperHeight);
                return maxScroll;
            } else {
                const containerSize = container.scrollWidth || container.offsetWidth;
                const wrapperSize = wrapper.clientWidth;
                return Math.max(0, containerSize - wrapperSize);
            }
        };

        const setScroll = (value) => {
            if (isMobile) {
                // Untuk mobile
                container.style.transform = `translateY(-${value}px)`;
                container.style.willChange = 'transform';
            } else {
                container.style.transform = `translateX(-${value}px)`;
                container.style.willChange = 'transform';
            }
        };

        const pauseAndBounce = (newDirection) => {
            isPaused = true;
            if (pauseTimeout) clearTimeout(pauseTimeout);
            pauseTimeout = setTimeout(() => {
                scrollDirection = newDirection;
                isPaused = false;
            }, ALUR_BOUNCE_PAUSE_DELAY);
        };

        const autoScroll = () => {
            if (isPaused) {
                animationFrameId = requestAnimationFrame(autoScroll);
                return;
            }

            const maxScroll = calculateMaxScroll();
            if (maxScroll <= 0) {
                animationFrameId = requestAnimationFrame(autoScroll);
                return;
            }

            const scrollSpeed = isMobile ? ALUR_SCROLL_SPEED_MOBILE : ALUR_SCROLL_SPEED_DESKTOP;
            currentScroll += scrollSpeed * scrollDirection;

            if (currentScroll > maxScroll) {
                currentScroll = maxScroll;
                setScroll(currentScroll);
                pauseAndBounce(-1);
            } else if (currentScroll < 0) {
                currentScroll = 0;
                setScroll(currentScroll);
                pauseAndBounce(1);
            } else {
                setScroll(currentScroll);
            }

            animationFrameId = requestAnimationFrame(autoScroll);
        };

        // Untuk mobile, tunggu sebentar untuk memastikan layout sudah selesai
        if (isMobile) {
            setTimeout(() => {
                const maxScroll = calculateMaxScroll();
                if (maxScroll > 0) {
                    autoScroll();
                }
            }, 500);
        } else {
            autoScroll();
        }

        window.addEventListener('beforeunload', () => {
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            if (pauseTimeout) clearTimeout(pauseTimeout);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
};

// Initialize all portal features
window.initPortal = function(options = {}) {
    const { persentase } = options;

    const initializeFeatures = function() {
        setTimeout(function() {
            // Initialize info text animation
            if (persentase !== undefined && typeof window.initInfoTextAnimation === 'function') {
                window.initInfoTextAnimation(persentase);
            }

            // Auto scroll disabled - all content now visible
            // if (typeof window.initAlurAutoScroll === 'function') {
            //     window.initAlurAutoScroll();
            // } else {
            //     // Retry jika function belum tersedia
            //     setTimeout(function() {
            //         if (typeof window.initAlurAutoScroll === 'function') {
            //             window.initAlurAutoScroll();
            //         }
            //     }, 500);
            // }
        }, 100);
    };

    // Jika halaman sudah ter-load, langsung jalankan
    if (document.readyState === 'complete') {
        initializeFeatures();
    } else {
        // Jika belum, tunggu event load
        window.addEventListener('load', initializeFeatures);
    }
};
