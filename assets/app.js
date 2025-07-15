import './bootstrap.js';
import './js/custome.js';
import './js/offline-manager.js';
import './js/local-storage-manager.js';
import './js/indexeddb-manager.js';
import './js/cache-optimizer.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/sass/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Variables globales pour la gestion de l'installation PWA
let deferredPrompt;
let installBanner = null;

// Fonction pour détecter si l'appareil est mobile
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

// Fonction pour détecter iOS Safari
function isIOSSafari() {
    const iOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    const safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    return iOS && safari;
}

// Fonction pour vérifier si l'app est déjà installée
function isAppInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true;
}

// Vérifier si le navigateur supporte les notifications et Service Workers
if ("serviceWorker" in navigator && "Notification" in window) {
    console.log("📢 Service Worker détecté !");
    
    // N'enregistrer le Service Worker que sur mobile
    if (isMobileDevice()) {
        navigator.serviceWorker.register("/service-worker.js")
            .then(registration => {
                registration.update();
                console.log("✅ Service Worker enregistré :", registration);
                demanderPermissionNotification();

                // Gérer l'installation PWA après l'enregistrement du service worker
                setTimeout(() => {
                    gererInstallationPWA();
                }, 2000); // Attendre 2 secondes pour que tout soit initialisé
            })
            .catch(error => console.error("❌ Erreur d'enregistrement du Service Worker :", error));
    } else {
        console.log("ℹ️ Service Worker non enregistré car appareil desktop détecté");
    }
} else {
    console.warn("🚨 Notifications ou Service Workers non supportés sur ce navigateur.");
}

// Demande de permission pour les notifications
function demanderPermissionNotification() {
    if (Notification.permission === "granted") {
        envoyerNotification();
    } else if (Notification.permission === "denied") {
        alert("❌ Notifications bloquées ! Active-les dans les paramètres du navigateur.");
    } else {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                envoyerNotification();
            } else {
                console.warn("⚠️ L'utilisateur a refusé les notifications.");
            }
        });
    }
}

// Fonction pour envoyer une notification
function envoyerNotification() {
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.ready.then(registration => {
            registration.showNotification("🔔 Notification Active !", {
                body: "le notification depuis l'application steamtech est activé🎉",
                icon: "assets/icons/icon-192x192.png", // Remplace avec une vraie icône
                vibrate: [200, 100, 200],
                tag: "test-notification"
            });
        }).catch(error => console.error("❌ Erreur lors de l'affichage de la notification :", error));
    }
} 

// Vérifier le support de 'windowControlsOverlay'
if ('windowControlsOverlay' in navigator) {
    console.log("Windows Control Overlay activé");

    navigator.windowControlsOverlay.addEventListener("geometrychange", (event) => {
        console.log("Taille de l'overlay :", event.bounds);
    });
} else {
    console.warn("🚨 Windows Control Overlay non supporté.");
}

// ==================== GESTION INSTALLATION PWA ====================

// Écouter l'événement beforeinstallprompt
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('📱 Événement beforeinstallprompt capturé');
    // Empêcher la bannière automatique du navigateur
    e.preventDefault();
    // Stocker l'événement pour l'utiliser plus tard
    deferredPrompt = e;
});

// Fonction principale de gestion de l'installation PWA
function gererInstallationPWA() {
    // Ne pas afficher si l'app est déjà installée
    if (isAppInstalled()) {
        console.log('✅ PWA déjà installée');
        return;
    }

    console.log('🔍 Vérification des conditions d\'installation PWA...');

    // Vérifier si on doit montrer la bannière d'installation
    const shouldShowBanner = localStorage.getItem('pwa-install-dismissed') !== 'true';

    if (shouldShowBanner && isMobileDevice()) {
        // Délai avant d'afficher la bannière pour ne pas être intrusif
        setTimeout(() => {
            afficherBanniereInstallation();
        }, 3000);
    }
}

