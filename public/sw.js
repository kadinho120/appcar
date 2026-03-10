const CACHE_NAME = 'mecanico-v1';
const ASSETS = [
    '/js/app.js',
    '/icons/icon-192.png',
    '/icons/icon-512.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('SW: Pre-caching static assets');
                return cache.addAll(ASSETS).catch(err => {
                    console.error('SW: Cache addAll failed. Check if all assets exist and are accessible:', err);
                });
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
    );
});
