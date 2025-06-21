(function () {
    const maintenanceData = {
        "task_mapping": {
            "vidanger_huile_moteur": "Vidanger l'huile de la pompe",
            "nettoyer_filtre_air": "Nettoyer filtre à air",
            "graisser_unite_coupe": "Graisser unité de coupe",
            "nettoyer_les_ailettes_moteur": "Nettoyer les ailettes et le moteur",
            "controler_pneus_roues": "Contrôler pneus et roues",
            "faire_un_nettoyage_de_la_bougie": "Faire un nettoyage de la bougie"
        },
        "maintenance_schedule": [
            { "heures": "50", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "100", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "200", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "300", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "400", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "500", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "600", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "700", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "800", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "900", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1000", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1100", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1200", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "1300", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1400", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1500", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1600", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "1700", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1800", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "1900", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2000", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "2100", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2200", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2300", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2400", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "2500", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2600", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2700", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "2800", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "2900", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "3000", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "3100", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "3200", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": true },
            { "heures": "3300", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "3400", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false },
            { "heures": "3500", "vidanger_huile_moteur": true, "nettoyer_filtre_air": true, "graisser_unite_coupe": true, "nettoyer_les_ailettes_moteur": true, "controler_pneus_roues": true, "faire_un_nettoyage_de_la_bougie": false }
        ]
    };

    let currentHighlightedRow = null;
    let debounceTimer;

    function generateMaintenanceTable() {
        const tableBody = document.getElementById('maintenanceTableBody');
        if (!tableBody) {
            console.error("L'élément 'maintenanceTableBody' n'a pas été trouvé.");
            return;
        }

        const schedule = maintenanceData.maintenance_schedule;
        const taskKeys = Object.keys(maintenanceData.task_mapping);

        tableBody.innerHTML = '';

        schedule.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.dataset.hours = row.heures;
            tr.dataset.rowIndex = index;

            const hoursCell = document.createElement('td');
            hoursCell.className = 'hours-cell';
            hoursCell.textContent = row.heures + 'h';
            tr.appendChild(hoursCell);

            taskKeys.forEach(taskKey => {
                const td = document.createElement('td');
                const isRequired = row[taskKey];

                if (isRequired) {
                    td.innerHTML = '<i class="mdi mdi-check-circle task-icon required" title="Requis"></i>';
                    td.className = 'task-required';
                } else {
                    td.innerHTML = '<i class="mdi mdi-minus-circle task-icon not-required" title="Non requis"></i>';
                    td.className = 'task-not-required';
                }

                tr.appendChild(td);
            });

            tableBody.appendChild(tr);
        });
    }

    function clearHighlight() {
        if (currentHighlightedRow) {
            currentHighlightedRow.classList.remove('highlighted-row');
            currentHighlightedRow = null;
        }
    }

    function highlightRow(row) {
        clearHighlight();
        if (row) {
            row.classList.add('highlighted-row');
            currentHighlightedRow = row;

            // Faire défiler vers la ligne mise en surbrillance
            row.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    function findClosestRow(hours) {
        const schedule = maintenanceData.maintenance_schedule;
        let closestRow = null;
        let minDifference = Infinity;

        schedule.forEach((item, index) => {
            const scheduleHours = parseInt(item.heures);
            const difference = Math.abs(scheduleHours - hours);

            if (difference < minDifference) {
                minDifference = difference;
                closestRow = document.querySelector(`tr[data-row-index="${index}"]`);
            }
        });

        return { row: closestRow, difference: minDifference };
    }

    function updateHoursInfo(hours, closestRow, difference) {
        const hoursInfo = document.getElementById('hoursInfo');
        const hoursInfoText = document.getElementById('hoursInfoText');

        if (closestRow) {
            const scheduleHours = closestRow.dataset.hours;
            const scheduleData = maintenanceData.maintenance_schedule.find(item => item.heures === scheduleHours);

            if (scheduleData) {
                const requiredTasks = Object.keys(scheduleData).filter(key =>
                    key !== 'heures' && scheduleData[key] === true
                );

                const taskNames = requiredTasks.map(taskKey =>
                    maintenanceData.task_mapping[taskKey]
                );

                let infoText = `Entretien prévu à ${scheduleHours}h`;
                if (difference > 0) {
                    infoText += ` (${difference}h de différence)`;
                }
                infoText += `<br><strong>Tâches requises :</strong> ${taskNames.join(', ')}`;

                hoursInfoText.innerHTML = infoText;
                hoursInfo.style.display = 'block';
            }
        } else {
            hoursInfo.style.display = 'none';
        }
    }

    function locateHours() {
        const hoursInput = document.getElementById('machineHours');
        const hours = parseInt(hoursInput.value);

        if (isNaN(hours) || hours < 0) {
            clearHighlight();
            document.getElementById('hoursInfo').style.display = 'none';
            return;
        }

        const { row: closestRow, difference } = findClosestRow(hours);

        if (closestRow) {
            highlightRow(closestRow);
            updateHoursInfo(hours, closestRow, difference);
        } else {
            clearHighlight();
            document.getElementById('hoursInfo').style.display = 'none';
        }
    }

    function initializeEventListeners() {
        const locateButton = document.getElementById('locateHours');
        const hoursInput = document.getElementById('machineHours');

        if (locateButton) {
            locateButton.addEventListener('click', locateHours);
        }

        if (hoursInput) {
            hoursInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(locateHours, 300);
            });

            hoursInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Empêcher la soumission de formulaire si applicable
                    clearTimeout(debounceTimer);
                    locateHours();
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            generateMaintenanceTable();
            initializeEventListeners();
        });
    } else {
        generateMaintenanceTable();
        initializeEventListeners();
    }
})(); 