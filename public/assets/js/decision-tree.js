document.addEventListener('DOMContentLoaded', function () {
    console.log('Decision tree script loaded');

    const appElement = document.getElementById('decision-tree-app');
    if (!appElement) {
        console.error('Decision tree app element not found');
        return;
    }

    const jsonUrl = appElement.getAttribute('data-json-url');
    console.log('JSON URL:', jsonUrl);

    let navigationStack = [];
    let elements = [];



    // Fonction pour générer un flowchart Mermaid à partir d'un problème
    function generateMermaidFlowchart(rootId, elements) {
        const visited = new Set();
        const flowchart = [];
        const nodeCounter = { count: 0 };
        const idMap = new Map(); // Map pour associer les IDs originaux aux IDs sécurisés
        
        function getSafeNodeId(originalId) {
            if (!idMap.has(originalId)) {
                idMap.set(originalId, `node_${nodeCounter.count++}`);
            }
            return idMap.get(originalId);
        }
        
        function addNode(nodeId, nodeType, nodeTitle) {
            if (visited.has(nodeId)) return;
            visited.add(nodeId);
            
            let nodeStyle = '';
            switch (nodeType) {
                case 'probleme':
                    nodeStyle = ':::probleme';
                    break;
                case 'etat':
                case 'symptome':
                    nodeStyle = ':::etat';
                    break;
                case 'verif':
                    nodeStyle = ':::verif';
                    break;
                case 'action':
                    nodeStyle = ':::action';
                    break;
            }
            
            // Échapper les caractères spéciaux pour Mermaid et limiter la longueur
            const escapedTitle = nodeTitle
                .replace(/[^\w\s\-]/g, ' ')
                .replace(/\s+/g, ' ')
                .trim()
                .substring(0, 50);
            
            const safeId = getSafeNodeId(nodeId);
            flowchart.push(`    ${safeId}["${escapedTitle}"]${nodeStyle}`);
            return safeId;
        }
        
        function addConnection(fromSafeId, toSafeId, label = '') {
            if (label) {
                flowchart.push(`    ${fromSafeId} -->|${label}| ${toSafeId}`);
            } else {
                flowchart.push(`    ${fromSafeId} --> ${toSafeId}`);
            }
        }
        
        function addCircleNode() {
            const circleId = `n${Math.random().toString(36).substr(2, 9)}`;
            flowchart.push(`    ${circleId}(( ))`);
            return circleId;
        }

        function traverseNode(nodeId, parentSafeId = null) {
            const node = elements.find(e => e.id === nodeId);
            if (!node || visited.has(nodeId)) return null;
            
            const safeId = addNode(nodeId, node.type, node.title);
            
            // Ajouter la connexion avec le parent si fourni
            if (parentSafeId) {
                addConnection(parentSafeId, safeId);
            }
            
            // Traiter les connexions selon le type de nœud
            if (node.type === 'verif' && (node.next_ok || node.next_ko)) {
                let fromID = safeId;
                if (node.next_ok && node.next_ko) {
                    // Pour les vérifs avec OK/KO, toujours utiliser un cercle de jonction
                    const circleId = addCircleNode();
                    flowchart.push(`    ${safeId} --> ${circleId}`);
                    fromID = circleId;
                }

                // Branche OK (à gauche)
                if (node.next_ok) {
                    const nextOkSafeId = getSafeNodeId(node.next_ok);
                    flowchart.push(`    ${fromID} --OK--> ${nextOkSafeId}`);
                    traverseNode(node.next_ok, null);
                }
                // Branche KO (à droite)
                if (node.next_ko) {
                    const nextKoSafeId = getSafeNodeId(node.next_ko);
                    flowchart.push(`    ${fromID} --KO--> ${nextKoSafeId}`);
                    traverseNode(node.next_ko, null);
                }
            } else if (node.next) {
                if (Array.isArray(node.next)) {
                    let fromID = safeId;
                    if (node.next.length > 1) {
                        // Pour les nœuds avec plusieurs next, utiliser un cercle de jonction
                        const circleId = addCircleNode();
                        flowchart.push(`    ${safeId} --> ${circleId}`);
                        fromID = circleId;
                    }
                    node.next.forEach((nextId, index) => {
                        const nextSafeId = getSafeNodeId(nextId);
                        flowchart.push(`    ${fromID} --> ${nextSafeId}`);
                        traverseNode(nextId, null);
                    });
                } else {
                    // Liaison directe pour un seul next
                    traverseNode(node.next, safeId);
                }
            }
            
            return safeId;
        }
        
        // Correction : toujours partir du noeud racine (état, symptôme, action, etc.)
        traverseNode(rootId, null);
        
        if (flowchart.length === 0) return '';
        
        return `%%{ init: { "flowchart": { "curve": "stepAfter" } } }%%\nflowchart TD\n${flowchart.join('\n')}\n\nclassDef probleme fill:#ff9999,stroke:#333,stroke-width:2px,color:#000\nclassDef etat fill:#99ccff,stroke:#333,stroke-width:2px,color:#000\nclassDef verif fill:#ffff99,stroke:#333,stroke-width:2px,color:#000\nclassDef action fill:#99ff99,stroke:#333,stroke-width:2px,color:#000`;
    }

    console.log('Fetching JSON data from:', jsonUrl);
    fetch(jsonUrl)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('JSON data loaded successfully:', data);
            elements = data.elements;
            console.log('Elements count:', elements.length);
            showCategories();
        })
        .catch(error => {
            console.error('Error loading JSON data:', error);
            document.getElementById('decision-tree-app').innerHTML = `
                <div class="alert alert-danger">
                    <h4>Erreur de chargement</h4>
                    <p>Impossible de charger les données de l'arbre de dépannage.</p>
                    <p>Erreur: ${error.message}</p>
                </div>
            `;
        });

    function updateBreadcrumb() {
        const breadcrumbDiv = document.getElementById('decision-tree-breadcrumb');
        if (navigationStack.length === 0) {
            breadcrumbDiv.innerHTML = '';
            return;
        }
        let html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        navigationStack.forEach((item, idx) => {
            // Cherche le type de l'item dans elements
            const element = elements.find(e => e.id === item.id);
            let badge = '';
            if (element) {
                if (element.type === 'probleme') {
                    badge = '<span class="breadcrumb-problem-badge"></span>';
                } else if (element.type === 'etat' || element.type === 'symptome') {
                    badge = '<span class="breadcrumb-state-badge"></span>';
                } else if (element.type === 'verif') {
                    badge = '<span class="breadcrumb-verif-badge"></span>';
                } else if (element.type === 'action') {
                    badge = '<span class="breadcrumb-action-badge"></span>';
                }
            }
            if (idx < navigationStack.length - 1) {
                html += `<li class="breadcrumb-item"><a href="#" onclick="window.breadcrumbGoTo(${idx});return false;">${badge}${item.label}</a></li>`;
            } else {
                html += `<li class="breadcrumb-item active" aria-current="page">${badge}${item.label}</li>`;
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

        // Trouver le problème dans les éléments pour obtenir sa page
        const problem = elements.find(e => e.id === problemId);

        appDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <button class="btn btn-secondary" onclick="window.showCategories()">Retour aux catégories</button>
                <button class="btn btn-outline-info" onclick="window.goBackStep()">Retour</button>
            </div>
            <div class="row">
                <div id="problem-flowchart" class="col-12"></div>
                <div id="problem-steps" class="col-12"></div>
            </div>`;    


        // Afficher tous les états enfants du problème
        const etats = elements.filter(e => (e.parent === problemId) && (e.type === 'etat' || e.type === 'symptome'));
        const flowchartDiv = document.getElementById('problem-flowchart');
        const stepsDiv = document.getElementById('problem-steps');
        
        // Nettoyer le flowchart (il sera affiché dans showStep)
        flowchartDiv.innerHTML = '';
        
        if (etats.length > 1) {
            let html = '<div class="mb-2">Veuillez sélectionner l\'état correspondant :</div><ul>';
            etats.forEach(etat => {
                html += `<li><button class="btn btn-outline-primary mb-2" onclick="window.showStep('${etat.id}')">${etat.title}</button></li>`;
            });
            html += '</ul>';
            stepsDiv.innerHTML = html;
            window.showStep = (id) => showStep(id, elements, stepsDiv, true);
        } else if (etats.length === 1) {
            showStep(etats[0].id, elements, stepsDiv, true);
        } else {
            stepsDiv.innerHTML = '<em>Aucune étape détaillée pour ce problème.</em>';
        }
        window.showCategories = showCategories;
    }

    function showStep(stepId, elements, container, pushNav = true) {
        const step = elements.find(e => e.id === stepId);
        console.log('[DEBUG] showStep appelé avec :', stepId, step ? step.type : 'inconnu');
        if (!step) return;
        if (pushNav) {
            navigationStack = navigationStack.slice(0, navigationStack.length); // garder tout l'historique
            navigationStack.push({ id: stepId, label: step.title });
        }
        updateBreadcrumb();

        // Affichage du flowchart si c'est un état/symptôme de premier niveau
        const flowchartDiv = document.getElementById('problem-flowchart');
        if (flowchartDiv) {
            // On nettoie le flowchart
            flowchartDiv.innerHTML = '';
            // On affiche le flowchart uniquement si le parent est un problème
            const parent = elements.find(e => e.id === step.parent);
            if (parent && parent.type === 'probleme' && (step.type === 'etat' || step.type === 'symptome')) {
                const flowchart = generateMermaidFlowchart(stepId, elements);
                if (flowchart) {
                    const flowchartContainer = document.createElement('div');
                    flowchartContainer.className = 'mb-4';
                    flowchartContainer.innerHTML = `
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="mdi mdi-flowchart"></i> Diagramme de flux du diagnostic</h5>
                            </div>
                            <div class="card-body">
                                <div class="mermaid">
                                    ${flowchart}
                                </div>
                            </div>
                        </div>
                    `;
                    flowchartDiv.appendChild(flowchartContainer);
                    // Afficher le code Mermaid sous le diagramme
                    const codePre = document.getElementById('mermaid-code');
                    const codeContainer = document.getElementById('mermaid-code-container');
                    if (codePre && codeContainer) {
                        console.log('[DEBUG] Code Mermaid généré :', flowchart);
                        codePre.textContent = flowchart;
                        codeContainer.style.display = 'block';
                    }
                    if (typeof mermaid !== 'undefined') {
                        mermaid.initialize({ 
                            startOnLoad: false,
                            theme: 'default',
                            flowchart: {
                                useMaxWidth: true,
                                htmlLabels: true
                            }
                        });
                        setTimeout(() => {
                            mermaid.init();
                        }, 100);
                    }
                }
            }
        }

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
        let html = `<div class="border p-3 my-2"><strong>${step.title}<span style="display: none;"> - ${step.id}</span></strong></div>`;
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