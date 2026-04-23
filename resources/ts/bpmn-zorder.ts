import {Cell, Graph, InternalEvent} from "@maxgraph/core";

export function initZOrder(graph: Graph) {
    const handler = () => organizeBpmnZOrder(graph);

    graph.addListener(InternalEvent.CELLS_ADDED,   handler);
    graph.addListener(InternalEvent.CELLS_MOVED,   handler);
    graph.addListener(InternalEvent.CELLS_RESIZED, handler);
}

/**
 * Récupère toutes les cellules récursivement (enfants, petits-enfants, etc.)
 */
function getAllCellsRecursive(cell: Cell, cells: Cell[] = []): Cell[] {
    const children = cell.getChildren();
    if (!children) {
        return cells;
    }

    for (const child of children) {
        cells.push(child);
        getAllCellsRecursive(child, cells);
    }

    return cells;
}

// Fonction pour organiser le z-order des éléments BPMN
function organizeBpmnZOrder(graph: Graph) {
    const model = graph.getDataModel();
    const parent = graph.getDefaultParent();

    // Récupérer TOUTES les cellules (y compris celles dans les lanes)
    const allCells = getAllCellsRecursive(parent);

    if (!allCells || allCells.length === 0) {
        return;
    }

    // Trier par type
    const pools: Cell[] = [];
    const lanes: Cell[] = [];
    const processes: Cell[] = [];
    const states: Cell[] = [];
    const gateways: Cell[] = [];
    const annotations: Cell[] = [];
    const databases: Cell[] = [];
    const edges: Cell[] = [];
    const eventFlowEdges: Cell[] = [];  // ← bpmnEventFlow : toujours au-dessus
    const conversations: Cell[] = [];
    const data: Cell[] = [];
    const activities: Cell[] = [];

    allCells.forEach(cell => {
        if (cell.isEdge()) {
            // bpmnEventFlow séparé des autres edges
            if (cell.getStyle()?.baseStyleNames?.includes("bpmnEventFlow")) {
                eventFlowEdges.push(cell);
            } else {
                edges.push(cell);
            }
        } else if (cell.isVertex()) {
            const style = cell.getStyle();
            if (style?.baseStyleNames?.includes('lane')) {
                const cellParent = cell.getParent();
                if (cellParent === parent || !cellParent?.isVertex()) {
                    pools.push(cell);
                } else {
                    lanes.push(cell);
                }
            }
            else if (style?.baseStyleNames?.includes('process')) {
                processes.push(cell);
            }
            else if (style?.baseStyleNames?.includes('gateway')) {
                gateways.push(cell);
            }
            else if (style?.baseStyleNames?.includes('stateIcon') ||
                style?.baseStyleNames?.includes('state')) {
                states.push(cell);
            }
            else if (style?.baseStyleNames?.includes('database')) {
                databases.push(cell);
            }
            else if (style?.baseStyleNames?.includes('annotation')) {
                annotations.push(cell);
            }
            else if (style?.baseStyleNames?.includes('conversation')) {
                conversations.push(cell);
            }
            else if (style?.baseStyleNames?.includes('bpmnIcon') ||
                style?.baseStyleNames?.includes('bpmnBadge')) {
                processes.push(cell);
            }
            else if (style?.baseStyleNames?.includes('activities')) {
                activities.push(cell);
            }
            else if (style?.baseStyleNames?.includes('data')) {
                data.push(cell);
            }
                // Les cellules enfants des bpmnEventFlow (bpmnEventCircleBg,
                // bpmnEventCircleIcon) héritent du z-order de leur edge parent —
            // pas besoin de les traiter séparément.
            else if (style?.baseStyleNames?.includes('bpmnEventCircleBg') ||
                style?.baseStyleNames?.includes('bpmnEventCircleIcon')) {
                // ignoré volontairement
            }
            else {
                console.log('organizeBpmnZOrder - unknown cell type', cell);
            }
        }
    });

    model.beginUpdate();
    try {
        if (pools.length > 0)          graph.orderCells(true,  pools);
        if (activities.length > 0)     graph.orderCells(false, activities);
        if (pools.length > 0)          graph.orderCells(false, pools);
        if (edges.length > 0)          graph.orderCells(false, edges);
        if (processes.length > 0)      graph.orderCells(false, processes);
        if (data.length > 0)           graph.orderCells(false, data);
        if (databases.length > 0)      graph.orderCells(false, databases);
        if (gateways.length > 0)       graph.orderCells(false, gateways);
        if (states.length > 0)         graph.orderCells(false, states);
        if (annotations.length > 0)    graph.orderCells(false, annotations);
        if (conversations.length > 0)  graph.orderCells(false, conversations);

        // ← bpmnEventFlow en dernier : toujours au-dessus de tout
        if (eventFlowEdges.length > 0) graph.orderCells(false, eventFlowEdges);

    } finally {
        model.endUpdate();
    }
}

//==================================

export function setupBringToFrontKeyBinding(
    graph: Graph,
    container: HTMLElement
): void {
    const handleKeyPress = (event: KeyboardEvent) => {
        if (event.key === '+') {
            event.preventDefault();
            bringSelectedToFront(graph);
        }
        else if (event.key === '-') {
            event.preventDefault();
            bringSelectedToBack(graph);
        }
    };

    container.addEventListener('keydown', handleKeyPress);
}

export function bringSelectedToFront(graph: Graph): void {
    const selectionCells = graph.getSelectionCells();
    if (!selectionCells || selectionCells.length === 0) return;

    graph.batchUpdate(() => {
        graph.orderCells(false, selectionCells);
    });
}

export function bringSelectedToBack(graph: Graph): void {
    const selectionCells = graph.getSelectionCells();
    if (!selectionCells || selectionCells.length === 0) return;

    graph.batchUpdate(() => {
        graph.orderCells(true, selectionCells);
    });
}