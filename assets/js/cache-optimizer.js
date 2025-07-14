/**
 * Optimiseur de cache pour Steamtech PWA
 * Surveille et optimise automatiquement l'utilisation du stockage
 */

class CacheOptimizer {
    constructor() {
        this.maxTotalStorage = 50 * 1024 * 1024; // 50MB max total
        this.warningThreshold = 0.8; // 80% d'utilisation
        this.criticalThreshold = 0.95; // 95% d'utilisation
        this.isOptimizing = false;
        
        this.init();
    }

    async init() {
        // Vérification périodique toutes les 30 minutes
        setInterval(() => {
            this.optimizeStorage();
        }, 30 * 60 * 1000);

        // Vérification au démarrage
        setTimeout(() => {
            this.checkStorageStatus();
        }, 5000);

        // Écouter les événements de stockage plein
        this.bindStorageEvents();
    }

    /**
     * Surveillance de l'état du stockage
     */
    async checkStorageStatus() {
        try {
            const stats = await this.getStorageStatistics();
            const usagePercent = stats.usagePercentage;

            if (usagePercent >= this.criticalThreshold) {
                console.warn('🚨 Stockage critique:', usagePercent + '%');
                this.showStorageWarning('critical');
                await this.aggressiveCleanup();
            } else if (usagePercent >= this.warningThreshold) {
                console.warn('⚠️ Stockage élevé:', usagePercent + '%');
                this.showStorageWarning('warning');
                await this.gentleCleanup();
            }

            return stats;
        } catch (error) {
            console.error('❌ Erreur vérification stockage:', error);
        }
    }

    /**
     * Récupération des statistiques de stockage
     */
    async getStorageStatistics() {
        const stats = {
            localStorage: 0,
            cacheAPI: 0,
            indexedDB: 0,
            total: 0,
            quota: 0,
            usagePercentage: 0
        };

        try {
            // Taille du localStorage
            stats.localStorage = this.getLocalStorageSize();

            // Taille des caches (Service Worker)
            stats.cacheAPI = await this.getCacheAPISize();

            // Taille IndexedDB
            if (window.IndexedDBManager) {
                const idbStats = await window.IndexedDBManager.getStorageStats();
                stats.indexedDB = idbStats.usage || 0;
            }

            // Estimation totale via l'API Storage
            if ('estimate' in navigator.storage) {
                const estimate = await navigator.storage.estimate();
                stats.quota = estimate.quota;
                stats.total = estimate.usage;
                stats.usagePercentage = ((estimate.usage / estimate.quota) * 100);
            } else {
                // Fallback : calcul manuel
                stats.total = stats.localStorage + stats.cacheAPI + stats.indexedDB;
                stats.quota = this.maxTotalStorage;
                stats.usagePercentage = ((stats.total / this.maxTotalStorage) * 100);
            }

            return stats;
        } catch (error) {
            console.error('❌ Erreur calcul statistiques:', error);
            return stats;
        }
    }

    /**
     * Calcul de la taille du localStorage
     */
    getLocalStorageSize() {
        let total = 0;
        for (let key in localStorage) {
            if (localStorage.hasOwnProperty(key)) {
                total += localStorage[key].length + key.length;
            }
        }
        return total * 2; // Unicode est sur 2 bytes
    }

    /**
     * Calcul de la taille des caches
     */
    async getCacheAPISize() {
        try {
            const cacheNames = await caches.keys();
            let totalSize = 0;

            for (const cacheName of cacheNames) {
                const cache = await caches.open(cacheName);
                const requests = await cache.keys();
                
                for (const request of requests) {
                    const response = await cache.match(request);
                    if (response) {
                        const blob = await response.blob();
                        totalSize += blob.size;
                    }
                }
            }

            return totalSize;
        } catch (error) {
            console.error('❌ Erreur calcul taille cache:', error);
            return 0;
        }
    }

    /**
     * Nettoyage doux (suppression des données expirées et anciennes)
     */
    async gentleCleanup() {
        if (this.isOptimizing) return;
        this.isOptimizing = true;

        try {
            console.log('🧹 Début du nettoyage doux...');

            // Nettoyage localStorage
            if (window.LocalStorageManager) {
                window.LocalStorageManager.cleanupOldData();
            }

            // Nettoyage IndexedDB
            if (window.IndexedDBManager) {
                await window.IndexedDBManager.cleanupOldData(60); // 60 jours
            }

            // Nettoyage cache des images (gardent 30 jours)
            await this.cleanupImageCache(30);

            console.log('✅ Nettoyage doux terminé');
        } catch (error) {
            console.error('❌ Erreur nettoyage doux:', error);
        } finally {
            this.isOptimizing = false;
        }
    }

