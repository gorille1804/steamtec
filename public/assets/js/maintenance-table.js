(function () {
    const maintenanceData = {
        "task_mapping": {
            "vidanger_huile_moteur": {
                "name": "Vidanger l'huile de la pompe *",
                "pdf_link": "/assets/pdf/maintenance/vidanger_huile_moteur.pdf"
            },
            "nettoyer_filtre_air": {
                "name": "Nettoyer filtre entrée pompe",
                "pdf_link": "/assets/pdf/maintenance/nettoyer_filtre_air.pdf"
            },
            "graisser_unite_coupe": {
                "name": "Changer filtre à carburant",
                "pdf_link": "/assets/pdf/maintenance/graisser_unite_coupe.pdf"
            },
            "nettoyer_les_ailettes_moteur": {
                "name": "Nettoyer les buses de diffusion vapeur",
                "pdf_link": "/assets/pdf/maintenance/nettoyer_les_ailettes_moteur.pdf"
            },
            "controler_pneus_roues": {
                "name": "Changer les joints de flexibles",
                "pdf_link": "/assets/pdf/maintenance/controler_pneus_roues.pdf"
            },
            "faire_un_nettoyage_de_la_bougie": {
                "name": "Faire un détartrage de la STEAM_Tec",
                "pdf_link": "/assets/pdf/maintenance/faire_un_nettoyage_de_la_bougie.pdf"
            }
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

    // Variables globales
    let currentHighlightedRow = null;
    let debounceTimer = null;
    let selectedMachineId = null;
    let selectedMachineName = null;
    let currentModalData = null;
    let existingMaintenanceLogs = []; // Nouvelle variable pour stocker les logs existants
    // Compteur global pour les requêtes AJAX
    let activeAjaxRequests = 0;

    // Fonctions pour le backdrop de chargement
    function showLoadingBackdrop() {
        activeAjaxRequests++;
        if (!document.getElementById('loadingBackdrop')) {
            const backdrop = document.createElement('div');
            backdrop.id = 'loadingBackdrop';
            backdrop.className = 'loading-backdrop';
            backdrop.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <div class="loading-text mt-2">Chargement en cours...</div>
                </div>
            `;
            document.body.appendChild(backdrop);
        }
        // Afficher le backdrop seulement si c'est la première requête
        if (activeAjaxRequests === 1) {
            document.getElementById('loadingBackdrop').style.display = 'flex';
        }
    }

    function hideLoadingBackdrop() {
        activeAjaxRequests = Math.max(0, activeAjaxRequests - 1);
        if (activeAjaxRequests === 0) {
            const backdrop = document.getElementById('loadingBackdrop');
            if (backdrop) {
                backdrop.style.display = 'none';
            }
        }
    }

    // Fonction pour récupérer les logs d'entretien existants
    async function fetchExistingMaintenanceLogs(machineId) {
        try {
            showLoadingBackdrop();
            const response = await fetch(`/dashboard/entretiens/maintenance-table/logs?machineId=${machineId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    existingMaintenanceLogs = data.logs;
                    console.log('Logs récupérés:', existingMaintenanceLogs);
                } else {
                    console.error('Erreur lors de la récupération des logs:', data.message);
                }
            } else {
                console.error('Erreur lors de la récupération des logs:', response.statusText);
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des logs:', error);
        } finally {
            hideLoadingBackdrop();
        }
    }

    // Fonction pour marquer les cellules avec les entretiens effectués
    function markCompletedMaintenanceCells() {
        console.log('Début de markCompletedMaintenanceCells');
        console.log('selectedMachineId:', selectedMachineId);
        console.log('existingMaintenanceLogs:', existingMaintenanceLogs);

        if (!selectedMachineId || existingMaintenanceLogs.length === 0) {
            console.log('Conditions non remplies - selectedMachineId:', !!selectedMachineId, 'logs count:', existingMaintenanceLogs.length);
            return;
        }

        // Filtrer les logs pour la machine sélectionnée
        const machineLogs = existingMaintenanceLogs.filter(log =>
            log.parcMachineId === selectedMachineId
        );

        console.log('Logs filtrés pour la machine:', machineLogs);

        machineLogs.forEach(log => {
            console.log('Traitement du log:', log);

            // Essayer de trouver la cellule avec le nom de la tâche
            let cell = document.querySelector(
                `td[data-hours="${log.hours}"][data-task-name="${log.activity}"]`
            );

            // Si pas trouvé, essayer avec la clé de la tâche
            if (!cell) {
                // Trouver la clé correspondante au nom
                const taskKey = Object.keys(maintenanceData.task_mapping).find(key =>
                    maintenanceData.task_mapping[key].name === log.activity
                );

                if (taskKey) {
                    cell = document.querySelector(
                        `td[data-hours="${log.hours}"][data-task-key="${taskKey}"]`
                    );
                }
            }

            // Si toujours pas trouvé, essayer avec la clé directement (pour les anciens logs)
            if (!cell) {
                cell = document.querySelector(
                    `td[data-hours="${log.hours}"][data-task-key="${log.activity}"]`
                );
            }

            console.log('Cellule trouvée:', cell);
            console.log('Sélecteur utilisé:', `td[data-hours="${log.hours}"][data-task-name="${log.activity}"]`);

            if (cell) {
                console.log('Marquage de la cellule comme complétée');

                // Marquer la cellule comme complétée
                cell.classList.add('completed-maintenance');
                cell.classList.remove('clickable-cell');

                // Afficher la date au lieu de l'icône
                const date = new Date(log.date);
                const formattedDate = date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                cell.innerHTML = `<span class="maintenance-date">${formattedDate}</span>`;
                cell.title = `Entretien effectué le ${formattedDate}`;

                // Ajouter les informations du log
                cell.dataset.maintenanceDate = log.date;
                cell.dataset.maintenanceLogId = log.id;

                console.log('Cellule marquée avec succès');
            } else {
                console.log('Cellule non trouvée pour le log:', log);
            }
        });

        console.log('Fin de markCompletedMaintenanceCells');
    }

    // Gestion de la sélection de machine
    function initializeMachineSelection() {
        const machineSelect = document.getElementById('machineSelect');
        const hoursSection = document.getElementById('hoursSection');
        const maintenanceTableSection = document.getElementById('maintenanceTableSection');
        const machineHoursInput = document.getElementById('machineHours');

        if (machineSelect) {
            machineSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];

                if (this.value) {
                    // Récupérer les données de la machine sélectionnée
                    selectedMachineId = this.value;
                    selectedMachineName = selectedOption.dataset.machineName;
                    selectedMachineCurrentHours = parseInt(selectedOption.dataset.currentHours) || 0;

                    // Afficher la section des heures
                    hoursSection.style.display = 'block';

                    // Pré-remplir le champ des heures avec les heures actuelles de la machine
                    machineHoursInput.value = selectedMachineCurrentHours;

                    // Afficher la section du tableau AVANT de générer le contenu
                    maintenanceTableSection.style.display = 'block';

                    // Récupérer les logs d'entretien existants pour cette machine
                    fetchExistingMaintenanceLogs(selectedMachineId).then(() => {
                        // Générer le tableau de maintenance
                        generateMaintenanceTable();

                        // Localiser automatiquement les heures dans le tableau
                        if (selectedMachineCurrentHours > 0) {
                            locateHours();
                        }
                    });

                    // Ajouter une classe pour indiquer qu'une machine est sélectionnée
                    machineSelect.classList.add('is-valid');
                    machineSelect.classList.remove('is-invalid');
                } else {
                    // Masquer les sections si aucune machine n'est sélectionnée
                    hoursSection.style.display = 'none';
                    maintenanceTableSection.style.display = 'none';

                    // Réinitialiser les variables
                    selectedMachineId = null;
                    selectedMachineName = null;
                    selectedMachineCurrentHours = 0;

                    // Retirer les classes de validation
                    machineSelect.classList.remove('is-valid', 'is-invalid');
                }
            });
        }
    }

    // Créer le modal HTML
    function createMaintenanceModal() {
        const modalHTML = `
            <div id="maintenanceModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Log d'entretien</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="maintenanceHours" class="form-label">Heures de la machine</label>
                                    <input type="number" class="form-control" id="maintenanceHours" min="0" step="1">
                                </div>
                                <div class="col-md-6">
                                    <label for="maintenanceDate" class="form-label">Date d'entretien</label>
                                    <input type="date" class="form-control" id="maintenanceDate" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tâches d'entretien à effectuer</label>
                                <div id="maintenanceTasksContainer" class="border rounded p-3 bg-light">
                                    <!-- Les tâches seront générées dynamiquement ici -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" id="saveMaintenanceLog">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Ajouter le modal au body si il n'existe pas déjà
        if (!document.getElementById('maintenanceModal')) {
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
    }

    function generateMaintenanceTable() {
        console.log('Début de generateMaintenanceTable');

        const tableBody = document.getElementById('maintenanceTableBody');
        if (!tableBody) {
            console.error("L'élément 'maintenanceTableBody' n'a pas été trouvé.");
            return;
        }

        console.log('TableBody trouvé:', tableBody);
        console.log('MaintenanceData:', maintenanceData);

        const schedule = maintenanceData.maintenance_schedule;
        const taskKeys = Object.keys(maintenanceData.task_mapping);

        console.log('Schedule:', schedule);
        console.log('TaskKeys:', taskKeys);

        tableBody.innerHTML = '';

        schedule.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.dataset.hours = row.heures;
            tr.dataset.rowIndex = index;

            const hoursCell = document.createElement('td');
            hoursCell.className = 'hours-cell';
            hoursCell.textContent = row.heures + ' h';
            tr.appendChild(hoursCell);

            taskKeys.forEach((taskKey, taskIndex) => {
                const td = document.createElement('td');
                const isRequired = row[taskKey];

                if (isRequired) {
                    td.innerHTML = '<i class="mdi mdi-asterisk-circle-outline task-icon required" title="Prévu"></i>';
                    td.className = 'task-required';
                } else {
                    td.innerHTML = '<i class="mdi mdi-radiobox-blank task-icon not-required" title="Non prévu"></i>';
                    td.className = 'task-not-required';
                }

                // Ajouter des attributs pour identifier la cellule
                td.dataset.hours = row.heures;
                td.dataset.taskKey = taskKey;
                td.dataset.taskName = maintenanceData.task_mapping[taskKey].name;
                td.dataset.rowIndex = index;
                td.dataset.taskIndex = taskIndex;

                // Ajouter la classe pour le clic
                td.classList.add('clickable-cell');

                tr.appendChild(td);
            });

            // Ajouter la ligne au tableau
            tableBody.appendChild(tr);
        });

        console.log('Tableau généré avec', schedule.length, 'lignes');

        // Marquer les cellules avec les entretiens effectués
        markCompletedMaintenanceCells();

        console.log('Fin de generateMaintenanceTable');
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
        let closestHours = -1;
        let closestIndex = -1;

        // Trouve la valeur la plus élevée dans le planning qui est inférieure ou égale aux heures saisies
        schedule.forEach((item, index) => {
            const scheduleHours = parseInt(item.heures);
            if (scheduleHours <= hours) {
                if (scheduleHours > closestHours) {
                    closestHours = scheduleHours;
                    closestIndex = index;
                }
            }
        });

        if (closestIndex !== -1) {
            closestRow = document.querySelector(`tr[data-row-index="${closestIndex}"]`);
        }

        const difference = closestHours !== -1 ? hours - closestHours : Infinity;

        return {
            row: closestRow,
            difference: difference
        };
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
                    maintenanceData.task_mapping[taskKey].name
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

    function showMaintenanceModal(cell) {
        // Vérifier si la cellule est déjà complétée
        if (cell.classList.contains('completed-maintenance')) {
            const maintenanceDate = cell.dataset.maintenanceDate;
            const message = maintenanceDate
                ? `Cette tâche d'entretien a déjà été effectuée le ${maintenanceDate}`
                : 'Cette tâche d\'entretien a déjà été effectuée';

            showErrorMessage(message);
            return;
        }

        const cellHours = cell.dataset.hours;
        const taskKey = cell.dataset.taskKey;
        const taskName = cell.dataset.taskName;

        // Vérifier si des heures sont saisies dans la zone de texte
        const hoursInput = document.getElementById('machineHours');
        const enteredHours = hoursInput ? parseInt(hoursInput.value) : null;
        const hoursValue = enteredHours && !isNaN(enteredHours) ? enteredHours : cellHours;

        // Remplir les champs du modal
        const maintenanceHoursField = document.getElementById('maintenanceHours');

        if (enteredHours && !isNaN(enteredHours)) {
            maintenanceHoursField.value = enteredHours;
            maintenanceHoursField.readOnly = true;
            maintenanceHoursField.style.backgroundColor = '#f8f9fa';
            maintenanceHoursField.style.color = '#6c757d';
        } else {
            maintenanceHoursField.value = cellHours;
            maintenanceHoursField.readOnly = false;
            maintenanceHoursField.style.backgroundColor = '';
            maintenanceHoursField.style.color = '';
        }

        // Définir la date d'aujourd'hui par défaut
        document.getElementById('maintenanceDate').value = new Date().toISOString().split('T')[0];

        // Récupérer les tâches déjà faites pour cette machine et cette échéance
        let alreadyDoneTaskKeys = [];
        if (existingMaintenanceLogs && selectedMachineId) {
            alreadyDoneTaskKeys = existingMaintenanceLogs
                .filter(log => log.parcMachineId == selectedMachineId && log.hours == hoursValue)
                .map(log => {
                    // Chercher la clé correspondant à l'activité
                    const key = Object.keys(maintenanceData.task_mapping).find(k => maintenanceData.task_mapping[k].name === log.activity);
                    return key || log.activity;
                });
        }
        // Ajouter la tâche cliquée si elle n'est pas déjà dedans
        if (!alreadyDoneTaskKeys.includes(taskKey)) {
            alreadyDoneTaskKeys.push(taskKey);
        }

        // Générer les boutons toggle pour toutes les tâches sélectionnées
        generateTaskCheckboxes(alreadyDoneTaskKeys);

        // Supprimer les classes d'erreur
        clearValidationErrors();

        // Stocker les données actuelles
        currentModalData = {
            hours: hoursValue,
            taskKey: taskKey,
            taskName: taskName,
            rowIndex: cell.dataset.rowIndex,
            taskIndex: cell.dataset.taskIndex
        };

        // Afficher le modal
        const modal = new bootstrap.Modal(document.getElementById('maintenanceModal'));
        modal.show();
    }

    function generateTaskCheckboxes(selectedTaskKeys) {
        const container = document.getElementById('maintenanceTasksContainer');
        const taskKeys = Object.keys(maintenanceData.task_mapping);

        container.innerHTML = '';

        // Créer un groupe de boutons toggle avec bordure visible
        const btnGroup = document.createElement('div');
        btnGroup.className = 'btn-group w-100 flex-wrap border border-2 rounded border-primary p-1 mb-2';
        btnGroup.setAttribute('role', 'group');
        btnGroup.setAttribute('aria-label', "Tâches d'entretien");

        taskKeys.forEach(taskKey => {
            const taskName = maintenanceData.task_mapping[taskKey].name;
            const pdfLink = maintenanceData.task_mapping[taskKey].pdf_link;
            const isSelected = Array.isArray(selectedTaskKeys) && selectedTaskKeys.includes(taskKey);

            // Bouton de tâche
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn border-0 pe-1';
            button.setAttribute('data-task-key', taskKey);
            button.setAttribute('data-task-name', taskName);
            button.textContent = taskName;

            // Bouton PDF
            const pdfButton = document.createElement('a');
            pdfButton.href = pdfLink;
            pdfButton.target = '_blank';
            pdfButton.className = 'btn text-danger ps-1';
            pdfButton.title = 'Voir la documentation PDF';
            pdfButton.innerHTML = '<i class="mdi mdi-file-pdf-box"></i>';

            // Grouper les deux boutons dans un sous-groupe
            const subGroup = document.createElement('div');
            subGroup.className = 'btn-group me-2 mb-2';
            subGroup.setAttribute('role', 'group');
            subGroup.appendChild(button);
            subGroup.appendChild(pdfButton);

            // Appliquer la couleur si sélectionné
            if (isSelected) {
                subGroup.classList.add('bg-primary', 'text-white');
                button.classList.add('text-white', 'active');
            }
            button.addEventListener('click', function () {
                subGroup.classList.toggle('bg-primary');
                subGroup.classList.toggle('text-white');
                button.classList.toggle('text-white');
                button.classList.toggle('active');
            });

            btnGroup.appendChild(subGroup);
        });

        container.appendChild(btnGroup);
    }

    function getSelectedTasks() {
        const selectedTasks = [];
        const buttons = document.querySelectorAll('#maintenanceTasksContainer .btn.active');
        buttons.forEach(button => {
            selectedTasks.push({
                key: button.getAttribute('data-task-key'),
                name: button.getAttribute('data-task-name')
            });
        });
        return selectedTasks;
    }

    function clearValidationErrors() {
        const formElements = document.querySelectorAll('#maintenanceModal .form-control, #maintenanceModal .form-select');
        formElements.forEach(element => {
            element.classList.remove('is-invalid');
        });

        const feedbackElements = document.querySelectorAll('#maintenanceModal .invalid-feedback');
        feedbackElements.forEach(element => {
            element.remove();
        });

        // Réinitialiser le style du container des tâches
        const container = document.getElementById('maintenanceTasksContainer');
        if (container) {
            container.style.borderColor = '';
        }
    }

    function showValidationError(elementId, message) {
        const element = document.getElementById(elementId);
        element.classList.add('is-invalid');

        // Supprimer l'ancien message d'erreur s'il existe
        const existingFeedback = element.parentNode.querySelector('.invalid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }

        // Ajouter le nouveau message d'erreur
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        element.parentNode.appendChild(feedback);
    }

    function validateForm() {
        let isValid = true;

        // Validation de la date
        const date = document.getElementById('maintenanceDate').value;
        if (!date) {
            showValidationError('maintenanceDate', 'La date d\'entretien est obligatoire');
            isValid = false;
        } else {
            const selectedDate = new Date(date);
            const today = new Date();
            if (selectedDate > today) {
                showValidationError('maintenanceDate', 'La date ne peut pas être dans le futur');
                isValid = false;
            }
        }

        // Validation des tâches sélectionnées
        const selectedTasks = getSelectedTasks();
        if (selectedTasks.length === 0) {
            showValidationError('maintenanceTasksContainer', 'Veuillez sélectionner au moins une tâche d\'entretien');
            isValid = false;
        }

        // Si le formulaire est valide, nettoyer les erreurs
        if (isValid) {
            clearValidationErrors();
        }

        return isValid;
    }

    function saveMaintenanceLog() {
        if (!currentModalData) {
            console.error('Aucune donnée de modal disponible');
            return;
        }
        if (!validateForm()) {
            return;
        }
        if (!selectedMachineId) {
            showErrorMessage('Veuillez sélectionner une machine avant d\'enregistrer le log d\'entretien');
            return;
        }
        showLoadingBackdrop();
        const selectedTasks = getSelectedTasks();
        const formData = {
            hours: document.getElementById('maintenanceHours').value,
            tasks: selectedTasks,
            date: document.getElementById('maintenanceDate').value,
            taskKey: currentModalData.taskKey,
            rowIndex: currentModalData.rowIndex,
            taskIndex: currentModalData.taskIndex,
            machineId: selectedMachineId,
            machineName: selectedMachineName
        };
        const modalRowIndex = currentModalData.rowIndex;
        const modalTaskIndex = currentModalData.taskIndex;
        fetch('/dashboard/entretiens/maintenance-table/log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
            .then(async response => {
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('maintenanceModal'));
                        modal.hide();
                        clearValidationErrors();
                        currentModalData = null;
                        showSuccessMessage(data.message);
                        await fetchExistingMaintenanceLogs(selectedMachineId);
                        markCompletedMaintenanceCells();
                        saveToLocalStorage(formData, data.logIds);
                    } else {
                        showErrorMessage(data.message || 'Erreur lors de la sauvegarde');
                    }
                } else {
                    console.error('Erreur lors de la sauvegarde:', response.statusText);
                    showErrorMessage('Erreur de connexion lors de la sauvegarde du log d\'entretien');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la sauvegarde:', error);
                showErrorMessage('Erreur de connexion lors de la sauvegarde du log d\'entretien');
            })
            .finally(() => {
                hideLoadingBackdrop();
            });
    }

    function saveToLocalStorage(formData, logIds) {
        try {
            const maintenanceLogs = JSON.parse(localStorage.getItem('maintenanceLogs') || '[]');
            const newLog = {
                ...formData,
                id: Date.now(),
                createdAt: new Date().toISOString(),
                serverLogIds: logIds // Stocker les IDs des logs créés côté serveur
            };

            maintenanceLogs.push(newLog);
            localStorage.setItem('maintenanceLogs', JSON.stringify(maintenanceLogs));
        } catch (error) {
            console.warn('Impossible de sauvegarder en localStorage:', error);
        }
    }

    function showSuccessMessage(message) {
        // Créer une notification de succès
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="mdi mdi-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    function showErrorMessage(message) {
        // Créer une notification d'erreur
        const notification = document.createElement('div');
        notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="mdi mdi-alert-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    function updateCellVisualFeedback(rowIndex, taskIndex, status) {
        // Optionnel : mettre à jour l'apparence de la cellule pour indiquer que la tâche a été effectuée
        const cell = document.querySelector(`tr[data-row-index="${rowIndex}"] td:nth-child(${taskIndex + 2})`);
        if (cell && status === 'completed') {
            cell.style.opacity = '0.6';
            cell.title = 'Tâche effectuée';
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

        // Ajouter les écouteurs d'événements pour les cellules cliquables
        document.addEventListener('click', function (e) {
            if (e.target.closest('.clickable-cell')) {
                const cell = e.target.closest('.clickable-cell');
                showMaintenanceModal(cell);
            }
        });

        // Écouteur pour le bouton de sauvegarde du modal
        document.addEventListener('click', function (e) {
            if (e.target.id === 'saveMaintenanceLog') {
                saveMaintenanceLog();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', async function () {
            createMaintenanceModal();
            initializeEventListeners();
            initializeMachineSelection();
        });
    } else {
        createMaintenanceModal();
        initializeEventListeners();
        initializeMachineSelection();
    }
})(); 