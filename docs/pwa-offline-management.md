# Gestion PWA Hors Ligne - Steamtech

## Vue d'ensemble

Ce document décrit le système de gestion PWA (Progressive Web App) hors ligne pour l'application Steamtech, permettant de désactiver le PWA pour les routes nécessitant une connexion internet.

## Fonctionnalités

### 1. Détection des Routes

Le système distingue trois types de routes :

- **Routes nécessitant une connexion** (`/dashboard/*`) : Ne peuvent pas fonctionner en mode hors ligne
- **Routes compatibles hors ligne** (`/`, `/login`, etc.) : Peuvent fonctionner sans connexion
- **Routes avec cache par défaut** : Utilisent le cache PWA standard

### 2. Comportements

#### En Mode Connecté
- Toutes les routes fonctionnent normalement
- Le service worker est actif pour optimiser les performances
- Cache intelligent selon le type de route

#### En Mode Hors Ligne
- **Routes nécessitant une connexion** : Affichage d'une page d'erreur spécifique
- **Routes compatibles hors ligne** : Fonctionnement normal avec cache
- **Autres routes** : Utilisation du cache disponible

### 3. Indicateurs Visuels

- **Indicateur de statut réseau** : Affiche l'état de la connexion
- **Avertissement pour routes nécessitant une connexion** : Modal informatif
- **Messages d'erreur contextuels** : Selon le type d'action

## Configuration

### Fichier de Configuration Principal

`assets/js/pwa-config.js` contient toute la configuration centralisée :

```javascript
const PWA_CONFIG = {
    // Routes nécessitant une connexion
    ONLINE_ONLY_ROUTES: [
        '/dashboard',
        '/dashboard/users',
        // ...
    ],
    
    // Routes compatibles hors ligne
    OFFLINE_CAPABLE_ROUTES: [
        '/',
        '/login',
        // ...
    ],
    
    // Méthodes utilitaires
    requiresOnlineConnection(pathname),
    canWorkOffline(pathname),
    getCacheStrategy(pathname),
    shouldRegisterServiceWorker(pathname, isOnline)
};
```

### Service Worker

`public/service-worker.js` implémente les stratégies de cache :

- **online-only** : Pas de cache, requête réseau uniquement
- **offline-capable** : Cache avec revalidation
- **cache-first** : Cache prioritaire, réseau en fallback

## Utilisation

### Pour les Développeurs

1. **Ajouter une nouvelle route nécessitant une connexion** :
   ```javascript
   // Dans pwa-config.js
   ONLINE_ONLY_ROUTES.push('/dashboard/nouvelle-route');
   ```

2. **Ajouter une route compatible hors ligne** :
   ```javascript
   // Dans pwa-config.js
   OFFLINE_CAPABLE_ROUTES.push('/nouvelle-page');
   ```

3. **Vérifier le statut d'une route** :
   ```javascript
   if (window.PWA_CONFIG.requiresOnlineConnection('/dashboard/users')) {
       // Route nécessite une connexion
   }
   ```

### Pour les Utilisateurs

- **Mode connecté** : Fonctionnement normal
- **Mode hors ligne sur `/dashboard`** : Message d'erreur avec redirection
- **Mode hors ligne sur `/`** : Fonctionnement normal avec cache

## Architecture Technique

### Composants

1. **PWA Config** (`pwa-config.js`) : Configuration centralisée
2. **Service Worker** (`service-worker.js`) : Gestion du cache et des requêtes
3. **Offline Manager** (`offline-manager.js`) : Détection et affichage des états
4. **App Principal** (`app.js`) : Orchestration et enregistrement du service worker

### Flux de Données

```
Utilisateur → App.js → PWA Config → Service Worker → Cache/Network
                ↓
            Offline Manager → UI Indicators
```

### Stratégies de Cache

1. **Cache First** : Pour les assets statiques (CSS, JS, images)
2. **Network First** : Pour les données dynamiques (API, JSON)
3. **Stale While Revalidate** : Pour les pages HTML
4. **Online Only** : Pour les routes nécessitant une connexion

## Tests

### Scénarios de Test

1. **Connexion normale** :
   - Accès à `/dashboard` → Fonctionne
   - Accès à `/` → Fonctionne avec cache

2. **Mode hors ligne** :
   - Accès à `/dashboard` → Page d'erreur
   - Accès à `/` → Fonctionne depuis le cache

3. **Changement d'état réseau** :
   - Perte de connexion → Indicateur affiché
   - Rétablissement → Synchronisation automatique

### Outils de Test

- **Chrome DevTools** : Onglet Application > Service Workers
- **Network Throttling** : Simuler des conditions réseau
- **Console** : Logs détaillés du comportement

## Maintenance

### Mise à Jour du Cache

Le cache est versionné et se met à jour automatiquement :

```javascript
const CACHE_VERSION = 7; // Incrémenter pour forcer la mise à jour
```

### Nettoyage

Le service worker nettoie automatiquement les anciens caches lors de l'activation.

### Monitoring

Les logs dans la console permettent de suivre :
- Enregistrement du service worker
- Stratégies de cache utilisées
- Erreurs de connexion
- Synchronisation des données

## Sécurité

- Vérification de l'origine des requêtes
- Pas de cache pour les données sensibles
- Validation des réponses réseau
- Gestion sécurisée des erreurs

## Performance

- Cache intelligent selon le type de contenu
- Limitation de la taille des caches
- Expiration automatique des données
- Optimisation des requêtes réseau 