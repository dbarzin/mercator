// src/ts/bpmn-menu-select.ts
import { BPMN_ICONS } from "./bpmn-icons";
import {fetchActorObjects, fetchGraphObjects, fetchInformationObjects, fetchProcessObjects} from "./bpmn-api";
import {
    removeBottomCenterBadge, setAdHocMarker, setCompensationMarker,
    setLoopMarker,
    setParallelMarker,
    setSequentialMarker,
    setSubProcessMarker
} from "./bpmn-badge";
import {Cell, Graph} from "@maxgraph/core";
import {setConditionalFlow, setDefaultFlow, setMessageFlow, setSequenceFlow, setEventFlow} from "./bpmn-arrows";
import {hideMenu} from "./bpmn-edit";
import {
    isActivitiesVertex, isConversationVertex, isDataVertex,
    isGatewayVertex, isLaneVertex,
    isProcessVertex, isStateVertex,
    setDatabaseVertex,
    setDataVertex,
    setIconCellValue,
    setInputDataVertex,
    setOutputDataVertex
} from "./bpmn-helpers";

type AnyGraph = any;
type AnyCell = any;

export type BpmnElementDef = {
    id: string;
    name: string;
    glyph: string;
    url?: string
};

export type BpmnMenuSelectOptions = {
    vertexMenuId?: string;               // default: "vertex-menu"
    wrenchSelector?: string;             // default: 'button[data-action="config"]'
    elements?: BpmnElementDef[];

    /** IMPORTANT: le nom exact de la font qui contient tes glyphs BPMN */
    glyphFontFamily?: string;            // default: "bpmn"

    /** marge entre vertex-menu et sous-menu */
    sideOffsetPx?: number;               // default: 8
};

const PROCESS_ELEMENTS: BpmnElementDef[] = [
    { id: "task", name: "Task", glyph: BPMN_ICONS.TASK },
    { id: "user-task", name: "User task", glyph: BPMN_ICONS.USER_TASK },
    { id: "send-task", name: "Send task", glyph: BPMN_ICONS.SEND_TASK },
    { id: "receive-task", name: "Receive task", glyph: BPMN_ICONS.RECEIVE_TASK },
    { id: "manual-task", name: "Manual task", glyph: BPMN_ICONS.MANUAL_TASK },
    { id: "service-task", name: "Service task", glyph: BPMN_ICONS.SERVICE_TASK },
    { id: "business-task", name: "Business task", glyph: BPMN_ICONS.BUSINESS_TASK },
    { id: "script-task", name: "Script task", glyph: BPMN_ICONS.SCRIPT_TASK },
    // Markers
    { id: "loop-marker", name: "Loop", glyph: BPMN_ICONS.LOOP_MARKER },
    { id: "parallel-marker", name: "Parallel", glyph: BPMN_ICONS.PARALLEL_MARKER },
    { id: "sequential-marker", name: "Sequential", glyph: BPMN_ICONS.SEQUENTIAL_MARKER },
    { id: "ad-hoc-marker", name: "Ad Hoc", glyph: BPMN_ICONS.AD_HOC_MARKER },
    { id: "compensation-marker", name: "Compensation", glyph: BPMN_ICONS.COMPENSATION_MARKER },
];

