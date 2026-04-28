// src/bpmn-edit.ts
import {
    Graph,
    RubberBandHandler,
    InternalEvent,
    UndoManager,
    Client, Cell,
} from '@maxgraph/core';
import { applyGraphStyles } from './graph-styles';
import { downloadSvg, embedFontInSvg, exportGraphToSvg } from "./bpmn-svg";
import {addBPMNAnnotation, addBPMNGateway, addBPMNState, addBPMNTask} from "./bpmn-helpers";

export interface BpmnEditorContext {
    graph: Graph;
    undoManager: UndoManager;
}

export function initBpmnEditor(containerId = 'graph-container'): BpmnEditorContext {
    console.log('🎨 Initialisation du graphe MaxGraph');

    // À faire très tôt (avant création du Graph)
    Client.imageBasePath = '/dist/images';

    const container = document.getElementById(containerId) as HTMLElement | null;
    if (!container) {
        throw new Error(`#${containerId} introuvable`);
    }
    container.tabIndex = 0;

    const graph = new Graph(container);

    //=====================================================
    // Grille
    graph.gridSize = 10;
    graph.gridEnabled = true;

    // ⬇️  On applique les styles UNE FOIS ici
    applyGraphStyles(graph);

    //=====================================================

    graph.setDropEnabled(true);
    graph.setPanning(true);
    graph.setConnectable(false);
    graph.setCellsEditable(true);
    graph.setCellsResizable(true);
    graph.setCellsMovable(true);
    graph.setAllowDanglingEdges(false);
    graph.setDisconnectOnMove(false);
    graph.setSplitEnabled(false);
    graph.setHtmlLabels(true);

    // Sélection multiple
    new RubberBandHandler(graph);

    // Undo manager
    const undoManager = new UndoManager();

    // 👇 flag de suspension (custom)
    (undoManager as any).__suspended = false;

    const listener = (_sender: any, evt: any) => {
        // 👇 si suspendu => on n'enregistre rien
        if ((undoManager as any).__suspended) return;

        undoManager.undoableEditHappened(evt.getProperty('edit'));
    };
    graph.getDataModel().addListener(InternalEvent.UNDO, listener);
    graph.getView().addListener(InternalEvent.UNDO, listener);


    //=============================================================================
    // Force l'édition du label d'un stateIcon

    function isStateIconCell(graph: Graph, cell: Cell): boolean | undefined {
        if (!cell) return false;

        return cell.style.baseStyleNames?.includes("stateIcon");
        /*
        // Selon ton modèle: style objet (baseStyleNames) ou style calculé
        const s = cell.style;
        if (s && typeof s === "object" && Array.isArray(s.baseStyleNames)) {
            return s.baseStyleNames.includes("stateIcon");
        }

        const computed = graph.getCellStyle?.(cell);
        return !!computed && (
            computed.baseStyleNames?.includes?.("stateIcon") ||
            computed.styleName === "stateIcon"
        );
         */
    }

    graph.addListener(InternalEvent.DOUBLE_CLICK, (sender: any, evt: any) => {
        const cell = evt.getProperty("cell");
        if (!cell) return;

        // Hide the Menu
        hideMenu();

        // Si on double-clique l'icône => on édite le parent
        if (isStateIconCell(graph, cell)) {
            const parent = cell.parent;
            if (parent) {
                graph.startEditingAtCell(parent);
                evt.consume();
            }
        }
    });

    /*
    //==============================================================================
    // Resize uniquement pour "lane", "annotation" et "process"
    const baseIsCellResizable =
        (graph as any).isCellResizable?.bind(graph) ?? ((_: any) => true);

    (graph as any).isCellResizable = (cell: any) => {
        const names= cell.getStyle().baseStyleNames;
        console.log("NEVER CALLED !!!!!");
        if (Array.isArray(names) &&
            !names.includes("lane") &&
            !names.includes("process") &&
            !names.includes("annotation")
        )
            return false;
        return baseIsCellResizable(cell);
    };
    */

    //==============================================================================
    // Move label uniquement pour "state", "arrow" et "condition"

    const baseIsLabelMovable = graph.isLabelMovable?.bind(graph);

    graph.isLabelMovable = (cell: any) => {
        const baseStyleNames = cell?.style?.baseStyleNames;

        const isMovable =
            baseStyleNames?.includes('state') || baseStyleNames?.includes('gateway');

        return isMovable
            ? true
            : baseIsLabelMovable
                ? baseIsLabelMovable(cell)
                : false;
    };

    //==============================================================================
    // bloque le drop sur les objets sauf "lane"

    function hasLaneDestination(graph: any, destinations: any[]): boolean {
        if (!destinations?.length) return false;

        const model = graph.model;

        for (const cell of destinations) {
            if (!cell) continue;

            // optionnel : on filtre les non-vertices si le modèle sait le faire
            if (!cell.isVertex()) continue;

            if (cell.style.baseStyleNames?.includes?.("lane"))
                return true;

        }

        return false;
    }

    graph.getDropTarget = function (cells: any[], evt: MouseEvent, cell: any) {
        // cells = cellules déplacées
        // cell  = cellule sous la souris (destination potentielle)

        if (hasLaneDestination(this, [cell])) {
            return cell;   // autorisé → devient parent
        }

        return null;     // interdit → defaultParent
    };

    //==============================================================================

    const isStateIcon = (cell: any) =>
        cell?.style?.baseStyleNames?.includes?.('stateIcon') === true;

    const isTaskIcon = (cell: any) =>
        cell?.style?.baseStyleNames?.includes?.('bpmnIcon') === true;

    const isBadgeIcon = (cell: any) =>
        cell?.style?.baseStyleNames?.includes?.('bpmnBadge') === true;

    /*
    const oldIsCellMovable = graph.isCellMovable?.bind(graph);
    graph.isCellMovable = (cell: any) => {
        if (isStateIcon(cell) || isTaskIcon(cell)) return true;     // on ne déplace jamais l’icône
        return oldIsCellMovable ? oldIsCellMovable(cell) : true;
    };

    const oldIsCellSelectable = graph.isCellSelectable?.bind(graph);
    graph.isCellSelectable = (cell: any) => {
        if (isStateIcon(cell) || isTaskIcon(cell)) return false;     // on ne sélectionne jamais l’icône
        return oldIsCellSelectable ? oldIsCellSelectable(cell) : true;
    };
    */

    //==============================================================================
    // Interdit l’édition des cellules qui utilisent le style bpmnIcon
    /* Pas nécessaire car pas de sélection possible
    const prevIsCellEditable = graph.isCellEditable?.bind(graph);

    (graph as any).isCellEditable = (cell: any) => {
        const style = cell?.getStyle?.() ?? "";
        if (typeof style === "string" && style.includes("bpmnIcon")) return false;
        return prevIsCellEditable ? prevIsCellEditable(cell) : true;
    };
    */
    //==============================================================================
    // Pas de sélection des cellules qui utilisent le style bpmnIcon ou stateIcon

    const prevIsCellSelectable = graph.isCellSelectable?.bind(graph);

    (graph as any).isCellSelectable = (cell: any) => {
        if (!cell) return false;
        if (isStateIcon(cell) || isTaskIcon(cell) || isBadgeIcon(cell)) return false;
        return prevIsCellSelectable ? prevIsCellSelectable(cell) : true;
    };


    //==============================================================================
    // Désactiver le dragover - nécessaire pour autoriser le drop

    container.addEventListener('dragover', (event) => {
        event.preventDefault();
    });

    //==============================================================================
    // Drop task on the graph

    const squareIcon = document.getElementById('task-btn');

    squareIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'task-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'task-node') {

            // 1) Trouver la cellule sous la souris (en coordonnées container)
            //    offsetX/offsetY marche bien si le listener est sur le container du graph
            const dropCell = graph.getCellAt(event.offsetX, event.offsetY);

            // 2) Vérifier si c'est une lane (selon ton style)
            const isLaneCell = (cell: any) => {
                if (!cell) return false;
                return cell.style.baseStyleNames.includes("lane") ||
                    cell.style.baseStyleNames.includes("activities");
            };
            const isLane = isLaneCell(dropCell) ? dropCell : null;

            // 3) Parent cible: lane si lane, sinon parent par défaut
            const parent = isLane ?? graph.getDefaultParent();

            // 4) Position de drop (coordonnées "graph")
            const pt = graph.getPointForEvent(event);

            // 5) Si on insère dans une lane, il faut convertir la position en coordonnées locales du parent
            //    (sinon l’enfant va se retrouver "décalé" parce que pt est en coordonnées globales)
            let x = pt.x;
            let y = pt.y;

            if (isLane) {
                const parentState = graph.getView()?.getState?.(isLane);
                if (parentState?.origin) {
                    x -= parentState.origin.x;
                    y -= parentState.origin.y;
                }

                // Empêcher de dropper dans l’en-tête d’une lane
                // TODO : pas pour activities !!!
                y = Math.max(y, 30);
            }

            graph.batchUpdate(() => {
                // Conteneur
                addBPMNTask(graph, parent, x, y);
            });
        }
    });

    //==============================================================================
    // Drop state on the graph

    const stateIcon = document.getElementById('state-btn');

    stateIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'state-node');
    });

    container.addEventListener("drop", (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData("node-type") !== "state-node") return;

        // 1) Trouver la cellule sous la souris (en coordonnées container)
        //    offsetX/offsetY marche bien si le listener est sur le container du graph
        const dropCell = graph.getCellAt(event.offsetX, event.offsetY);

        // 2) Vérifier si c'est une lane (selon ton style)
        const isLaneCell = (cell: any) => {
            if (!cell) return false;
            return cell.style.baseStyleNames.includes("lane")||
                cell.style.baseStyleNames.includes("activities");
        };

        const isLane = isLaneCell(dropCell) ? dropCell : null;

        // 3) Parent cible: lane si lane, sinon parent par défaut
        const parent = isLane ?? graph.getDefaultParent();

        // 4) Position de drop (coordonnées "graph")
        const pt = graph.getPointForEvent(event);

        // 5) Si on insère dans une lane, il faut convertir la position en coordonnées locales du parent
        //    (sinon l’enfant va se retrouver "décalé" parce que pt est en coordonnées globales)
        let x = pt.x;
        let y = pt.y;

        if (isLane) {
            const parentState = graph.getView()?.getState?.(isLane);
            if (parentState?.origin) {
                x -= parentState.origin.x;
                y -= parentState.origin.y;
            }

            // Empêcher de dropper dans l’en-tête d’une lane
            y = Math.max(y, 30);
        }

        graph.batchUpdate(() => {
            addBPMNState(graph, parent, x, y);
        });
    });

    //==============================================================================
    // Drop gateway on the graph

    const condIcon = document.getElementById('gateway-btn');

    condIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'gateway-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'gateway-node') {

            // 1) Trouver la cellule sous la souris (en coordonnées container)
            //    offsetX/offsetY marche bien si le listener est sur le container du graph
            const dropCell = graph.getCellAt(event.offsetX, event.offsetY);

            // 2) Vérifier si c'est une lane (selon ton style)
            const isLaneCell = (cell: any) => {
                if (!cell) return false;
                return cell.style.baseStyleNames.includes("lane")||
                    cell.style.baseStyleNames.includes("activities");
            };

            const isLane = isLaneCell(dropCell) ? dropCell : null;

            // 3) Parent cible: lane si lane, sinon parent par défaut
            const parent = isLane ?? graph.getDefaultParent();

            // 4) Position de drop (coordonnées "graph")
            const pt = graph.getPointForEvent(event);

            // 5) Si on insère dans une lane, il faut convertir la position en coordonnées locales du parent
            //    (sinon l’enfant va se retrouver "décalé" parce que pt est en coordonnées globales)
            let x = pt.x;
            let y = pt.y;

            if (isLane) {
                const parentState = graph.getView()?.getState?.(isLane);
                if (parentState?.origin) {
                    x -= parentState.origin.x;
                    y -= parentState.origin.y;
                }

                // Empêcher de dropper dans l’en-tête d’une lane
                y = Math.max(y, 30);
            }

            // Ajouter un nouveau nœud à l'emplacement du drop
            graph.batchUpdate(() => {
                addBPMNGateway(graph, parent, x, y);
            });
        }
    });

    //==============================================================================
    // Drop data on the graph

    const dataIcon = document.getElementById('data-btn');

    dataIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'data-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'data-node') {

            // 1) Trouver la cellule sous la souris (en coordonnées container)
            //    offsetX/offsetY marche bien si le listener est sur le container du graph
            const dropCell = graph.getCellAt(event.offsetX, event.offsetY);

            // 2) Vérifier si c'est une lane (selon ton style)
            const isLaneCell = (cell: any) => {
                if (!cell) return false;
                return cell.style.baseStyleNames.includes("lane")||
                    cell.style.baseStyleNames.includes("activities");
            };

            const isLane = isLaneCell(dropCell) ? dropCell : null;

            // 3) Parent cible: lane si lane, sinon parent par défaut
            const parent = isLane ?? graph.getDefaultParent();

            // 4) Position de drop (coordonnées "graph")
            const pt = graph.getPointForEvent(event);

            // 5) Si on insère dans une lane, il faut convertir la position en coordonnées locales du parent
            //    (sinon l’enfant va se retrouver "décalé" parce que pt est en coordonnées globales)
            let x = pt.x;
            let y = pt.y;

            if (isLane) {
                const parentState = graph.getView()?.getState?.(isLane);
                if (parentState?.origin) {
                    x -= parentState.origin.x;
                    y -= parentState.origin.y;
                }

                // Empêcher de dropper dans l’en-tête d’une lane
                y = Math.max(y, 30);
            }

            // Ajouter un nouveau nœud à l'emplacement du drop
            graph.batchUpdate(() => {
                const vertex = graph.insertVertex({
                    parent,
                    value: '',
                    position: [x, y],
                    size: [60, 80],
                    style: { baseStyleNames: ['data'] },
                });

                const icon = graph.insertVertex({
                    parent: vertex,
                    value: "",
                    position: [0, 0],
                    size: [26, 26],
                    style: { baseStyleNames: ["bpmnIcon"] },
                });

            });
        }
    });

    //==============================================================================
    // Drop Lane on the graph

    const laneIcon = document.getElementById('lane-btn');

    laneIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'lane-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'lane-node') {
            // Gets drop location point for vertex
            const pt = graph.getPointForEvent(event);

            // Ajouter un nouveau nœud à l'emplacement du drop
            graph.batchUpdate(() => {

                // Ajouter le carré
                const parent = graph.getDefaultParent();

                const vertex = graph.insertVertex({
                    parent,
                    value: 'Lane',
                    position: [pt.x, pt.y],
                    size: [600, 150],
                    style: { baseStyleNames: ['lane'] },
                });

                // Mettre en arrière plan
                graph.orderCells(true, [vertex]);
            });
        }
    });

    //==============================================================================
    // Drop activities on the graph

    const activitiesIcon = document.getElementById('activities-btn');

    activitiesIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'activities-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'activities-node') {

            // 1) Trouver la cellule sous la souris (en coordonnées container)
            //    offsetX/offsetY marche bien si le listener est sur le container du graph
            const dropCell = graph.getCellAt(event.offsetX, event.offsetY);

            // 2) Vérifier si c'est une lane (selon ton style)
            const isLaneCell = (cell: any) => {
                if (!cell) return false;
                return cell.style.baseStyleNames.includes("lane");
            };

            const isLane = isLaneCell(dropCell) ? dropCell : null;

            // 3) Parent cible: lane si lane, sinon parent par défaut
            const parent = isLane ?? graph.getDefaultParent();

            // 4) Position de drop (coordonnées "graph")
            const pt = graph.getPointForEvent(event);

            // 5) Si on insère dans une lane, il faut convertir la position en coordonnées locales du parent
            //    (sinon l’enfant va se retrouver "décalé" parce que pt est en coordonnées globales)
            let x = pt.x;
            let y = pt.y;

            if (isLane) {
                const parentState = graph.getView()?.getState?.(isLane);
                if (parentState?.origin) {
                    x -= parentState.origin.x;
                    y -= parentState.origin.y;
                }

                // Empêcher de dropper dans l’en-tête d’une lane
                y = Math.max(y, 30);
            }

            // Ajouter un nouveau nœud à l'emplacement du drop
            graph.batchUpdate(() => {
                const vertex = graph.insertVertex({
                    parent,
                    value: '',
                    position: [x, y],
                    size: [300, 200],
                    style: { baseStyleNames: ['activities'] },
                });
            });
        }
    });

    //==============================================================================
    // Drop annotation on the graph

    const annotationIcon = document.getElementById('annotation-btn');

    annotationIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'annotation-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'annotation-node') {

            // Gets drop location point for vertex
            const pt = graph.getPointForEvent(event);

            graph.batchUpdate(() => {
                const parent = graph.getDefaultParent();
                addBPMNAnnotation(graph, parent, pt.x, pt.y);
            });

        }
    });


    //==============================================================================
    // Drop conversation on the graph

    const conversationIcon = document.getElementById('conversation-btn');

    conversationIcon?.addEventListener('dragstart', (event) => {
        event.dataTransfer?.setData('node-type', 'conversation-node');
    });

    container.addEventListener('drop', (event) => {
        event.preventDefault();

        if (event.dataTransfer?.getData('node-type') == 'conversation-node') {

            // Gets drop location point for vertex
            const pt = graph.getPointForEvent(event);

            graph.batchUpdate(() => {
                const parent = graph.getDefaultParent();

                // Conteneur
                const vertex = graph.insertVertex({
                    parent,
                    value: "",                 // Pas de texte pour le conteneur
                    position: [pt.x, pt.y],
                    size: [40, 40],
                    style: {baseStyleNames: ["conversation"]},
                });
            });

        }
    });

    console.log('✅ Graphe MaxGraph initialisé');
    return { graph, undoManager };
}

