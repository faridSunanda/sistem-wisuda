(function() {
    const main = document.querySelector('main');
    const footer = document.getElementById('main-footer');
    
    if (!main || !footer) return;

    let lastTop = 0;
    
    function updateFooterVisibility() {
        const top = main.scrollTop;
        const height = main.clientHeight;
        const scrollHeight = main.scrollHeight;
        const scrollingDown = top > lastTop;
        lastTop = top;

        const scrollable = scrollHeight > height;
        if (!scrollable) {
            footer.classList.remove('opacity-0', 'pointer-events-none');
            return;
        }

        if (scrollingDown) {
            footer.classList.remove('opacity-0', 'pointer-events-none');
        } else {
            footer.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    main.addEventListener('scroll', updateFooterVisibility, { passive: true });
    window.addEventListener('resize', updateFooterVisibility);
    setTimeout(updateFooterVisibility, 100);
})();