const STATE_ELEMENTS: BpmnElementDef[] = [
    { id: "start-event", name: "Start event", glyph: BPMN_ICONS.START_EVENT },
    { id: "inter-event", name: "Intermediate event", glyph: BPMN_ICONS.INTER_EVENT },
    { id: "end-event", name: "End event", glyph: BPMN_ICONS.END_EVENT },

    { id: "message-start-event", name: "Message start event", glyph: BPMN_ICONS.MESSAGE_START_EVENT },
    { id: "message-sub-event", name: "Message sub-process start event", glyph: BPMN_ICONS.MESSAGE_SUB_START_EVENT },
    { id: "message-catch-event", name: "Message catch event", glyph: BPMN_ICONS.MESSAGE_CATCH_EVENT },
    { id: "message-bound-non-interruptible-event", name: "Message bound non-interruptible event", glyph: BPMN_ICONS.MESSAGE_BOUND_NON_INTERRUPT_EVENT },
    { id: "message-throw-event", name: "Message throw event", glyph: BPMN_ICONS.MESSAGE_THROW_EVENT },
    { id: "message-end-event", name: "Message end event", glyph: BPMN_ICONS.MESSAGE_END_EVENT },

    { id: "timer-start-event", name: "Timer start event", glyph: BPMN_ICONS.TIMER_START_EVENT },
    { id: "timer-catch-event", name: "Timer catch event", glyph: BPMN_ICONS.TIMER_CATCH_EVENT },
    { id: "timer-bound-non-interrupt-event", name: "Timer bound non-interruptible event", glyph: BPMN_ICONS.TIMER_BOUND_NON_INTERRUPT_EVENT },
    { id: "timer-sub-event", name: "Timer catch event", glyph: BPMN_ICONS.TIMER_SUB_EVENT },

    { id: "conditional-start-event", name: "Conditional start event", glyph: BPMN_ICONS.CONDITIONAL_START_EVENT },
    { id: "conditional-non-interrupt-start-event", name: "Conditional non-interruptible start event", glyph: BPMN_ICONS.CONDITIONAL_NON_INTERRUPT_START_EVENT },
    { id: "conditional-catch-event", name: "Conditional catch event", glyph: BPMN_ICONS.CONDITIONAL_CATCH_EVENT },

    { id: "link-catch-event", name: "Link catch event", glyph: BPMN_ICONS.LINK_CATCH_EVENT },
    { id: "link-end-event", name: "Link end event", glyph: BPMN_ICONS.LINK_END_EVENT },

    { id: "signal-start-event", name: "Signal start event", glyph: BPMN_ICONS.SIGNAL_START_EVENT },
    { id: "signal-non-interrupt-start-event", name: "Signal non-interruptible start event", glyph: BPMN_ICONS.SIGNAL_NON_INTERRUPT_START_EVENT },
    { id: "signal-catch-event", name: "Signal catch event", glyph: BPMN_ICONS.SIGNAL_CATCH_EVENT },
    { id: "signal-bound-non-interrupt-event", name: "Signal bound non-interruptible event", glyph: BPMN_ICONS.SIGNAL_BOUND_NON_INTERRUPT_EVENT },
    { id: "signal-throw-event", name: "Signal throw event", glyph: BPMN_ICONS.SIGNAL_THROW_EVENT },
    { id: "signal-end-event", name: "Signal end event", glyph: BPMN_ICONS.SIGNAL_END_EVENT },

    { id: "error-sub-process-start-event", name: "Error sub-process start event", glyph: BPMN_ICONS.ERROR_SUB_PROCESS_START_EVENT },
    { id: "error-end-event", name: "Error end event", glyph: BPMN_ICONS.ERROR_END_EVENT },

    { id: "escalation-start-event", name: "Escalation start event", glyph: BPMN_ICONS.ESCALATION_START_EVENT },
    { id: "escalation-bound-event", name: "Escalation bound event", glyph: BPMN_ICONS.ESCALATION_BOUND_EVENT },
    { id: "escalation-bound-non-interrupt-event", name: "Escalation bound non-interrupt event", glyph: BPMN_ICONS.ESCALATION_BOUND_NON_INTERRUPT_EVENT },
    { id: "escalation-end-event", name: "Escalation end event", glyph: BPMN_ICONS.ESCALATION_END_EVENT },

    { id: "cancel-bound-event", name: "Cancel boundary event", glyph: BPMN_ICONS.CANCEL_BOUND_EVENT },
    { id: "cancel-end-event", name: "Cancel end event", glyph: BPMN_ICONS.CANCEL_END_EVENT },

    { id: "termination-event", name: "Termination event", glyph: BPMN_ICONS.TERMINATION_EVENT },

    { id: "compensation-sub-process-start-event", name: "Compensation start sub-process event", glyph: BPMN_ICONS.COMPENSATION_SUB_PROCESS_START_EVENT },
    { id: "compensation-throw-event", name: "Compensation throw event", glyph: BPMN_ICONS.COMPENSATION_THROW_EVENT },

    { id: "multiple-sub-process-start-event", name: "Multiple sub-process start event", glyph: BPMN_ICONS.MULTIPLE_SUB_PROCESS_START_EVENT },
    { id: "multiple-bound-non-interrupt-event", name: "Multiple boundary non-interrupting event", glyph: BPMN_ICONS.MULTIPLE_BOUND_NON_INTERRUPT_EVENT },

    { id: "multiple-parallel-start-event", name: "Multiple parallel start event", glyph: BPMN_ICONS.MULTIPLE_PARALLEL_START_EVENT },

    // TODO : ajouter les autres types d'event
];

