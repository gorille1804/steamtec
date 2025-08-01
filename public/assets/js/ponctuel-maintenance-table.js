// Script d'entretien ponctuel

(function () {
    // Chargement dynamique des données de maintenance depuis le JSON
    let maintenanceData = null;

    // Variables globales
    let currentHighlightedRow = null;
    let debounceTimer = null;
    let selectedMachineId = null;
    let selectedMachineName = null;
    let currentModalData = null;
    let existingMaintenanceLogs = [];

    // Fonctions pour le backdrop de chargement
    function showLoadingBackdrop() {
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
            markCompletedMaintenanceCells();
            hideLoadingBackdrop();
        }
    }

    // Fonction pour marquer les cellules avec les entretiens effectués
    function markCompletedMaintenanceCells() {
        if (!selectedMachineId || existingMaintenanceLogs.length === 0) {
            return;
        }

        const machineLogs = existingMaintenanceLogs.filter(log =>
            log.parcMachineId === selectedMachineId
        );

        machineLogs.forEach(log => {
            let cell = null;
            let years = null;
            // Si le log est basé sur les années, il faut la valeur d'années
            if (log.isYear) {
                years = log.hours;
                // Si years n'est pas fourni, le déduire à partir du planning
                if (!years) {
                    const schedule = maintenanceData.maintenance_schedule.find(row => parseInt(row.heures) === parseInt(log.hours));
                    if (schedule) {
                        years = schedule.annees;
                    }
                }
                if (years) {
                    cell = document.querySelector(
                        `td[data-annees="${years}"][data-task-key="${log.activity}"]`
                    );
                }
            } else {
                // Logique actuelle par heures
                cell = document.querySelector(
                    `td[data-hours="${log.hours}"][data-task-key="${log.activity}"]`
                );
            }

            if (!cell) {
                const taskKey = Object.keys(maintenanceData.task_mapping).find(key =>
                    maintenanceData.task_mapping[key].name === log.activity
                );
                if (log.isYear && years && taskKey) {
                    cell = document.querySelector(
                        `td[data-annees="${years}"][data-task-key="${taskKey}"]`
                    );
                } else if (!log.isYear && taskKey) {
                    cell = document.querySelector(
                        `td[data-hours="${log.hours}"][data-task-key="${taskKey}"]`
                    );
                }
            }

            // Si toujours pas trouvé, placer dans la ligne dont la valeur d'heures est la plus grande mais <= log.hours
            if (!cell) {
                const schedule = maintenanceData.maintenance_schedule;
                let closestRow = null;
                let closestHours = -1;
                schedule.forEach(row => {
                    const rowHours = parseInt(row.heures);
                    if (rowHours <= parseInt(log.hours) && rowHours > closestHours) {
                        closestHours = rowHours;
                        closestRow = row;
                    }
                });
                if (closestRow) {
                    // Chercher la cellule dans cette ligne, même si la tâche n'est pas prévue
                    cell = document.querySelector(
                        `tr[data-hours="${closestRow.heures}"] td[data-task-key="${log.activity}"]`
                    );
                    if (!cell) {
                        // Essayer avec le nom de la tâche
                        const taskKey = Object.keys(maintenanceData.task_mapping).find(key => maintenanceData.task_mapping[key].name === log.activity);
                        if (taskKey) {
                            cell = document.querySelector(
                                `tr[data-hours="${closestRow.heures}"] td[data-task-key="${taskKey}"]`
                            );
                        }
                    }
                }
            }

            if (!cell) {
                cell = document.querySelector(
                    `td[data-task-key="${log.activity}"]`
                );
            }

            if (cell) {
                cell.classList.add('completed-maintenance');
                cell.classList.remove('clickable-cell');

                const date = new Date(log.date);
                const formattedDate = date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                cell.innerHTML = `<span class="maintenance-date">${formattedDate}</span>`;
                cell.title = `Entretien effectué le ${formattedDate}`;
                cell.dataset.maintenanceDate = log.date;
                cell.dataset.maintenanceLogId = log.id;
                cell.dataset.maintenanceLogHours = log.hours;
            }
        });
    }

    // Fonction pour initialiser la sélection de machine
    function initializeMachineSelection() {
        const machineSelect = document.getElementById('machineSelect');
        const hoursSection = document.getElementById('hoursSection');
        const maintenanceTableSection = document.getElementById('maintenanceTableSection');

        if (machineSelect) {
            machineSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];

                if (this.value) {
                    selectedMachineId = this.value;
                    selectedMachineName = selectedOption.getAttribute('data-machine-name');
                    const currentHours = selectedOption.getAttribute('data-current-hours');

                    hoursSection.style.display = 'block';

                    const machineHoursInput = document.getElementById('machineHours');
                    if (machineHoursInput) {
                        machineHoursInput.value = currentHours;
                        machineHoursInput.dispatchEvent(new Event('input'));
                    }

                    fetchExistingMaintenanceLogs(selectedMachineId);
                    generateMaintenanceTable();
                    maintenanceTableSection.style.display = 'block';
                } else {
                    hoursSection.style.display = 'none';
                    maintenanceTableSection.style.display = 'none';
                    selectedMachineId = null;
                    selectedMachineName = null;
                }
            });
        }
    }

    // Fonction pour créer le modal de maintenance
    function createMaintenanceModal() {
        const existingModal = document.getElementById('maintenanceModal');
        if (existingModal) {
            existingModal.remove();
        }

        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'maintenanceModal';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-labelledby', 'maintenanceModalLabel');
        modal.setAttribute('aria-hidden', 'true');

        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="maintenanceModalLabel">
                            <i class="mdi mdi-wrench text-primary"></i>
                            Enregistrer un entretien ponctuel
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="maintenanceForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="maintenanceDate" class="form-label">
                                            <i class="mdi mdi-calendar text-primary"></i>
                                            Date de l'entretien
                                        </label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="maintenanceDate" 
                                               name="maintenanceDate" 
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="maintenanceHours" class="form-label">
                                            <i class="mdi mdi-clock-outline text-primary"></i>
                                            Heures de la machine
                                        </label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="maintenanceHours" 
                                               name="maintenanceHours" 
                                               required 
                                               readonly>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="maintenanceIsYear" name="maintenanceIsYear">
                                            <label class="form-check-label" for="maintenanceIsYear">
                                                <i class="mdi mdi-calendar-range text-primary"></i> Année
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-checkbox-multiple-marked-outline text-primary"></i>
                                    Tâches effectuées
                                </label>
                                <div id="taskCheckboxes" class="border rounded p-3">
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="mdi mdi-information-outline"></i>
                                <strong>Information :</strong> Seules les tâches cochées seront enregistrées comme effectuées.
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="mdi mdi-close"></i>
                            Annuler
                        </button>
                        <button type="button" class="btn btn-primary" id="saveMaintenanceBtn">
                            <i class="mdi mdi-content-save"></i>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    }

    // Fonction pour générer le tableau de maintenance
    function generateMaintenanceTable() {
        const tableBody = document.getElementById('maintenanceTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        maintenanceData.maintenance_schedule.forEach((schedule, index) => {
            const row = document.createElement('tr');
            row.setAttribute('data-hours', schedule.heures);
            row.setAttribute('data-annees', schedule.annees);

            // Cellule des heures
            const hoursCell = document.createElement('td');
            hoursCell.className = 'text-center fw-bold';
            hoursCell.textContent = schedule.heures + ' h';
            row.appendChild(hoursCell);

            // Cellule des années
            const yearsCell = document.createElement('td');
            yearsCell.className = 'text-center fw-bold';
            yearsCell.textContent = schedule.annees + ' an' + (schedule.annees > 1 ? 's' : '');
            row.appendChild(yearsCell);

            // Cellules pour chaque tâche
            Object.keys(maintenanceData.task_mapping).forEach(taskKey => {
                const cell = document.createElement('td');
                cell.className = 'text-center clickable-cell';
                cell.setAttribute('data-hours', schedule.heures);
                cell.setAttribute('data-task-key', taskKey);
                cell.setAttribute('data-task-name', maintenanceData.task_mapping[taskKey].name);
                cell.setAttribute('data-annees', schedule.annees);

                if (schedule[taskKey]) {
                    cell.innerHTML = '<i class="mdi mdi-asterisk-circle-outline text-success"></i>';
                    cell.title = `Cliquez pour enregistrer: ${maintenanceData.task_mapping[taskKey].name}`;
                } else {
                    cell.innerHTML = '<i class="mdi mdi-radiobox-blank text-muted"></i>';
                    cell.title = 'Aucune tâche requise';
                }
                cell.addEventListener('click', function () {
                    showMaintenanceModal(this);
                });

                row.appendChild(cell);
            });

            tableBody.appendChild(row);
        });

        setTimeout(() => {
            markCompletedMaintenanceCells();
        }, 100);
    }

    // Fonction pour effacer la surbrillance
    function clearHighlight() {
        if (currentHighlightedRow) {
            currentHighlightedRow.classList.remove('highlighted-row');
            currentHighlightedRow = null;
        }
    }

    // Fonction pour surligner une ligne
    function highlightRow(row) {
        clearHighlight();
        if (row) {
            row.classList.add('highlighted-row');
            currentHighlightedRow = row;
        }
    }

    // Fonction pour trouver la ligne la plus proche
    function findClosestRow(hours) {
        const rows = document.querySelectorAll('#maintenanceTableBody tr');
        let closestRow = null;
        let minDifference = Infinity;

        rows.forEach(row => {
            const rowHours = parseInt(row.getAttribute('data-hours'));
            const difference = Math.abs(rowHours - hours);

            if (difference < minDifference) {
                minDifference = difference;
                closestRow = row;
            }
        });

        return { row: closestRow, difference: minDifference };
    }

    // Fonction pour mettre à jour les informations des heures
    function updateHoursInfo(hours, closestRow, difference) {
        const hoursInfo = document.getElementById('hoursInfo');
        const hoursInfoText = document.getElementById('hoursInfoText');

        if (closestRow) {
            const closestHours = closestRow.getAttribute('data-hours');
            const closestYears = closestRow.getAttribute('data-annees');

            if (difference === 0) {
                hoursInfo.className = 'alert alert-success';
                hoursInfoText.innerHTML = `
                    <strong>Parfait !</strong> Vous êtes exactement à ${closestHours} heures (${closestYears} an(s)). 
                    C'est le moment d'effectuer les tâches d'entretien ponctuel indiquées dans le tableau.
                `;
            } else {
                hoursInfo.className = 'alert alert-info';
                const direction = hours > closestHours ? 'après' : 'avant';
                hoursInfoText.innerHTML = `
                    <strong>Information :</strong> Vous êtes à ${difference} heures ${direction} l'entretien ponctuel de ${closestHours} heures (${closestYears} an(s)). 
                    <br><small>Cliquez sur les icônes d'outils dans le tableau pour enregistrer les tâches effectuées.</small>
                `;
            }
            hoursInfo.style.display = 'block';
        } else {
            hoursInfo.style.display = 'none';
        }
    }

    // MAJ : gestion du mode via toggle buttons radio
    function updateInputMode() {
        const modeHeures = document.getElementById('modeHeures');
        const label = document.getElementById('machineHoursLabel');
        const help = document.getElementById('machineHoursHelp');
        const input = document.getElementById('machineHours');
        if (!modeHeures || !label || !help || !input) return;

        if (modeHeures.checked) {
            // Mode Heures
            label.innerHTML = '<i class="mdi mdi-clock-outline text-primary"></i> Nombre d\'heures de la machine';
            input.placeholder = 'Ex: 1250';
            input.step = 50;
            input.min = 0;
            input.max = 5000;
            help.textContent = 'Entrez le nombre d\'heures pour voir la ligne correspondante dans le tableau';
        } else {
            // Mode Années
            label.innerHTML = '<i class="mdi mdi-calendar-range text-primary"></i> Nombre d\'années de la machine';
            input.placeholder = 'Ex: 2';
            input.step = 1;
            input.min = 0;
            input.max = 10;
            help.textContent = 'Entrez le nombre d\'années pour voir la ligne correspondante dans le tableau';
        }
        input.value = '';
        clearHighlight();
        document.getElementById('hoursInfo').style.display = 'none';
    }

    // Ajout : recherche par années
    function findClosestRowByYears(years) {
        const rows = document.querySelectorAll('#maintenanceTableBody tr');
        let closestRow = null;
        let minDifference = Infinity;

        rows.forEach(row => {
            const rowYears = parseInt(row.getAttribute('data-annees'));
            const difference = Math.abs(rowYears - years);
            if (difference < minDifference) {
                minDifference = difference;
                closestRow = row;
            }
        });
        return { row: closestRow, difference: minDifference };
    }

    // MAJ : locateHours utilise le bon mode
    function locateHours() {
        const hoursInput = document.getElementById('machineHours');
        const modeHeures = document.getElementById('modeHeures');
        if (!hoursInput || !hoursInput.value) return;

        if (modeHeures && modeHeures.checked) {
        // Mode Heures
            const hours = parseInt(hoursInput.value);
            if (isNaN(hours) || hours < 0) return;
            const { row: closestRow, difference } = findClosestRow(hours);
            if (closestRow) {
                highlightRow(closestRow);
                updateHoursInfo(hours, closestRow, difference);
                closestRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            // Mode Années
            const years = parseInt(hoursInput.value);
            if (isNaN(years) || years < 0) return;
            const { row: closestRow, difference } = findClosestRowByYears(years);
            if (closestRow) {
                highlightRow(closestRow);
                updateYearsInfo(years, closestRow, difference);
                closestRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    // Ajout : message info pour les années
    function updateYearsInfo(years, closestRow, difference) {
        const hoursInfo = document.getElementById('hoursInfo');
        const hoursInfoText = document.getElementById('hoursInfoText');
        if (closestRow) {
            const closestHours = closestRow.getAttribute('data-hours');
            const closestYears = closestRow.getAttribute('data-annees');
            if (difference === 0) {
                hoursInfo.className = 'alert alert-success';
                hoursInfoText.innerHTML = `
                    <strong>Parfait !</strong> Vous êtes exactement à ${closestYears} an(s) (${closestHours} heures).<br>
                    C'est le moment d'effectuer les tâches d'entretien ponctuel indiquées dans le tableau.`;
            } else {
                hoursInfo.className = 'alert alert-info';
                const direction = years > closestYears ? 'après' : 'avant';
                hoursInfoText.innerHTML = `
                    <strong>Information :</strong> Vous êtes à ${difference} an(s) ${direction} l'entretien ponctuel de ${closestYears} an(s) (${closestHours} heures).<br>
                    <small>Cliquez sur les icônes d'outils dans le tableau pour enregistrer les tâches effectuées.</small>
                `;
            }
            hoursInfo.style.display = 'block';
        } else {
            hoursInfo.style.display = 'none';
        }
    }

    // Fonction pour afficher le modal de maintenance
    function showMaintenanceModal(cell) {
        if (!selectedMachineId) {
            showErrorMessage('Veuillez sélectionner une machine d\'abord.');
            return;
        }

        createMaintenanceModal();

        const hours = cell.getAttribute('data-hours');
        const taskKey = cell.getAttribute('data-task-key');
        const taskName = cell.getAttribute('data-task-name');
        const annees = cell.getAttribute('data-annees');
        const maintenanceDate = cell.dataset.maintenanceDate;
        const maintenanceLogId = cell.dataset.maintenanceLogId;
        const maintenanceLogHours = cell.dataset.maintenanceLogHours;

        let selectedTaskKeys = [];
        let forceSingleTask = false;
        let forceHours = hours;
        let forceDate = null;

        // Si la cellule contient une date (log existant), n'afficher que la tâche du log
        if (maintenanceDate && maintenanceLogId) {
            selectedTaskKeys = [taskKey];
            forceSingleTask = true;
            forceHours = maintenanceLogHours || hours;
            forceDate = maintenanceDate.split('T')[0] || maintenanceDate;
        } else {
            // Récupérer les tâches déjà faites pour cette machine et cette échéance
            if (existingMaintenanceLogs && selectedMachineId) {
                selectedTaskKeys = existingMaintenanceLogs
                    .filter(log => log.parcMachineId == selectedMachineId && log.hours == hours)
                    .map(log => {
                        const key = Object.keys(maintenanceData.task_mapping).find(k => maintenanceData.task_mapping[k].name === log.activity);
                        return key || log.activity;
                    });
            }
            if (!selectedTaskKeys.includes(taskKey)) {
                selectedTaskKeys.push(taskKey);
            }
        }

        // Générer les boutons toggle pour toutes les tâches sélectionnées
        generateTaskCheckboxes(selectedTaskKeys, forceSingleTask);

        // Pré-remplir les champs
        const dateInput = document.getElementById('maintenanceDate');
        const hoursInput = document.getElementById('maintenanceHours');
        const isYearCheckbox = document.getElementById('maintenanceIsYear');
        if (dateInput) {
            dateInput.value = forceDate || new Date().toISOString().split('T')[0];
        }
        if (hoursInput) {
            hoursInput.value = forceHours;
            hoursInput.readOnly = false;
            hoursInput.style.backgroundColor = '';
            hoursInput.style.color = '';
        }
        if (isYearCheckbox) {
            isYearCheckbox.checked = !hoursInput.readOnly; // Synchronise la case à cocher avec le mode
        }

        // Supprimer les classes d'erreur
        clearValidationErrors();

        // Stocker les données actuelles
        currentModalData = {
            hours: forceHours,
            taskKey: taskKey,
            taskName: taskName,
            rowIndex: cell.dataset.rowIndex,
            taskIndex: cell.dataset.taskIndex,
            logId: maintenanceLogId || null
        };

        // Afficher le modal
        const modal = new bootstrap.Modal(document.getElementById('maintenanceModal'));
        modal.show();
        document.getElementById('saveMaintenanceBtn').addEventListener('click', saveMaintenanceLog);

        // Synchronisation dans showMaintenanceModal
        const modeHeures = document.getElementById('modeHeures');
        if (isYearCheckbox && modeHeures) {
            isYearCheckbox.checked = !modeHeures.checked;
        }
    }

    // Fonction pour générer les boutons toggle des tâches
    function generateTaskCheckboxes(selectedTaskKeys, forceSingleTask) {
        const container = document.getElementById('taskCheckboxes');
        if (!container) return;

        container.innerHTML = '';

        // Créer un groupe de boutons toggle avec bordure visible
        const btnGroup = document.createElement('div');
        btnGroup.className = 'btn-group w-100 flex-wrap border border-2 rounded border-primary p-1 mb-2';
        btnGroup.setAttribute('role', 'group');
        btnGroup.setAttribute('aria-label', "Tâches effectuées");

        Object.keys(maintenanceData.task_mapping).forEach(taskKey => {
            // Si on force l'affichage d'une seule tâche (édition d'un log existant), n'afficher que celle-ci
            if (forceSingleTask && !selectedTaskKeys.includes(taskKey)) return;

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

            // Grouper les deux boutons dans un sous-groupe
            const subGroup = document.createElement('div');
            subGroup.className = 'btn-group me-2 mb-2';
            subGroup.setAttribute('role', 'group');
            subGroup.appendChild(button);

            // Bouton PDF uniquement si pdfLink existe
            if (pdfLink) {
                const pdfButton = document.createElement('a');
                pdfButton.href = pdfLink;
                pdfButton.target = '_blank';
                pdfButton.className = 'btn text-danger ps-1';
                pdfButton.title = 'Voir la documentation PDF';
                pdfButton.innerHTML = '<i class="mdi mdi-file-pdf-box"></i>';
                subGroup.appendChild(pdfButton);
            }

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

    // Fonction pour obtenir les tâches sélectionnées via boutons toggle
    function getSelectedTasks() {
        const selectedTasks = [];
        const buttons = document.querySelectorAll('#taskCheckboxes .btn.active');
        buttons.forEach(button => {
            selectedTasks.push({
                key: button.getAttribute('data-task-key'),
                name: button.getAttribute('data-task-name')
            });
        });
        return selectedTasks;
    }

    // Fonction pour effacer les erreurs de validation
    function clearValidationErrors() {
        const errorElements = document.querySelectorAll('.is-invalid');
        errorElements.forEach(element => {
            element.classList.remove('is-invalid');
        });

        const errorMessages = document.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(element => {
            element.remove();
        });
    }

    // Fonction pour afficher une erreur de validation
    function showValidationError(elementId, message) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList.add('is-invalid');

            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;

            element.parentNode.appendChild(errorDiv);
        }
    }

    // Fonction pour valider le formulaire
    function validateForm() {
        clearValidationErrors();
        let isValid = true;

        const dateInput = document.getElementById('maintenanceDate');
        const selectedTasks = getSelectedTasks();

        if (!dateInput || !dateInput.value) {
            showValidationError('maintenanceDate', 'La date est requise');
            isValid = false;
        }

        if (selectedTasks.length === 0) {
            const container = document.getElementById('taskCheckboxes');
            if (container) {
                container.classList.add('is-invalid');

                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = 'Veuillez sélectionner au moins une tâche';

                container.appendChild(errorDiv);
            }
            isValid = false;
        }

        return isValid;
    }

    // Fonction pour sauvegarder le log de maintenance
    async function saveMaintenanceLog() {
        if (!validateForm()) {
            return;
        }

        const selectedTasks = getSelectedTasks();
        const dateInput = document.getElementById('maintenanceDate');
        const hoursInput = document.getElementById('maintenanceHours');
        const isYearCheckbox = document.getElementById('maintenanceIsYear');

        const formData = {
            machineId: selectedMachineId,
            date: dateInput.value,
            hours: hoursInput.value,
            tasks: selectedTasks,
            isYear: isYearCheckbox && isYearCheckbox.checked ? 1 : 0
        };

        try {
            showLoadingBackdrop();

            const response = await fetch('/dashboard/entretiens/maintenance-table/log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('maintenanceModal'));
                modal.hide();

                showSuccessMessage(data.message);

                await fetchExistingMaintenanceLogs(selectedMachineId);
                markCompletedMaintenanceCells();
                saveToLocalStorage(formData, data.logIds);

            } else {
                showErrorMessage(data.message || 'Erreur lors de l\'enregistrement');
            }

        } catch (error) {
            console.error('Erreur lors de la sauvegarde:', error);
            showErrorMessage('Erreur de connexion lors de l\'enregistrement');
        } finally {
            hideLoadingBackdrop();
        }
    }

    // Fonction pour sauvegarder dans le localStorage
    function saveToLocalStorage(formData, logIds) {
        const storageKey = `maintenance_logs_${selectedMachineId}`;
        const existingData = JSON.parse(localStorage.getItem(storageKey) || '[]');

        const newEntry = {
            ...formData,
            logIds: logIds,
            timestamp: new Date().toISOString()
        };

        existingData.push(newEntry);
        localStorage.setItem(storageKey, JSON.stringify(existingData));
    }

    // Fonction pour afficher un message de succès
    function showSuccessMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="mdi mdi-check-circle"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const contentContainer = document.querySelector('.card-body');
        if (contentContainer) {
            contentContainer.insertBefore(alertDiv, contentContainer.firstChild);
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Fonction pour afficher un message d'erreur
    function showErrorMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="mdi mdi-alert-circle"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const contentContainer = document.querySelector('.card-body');
        if (contentContainer) {
            contentContainer.insertBefore(alertDiv, contentContainer.firstChild);
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // MAJ : initializeEventListeners écoute les deux boutons radio
    function initializeEventListeners() {
        const hoursInput = document.getElementById('machineHours');
        if (hoursInput) {
            hoursInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    locateHours();
                }, 300);
            });
        }
        if (hoursInput) {
            hoursInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    locateHours();
                }
            });
        }
        // Toggle buttons
        const modeHeures = document.getElementById('modeHeures');
        const modeAnnees = document.getElementById('modeAnnees');
        if (modeHeures) {
            modeHeures.addEventListener('change', updateInputMode);
        }
        if (modeAnnees) {
            modeAnnees.addEventListener('change', updateInputMode);
        }
    }

    // Initialisation principale : attendre le chargement du JSON avant de lancer l'UI
    document.addEventListener('DOMContentLoaded', function () {
        showLoadingBackdrop();
        fetch('/assets/data/ponctuel-maintenance-data.json')
            .then(response => {
                if (!response.ok) throw new Error('Erreur lors du chargement des données de maintenance');
                return response.json();
            })
            .then(data => {
                maintenanceData = data;
                // Appeler ici la fonction d'initialisation principale
                if (typeof initializeMachineSelection === 'function') {
                    initializeMachineSelection();
                }
                hideLoadingBackdrop();
            })
            .catch(error => {
                hideLoadingBackdrop();
                alert('Impossible de charger les données de maintenance : ' + error.message);
            });
    });

})();
