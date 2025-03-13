const CACHE_NAME = "steamtech";
const appUrl = self.location.origin; // Récupère automatiquement l'URL de l'application

// Récupérer le manifeste
async function getManifest() {
    const response = await fetch('/build/manifest.json');
    return await response.json();
}

// Fonction pour ajouter dynamiquement les fichiers au cache
async function cacheFiles() {
    const manifest = await getManifest();
    const urlsToCache = [
        "/",
        "/manifest.json",
        `${manifest["build/app.js"]}`,
        `${manifest["build/app.css"]}`,
        `${manifest["build/runtime.js"]}`,
        `${manifest["build/service-worker.js"]}`,
        "assets/images/logo.png",
        "assets/images/logo.svg",
        "assets/icons/icon-192x192.png",
        "assets/icons/icon-512x512.png",
        "assets/screenshots/screenshot-1.png",
        "assets/screenshots/screenshot-2.png"
    ];

    return urlsToCache;
}
// Installation du Service Worker et mise en cache
self.addEventListener("install", (event) => {
    console.log("Service Worker Service Worker est en train d'être installé...");
    self.skipWaiting();
    event.waitUntil(
        cacheFiles().then((urlsToCache) => {
            return caches.open(CACHE_NAME).then((cache) => {
                return cache.addAll(urlsToCache).then(() => {
                    console.log('Tous les fichiers ont été ajoutés au cache');
                });
            });
        })
    );
});

// Interception des requêtes et récupération du cache
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request).then((networkResponse) => {
                return caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, networkResponse.clone()); // Met en cache la nouvelle réponse
                    return networkResponse;
                });
            });
        }).catch(() => {
            return caches.match("/offline.html"); // Fichier de secours si offline
        })
    );
});

// Activation et suppression des anciens caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())// Met à jour immédiatement les clients
    );
});

// 📌 Écoute de la réception d'une notification push (facultatif)
self.addEventListener("push", event => {
    console.log("📩 Notification push reçue !");
    const options = {
        body: "Ceci est un message push venant de service worker du steamtech !",
        icon: 'assets/icons/icon-192x192.png',
        vibrate: [200, 100, 200]
    };
    event.waitUntil(
        self.registration.showNotification("🔔 Steamtech Notification Push", options)
    );
});

self.addEventListener("notificationclick", event => {
    event.notification.close();

    event.waitUntil(
        clients.matchAll({ type: "window" }).then(clientList => {
            for (let client of clientList) {
                if (client.url === appUrl && "focus" in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(appUrl);
        })
    );
});

