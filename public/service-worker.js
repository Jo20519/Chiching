const CACHE_NAME = "chiching-cache-v2";

const urlsToCache = [
    "/",
    "/css/app.css",
    "/js/app.js",
    "/images/icons/web-app-manifest-192x192.png",
    "/images/icons/web-app-manifest-512x512.png",
    "/images/icons/apple-touch-icon.png",
    "/images/icons/favicon.png",
    "/images/icons/favicon.ico"
];

self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
    );
});

self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.map(key => {
                if (key !== CACHE_NAME) return caches.delete(key);
            }))
        )
    );
});

self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request).then(response => response || fetch(event.request))
    );
});
