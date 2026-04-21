// src/bpmn-menu.ts

import type {Graph, Cell, UndoManager} from "@maxgraph/core";

export type VertexActionId =
    | "delete"
    | "connect"
    | "color"
    | "add-state"
    | "add-task"
    | "add-gateway"
    | "add-annotations"
    | "search"
    | "rotate"
;

export interface ActionContext {
    graph: Graph;
    undoManager: UndoManager;
    cell: Cell;
    parent: Cell;
    menuEl: HTMLElement;
    event?: MouseEvent;
}

export type VertexActionHandler = (ctx: ActionContext) => void;

export interface VertexMenuController {
    destroy(): void;
    getCurrentCell(): Cell | null;
    showForCell(cell: Cell): void;
    hide(): void;
}
