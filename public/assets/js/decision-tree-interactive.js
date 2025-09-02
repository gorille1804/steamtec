// Application de Diagnostic Interactif
// Version non minifiée

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

// Types et interfaces
class TreeElement {
    constructor(data) {
        this.id = data.id;
        this.type = data.type;
        this.title = data.title;
        this.parent = data.parent;
        this.next = data.next;
        this.next_ok = data.next_ok;
        this.next_ko = data.next_ko;
        this.image = data.image;
        this.usedoc = data.usedoc;
    }
}

class DiagnosticState {
    constructor() {
        this.currentNodeId = null;
        this.history = [];
        this.isComplete = false;
    }
}

// Classe principale de l'application
class DiagnosticApp {
    constructor() {
        this.data = null;
        this.state = new DiagnosticState();
        this.loading = true;
        this.error = null;
        this.appElement = document.getElementById('app');
        
        console.log('Élément app trouvé:', this.appElement);
        if (!this.appElement) {
            console.error('Élément avec ID "app" non trouvé dans le DOM');
        }

        this.init();
    }

    async init() {
        console.log('Initialisation avec URL:', window.jsonUrl);
        await this.loadDiagnosticData();
        this.render();
    }

    async loadDiagnosticData() {
        try {
            this.loading = true;
            console.log('Tentative de chargement depuis:', window.jsonUrl);
            const response = await fetch(window.jsonUrl);
            console.log('Réponse reçue:', response.status, response.statusText);
            
            if (!response.ok) {
                throw new Error(`Impossible de charger les données de diagnostic: ${response.status} ${response.statusText}`);
            }
            
            const jsonData = await response.json();
            console.log('Données JSON chargées:', jsonData);
            this.data = jsonData;
            this.error = null;
        } catch (err) {
            console.error('Erreur lors du chargement:', err);
            this.error = err.message || 'Erreur inconnue';
        } finally {
            this.loading = false;
        }
    }

    startDiagnostic(nodeId) {
        this.state = new DiagnosticState();
        this.state.currentNodeId = nodeId;
        this.state.history = [nodeId];
        this.state.isComplete = false;
        this.render();
    }

    makeChoice(nextNodeId) {
        if (!this.data || !nextNodeId) return;

        const nextNode = this.data.elements.find(el => el.id === nextNodeId);
        const isComplete = !nextNode ||
            (nextNode.type === 'action' &&
                !nextNode.next &&
                !nextNode.next_ok &&
                !nextNode.next_ko);

        this.state.currentNodeId = nextNodeId;
        this.state.history.push(nextNodeId);
        this.state.isComplete = isComplete;
        this.render();
    }

    goBack() {
        if (this.state.history.length <= 1) {
            this.state = new DiagnosticState();
        } else {
            this.state.history.pop();
            this.state.currentNodeId = this.state.history[this.state.history.length - 1];
            this.state.isComplete = false;
        }
        this.render();
    }

    reset() {
        this.state = new DiagnosticState();
        this.render();
    }

    navigateToNode(nodeId) {
        if (!this.data || !nodeId) return;

        const nextNode = this.data.elements.find(el => el.id === nodeId);
        const isComplete = !nextNode ||
            (nextNode.type === 'action' &&
                !nextNode.next &&
                !nextNode.next_ok &&
                !nextNode.next_ko);

        // Trouver l'index du nœud dans l'historique
        const nodeIndex = this.state.history.indexOf(nodeId);

        if (nodeIndex !== -1) {
            // Si le nœud est dans l'historique, naviguer vers cette position
            this.state.history = this.state.history.slice(0, nodeIndex + 1);
            this.state.currentNodeId = nodeId;
            this.state.isComplete = isComplete;
        } else {
            // Si le nœud n'est pas dans l'historique, l'ajouter
            this.state.currentNodeId = nodeId;
            this.state.history.push(nodeId);
            this.state.isComplete = isComplete;
        }

        this.render();
    }

    getCurrentNode() {
        if (!this.data || !this.state.currentNodeId) return null;
        return this.data.elements.find(el => el.id === this.state.currentNodeId);
    }

    getProgress() {
        if (!this.state.currentNodeId) return 0;
        return Math.min((this.state.history.length / 5) * 100, 90);
    }

