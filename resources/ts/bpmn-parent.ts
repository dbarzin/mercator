// src/bpmn-parent.ts
// =============================================
// Gestion du drop sur un parent activities

import {Graph, InternalEvent, Point} from "@maxgraph/core";

type AnyGraph = any;
type AnyCell = any;

const isGroup = (cell: AnyCell): boolean => {
    if (!cell) return false;

    const st = cell?.style;
    const bs = st?.baseStyleNames;
    return bs?.includes("activities") || bs?.includes("lane");
};

export function findGroupUnderMouse(graph: Graph, pt: Point): AnyCell | null  {
    // Cell sous le pointeur
    let under: AnyCell | null =
        typeof graph.getCellAt === "function" ? graph.getCellAt(pt.x-1, pt.y-1) : null;
    // Important : parfois "under" est un enfant interne / ou même une des cells déplacées
    // -> on remonte la hiérarchie jusqu’à trouver un parent activities
    while (under) {
        if (isGroup(under)) {
            return under;
        }
        under = typeof under.getParent === "function" ? under.getParent() : under.parent;
    }

    return null;
}


export function installDropInActivitiesParent(graph: AnyGraph) {
    let reparenting = false;


    graph.addListener(InternalEvent.MOVE_CELLS, (_sender: any, e: any) => {
        if (reparenting) return;

        const evt: MouseEvent | null = e.getProperty?.("event") ?? null;
        const cells: AnyCell[] = e.getProperty?.("cells") ?? [];

        if (!evt || !cells.length) return;

        const target = findGroupUnderMouse(graph, graph.getPointForEvent(evt));
        if (!target) return;

        // Si déjà au bon parent, rien à faire
        const curParent =
            typeof cells[0].getParent === "function" ? cells[0].getParent() : cells[0].parent;
        if (curParent === target) return;

        reparenting = true;
        try {
            graph.batchUpdate(() => {
                // dx=0 dy=0, clone=false, target=activities
                graph.moveCells(cells, 0, 0, false, target, evt);
            });
        } finally {
            reparenting = false;
        }
    });
}
