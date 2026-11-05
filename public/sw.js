const CACHE_NAME = "banksampah-v2";

// Saat service worker terpasang
self.addEventListener("install", (event) => {
    self.skipWaiting();
});

// Bersihkan cache usang saat update
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => {
                return Promise.all(
                    keys.map((key) => {
                        if (key !== CACHE_NAME) {
                            return caches.delete(key);
                        }
                    })
                );
            })
            .then(() => self.clients.claim())
    );
});

// Selalu tembuskan request langsung ke server Laravel (Network First)
self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET" || !event.request.url.startsWith("http")) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((cached) => {
                    return cached || new Response("Aplikasi sedang offline.", {
                        status: 503,
                        statusText: "Offline",
                        headers: { "Content-Type": "text/plain; charset=utf-8" }
                    });
                });
            })
    );
});
