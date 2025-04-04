console.log("=== CUSTOM JS LOADED SUCCESSFULLY ===");

// Ajoutez ici le code pour les onglets
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded, initializing tab handlers");
    
    // Ajouter un gestionnaire d'événements pour le défilement en douceur
    const configLinks = document.querySelectorAll('.config_lik_group a');
    
    if (configLinks.length > 0) {
        console.log(`Found ${configLinks.length} configuration links`);
        
        configLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                console.log(`Link clicked: ${this.getAttribute('href')}`);
                
                // Faire défiler jusqu'à la section des onglets après un court délai
                setTimeout(() => {
                    const headerNav = document.querySelector('.header-navigations');
                    if (headerNav) {
                        headerNav.scrollIntoView({
                            behavior: 'smooth'
                        });
                        console.log("Scrolled to navigation section");
                    } else {
                        console.error("Header navigation element not found");
                    }
                }, 50);
            });
        });
    } else {
        console.warn("No configuration links found on this page");
    }
});
