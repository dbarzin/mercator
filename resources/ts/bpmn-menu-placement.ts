// src/bpmn-menu-placement.ts
import {Graph, InternalEvent, Point, UndoManager} from "@maxgraph/core";
import {findGroupUnderMouse} from "./bpmn-parent";

type AnyGraph = any;
type AnyCell = any;

export function startPlaceVertexFollowMouse(opts: {
    graph: AnyGraph;
    undoManager: UndoManager;
    cell: AnyCell;
    container: HTMLElement;
    /** si true => Escape supprime le vertex */
    deleteOnEscape?: boolean;
    /** hauteur d’en-tête de lane (px/unités graph). 0 = pas de clamp */
    laneHeaderHeight?: number;
}) {
    const { graph, cell, undoManager } = opts;

    const wasEnabled = graph.isEnabled?.() ?? true;
    graph.setEnabled?.(false);

    // Sauve l'état précédent (important si déjà suspendu ailleurs)
    const prevSuspended = (undoManager as any).__suspended ?? false;

    const suspendUndo = () => {
        (undoManager as any).__suspended = true;
    };
    const restoreUndo = () => {
        (undoManager as any).__suspended = prevSuspended;
    };

    let placing = true;
    let undoSuspendedForDrag = false; // on ne suspend qu'au 1er mouvement

    const model = graph.getDataModel ? graph.getDataModel() : graph.model;

    const isLaneCell = (c: any) => {
        if (!c) return false;
        return c.style.baseStyleNames?.includes("lane");
    };

    const getParentCell = (c: any) => (typeof c?.getParent === "function" ? c.getParent() : c?.parent);

    const toGraphPoint = (evt: MouseEvent) => {
        const pt = graph.getPointForEvent(evt); // coordonnées "graph"
        return new Point(pt.x, pt.y);
    };

    /**
     * Convertit un point en coordonnées graph -> coordonnées locales du parent
     * (indispensable quand la cellule est enfant d'une lane).
     */
    const toParentLocalPoint = (parent: AnyCell | null, pt: Point) => {
        if (!parent) return pt;

        const view = graph.getView?.();
        const st = view?.getState?.(parent);

        // state.origin est exprimé en unités graph, donc cohérent avec getPointForEvent
        if (st?.origin) {
            return new Point(pt.x - st.origin.x, pt.y - st.origin.y);
        }
        return pt;
    };

    const clampInLaneHeader = (parent: AnyCell | null, localPt: Point) => {
        if (!parent) return localPt;
        if (!isLaneCell(parent)) return localPt;

        const h = opts.laneHeaderHeight ?? 30;
        if (!h) return localPt;

        return new Point(localPt.x, Math.max(localPt.y, h));
    };

    const moveCellTo = (graphPt: Point) => {
        const oldParent = getParentCell(cell);
        const newParent = findGroupUnderMouse(graph, graphPt) ?? oldParent;

        model.beginUpdate();
        try {
            // 1) Re-parent via le modèle (PAS cell.parent = ...)
            if (newParent && newParent !== oldParent) {
                // index à la fin (ou calcule un index si tu veux)
                const idx = (typeof newParent.getChildCount === "function")
                    ? newParent.getChildCount()
                    : (newParent.children?.length ?? 0);

                if (model.add) model.add(newParent, cell, idx);
                else if (graph.addCell) graph.addCell(cell, newParent, idx);
                // (selon ta version, l'ordre des args peut varier; l'idée reste: passer par le modèle)
            }

            // 2) Recalculer le repère à partir du parent ACTUEL (après reparent)
            const parentNow = getParentCell(cell);

            let localPt = toParentLocalPoint(parentNow, graphPt);
            localPt = clampInLaneHeader(parentNow, localPt);

            const geo = cell.getGeometry ? cell.getGeometry() : model.getGeometry?.(cell);
            if (!geo) return;

            const g2 = geo.clone ? geo.clone() : { ...geo };
            const w = g2.width ?? 40;
            const h = g2.height ?? 40;

            g2.x = localPt.x - w / 2;
            g2.y = localPt.y - h / 2;

            if (model.setGeometry) model.setGeometry(cell, g2);
            else cell.setGeometry(g2);

        } finally {
            model.endUpdate();
        }
    };

    const cleanup = () => {
        InternalEvent.removeListener(window, "mousemove", onMouseMove);
        InternalEvent.removeListener(window, "mouseup", onMouseUp);
        InternalEvent.removeListener(window, "keydown", onKeyDown);

        // Ne restaure que si on a suspendu pendant le drag
        if (undoSuspendedForDrag) restoreUndo();

        graph.setEnabled?.(wasEnabled);
        graph.setSelectionCell?.(cell);
    };

    const stop = () => {
        placing = false;
        cleanup();
    };

    const cancelPlacement = () => {
        placing = false;

        if (opts.deleteOnEscape ?? true) {
            // Si Escape doit annuler sans polluer l'historique :
            if (undoSuspendedForDrag) restoreUndo();

            model.beginUpdate();
            try {
                graph.removeCells([cell], true);
            } finally {
                model.endUpdate();
            }
        }

        cleanup();
    };

    const onMouseMove = (evt: MouseEvent) => {
        if (!placing) return;

        // Suspendre l'undo au premier mouvement (pas avant)
        if (!undoSuspendedForDrag) {
            suspendUndo();
            undoSuspendedForDrag = true;
        }

        evt.preventDefault();
        evt.stopPropagation();

        moveCellTo(toGraphPoint(evt));

    };

    const onMouseUp = (evt: MouseEvent) => {
        if (!placing) return;
        if (evt.button !== 0) return;

        evt.preventDefault();
        evt.stopPropagation();

        // Place une dernière fois
        moveCellTo(toGraphPoint(evt));

        stop();
    };

    const onKeyDown = (evt: KeyboardEvent) => {
        if (!placing) return;

        if (evt.key === "Escape") {
            evt.preventDefault();
            evt.stopPropagation();
            cancelPlacement();
        }
    };

    // IMPORTANT : on NE suspend PAS ici
    InternalEvent.addListener(window, "mousemove", onMouseMove);
    InternalEvent.addListener(window, "mouseup", onMouseUp);
    InternalEvent.addListener(window, "keydown", onKeyDown);
}
