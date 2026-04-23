// src/bpmn-show.ts
import { initBpmnEditor } from './bpmn-edit';
import { installEdgeRules } from "./bpmn-arrows";
import { Graph, InternalEvent, ShapeRegistry } from "@maxgraph/core";
import { BpmnDataObjectShape } from "./bpmn-shapes";
import { exposeGraphHelpers } from "./bpmn-save";

console.log("🚀 Initialisation de BPMN");

// Init éditeur (graph + undo/redo)
const { graph, undoManager } = initBpmnEditor('graph-container');

// Dashed edges for database vertices
installEdgeRules(graph);

// Register shapes
ShapeRegistry.add("bpmnDataObject", BpmnDataObjectShape);

// Pour load/save
exposeGraphHelpers(graph);

// Disable graph for show mode
graph.setEnabled(false);
graph.setCellsSelectable(true);

// Zoom à la molette
InternalEvent.addMouseWheelListener((evt, up) => {
    const step = 0.1;
    if (up) graph.zoomIn();
    else graph.zoomOut();
    InternalEvent.consume(evt);
}, graph.container);

// Cursor sur les objets
graph.container.addEventListener("mousemove", (e) => {
    const rect = graph.container.getBoundingClientRect();
    let mouseEvent = e as MouseEvent;
    const x = mouseEvent.clientX - rect.left;
    const y = mouseEvent.clientY - rect.top;

    const cell = graph.getCellAt(x, y);
    if (typeof cell === "object" && cell?.url) {
        graph.container.style.cursor = "pointer";
    } else {
        graph.container.style.cursor = "default";
    }
});

// Click sur les objects avec une URL
graph.addListener(InternalEvent.CLICK, (_sender: any, evt: { getProperty: (arg0: string) => any; }) => {
    const cell = evt.getProperty("cell");

    if (!cell || !cell.isVertex()) return;

    if (cell.url) {
        window.location.href = cell.url;
    }
});

// Fonction pour ajuster la hauteur du conteneur
function adjustContainerSize(graph: Graph, padding: number = 30): void {
    const bounds = graph.getGraphBounds();
    const container = graph.container;

    if (bounds && container) {
        // Calculer la hauteur nécessaire avec padding
        const requiredHeight = bounds.y + bounds.height + padding;
        const finalHeight = Math.max(200, requiredHeight);

        // Calculer la largeur nécessaire avec padding
        // const requiredWidth = bounds.x + bounds.width + padding;
        // const finalWidth = Math.max(600, requiredWidth);
        const finalWidth = '100';

        container.style.height = `${finalHeight}px`;
        container.style.width = `${finalHeight}%`;

        console.log(`📏 Container size adjusted to: ${finalWidth}% x ${finalHeight}px (content: ${bounds.width}px x ${bounds.height}px)`);
    }
}

// Wrapper pour la fonction loadGraph existante
const originalLoadGraph = (window as any).loadGraph;

if (originalLoadGraph) {
    console.log('✅ loadGraph existante trouvée, ajout de l\'ajustement automatique de hauteur');

    (window as any).loadGraph = function(xmlContent: string) {
        // Appeler la fonction originale
        originalLoadGraph(xmlContent);

        // Ajuster la hauteur après chargement
        setTimeout(() => {
            adjustContainerSize(graph, 30);
        }, 150);
    };
} else {
    console.warn('⚠️ Aucune fonction loadGraph trouvée dans exposeGraphHelpers');
}


console.log('✅ Affichage BPMN initialisé et prêt');