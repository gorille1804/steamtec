/**
 * Gestionnaire d'état offline pour Steamtech PWA
 * Détecte l'état de connexion et affiche des indicateurs visuels
 */

class OfflineManager {
    constructor() {
        this.isOnline = navigator.onLine;
        this.indicator = null;
        this.init();
    }

    init() {
        this.createOfflineIndicator();
        this.bindEvents();
        this.updateNetworkStatus();
    }

    /**
     * Crée l'indicateur visuel d'état réseau
     */
    createOfflineIndicator() {
        // Éviter les doublons
        if (document.getElementById('network-status-indicator')) {
            return;
        }

        this.indicator = document.createElement('div');
        this.indicator.id = 'network-status-indicator';
        this.indicator.className = 'network-status-indicator';
        
        this.indicator.innerHTML = `
            <div class="network-status-content">
                <i class="network-status-icon mdi mdi-wifi-off"></i>
                <span class="network-status-text">Mode hors ligne</span>
                <button class="network-status-close" aria-label="Fermer">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
        `;

        document.body.appendChild(this.indicator);

        // Gestionnaire de fermeture
        this.indicator.querySelector('.network-status-close').addEventListener('click', () => {
            this.hideIndicator();
        });
    }

    /**
     * Lie les événements de détection réseau
     */
    bindEvents() {
        window.addEventListener('online', () => {
            console.log('🟢 Connexion rétablie');
            this.isOnline = true;
            this.showOnlineStatus();
        });

        window.addEventListener('offline', () => {
            console.log('🔴 Connexion perdue - Mode offline activé');
            this.isOnline = false;
            this.showOfflineStatus();
        });

        // Vérification périodique (optionnelle)
        setInterval(() => {
            this.checkConnection();
        }, 30000); // Toutes les 30 secondes
    }

    /**
     * Vérifie l'état de la connexion
     */
    async checkConnection() {
        try {
            const response = await fetch('/manifest.json', { 
                cache: 'no-cache',
                method: 'HEAD'
            });
            
            const currentOnlineStatus = response.ok;
            
            if (currentOnlineStatus !== this.isOnline) {
                this.isOnline = currentOnlineStatus;
                this.updateNetworkStatus();
            }
        } catch (error) {
            if (this.isOnline) {
                this.isOnline = false;
                this.showOfflineStatus();
            }
        }
    }

    /**
     * Met à jour l'affichage selon l'état réseau
     */
    updateNetworkStatus() {
        if (this.isOnline) {
            this.hideIndicator();
        } else {
            this.showOfflineStatus();
        }
    }

    /**
     * Affiche l'état offline
     */
    showOfflineStatus() {
        if (!this.indicator) return;

        const icon = this.indicator.querySelector('.network-status-icon');
        const text = this.indicator.querySelector('.network-status-text');
        
        icon.className = 'network-status-icon mdi mdi-wifi-off';
        text.textContent = 'Mode hors ligne - Fonctionnalités limitées';
        
        this.indicator.className = 'network-status-indicator offline visible';
        
        // Auto-masquer après 5 secondes
        setTimeout(() => {
            if (!this.isOnline) {
                this.indicator.classList.add('minimized');
            }
        }, 5000);
    }

    /**
     * Affiche brièvement l'état online
     */
    showOnlineStatus() {
        if (!this.indicator) return;

        const icon = this.indicator.querySelector('.network-status-icon');
        const text = this.indicator.querySelector('.network-status-text');
        
        icon.className = 'network-status-icon mdi mdi-wifi';
        text.textContent = 'Connexion rétablie';
        
        this.indicator.className = 'network-status-indicator online visible';
        
        // Masquer après 3 secondes
        setTimeout(() => {
            this.hideIndicator();
        }, 3000);
    }

    /**
     * Masque l'indicateur
     */
    hideIndicator() {
        if (this.indicator) {
            this.indicator.classList.remove('visible', 'minimized');
        }
    }