// Fonction pour créer et afficher la bannière d'installation
function afficherBanniereInstallation() {
    // Ne pas créer plusieurs bannières
    if (installBanner) return;

    console.log('📱 Affichage de la bannière d\'installation PWA');

    // Créer la bannière d'installation
    installBanner = document.createElement('div');
    installBanner.id = 'pwa-install-banner';
    installBanner.innerHTML = `
        <div class="pwa-banner-content">
            <div class="pwa-banner-icon">
                <img src="/assets/icons/icon-192x192.png" alt="SteamTec" />
            </div>
            <div class="pwa-banner-text">
                <h4>Installer SteamTec</h4>
                <p>${isIOSSafari() ?
            'Appuie sur <strong>Partager</strong> puis <strong>Sur l\'écran d\'accueil</strong>' :
            'Installe l\'app pour un accès rapide hors-ligne'
        }</p>
            </div>
            <div class="pwa-banner-actions">
                ${!isIOSSafari() ? '<button id="pwa-install-btn" class="btn-install">Installer</button>' : ''}
                <button id="pwa-dismiss-btn" class="btn-dismiss">✕</button>
            </div>
        </div>
    `;

    // Ajouter la bannière au body
    document.body.appendChild(installBanner);

    // Ajouter les événements
    const installBtn = document.getElementById('pwa-install-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');

    if (installBtn) {
        installBtn.addEventListener('click', installerPWA);
    }

    dismissBtn.addEventListener('click', fermerBanniere);

    // Auto-fermeture après 30 secondes
    setTimeout(() => {
        if (installBanner) {
            fermerBanniere();
        }
    }, 30000);
}

// Fonction pour installer la PWA
async function installerPWA() {
    if (!deferredPrompt) {
        console.log('❌ Prompt d\'installation non disponible');
        return;
    }

    console.log('🚀 Installation de la PWA...');

    // Afficher le prompt d'installation
    deferredPrompt.prompt();

    // Attendre la réponse de l'utilisateur
    const choiceResult = await deferredPrompt.userChoice;

    if (choiceResult.outcome === 'accepted') {
        console.log('✅ L\'utilisateur a accepté l\'installation');
        // Notification de succès
        afficherNotificationInstallation('✅ SteamTec installé avec succès !', 'success');

        // Marquer comme installé pour éviter de redemander
        localStorage.setItem('pwa-installed', 'true');
    } else {
        console.log('❌ L\'utilisateur a refusé l\'installation');
        afficherNotificationInstallation('Installation annulée', 'info');
    }

    // Nettoyer
    deferredPrompt = null;
    fermerBanniere();
}

// Fonction pour fermer la bannière
function fermerBanniere() {
    if (installBanner) {
        installBanner.style.animation = 'slideOutDown 0.3s ease-in-out';
        setTimeout(() => {
            if (installBanner && installBanner.parentNode) {
                installBanner.parentNode.removeChild(installBanner);
                installBanner = null;
            }
        }, 300);

        // Marquer comme fermé pour ne plus afficher pendant cette session
        localStorage.setItem('pwa-install-dismissed', 'true');
    }
}

// Fonction pour afficher une notification d'installation
function afficherNotificationInstallation(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `pwa-notification pwa-notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">✕</button>
    `;

    document.body.appendChild(notification);

    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Écouter les événements d'installation
window.addEventListener('appinstalled', (evt) => {
    console.log('🎉 PWA installée avec succès !');
    afficherNotificationInstallation('🎉 SteamTec ajouté à votre écran d\'accueil !', 'success');
    localStorage.setItem('pwa-installed', 'true');
    fermerBanniere();
});

// ==================== FONCTIONS DEBUG PWA (pour développement) ====================

// Fonction pour forcer l'affichage de la bannière (pour tests)
window.forceShowPWABanner = function () {
    localStorage.removeItem('pwa-install-dismissed');
    localStorage.removeItem('pwa-installed');
    if (installBanner) {
        fermerBanniere();
    }
    setTimeout(() => {
        afficherBanniereInstallation();
    }, 500);
    console.log('🧪 Bannière PWA forcée (mode debug)');
};

// Fonction pour réinitialiser l'état d'installation PWA
window.resetPWAState = function () {
    localStorage.removeItem('pwa-install-dismissed');
    localStorage.removeItem('pwa-installed');
    if (installBanner) {
        fermerBanniere();
    }
    console.log('🔄 État PWA réinitialisé');
};

// Fonction pour obtenir l'état actuel de la PWA
window.getPWAStatus = function () {
    const status = {
        isInstalled: isAppInstalled(),
        isMobile: isMobileDevice(),
        isIOSSafari: isIOSSafari(),
        hasDeferredPrompt: !!deferredPrompt,
        installDismissed: localStorage.getItem('pwa-install-dismissed') === 'true',
        markedAsInstalled: localStorage.getItem('pwa-installed') === 'true',
        bannerVisible: !!installBanner
    };
    console.table(status);
    return status;
};

console.log('🛠️ Fonctions debug PWA disponibles : forceShowPWABanner(), resetPWAState(), getPWAStatus()');
  