export function wireEditorUi(graph: Graph, undoManager: UndoManager) {

    graph.container.addEventListener('pointerdown', () => {
        (graph.container as HTMLElement).focus();
    });


    document.getElementById('download-svg')?.addEventListener('click', async () => {
        const svg = exportGraphToSvg(graph);

        await embedFontInSvg(svg, {
            fontUrl: "/vendor/mercator-bpmn/fonts/bpmn.ttf",
            fontFamily: "bpmn",
            mime: "font/ttf",
        });

        downloadSvg(svg, "bpmn-export.svg");
    });

    // Zoom
    document.getElementById('zoom-in-btn')?.addEventListener('click', () => {
        graph.zoomIn();
        console.log('🔍 Zoom in');
    });

    document.getElementById('zoom-out-btn')?.addEventListener('click', () => {
        graph.zoomOut();
        console.log('🔍 Zoom out');
    });

    document.getElementById('fit-in-btn')?.addEventListener('click', () => {
        graph.center();
        console.log('⬜ Vue ajustée');
    });

    // Boutons Undo/Redo
    const undoButton = document.getElementById('undoButton') as HTMLButtonElement | null;
    const redoButton = document.getElementById('redoButton') as HTMLButtonElement | null;

    undoButton?.addEventListener('click', () => {
        if (undoManager.canUndo()) {
            undoManager.undo();
        }
    });

    redoButton?.addEventListener('click', () => {
        if (undoManager.canRedo()) {
            undoManager.redo();
        }
    });

    graph.container.addEventListener('keydown', (event: KeyboardEvent) => {
        // Si l'utilisateur tape dans un champ, ne touche à rien
        // if (isTypingTarget(event)) return;

        // Ctrl+Z => Undo
        if (event.ctrlKey && event.key.toLowerCase() === 'z') {
            event.preventDefault();
            event.stopPropagation();
            undoManager.canUndo() && undoManager.undo();
            return;
        }

        // Ctrl+Y => Redo
        if (event.ctrlKey && event.key.toLowerCase() === 'y') {
            event.preventDefault();
            event.stopPropagation();
            undoManager.canRedo() && undoManager.redo();
            return;
        }

        // Ctrl+A => select all
        if (event.ctrlKey && event.key.toLowerCase() === 'a') {
            event.preventDefault();
            event.stopPropagation();
            graph.selectAll();
            return;
        }

        // Delete / Backspace => remove selection
        if (event.key === 'Delete' || event.key === 'Backspace') {
            // Si l'utilisateur édite un label : laisse MaxGraph gérer la touche
            if (graph.isEditing()) return;

            event.preventDefault();
            event.stopPropagation();

            const cells = graph.getSelectionCells();
            if (!cells?.length) return;

            graph.getDataModel().beginUpdate();
            try {
                graph.removeCells(cells, true);
            } finally {
                graph.getDataModel().endUpdate();
            }
        }
    });

    console.log('✅ UI d’édition câblée');
}

