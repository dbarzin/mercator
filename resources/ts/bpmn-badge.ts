import {Cell, Graph, Point} from "@maxgraph/core";
import {BPMN_ICONS} from "./bpmn-icons";
import {isLaneVertex} from "./bpmn-helpers";

function setBadgeValue(graph: Graph, cell: Cell, glyph: string) {
    const badgeCell = getBadge(graph, cell);
    let newValue: string;
    if (glyph == "") {
        newValue = "";
        }
    else {
        const curValue = badgeCell.value ?? "";
        if (curValue.includes(BPMN_ICONS.SUB_PROCESS_ACTIVITY)) {
            if (glyph == BPMN_ICONS.SUB_PROCESS_ACTIVITY) {
                // No changes
                newValue = BPMN_ICONS.SUB_PROCESS_ACTIVITY;
            } else {
                // glyphs and sub-process
                newValue = glyph + BPMN_ICONS.SUB_PROCESS_ACTIVITY;
            }
        } else {
            if (glyph == BPMN_ICONS.SUB_PROCESS_ACTIVITY)
                newValue = curValue + BPMN_ICONS.SUB_PROCESS_ACTIVITY;
            else
                newValue = glyph;
        }
    }
    graph.batchUpdate(() => {
        badgeCell.setValue(newValue);
        graph.refresh(badgeCell);
    });
}

export function removeBottomCenterBadge(graph: Graph, parentVertex: Cell) {
    const child = getBadge(graph, parentVertex);
    if (!child) return;
    graph.batchUpdate(() => {
        graph.removeCells([child], true);
    });
}


function getBadge(graph: Graph, cell : Cell): Cell {
    console.log("getBadge", cell);
    // Check if the cell already has a badge
    for (const child of cell.getChildren()) {
        const styleName = child.getStyle?.()?.baseStyleNames?.includes?.("bpmnBadge");
        if (styleName)
            return child;
    }

    console.log("createBadge", cell);
    if (isLaneVertex(graph, cell)) {
        console.log("isLane ");
    }

    // ✅ Initialiser à null avec type explicite
    let newBadge: Cell | null = null;


    graph.batchUpdate(() => {
        const isLane = isLaneVertex(graph, cell);

        if (isLane) {
            console.log("isLane - création du badge dans la lane");
        }

        // Créer le badge
        newBadge = graph.insertVertex({
            parent: cell,
            position: [0, 0], // Position temporaire
            size: [30, 28],
            style: { baseStyleNames: ["bpmnBadge"] },
        });

        const badgeGeo = newBadge.getGeometry();

        if (badgeGeo) {
            // ✅ Définir explicitement les valeurs après création
            badgeGeo.relative = true;
            badgeGeo.x = 0.5;  // ✅ Force x à 0.5 (50%)
            badgeGeo.y = 1;    // ✅ Force y à 1 (100%)
            badgeGeo.offset = new Point(-15, -28); // Centré + au-dessus du bas

            console.log("Badge geometry configured:", {
                x: badgeGeo.x,
                y: badgeGeo.y,
                relative: badgeGeo.relative,
                offset: badgeGeo.offset
            });
        }

        newBadge.setConnectable(false);
    });

    if (!newBadge) {
        throw new Error("Failed to create badge");
    }
    return newBadge;
}


/* sub-process marker */
export function setSubProcessMarker(graph: Graph, cell: Cell) {
    setBadgeValue(graph, cell, BPMN_ICONS.SUB_PROCESS_ACTIVITY);
}

export function hasSubProcessMarker(graph: Graph, cell: Cell): boolean {
    const badge = getBadge(graph, cell);
    return badge.value.includes(BPMN_ICONS.SUB_PROCESS_ACTIVITY);
}

/* Sequential marker */
export function setSequentialMarker(graph: Graph, cell: Cell) {
    setBadgeValue(graph, cell, BPMN_ICONS.SEQUENTIAL_MARKER);
}
export function hasSequentialMarker(graph: Graph, cell: Cell): boolean {
    const badge = getBadge(graph, cell);
    return badge.value.includes(BPMN_ICONS.SEQUENTIAL_MARKER);
}

/* Parallel marker */
export function setParallelMarker(graph: Graph, cell: Cell) {
    setBadgeValue(graph, cell, BPMN_ICONS.PARALLEL_MARKER);
}
export function hasParallelMarker(graph: Graph, cell: Cell): boolean {
    const badge = getBadge(graph, cell);
    return badge.value.includes(BPMN_ICONS.PARALLEL_MARKER);
}
/* Loop marker */
export function hasLoopMarker(graph: Graph, cell: Cell): boolean {
    const badge = getBadge(graph, cell);
    return badge.value.includes(BPMN_ICONS.LOOP_MARKER);
}
export function setLoopMarker(graph: Graph, cell: Cell) {
    setBadgeValue(graph, cell, BPMN_ICONS.LOOP_MARKER);
}
/* Ad-Hoc marker */
export function hasAdHocMarker(graph: Graph, cell: Cell): boolean {
    const badge = getBadge(graph, cell);
    return badge.value.includes(BPMN_ICONS.AD_HOC_MARKER);
}
export function setAdHocMarker(graph: Graph, cell: Cell) {
    setBadgeValue(graph, cell, BPMN_ICONS.AD_HOC_MARKER);
}
/* Compensation marker */
export function hasCompensationMarker(graph: Graph, cell: Cell): boolean {
    const badge = getBadge(graph, cell);
    return badge.value.includes(BPMN_ICONS.COMPENSATION_MARKER);
}
export function setCompensationMarker(graph: Graph, cell: Cell) {
    setBadgeValue(graph, cell, BPMN_ICONS.COMPENSATION_MARKER);
}