const GATEWAY_ELEMENTS: BpmnElementDef[] = [
    { id: "gateway", name: "Gateway", glyph: BPMN_ICONS.GATEWAY },
    { id: "exclusive-gateway", name: "Exclusive gateway", glyph: BPMN_ICONS.EXCLUSIVE_GATEWAY },
    { id: "inclusive-gateway", name: "Inclusive gateway", glyph: BPMN_ICONS.INCLUSIVE_GATEWAY },
    { id: "parallel-gateway", name: "Parallel gateway", glyph: BPMN_ICONS.PARALLEL_GATEWAY },
    { id: "complex-gateway", name: "Complex gateway", glyph: BPMN_ICONS.COMPLEX_GATEWAY },
    { id: "event-gateway", name: "Event gateway", glyph: BPMN_ICONS.EVENT_GATEWAY },
];

const ACTIVITIES_ELEMENTS: BpmnElementDef[] = [
    { id: "task", name: "Task", glyph: BPMN_ICONS.TASK },
    { id: "transaction", name: "Transaction", glyph: BPMN_ICONS.TASK },
    { id: "sub-process", name: "Sub process", glyph: BPMN_ICONS.TASK },
    { id: "call-activity", name: "Call activity", glyph: BPMN_ICONS.TASK },
];

const DATA_ELEMENTS: BpmnElementDef[] = [
    { id: "data", name: "Data", glyph: BPMN_ICONS.DATA },
    { id: "data-input", name: "Data input", glyph: BPMN_ICONS.DATA_INPUT },
    { id: "data-output", name: "Data output", glyph: BPMN_ICONS.DATA_OUTPUT },
    { id: "database", name: "Datastore", glyph: BPMN_ICONS.DATABASE },
];

