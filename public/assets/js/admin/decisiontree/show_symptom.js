function initializeDecisionTree(treeData) {
    // Convertir les liens de format source/target par ID à source/target par index
    function prepareData(data) {
        const nodeMap = new Map();
        data.nodes.forEach((node, index) => {
            nodeMap.set(node.id, index);
            node.children = [];
            node.parent = null;
        });

        // Convertir les liens
        const links = data.links.map(link => {
            const sourceIndex = nodeMap.get(link.source);
            const targetIndex = nodeMap.get(link.target);
            return {
                source: sourceIndex,
                target: targetIndex,
                label: link.label
            };
        });

        // Établir les relations parent-enfant
        links.forEach(link => {
            const sourceNode = data.nodes[link.source];
            const targetNode = data.nodes[link.target];
            sourceNode.children.push({
                node: targetNode,
                label: link.label
            });
            targetNode.parent = {
                node: sourceNode,
                label: link.label
            };
        });

        return { nodes: data.nodes, links };
    }

    // Préparer les données pour D3
    const data = prepareData(treeData);

    // Fonction utilitaire pour mesurer la largeur d'une ligne de texte
    function getTextWidth(text, font = "14px Segoe UI") {
        const canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
        const context = canvas.getContext("2d");
        context.font = font;
        return context.measureText(text).width;
    }

    // Fonction utilitaire pour découper le texte en plusieurs lignes
    function wrapText(text, maxWidth) {
        const words = text.split(/\s+/);
        const lines = [];
        let currentLine = [];
        let currentWidth = 0;
        const font = "14px Segoe UI";
        words.forEach(word => {
            const wordWidth = getTextWidth(word + (currentLine.length ? " " : ""), font);
            if (currentWidth + wordWidth > maxWidth && currentLine.length > 0) {
                lines.push(currentLine.join(" "));
                currentLine = [word];
                currentWidth = getTextWidth(word, font);
            } else {
                currentLine.push(word);
                currentWidth += wordWidth;
            }
        });
        if (currentLine.length > 0) {
            lines.push(currentLine.join(" "));
        }
        return lines;
    }

    // Juste avant le layout, définir la taille des nœuds pour éviter le chevauchement
    d3.selectAll('.node').each(function (d) {
        d.nodeWidth = 232; // 200 + 32 (padding)
        d.nodeHeight = Math.max(56, (wrapText(d.label, 200).length * 20 + 16));
    });
    const nodeWidth = 232;
    const nodeHeight = 56;
    const treeLayout = d3.tree().nodeSize([nodeWidth + 100, nodeHeight + 80]);

    // Création de la hiérarchie pour l'arbre
    const rootNode = data.nodes.find(n => n.type === "symptom");
    const hierarchyData = createHierarchy(rootNode, data);

    // Appliquer le layout en arbre
    const root = d3.hierarchy(hierarchyData);
    treeLayout(root);

    // Mettre à jour les coordonnées x,y des nœuds en fonction de la nouvelle disposition
    root.each(d => {
        if (d.data.originalNode) {
            d.data.originalNode.x = d.x;
            d.data.originalNode.y = d.y;
        }
    });

    // Configuration du diagramme
    const width = document.getElementById('tree-container').clientWidth;
    const height = document.getElementById('tree-container').clientHeight;

    // Fonction pour créer une hiérarchie adaptée à d3.hierarchy
    function createHierarchy(node, data) {
        const result = {
            name: node.label,
            originalNode: node,
            children: []
        };

        // Trouver tous les liens sortants de ce nœud
        const outgoingLinks = data.links.filter(l =>
            data.nodes[l.source].id === node.id
        );

        // Pour chaque lien, ajouter l'enfant récursivement
        outgoingLinks.forEach(link => {
            const targetNode = data.nodes[link.target];
            const childHierarchy = createHierarchy(targetNode, data);
            childHierarchy.linkLabel = link.label;
            result.children.push(childHierarchy);
        });

        return result;
    }

    // Créer le SVG
    const svg = d3.select('#tree-container')
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .call(d3.zoom().on('zoom', (event) => {
            g.attr('transform', event.transform);
        }))
        .append('g');

    const g = svg.append('g')
        .attr('transform', `translate(${width / 2}, 50)`);

    // Créer les liens
    const link = g.selectAll('.link')
        .data(data.links)
        .enter()
        .append('path')
        .attr('class', 'link')
        .attr('id', (d, i) => `link-${i}`)
        .attr('stroke', '#999')
        .attr('stroke-opacity', 0.6)
        .attr('marker-end', 'url(#arrowhead)');

    // Créer les nœuds
    const node = g.selectAll('.node')
        .data(data.nodes)
        .enter()
        .append('g')
        .attr('class', d => `node ${d.type}`)
        .attr('id', d => `node-${d.id}`)
        .attr('transform', d => `translate(${d.x}, ${d.y})`)
        .on('click', handleNodeClick)
        .on('mouseover', handleMouseOver)
        .on('mouseout', handleMouseOut);

    // Ajouter des formes pour les nœuds
    node.each(function (d) {
        const element = d3.select(this);
        const maxTextWidth = 200;
        const font = "14px Segoe UI";
        const lines = wrapText(d.label, maxTextWidth);
        const textWidths = lines.map(line => getTextWidth(line, font));
        const textWidth = Math.ceil(Math.max(...textWidths));
        const width = textWidth + 32; // 16px padding de chaque côté
        const lineHeight = 20;
        const height = lines.length * lineHeight + 16; // 8px padding haut/bas

        if (d.type === 'check') {
            // Pour que le texte soit bien contenu, on agrandit la largeur du losange
            const diamondWidth = width * Math.SQRT2;
            const diamondHeight = height * Math.SQRT2;
            element.append('polygon')
                .attr('points', `0,-${diamondHeight / 2} ${diamondWidth / 2},0 0,${diamondHeight / 2} -${diamondWidth / 2},0`);
        } else {
            element.append('rect')
                .attr('width', width)
                .attr('height', height)
                .attr('x', -width / 2)
                .attr('y', -height / 2)
                .attr('rx', 8)
                .attr('ry', 8);
        }

        // Ajout du texte multi-lignes centré verticalement
        const textGroup = element.append('text')
            .attr('text-anchor', 'middle')
            .attr('fill', 'white')
            .attr('font-size', '14px')
            .attr('y', -(height / 2) + 8 + lineHeight); // 8px de padding haut

        lines.forEach((line, i) => {
            textGroup.append('tspan')
                .attr('x', 0)
                .attr('dy', i === 0 ? 0 : lineHeight)
                .text(line);
        });
    });

    // Ajouter des étiquettes aux liens (labels OK/KO toujours à l'endroit)
    g.selectAll('.link-label')
        .data(data.links)
        .enter()
        .append('text')
        .attr('class', 'link-label')
        .attr('x', d => {
            const sourceNode = data.nodes[d.source];
            const targetNode = data.nodes[d.target];
            return (sourceNode.x + targetNode.x) / 2;
        })
        .attr('y', d => {
            const sourceNode = data.nodes[d.source];
            const targetNode = data.nodes[d.target];
            return (sourceNode.y + targetNode.y) / 2;
        })
        .attr('text-anchor', 'middle')
        .attr('alignment-baseline', 'middle')
        .attr('transform', d => {
            const sourceNode = data.nodes[d.source];
            const targetNode = data.nodes[d.target];
            const x = (sourceNode.x + targetNode.x) / 2;
            const y = (sourceNode.y + targetNode.y) / 2;
            const angle = Math.atan2(targetNode.y - sourceNode.y, targetNode.x - sourceNode.x) * 180 / Math.PI;
            // Si l'angle est proche de 90 ou -90 degrés (vertical), pas de rotation
            if (Math.abs(Math.abs(angle) - 90) < 10) {
                return `rotate(0,${x},${y})`;
            }
            // Sinon, rotation normale (et retournement si à l'envers)
            if (angle > 90 || angle < -90) {
                return `rotate(${angle + 180},${x},${y})`;
            }
            return `rotate(${angle},${x},${y})`;
        })
        .text(d => d.label);

    // Ajouter des marqueurs de flèche pour les liens
    svg.append('defs').append('marker')
        .attr('id', 'arrowhead')
        .attr('viewBox', '0 -5 10 10')
        .attr('refX', 10)
        .attr('refY', 0)
        .attr('markerWidth', 6)
        .attr('markerHeight', 6)
        .attr('orient', 'auto')
        .append('path')
        .attr('d', 'M0,-5L10,0L0,5')
        .attr('fill', '#999');

    // Mettre à jour les positions des liens
    updateLinkPositions();

    // Centrer la vue sur la racine de l'arbre
    centerOnNode(rootNode, 0.6);

    // Fonctions de gestion des événements
    function handleNodeClick(event, d) {
        d3.selectAll('.link').classed('path-highlight', false);
        d3.selectAll('.node polygon, .node rect').attr('stroke-width', 2);

        if (d.type === 'check') {
            d3.select(this).select('polygon').attr('stroke-width', 4);
        } else {
            d3.select(this).select('rect').attr('stroke-width', 4);
        }

        const path = [];
        let currentNode = d;

        while (currentNode.parent) {
            path.push({
                source: currentNode.parent.node,
                target: currentNode,
                label: currentNode.parent.label
            });
            currentNode = currentNode.parent.node;
        }

        path.forEach(p => {
            const linkIndex = data.links.findIndex(link =>
                link.source === data.nodes.indexOf(p.source) &&
                link.target === data.nodes.indexOf(p.target)
            );
            if (linkIndex >= 0) {
                d3.select(`#link-${linkIndex}`).classed('path-highlight', true);
            }
        });

        const tooltip = d3.select('#tooltip');
        tooltip.style('opacity', 1)
            .html(`<strong>${d.type.toUpperCase()}</strong>: ${d.label}`)
            .style('left', (event.pageX + 10) + 'px')
            .style('top', (event.pageY - 20) + 'px');
    }

    function handleMouseOver(event, d) {
        if (d.type === 'check') {
            d3.select(this).select('polygon')
                .attr('stroke-width', 3);
        } else {
            d3.select(this).select('rect')
                .attr('stroke-width', 3);
        }

        const tooltip = d3.select('#tooltip');
        tooltip.style('opacity', 1)
            .html(`<strong>${d.type.toUpperCase()}</strong>: ${d.label}`)
            .style('left', (event.pageX + 10) + 'px')
            .style('top', (event.pageY - 20) + 'px');
    }

    function handleMouseOut(event, d) {
        if (d.type === 'check') {
            d3.select(this).select('polygon')
                .attr('stroke-width', 2);
        } else {
            d3.select(this).select('rect')
                .attr('stroke-width', 2);
        }

        d3.select('#tooltip').style('opacity', 0);
    }

    function updateLinkPositions() {
        link.attr('d', d => {
            const sourceNode = data.nodes[d.source];
            const targetNode = data.nodes[d.target];
            // Lien en L : horizontal puis vertical
            const midX = targetNode.x;
            const midY = sourceNode.y;
            return `M${sourceNode.x},${sourceNode.y} L${midX},${midY} L${targetNode.x},${targetNode.y}`;
        });
    }

    function centerOnNode(node, customScale = 0.8) {
        if (node) {
            const transform = d3.zoomIdentity
                .translate(-node.x, 0)
                .scale(customScale);

            svg.transition()
                .duration(750)
                .call(d3.zoom().transform, transform);
        }
    }

    // Redimensionnement
    function resizeChart() {
        const container = document.getElementById('tree-container');
        const width = container.clientWidth;
        const height = container.clientHeight;

        d3.select('#tree-container svg')
            .attr('width', width)
            .attr('height', height);
    }

    window.addEventListener('resize', resizeChart);
} 