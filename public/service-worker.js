const CACHE_VERSION = 4; // Incrémentez la version pour forcer la mise à jour
const CACHE_NAME = `steamtech-v${CACHE_VERSION}`;
const appUrl = self.location.origin; // Récupère automatiquement l'URL de l'application

// Récupérer le manifeste
async function getManifest() {
    try {
        const response = await fetch('/build/manifest.json');
        if (!response.ok) throw new Error("Manifest introuvable");
        return await response.json();
    } catch (error) {
        console.error("Erreur lors du chargement du manifest :", error);
        return {}; // Retourner un objet vide pour éviter les erreurs
    }
}

// Fonction pour ajouter dynamiquement les fichiers au cache
async function cacheFiles() {
    const manifest = await getManifest();
    return [
        "/",
        "/manifest.json",
        manifest["build/app.js"] || "/build/app.js",
        manifest["build/app.css"] || "/build/app.css",
        manifest["build/runtime.js"] || "/build/runtime.js",
        "assets/images/logo.png",
        "assets/images/logo.svg",
        "assets/icons/icon-192x192.png",
        "assets/icons/icon-512x512.png",
        "assets/screenshots/screenshot-1.png",
        "assets/screenshots/screenshot-2.png"
    ];
}
// Installation du Service Worker et mise en cache
self.addEventListener("install", (event) => {
    console.log("📦 Installation du Service Worker...");
    event.waitUntil(
        cacheFiles().then((urlsToCache) => {
            return caches.open(CACHE_NAME).then((cache) => {
                return cache.addAll(urlsToCache).then(() => {
                    console.log('✅ Tous les fichiers ont été ajoutés au cache');
                    self.skipWaiting(); // Force l'installation immédiate
                });
            });
        })
    );
});


// Interception des requêtes et récupération du cache
self.addEventListener("fetch", (event) => {
    event.respondWith(
        fetch(event.request) // Essaye de récupérer depuis le réseau en premier
        .then((networkResponse) => {
            // Only cache requests from our own origin and with supported schemes
            const url = new URL(event.request.url);
            if (url.origin === self.location.origin && 
                (url.protocol === 'http:' || url.protocol === 'https:')) {
                return caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, networkResponse.clone());
                    return networkResponse;
                });
            }
            return networkResponse;
        })
        .catch(() => {
            return caches.match(event.request); // Si le réseau échoue, utiliser le cache
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
                        console.log(`🗑️ Suppression de l'ancien cache : ${cache}`);
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
        badge: 'assets/icons/icon-192x192.png',
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

