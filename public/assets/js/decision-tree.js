document.addEventListener('DOMContentLoaded', function () {
    const jsonUrl = document.getElementById('decision-tree-app').getAttribute('data-json-url');
    let navigationStack = [];
    let elements = [];

    fetch(jsonUrl)
        .then(response => response.json())
        .then(data => {
            elements = data.elements;
            showCategories();
        });

    function updateBreadcrumb() {
        const breadcrumbDiv = document.getElementById('decision-tree-breadcrumb');
        if (navigationStack.length === 0) {
            breadcrumbDiv.innerHTML = '';
            return;
        }
        let html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        navigationStack.forEach((item, idx) => {
            if (idx < navigationStack.length - 1) {
                html += `<li class="breadcrumb-item"><a href="#" onclick="window.breadcrumbGoTo(${idx});return false;">${item.label}</a></li>`;
            } else {
                html += `<li class="breadcrumb-item active" aria-current="page">${item.label}</li>`;
            }
        });
        html += '</ol></nav>';
        breadcrumbDiv.innerHTML = html;
        window.breadcrumbGoTo = (idx) => {
            navigationStack = navigationStack.slice(0, idx + 1);
            if (navigationStack.length === 1) {
                showCategories();
            } else if (navigationStack.length === 2) {
                showProblem(navigationStack[1].id, elements, document.getElementById('decision-tree-app'), navigationStack[0].label, navigationStack[1].label, false);
            } else if (navigationStack.length > 2) {
                showStep(navigationStack[navigationStack.length - 1].id, elements, document.getElementById('problem-details'), false);
            }
        };
    }

    function showCategories() {
        navigationStack = [];
        updateBreadcrumb();
        const categories = elements.filter(e => e.type === 'categorie');
        const appDiv = document.getElementById('decision-tree-app');
        appDiv.innerHTML = '';
        const ul = document.createElement('ul');
        categories.forEach(cat => {
            const li = document.createElement('li');
            li.innerHTML = `<strong>${cat.title}</strong>`;
            const problems = elements.filter(e => e.type === 'probleme' && e.parent === cat.id);
            if (problems.length) {
                const subUl = document.createElement('ul');
                problems.forEach(prob => {
                    const subLi = document.createElement('li');
                    const btn = document.createElement('button');
                    btn.textContent = prob.title;
                    btn.onclick = () => showProblem(prob.id, elements, appDiv, cat.title, prob.title, true);
                    subLi.appendChild(btn);
                    subUl.appendChild(subLi);
                });
                li.appendChild(subUl);
            }
            ul.appendChild(li);
        });
        appDiv.appendChild(ul);
    }

    function showProblem(problemId, elements, appDiv, catTitle, probTitle, pushNav = true) {
        if (pushNav) {
            navigationStack = [
                { id: elements.find(e => e.type === 'categorie' && e.title === catTitle).id, label: catTitle },
                { id: problemId, label: probTitle }
            ];
        }
        updateBreadcrumb();
        appDiv.innerHTML = `<button class="btn btn-secondary mb-2" onclick="window.showCategories()">Retour aux catégories</button>
            <h3>${catTitle} > ${probTitle}</h3>
            <div id="problem-details"></div>`;
        // Afficher tous les états enfants du problème
        const etats = elements.filter(e => (e.parent === problemId) && (e.type === 'etat' || e.type === 'symptome'));
        const detailsDiv = document.getElementById('problem-details');
        if (etats.length > 1) {
            let html = '<div class="mb-2">Veuillez sélectionner l\'état correspondant :</div><ul>';
            etats.forEach(etat => {
                html += `<li><button class="btn btn-outline-primary mb-2" onclick="window.showStep('${etat.id}')">${etat.title}</button></li>`;
            });
            html += '</ul>';
            detailsDiv.innerHTML = html;
            window.showStep = (id) => showStep(id, elements, detailsDiv, true);
        } else if (etats.length === 1) {
            showStep(etats[0].id, elements, detailsDiv, true);
        } else {
            detailsDiv.innerHTML = '<em>Aucune étape détaillée pour ce problème.</em>';
        }
        window.showCategories = showCategories;
    }

    function showStep(stepId, elements, container, pushNav = true) {
        const step = elements.find(e => e.id === stepId);
        if (!step) return;
        if (pushNav) {
            navigationStack = navigationStack.slice(0, navigationStack.length); // garder tout l'historique
            navigationStack.push({ id: stepId, label: step.title });
        }
        updateBreadcrumb();
        // Si l'étape possède un next qui pointe vers un problème, rediriger automatiquement
        if (step.next && elements.find(e => e.id === step.next && e.type === 'probleme')) {
            const nextProblem = elements.find(e => e.id === step.next && e.type === 'probleme');
            const cat = elements.find(e => e.id === nextProblem.parent && e.type === 'categorie');
            showProblem(nextProblem.id, elements, document.getElementById('decision-tree-app'), cat.title, nextProblem.title, true);
            return;
        }
        // Ajout : gestion du cas où le noeud est de type 'probleme'
        if (step && step.type === 'probleme') {
            const etats = elements.filter(e => (e.parent === step.id) && (e.type === 'etat' || e.type === 'symptome'));
            if (etats.length === 1) {
                showStep(etats[0].id, elements, container, true);
                return;
            } else if (etats.length > 1) {
                let html = '<div class="mb-2">Veuillez sélectionner l\'état correspondant :</div><ul>';
                etats.forEach(etat => {
                    html += `<li><button class="btn btn-outline-primary mb-2" onclick="window.showStep('${etat.id}')">${etat.title}</button></li>`;
                });
                html += '</ul>';
                container.innerHTML = html;
                window.showStep = (id) => showStep(id, elements, container, true);
                return;
            }
        }
        let html = `<div class="border p-3 my-2"><strong>${step.title} - ${step.id}</strong></div>`;
        if (navigationStack.length > 2) {
            html = `<button class="btn btn-outline-secondary mb-2" onclick="window.goBackStep()">Retour</button>` + html;
        }
        if (step.type === 'etat' && step.next) {
            if (Array.isArray(step.next) && step.next.length > 1) {
                html += `<div class="mt-2 mb-2">Veuillez choisir la suite :</div>`;
                step.next.forEach(nextId => {
                    const nextNode = elements.find(e => e.id === nextId);
                    if (nextNode) {
                        html += `<button class="btn btn-primary me-2 mb-2" onclick="window.showStep('${nextNode.id}')">${nextNode.title}</button>`;
                    }
                });
            } else if (Array.isArray(step.next) && step.next.length === 1) {
                html += `<button class="btn btn-primary" onclick="window.showStep('${step.next[0]}')">Commencer le diagnostic</button>`;
            } else if (typeof step.next === 'string') {
                html += `<button class="btn btn-primary" onclick="window.showStep('${step.next}')">Commencer le diagnostic</button>`;
            }
        }
        if (step.type === 'action') {
            if (step.usedoc === true) {
                html += `<div class="alert alert-danger mt-2"><i class="mdi mdi-file-document"></i> Besoin d'une fiche technique</div>`;
            }
            if (step.next) {
                if (Array.isArray(step.next) && step.next.length > 1) {
                    html += `<div class="mt-2 mb-2">Veuillez choisir la suite :</div>`;
                    step.next.forEach(nextId => {
                        const nextNode = elements.find(e => e.id === nextId);
                        if (nextNode) {
                            html += `<button class="btn btn-primary me-2 mb-2" onclick="window.showStep('${nextNode.id}')">${nextNode.title}</button>`;
                        }
                    });
                } else if (Array.isArray(step.next) && step.next.length === 1) {
                    html += `<button class="btn btn-primary mt-2" onclick="window.showStep('${step.next[0]}')">Suite</button>`;
                } else if (typeof step.next === 'string') {
                    html += `<button class="btn btn-primary mt-2" onclick="window.showStep('${step.next}')">Suite</button>`;
                }
            }
        }
        if (step.type === 'verif') {
            if (step.usedoc === true) {
                html += `<div class="alert alert-danger mt-2"><i class="mdi mdi-file-document"></i> Besoin d'une fiche technique</div>`;
            }
            html += `<div>`;
            if (step.next_ok) {
                html += `<button class="btn btn-success me-2" onclick="window.showStep('${step.next_ok}')">OK</button>`;
            }
            if (step.next_ko) {
                html += `<button class="btn btn-danger" onclick="window.showStep('${step.next_ko}')">KO</button>`;
            }
            html += `</div>`;
        }
        container.innerHTML = html;
        window.showStep = (id) => showStep(id, elements, container, true);
        window.goBackStep = () => {
            if (navigationStack.length > 2) {
                navigationStack.pop();
                const prevStep = navigationStack[navigationStack.length - 1];
                showStep(prevStep.id, elements, container, false);
            }
        };
    }
}); 