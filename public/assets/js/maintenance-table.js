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

    // Variables globales
    let currentHighlightedRow = null;
    let debounceTimer = null;
    let selectedMachineId = null;
    let selectedMachineName = null;
    let currentModalData = null;
    let existingMaintenanceLogs = []; // Nouvelle variable pour stocker les logs existants

    // Fonctions pour le backdrop de chargement
    function showLoadingBackdrop() {
        // Créer le backdrop s'il n'existe pas
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

        // Afficher le backdrop
        document.getElementById('loadingBackdrop').style.display = 'flex';
    }

    function hideLoadingBackdrop() {
        const backdrop = document.getElementById('loadingBackdrop');
        if (backdrop) {
            backdrop.style.display = 'none';
        }
    }

    // Fonction pour récupérer les logs d'entretien existants
    async function fetchExistingMaintenanceLogs(machineId) {
        try {
            // Afficher le backdrop de chargement
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
                    // Stocker les logs dans une variable globale
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
            // Masquer le backdrop de chargement
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
                    maintenanceData.task_mapping[key] === log.activity
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
                td.dataset.taskName = maintenanceData.task_mapping[taskKey];
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

        // Remplir les champs du modal
        const maintenanceHoursField = document.getElementById('maintenanceHours');

        if (enteredHours && !isNaN(enteredHours)) {
            // Si des heures sont saisies, utiliser ces heures et rendre le champ en lecture seule
            maintenanceHoursField.value = enteredHours;
            maintenanceHoursField.readOnly = true;
            maintenanceHoursField.style.backgroundColor = '#f8f9fa';
            maintenanceHoursField.style.color = '#6c757d';
        } else {
            // Sinon, utiliser les heures de la cellule et rendre le champ modifiable
            maintenanceHoursField.value = cellHours;
            maintenanceHoursField.readOnly = false;
            maintenanceHoursField.style.backgroundColor = '';
            maintenanceHoursField.style.color = '';
        }

        // Définir la date d'aujourd'hui par défaut
        document.getElementById('maintenanceDate').value = new Date().toISOString().split('T')[0];

        // Générer les checkboxes pour toutes les tâches
        generateTaskCheckboxes(taskKey);

        // Supprimer les classes d'erreur
        clearValidationErrors();

        // Stocker les données actuelles
        currentModalData = {
            hours: enteredHours && !isNaN(enteredHours) ? enteredHours : cellHours,
            taskKey: taskKey,
            taskName: taskName,
            rowIndex: cell.dataset.rowIndex,
            taskIndex: cell.dataset.taskIndex
        };

        // Afficher le modal
        const modal = new bootstrap.Modal(document.getElementById('maintenanceModal'));
        modal.show();
    }

    function generateTaskCheckboxes(selectedTaskKey) {
        const container = document.getElementById('maintenanceTasksContainer');
        const taskKeys = Object.keys(maintenanceData.task_mapping);

        container.innerHTML = '';

        taskKeys.forEach(taskKey => {
            const taskName = maintenanceData.task_mapping[taskKey];
            const isSelected = taskKey === selectedTaskKey;

            const taskDiv = document.createElement('div');
            taskDiv.className = 'form-check mb-2';
            taskDiv.innerHTML = `
                <input class="form-check-input" type="checkbox" 
                       id="task_${taskKey}" 
                       value="${taskKey}" 
                       data-task-name="${taskName}"
                       ${isSelected ? 'checked' : ''}>
                <label class="form-check-label" for="task_${taskKey}">
                    ${taskName}
                </label>
            `;

            container.appendChild(taskDiv);
        });
    }

    function getSelectedTasks() {
        const selectedTasks = [];
        const checkboxes = document.querySelectorAll('#maintenanceTasksContainer input[type="checkbox"]:checked');

        checkboxes.forEach(checkbox => {
            selectedTasks.push({
                key: checkbox.value,
                name: checkbox.dataset.taskName
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

        // Validation du formulaire
        if (!validateForm()) {
            return;
        }

        // Vérifier qu'une machine est sélectionnée
        if (!selectedMachineId) {
            showErrorMessage('Veuillez sélectionner une machine avant d\'enregistrer le log d\'entretien');
            return;
        }

        // Afficher le backdrop de chargement
        showLoadingBackdrop();

        // Récupérer les valeurs du formulaire
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

        // Sauvegarder les données nécessaires avant de réinitialiser
        const modalRowIndex = currentModalData.rowIndex;
        const modalTaskIndex = currentModalData.taskIndex;

        // Envoyer les données à l'API Symfony
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
                        // Fermer le modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('maintenanceModal'));
                        modal.hide();

                        // Réinitialiser le formulaire
                        clearValidationErrors();
                        currentModalData = null;

                        // Afficher un message de confirmation
                        showSuccessMessage(data.message);

                        // Mettre à jour les logs existants et re-marquer les cellules
                        await fetchExistingMaintenanceLogs(selectedMachineId);
                        markCompletedMaintenanceCells();

                        // Optionnel : sauvegarder aussi en localStorage pour la persistance côté client
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
                // Masquer le backdrop de chargement
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