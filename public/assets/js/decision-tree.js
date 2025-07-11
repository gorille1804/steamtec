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
            // Chercher le document PDF si besoin
            let docLink = null;
            if ((nodeType === 'action' || nodeType === 'verif')) {
                const node = elements.find(e => e.id === nodeId);
                if (node && node.usedoc === true) {
                    const docFile = findDepannageDoc(nodeTitle);
                    if (docFile) {
                        docLink = `/uploads/documents/depannage/${encodeURIComponent(docFile)}`;
                    }
                }
            }
            // Préparer le titre pour Mermaid en préservant les caractères spéciaux
            // et en ajoutant des retours à la ligne pour les textes longs
            let processedTitle = nodeTitle
                .replace(/"/g, '\"') // Échapper les guillemets doubles
                .replace(/\n/g, '<br/>') // Convertir les retours à la ligne existants
                .trim();
            // Ajouter des retours à la ligne pour les textes longs (tous les 40 caractères environ)
            if (processedTitle.length > 40) {
                const words = processedTitle.split(' ');
                let lines = [];
                let currentLine = '';
                words.forEach(word => {
                    if ((currentLine + ' ' + word).length > 40 && currentLine.length > 0) {
                        lines.push(currentLine.trim());
                        currentLine = word;
                    } else {
                        currentLine += (currentLine ? ' ' : '') + word;
                    }
                });
                if (currentLine) {
                    lines.push(currentLine.trim());
                }
                processedTitle = lines.join('<br/>');
            }
            // Limiter la longueur totale à 200 caractères
            if (processedTitle.length > 200) {
                processedTitle = processedTitle.substring(0, 197) + '...';
            }
            // Si docLink existe, rendre le label cliquable
            if (docLink) {
                processedTitle = `<a href=\"${docLink}\" target=\"_blank\">${processedTitle}</a>`;
                nodeStyle = ':::document';
            }
            const safeId = getSafeNodeId(nodeId);
            flowchart.push(`    ${safeId}["${processedTitle}"]${nodeStyle}`);
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

        function addImageNode(imageUrl, parentSafeId) {
            // Crée un noeud image Mermaid (rectangle avec icône ou nom de fichier)
            const imageNodeId = `img_${Math.random().toString(36).substr(2, 9)}`;
            // On affiche le nom du fichier image (ou une icône)
            let label = imageUrl ? `<img src='${imageUrl}' style='width:32px;height:auto;vertical-align:middle;'/>` : 'Image';
            // Si Mermaid ne supporte pas <img>, on affiche juste le nom du fichier
            if (!imageUrl.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)) {
                label = imageUrl;
            }
            flowchart.push(`    ${imageNodeId}["${label}"]:::image`);
            if (parentSafeId) {
                flowchart.push(`    ${parentSafeId} --> ${imageNodeId}`);
            }
            return imageNodeId;
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
                // Ajout gestion image : si le noeud verif a une image, on insère un noeud image juste après
                if (node.image) {
                    fromID = addImageNode(node.image, safeId);
                }
                if (node.next_ok && node.next_ko) {
                    // Pour les vérifs avec OK/KO, toujours utiliser un cercle de jonction
                    const circleId = addCircleNode();
                    flowchart.push(`    ${fromID} --> ${circleId}`);
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
        
        return `%%{ init: { "flowchart": { "curve": "stepAfter" } } }%%\nflowchart TD\n${flowchart.join('\n')}\n\nclassDef probleme fill:#ff9999,stroke:#333,stroke-width:2px,color:#000\nclassDef etat fill:#99ccff,stroke:#333,stroke-width:2px,color:#000\nclassDef verif fill:#ffff99,stroke:#333,stroke-width:2px,color:#000\nclassDef action fill:#99ff99,stroke:#333,stroke-width:2px,color:#000\nclassDef image fill:#fff,stroke:#333,stroke-width:2px,color:#000,stroke-dasharray: 5 5\nclassDef document fill:#ff9999,stroke:#333,stroke-width:1px,color:#fff`;
    }

    // Liste des documents PDF disponibles pour les fiches techniques (à synchroniser avec le dossier uploads/documents/depannage/)
    const DEPANNAGE_DOCS = [
        "DT001 CHANGER LA SONDE ELECTRONIQUE.pdf",
        "DT005 REMPLACE LE PROGRAMMATEUR.pdf",
        "DT006 CHANGER LA POMPE ANTICALCAIRE.pdf",
        "DT013 CHANGER LA VANNE BY-PASS.pdf",
        "DT014 VERIF ETAT CABLAGE BOITIER ELEC POMPE + ENCLENCHEMENT CONTACTEUR.pdf",
        "DT018 NETTOYER ET VERIFIER LE BRULEUR.pdf",
        "DT019 CHANGER LA POMPE A GASOIL.pdf",
        "DT020 VERIFIER SI BRULEUR EST ALIMENTE EN CARBURANT.pdf",
        "DT021 CHANGER LELECTROVANNE.pdf",
        "DT022 TESTER FONCTIONNEMENT BOBINE D'ELECTROVANNE.pdf",
        "DT030  CHANGER  LA SONDE DE DEBIT.pdf",
        "DT032 VERIF  BON POSITION SONDE ELECTRONIQUE.pdf",
        "D002 VERIFIER CABLAGE SONDE ELECTRO.pdf",
        "D003 VERIFIER CABLAGE PHASE NEUTRE PROG.pdf",
        "D007 VERIFIER CABLAGE POMPE ANTICALC.pdf",
        "D008 DEMONTER CLAPETS.pdf",
        "D009 NETTOYER L'ACCESSOIRE BOUCHE.pdf",
        "D011 REALISER UN DETARTRAGE DE LA MACHINE.pdf",
        "D012 CHANGER CLAPET RACCORD DE SORTIE.pdf",
        "D015 VERIFIER LE FILTRE CARBURANT.pdf",
        "D016 CHANGER LE FILTRE A CARBURANT.pdf",
        "D017 VERIFIER CHANGER FILTRE INT POMPE GASOIL.pdf",
        "D023 VERIF BON BRANCHEMENT ARMOIRE ELEC ELECTROVANNE.pdf",
        "D024 VERIF BON BRANCHEMENT ARMOIRE ELEC DE ALLUM CHAUDIERE.pdf",
        "D025 VERIF  BOBINE ET ELECTRODES BIEN BRANCHEES + ETAT DES CABLES.pdf",
        "D028 VERIF BRANCHEMENT PROG ET VOYANT VERT.pdf",
        "D031  SEPARER MECANIQUEMENT SONDE MECA.pdf"
    ];

    function findDepannageDoc(title) {
        // On cherche un fichier dont le nom commence par le titre (en ignorant la casse et les espaces)
        const normalizedTitle = title.trim().toLowerCase().replace(/\s+/g, ' ');
        return DEPANNAGE_DOCS.find(file => file.toLowerCase().replace(/\s+/g, ' ').startsWith(normalizedTitle));
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="mdi mdi-flowchart"></i> Diagramme de flux du diagnostic</h5>
                                <div class="btn-group" role="group" aria-label="Contrôles de zoom">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.zoomOut()" title="Zoom arrière">
                                        <i class="mdi mdi-magnify-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.resetZoom()" title="Zoom normal">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.zoomIn()" title="Zoom avant">
                                        <i class="mdi mdi-magnify-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="mermaid-zoom-container">
                                    <div class="mermaid" id="mermaid-flowchart">
                                        ${flowchart}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    flowchartDiv.appendChild(flowchartContainer);
                    // Injection du CSS pour la zone de zoom (une seule fois)
                    if (!document.getElementById('mermaid-zoom-style')) {
                        const style = document.createElement('style');
                        style.id = 'mermaid-zoom-style';
                        style.innerHTML = `
                            .mermaid-zoom-container {
                                width: 100%;
                                height: calc(100vh - 210px); /* Ajuster 210px selon la hauteur de la barre de navigation/breadcrumbs */
                                overflow: auto;
                                overflow-x: auto;
                                /* display: flex; */
                                /* align-items: center; */
                                /* justify-content: center; */
                                background: #fff;
                            }
                            .mermaid-zoom-container .mermaid {
                                margin: 0;
                                display: block;
                            }
                            @media (max-width: 900px) {
                                .mermaid-zoom-container {
                                    height: calc(100vh - 100px);
                                    padding: 10px 4px 10px 4px;
                                }
                            }
                        `;
                        document.head.appendChild(style);
                    }
                    // Afficher le code Mermaid sous le diagramme
                    const codePre = document.getElementById('mermaid-code');
                    const codeContainer = document.getElementById('mermaid-code-container');
                    if (codePre && codeContainer) {
                        console.log('[DEBUG] Code Mermaid généré :', flowchart);
                        codePre.textContent = flowchart;
                        //codeContainer.style.display = 'block';
                    }
                    if (typeof mermaid !== 'undefined') {
                        mermaid.initialize({ 
                            startOnLoad: false,
                            theme: 'default',
                            flowchart: {
                                useMaxWidth: true,
                                htmlLabels: true
                            },
                            zoom: {
                                enabled: true,
                                scale: 1
                            }
                        });

                        // Variables globales pour le zoom
                        window.currentZoom = 1;
                        window.minZoom = 0.5;
                        window.maxZoom = 3;

                        // Fonctions de zoom
                        window.zoomIn = function () {
                            if (window.currentZoom < window.maxZoom) {
                                window.currentZoom = Math.min(window.currentZoom * 1.2, window.maxZoom);
                                applyZoom();
                            }
                        };

                        window.zoomOut = function () {
                            if (window.currentZoom > window.minZoom) {
                                window.currentZoom = Math.max(window.currentZoom / 1.2, window.minZoom);
                                applyZoom();
                            }
                        };

                        window.resetZoom = function () {
                            window.currentZoom = 1;
                            applyZoom();
                        };

                        function applyZoom() {
                            const container = document.querySelector('.mermaid-zoom-container');
                            const svg = document.querySelector('#mermaid-flowchart svg');
                            if (svg && container) {
                                // Mémoriser le centre visible avant zoom
                                const prevScrollLeft = container.scrollLeft;
                                const prevScrollTop = container.scrollTop;
                                const prevWidth = svg.clientWidth * (1 / window.currentZoom);
                                const prevHeight = svg.clientHeight * (1 / window.currentZoom);
                                const containerWidth = container.clientWidth;
                                const containerHeight = container.clientHeight;
                                const relX = (prevScrollLeft + containerWidth / 2) / prevWidth;
                                const relY = (prevScrollTop + containerHeight / 2) / prevHeight;

                                svg.style.transform = `scale(${window.currentZoom})`;
                                svg.style.transformOrigin = 'top left';
                                svg.style.transition = 'transform 0.3s ease';

                                // Appliquer le scroll pour garder la même zone visible
                                setTimeout(() => {
                                    const newWidth = svg.clientWidth * window.currentZoom;
                                    const newHeight = svg.clientHeight * window.currentZoom;
                                    container.scrollLeft = newWidth * relX - containerWidth / 2;
                                    container.scrollTop = newHeight * relY - containerHeight / 2;
                                }, 10);
                            }
                        }

                        setTimeout(() => {
                            mermaid.init();
                            // Patch : forcer tous les liens du diagramme à s’ouvrir dans un nouvel onglet
                            setTimeout(() => {
                                const mermaidDiv = document.getElementById('mermaid-flowchart');
                                if (mermaidDiv) {
                                    const links = mermaidDiv.querySelectorAll('a');
                                    links.forEach(link => {
                                        link.setAttribute('target', '_blank');
                                        link.setAttribute('rel', 'noopener noreferrer');
                                    });
                                }
                                applyZoom();
                            }, 200);
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