    getCategories() {
        if (!this.data) return [];
        return this.data.elements.filter(el => el.type === 'categorie');
    }

    getProblemsByCategory(categoryId) {
        if (!this.data) return [];
        return this.data.elements.filter(el => el.type === 'probleme' && el.parent === categoryId);
    }

    getChildElements(elementId) {
        if (!this.data) return [];
        return this.data.elements.filter(el => el.parent === elementId);
    }

    getNextElements(elementId) {
        if (!this.data) return [];
        const element = this.data.elements.find(el => el.id === elementId);
        if (!element) return [];

        const nextIds = [];

        if (element.type === 'verif') {
            if (element.next_ok) {
                nextIds.push(element.next_ok);
            }
            if (element.next_ko) {
                nextIds.push(element.next_ko);
            }
        } else {
            if (element.next) {
                nextIds.push(...element.next);
            }
        }

        return this.data.elements.filter(el => nextIds.includes(el.id));
    }

    getChoicesForElement(elementId) {
        if (!this.data) return [];
        const element = this.data.elements.find(el => el.id === elementId);
        if (!element) return [];

        if (element.type === 'probleme') {
            const childStates = this.getChildElements(elementId);
            return childStates.map(el => ({
                id: el.id,
                title: el.title,
                type: 'next'
            }));
        }

        if (element.type === 'verif') {
            const choices = [];

            if (element.next_ok) {
                const okElement = this.data.elements.find(el => el.id === element.next_ok);
                if (okElement) {
                    choices.push({ id: okElement.id, title: okElement.title, type: 'ok' });
                }
            }

            if (element.next_ko) {
                const koElement = this.data.elements.find(el => el.id === element.next_ko);
                if (koElement) {
                    choices.push({ id: koElement.id, title: koElement.title, type: 'ko' });
                }
            }

            return choices;
        } else {
            return this.getNextElements(elementId).map(el => ({
                id: el.id,
                title: el.title,
                type: 'next'
            }));
        }
    }

