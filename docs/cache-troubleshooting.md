# Guide de résolution des problèmes de cache

## Problème : "Failed to load data - Content unavailable. Resource was not cached"

Cette erreur indique que le service worker ne parvient pas à charger correctement les ressources depuis le cache ou le réseau.

## Solutions rapides

### 1. Vider le cache du navigateur
1. Ouvrir les outils de développement (F12)
2. Aller dans l'onglet "Application" ou "Storage"
3. Dans la section "Storage", cliquer sur "Clear storage"
4. Cocher toutes les options et cliquer sur "Clear site data"
5. Recharger la page

### 2. Utiliser le script de débogage (en mode développement)
Si vous êtes en mode développement, un script de débogage est automatiquement chargé.

Dans la console du navigateur, tapez :
```javascript
// Vérifier l'état du cache
checkCacheStatus()

// Forcer la mise à jour complète
forceCacheUpdate()
```

### 3. Désactiver temporairement le service worker
Dans la console du navigateur :
```javascript
// Désenregistrer le service worker
navigator.serviceWorker.getRegistrations().then(function(registrations) {
    for(let registration of registrations) {
        registration.unregister();
    }
});

// Nettoyer les caches
caches.keys().then(function(names) {
    for (let name of names) {
        caches.delete(name);
    }
});

// Recharger la page
location.reload();
```

## Solutions avancées

### 1. Vérifier la configuration du service worker
Le service worker utilise plusieurs stratégies de cache :
- **Cache First** : Pour les assets statiques (CSS, JS, images)
- **Network First** : Pour les données JSON et API
- **Stale While Revalidate** : Pour les pages HTML

### 2. Vérifier les fichiers de cache
Les fichiers suivants sont mis en cache :
- `/build/app.js`
- `/build/app.css`
- `/build/runtime.js`
- `/assets/app.js`
- `/assets/bootstrap.js`
- Et d'autres assets statiques

### 3. Forcer la mise à jour du service worker
Le service worker a été mis à jour avec une nouvelle version (v8) qui inclut :
- Nettoyage automatique des anciens caches
- Meilleure gestion des erreurs
- Logs détaillés pour le débogage

## Diagnostic

### Vérifier les logs du service worker
1. Ouvrir les outils de développement
2. Aller dans l'onglet "Application" > "Service Workers"
3. Vérifier les logs pour identifier les erreurs

### Vérifier l'état du cache
Dans la console :
```javascript
// Lister tous les caches
caches.keys().then(keys => console.log('Caches:', keys));

// Vérifier le contenu d'un cache spécifique
caches.open('steamtec-v8').then(cache => {
    cache.keys().then(keys => {
        console.log('Contenu du cache:', keys.map(req => req.url));
    });
});
```

## Prévention

### 1. Version du cache
Le service worker utilise un système de versioning. Quand vous modifiez des assets :
- Incrémenter `CACHE_VERSION` dans `service-worker.js`
- Cela force automatiquement la mise à jour du cache

### 2. Stratégies de cache appropriées
- Utiliser "Cache First" pour les assets statiques
- Utiliser "Network First" pour les données dynamiques
- Utiliser "Stale While Revalidate" pour les pages HTML

### 3. Gestion des erreurs
Le service worker inclut maintenant une meilleure gestion des erreurs avec :
- Logs détaillés
- Réponses d'erreur informatives
- Fallback vers le cache en cas d'erreur réseau

## Contact

Si les problèmes persistent, vérifiez :
1. Les logs du service worker dans la console
2. L'état du cache avec `checkCacheStatus()`
3. Les erreurs réseau dans l'onglet "Network" des outils de développement 