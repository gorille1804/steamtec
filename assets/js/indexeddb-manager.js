/**
 * Gestionnaire IndexedDB pour Steamtech PWA
 * Gère les gros volumes de données et les fichiers
 */

class IndexedDBManager {
    constructor() {
        this.dbName = 'SteamtechDB';
        this.version = 1;
        this.db = null;
        this.isSupported = 'indexedDB' in window;
        
        if (this.isSupported) {
            this.init();
        } else {
            console.warn('IndexedDB non supporté par ce navigateur');
        }
    }

    async init() {
        try {
            this.db = await this.openDatabase();
            console.log('📚 IndexedDB initialisé avec succès');
        } catch (error) {
            console.error('❌ Erreur d\'initialisation IndexedDB:', error);
        }
    }

    openDatabase() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.version);

            request.onerror = () => {
                reject(request.error);
            };

            request.onsuccess = () => {
                resolve(request.result);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Store pour les documents PDF et images
                if (!db.objectStoreNames.contains('documents')) {
                    const documentsStore = db.createObjectStore('documents', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    });
                    documentsStore.createIndex('filename', 'filename', { unique: false });
                    documentsStore.createIndex('category', 'category', { unique: false });
                    documentsStore.createIndex('timestamp', 'timestamp', { unique: false });
                }

                // Store pour l'historique complet des maintenances
                if (!db.objectStoreNames.contains('maintenance_history')) {
                    const maintenanceStore = db.createObjectStore('maintenance_history', {
                        keyPath: 'id',
                        autoIncrement: true
                    });
                    maintenanceStore.createIndex('machineId', 'machineId', { unique: false });
                    maintenanceStore.createIndex('userId', 'userId', { unique: false });
                    maintenanceStore.createIndex('date', 'date', { unique: false });
                }

                // Store pour les images de machines
                if (!db.objectStoreNames.contains('machine_images')) {
                    const imagesStore = db.createObjectStore('machine_images', {
                        keyPath: 'id',
                        autoIncrement: true
                    });
                    imagesStore.createIndex('machineId', 'machineId', { unique: false });
                    imagesStore.createIndex('type', 'type', { unique: false });
                }

                // Store pour les données de chantiers étendues
                if (!db.objectStoreNames.contains('chantier_details')) {
                    const chantierStore = db.createObjectStore('chantier_details', {
                        keyPath: 'id',
                        autoIncrement: true
                    });
                    chantierStore.createIndex('chantierId', 'chantierId', { unique: false });
                    chantierStore.createIndex('userId', 'userId', { unique: false });
                }
            };
        });
    }

    /**
     * Stockage d'un document (PDF, image)
     */
    async storeDocument(file, metadata = {}) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            // Convertir le fichier en ArrayBuffer
            const arrayBuffer = await this.fileToArrayBuffer(file);
            
            const document = {
                filename: file.name,
                data: arrayBuffer,
                size: file.size,
                type: file.type,
                category: metadata.category || 'general',
                description: metadata.description || '',
                timestamp: Date.now(),
                ...metadata
            };

            const transaction = this.db.transaction(['documents'], 'readwrite');
            const store = transaction.objectStore('documents');
            const result = await this.promisifyRequest(store.add(document));
            
            console.log(`📄 Document stocké: ${file.name} (ID: ${result})`);
            return result;
        } catch (error) {
            console.error('❌ Erreur stockage document:', error);
            throw error;
        }
    }

    /**
     * Récupération d'un document
     */
    async getDocument(id) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const transaction = this.db.transaction(['documents'], 'readonly');
            const store = transaction.objectStore('documents');
            const result = await this.promisifyRequest(store.get(id));
            
            if (result && result.data) {
                // Convertir l'ArrayBuffer en Blob pour utilisation
                result.blob = new Blob([result.data], { type: result.type });
                result.url = URL.createObjectURL(result.blob);
            }
            
            return result;
        } catch (error) {
            console.error('❌ Erreur récupération document:', error);
            throw error;
        }
    }

    /**
     * Liste des documents par catégorie
     */
    async getDocumentsByCategory(category) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const transaction = this.db.transaction(['documents'], 'readonly');
            const store = transaction.objectStore('documents');
            const index = store.index('category');
            const results = await this.promisifyRequest(index.getAll(category));
            
            return results;
        } catch (error) {
            console.error('❌ Erreur récupération documents par catégorie:', error);
            throw error;
        }
    }

    /**
     * Stockage d'un historique de maintenance complet
     */
    async storeMaintenanceHistory(maintenanceData) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const historyEntry = {
                ...maintenanceData,
                timestamp: Date.now(),
                id: maintenanceData.id || undefined // Laisse IndexedDB générer l'ID si non fourni
            };

            const transaction = this.db.transaction(['maintenance_history'], 'readwrite');
            const store = transaction.objectStore('maintenance_history');
            const result = await this.promisifyRequest(store.add(historyEntry));
            
            console.log(`🔧 Historique maintenance stocké (ID: ${result})`);
            return result;
        } catch (error) {
            console.error('❌ Erreur stockage historique maintenance:', error);
            throw error;
        }
    }

    /**
     * Récupération de l'historique par machine
     */
    async getMaintenanceHistoryByMachine(machineId, limit = 50) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const transaction = this.db.transaction(['maintenance_history'], 'readonly');
            const store = transaction.objectStore('maintenance_history');
            const index = store.index('machineId');
            
            const results = [];
            const request = index.openCursor(IDBKeyRange.only(machineId), 'prev'); // Plus récent en premier
            
            return new Promise((resolve, reject) => {
                request.onsuccess = (event) => {
                    const cursor = event.target.result;
                    if (cursor && results.length < limit) {
                        results.push(cursor.value);
                        cursor.continue();
                    } else {
                        resolve(results);
                    }
                };
                
                request.onerror = () => {
                    reject(request.error);
                };
            });
        } catch (error) {
            console.error('❌ Erreur récupération historique par machine:', error);
            throw error;
        }
    }

    /**
     * Stockage d'images de machines
     */
    async storeMachineImage(machineId, imageFile, type = 'photo') {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const arrayBuffer = await this.fileToArrayBuffer(imageFile);
            
            const imageData = {
                machineId: machineId,
                type: type, // 'photo', 'schema', 'defaut', etc.
                filename: imageFile.name,
                data: arrayBuffer,
                size: imageFile.size,
                mimeType: imageFile.type,
                timestamp: Date.now()
            };

            const transaction = this.db.transaction(['machine_images'], 'readwrite');
            const store = transaction.objectStore('machine_images');
            const result = await this.promisifyRequest(store.add(imageData));
            
            console.log(`📸 Image machine stockée (ID: ${result})`);
            return result;
        } catch (error) {
            console.error('❌ Erreur stockage image machine:', error);
            throw error;
        }
    }

    /**
     * Récupération des images d'une machine
     */
    async getMachineImages(machineId) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const transaction = this.db.transaction(['machine_images'], 'readonly');
            const store = transaction.objectStore('machine_images');
            const index = store.index('machineId');
            const results = await this.promisifyRequest(index.getAll(machineId));
            
            // Convertir les ArrayBuffer en URLs d'objets
            results.forEach(image => {
                if (image.data) {
                    image.blob = new Blob([image.data], { type: image.mimeType });
                    image.url = URL.createObjectURL(image.blob);
                }
            });
            
            return results;
        } catch (error) {
            console.error('❌ Erreur récupération images machine:', error);
            throw error;
        }
    }

    /**
     * Nettoyage des données anciennes
     */
    async cleanupOldData(daysToKeep = 90) {
        if (!this.db) {
            throw new Error('Base de données non initialisée');
        }

        try {
            const cutoffDate = Date.now() - (daysToKeep * 24 * 60 * 60 * 1000);
            const stores = ['documents', 'maintenance_history', 'machine_images', 'chantier_details'];
            let totalDeleted = 0;

            for (const storeName of stores) {
                const transaction = this.db.transaction([storeName], 'readwrite');
                const store = transaction.objectStore('store');
                const index = store.index('timestamp');
                
                const request = index.openCursor(IDBKeyRange.upperBound(cutoffDate));
                
                const deleted = await new Promise((resolve, reject) => {
                    let count = 0;
                    
                    request.onsuccess = (event) => {
                        const cursor = event.target.result;
                        if (cursor) {
                            cursor.delete();
                            count++;
                            cursor.continue();
                        } else {
                            resolve(count);
                        }
                    };
                    
                    request.onerror = () => reject(request.error);
                });
                
                totalDeleted += deleted;
            }

            console.log(`🧹 ${totalDeleted} entrées anciennes supprimées d'IndexedDB`);
            return totalDeleted;
        } catch (error) {
            console.error('❌ Erreur nettoyage IndexedDB:', error);
            throw error;
        }
    }

    /**
     * Statistiques de la base de données
     */
    async getStorageStats() {
        if (!this.db) {
            return { error: 'Base de données non initialisée' };
        }

        try {
            const stats = {};
            const stores = ['documents', 'maintenance_history', 'machine_images', 'chantier_details'];

            for (const storeName of stores) {
                try {
                    // Vérifier si l'object store existe avant de l'utiliser
                    if (this.db.objectStoreNames.contains(storeName)) {
                        const transaction = this.db.transaction([storeName], 'readonly');
                        const store = transaction.objectStore(storeName);
                        const count = await this.promisifyRequest(store.count());
                        stats[storeName] = { count };
                    } else {
                        console.warn(`⚠️ Object store '${storeName}' n'existe pas`);
                        stats[storeName] = { count: 0, error: 'Store non existant' };
                    }
                } catch (storeError) {
                    console.warn(`⚠️ Erreur accès object store '${storeName}':`, storeError);
                    stats[storeName] = { count: 0, error: storeError.message };
                }
            }

            // Estimation de la taille (approximative)
            try {
                if ('estimate' in navigator.storage) {
                    const estimate = await navigator.storage.estimate();
                    stats.quota = estimate.quota;
                    stats.usage = estimate.usage;
                    stats.usagePercentage = ((estimate.usage / estimate.quota) * 100).toFixed(2);
                }
            } catch (storageError) {
                console.warn('⚠️ Impossible de récupérer les statistiques de stockage:', storageError);
                stats.storageError = storageError.message;
            }

            return stats;
        } catch (error) {
            console.error('❌ Erreur récupération statistiques:', error);
            return { error: error.message };
        }
    }

    /**
     * Utilitaires
     */
    fileToArrayBuffer(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = () => reject(reader.error);
            reader.readAsArrayBuffer(file);
        });
    }

    promisifyRequest(request) {
        return new Promise((resolve, reject) => {
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Fermeture de la base de données
     */
    close() {
        if (this.db) {
            this.db.close();
            this.db = null;
            console.log('📚 IndexedDB fermé');
        }
    }

    /**
     * Réinitialisation complète de la base de données
     */
    async resetDatabase() {
        try {
            console.log('🔄 Réinitialisation de la base de données IndexedDB...');

            // Fermer la connexion actuelle
            this.close();

            // Supprimer la base de données existante
            await this.promisifyRequest(indexedDB.deleteDatabase(this.dbName));

            // Réinitialiser
            await this.init();

            console.log('✅ Base de données IndexedDB réinitialisée avec succès');
            return true;
        } catch (error) {
            console.error('❌ Erreur réinitialisation IndexedDB:', error);
            throw error;
        }
    }
}

// Instance globale
const indexedDBManager = new IndexedDBManager();

// Export pour utilisation globale
window.IndexedDBManager = indexedDBManager;

// Fonctions utilitaires globales
window.storeDocument = async (file, metadata) => {
    return await indexedDBManager.storeDocument(file, metadata);
};

window.getDocument = async (id) => {
    return await indexedDBManager.getDocument(id);
};

window.storeMachineImage = async (machineId, imageFile, type) => {
    return await indexedDBManager.storeMachineImage(machineId, imageFile, type);
};

window.getMachineImages = async (machineId) => {
    return await indexedDBManager.getMachineImages(machineId);
};

window.resetIndexedDB = async () => {
    return await indexedDBManager.resetDatabase();
};

window.getIndexedDBStats = async () => {
    return await indexedDBManager.getStorageStats();
};

console.log('💾 IndexedDB Manager initialisé'); 