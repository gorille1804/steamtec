const CACHE_VERSION = 7; // Version mise à jour avec tous les CSS et JS
const CACHE_NAME = `steamtec-v${CACHE_VERSION}`;
const IMAGES_CACHE_NAME = `steamtec-images-v${CACHE_VERSION}`;
const DOCUMENTS_CACHE_NAME = `steamtec-documents-v${CACHE_VERSION}`;
const appUrl = self.location.origin; // Récupère automatiquement l'URL de l'application

// Configuration des routes qui nécessitent une connexion internet
const ONLINE_ONLY_ROUTES = [
    '/dashboard',
    '/dashboard/',
    '/dashboard/users',
    '/dashboard/machines',
    '/dashboard/chantiers',
    '/dashboard/entretiens',
    '/dashboard/profile',
    '/dashboard/arbre-de-depannage',
    '/dashboard/documents',
    '/dashboard/parc-machine',
    '/dashboard/user-machine',
    '/dashboard/historique',
    '/dashboard/historique/entretiens',
    '/dashboard/historique/chantiers',
    '/dashboard/configuration',
    '/dashboard/about',
    '/dashboard/accessoire',
    '/dashboard/desherbage',
    '/dashboard/nettoyage',
    '/dashboard/contact',
    '/dashboard/societe'
];

// Fonction pour vérifier si une route nécessite une connexion
function requiresOnlineConnection(url) {
    const pathname = new URL(url).pathname;
    return ONLINE_ONLY_ROUTES.some(route => pathname.startsWith(route));
}

// Fonction pour obtenir la stratégie de cache pour une route
function getCacheStrategy(url) {
    const pathname = new URL(url).pathname;

    if (requiresOnlineConnection(url)) {
        return 'online-only';
    } else if (pathname === '/' || pathname.startsWith('/login') || pathname.startsWith('/forgot-password') || pathname.startsWith('/reset-password')) {
        return 'offline-capable';
    } else {
        return 'cache-first';
    }
}

// Configuration du cache des médias
const CACHE_STRATEGIES = {
    images: {
        cacheName: IMAGES_CACHE_NAME,
        maxEntries: 100,
        maxAgeSeconds: 30 * 24 * 60 * 60, // 30 jours
        extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp', '.avif']
    },
    documents: {
        cacheName: DOCUMENTS_CACHE_NAME,
        maxEntries: 50,
        maxAgeSeconds: 90 * 24 * 60 * 60, // 90 jours
        extensions: ['.pdf', '.doc', '.docx', '.xlsx', '.zip']
    }
};

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
        // Pages critiques (seulement les pages publiques)
        "/",
        "/manifest.json",

        // Assets JS/CSS principaux
        manifest["build/app.js"] || "/build/app.js",
        manifest["build/app.css"] || "/build/app.css",
        manifest["build/runtime.js"] || "/build/runtime.js",

        // Assets JS/CSS du répertoire assets/
        "/assets/app.js",
        "/assets/bootstrap.js",
        "/assets/js/custome.js",
        "/assets/js/decision-tree.js",
        "/assets/styles/app.css",
        "/assets/styles/bootstrap.min.css",

        // CSS du répertoire public/assets/css/
        "/assets/css/main.css",
        "/assets/css/maintenance-table.css",
        "/assets/css/notification.css",
        "/assets/css/style.css",
        "/assets/css/security.css",
        "/assets/css/owl.carousel.min.css",
        "/assets/css/owl.theme.default.css",

        // JS principaux du répertoire public/assets/js/
        "/assets/js/chart.js",
        "/assets/js/Chart.roundedBarCharts.js",
        "/assets/js/codemirror.js",
        "/assets/js/offline-manager.js",
        "/assets/js/local-storage-manager.js",
        "/assets/js/indexeddb-manager.js",
        "/assets/js/cache-optimizer.js",
        "/assets/js/ponctuel-maintenance-table.js",
        "/assets/js/maintenance-table.js",
        "/assets/js/template.js",
        "/assets/js/decision-tree.js",
        "/assets/js/typeahead.js",
        "/assets/js/userDashboard.js",
        "/assets/js/todolist.js",
        "/assets/js/sweetalert2@11.js",
        "/assets/js/settings.js",
        "/assets/js/select2.js",
        "/assets/js/dashboard.js",
        "/assets/js/file-upload.js",
        "/assets/js/filtreAndTriMachineTable.js",
        "/assets/js/hoverable-collapse.js",
        "/assets/js/jquery-file-upload.js",
        "/assets/js/jquery.cookie.js",
        "/assets/js/off-canvas.js",
        "/assets/js/owl.carousel.min.js",

        // CSS supplémentaires trouvés dans le répertoire JS
        "/assets/js/select.dataTables.min.css",

        // Plugins CSS
        "/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css",
        "/assets/plugins/datatables.net-bs4/dataTables.bootstrap4.css",
        "/assets/plugins/feather/feather.css",
        "/assets/plugins/jquery/jquery-ui.css",
        "/assets/plugins/pwstabs/jquery.pwstabs.css",
        "/assets/plugins/select2/select2.css",
        "/assets/plugins/select2-bootstrap-theme/select2-bootstrap.css",
        "/assets/plugins/css/animate.css",

        // Plugins JS
        "/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js",
        "/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.js",
        "/assets/plugins/chart.js/Chart.min.js",
        "/assets/plugins/codemirror/codemirror.js",
        "/assets/plugins/codemirror/javascript.js",
        "/assets/plugins/codemirror/xml.js",
        "/assets/plugins/datatables.net/jquery.dataTables.js",
        "/assets/plugins/datatables.net-bs4/dataTables.bootstrap4.js",
        "/assets/plugins/jquery/jquery.min.js",
        "/assets/plugins/jquery/jquery-ui.min.js",
        "/assets/plugins/js/bootstrap.bundle.min.js",
        "/assets/plugins/progressbar.js/progressbar.min.js",
        "/assets/plugins/pwstabs/jquery.pwstabs.js",
        "/assets/plugins/select2/select2.js",
        "/assets/plugins/typeahead.js/typeahead.bundle.js",

        // Autres CSS et JS critiques
        "/assets/plugins/codemirror/codemirror.css",
        "/assets/plugins/typicons/src/typicons.css",

        // Données JSON critiques pour fonctionnement offline
        "/assets/data/decision-tree.json",
        "/assets/data/maintenance-table-data.json",
        "/assets/data/ponctuel-maintenance-data.json",

        // Images et icônes
        "assets/images/logo.png",
        "assets/images/logo.svg",
        "assets/icons/icon-192x192.png",
        "assets/icons/icon-512x512.png",
        "assets/screenshots/screenshot-1.png",
        "assets/screenshots/screenshot-2.png",

        // Documents PDF critiques pour consultation offline
        "/uploads/ARBRE_DE_DEPANNAGE.pdf",
        "/uploads/ACCESSOIRES-250601.pdf",
        "/uploads/maintenance_machine/ELEC_ENTRETIEN_REGULIER_PONCTUEL.pdf",

        // Page de fallback offline
        "/offline-fallback.html"
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


