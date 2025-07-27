/**
 * Configuration PWA pour Steamtech
 * Gestion centralisée des routes et comportements PWA
 */

const PWA_CONFIG = {
    // Routes qui nécessitent une connexion internet
    ONLINE_ONLY_ROUTES: [
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
    ],

    // Routes qui peuvent fonctionner en mode offline
    OFFLINE_CAPABLE_ROUTES: [
        '/',
        '/login',
        '/forgot-password',
        '/reset-password'
    ],

    // Configuration du cache
    CACHE_CONFIG: {
        version: 7,
        maxAge: {
            images: 30 * 24 * 60 * 60, // 30 jours
            documents: 90 * 24 * 60 * 60, // 90 jours
            assets: 7 * 24 * 60 * 60 // 7 jours
        },
        maxEntries: {
            images: 100,
            documents: 50,
            assets: 200
        }
    },

    // Messages d'erreur
    MESSAGES: {
        ONLINE_REQUIRED: 'Cette page nécessite une connexion internet pour fonctionner correctement.',
        OFFLINE_MODE: 'Mode hors ligne - Fonctionnalités limitées',
        CONNECTION_RESTORED: 'Connexion rétablie',
        SYNC_COMPLETE: 'Synchronisation terminée'
    },

    /**
     * Vérifie si une route nécessite une connexion internet
     * @param {string} pathname - Le chemin de la route
     * @returns {boolean} - True si la route nécessite une connexion
     */
    requiresOnlineConnection(pathname) {
        return this.ONLINE_ONLY_ROUTES.some(route => pathname.startsWith(route));
    },

    /**
     * Vérifie si une route peut fonctionner en mode offline
     * @param {string} pathname - Le chemin de la route
     * @returns {boolean} - True si la route peut fonctionner offline
     */
    canWorkOffline(pathname) {
        return this.OFFLINE_CAPABLE_ROUTES.some(route => pathname.startsWith(route));
    },

    /**
     * Retourne la stratégie de cache appropriée pour une route
     * @param {string} pathname - Le chemin de la route
     * @returns {string} - La stratégie de cache ('online-only', 'offline-capable', 'cache-first')
     */
    getCacheStrategy(pathname) {
        if (this.requiresOnlineConnection(pathname)) {
            return 'online-only';
        } else if (this.canWorkOffline(pathname)) {
            return 'offline-capable';
        } else {
            return 'cache-first';
        }
    },

    /**
     * Vérifie si le service worker doit être enregistré pour la route actuelle
     * @param {string} pathname - Le chemin de la route
     * @param {boolean} isOnline - L'état de la connexion
     * @returns {boolean} - True si le service worker doit être enregistré
     */
    shouldRegisterServiceWorker(pathname, isOnline) {
        // Ne pas enregistrer le service worker pour les routes nécessitant une connexion en mode offline
        if (this.requiresOnlineConnection(pathname) && !isOnline) {
            return false;
        }
        return true;
    }
};

// Export pour utilisation dans d'autres modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PWA_CONFIG;
} else {
    window.PWA_CONFIG = PWA_CONFIG;
} 