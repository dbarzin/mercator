import {
    Cell,
    CellEditorHandler,
    eventUtils,
    FastOrganicLayout,
    Graph,
    GraphDataModel,
    type GraphPluginConstructor,
    InternalEvent,
    ModelXmlSerializer,
    Morphing,
    PanningHandler,
    RubberBandHandler,
    SelectionCellsHandler,
    SelectionHandler,
    styleUtils,
    UndoManager,
    VertexHandlerConfig,
} from '@maxgraph/core';

//-----------------------------------------------------------------------
// Interfaces métier

interface Edge {
    attachedNodeId: string;
    name: string;
    edgeType: string;
    edgeDirection: string;
    bidirectional: boolean;
}

interface Node {
    id: string;
    vue: string;
    label: string;
    image: string;
    type: string;
    edges: Edge[];
}

type NodeMap = Map<string, Node>;

// Déclaration de globals fournies par ailleurs
declare const _nodes: NodeMap;
declare const $: any;

//-----------------------------------------------------------------------
// Plugins MaxGraph

const plugins: GraphPluginConstructor[] = [
    // L'ordre est important
    CellEditorHandler,
    SelectionCellsHandler,
    SelectionHandler,
    PanningHandler,
    RubberBandHandler,
];

// Initialisation du graph

const container = document.getElementById('graph-container') as HTMLDivElement | null;
if (!container) {
    throw new Error('#graph-container introuvable');
}

const graph = new Graph(container, new GraphDataModel(), plugins);
const model = graph.getDataModel();

//-----------------------------------------------------------------------
// Style des arêtes

const edgeDefaultStyle = graph.getStylesheet().getDefaultEdgeStyle();
edgeDefaultStyle.labelBackgroundColor = '#FFFFFF';
edgeDefaultStyle.strokeWidth = 2;
edgeDefaultStyle.rounded = true;
edgeDefaultStyle.entryPerimeter = false;
edgeDefaultStyle.edgeStyle = 'manhattanEdgeStyle';

// Désactiver le folding
(graph as any).getFoldingImage = () => null;

// Sélection des sommets
VertexHandlerConfig.selectionColor = '#00a8ff';
VertexHandlerConfig.selectionStrokeWidth = 2;

//-----------------------------------------------------------------------
// Undo / Redo

const undoManager = new UndoManager();
const undoListener = (_sender: unknown, evt: any) => {
    const edit = evt.getProperty('edit');
    if (edit) {
        undoManager.undoableEditHappened(edit);
    }
};

model.addListener(InternalEvent.UNDO, undoListener);
graph.getView().addListener(InternalEvent.UNDO, undoListener);

const undoButton = document.getElementById('undoButton') as HTMLButtonElement | null;
const redoButton = document.getElementById('redoButton') as HTMLButtonElement | null;

if (undoButton) {
    undoButton.addEventListener('click', () => {
        if (undoManager.canUndo()) {
            undoManager.undo();
        }
    });
}

if (redoButton) {
    redoButton.addEventListener('click', () => {
        if (undoManager.canRedo()) {
            undoManager.redo();
        }
    });
}

// Raccourcis clavier Undo / Redo
document.addEventListener('keydown', (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'z') {
        event.preventDefault();
        if (undoManager.canUndo()) {
            undoManager.undo();
        }
    } else if (event.ctrlKey && event.key === 'y') {
        event.preventDefault();
        if (undoManager.canRedo()) {
            undoManager.redo();
        }
    }
});

// --------------------------------------------------------------------------------
// Menus contextuels

const edgeContextMenu = document.getElementById('edge-context-menu') as HTMLDivElement | null;
const edgeColorSelect = document.getElementById('edge-color-select') as HTMLInputElement | null;
const thicknessSelect = document.getElementById('edge-thickness-select') as HTMLSelectElement | null;

