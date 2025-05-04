// Historique des nœuds visités
let history = [];

// Nœud courant
let currentNodeId = null;

// Map des nœuds pour un accès facile
let nodesMap = {};

// Fonction pour initialiser le troubleshooter
function initializeTroubleshooter(treeData) {
    // Transformer les données pour un accès facile
    nodesMap = {};
    treeData.nodes.forEach(node => {
        nodesMap[node.id] = {
            ...node,
            next: { OK: null, KO: null }
        };
    });

    treeData.links.forEach(link => {
        const sourceNode = nodesMap[link.source];
        sourceNode.next[link.label] = link.target;
    });

    // Définir le premier nœud comme nœud courant
    currentNodeId = treeData.nodes[0].id;

    // Afficher le nœud courant
    renderCurrentNode();
}

// Fonction pour obtenir l'icône selon le type de nœud
function getNodeIcon(type) {
    return '';
}

// Fonction pour obtenir l'icône selon le type pour l'historique
function getHistoryIcon(type) {
    switch (type) {
        case 'symptom':
            return '⚠️';
        case 'check':
            return '❓';
        case 'action':
            return '✅';
        default:
            return '';
    }
}

// Affiche le nœud courant
function renderCurrentNode() {
    const node = nodesMap[currentNodeId];
    const currentNodeElement = document.getElementById('current-node');

    let buttonsHtml = '';

    // Si le nœud a des liens sortants, afficher les boutons
    if (node.next.OK || node.next.KO) {
        if (node.next.OK) {
            buttonsHtml += `<button class="btn btn-success" data-result="OK">Oui / OK</button>`;
        }
        if (node.next.KO) {
            buttonsHtml += `<button class="btn btn-danger" data-result="KO">Non / KO</button>`;
        }
    } else {
        // Nœud final (action sans suite)
        buttonsHtml = `<button class="btn btn-primary" id="restart-final">Terminer</button>`;
    }

    // Afficher le contenu du nœud
    currentNodeElement.innerHTML = `
        ${getNodeIcon(node.type)}
        <div class="node-type ${node.type}">${translateNodeType(node.type)}</div>
        <div class="node-title">${node.label}</div>
        <div class="buttons">
            ${buttonsHtml}
        </div>
    `;

    // Ajouter les écouteurs d'événements
    const buttons = currentNodeElement.querySelectorAll('.btn[data-result]');
    buttons.forEach(button => {
        button.addEventListener('click', handleNodeResponse);
    });

    const finalButton = document.getElementById('restart-final');
    if (finalButton) {
        finalButton.addEventListener('click', restartTroubleshooter);
    }
}

// Traduit le type de nœud en français
function translateNodeType(type) {
    switch (type) {
        case 'symptom':
            return 'Symptôme';
        case 'check':
            return 'Vérification';
        case 'action':
            return 'Action';
        default:
            return type;
    }
}

// Gère la réponse de l'utilisateur (OK/KO)
function handleNodeResponse(event) {
    const result = event.target.getAttribute('data-result');
    const currentNode = nodesMap[currentNodeId];

    // Ajouter à l'historique
    history.push({
        id: currentNodeId,
        label: currentNode.label,
        type: currentNode.type,
        result: result
    });

    // Mettre à jour l'historique affiché
    updateHistory();

    // Aller au nœud suivant
    currentNodeId = currentNode.next[result];
    renderCurrentNode();
}

// Mettre à jour l'affichage de l'historique
function updateHistory() {
    const historyList = document.getElementById('history-list');

    // Vider l'historique actuel
    historyList.innerHTML = '';

    // Ajouter chaque élément de l'historique
    history.forEach(item => {
        const historyItem = document.createElement('div');
        historyItem.className = `history-item ${item.type}`;

        historyItem.innerHTML = `
            <div class="history-item-icon">${getHistoryIcon(item.type)}</div>
            <div class="history-item-label">${item.label}</div>
            ${item.result ? `<div class="history-item-result ${item.result.toLowerCase()}">${item.result}</div>` : ''}
        `;

        historyList.appendChild(historyItem);
    });
}

// Redémarrer le troubleshooter
function restartTroubleshooter() {
    currentNodeId = Object.keys(nodesMap)[0]; // Premier nœud
    history = [];
    renderCurrentNode();
    updateHistory();
}

// Initialiser l'application
document.addEventListener('DOMContentLoaded', () => {
    // Ajouter l'écouteur d'événement pour le bouton de redémarrage
    const restartBtn = document.getElementById('restart-btn');
    if (restartBtn) {
        restartBtn.addEventListener('click', restartTroubleshooter);
    }
}); 