// Hide the action select menu
export function hideMenu() {
    const menuEl = document.getElementById("vertex-menu")!;
    menuEl.classList.add("hidden");
}

export function enableArrowKeyMovement(graph: Graph, step = 10) {

    function moveSelectedVertex(graph: Graph, dx: number, dy: number) {
        const cells = graph.getSelectionCells();

        if (!cells || cells.length === 0) return;

        graph.batchUpdate(() => {
            for (const cell of cells) {
                if (!cell?.isVertex?.()) continue;

                const geo = cell.getGeometry();
                if (!geo) continue;

                // IMPORTANT : cloner la géométrie
                const newGeo = geo.clone();
                newGeo.translate(dx, dy);

                graph.model.setGeometry(cell, newGeo);
            }
        });
    }

    graph.container.addEventListener('keydown', (event: KeyboardEvent) => {
        // Si l'utilisateur édite un label : laisse MaxGraph gérer la touche
        if (graph.isEditing()) return;


        const step = 1;
        switch (event.key) {
            case 'ArrowUp':
                hideMenu()
                moveSelectedVertex(graph, 0, -step);
                event.preventDefault();
                break;
            case 'ArrowDown':
                hideMenu()
                moveSelectedVertex(graph, 0, step);
                event.preventDefault();
                break;
            case 'ArrowLeft':
                hideMenu()
                moveSelectedVertex(graph, -step, 0);
                event.preventDefault();
                break;
            case 'ArrowRight':
                hideMenu()
                moveSelectedVertex(graph, step, 0);
                event.preventDefault();
                break;
        }
    });

}


export function showStatus(message: string, duration = 2000) {
    const status = document.getElementById('status');
    if (!status) return;

    status.textContent = message;
    status.classList.add('show');

    setTimeout(() => {
        status.classList.remove('show');
    }, duration);
}

/* not used
// Check taget is not an input, textarea or select
function isTypingTarget(ev: KeyboardEvent): boolean {
    const el = ev.target as HTMLElement | null;
    if (!el) return false;

    // Remonte au vrai champ si tu as des wrappers
    const field = el.closest('input, textarea, select, [contenteditable="true"]') as HTMLElement | null;
    return !!field;
}
*/