const textContextMenu = document.getElementById('text-context-menu') as HTMLDivElement | null;
const textFontSelect = document.getElementById('text-font-select') as HTMLSelectElement | null;
const textColorSelect = document.getElementById('text-color-select') as HTMLInputElement | null;
const textSizeSelect = document.getElementById('text-size-select') as HTMLSelectElement | null;
const textBoldSelect = document.getElementById('text-bold-select') as HTMLButtonElement | null;
const textItalicSelect = document.getElementById('text-italic-select') as HTMLButtonElement | null;
const textUnderlineSelect = document.getElementById('text-underline-select') as HTMLButtonElement | null;

let selectedCell: Cell | null = null;

graph.container.addEventListener('contextmenu', (event: MouseEvent) => {
    event.preventDefault();

    const cell = graph.getCellAt(event.offsetX, event.offsetY) as Cell | null;
    if (!cell) return;

    if (!edgeContextMenu || !textContextMenu) return;

    const rect = container.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const currentStyle = graph.getCellStyle(cell) as any;

    if (cell.isEdge()) {
        selectedCell = cell;

        edgeContextMenu.style.display = 'block';
        edgeContextMenu.style.left = `${x + 75}px`;
        edgeContextMenu.style.top = `${y + 100}px`;

        if (edgeColorSelect && thicknessSelect) {
            edgeColorSelect.value = currentStyle.strokeColor || '#000000';
            thicknessSelect.value = String(currentStyle.strokeWidth ?? '1');
        }

        textContextMenu.style.display = 'none';
    } else if (cell.isVertex()) {
        // Vertex avec label editable
        const cellValue = cell.value as string | null;
        const hasText = !!cellValue && cellValue.trim() !== '';

        const style = cell.style as any;

        if (hasText && textColorSelect && textFontSelect && textSizeSelect) {
            selectedCell = cell;

            textContextMenu.style.display = 'block';
            textContextMenu.style.left = `${x + 75}px`;
            textContextMenu.style.top = `${y + 100}px`;

            edgeContextMenu.style.display = 'none';

            textColorSelect.value = currentStyle.fontColor || '#000000';
            textFontSelect.value = currentStyle.fontFamily || 'Arial';
            textSizeSelect.value = String(currentStyle.fontSize ?? '12');

            const fontStyle = style?.fontStyle ?? 0;

            if (textBoldSelect) {
                if (fontStyle & 1) textBoldSelect.classList.add('selected');
                else textBoldSelect.classList.remove('selected');
            }
            if (textItalicSelect) {
                if (fontStyle & 2) textItalicSelect.classList.add('selected');
                else textItalicSelect.classList.remove('selected');
            }
            if (textUnderlineSelect) {
                if (fontStyle & 4) textUnderlineSelect.classList.add('selected');
                else textUnderlineSelect.classList.remove('selected');
            }
        } else if (!style?.image && (!cell.children || cell.children.length === 0)) {
            // Vertex "simple" → menu arête (couleur/bordure)
            selectedCell = cell;

            edgeContextMenu.style.display = 'block';
            edgeContextMenu.style.left = `${x + 75}px`;
            edgeContextMenu.style.top = `${y + 100}px`;

            if (edgeColorSelect && thicknessSelect) {
                edgeColorSelect.value = currentStyle.strokeColor || '#000000';
                thicknessSelect.value = String(currentStyle.strokeWidth ?? '1');
            }

            textContextMenu.style.display = 'none';
        } else {
            textContextMenu.style.display = 'none';
            edgeContextMenu.style.display = 'none';
        }
    } else {
        textContextMenu.style.display = 'none';
        edgeContextMenu.style.display = 'none';
    }
});

// Appliquer style arête / vertex

document.getElementById('apply-edge-style')?.addEventListener('click', (e) => {
    e.preventDefault();

    if (!selectedCell || !edgeColorSelect || !thicknessSelect) return;

    graph.batchUpdate(() => {
        const style = (selectedCell?.style ?? {}) as any;
        const thickness = parseInt(thicknessSelect.value, 10) || 1;

        if (selectedCell?.isEdge()) {
            style.strokeColor = edgeColorSelect.value;
            style.strokeWidth = thickness;
        } else {
            style.fillColor = edgeColorSelect.value;
            style.strokeWidth = thickness;
        }

        selectedCell.style = style;
        graph.refresh(selectedCell);
    });

    if (edgeContextMenu) edgeContextMenu.style.display = 'none';
});