// Gestion du cache des médias avec limitation et expiration

// Cache spécialisé pour les images
async function cacheImage(request) {
    const cache = await caches.open(IMAGES_CACHE_NAME);

    try {
        // Essayer le cache d'abord
        const cachedResponse = await cache.match(request);
        if (cachedResponse && await isResponseValid(cachedResponse, CACHE_STRATEGIES.images.maxAgeSeconds)) {
            return cachedResponse;
        }

        // Sinon, récupérer depuis le réseau
        const networkResponse = await fetch(request);
        if (networkResponse.status === 200) {
            // Gérer la taille du cache avant d'ajouter
            await manageImageCacheSize();
            await cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        // Retourner depuis le cache même si expiré en cas d'erreur réseau
        const cachedResponse = await cache.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

// Cache spécialisé pour les documents
async function cacheDocument(request) {
    const cache = await caches.open(DOCUMENTS_CACHE_NAME);

    try {
        const cachedResponse = await cache.match(request);
        if (cachedResponse && await isResponseValid(cachedResponse, CACHE_STRATEGIES.documents.maxAgeSeconds)) {
            return cachedResponse;
        }

        const networkResponse = await fetch(request);
        if (networkResponse.status === 200) {
            await manageDocumentCacheSize();
            await cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        const cachedResponse = await cache.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

// Vérifier si une réponse cache est encore valide
async function isResponseValid(response, maxAgeSeconds) {
    const dateHeader = response.headers.get('date');
    if (!dateHeader) return false;

    const responseDate = new Date(dateHeader);
    const now = new Date();
    const ageInSeconds = (now.getTime() - responseDate.getTime()) / 1000;

    return ageInSeconds < maxAgeSeconds;
}

// Gérer la taille du cache des images
async function manageImageCacheSize() {
    const cache = await caches.open(IMAGES_CACHE_NAME);
    const keys = await cache.keys();

    if (keys.length >= CACHE_STRATEGIES.images.maxEntries) {
        // Supprimer les plus anciens (FIFO)
        const toDelete = keys.slice(0, keys.length - CACHE_STRATEGIES.images.maxEntries + 10); // Garde de la marge
        await Promise.all(toDelete.map(key => cache.delete(key)));
        console.log(`🧹 ${toDelete.length} images supprimées du cache`);
    }
}

// Gérer la taille du cache des documents
async function manageDocumentCacheSize() {
    const cache = await caches.open(DOCUMENTS_CACHE_NAME);
    const keys = await cache.keys();

    if (keys.length >= CACHE_STRATEGIES.documents.maxEntries) {
        const toDelete = keys.slice(0, keys.length - CACHE_STRATEGIES.documents.maxEntries + 5);
        await Promise.all(toDelete.map(key => cache.delete(key)));
        console.log(`🧹 ${toDelete.length} documents supprimés du cache`);
    }
}

// Déterminer le type de fichier par son extension
function getFileType(url) {
    const pathname = new URL(url).pathname.toLowerCase();

    for (const ext of CACHE_STRATEGIES.images.extensions) {
        if (pathname.endsWith(ext)) {
            return 'image';
        }
    }

    for (const ext of CACHE_STRATEGIES.documents.extensions) {
        if (pathname.endsWith(ext)) {
            return 'document';
        }
    }

    return 'other';
}

// Stratégies de cache différenciées

// Cache First Strategy - pour les assets statiques
async function cacheFirst(request) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
        return cachedResponse;
    }

    try {
        const networkResponse = await fetch(request);
        const cache = await caches.open(CACHE_NAME);
        cache.put(request, networkResponse.clone());
        return networkResponse;
    } catch (error) {
        throw error;
    }
}

// Network First Strategy - pour les API et pages dynamiques
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        const cache = await caches.open(CACHE_NAME);

        // Mettre en cache les réponses réussies
        if (networkResponse.status === 200) {
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

// Stale While Revalidate - pour les pages HTML
async function staleWhileRevalidate(request) {
    const cachedResponse = await caches.match(request);

    const fetchPromise = fetch(request).then((networkResponse) => {
        if (networkResponse.status === 200) {
            const cache = caches.open(CACHE_NAME);
            cache.then(c => c.put(request, networkResponse.clone()));
        }
        return networkResponse;
    });

    return cachedResponse || fetchPromise;
}

// Interception des requêtes avec stratégies différenciées
self.addEventListener("fetch", (event) => {
    const url = new URL(event.request.url);

    // Ignorer les requêtes vers d'autres domaines
    if (url.origin !== self.location.origin) {
        return;
    }

    // Ignorer les requêtes non-GET
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        (async () => {
            try {
                const fileType = getFileType(url.href);

                // Stratégie spécialisée pour les images
                if (fileType === 'image') {
                    return await cacheImage(event.request);
                }

                // Stratégie spécialisée pour les documents
                else if (fileType === 'document') {
                    return await cacheDocument(event.request);
                }

                // Stratégie Cache First pour les autres assets statiques
                else if (url.pathname.includes('/assets/') ||
                    url.pathname.includes('/build/') ||
                    url.pathname.match(/\.(css|js|woff|woff2|ttf|eot)$/)) {

                    return await cacheFirst(event.request);
                }

                // Stratégie Network First pour les données JSON et API
                else if (url.pathname.includes('/data/') ||
                    url.pathname.includes('/api/') ||
                    url.pathname.endsWith('.json')) {

                    return await networkFirst(event.request);
                }

                // Routes qui nécessitent une connexion internet - pas de cache
                else if (getCacheStrategy(url.href) === 'online-only') {
                    console.log(`🌐 Route nécessitant une connexion: ${url.pathname}`);
                    return await fetch(event.request);
                }

                // Routes qui peuvent fonctionner en mode offline
                else if (getCacheStrategy(url.href) === 'offline-capable') {
                    console.log(`📱 Route compatible offline: ${url.pathname}`);
                    return await staleWhileRevalidate(event.request);
                }

                // Stratégie Stale While Revalidate pour les pages HTML
                else if (event.request.mode === 'navigate' ||
                    event.request.headers.get('accept')?.includes('text/html')) {

                    return await staleWhileRevalidate(event.request);
                }

                // Stratégie par défaut (Network First)
                else {
                    return await networkFirst(event.request);
                }
            } catch (error) {
                // Gestion des erreurs selon le type de requête

                // Pour les routes qui nécessitent une connexion, afficher une erreur spécifique
                if (getCacheStrategy(event.request.url) === 'online-only') {
                    console.log(`❌ Erreur de connexion pour route nécessitant internet: ${event.request.url}`);
                    return new Response(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Connexion requise</title>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <style>
                                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                                .error-container { max-width: 500px; margin: 0 auto; }
                                .error-icon { font-size: 64px; color: #dc3545; }
                                .error-title { color: #dc3545; margin: 20px 0; }
                                .error-message { color: #6c757d; margin: 20px 0; }
                                .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
                            </style>
                        </head>
                        <body>
                            <div class="error-container">
                                <div class="error-icon">🌐</div>
                                <h1 class="error-title">Connexion Internet Requise</h1>
                                <p class="error-message">Cette page nécessite une connexion internet pour fonctionner correctement.</p>
                                <p class="error-message">Veuillez vérifier votre connexion et réessayer.</p>
                                <a href="/" class="btn">Retour à l'accueil</a>
                            </div>
                        </body>
                        </html>
                    `, {
                        status: 503,
                        statusText: 'Service Unavailable',
                        headers: new Headers({
                            'Content-Type': 'text/html; charset=utf-8'
                        })
                    });
                }

                // Pour les autres pages de navigation, rediriger vers la page offline
                if (event.request.mode === 'navigate' ||
                    event.request.headers.get('accept')?.includes('text/html')) {

                    const offlinePage = await caches.match('/offline-fallback.html');
                    if (offlinePage) {
                        return offlinePage;
                    }
                }

                // Pour les assets, essayer de récupérer depuis le cache
                const cachedResponse = await caches.match(event.request);
                if (cachedResponse) {
                    return cachedResponse;
                }

                // Réponse d'erreur générique
                return new Response('Ressource non disponible hors ligne', {
                    status: 503,
                    statusText: 'Service Unavailable',
                    headers: new Headers({
                        'Content-Type': 'text/plain'
                    })
                });
            }
        })()
    );
});

// Activation et suppression des anciens caches
self.addEventListener("activate", (event) => {
    const currentCaches = [CACHE_NAME, IMAGES_CACHE_NAME, DOCUMENTS_CACHE_NAME];

    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (!currentCaches.includes(cache)) {
                        console.log(`🗑️ Suppression de l'ancien cache : ${cache}`);
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => {
            console.log('🔄 Service Worker activé avec les nouveaux caches');
            return self.clients.claim(); // Met à jour immédiatement les clients
        })
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

// 🔄 Background Sync pour la synchronisation des données offline
self.addEventListener('sync', event => {
    console.log('🔄 Événement de synchronisation en arrière-plan:', event.tag);

    if (event.tag === 'maintenance-sync') {
        event.waitUntil(syncMaintenanceData());
    } else if (event.tag === 'chantier-sync') {
        event.waitUntil(syncChantierData());
    } else if (event.tag === 'offline-data-sync') {
        event.waitUntil(syncAllOfflineData());
    }
});

// Synchronisation des données de maintenance
async function syncMaintenanceData() {
    try {
        console.log('🔧 Synchronisation des données de maintenance...');

        const clients = await self.clients.matchAll();

        // Demander aux clients d'envoyer leurs données de maintenance non synchronisées
        clients.forEach(client => {
            client.postMessage({
                type: 'SYNC_MAINTENANCE_REQUEST',
                timestamp: Date.now()
            });
        });

        return true;
    } catch (error) {
        console.error('❌ Erreur lors de la synchronisation de maintenance:', error);
        throw error;
    }
}

// Synchronisation des données de chantier
async function syncChantierData() {
    try {
        console.log('🏗️ Synchronisation des données de chantier...');

        const clients = await self.clients.matchAll();

        clients.forEach(client => {
            client.postMessage({
                type: 'SYNC_CHANTIER_REQUEST',
                timestamp: Date.now()
            });
        });

        return true;
    } catch (error) {
        console.error('❌ Erreur lors de la synchronisation de chantier:', error);
        throw error;
    }
}

// Synchronisation générale de toutes les données offline
async function syncAllOfflineData() {
    try {
        console.log('📊 Synchronisation de toutes les données offline...');

        await Promise.allSettled([
            syncMaintenanceData(),
            syncChantierData()
        ]);

        // Notifier les clients que la synchronisation est terminée
        const clients = await self.clients.matchAll();
        clients.forEach(client => {
            client.postMessage({
                type: 'SYNC_COMPLETED',
                timestamp: Date.now()
            });
        });

        return true;
    } catch (error) {
        console.error('❌ Erreur lors de la synchronisation générale:', error);
        throw error;
    }
}

// 📨 Écoute des messages des clients pour déclencher des synchronisations
self.addEventListener('message', event => {
    console.log('📨 Message reçu dans le service worker:', event.data);

    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    } else if (event.data && event.data.type === 'REGISTER_BACKGROUND_SYNC') {
        // Enregistrer une synchronisation en arrière-plan
        const tag = event.data.tag || 'offline-data-sync';

        self.registration.sync.register(tag).then(() => {
            console.log(`✅ Background sync enregistré: ${tag}`);
        }).catch(error => {
            console.error(`❌ Erreur d'enregistrement background sync:`, error);
        });
    }
});