    /**
     * Nettoyage agressif (libération maximale d'espace)
     */
    async aggressiveCleanup() {
        if (this.isOptimizing) return;
        this.isOptimizing = true;

        try {
            console.log('🚨 Début du nettoyage agressif...');

            // Nettoyage localStorage (garde seulement les données critiques)
            if (window.LocalStorageManager) {
                window.LocalStorageManager.clearCache();
            }

            // Nettoyage IndexedDB agressif
            if (window.IndexedDBManager) {
                await window.IndexedDBManager.cleanupOldData(7); // 7 jours seulement
            }

            // Nettoyage cache des images (garde 7 jours)
            await this.cleanupImageCache(7);

            // Nettoyage cache des documents (garde 14 jours)
            await this.cleanupDocumentCache(14);

            console.log('✅ Nettoyage agressif terminé');
            
            // Revérifier après nettoyage
            setTimeout(() => {
                this.checkStorageStatus();
            }, 2000);

        } catch (error) {
            console.error('❌ Erreur nettoyage agressif:', error);
        } finally {
            this.isOptimizing = false;
        }
    }

    /**
     * Nettoyage du cache des images
     */
    async cleanupImageCache(daysToKeep) {
        try {
            const cutoffTime = Date.now() - (daysToKeep * 24 * 60 * 60 * 1000);
            
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.ready.then(registration => {
                    registration.postMessage({
                        type: 'CLEANUP_IMAGE_CACHE',
                        cutoffTime: cutoffTime
                    });
                });
            }
        } catch (error) {
            console.error('❌ Erreur nettoyage cache images:', error);
        }
    }

    /**
     * Nettoyage du cache des documents
     */
    async cleanupDocumentCache(daysToKeep) {
        try {
            const cutoffTime = Date.now() - (daysToKeep * 24 * 60 * 60 * 1000);
            
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.ready.then(registration => {
                    registration.postMessage({
                        type: 'CLEANUP_DOCUMENT_CACHE',
                        cutoffTime: cutoffTime
                    });
                });
            }
        } catch (error) {
            console.error('❌ Erreur nettoyage cache documents:', error);
        }
    }

    /**
     * Affichage des alertes de stockage
     */
    showStorageWarning(level) {
        // Éviter les doublons
        if (document.querySelector('.storage-warning')) {
            return;
        }

        const warning = document.createElement('div');
        warning.className = `storage-warning ${level}`;
        
        const messages = {
            warning: {
                icon: '⚠️',
                title: 'Espace de stockage limité',
                text: 'L\'espace de stockage de l\'application approche de sa limite. Un nettoyage automatique va être effectué.',
                action: 'Optimiser maintenant'
            },
            critical: {
                icon: '🚨',
                title: 'Espace de stockage critique',
                text: 'L\'espace de stockage est presque plein. Certaines fonctionnalités peuvent être limitées.',
                action: 'Nettoyer maintenant'
            }
        };

        const msg = messages[level];
        
        warning.innerHTML = `
            <div class="storage-warning-content">
                <div class="storage-warning-header">
                    <span class="storage-warning-icon">${msg.icon}</span>
                    <h4 class="storage-warning-title">${msg.title}</h4>
                    <button class="storage-warning-close" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                <p class="storage-warning-text">${msg.text}</p>
                <div class="storage-warning-actions">
                    <button class="storage-warning-btn primary" onclick="window.CacheOptimizer.manualOptimization()">
                        ${msg.action}
                    </button>
                    <button class="storage-warning-btn secondary" onclick="window.CacheOptimizer.showStorageDetails()">
                        Voir les détails
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(warning);

        // Animation d'entrée
        setTimeout(() => {
            warning.classList.add('visible');
        }, 100);

        // Auto-masquage après 10 secondes pour le niveau warning
        if (level === 'warning') {
            setTimeout(() => {
                if (warning.parentNode) {
                    warning.classList.remove('visible');
                    setTimeout(() => warning.remove(), 300);
                }
            }, 10000);
        }
    }

    /**
     * Optimisation manuelle déclenchée par l'utilisateur
     */
    async manualOptimization() {
        const stats = await this.checkStorageStatus();
        
        if (stats.usagePercentage >= this.criticalThreshold) {
            await this.aggressiveCleanup();
        } else {
            await this.gentleCleanup();
        }

        // Masquer les avertissements
        const warnings = document.querySelectorAll('.storage-warning');
        warnings.forEach(warning => warning.remove());

        // Afficher un message de succès
        this.showOptimizationComplete();
    }

    /**
     * Affichage des détails de stockage
     */
    async showStorageDetails() {
        const stats = await this.getStorageStatistics();
        
        const modal = document.createElement('div');
        modal.className = 'storage-details-modal';
        modal.innerHTML = `
            <div class="storage-details-content">
                <div class="storage-details-header">
                    <h3>Détails du stockage</h3>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
                <div class="storage-details-body">
                    <div class="storage-stat">
                        <span class="storage-stat-label">Utilisation totale:</span>
                        <span class="storage-stat-value">${this.formatBytes(stats.total)}</span>
                    </div>
                    <div class="storage-stat">
                        <span class="storage-stat-label">Quota disponible:</span>
                        <span class="storage-stat-value">${this.formatBytes(stats.quota)}</span>
                    </div>
                    <div class="storage-stat">
                        <span class="storage-stat-label">Pourcentage utilisé:</span>
                        <span class="storage-stat-value">${stats.usagePercentage.toFixed(1)}%</span>
                    </div>
                    <hr>
                    <div class="storage-stat">
                        <span class="storage-stat-label">Cache des pages:</span>
                        <span class="storage-stat-value">${this.formatBytes(stats.cacheAPI)}</span>
                    </div>
                    <div class="storage-stat">
                        <span class="storage-stat-label">Données locales:</span>
                        <span class="storage-stat-value">${this.formatBytes(stats.localStorage)}</span>
                    </div>
                    <div class="storage-stat">
                        <span class="storage-stat-label">Base de données:</span>
                        <span class="storage-stat-value">${this.formatBytes(stats.indexedDB)}</span>
                    </div>
                </div>
                <div class="storage-details-actions">
                    <button onclick="window.CacheOptimizer.manualOptimization()" class="btn-primary">
                        Optimiser maintenant
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('visible'), 100);
    }

    /**
     * Message de confirmation d'optimisation
     */
    showOptimizationComplete() {
        const notification = document.createElement('div');
        notification.className = 'optimization-complete';
        notification.innerHTML = `
            <div class="optimization-complete-content">
                <i class="mdi mdi-check-circle"></i>
                <span>Optimisation terminée</span>
            </div>
        `;

        document.body.appendChild(notification);
        setTimeout(() => notification.classList.add('visible'), 100);
        setTimeout(() => {
            notification.classList.remove('visible');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    /**
     * Liaison des événements de stockage
     */
    bindStorageEvents() {
        // Écouter les erreurs de quota
        window.addEventListener('error', (event) => {
            if (event.error && event.error.name === 'QuotaExceededError') {
                console.error('🚨 Quota de stockage dépassé');
                this.aggressiveCleanup();
            }
        });

        // Écouter les changements de visibilité pour vérifier le stockage
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.checkStorageStatus();
            }
        });
    }

    /**
     * Optimisation du stockage
     */
    async optimizeStorage() {
        await this.checkStorageStatus();
    }

    /**
     * Formatage des tailles en bytes
     */
    formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}

