/**
 * Script de test pour IndexedDB Manager
 * À exécuter dans la console du navigateur pour diagnostiquer les problèmes
 */

console.log('🧪 Test IndexedDB Manager...');

// Attendre que l'IndexedDB Manager soit initialisé
setTimeout(async () => {
    try {
        console.log('📊 Récupération des statistiques IndexedDB...');
        
        // Test des statistiques
        const stats = await window.getIndexedDBStats();
        console.log('📈 Statistiques IndexedDB:', stats);
        
        // Test de récupération des documents
        console.log('📄 Test récupération documents...');
        const documents = await window.getDocument(1);
        console.log('📄 Document récupéré:', documents);
        
        // Test de récupération des images de machine
        console.log('🖼️ Test récupération images machine...');
        const images = await window.getMachineImages('test-machine');
        console.log('🖼️ Images machine récupérées:', images);
        
        console.log('✅ Tests IndexedDB terminés avec succès');
        
    } catch (error) {
        console.error('❌ Erreur lors des tests IndexedDB:', error);
        
        // Si l'erreur persiste, proposer une réinitialisation
        if (error.message.includes('objectStore') || error.message.includes('not found')) {
            console.log('🔄 Erreur de structure IndexedDB détectée');
            console.log('💡 Pour résoudre le problème, exécutez dans la console:');
            console.log('   await window.resetIndexedDB()');
        }
    }
}, 2000);

// Fonction de diagnostic rapide
window.diagnoseIndexedDB = async () => {
    console.log('🔍 Diagnostic IndexedDB...');
    
    try {
        // Vérifier si IndexedDB est supporté
        if (!window.indexedDB) {
            console.error('❌ IndexedDB non supporté par ce navigateur');
            return;
        }
        
        // Vérifier si le manager existe
        if (!window.IndexedDBManager) {
            console.error('❌ IndexedDB Manager non initialisé');
            return;
        }
        
        // Vérifier la connexion à la base de données
        if (!window.IndexedDBManager.db) {
            console.error('❌ Base de données IndexedDB non connectée');
            return;
        }
        
        // Lister les object stores disponibles
        const storeNames = Array.from(window.IndexedDBManager.db.objectStoreNames);
        console.log('📚 Object stores disponibles:', storeNames);
        
        // Test des statistiques
        const stats = await window.getIndexedDBStats();
        console.log('📊 Statistiques:', stats);
        
        console.log('✅ Diagnostic terminé - IndexedDB fonctionne correctement');
        
    } catch (error) {
        console.error('❌ Erreur diagnostic:', error);
    }
};

console.log('💡 Pour diagnostiquer IndexedDB, exécutez: window.diagnoseIndexedDB()'); 