const EDGE_ELEMENTS: BpmnElementDef[] = [
    { id: "sequence-flow", name: "Sequence flow", glyph: BPMN_ICONS.SEQUENCE_FLOW },
    { id: "message-flow", name: "Message flow", glyph: BPMN_ICONS.CONDITIONAL_FLOW },
    { id: "conditional-flow", name: "Conditional flow", glyph: BPMN_ICONS.CONDITIONAL_FLOW },
    { id: "default-flow", name: "Default flow", glyph: BPMN_ICONS.DEFAULT_FLOW },
    { id: "message-boundary-event", name: "Message Boundary Event", glyph: BPMN_ICONS.MESSAGE_CATCH_EVENT },
    { id: "timer-boundary-event", name: "Timer Boundary Event", glyph: BPMN_ICONS.TIMER_CATCH_EVENT },
    { id: "conditional-boundary-event", name: "Conditional Boundary Event", glyph: BPMN_ICONS.CONDITIONAL_CATCH_EVENT },
    { id: "signal-boundary-event", name: "Signal Boundary Event", glyph: BPMN_ICONS.SIGNAL_CATCH_EVENT },
    { id: "error-boundary-event", name: "Error Boundary Event", glyph: BPMN_ICONS.ERROR_CATCH_EVENT },
    { id: "escalation-boundary-event", name: "Escalation Boundary Event", glyph: BPMN_ICONS.ESCALATION_BOUND_EVENT },
    { id: "compensation-boundary-event", name: "Compensation Boundary Event", glyph: BPMN_ICONS.COMPENSATION_BOUND_EVENT },
    { id: "cancel-boundary-event", name: "Cancel Boundary Event", glyph: BPMN_ICONS.CANCEL_BOUND_EVENT },
    { id: "multiple-boundary-event", name: "Multiple Boundary Event", glyph: BPMN_ICONS.MULTIPLE_BOUND_EVENT },
    { id: "multiple-parallel-boundary-event", name: "Multiple Boundary Event", glyph: BPMN_ICONS.MULTIPLE_PARALLEL_BOUND_EVENT },
];

export function setIconValue(graph: AnyGraph, iconCell: Cell, value: any) {
    const model = graph.model;
    if (!model) return;
    graph.batchUpdate(() => {
        model.setValue(iconCell, value);
    });
}

function ensureStylesOnce() {
    const id = "bpmn-menu-select-style";
    if (document.getElementById(id)) return;

    const style = document.createElement("style");
    style.id = id;
    style.textContent = `
    .bpmn-select-menu {
      position: absolute;
      z-index: 9999;
      min-width: 240px;
      max-width: 300px;
      background: #ffffff;
      border: 1px solid rgba(0,0,0,.12);
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0,0,0,.12);
      padding: 10px;
    }
    .bpmn-select-menu.hidden { display: none; }

    .bpmn-select-title {
      font-weight: 600;
      font-size: 13px;
      margin: 2px 0 8px;
    }

    .bpmn-select-search {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 6px 8px;
      border: 1px solid rgba(0,0,0,.12);
      border-radius: 10px;
      margin-bottom: 6px;
    }
    .bpmn-select-search i { opacity: .7; }
    .bpmn-select-search input {
      width: 100%;
      border: none;
      outline: none;
      font-size: 13px;
    }

    .bpmn-select-list {
      max-height: 220px;
      overflow: auto;
      padding: 2px;
      border-radius: 10px;
    }

    /* compact */
    .bpmn-select-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 8px;
      border-radius: 10px;
      cursor: pointer;
      user-select: none;
    }
    .bpmn-select-item:hover { background: rgba(0,0,0,.05); }
    .bpmn-select-item.selected { outline: 2px solid rgba(0,0,0,.18); background: rgba(0,0,0,.04); }

    .bpmn-select-glyph {
      width: 22px;
      height: 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(0,0,0,.08);
      border-radius: 8px;
      font-size: 15px;
      line-height: 1;

      /* IMPORTANT: on forcera la police via style inline */
      font-family: var(--bpmn-glyph-font, inherit);
      font-variant-ligatures: none;
      speak: none;
    }

    .bpmn-select-name { font-size: 13px; }
  `;
    document.head.appendChild(style);
}


function getSelectedVertex(graph: AnyGraph): AnyCell | null {
    const sel =
        (graph.getSelectionCell()) ||
        (graph.getSelectionModel()?.cell) ||
        null;
    if (!sel) return null;
    return (
        isProcessVertex(graph, sel)||
        isStateVertex(graph, sel)||
        isGatewayVertex(graph,sel)||
        isActivitiesVertex(graph,sel)||
        isDataVertex(graph,sel)||
        isLaneVertex(graph,sel)||
        isConversationVertex(graph,sel)) ? sel : null;
}


function getSingleSelectedEdge(graph: Graph): Cell | null {
    const edges = graph
        .getSelectionCells()
        .filter(cell => cell.isEdge());

    return edges.length === 1 ? edges[0] : null;
}