// Appliquer style texte

document.getElementById('apply-text-style')?.addEventListener('click', (e) => {
    e.preventDefault();

    if (!selectedCell || !textFontSelect || !textColorSelect || !textSizeSelect) return;

    graph.batchUpdate(() => {
        const style = (selectedCell?.style ?? {}) as any;

        style.fontFamily = textFontSelect.value;
        style.fontColor = textColorSelect.value;
        style.fontSize = parseInt(textSizeSelect.value, 10) || 12;

        let flag = 0;
        if (textBoldSelect?.classList.contains('selected')) flag |= 1;
        if (textItalicSelect?.classList.contains('selected')) flag |= 2;
        if (textUnderlineSelect?.classList.contains('selected')) flag |= 4;

        style.fontStyle = flag;

        if (selectedCell) {
            selectedCell.style = style;
            graph.refresh(selectedCell);
        }
        graph.refresh(selectedCell);
    });

    if (textContextMenu) textContextMenu.style.display = 'none';
});

// Boutons avec classe .button → toggle "selected"

document
    .querySelectorAll<HTMLButtonElement>('.button')
    .forEach((button) => {
        button.addEventListener('click', () => {
            button.classList.toggle('selected');
        });
    });

// Cacher les menus contextuels en cliquant ailleurs

document.addEventListener('click', (event) => {
    const target = event.target as Node | null;

    if (textContextMenu && !textContextMenu.contains(target as Node)) {
        textContextMenu.style.display = 'none';
    }
    if (edgeContextMenu && !edgeContextMenu.contains(target as Node)) {
        edgeContextMenu.style.display = 'none';
    }
});

// --------------------------------------------------------------------------------
// Grille

graph.setGridEnabled(true);
graph.setGridSize(10);

container.style.backgroundImage = `
  linear-gradient(to right, #e0e0e0 1px, transparent 1px),
  linear-gradient(to bottom, #e0e0e0 1px, transparent 1px)
`;
container.style.backgroundSize = '10px 10px';

// -----------------------------------------------------------------------
// Panning

graph.setPanning(true);
(graph as any).allowAutoPanning = true;
(graph as any).useScrollbarsForPanning = true;

//-------------------------------------------------------------------------
// LOAD / SAVE

export function loadGraph(xml: string) {
    new ModelXmlSerializer(model).import(xml);
}

(window as any).loadGraph = loadGraph;

