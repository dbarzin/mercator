// src/ts/bpmn-arrows.ts

import {Cell, Geometry, Graph, InternalEvent, Point} from "@maxgraph/core";


/*
MaxGraph reprend les type de start et end Arrows: :

    none	rien
    block	flèche pleine
    open	flèche ouverte
    classic	flèche classique
    oval	cercle
    diamond	losange
    thinDiamond	losange fin

*/

export function setAnnotationArrow(graph: Graph, edge: Cell) {
    const model = graph.model;

    model.beginUpdate();
    try {
        graph.setCellStyles?.("dashed", true, [edge]);
        graph.setCellStyles?.("edgeStyle", "straightEdgeStyle", [edge]);
        graph.setCellStyles?.("startArrow", "none", [edge]);
        graph.setCellStyles?.("endArrow", "none", [edge]);
    } finally {
        model.endUpdate();
    }
}
export function setAnnotationDirectionlArrow(graph: Graph, edge: Cell) {
    const model = graph.model;

    model.beginUpdate();
    try {
        graph.setCellStyles?.("dashed", true, [edge]);
        graph.setCellStyles?.("edgeStyle", "straightEdgeStyle", [edge]);
        graph.setCellStyles?.("startArrow", "none", [edge]);
        graph.setCellStyles?.("endArrow", "classic", [edge]);
    } finally {
        model.endUpdate();
    }
}

function setOrthogonalArrow(graph: Graph, edge: Cell) {
    const model = graph.model;

    model.beginUpdate();
    try {
        graph.setCellStyles?.("dashed", false, [edge]);
        graph.setCellStyles?.("edgeStyle", "orthogonalEdgeStyle", [edge]);
        graph.setCellStyles?.("startArrow", "none", [edge]);
        graph.setCellStyles?.("endArrow", "classic", [edge]);
    } finally {
        model.endUpdate();
    }
}

export function setMessageFlow(graph: Graph, edge: Cell) {
    const model = graph.model;

    model.beginUpdate();
    try {
        graph.batchUpdate(() => {
            graph.setCellStyles("startArrow", "bpmnMessage", [edge]);
            graph.setCellStyles("startFill", "1", [edge]);
            graph.setCellStyles("startFillColor", "#FFFFFF", [edge]);
            graph.setCellStyles("startStrokeColor", "#000000", [edge]);
            graph.setCellStyles("startSize", "12", [edge]);
            graph.setCellStyles("endFillColor", "#000000", [edge]);
            graph.setCellStyles("dashed", true, [edge]);
        });
    } finally {
        model.endUpdate();
    }
}

export function setConversationFlow(graph: Graph, edge: Cell) {
    const model = graph.model;

    model.beginUpdate();
    try {
        graph.batchUpdate(() => {
            edge.style.baseStyleNames = ["bpmnConversationLink"];
        });
    } finally {
        model.endUpdate();
    }
}

export function setSequenceFlow(graph: Graph, edge: Cell) {
    graph.batchUpdate(() => {
        graph.setCellStyles("startArrow", null, [edge]);
        graph.setCellStyles("startFill", "0", [edge]);
        graph.setCellStyles("startFillColor", "#FFFFFF", [edge]);
        graph.setCellStyles("startStrokeColor", "#000000", [edge]);
        graph.setCellStyles("startSize", "12", [edge]);
        graph.setCellStyles("strokeColor", "black", [edge]);
        graph.setCellStyles("dashed", false, [edge]);
        graph.setCellStyles("endFillColor", "#000000", [edge]);
    });
}

export function setConditionalFlow(graph: Graph, edge: Cell) {
    graph.batchUpdate(() => {
        graph.setCellStyles("startArrow", "diamond", [edge]);
        graph.setCellStyles("startFill", "1", [edge]);
        graph.setCellStyles("startFillColor", "#FFFFFF", [edge]);
        graph.setCellStyles("startStrokeColor", "#000000", [edge]);
        graph.setCellStyles("startSize", "1", [edge]);
        graph.setCellStyles("dashed", false, [edge]);
        graph.setCellStyles("endFillColor", "#FFFFFF", [edge]);
    });
}