function positionMenuBelowVertexMenu(
    menu: HTMLElement,
    vertexMenu: HTMLElement,
    verticalOffset = -100
) {
    const m = vertexMenu.getBoundingClientRect();

    let left = m.left;
    let top = m.bottom + verticalOffset - 40;

    const menuWidth = menu.offsetWidth || 300;
    const menuHeight = menu.offsetHeight || 320;

    // débordement à droite → on aligne à droite du bouton
    if (left + menuWidth > window.innerWidth - 8) {
        left = Math.max(8, m.right - menuWidth);
    }

    // débordement en bas → on affiche au-dessus du bouton
    if (top + menuHeight > window.innerHeight - 8) {
        top = Math.max(8, m.top - menuHeight - verticalOffset + 40);
    }

    menu.style.left = `${left}px`;
    menu.style.top = `${top}px`;
}

export function setupBpmnMenuSelect(graph: AnyGraph) {
    ensureStylesOnce();
    const vertexMenuId = "vertex-menu";
    const wrenchSelector = 'button[data-action="config"]';
    const searchSelector = 'button[data-action="search"]';
    const glyphFontFamily = "BPMN";
    const sideOffset =  8;

    let objectSelect: boolean = false;

    const vertexMenu = document.getElementById(vertexMenuId);
    if (!vertexMenu) {
        console.warn(`[bpmn-menu-select] vertex menu #${vertexMenuId} introuvable`);
        return;
    }

    const wrenchBtn = vertexMenu.querySelector(wrenchSelector) as HTMLButtonElement | null;
    if (!wrenchBtn) {
        console.warn(`[bpmn-menu-select] bouton wrench introuvable (${wrenchSelector})`);
        return;
    }

    const searchBtn = vertexMenu.querySelector(searchSelector) as HTMLButtonElement | null;
    if (!searchBtn) {
        console.warn(`[bpmn-menu-select] bouton search introuvable (${searchSelector})`);
        return;
    }

    // sous-menu (une seule fois)
    const existing = document.getElementById("bpmn-select-menu");
    const menu = (existing ?? document.createElement("div")) as HTMLDivElement;
    menu.id = "bpmn-select-menu";
    menu.className = "bpmn-select-menu hidden";
    menu.setAttribute("role", "dialog");
    menu.setAttribute("aria-hidden", "true");
    menu.tabIndex = -1;

    // expose la font via CSS var
    menu.style.setProperty("--bpmn-glyph-font", glyphFontFamily);

    if (!existing) {
        menu.innerHTML = `
      <!-- div class="bpmn-select-title">Change Element</div-->
      <div class="bpmn-select-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Filter..." aria-label="Filter elements" />
      </div>
      <div class="bpmn-select-list" role="listbox" tabindex="0"></div>
    `;
        document.body.appendChild(menu);
    }

    const input = menu.querySelector("input") as HTMLInputElement;
    const list = menu.querySelector(".bpmn-select-list") as HTMLDivElement;

    let elements: BpmnElementDef[] = [];
    let selectedIndex = 0;
    let filtered: BpmnElementDef[] = []; //[...elements];

    const closeMenu = () => {
        menu.classList.add("hidden");
        menu.setAttribute("aria-hidden", "true");
    };

    // Open the Menu
    const openMenu = (objects: BpmnElementDef[]| null): void => {

        const vertex = getSelectedVertex(graph);
        const edge = getSingleSelectedEdge(graph);
        // Is vertex found?
        if (!vertex && !edge) {
            console.error("[openMenu]: no vertex selected");
            closeMenu();
            return;
        }

        // Fill the menu with objects
        if (objects) {
            objects.push({
                    id: "",
                    name: "",
                    url: "",
                    glyph: BPMN_ICONS.TRASH });
            elements = objects;
        }
        else if (edge) {
            elements = EDGE_ELEMENTS;
        }
        // Fill object in the Menu depending on the vertex type
        else if (isProcessVertex(graph, vertex)) {
            elements = PROCESS_ELEMENTS;
        } else if (isStateVertex(graph, vertex)) {
            elements = STATE_ELEMENTS;
        } else if (isGatewayVertex(graph, vertex)) {
            elements = GATEWAY_ELEMENTS;
        } else if (isDataVertex(graph, vertex)) {
            elements = DATA_ELEMENTS;
        } else if (isActivitiesVertex(graph, vertex)) {
            console.log("ACTIVITIES_ELEMENTS");
            elements = ACTIVITIES_ELEMENTS;
        } else {
            console.warn("[bpmn-menu-select] vertex non-process/non-state");
            return;
        }

        // Initialize the list
        input.value = "";
        filtered = [...elements];
        selectedIndex = 0;
        renderList();

        // Compute menu position
        positionMenuBelowVertexMenu(menu, vertexMenu, sideOffset);

        // Show the menu
        menu.classList.remove("hidden");
        menu.setAttribute("aria-hidden", "false");

        input.focus();
        input.select();
    };

    // Apply the selected item on vertex or edge
    const applySelection = (el: BpmnElementDef) => {
        console.log("applySelection", el);
        const processVertex = getSelectedVertex(graph);
        const edge = getSingleSelectedEdge(graph);
        if (!processVertex && !edge) return;

        // What have we chosen?
        if (objectSelect) {
            console.log("applySelection objectSelect ", objectSelect);
            // it was an object Select
            graph.batchUpdate(() => {
                // Remove link ?
                if (el.glyph==BPMN_ICONS.TRASH) {
                    processVertex.value = null;
                    processVertex.url = null;
                    graph.refresh(processVertex);
                    removeBottomCenterBadge(graph, processVertex);
                    hideMenu();
                }
                else {
                    processVertex.value = el.name;
                    processVertex.url = el.url;
                    graph.refresh(processVertex);
                    console.log("applySelection setSubProcessMarker ", processVertex);
                    setSubProcessMarker(graph, processVertex);
                    hideMenu();
                }
            });
        }
        else if (edge) {
            switch (el.id) {
                case "sequence-flow":
                    // point de départ normal et ligne pleine
                    setSequenceFlow(graph, edge);
                    break;
                case "message-flow":
                    // point de départ rond avec stroke
                    setMessageFlow(graph, edge);
                    break;
                case "conditional-flow":
                    // point de départ losange avec plein
                    setConditionalFlow(graph, edge);
                    break;
                case "default-flow":
                    // point de départ ligne oblique et pein
                    setDefaultFlow(graph, edge);
                    break;
                default:
                    setEventFlow(graph, edge, el.glyph);
            }
            graph.view.invalidate();
            graph.view.validate();

            // Unselect the edge
            graph.clearSelection();

        }
        else if (isActivitiesVertex(graph, processVertex)) {
            // it is an activity
            switch (el.id) {
                case "task":
                    graph.batchUpdate(() => {
                        graph.setCellStyles("strokeColor", "black", [processVertex]);
                        graph.setCellStyles("strokeWidth", "1", [processVertex]);
                        graph.setCellStyles("dashed", false, [processVertex]);
                    });
                    break;
                case "transaction":
                    graph.batchUpdate(() => {
                        graph.setCellStyles("strokeColor", "black", [processVertex]);
                        graph.setCellStyles("strokeWidth", "4", [processVertex]);
                        graph.setCellStyles("dashed", false, [processVertex]);
                    });
                    break;
                case "sub-process":
                    graph.batchUpdate(() => {
                        graph.setCellStyles("strokeColor", "black", [processVertex]);
                        graph.setCellStyles("strokeWidth", "2", [processVertex]);
                        graph.setCellStyles("dashed", true, [processVertex]);
                        graph.setCellStyles("dashPattern", "6 3", [processVertex]);
                    });
                    break;
                case "call-activity":
                    graph.batchUpdate(() => {
                        graph.setCellStyles("strokeColor", "black", [processVertex]);
                        graph.setCellStyles("strokeWidth", "2", [processVertex]);
                        graph.setCellStyles("dashed", false, [processVertex]);
                    });
                    break;
            }
        } else {
            // Set the icon with the glyph
            if (el.glyph == BPMN_ICONS.TASK)
                // Task shown as empty in activity
                setIconCellValue(graph, processVertex, "");
            else if (el.glyph == BPMN_ICONS.DATA)
                // Set Data Icon
                setDataVertex(graph, processVertex);
            else if (el.glyph == BPMN_ICONS.DATABASE)
                // Set Database Vertex
                setDatabaseVertex(graph, processVertex);
            else if (el.glyph == BPMN_ICONS.DATA_INPUT)
                // Set Input Data Vertex
                setInputDataVertex(graph, processVertex);
            else if (el.glyph == BPMN_ICONS.DATA_OUTPUT)
                // Set Output Data Vertex
                setOutputDataVertex(graph, processVertex);
            else if (el.glyph == BPMN_ICONS.LOOP_MARKER) {
                setLoopMarker(graph, processVertex);
            }
            else if (el.glyph == BPMN_ICONS.PARALLEL_MARKER) {
                setParallelMarker(graph, processVertex);
            }
            else if (el.glyph == BPMN_ICONS.SEQUENTIAL_MARKER) {
                setSequentialMarker(graph, processVertex);
            }
            else if (el.glyph == BPMN_ICONS.AD_HOC_MARKER) {
                setAdHocMarker(graph, processVertex);
            }
            else if (el.glyph == BPMN_ICONS.COMPENSATION_MARKER) {
                setCompensationMarker(graph, processVertex);
            }
            else
                setIconCellValue(graph, processVertex, el.glyph);

            // Unselect the vertex
            graph.clearSelection();
        }
        closeMenu();
    };

    const highlightSelected = () => {
        const items = Array.from(list.querySelectorAll(".bpmn-select-item")) as HTMLDivElement[];
        items.forEach((it, i) => {
            it.classList.toggle("selected", i === selectedIndex);
            it.setAttribute("aria-selected", i === selectedIndex ? "true" : "false");
        });
        const selected = items[selectedIndex];
        if (selected) selected.scrollIntoView({ block: "nearest" });
    };

    const renderList = () => {
        list.innerHTML = "";

        if (filtered.length === 0) {
            const empty = document.createElement("div");
            empty.style.padding = "8px";
            empty.style.opacity = "0.7";
            empty.textContent = "No match.";
            list.appendChild(empty);
            return;
        }

        filtered.forEach((el, idx) => {
            const row = document.createElement("div");
            row.className = "bpmn-select-item" + (idx === selectedIndex ? " selected" : "");
            row.setAttribute("role", "option");
            row.setAttribute("aria-selected", idx === selectedIndex ? "true" : "false");
            row.dataset.idx = String(idx);

            const glyph = document.createElement("span");
            glyph.className = "bpmn-select-glyph";
            glyph.textContent = el.glyph;

            const name = document.createElement("span");
            name.className = "bpmn-select-name";
            name.textContent = el.name;

            row.appendChild(glyph);
            row.appendChild(name);

            row.addEventListener("mouseenter", () => {
                selectedIndex = idx;
                highlightSelected();
            });

            row.addEventListener("click", (e) => {
                e.preventDefault();
                applySelection(el);
            });

            list.appendChild(row);
        });
    };

    const moveSelection = (delta: number) => {
        if (filtered.length === 0) return;
        selectedIndex = Math.max(0, Math.min(filtered.length - 1, selectedIndex + delta));
        highlightSelected();
    };

    // Filtrage des objets du menu
    input.addEventListener("input", () => {
        const q = input.value.trim().toLowerCase();
        filtered = elements.filter((e) => e.name.toLowerCase().includes(q));
        selectedIndex = 0;
        renderList();
    });

    // Selection de l'objet dans le menu avec les flèches
    const onKeyDown = (e: KeyboardEvent) => {
        if (menu.classList.contains("hidden")) return;

        if (e.key === "Escape") {
            e.preventDefault();
            closeMenu();
            return;
        }
        if (e.key === "ArrowDown") {
            e.preventDefault();
            moveSelection(+1);
            return;
        }
        if (e.key === "ArrowUp") {
            e.preventDefault();
            moveSelection(-1);
            return;
        }
        if (e.key === "Enter") {
            e.preventDefault();
            const el = filtered[selectedIndex];
            if (el) applySelection(el);
            return;
        }
    };
    document.addEventListener("keydown", onKeyDown);

    // Fermeture quand le menu perd le focus (focus sort du menu ET du vertexMenu)
    const onFocusIn = (e: FocusEvent) => {
        if (menu.classList.contains("hidden")) return;
        const t = e.target as Node;
        // si le focus revient dans menu/vertexMenu, on laisse ouvert
        if (menu.contains(t) || vertexMenu.contains(t)) return;
        closeMenu();
    };
    document.addEventListener("focusin", onFocusIn);

    // Click extérieur (au cas où)
    document.addEventListener("mousedown", (e) => {
        if (menu.classList.contains("hidden")) return;
        const t = e.target as Node;
        if (menu.contains(t) || vertexMenu.contains(t)) return;
        closeMenu();
    });

    // Toggle sur le wrench
    wrenchBtn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (!menu.classList.contains("hidden")) {
            closeMenu();
            return;
        }
        // We ware not selecting an object, so we can change the icon
        objectSelect = false;
        // Open the menu
        openMenu(null);
    });

    // Toggle sur le search
    searchBtn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (!menu.classList.contains("hidden")) {
            closeMenu();
            return;
        }

        // We are selecting an object, so we can't change the icon'
        objectSelect = true;

        // Get the selected vertex
        const vertex = getSelectedVertex(graph);
        if (isDataVertex(graph, vertex)) {
            // Fetch the objects
            fetchInformationObjects()
                .then((objects) =>
                    openMenu(objects))
                .catch((error) =>
                    console.error("[bpmn-menu-select] Impossible de charger les objets du graph", error)
                );
        }
        else if (isProcessVertex(graph, vertex)) {
            // Fetch the objects
            fetchGraphObjects()
                .then((objects) =>
                    openMenu(objects))
                .catch((error) =>
                    console.error("[bpmn-menu-select] Impossible de charger les objets du graph", error)
                );
            }
        else if (isLaneVertex(graph, vertex)) {
            // Fetch the objects
            fetchActorObjects()
                .then((objects) =>
                    openMenu(objects))
                .catch((error) =>
                    console.error("[bpmn-menu-select] Impossible de charger les objets du graph", error)
                );
        }
        else if (isConversationVertex(graph, vertex)) {
            // Fetch the objects
            fetchProcessObjects()
                .then((objects) =>
                    openMenu(objects))
                .catch((error) =>
                    console.error("[bpmn-menu-select] Impossible de charger les objets du graph", error)
                );
        }
        else
            console.log("[bpmn-menu-select] invalid object : ", vertex);
    });

    // reposition si la fenêtre change
    window.addEventListener("resize", () => {
        if (!menu.classList.contains("hidden")) positionMenuBelowVertexMenu(menu, vertexMenu, sideOffset);
    });
    window.addEventListener("scroll", () => {
        if (!menu.classList.contains("hidden")) positionMenuBelowVertexMenu(menu, vertexMenu, sideOffset);
    }, true);
}
