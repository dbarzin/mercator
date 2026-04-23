// src/bpmn-helpers.ts

// Functions to draw BPMN elements with MaxGraph

// Add a state element at x.y
import {Cell, Graph} from "@maxgraph/core";
import {BPMN_ICONS} from "./bpmn-icons";
import {setConversationFlow} from "./bpmn-arrows";

export function addBPMNState(graph: Graph, parent: Cell, x: number, y: number): Cell {
    const vertex = graph.insertVertex({
        parent,
        value: "",
        position: [x, y],
        size: [40, 40],
        style: { baseStyleNames: ["state"] },
    });

    const icon = graph.insertVertex({
        parent: vertex,
        value: BPMN_ICONS.START_EVENT,
        position: [0, 0],
        size: [40, 40],
        style: { baseStyleNames: ["stateIcon"] },
    });

    const g = icon.getGeometry();
    if (g) {
        g.relative = true;
        g.x = 0.5;
        g.y = 0.5;
        g.offset = { x: -20, y: -20 } as any;
        icon.setGeometry(g);
    }
    return vertex;
}

export function addBPMNTask(graph: Graph, parent: Cell, x: number, y: number): Cell {
    const vertex = graph.insertVertex({
        parent,
        value: "",                 // Pas de texte pour le conteneur
        position: [x, y],
        size: [100, 80],
        style: { baseStyleNames: ["process"] },
    });

    // Icône (enfant)
    const icon = graph.insertVertex({
        parent: vertex,            // IMPORTANT : enfant du vertex => suit ses déplacements
        value: "",
        position: [0, 0],
        size: [26, 26],            // taille fixe de la “boîte” icône (ne scale pas)
        style: { baseStyleNames: ["bpmnIcon"] },
    });

    // Position relative + offset (top-left avec petite marge)
    const g = icon.getGeometry();
    if (g) {
        g.relative = true;         // ancrage relatif au parent
        g.x = 0;                   // coin gauche
        g.y = 0;                   // coin haut
        g.offset = { x: 0, y: -2 } as any; // marge interne
        icon.setGeometry(g);
    }

    return vertex;

}

export function addBPMNGateway(graph: Graph, parent: Cell, x: number, y: number): Cell {

    const vertex = graph.insertVertex({
        parent,
        value: '',
        position: [x, y],
        size: [40, 40],
        style: { baseStyleNames: ['gateway'] },
    });

    const icon = graph.insertVertex({
        parent: vertex,
        value: BPMN_ICONS.GATEWAY,
        position: [0, 0],
        // taille fixe de la “boîte” icône (ne scale pas)
        size: [45, 45],
        style: { baseStyleNames: ["stateIcon"] },
    });

    const g = icon.getGeometry();
    if (g) {
        g.relative = true;
        // centre du parent
        g.x = 0.5;
        g.y = 0.5;
        // offset = -½ taille icône
        g.offset = { x: -23, y: -23, } as any;
        icon.setGeometry(g);
    }

    return vertex;

}

export function addBPMNAnnotation(graph: Graph, parent: Cell, x: number, y: number): Cell {

    // Conteneur
    const vertex = graph.insertVertex({
        parent,
        value: "",
        position: [x, y],
        size: [100, 80],
        style: { baseStyleNames: ["annotation"] },
    });

    graph.setCellStyles("fillColor", "#FFFFFF", [vertex]);

    return vertex;

    }

export function addBPMNConnection(graph: Graph, source: Cell, target: Cell) : Cell {
    const edge= graph.insertEdge(
        {
            parent: graph.getDefaultParent(),
            source,
            target,
            style: { baseStyleNames: ["bpmn-edge"] },
        });
    if (isConversationVertex(graph, source) || isConversationVertex(graph, target))
        setConversationFlow(graph, edge);
    return edge;
    }

//===================================

export function isProcessVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "process");
}
export function isStateVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "state");
}

export function isGatewayVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "gateway");
}

export function isActivitiesVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "activities");
}

export function isDataVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "data") || cellHasBaseStyle(cell, "database");
}

export function isLaneVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "lane");
}


export function isConversationVertex(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "conversation");
}
function isEdge(graph: Graph, cell: Cell): boolean {
    if (!cell) return false;
    return cellHasBaseStyle(cell, "process");
}

//=================================
// ICONS

function getBaseStyleNames(cell: Cell): string[] {
    const s = cell?.style;
    if (s && typeof s === "object" && Array.isArray(s.baseStyleNames)) return s.baseStyleNames;
    return [];
}

function cellHasBaseStyle(cell: Cell, baseStyle: string): boolean {
    return getBaseStyleNames(cell).includes(baseStyle);
}

function findIconChild(graph: Graph, processVertex: Cell): Cell | null {
    const model = graph.model;
    if (!model || !processVertex) return null;
    const childCount = processVertex.getChildCount();
    for (let i = 0; i < childCount; i++) {
        const child = processVertex.getChildAt(i);
        if (!child) continue;
        if (
            cellHasBaseStyle(child, "bpmnIcon") ||
            cellHasBaseStyle(child, "stateIcon")
        ) return child;
    }
    return null;
}

export function setIconCellValue(graph: Graph, processVertex: Cell, value: any) {
    console.log(`setIconCellValue(${value}) `, processVertex);
    const model = graph.model;
    if (!model) return;
    const iconCell = findIconChild(graph, processVertex);
    if (!iconCell) return;
    graph.batchUpdate(() => {
        model.setValue(iconCell, value);
    });
}

export function setDatabaseVertex(graph: Graph, cell: Cell) {
    const model = graph.model;
    if (!model) return;
    const style = cell.getClonedStyle();
    style.baseStyleNames = ["database"];
    const iconCell = findIconChild(graph, cell);
    if (!iconCell) return;
    graph.batchUpdate(() => {
        graph.setCellStyle(style, [cell]);
        model.setValue(iconCell, "");
    });
}

export function setDataVertex(graph: Graph, cell: Cell) {
    const model = graph.model;
    if (!model) return;
    const style = cell.getClonedStyle();
    style.baseStyleNames = ["data"];
    const iconCell = findIconChild(graph, cell);
    if (!iconCell) return;
    graph.batchUpdate(() => {
        graph.setCellStyle(style, [cell]);
        model.setValue(iconCell, "");
    });
}

export function setInputDataVertex(graph: Graph, cell: Cell) {
    const model = graph.model;
    if (!model) return;
    const style = cell.getClonedStyle();
    style.baseStyleNames = ["data"];
    const iconCell = findIconChild(graph, cell);
    if (!iconCell) return;
    graph.batchUpdate(() => {
        graph.setCellStyle(style, [cell]);
        model.setValue(iconCell, BPMN_ICONS.DATA_INPUT);
    });
}

export function setOutputDataVertex(graph: Graph, cell: Cell) {
    const model = graph.model;
    if (!model) return;
    const style = cell.getClonedStyle();
    style.baseStyleNames = ["data"];
    const iconCell = findIconChild(graph, cell);
    if (!iconCell) return;
    graph.batchUpdate(() => {
        graph.setCellStyle(style, [cell]);
        model.setValue(iconCell, BPMN_ICONS.DATA_OUTPUT);
    });
}