export function setDefaultFlow(graph: Graph, edge: Cell) {
    graph.batchUpdate(() => {
        graph.setCellStyles("startArrow", "bpmnSlash", [edge]);
        graph.setCellStyles("startSize", "12", [edge]);
        graph.setCellStyles("dashed", false, [edge]);
        graph.setCellStyles("endFillColor", "#000000", [edge]);
    });
}

/**
 * Cherche la cellule icône enfant d'un edge (style bpmnEventCircleIcon)
 */

function findEdgeChild(edge: Cell, styleName: string): Cell | null {
    return edge.children?.find(
        child => child.style?.baseStyleNames?.includes(styleName)
    ) ?? null;
}

export function setEventFlow(graph: Graph, edge: Cell, iconValue?: string): void {
    graph.batchUpdate(() => {
        const model = graph.getDataModel();

        // 1. Style de l'edge
        model.setStyle(edge, { baseStyleNames: ["bpmnEventFlow"] });

        const d = 40;

        // ── Cercle blanc (fond) ──────────────────────────────────────
        let bgCell = findEdgeChild(edge, "bpmnEventCircleBg");
        if (!bgCell) {
            bgCell = new Cell("", new Geometry(0, 0, d, d), { baseStyleNames: ["bpmnEventCircleBg"] });
            bgCell.vertex = true;

            const g = bgCell.getGeometry()!;
            g.relative = true;
            g.x = -1;
            g.y = 0;
            g.offset = { x: -d / 2, y: -d / 2 } as any;
            bgCell.setGeometry(g);

            model.add(edge, bgCell);
        }

        // ── Icône (par-dessus le cercle blanc) ───────────────────────
        let iconCell = findEdgeChild(edge, "bpmnEventCircleIcon");

        if (iconValue !== undefined) {
            if (!iconCell) {
                iconCell = new Cell(iconValue, new Geometry(0, 0, d, d), { baseStyleNames: ["bpmnEventCircleIcon"] });
                iconCell.vertex = true;

                const g = iconCell.getGeometry()!;
                g.relative = true;
                g.x = -1;
                g.y = 0;
                g.offset = { x: -d / 2, y: -d / 2 } as any;
                iconCell.setGeometry(g);

                model.add(edge, iconCell);
            } else {
                model.setValue(iconCell, iconValue);
            }
        } else if (iconCell) {
            model.remove(iconCell);
        }

        // 2. Edge au premier plan
        graph.orderCells(false, [edge]);
    });
}

function getEdgeTerminal(edge: any, source: boolean) {
    return source ? edge?.source : edge?.target;
}

export function installEdgeRules(graph: Graph) {
    graph.addListener(InternalEvent.CELL_CONNECTED, (_sender: any, evt: any) => {

        const edge = evt.getProperty?.("edge") ?? evt.getProperty?.("cell");
        if (!edge) return;

        const dest = getEdgeTerminal(edge, false);
        if (!dest) return;

        const src = getEdgeTerminal(edge, true);
        if (!src) return;

        if (
            src.style.baseStyleNames.includes("data")||
            dest.style.baseStyleNames.includes("data")||
            src.style.baseStyleNames.includes("database")||
            dest.style.baseStyleNames.includes("database"))
            setAnnotationDirectionlArrow(graph, edge);
        else if (
            src.style.baseStyleNames.includes("bpmnDataObject")||
            dest.style.baseStyleNames.includes("bpmnDataObject")||
            src.style.baseStyleNames.includes("annotation")||
            dest.style.baseStyleNames.includes("annotation")
        )
            setAnnotationArrow(graph, edge);
        else
            setOrthogonalArrow(graph, edge);

        // met l'edge en arrière-plan
        graph.orderCells(true, [edge]);
    });
}