    /**
     * Affiche un message d'erreur spécifique au mode offline
     */
    showOfflineError(message = 'Cette action nécessite une connexion internet') {
        const errorElement = document.createElement('div');
        errorElement.className = 'offline-error-toast';
        errorElement.innerHTML = `
            <div class="offline-error-content">
                <i class="mdi mdi-wifi-off"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(errorElement);

        // Animation d'entrée
        setTimeout(() => {
            errorElement.classList.add('visible');
        }, 100);

        // Suppression automatique
        setTimeout(() => {
            errorElement.classList.remove('visible');
            setTimeout(() => {
                if (errorElement.parentNode) {
                    errorElement.parentNode.removeChild(errorElement);
                }
            }, 300);
        }, 4000);
    }

    /**
     * Retourne l'état actuel de la connexion
     */
    getNetworkStatus() {
        return {
            isOnline: this.isOnline,
            lastCheck: new Date().toISOString()
        };
    }
}

// Styles CSS injectés dynamiquement
const offlineStyles = `
<style id="offline-manager-styles">
.network-status-indicator {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 12px 16px;
    min-width: 280px;
    transform: translateX(100%);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border-left: 4px solid #f44336;
}

.network-status-indicator.visible {
    transform: translateX(0);
}

.network-status-indicator.minimized {
    transform: translateX(calc(100% - 50px));
    opacity: 0.7;
}

.network-status-indicator.online {
    border-left-color: #4caf50;
}

.network-status-indicator.offline {
    border-left-color: #f44336;
}

.network-status-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.network-status-icon {
    font-size: 20px;
    color: #f44336;
}

.network-status-indicator.online .network-status-icon {
    color: #4caf50;
}

.network-status-text {
    flex-grow: 1;
    font-size: 14px;
    font-weight: 500;
    color: #333;
}

.network-status-close {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.network-status-close:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.offline-error-toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    z-index: 10001;
    background: #f44336;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
    transition: all 0.3s ease;
    opacity: 0;
}

.offline-error-toast.visible {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

.offline-error-content {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.offline-error-content i {
    font-size: 16px;
}

@media (max-width: 768px) {
    .network-status-indicator {
        top: 10px;
        right: 10px;
        left: 10px;
        min-width: auto;
    }
    
    .network-status-indicator.minimized {
        transform: translateY(-calc(100% - 30px));
    }
}
</style>
`;

// Injection des styles
if (!document.getElementById('offline-manager-styles')) {
    document.head.insertAdjacentHTML('beforeend', offlineStyles);
}

// Initialisation automatique
let offlineManager;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        offlineManager = new OfflineManager();
    });
} else {
    offlineManager = new OfflineManager();
}

// Extension pour la synchronisation en arrière-plan
if (offlineManager) {
    // Écouter les messages du service worker
    navigator.serviceWorker.addEventListener('message', event => {
        const { type, timestamp } = event.data;
        
        switch (type) {
            case 'SYNC_MAINTENANCE_REQUEST':
                console.log('🔧 Demande de synchronisation maintenance reçue');
                if (window.LocalStorageManager) {
                    window.LocalStorageManager.processSyncQueue();
                }
                break;
                
            case 'SYNC_CHANTIER_REQUEST':
                console.log('🏗️ Demande de synchronisation chantier reçue');
                // Traiter la synchronisation des chantiers
                break;
                
            case 'SYNC_COMPLETED':
                console.log('✅ Synchronisation terminée');
                if (offlineManager) {
                    offlineManager.showSyncComplete();
                }
                break;
        }
    });

    // Méthode pour déclencher une synchronisation en arrière-plan
    offlineManager.triggerBackgroundSync = function(tag = 'offline-data-sync') {
        if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
            navigator.serviceWorker.ready.then(registration => {
                registration.sync.register(tag).then(() => {
                    console.log(`🔄 Background sync programmé: ${tag}`);
                }).catch(error => {
                    console.error('❌ Erreur background sync:', error);
                });
            });
        } else {
            console.warn('Background Sync non supporté');
            // Fallback: synchronisation immédiate
            if (window.LocalStorageManager) {
                window.LocalStorageManager.processSyncQueue();
            }
        }
    };

    // Méthode pour afficher le statut de synchronisation
    offlineManager.showSyncComplete = function() {
        const syncNotification = document.createElement('div');
        syncNotification.className = 'sync-complete-notification';
        syncNotification.innerHTML = `
            <div class="sync-complete-content">
                <i class="mdi mdi-check-circle"></i>
                <span>Données synchronisées</span>
            </div>
        `;

        document.body.appendChild(syncNotification);

        // Animation d'entrée
        setTimeout(() => {
            syncNotification.classList.add('visible');
        }, 100);

        // Suppression automatique
        setTimeout(() => {
            syncNotification.classList.remove('visible');
            setTimeout(() => {
                if (syncNotification.parentNode) {
                    syncNotification.parentNode.removeChild(syncNotification);
                }
            }, 300);
        }, 3000);
    };

    // Programmer une synchronisation lors de la reconnexion
    window.addEventListener('online', () => {
        setTimeout(() => {
            offlineManager.triggerBackgroundSync();
        }, 1000); // Délai pour laisser la connexion se stabiliser
    });
}

// Styles pour les notifications de synchronisation
const syncStyles = `
<style id="sync-notification-styles">
.sync-complete-notification {
    position: fixed;
    top: 70px;
    right: 20px;
    z-index: 10002;
    background: linear-gradient(135deg, #4caf50, #45a049);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    transform: translateX(100%);
    transition: all 0.3s ease;
    opacity: 0;
}

.sync-complete-notification.visible {
    transform: translateX(0);
    opacity: 1;
}

.sync-complete-content {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
}

.sync-complete-content i {
    font-size: 18px;
}

@media (max-width: 768px) {
    .sync-complete-notification {
        top: 60px;
        right: 10px;
        left: 10px;
        transform: translateY(-100px);
    }
    
    .sync-complete-notification.visible {
        transform: translateY(0);
    }
}
</style>
`;

// Injection des styles de synchronisation
if (!document.getElementById('sync-notification-styles')) {
    document.head.insertAdjacentHTML('beforeend', syncStyles);
}

// Export pour utilisation globale
window.OfflineManager = offlineManager;
window.showOfflineError = (message) => {
    if (offlineManager) {
        offlineManager.showOfflineError(message);
    }
};

window.triggerBackgroundSync = (tag) => {
    if (offlineManager && offlineManager.triggerBackgroundSync) {
        offlineManager.triggerBackgroundSync(tag);
    }
}; 