async function saveGraphToDatabase(
    id: number | string,
    name: string,
    type: string,
    content: string,
): Promise<void> {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
    const csrfToken = csrfMeta?.content;
    if (!csrfToken) {
        console.error('CSRF token manquant');
        alert('Token CSRF manquant.');
        return;
    }

    try {
        const response = await fetch(`/admin/graphs/${id}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                _method: 'PUT',
                id,
                name,
                type,
                content,
            }),
        });

        if (response.status !== 200) {
            let errorMessage = 'Erreur lors de la sauvegarde du graphe.';
            try {
                const error = await response.json();
                if (error?.message) errorMessage = error.message;
            } catch {
                // ignore
            }
            throw new Error(errorMessage);
        }

        console.log('Graphe sauvegardé avec succès');
    } catch (error) {
        console.error('Erreur lors de la sauvegarde :', error);
        alert('Erreur lors de la sauvegarde du graphe.');
    }
}

function getXMLGraph(): string {
    return new ModelXmlSerializer(graph.getDataModel()).export();
}

(window as any).getXMLGraph = getXMLGraph;

function saveGraph() {
    const idInput = document.querySelector('#id') as HTMLInputElement | null;
    const nameInput = document.querySelector('#name') as HTMLInputElement | null;
    const typeInput = document.querySelector('#type') as HTMLInputElement | null;

    if (!idInput || !nameInput || !typeInput) {
        alert('Champs id / name / type manquants');
        return;
    }

    const xml = new ModelXmlSerializer(model).export();

    saveGraphToDatabase(idInput.value, nameInput.value, typeInput.value, xml);
}

document.getElementById('saveButton')?.addEventListener('click', saveGraph);

//-------------------------------------------------------------------------
// Utilitaire coordonnées

type GraphPoint = { x: number; y: number };

function getGraphPointFromEvent(graph: Graph, evt: MouseEvent | DragEvent): GraphPoint {
    if (typeof (graph as any).getPointForEvent === 'function') {
        const pt = (graph as any).getPointForEvent(evt as any);
        return {x: pt.x, y: pt.y};
    }

    const view = (graph as any).view ?? graph.getView();
    const pt = styleUtils.convertPoint(
        graph.container,
        eventUtils.getClientX(evt as any),
        eventUtils.getClientY(evt as any),
    );
    const tr = view.translate ?? {x: 0, y: 0};
    const scale = view.scale ?? 1;
    const panDx = (graph as any).panDx ?? 0;
    const panDy = (graph as any).panDy ?? 0;

    return {
        x: (pt.x - panDx) / scale - tr.x - 20,
        y: (pt.y - panDy) / scale - tr.y - 20,
    };
}

graph.enterStopsCellEditing = true;

//-------------------------------------------------------------------------
// Drag & drop : texte

const fontBtn = document.getElementById('font-btn') as HTMLElement | null;

if (fontBtn) {
    fontBtn.addEventListener('dragstart', (event: DragEvent) => {
        event.dataTransfer?.setData('node-type', 'text-node');
    });
}

container.addEventListener('dragover', (event: DragEvent) => {
    event.preventDefault();
});

container.addEventListener('drop', (event: DragEvent) => {
    event.preventDefault();
    const type = event.dataTransfer?.getData('node-type');

    if (type === 'text-node') {
        const pt = getGraphPointFromEvent(graph, event);

        graph.batchUpdate(() => {
            const parent = graph.getDefaultParent();
            graph.insertVertex({
                parent,
                value: 'Text',
                position: [pt.x, pt.y],
                size: [150, 30],
                style: {
                    fillColor: 'none',
                    strokeColor: 'none',
                    fontColor: '#000000',
                    fontSize: 14,
                    align: 'left',
                    verticalAlign: 'middle',
                },
            });
        });
    }
});

//-------------------------------------------------------------------------
// Drag & drop : carré

const squareIcon = document.getElementById('square-btn') as HTMLElement | null;

if (squareIcon) {
    squareIcon.addEventListener('dragstart', (event: DragEvent) => {
        event.dataTransfer?.setData('node-type', 'square-node');
    });
}

container.addEventListener('drop', (event: DragEvent) => {
    event.preventDefault();
    const type = event.dataTransfer?.getData('node-type');

    if (type === 'square-node') {
        const pt = getGraphPointFromEvent(graph, event);

        graph.batchUpdate(() => {
            const parent = graph.getDefaultParent();

            const vertex = graph.insertVertex({
                parent,
                value: '',
                position: [pt.x, pt.y],
                size: [150, 120],
                style: {
                    fillColor: '#fffacd',
                    strokeColor: '#000000',
                    strokeWidth: 1,
                    rounded: 2,
                },
            });

            graph.orderCells(true, [vertex]);
        });
    }
});

//-------------------------------------------------------------------------
// Drag & drop : nœud image

const nodeIcon = document.getElementById('nodeImage') as HTMLImageElement | null;
const nodeSelector = document.getElementById('node') as HTMLSelectElement | null;

if (nodeIcon) {
    nodeIcon.addEventListener('dragstart', (event: DragEvent) => {
        event.dataTransfer?.setData('node-type', 'icon-node');
    });
}

container.addEventListener('drop', (event: DragEvent) => {
    event.preventDefault();
    const type = event.dataTransfer?.getData('node-type');

    if (type === 'icon-node' && nodeIcon && nodeIcon.src && nodeSelector) {
        const pt = getGraphPointFromEvent(graph, event);
        const parent = graph.getDefaultParent();
        const nodeId = nodeSelector.value;

        graph.batchUpdate(() => {
            const existing = model.getCell(nodeId) as Cell | null;

            if (existing) {
                graph.setSelectionCells([existing]);
            } else {
                const node = _nodes.get(nodeId);
                if (!node) return;

                const newNode = graph.insertVertex({
                    parent,
                    id: nodeId,
                    value: node.label,
                    position: [pt.x - 16, pt.y - 16],
                    size: [32, 32],
                    style: {
                        shape: 'image',
                        image: nodeIcon.src,
                        editable: false,
                        resizable: true,
                        verticalLabelPosition: 'bottom',
                        spacingTop: -15,
                    },
                });

                node.edges.forEach((edge) => {
                    const targetNode = model.getCell(edge.attachedNodeId) as Cell | null;
                    if (targetNode) {
                        graph.insertEdge({
                            parent,
                            value: '',
                            source: newNode,
                            target: targetNode,
                            style: {
                                editable: false,
                                strokeColor: '#ff0000',
                                strokeWidth: 1,
                                startArrow: 'none',
                                endArrow: 'none',
                            },
                        });
                    }
                });
            }
        });
    }
});

//-------------------------------------------------------------------------
// Zoom

const zoomInButton = document.getElementById('zoom-in-btn') as HTMLButtonElement | null;
const zoomOutButton = document.getElementById('zoom-out-btn') as HTMLButtonElement | null;

if (zoomInButton) {
    zoomInButton.addEventListener('click', () => graph.zoomIn());
}
if (zoomOutButton) {
    zoomOutButton.addEventListener('click', () => graph.zoomOut());
}

//-------------------------------------------------------------------------
// Suppression avec Delete / Backspace

document.addEventListener('keydown', (event: KeyboardEvent) => {
    if (event.key === 'Delete' || event.key === 'Backspace') {
        const cells = graph.getSelectionCells();
        if (cells.length > 0) {
            graph.removeCells(cells);
        }
    }
});

//-------------------------------------------------------------------------
// CTRL+A : select all (jQuery)

$('body').keydown((event: any) => {
    if (event.ctrlKey && event.keyCode === 65) {
        event.preventDefault();
        event.stopPropagation();
        graph.selectAll();
    }
});

//-------------------------------------------------------------------------
// Connexions / déconnexions

graph.setConnectable(false);
(graph as any).isCellDisconnectable = () => false;

//-------------------------------------------------------------------------
// Group / ungroup

const groupButton = document.getElementById('group-btn') as HTMLButtonElement | null;
const ungroupButton = document.getElementById('ungroup-btn') as HTMLButtonElement | null;

if (groupButton) {
    groupButton.addEventListener('click', () => {
        const cells = graph.getSelectionCells();
        if (cells.length > 1) {
            const parent = graph.getDefaultParent();
            const group = graph.insertVertex({
                parent,
                style: {
                    fillColor: 'none',
                    strokeColor: 'none',
                },
            });
            graph.addCell(group);
            graph.groupCells(group, 5, cells);
        }
    });
}

if (ungroupButton) {
    ungroupButton.addEventListener('click', () => {
        const cells = graph.getSelectionCells();
        if (cells.length === 1) {
            graph.ungroupCells(cells);
            graph.setSelectionCells(cells);
        }
    });
}

//---------------------------------------------------------------------------
// Déplacement avec flèches

function moveSelectedVertex(graph: Graph, dx: number, dy: number) {
    const selected = graph.getSelectionCell() as Cell | null;
    if (selected && selected.isVertex()) {
        graph.batchUpdate(() => {
            const geo = selected.getGeometry();
            if (geo) {
                geo.translate(dx, dy);
                graph.refresh();
            }
        });
    }
}

document.addEventListener('keydown', (event: KeyboardEvent) => {
    const step = 1;
    switch (event.key) {
        case 'ArrowUp':
            moveSelectedVertex(graph, 0, -step);
            break;
        case 'ArrowDown':
            moveSelectedVertex(graph, 0, step);
            break;
        case 'ArrowLeft':
            moveSelectedVertex(graph, -step, 0);
            break;
        case 'ArrowRight':
            moveSelectedVertex(graph, step, 0);
            break;
    }
});

//---------------------------------------------------------------------------
// Fonctions utilitaires pour le double-clic

type Point = { x: number; y: number };

function placeObjectsOnCircle(center: Point, radius: number, n: number): Point[] {
    const points: Point[] = [];
    const angleStep = (2 * Math.PI) / n;

    for (let i = 0; i < n; i++) {
        const angle = i * angleStep;
        points.push({
            x: center.x + radius * Math.cos(angle),
            y: center.y + radius * Math.sin(angle),
        });
    }
    return points;
}

function getFilter(): string[] {
    const select = document.getElementById('filters') as HTMLSelectElement | null;
    if (!select) return [];
    const filter: string[] = [];
    for (const option of Array.from(select.options)) {
        if (option.selected) filter.push(option.value);
    }
    return filter;
}

function hasEdge(src: Cell | null, dest: Cell | null, name: string | null): boolean {
    if (!src || !dest) return false;
    let found = false;

    const check = (edges: Cell[]) => {
        edges.forEach((edge) => {
            if (edge.target === dest && (name == null || edge.value === name)) {
                found = true;
            }
        });
    };

    check(graph.getEdges(src));
    if (!found) {
        const reverseEdges = graph.getEdges(dest);
        reverseEdges.forEach((edge) => {
            if (edge.target === src && (name == null || edge.value === name)) {
                found = true;
            }
        });
    }
    return found;
}

//----------------------------------------------------------------
// Double-clic sur icône

graph.addListener(InternalEvent.DOUBLE_CLICK, (_sender, evt) => {
    const cell = evt.getProperty('cell') as Cell | null;

    if (!cell || !cell.isVertex()) return;
    const style = cell.style as any;
    if (style?.shape !== 'image') return;

    const node = _nodes.get(cell.id as string);
    if (!node) return;

    graph.batchUpdate(() => {
        const newEdges: Edge[] = [];
        const parent = graph.getDefaultParent();
        const filter = getFilter();

        node.edges.forEach((edge) => {
            const targetNode = _nodes.get(edge.attachedNodeId);
            if (!targetNode) return;

            const vertex = model.getCell(edge.attachedNodeId) as Cell | null;

            if (!vertex && !newEdges.some((e) => e.attachedNodeId === edge.attachedNodeId)) {
                // Filtrage
                if (
                    filter.length === 0 ||
                    filter.includes(targetNode.vue) ||
                    (filter.includes('8') && edge.edgeType === 'CABLE') ||
                    (filter.includes('9') && edge.edgeType === 'FLUX')
                ) {
                    newEdges.push(edge);
                }
            } else if (vertex && !hasEdge(cell, vertex, edge.name)) {
                graph.insertEdge({
                    parent,
                    value: edge.name,
                    source: cell,
                    target: vertex,
                    style: {
                        editable: false,
                        stroke: '#FF',
                        strokeWidth: 1,
                        startArrow:
                            edge.edgeType === 'FLUX' &&
                            (edge.bidirectional || edge.edgeDirection === 'FROM')
                                ? 'classic'
                                : 'none',
                        endArrow:
                            edge.edgeType === 'FLUX' &&
                            (edge.bidirectional || edge.edgeDirection === 'TO')
                                ? 'classic'
                                : 'none',
                    },
                });
            }
        });

        const geom = cell.getGeometry();
        if (!geom) return;

        const positions = placeObjectsOnCircle(
            {x: geom.x, y: geom.y},
            80,
            newEdges.length,
        );

        for (let i = 0; i < positions.length; i++) {
            const edge = newEdges[i];
            const newNode = _nodes.get(edge.attachedNodeId);
            if (!newNode) continue;

            const vertex = graph.insertVertex({
                parent,
                id: newNode.id,
                value: newNode.label,
                position: [positions[i].x, positions[i].y],
                size: [32, 32],
                style: {
                    shape: 'image',
                    image: newNode.image,
                    editable: false,
                    resizable: true,
                    verticalLabelPosition: 'bottom',
                    spacingTop: -15,
                },
            });

            graph.insertEdge({
                parent,
                value: edge.name,
                source: cell,
                target: vertex,
                style: {
                    editable: false,
                    stroke: '#FF',
                    strokeWidth: 1,
                    startArrow:
                        edge.edgeType === 'FLUX' &&
                        (edge.bidirectional || edge.edgeDirection === 'FROM')
                            ? 'classic'
                            : 'none',
                    endArrow:
                        edge.edgeType === 'FLUX' &&
                        (edge.bidirectional || edge.edgeDirection === 'TO')
                            ? 'classic'
                            : 'none',
                },
            });

            // TODO: insert edges with other existing objects
        }
    });
});

//-------------------------------------------------------------------------
// Update des nœuds depuis _nodes

document.getElementById('update-btn')?.addEventListener('click', () => {
    graph.batchUpdate(() => {
        const allCells = graph.getChildCells();

        allCells.forEach((cell) => {
            const style = cell.style as any;
            if (cell.isEdge()) {
                // Rien pour l'instant
            } else if (style?.image) {
                const node = _nodes.get(cell.id as string);
                if (!node) {
                    graph.removeCells([cell], true);
                } else {
                    cell.value = node.label;
                    styleUtils.setCellStyles(graph.getDataModel(), [cell], {
                        shape: 'image',
                        image: node.image,
                    });
                }
            }
        });

        graph.refresh();
    });
});

//---------------------------------------------------------------------------
// Export SVG

const svgElement = graph.container.querySelector('svg') as SVGSVGElement | null;

function embedImagesInSVG(svg: SVGSVGElement) {
    const images = svg.querySelectorAll('image');
    images.forEach((img) => {
        const href = img.getAttribute('xlink:href');
        if (!href) return;

        fetch(href)
            .then((response) => response.blob())
            .then((blob) => {
                const reader = new FileReader();
                reader.onloadend = () => {
                    const result = reader.result;
                    if (typeof result === 'string') {
                        img.setAttribute('xlink:href', result);
                    }
                };
                reader.readAsDataURL(blob);
            })
            .catch((err) => console.error('Erreur embed image SVG', err));
    });
}

function downloadSVG() {
    if (!svgElement) {
        alert('SVG introuvable');
        return;
    }

    embedImagesInSVG(svgElement);

    setTimeout(() => {
        const serializer = new XMLSerializer();
        const svgString = serializer.serializeToString(svgElement);
        const blob = new Blob([svgString], {type: 'image/svg+xml;charset=utf-8'});
        const url = URL.createObjectURL(blob);

        const link = document.createElement('a');
        const now = new Date();
        const timestamp =
            now.getFullYear() +
            String(now.getMonth() + 1).padStart(2, '0') +
            String(now.getDate()).padStart(2, '0') +
            String(now.getHours()).padStart(2, '0') +
            String(now.getMinutes()).padStart(2, '0');

        link.href = url;
        link.download = `graph-${timestamp}.svg`;
        link.click();

        URL.revokeObjectURL(url);
    }, 1000);
}

document.getElementById('download-btn')?.addEventListener('click', downloadSVG);

//-------------------------------------------------------------------------
// Layout organic

export function layout() {
    const parent = graph.getDefaultParent();
    const cells = graph.getChildVertices(parent);

    if (!cells || cells.length === 0) return;

    const organic = new FastOrganicLayout(graph);
    organic.forceConstant = 60;
    organic.disableEdgeStyle = false;

    graph.getDataModel().beginUpdate();
    try {
        organic.execute(parent, cells);
    } finally {
        const morph = new Morphing(graph);
        morph.addListener(InternalEvent.DONE, () => graph.getDataModel().endUpdate());
        morph.startAnimation();
    }
}

document.getElementById('layout-btn')?.addEventListener('click', layout);
