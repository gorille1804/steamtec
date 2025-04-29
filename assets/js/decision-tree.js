import * as joint from 'jointjs';

class DecisionTree {
    constructor(containerId, data) {
        this.container = document.getElementById(containerId);
        this.data = data;
        this.graph = new joint.dia.Graph();
        this.paper = new joint.dia.Paper({
            el: this.container,
            model: this.graph,
            width: '100%',
            height: 1000,
            gridSize: 10,
            drawGrid: true,
            background: {
                color: '#f8f9fa'
            }
        });

        this.init();
    }

    init() {
        // Définir les styles des éléments
        const elementStyle = {
            fill: '#222', // fond sombre
            stroke: '#fff',
            strokeWidth: 2,
        };

        const linkStyle = {
            stroke: '#fff',
            strokeWidth: 2,
            targetMarker: {
                type: 'path',
                d: 'M 10 -5 0 0 10 5 Z'
            }
        };

        // Créer les éléments de l'arbre
        const elements = {};
        this.data.nodes.forEach(node => {
            const shapeType = node.type === 'check' ? 'diamond' : 'rect';
            let shape;
            if (shapeType === 'diamond') {
                shape = new joint.shapes.standard.Polygon();
                shape.attr('body/refPoints', '90,0 180,60 90,120 0,60'); // diamond
                shape.resize(140, 140);
            } else {
                shape = new joint.shapes.standard.Rectangle();
                shape.resize(220, 80);
            }
            shape.position(node.x, node.y);
            shape.attr({
                body: elementStyle,
                label: {
                    text: node.label,
                    fill: '#fff', // texte blanc
                    fontSize: 22,
                    fontWeight: 'bold',
                    fontFamily: 'Arial, sans-serif',
                    textWrap: {
                        width: shapeType === 'diamond' ? 120 : 200,
                        height: shapeType === 'diamond' ? 120 : 60,
                        ellipsis: true
                    },
                    refY: '50%',
                    yAlignment: 'middle',
                    textShadow: '1px 1px 2px #000'
                }
            });
            elements[node.id] = shape;
            this.graph.addCell(shape);
        });

        // Créer les liens entre les éléments
        this.data.links.forEach(link => {
            const connection = new joint.shapes.standard.Link({
                source: { id: elements[link.source].id },
                target: { id: elements[link.target].id },
                labels: [{
                    position: 0.5,
                    attrs: {
                        text: {
                            text: link.label || '',
                            fill: '#fff',
                            fontSize: 18,
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif',
                            textShadow: '1px 1px 2px #000'
                        }
                    }
                }],
                attrs: linkStyle,
                router: { name: 'orthogonal' },
                connector: { name: 'rounded' }
            });
            this.graph.addCell(connection);
        });

        // Centrer et zoomer le diagramme
        this.centerContent();
    }

    centerContent() {
        const bbox = this.paper.getContentBBox();
        const paperWidth = this.paper.el.clientWidth;
        const paperHeight = this.paper.el.clientHeight;

        if (bbox.width === 0 || bbox.height === 0) return;

        // Calcul du facteur de zoom optimal (sans limitation à 1)
        const scale = Math.min(
            (paperWidth - 40) / bbox.width,
            (paperHeight - 40) / bbox.height
        );

        this.paper.scale(scale);

        // Centrer le contenu
        const tx = (paperWidth - bbox.width * scale) / 2 - bbox.x * scale;
        const ty = (paperHeight - bbox.height * scale) / 2 - bbox.y * scale;
        this.paper.translate(tx, ty);
    }
}

// Exporter la classe pour l'utiliser dans d'autres fichiers
export default DecisionTree; 