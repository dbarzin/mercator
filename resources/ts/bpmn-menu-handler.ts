// src/bpmn-menu-handlers.ts
import {Cell, CellStyle, ConnectionHandler, InternalEvent, UndoManager} from "@maxgraph/core";
import type { VertexActionHandler, VertexActionId } from "./bpmn-menu";
import { startPlaceVertexFollowMouse } from "./bpmn-menu-placement";
import {addBPMNAnnotation, addBPMNConnection, addBPMNGateway, addBPMNState, addBPMNTask} from "./bpmn-helpers";

export function makeDefaultHandlers(): Record<VertexActionId, VertexActionHandler> {
    return {
        "delete": ({ graph, cell }) => {
            graph.model.beginUpdate();
            try {
                graph.removeCells([cell], true);
            } finally {
                graph.model.endUpdate();
            }
        },
        "connect": ({ graph, cell, menuEl }) => {
            menuEl.classList.add("hidden");
            graph.setConnectable(true);

            const ch = ((graph as any).connectionHandler ?? graph.getPlugin("ConnectionHandler")) as any;

            // ✅ on définit le factoryMethod (c’est LUI qui est utilisé pour créer l’edge)
            const prevFactory = ch.factoryMethod;

            ch.factoryMethod = (source: any, target: any, _styleFromPreview?: any) => {
                const edge = addBPMNConnection(graph, source, target);
                return edge;
            };

            // (optionnel mais conseillé) restore après UNE connexion
            const restore = () => {
                ch.factoryMethod = prevFactory;
                ch.removeListener?.(restoreListener);
            };
            const restoreListener = () => restore();
            ch.addListener?.(InternalEvent.CONNECT, restoreListener);
            ch.addListener?.(InternalEvent.RESET, restoreListener); // reset si annulation

            const state = graph.view.getState(cell as any);
            if (!state) return;

            ch.start(state, state.x + state.width, state.y + state.height / 2);
        },


        "color": ({ graph, cell, menuEl }) => {
            const swatch = menuEl.closest<HTMLElement>("[data-color]");
            const color = swatch?.dataset.color;
            if (!color || !cell) return;

            const model = graph.model;

            model.beginUpdate();
            try {
                if (cell.isEdge()) {
                    // Edge → couleur du trait
                    graph.setCellStyles("strokeColor", color, [cell]);
                    // Couleur des flèches
                    graph.setCellStyles("endFill", true, [cell]);
                    graph.setCellStyles("endStrokeColor", color, [cell]);
                } else {
                    // console.log("Colorize ",cell, color)
                    if (cell.style.baseStyleNames?.includes("stateIcon")||
                        cell.style.baseStyleNames?.includes("bpmnBadge")) {
                        // The icon is selected, not the vertex itself.
                        if (cell.parent!=null)
                            cell = cell.parent;
                    }
                    graph.setCellStyles("fillColor", color, [cell]);
                }
            } finally {
                model.endUpdate();
            }

        },
        "add-task": ({ graph, undoManager, parent, cell, menuEl, event }) => {
            if (!event) return;

            const model = graph.getDataModel ? graph.getDataModel() : graph.model;
            model.beginUpdate();
            try {
                // disable selection
                graph.clearSelection();

                const p = graph.getPointForEvent(event);

                const vertex = addBPMNTask(graph, parent, p.x, p.y);

                addBPMNConnection(graph, cell, vertex);

                startPlaceVertexFollowMouse({ graph, undoManager, cell: vertex, container: graph.container });

                menuEl.classList.add("hidden");
            } finally {
                model.endUpdate();
            }
        },

        "add-state": ({ graph, undoManager, parent, cell, menuEl, event }) => {
            if (!event) return;

            console.log('add-state', parent, cell);

            const model = graph.getDataModel ? graph.getDataModel() : graph.model;
            model.beginUpdate();
            try {
                // disable selection
                graph.clearSelection();

                const p = graph.getPointForEvent(event);

                const vertex = addBPMNState(graph, parent, p.x, p.y);

                addBPMNConnection(graph, cell, vertex);

                startPlaceVertexFollowMouse(
                    { graph, undoManager, cell: vertex, container: graph.container });
                menuEl.classList.add("hidden");
            } finally {
                model.endUpdate();
            }
        },

        "add-gateway": ({ graph, undoManager, parent, cell, menuEl, event }) => {
            if (!event) return;

            const model = graph.getDataModel ? graph.getDataModel() : graph.model;
            model.beginUpdate();
            try {
                const p = graph.getPointForEvent(event);

                // disable selection
                graph.clearSelection();

                const vertex = addBPMNGateway(graph, parent, p.x, p.y)

                addBPMNConnection(graph, cell, vertex);

                startPlaceVertexFollowMouse(
                    { graph, undoManager, cell: vertex, container: graph.container });
                menuEl.classList.add("hidden");
            } finally {
                model.endUpdate();
            }
        },
        "add-annotations": ({ graph, undoManager, parent, cell, menuEl, event }) => {
            if (!event) return;

            const model = graph.getDataModel ? graph.getDataModel() : graph.model;
            model.beginUpdate();
            try {
                const pt = graph.getPointForEvent(event);

                // disable selection
                graph.clearSelection();

                const vertex = addBPMNAnnotation(graph, parent, pt.x, pt.y);

                const edge = addBPMNConnection(graph, cell, vertex);
                edge.style.baseStyleNames = ["bpmn-edge"];

                startPlaceVertexFollowMouse(
                    { graph, undoManager, cell: vertex, container: graph.container });
                menuEl.classList.add("hidden");
            } finally {
                model.endUpdate();
            }
        },
        "search":  ({ graph, undoManager, parent, cell, menuEl, event }) => {
            console.log('search');
        },
        "rotate": ({ graph, cell, menuEl }) => {
            graph.model.beginUpdate();
            try {
                if (cell.style.horizontal)
                    graph.setCellStyles("horizontal", false, [cell]);
                else
                    graph.setCellStyles("horizontal", true, [cell]);
            } finally {
                graph.model.endUpdate();
            }

            menuEl.classList.add("hidden");
        },
    };
}
