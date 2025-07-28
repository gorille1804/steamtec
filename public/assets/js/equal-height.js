document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour égaliser la hauteur des blocs .content_texte_nettoyage
    function equalizeHeight() {
        const textBlocks = document.querySelectorAll('.content_texte_nettoyage');
        
        if (textBlocks.length === 0) return;
        
        // Réinitialiser les hauteurs pour recalculer
        textBlocks.forEach(block => {
            block.style.height = 'auto';
        });
        
        // Trouver la hauteur maximale
        let maxHeight = 0;
        textBlocks.forEach(block => {
            const height = block.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });
        
        // Appliquer la hauteur maximale à tous les blocs
        textBlocks.forEach(block => {
            block.style.height = maxHeight + 'px';
        });
    }
    
    // Exécuter au chargement
    equalizeHeight();
    
    // Ré-exécuter lors du redimensionnement de la fenêtre
    window.addEventListener('resize', equalizeHeight);
}); 