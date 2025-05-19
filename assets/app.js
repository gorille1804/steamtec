import './bootstrap.js';
import './js/custome.js';
import DecisionTree from './js/decision-tree';
window.DecisionTree = DecisionTree;
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/sass/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Fonction pour détecter si l'appareil est mobile
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
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
  
