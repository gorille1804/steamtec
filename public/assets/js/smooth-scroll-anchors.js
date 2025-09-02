// Amélioration du défilement des liens d'ancrage
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les liens d'ancrage dans les sous-menus
    const anchorLinks = document.querySelectorAll('.sous-menu a[href*="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Vérifier si c'est un lien d'ancrage (contient #)
            if (href && href.includes('#')) {
                e.preventDefault();
                
                // Extraire l'ID de l'ancrage
                const targetId = href.split('#')[1];
                const targetElement = document.getElementById(targetId);
                
                // Vérifier si l'élément cible existe sur la page actuelle
                if (targetElement) {
                    // L'élément existe, faire le défilement fluide
                    const headerHeight = 0; // Correspond à la valeur CSS
                    const targetPosition = targetElement.offsetTop - headerHeight;
                    
                    // Défilement fluide vers la cible
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Fermer le menu mobile si ouvert
                    const contentHeader = document.querySelector('.content_header_bottom');
                    if (contentHeader && contentHeader.classList.contains('active')) {
                        contentHeader.classList.remove('active');
                        document.querySelector('html').classList.remove('no-scroll');
                    }
                } else {
                    // L'élément n'existe pas sur cette page, charger la page avec l'ancrage
                    window.location.href = href;
                }
            }
        });
    });
    
    // Gestion des liens d'ancrage directs dans l'URL
    if (window.location.hash) {
        setTimeout(function() {
            const targetId = window.location.hash.substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const headerHeight = 0;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }, 100);
    }
    
    // Sélectionne tous les liens de ul.config_lik_group a[href*="#"]
    const configLinks = document.querySelectorAll('ul.config_lik_group a[href*="#"]');
    configLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            // Simule le clic sur le bouton button.nav-link dont data-bs-target=href
            const button = document.querySelector(`button.nav-link[data-bs-target="${href}"]`);
            if (button) {
                button.click();
            }
        });
    });
}); 