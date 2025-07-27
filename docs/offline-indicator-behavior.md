# Comportement de l'Indicateur Offline - Steamtech

## Vue d'ensemble

L'indicateur de statut réseau (offline/online) ne s'affiche plus automatiquement sur toutes les pages. Il est maintenant conditionnel selon le mode d'utilisation de l'application.

## Règles d'Affichage

### 🚫 **Indicateur Masqué (Mode Navigateur)**

L'indicateur ne s'affiche **PAS** quand :
- L'application est ouverte dans un navigateur web classique
- L'utilisateur navigue sur les pages publiques (`/`, `/login`, etc.)
- L'application n'est pas installée en mode PWA

**Comportement** :
- Aucun indicateur visuel
- Les événements réseau sont toujours écoutés (logs dans la console)
- Pas d'interruption de l'expérience utilisateur

### ✅ **Indicateur Affiché (Mode PWA)**

L'indicateur s'affiche **SEULEMENT** quand :
- L'application est installée en mode PWA (standalone)
- **OU** l'utilisateur navigue sur une route nécessitant une connexion (`/dashboard/*`)

**Comportement** :
- Indicateur visuel en haut à droite
- Notifications de changement d'état réseau
- Avertissements pour les routes nécessitant une connexion

## Routes Concernées

### Routes avec Indicateur (Même en Mode Navigateur)
```
/dashboard
/dashboard/users
/dashboard/machines
/dashboard/chantiers
/dashboard/entretiens
/dashboard/profile
/dashboard/arbre-de-depannage
/dashboard/documents
/dashboard/parc-machine
/dashboard/user-machine
/dashboard/historique
/dashboard/configuration
/dashboard/about
/dashboard/accessoire
/dashboard/desherbage
/dashboard/nettoyage
/dashboard/contact
/dashboard/societe
```

### Routes sans Indicateur (Mode Navigateur)
```
/
/login
/forgot-password
/reset-password
```

## Détection Automatique

### Mode PWA
```javascript
// Détection du mode standalone
const isStandalone = window.matchMedia('(display-mode: standalone)').matches ||
                    window.navigator.standalone === true;
```

### Routes Nécessitant une Connexion
```javascript
// Vérification automatique des routes
const requiresOnlineRoutes = ['/dashboard', '/dashboard/users', /* ... */];
const isOnlineOnlyRoute = requiresOnlineRoutes.some(route => 
    currentPath.startsWith(route)
);
```

## Activation Dynamique

### Après Installation PWA
Quand l'utilisateur installe l'application :
1. L'événement `appinstalled` est déclenché
2. L'état PWA est mis à jour automatiquement
3. L'indicateur réseau est activé si nécessaire

```javascript
window.addEventListener('appinstalled', (evt) => {
    // Mise à jour automatique de l'état PWA
    setTimeout(() => {
        if (window.updatePWAStatus) {
            window.updatePWAStatus();
        }
    }, 1000);
});
```

## Fonctions Utilitaires

### Vérifier l'État Actuel
```javascript
// Obtenir le statut complet
const status = window.OfflineManager.getNetworkStatus();
console.log('En ligne:', status.isOnline);
console.log('Mode PWA:', status.isPWA);
```

### Forcer la Mise à Jour
```javascript
// Mettre à jour manuellement l'état PWA
window.updatePWAStatus();
```

### Diagnostic
```javascript
// Vérifier la configuration actuelle
console.log('Mode standalone:', window.matchMedia('(display-mode: standalone)').matches);
console.log('Route actuelle:', window.location.pathname);
console.log('Indicateur actif:', !!document.getElementById('network-status-indicator'));
```

## Logs de Debug

### Mode Navigateur
```
🌐 Mode navigateur détecté - Indicateur réseau désactivé
🟢 Connexion rétablie (mode navigateur)
🔴 Connexion perdue (mode navigateur)
```

### Mode PWA
```
📱 Mode PWA détecté - Indicateur réseau activé
🟢 Connexion rétablie
🔴 Connexion perdue - Mode offline activé
```

### Activation Dynamique
```
📱 Passage en mode PWA - Activation de l'indicateur réseau
```

## Avantages

### Pour l'Utilisateur
- **Expérience plus fluide** : Pas d'indicateur intrusif en mode navigateur
- **Feedback contextuel** : Indicateur seulement quand nécessaire
- **Installation progressive** : L'indicateur apparaît après installation PWA

### Pour le Développement
- **Code plus propre** : Logique conditionnelle claire
- **Maintenance facilitée** : Configuration centralisée
- **Tests simplifiés** : Comportement prévisible

## Configuration

### Modifier les Routes
Pour ajouter une route nécessitant une connexion :

```javascript
// Dans offline-manager.js
const requiresOnlineRoutes = [
    '/dashboard',
    '/dashboard/users',
    '/nouvelle-route', // Ajouter ici
    // ...
];
```

### Personnaliser le Comportement
```javascript
// Désactiver complètement l'indicateur
localStorage.setItem('disable-offline-indicator', 'true');

// Forcer l'affichage (mode debug)
localStorage.setItem('force-offline-indicator', 'true');
```

## Tests

### Scénarios de Test

1. **Mode Navigateur - Page Publique** :
   - Aller sur `/`
   - Vérifier qu'aucun indicateur n'est visible
   - Vérifier les logs dans la console

2. **Mode Navigateur - Dashboard** :
   - Aller sur `/dashboard`
   - Vérifier que l'indicateur est visible
   - Tester le mode hors ligne

3. **Mode PWA** :
   - Installer l'application
   - Vérifier que l'indicateur apparaît
   - Tester toutes les pages

4. **Transition Navigateur → PWA** :
   - Installer l'application depuis le navigateur
   - Vérifier l'activation automatique de l'indicateur

### Outils de Test

```javascript
// Forcer le mode PWA (pour tests)
localStorage.setItem('force-pwa-mode', 'true');
location.reload();

// Réinitialiser les paramètres
localStorage.removeItem('force-pwa-mode');
localStorage.removeItem('disable-offline-indicator');
location.reload();
```

## Support

En cas de problème :
1. Vérifier les logs dans la console
2. Utiliser `window.updatePWAStatus()` pour forcer la mise à jour
3. Vérifier que l'application est bien en mode PWA
4. Consulter la documentation de dépannage IndexedDB si nécessaire 