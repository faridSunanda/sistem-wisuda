<footer id="main-footer" class="bg-white/70 backdrop-blur-2xl border-t border-gray-200 py-4 px-4 md:px-6 hidden transition-all duration-300">
    <div class="text-center text-sm text-gray-600">
        <p>© UPT PSID | All Rights Reserved | Powered by Universitas Wahid Hasyim</p>
    </div>
</footer>

@push('scripts')
<script>
    (function() {
        const footer = document.getElementById('main-footer');
        const mainContent = document.querySelector('main');
        
        if (footer && mainContent) {
            function checkScroll() {
                const scrollTop = mainContent.scrollTop;
                const scrollHeight = mainContent.scrollHeight;
                const clientHeight = mainContent.clientHeight;
                
                const isScrollable = scrollHeight > clientHeight;
                const isAtBottom = scrollTop + clientHeight >= scrollHeight - 20;
                
                if (!isScrollable || isAtBottom) {
                    footer.classList.remove('hidden');
                } else {
                    footer.classList.add('hidden');
                }
            }
            
            mainContent.addEventListener('scroll', checkScroll);
            
            const observer = new MutationObserver(checkScroll);
            if (mainContent) {
                observer.observe(mainContent, {
                    childList: true,
                    subtree: true,
                    attributes: true
                });
            }
            
            setTimeout(checkScroll, 100);
            window.addEventListener('resize', checkScroll);
        }
    })();
</script>
@endpush

