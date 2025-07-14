/**
 * Gestionnaire de stockage local unifié pour Steamtech PWA
 * Gère la persistence des données critiques avec versioning et synchronisation
 */

class LocalStorageManager {
    constructor() {
        this.appPrefix = 'steamtech_';
        this.version = '1.0.0';
        this.maxStorageSize = 5 * 1024 * 1024; // 5MB max
        this.syncQueue = [];
        this.isOnline = navigator.onLine;
        
        this.init();
    }

    init() {
        this.bindNetworkEvents();
        this.cleanupOldData();
        this.processSyncQueue();
    }

    /**
     * Lie les événements réseau pour la synchronisation automatique
     */
    bindNetworkEvents() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.processSyncQueue();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
        });
    }

    /**
     * Génère une clé préfixée
     */
    getKey(key) {
        return `${this.appPrefix}${key}`;
    }

    /**
     * Sauvegarde des données avec métadonnées
     */
    save(key, data, options = {}) {
        try {
            const storageData = {
                data: data,
                timestamp: Date.now(),
                version: this.version,
                expiresAt: options.expiresAt || null,
                needsSync: options.needsSync || false,
                category: options.category || 'general',
                userId: options.userId || null
            };

            const serializedData = JSON.stringify(storageData);
            
            // Vérifier la taille avant de sauvegarder
            if (this.checkStorageSpace(serializedData.length)) {
                localStorage.setItem(this.getKey(key), serializedData);
                
                // Ajouter à la queue de synchronisation si nécessaire
                if (options.needsSync && !this.isOnline) {
                    this.addToSyncQueue(key, data, options);
                }
                
                console.log(`💾 Données sauvegardées: ${key}`);
                return true;
            } else {
                console.warn(`❌ Espace de stockage insuffisant pour: ${key}`);
                return false;
            }
        } catch (error) {
            console.error(`❌ Erreur lors de la sauvegarde de ${key}:`, error);
            return false;
        }
    }

    /**
     * Récupération des données avec validation
     */
    get(key, defaultValue = null) {
        try {
            const rawData = localStorage.getItem(this.getKey(key));
            
            if (!rawData) {
                return defaultValue;
            }

            const storageData = JSON.parse(rawData);
            
            // Vérifier l'expiration
            if (storageData.expiresAt && Date.now() > storageData.expiresAt) {
                this.remove(key);
                return defaultValue;
            }

            // Vérifier la version (migration simple)
            if (storageData.version !== this.version) {
                console.warn(`⚠️ Version différente pour ${key}, migration nécessaire`);
                // Ici on pourrait ajouter une logique de migration
            }

            return storageData.data;
        } catch (error) {
            console.error(`❌ Erreur lors de la récupération de ${key}:`, error);
            return defaultValue;
        }
    }

    /**
     * Suppression d'une donnée
     */
    remove(key) {
        try {
            localStorage.removeItem(this.getKey(key));
            console.log(`🗑️ Données supprimées: ${key}`);
            return true;
        } catch (error) {
            console.error(`❌ Erreur lors de la suppression de ${key}:`, error);
            return false;
        }
    }

    /**
     * Vérification de l'espace de stockage disponible
     */
    checkStorageSpace(requiredSpace) {
        try {
            const totalSize = new Blob(Object.values(localStorage)).size;
            return (totalSize + requiredSpace) < this.maxStorageSize;
        } catch (error) {
            return true; // En cas d'erreur, on autorise la sauvegarde
        }
    }

    /**
     * Nettoyage des données anciennes
     */
    cleanupOldData() {
        const currentTime = Date.now();
        const keys = Object.keys(localStorage);
        
        keys.forEach(key => {
            if (key.startsWith(this.appPrefix)) {
                try {
                    const data = JSON.parse(localStorage.getItem(key));
                    
                    // Supprimer les données expirées
                    if (data.expiresAt && currentTime > data.expiresAt) {
                        localStorage.removeItem(key);
                        console.log(`🧹 Données expirées supprimées: ${key}`);
                    }
                    
                    // Supprimer les données très anciennes (> 30 jours)
                    const thirtyDaysAgo = currentTime - (30 * 24 * 60 * 60 * 1000);
                    if (data.timestamp < thirtyDaysAgo && data.category === 'cache') {
                        localStorage.removeItem(key);
                        console.log(`🧹 Données de cache anciennes supprimées: ${key}`);
                    }
                } catch (error) {
                    // Supprimer les données corrompues
                    localStorage.removeItem(key);
                    console.log(`🧹 Données corrompues supprimées: ${key}`);
                }
            }
        });
    }

    /**
     * Sauvegarde spécifique pour les données d'entretien
     */
    saveMaintenanceLog(machineId, maintenanceData) {
        const key = `maintenance_${machineId}`;
        const existingLogs = this.get(key, []);
        
        const newLog = {
            id: Date.now(),
            ...maintenanceData,
            createdAt: new Date().toISOString(),
            synced: false
        };
        
        existingLogs.push(newLog);
        
        return this.save(key, existingLogs, {
            needsSync: true,
            category: 'maintenance',
            expiresAt: Date.now() + (90 * 24 * 60 * 60 * 1000) // 90 jours
        });
    }

    /**
     * Récupération des logs d'entretien
     */
    getMaintenanceLogs(machineId) {
        const key = `maintenance_${machineId}`;
        return this.get(key, []);
    }

    /**
     * Sauvegarde des données utilisateur
     */
    saveUserData(userId, userData) {
        return this.save(`user_${userId}`, userData, {
            category: 'user',
            userId: userId,
            expiresAt: Date.now() + (7 * 24 * 60 * 60 * 1000) // 7 jours
        });
    }

    /**
     * Sauvegarde des données de chantiers
     */
    saveChantierData(chantierData) {
        const key = `chantiers_${chantierData.userId || 'current'}`;
        return this.save(key, chantierData, {
            needsSync: true,
            category: 'chantier',
            expiresAt: Date.now() + (30 * 24 * 60 * 60 * 1000) // 30 jours
        });
    }

    /**
     * Cache des données JSON critiques
     */
    cacheJsonData(filename, data) {
        return this.save(`json_cache_${filename}`, data, {
            category: 'cache',
            expiresAt: Date.now() + (24 * 60 * 60 * 1000) // 24 heures
        });
    }

    /**
     * Ajout à la queue de synchronisation
     */
    addToSyncQueue(key, data, options) {
        const syncItem = {
            key,
            data,
            options,
            timestamp: Date.now(),
            retryCount: 0
        };
        
        this.syncQueue.push(syncItem);
        this.save('sync_queue', this.syncQueue, { category: 'system' });
    }

    /**
     * Traitement de la queue de synchronisation
     */
    async processSyncQueue() {
        if (!this.isOnline || this.syncQueue.length === 0) {
            return;
        }

        console.log(`🔄 Traitement de ${this.syncQueue.length} éléments de la queue de sync`);
        
        const processedItems = [];
        
        for (const item of this.syncQueue) {
            try {
                const success = await this.syncToServer(item);
                if (success) {
                    processedItems.push(item);
                    console.log(`✅ Synchronisation réussie: ${item.key}`);
                } else {
                    item.retryCount++;
                    if (item.retryCount >= 3) {
                        processedItems.push(item); // Retirer après 3 échecs
                        console.error(`❌ Échec définitif de synchronisation: ${item.key}`);
                    }
                }
            } catch (error) {
                console.error(`❌ Erreur de synchronisation pour ${item.key}:`, error);
                item.retryCount++;
            }
        }

        // Retirer les éléments traités de la queue
        this.syncQueue = this.syncQueue.filter(item => !processedItems.includes(item));
        this.save('sync_queue', this.syncQueue, { category: 'system' });
    }

    /**
     * Synchronisation avec le serveur (à implémenter selon l'API)
     */
    async syncToServer(item) {
        // Exemple de synchronisation - à adapter selon l'API
        try {
            if (item.options.category === 'maintenance') {
                const response = await fetch('/dashboard/entretiens/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(item.data)
                });
                
                return response.ok;
            }
            
            // Ajouter d'autres types de synchronisation ici
            return true;
        } catch (error) {
            return false;
        }
    }

    /**
     * Obtenir des statistiques du stockage
     */
    getStorageStats() {
        const keys = Object.keys(localStorage);
        const steamtechKeys = keys.filter(key => key.startsWith(this.appPrefix));
        
        let totalSize = 0;
        const categories = {};
        
        steamtechKeys.forEach(key => {
            try {
                const data = localStorage.getItem(key);
                const size = new Blob([data]).size;
                totalSize += size;
                
                const parsedData = JSON.parse(data);
                const category = parsedData.category || 'unknown';
                
                if (!categories[category]) {
                    categories[category] = { count: 0, size: 0 };
                }
                categories[category].count++;
                categories[category].size += size;
            } catch (error) {
                // Ignorer les erreurs de parsing
            }
        });
        
        return {
            totalKeys: steamtechKeys.length,
            totalSize: totalSize,
            categories: categories,
            syncQueueSize: this.syncQueue.length,
            storageUsagePercent: (totalSize / this.maxStorageSize) * 100
        };
    }

    /**
     * Vider le cache (conserver les données importantes)
     */
    clearCache() {
        const keys = Object.keys(localStorage);
        let cleared = 0;
        
        keys.forEach(key => {
            if (key.startsWith(this.appPrefix)) {
                try {
                    const data = JSON.parse(localStorage.getItem(key));
                    if (data.category === 'cache') {
                        localStorage.removeItem(key);
                        cleared++;
                    }
                } catch (error) {
                    // Supprimer les données corrompues
                    localStorage.removeItem(key);
                    cleared++;
                }
            }
        });
        
        console.log(`🧹 ${cleared} éléments de cache supprimés`);
        return cleared;
    }
}

// Instance globale
const localStorageManager = new LocalStorageManager();

// Export pour utilisation dans d'autres modules
window.LocalStorageManager = localStorageManager;

// Compatibilité avec l'ancien système
window.saveMaintenanceData = (machineId, data) => {
    return localStorageManager.saveMaintenanceLog(machineId, data);
};

window.getMaintenanceData = (machineId) => {
    return localStorageManager.getMaintenanceLogs(machineId);
};

// Fonctions utilitaires globales
window.saveOfflineData = (key, data, options) => {
    return localStorageManager.save(key, data, options);
};

window.getOfflineData = (key, defaultValue) => {
    return localStorageManager.get(key, defaultValue);
};

console.log('📦 Local Storage Manager initialisé'); 