// Styles CSS
const optimizerStyles = `
<style id="cache-optimizer-styles">
.storage-warning {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10003;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    max-width: 400px;
    transform: translateX(100%);
    transition: all 0.3s ease;
    opacity: 0;
    border-left: 4px solid #ff9800;
}

.storage-warning.critical {
    border-left-color: #f44336;
}

.storage-warning.visible {
    transform: translateX(0);
    opacity: 1;
}

.storage-warning-content {
    padding: 20px;
}

.storage-warning-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.storage-warning-icon {
    font-size: 24px;
}

.storage-warning-title {
    flex-grow: 1;
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.storage-warning-close {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
}

.storage-warning-text {
    margin: 0 0 16px 0;
    color: #666;
    line-height: 1.5;
}

.storage-warning-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.storage-warning-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.storage-warning-btn.primary {
    background: #2196f3;
    color: white;
}

.storage-warning-btn.secondary {
    background: #f5f5f5;
    color: #666;
}

.storage-details-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10004;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.storage-details-modal.visible {
    opacity: 1;
}

.storage-details-content {
    background: white;
    border-radius: 12px;
    padding: 24px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.storage-stat {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.optimization-complete {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 10005;
    background: #4caf50;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    transform: translateY(100px);
    transition: transform 0.3s ease;
    opacity: 0;
}

.optimization-complete.visible {
    transform: translateY(0);
    opacity: 1;
}

@media (max-width: 768px) {
    .storage-warning {
        top: 10px;
        right: 10px;
        left: 10px;
    }
}
</style>
`;

// Injection des styles
if (!document.getElementById('cache-optimizer-styles')) {
    document.head.insertAdjacentHTML('beforeend', optimizerStyles);
}

// Instance globale
const cacheOptimizer = new CacheOptimizer();

// Export pour utilisation globale
window.CacheOptimizer = cacheOptimizer;

console.log('🎯 Cache Optimizer initialisé'); 