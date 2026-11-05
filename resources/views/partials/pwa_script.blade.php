<!-- PWA Service Worker Registration -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register("{{ asset('sw.js') }}", { scope: '/' })
                .then(function(reg) {
                    console.log('PWA Service Worker registered:', reg.scope);
                })
                .catch(function(err) {
                    console.warn('PWA Service Worker registration failed:', err);
                });
        });
    }
</script>