    renderAdvancedFlowchart(currentNode) {
        if (!this.data || !currentNode) return '';

        const previousNode = this.state.history.length > 1 ?
            this.data.elements.find(el => el.id === this.state.history[this.state.history.length - 2]) : null;

        const nextNodes = this.getChoicesForElement(currentNode.id);

        let flowchart = '<div class="flex flex-col items-center justify-center space-y-6 py-0">';

        // Nœuds précédents (tous les nœuds sauf le courant)
        if (this.state.history.length > 1) {
            // Parcourir tous les nœuds précédents (sauf le nœud actuel)
            for (let i = 0; i < this.state.history.length - 1; i++) {
                const previousNode = this.data.elements.find(el => el.id === this.state.history[i]);

                if (previousNode) {
                    // Déterminer la couleur subtile selon le type
                    let nodeColor = 'bg-gray-50 border-gray-200 text-gray-600';

                    if (previousNode.type === 'etat') {
                        nodeColor = 'bg-green-50 border-green-200 text-green-700';
                    } else if (previousNode.type === 'verif') {
                        nodeColor = 'bg-yellow-50 border-yellow-200 text-yellow-700';
                    } else if (previousNode.type === 'action') {
                        nodeColor = 'bg-orange-50 border-orange-200 text-orange-700';
                    } else if (previousNode.usedoc) {
                        nodeColor = 'bg-red-50 border-red-200 text-red-700';
                    }

                    // Déterminer si c'était un choix OK ou KO
                    let choiceLabel = '';
                    if (i < this.state.history.length - 1) {
                        const nextNode = this.data.elements.find(el => el.id === this.state.history[i + 1]);
                        if (nextNode) {
                            const choices = this.getChoicesForElement(previousNode.id);
                            const choice = choices.find(c => c.id === nextNode.id);
                            if (choice && choice.type === 'ok') {
                                choiceLabel = '<div class="text-xs font-medium text-blue-600 mb-1">✓ OK</div>';
                            } else if (choice && choice.type === 'ko') {
                                choiceLabel = '<div class="text-xs font-medium text-orange-600 mb-1">✗ KO</div>';
                            }
                        }
                    }

                    flowchart += `
                                <div class="flex flex-col items-center mb-0">
                                    <button onclick="app.navigateToNode('${previousNode.id}')" class="${nodeColor} border-2 rounded-lg p-3 text-sm max-w-48 text-center shadow-sm hover:shadow-md transition-all cursor-pointer">
                                        ${choiceLabel}
                                        <div class="text-xs">${previousNode.title}</div>
                                    </button>
                                    <svg class="w-6 h-6 text-gray-400 mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m0 0l-7-7m7 7l7-7"></path>
                                    </svg>
                                </div>
                            `;
                }
            }
        }

        // Nœud actuel avec style JointJS-like
        let currentNodeColor = 'bg-blue-100 border-blue-500 text-blue-800';
        let nodeIcon = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'; // Check icon

        if (currentNode.type === 'etat') {
            currentNodeColor = 'bg-green-100 border-green-500 text-green-800';
            nodeIcon = 'M13 10V3L4 14h7v7l9-11h-7z'; // Lightning icon
        } else if (currentNode.type === 'verif') {
            currentNodeColor = 'bg-yellow-100 border-yellow-500 text-yellow-800';
            nodeIcon = 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'; // Clipboard icon
        } else if (currentNode.type === 'action') {
            currentNodeColor = 'bg-orange-100 border-orange-500 text-orange-800';
            nodeIcon = 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'; // Settings icon
        } else if (currentNode.usedoc) {
            currentNodeColor = 'bg-red-100 border-red-500 text-red-800';
            nodeIcon = 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'; // Document icon
        }

        flowchart += `
                <div class="flex flex-col items-center">
                    <button onclick="app.navigateToNode('${currentNode.id}')" class="${currentNodeColor} border-2 rounded-lg p-4 text-sm font-semibold max-w-48 text-center shadow-lg relative hover:shadow-xl transition-all cursor-pointer">
                        <div class="absolute -top-2 -left-2 w-6 h-6 ${currentNodeColor.split(' ')[0]} border-2 border-current rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${nodeIcon}"></path>
                            </svg>
                        </div>
                        <div class="text-xs leading-tight">${currentNode.title}</div>
                    </button>
                                        ${nextNodes.length > 0 ? `
                            <svg class="w-6 h-6 text-gray-400 mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m0 0l-7-7m7 7l7-7"></path>
                            </svg>
                        ` : ''}
                </div>
            `;

        // Nœuds suivants avec style avancé
        if (nextNodes.length > 0) {
            if (nextNodes.length === 1) {
                const nextNode = nextNodes[0];
                let nextNodeColor = 'bg-gray-50 border-gray-200 text-gray-600';
                let nextNodeIcon = 'M9 5l7 7-7 7';

                if (nextNode.type === 'ok') {
                    nextNodeColor = 'bg-blue-50 border-blue-200 text-blue-700';
                    nextNodeIcon = 'M5 13l4 4L19 7';
                } else if (nextNode.type === 'ko') {
                    nextNodeColor = 'bg-orange-50 border-orange-200 text-orange-700';
                    nextNodeIcon = 'M6 18L18 6M6 6l12 12';
                }

                flowchart += `
                            <div class="flex flex-col items-center">
                                <button onclick="app.navigateToNode('${nextNode.id}')" class="${nextNodeColor} border-2 rounded-lg p-3 text-sm max-w-48 text-center shadow-sm hover:shadow-md transition-all cursor-pointer">
                                    ${nextNode.type === 'ok' ? '<div class="text-xs font-medium text-blue-600 mb-1">✓ OK</div>' : ''}
                                    ${nextNode.type === 'ko' ? '<div class="text-xs font-medium text-orange-600 mb-1">✗ KO</div>' : ''}
                                    <div class="text-xs">${nextNode.title}</div>
                                </button>
                            </div>
                        `;
            } else {
                // Pour les vérifications avec deux options (OK/KO), afficher deux flèches distinctes
                if (currentNode.type === 'verif' && nextNodes.length === 2) {
                    flowchart += `
                            <div class="flex flex-col items-center">
                                <div class="flex space-x-8">
                                    ${nextNodes.map(choice => {
                        let choiceColor = 'bg-gray-50 border-gray-200 text-gray-600';
                        let choiceIcon = 'M9 5l7 7-7 7';

                        if (choice.type === 'ok') {
                            choiceColor = 'bg-blue-50 border-blue-200 text-blue-700';
                            choiceIcon = 'M5 13l4 4L19 7';
                        } else if (choice.type === 'ko') {
                            choiceColor = 'bg-orange-50 border-orange-200 text-orange-700';
                            choiceIcon = 'M6 18L18 6M6 6l12 12';
                        }

                        return `
                                            <div class="flex flex-col items-center">
                                                <button onclick="app.navigateToNode('${choice.id}')" class="${choiceColor} border-2 rounded-lg p-3 text-sm max-w-48 text-center shadow-sm hover:shadow-md transition-all cursor-pointer">
                                                    ${choice.type === 'ok' ? '<div class="text-xs font-medium text-blue-600 mb-1">✓ OK</div>' : ''}
                                                    ${choice.type === 'ko' ? '<div class="text-xs font-medium text-orange-600 mb-1">✗ KO</div>' : ''}
                                                    <div class="text-xs">${choice.title}</div>
                                                </button>
                                            </div>
                                        `;
                    }).join('')}
                                </div>
                            </div>
                        `;
                } else {
                    // Pour les autres cas avec plusieurs options
                    flowchart += `
                            <div class="flex flex-col items-center">
                                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-3 text-sm text-gray-600 max-w-96 text-center shadow-sm">
                                    <div class="mt-2 space-y-1">
                                        ${nextNodes.map(choice => `
                                            <button onclick="app.navigateToNode('${choice.id}')" class="text-xs bg-white rounded px-2 py-1 border hover:bg-gray-50 transition-colors cursor-pointer w-full text-left">
                                                ${choice.title}
                                            </button>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        `;
                }
            }
        }

        flowchart += '</div>';
        return flowchart;
    }

    render() {
        if (this.loading) {
            this.renderLoading();
            return;
        }

        if (this.error || !this.data) {
            this.renderError();
            return;
        }

        if (!this.state.currentNodeId) {
            this.renderHomePage();
            return;
        }

        const currentNode = this.getCurrentNode();

        if (!currentNode) {
            this.renderError('Étape de diagnostic introuvable');
            return;
        }

        const isFinalAction = currentNode.type === 'action' &&
            !currentNode.next &&
            !currentNode.next_ok &&
            !currentNode.next_ko;

        if (this.state.isComplete && isFinalAction) {
            this.renderDiagnosticResult(currentNode);
            return;
        }

        this.renderDiagnosticStep(currentNode);
    }

    renderLoading() {
        this.appElement.innerHTML = `
                <div class="min-h-screen flex items-center justify-center">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Chargement en cours...</p>
                    </div>
                </div>
            `;
    }

    renderError(message = this.error || 'Erreur inconnue') {
        this.appElement.innerHTML = `
                <div class="min-h-screen flex items-center justify-center">
                    <div class="text-center">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong class="font-bold">Erreur :</strong>
                            <span class="block sm:inline">${message}</span>
                        </div>
                        <button onclick="location.reload()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Réessayer
                        </button>
                    </div>
                </div>
            `;
    }

    renderHomePage() {
        const categories = this.getCategories();
        const problems = this.data.elements.filter(el => el.type === 'probleme');
        const totalElements = this.data.elements.length;

        const problemsByCategory = categories.map(category => ({
            ...category,
            problems: problems.filter(problem => problem.parent === category.id)
        }));

        this.appElement.innerHTML = `
                <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                    <!-- Contenu principal -->
                    <div class="max-w-7xl mx-auto px-6 py-12">
                        <!-- Instructions -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 mb-12">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <h2 class="text-2xl font-semibold text-gray-800">
                                    Comment utiliser le diagnostic ?
                                </h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-blue-600 font-bold text-lg">1</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-2">Choisissez une catégorie</h3>
                                    <p class="text-gray-600 text-sm">
                                        Sélectionnez la catégorie qui correspond le mieux à votre problème
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-green-600 font-bold text-lg">2</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-2">Suivez les étapes</h3>
                                    <p class="text-gray-600 text-sm">
                                        Répondez aux questions et suivez les actions recommandées
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="text-purple-600 font-bold text-lg">3</span>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-2">Obtenez la solution</h3>
                                    <p class="text-gray-600 text-sm">
                                        Recevez une solution personnalisée ou les coordonnées du SAV
                                    </p>
                                </div>
                            </div>
                        </div>

                                            <!-- Instruction avec flèche -->
                        <div class="bg-orange-100 text-black font-bold text-center py-4 px-6 mb-8 rounded-lg relative">
                            <span class="lg:hidden">SI PLUSIEURS PROBLEMES PRENDRE LE PROBLEME LE PLUS EN HAUT</span>
                            <span class="hidden lg:inline">SI PLUSIEURS PROBLEMES PRENDRE LE PROBLEME LE PLUS A GAUCHE</span>
                        </div>

                        <!-- Catégories -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                            ${problemsByCategory.map(category => `
                                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                                                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 h-16 flex items-center">
                                        <h3 class="text-xl font-semibold text-white">${category.title}</h3>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-3">
                                            ${category.problems.map(problem => `
                                                                                            <button
                                                    onclick="app.startDiagnostic('${problem.id}')"
                                                    class="w-full flex items-center justify-between p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200 text-left group"
                                                >
                                                    <span class="text-gray-800 font-medium">${problem.title}</span>
                                                </button>
                                            `).join('')}
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
    }

    renderDiagnosticStep(node) {
        const choices = this.getChoicesForElement(node.id);
        const progress = this.getProgress();

        const renderContent = () => {
            switch (node.type) {
                case 'probleme':
                    return `
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Problème identifié
                                </h2>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                                    <p class="text-red-800 text-lg leading-relaxed">
                                        ${node.title}
                                    </p>
                                </div>
                                <p class="text-gray-600 mb-6">
                                    Sélectionnez l'état qui correspond le mieux à votre situation pour continuer le diagnostic.
                                </p>
                            </div>
                        `;

                case 'etat':
                    return `
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    État de la machine
                                </h2>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                    <p class="text-blue-800 text-lg leading-relaxed">
                                        ${node.title}
                                    </p>
                                </div>
                                <p class="text-gray-600 mb-6">
                                    Décrivez l'état actuel de votre machine pour continuer le diagnostic.
                                </p>
                            </div>
                        `;

                case 'verif':
                    return `
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Vérification
                                </h2>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                                    <p class="text-yellow-800 text-lg leading-relaxed">
                                        ${node.title}
                                    </p>
                                </div>
                                <p class="text-gray-600 mb-6">
                                    Effectuez cette vérification et indiquez le résultat.
                                </p>
                            </div>
                        `;

                case 'action':
                    return `
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Action à effectuer
                                </h2>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <p class="text-green-800 text-lg leading-relaxed">
                                        ${node.title}
                                    </p>
                                    ${node.usedoc ? `
                                        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                                            <p class="text-blue-700 text-sm mb-2">
                                                📋 Consultez la documentation technique pour plus de détails :
                                            </p>
                                            ${(() => {
                                                const docFile = findDepannageDoc(node.title);
                                                if (docFile) {
                                                    return `
                                                        <a 
                                                            href="${window.location.origin}/uploads/documents/depannage/${docFile}" 
                                                            target="_blank"
                                                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
                                                        >
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            ${docFile}
                                                        </a>
                                                    `;
                                                } else {
                                                    return `
                                                        <div class="text-orange-600 text-sm">
                                                            ⚠️ Document technique non trouvé pour cette action
                                                        </div>
                                                    `;
                                                }
                                            })()}
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;

                default:
                    return `
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    ${node.title}
                                </h2>
                            </div>
                        `;
            }
        };

        const renderChoices = () => {
            if (choices.length === 0) return '';

            return `
                    <div class="space-y-3">
                        ${choices.map(choice => {
                let buttonClass = "w-full p-4 text-left border rounded-lg transition-all duration-200 font-medium";

                // Vérifier si le choix correspond à un nœud de type 'etat', 'verif' ou 'action'
                const choiceNode = this.data.elements.find(el => el.id === choice.id);
                const isEtat = choiceNode && choiceNode.type === 'etat';
                const isVerif = choiceNode && choiceNode.type === 'verif';
                const isAction = choiceNode && choiceNode.type === 'action';
                const hasUsedoc = choiceNode && choiceNode.usedoc === true;

                if (choice.type === 'ok') {
                    buttonClass += " bg-blue-50 hover:bg-blue-100 border-blue-200 hover:border-blue-300 text-blue-800 hover:text-blue-900";
                } else if (choice.type === 'ko') {
                    buttonClass += " bg-orange-50 hover:bg-orange-100 border-orange-200 hover:border-orange-300 text-orange-800 hover:text-orange-900";
                } else if (hasUsedoc) {
                    buttonClass += " bg-red-100 hover:bg-red-200 border-red-300 hover:border-red-400 text-red-900 hover:text-red-950";
                } else if (isEtat) {
                    buttonClass += " bg-green-50 hover:bg-green-100 border-green-200 hover:border-green-300 text-green-800 hover:text-green-900";
                } else if (isVerif) {
                    buttonClass += " bg-yellow-100 hover:bg-yellow-200 border-yellow-300 hover:border-yellow-400 text-yellow-900 hover:text-yellow-950";
                } else if (isAction) {
                    buttonClass += " bg-orange-100 hover:bg-orange-200 border-orange-300 hover:border-orange-400 text-orange-900 hover:text-orange-950";
                } else {
                    buttonClass += " bg-gray-50 hover:bg-blue-50 border-gray-200 hover:border-blue-300 text-gray-800 hover:text-blue-800";
                }

                return `
                                <button
                                    onclick="app.makeChoice('${choice.id}')"
                                    class="${buttonClass}"
                                >
                                    ${choice.title}
                                </button>
                            `;
            }).join('')}
                    </div>
                `;
        };

        this.appElement.innerHTML = `
                <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
                    <div class="max-w-7xl mx-auto p-6">
                        <!-- Header avec navigation -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <button
                                    onclick="app.goBack()"
                                    class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Retour
                                </button>
                            </div>
                            <button
                                onclick="app.reset()"
                                class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Accueil
                            </button>
                        </div>

                        <!-- Barre de progression -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                            <div class="bg-blue-600 h-2 rounded-full progress-bar" style="width: ${progress}%"></div>
                        </div>

                                            <!-- Contenu principal avec sidebar -->
                                                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                                                        <div class="flex flex-col lg:flex-row">
                                    <!-- Sidebar avec arbre de décision (1/3) - en haut sur mobile -->
                                    <div class="flex-1 lg:flex-[1] p-6 bg-gray-100 order-1 lg:order-2">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                                        </svg>
                                        Arbre de décision
                                    </h3>
                                    <div>
                                        ${this.renderAdvancedFlowchart(node)}
                                    </div>
                                </div>

                                    <!-- Filet vertical (desktop) et horizontal (mobile) -->
                                    <div class="hidden lg:block w-px bg-gray-200 order-2"></div>

                                    <!-- Contenu principal (2/3) - en bas sur mobile -->
                                    <div class="flex-1 lg:flex-[2] order-3 lg:order-1">
                                        ${node.image ? `
                                            <div class="bg-gray-50 p-8 text-center">
                                                <img
                                                    src="${window.location.origin}/${node.image}"
                                                    alt="Illustration du diagnostic"
                                                    class="max-w-full h-auto mx-auto rounded-lg shadow-md"
                                                    onerror="this.style.display='none'"
                                                />
                                            </div>
                                        ` : ''}

                                        <div class="p-8">
                                            ${renderContent()}
                                            ${renderChoices()}
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
    }

    renderDiagnosticResult(node) {
        const isSavAction = node.title.toLowerCase().includes('sav') ||
            node.title.toLowerCase().includes('appeler') ||
            node.title.toLowerCase().includes('0681676430');

        this.appElement.innerHTML = `
                <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
                    <div class="max-w-7xl mx-auto p-6">
                        <!-- Header avec navigation -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <button
                                    onclick="app.goBack()"
                                    class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Retour
                                </button>
                            </div>
                            <button
                                onclick="app.reset()"
                                class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Accueil
                            </button>
                        </div>

                        <!-- Barre de progression -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                            <div class="bg-blue-600 h-2 rounded-full progress-bar" style="width: 100%"></div>
                        </div>

                                            <!-- Contenu principal avec sidebar -->
                                                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                                                        <div class="flex flex-col lg:flex-row">
                                    <!-- Sidebar avec arbre de décision (1/3) - en haut sur mobile -->
                                    <div class="flex-1 lg:flex-[1] p-6 bg-gray-100 order-1 lg:order-2">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                                        </svg>
                                        Arbre de décision
                                    </h3>
                                    <div>
                                        ${this.renderAdvancedFlowchart(node)}
                                    </div>
                                </div>

                                    <!-- Filet vertical (desktop) et horizontal (mobile) -->
                                    <div class="hidden lg:block w-px bg-gray-200 order-2"></div>
                                    <div class="lg:hidden w-full h-px bg-gray-200 my-6 order-2"></div>

                                    <!-- Contenu principal (2/3) - en bas sur mobile -->
                                    <div class="flex-1 lg:flex-[2] order-3 lg:order-1">
                                        <!-- Header -->
                                        <div class="px-8 py-6 ${isSavAction ? 'bg-orange-50 border-b border-orange-100' : 'bg-green-50 border-b border-green-100'}">
                                            <div class="flex items-center">
                                                ${isSavAction ? `
                                                    <svg class="w-8 h-8 text-orange-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                ` : `
                                                    <svg class="w-8 h-8 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                `}
                                                <h2 class="text-2xl font-bold ${isSavAction ? 'text-orange-800' : 'text-green-800'}">
                                                    ${isSavAction ? 'Contactez le service après-vente' : 'Action à effectuer'}
                                                </h2>
                                            </div>
                                        </div>
    
                                        <div class="p-8">
                                            <!-- Action principale -->
                                            <div class="mb-8">
                                                <div class="border rounded-lg p-6 ${isSavAction ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-200'}">
                                                    <h3 class="text-lg font-semibold mb-3 ${isSavAction ? 'text-orange-800' : 'text-green-800'}">
                                                        ${isSavAction ? 'Action recommandée :' : 'Action à effectuer :'}
                                                    </h3>
                                                    <p class="leading-relaxed ${isSavAction ? 'text-orange-700' : 'text-green-700'}">
                                                        ${node.title}
                                                    </p>
                                                    ${node.usedoc ? `
                                                        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                                                            <p class="text-blue-700 text-sm mb-2">
                                                                📋 Consultez la documentation technique pour plus de détails :
                                                            </p>
                                                            ${(() => {
                                                                const docFile = findDepannageDoc(node.title);
                                                                if (docFile) {
                                                                    return `
                                                                        <a 
                                                                            href="${window.location.origin}/uploads/documents/depannage/${docFile}" 
                                                                            target="_blank"
                                                                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
                                                                        >
                                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            ${docFile}
                                                                        </a>
                                                                    `;
                                                                } else {
                                                                    return `
                                                                        <div class="text-orange-600 text-sm">
                                                                            ⚠️ Document technique non trouvé pour cette action
                                                                        </div>
                                                                    `;
                                                                }
                                                            })()}
                                                        </div>
                                                    ` : ''}
                                                </div>
                                            </div>
    
                                            ${isSavAction ? `
                                                <!-- Informations SAV -->
                                                <div class="mb-8">
                                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                                                        <h3 class="text-lg font-semibold text-orange-800 mb-4">
                                                            Informations du service après-vente :
                                                        </h3>
                                                        <div class="space-y-4">
                                                            <div class="flex items-center">
                                                                <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                                </svg>
                                                                <div>
                                                                    <span class="font-medium text-orange-800">Téléphone : </span>
                                                                    <a
                                                                        href="tel:0681676430"
                                                                        class="text-orange-700 hover:text-orange-900 underline"
                                                                    >
                                                                        0681676430
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="text-orange-700 text-sm">
                                                                <p>📞 Appelez ce numéro pour obtenir une assistance technique</p>
                                                                <p>🕒 Service disponible pour vous aider à résoudre votre problème</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
    }
}

// Initialisation de l'application
console.log('Initialisation de l\'application DiagnosticApp...');
const app = new DiagnosticApp();
console.log('Application initialisée:', app);
