import {Graph, KeyHandler} from '@maxgraph/core';

export function initCopyPaste(graph: Graph): void {

    let clipboard: any[] = [];

    // Écouter les événements natifs sur le conteneur ou document
    document.addEventListener('keydown', (evt: KeyboardEvent) => {
        // Vérifier que le focus est sur le graph
        if (graph.isEditing()) return;

        const ctrlKey = evt.ctrlKey || evt.metaKey;

        // Ctrl+C - Copier
        if (ctrlKey && evt.key === 'c') {
            const cells = graph.getSelectionCells();
            if (cells.length > 0) {
                clipboard = graph.cloneCells(cells);
                evt.preventDefault();
            }
        }

        // Ctrl+V - Coller
        if (ctrlKey && evt.key === 'v') {
            if (clipboard.length > 0) {
                graph.model.beginUpdate();
                try {
                    const parent = graph.getDefaultParent();
                    const pastedCells = graph.addCells(clipboard, parent);
                    graph.moveCells(pastedCells, 20, 20);
                    graph.setSelectionCells(pastedCells);
                    clipboard = graph.cloneCells(clipboard);
                } finally {
                    graph.model.endUpdate();
                }
                evt.preventDefault();
            }
        }

        // Ctrl+X - Couper
        if (ctrlKey && evt.key === 'x') {
            const cells = graph.getSelectionCells();
            if (cells.length > 0) {
                clipboard = graph.cloneCells(cells);
                graph.removeCells(cells);
                evt.preventDefault();
            }
        }
    });



}