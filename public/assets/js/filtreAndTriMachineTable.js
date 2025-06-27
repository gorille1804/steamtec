document.addEventListener('DOMContentLoaded', function() {

    // Code pour le tri des machines
        const headers = document.querySelectorAll('th');
        headers.forEach(header => {
            const icon = document.createElement('i');
            icon.classList.add('mdi', 'mdi-arrow-up-down'); 
            header.appendChild(icon);
            header.addEventListener('click', function() {
                const table = header.closest('table');
                const rows = Array.from(table.querySelectorAll('tr:nth-child(n+2)')); 
                const index = Array.from(header.parentElement.children).indexOf(header);
                const ascending = header.classList.contains('ascending');
                // Trier les lignes
                rows.sort((rowA, rowB) => {
                    const cellA = rowA.children[index].textContent.trim().toLowerCase(); 
                    const cellB = rowB.children[index].textContent.trim().toLowerCase();
                    if (ascending) {
                        return cellA > cellB ? 1 : (cellA < cellB ? -1 : 0); 
                    } else {
                        return cellA < cellB ? 1 : (cellA > cellB ? -1 : 0); 
                    }
                });
                // Réorganiser les lignes dans le tableau
                rows.forEach(row => table.appendChild(row));
                // Mettre à jour les icônes de tri
                headers.forEach(h => {
                    h.classList.remove('ascending', 'descending');
                    const icon = h.querySelector('i');
                    if (icon) icon.classList.remove('mdi-arrow-up', 'mdi-arrow-down');
                });
                // Ajouter l'icône et les classes de tri
                if (ascending) {
                    header.classList.add('descending');
                    icon.classList.add('mdi-arrow-down');
                } else {
                    header.classList.add('ascending');
                    icon.classList.add('mdi-arrow-up');
                }
            });
        });


    // Code pour le tri des machines
        const filterInput = document.getElementById('tableFilter');
        filterInput.addEventListener('input', function () {
            const filterText = filterInput.value.toLowerCase(); // Convertir le texte de filtre en minuscule
            const rows = document.querySelectorAll('#myTable tbody tr'); // Sélectionner toutes les lignes du tableau
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let matches = false;
                // Vérifier si le texte de chaque cellule de la ligne correspond au texte de filtre
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filterText)) {
                        matches = true; // Si une cellule contient le texte, la ligne correspond au filtre
                    }
                });
                // Afficher ou masquer la ligne en fonction de la correspondance
                if (matches) {
                    row.style.display = ''; // Afficher la ligne
                } else {
                    row.style.display = 'none'; // Masquer la ligne
                }
            });
        });

    });