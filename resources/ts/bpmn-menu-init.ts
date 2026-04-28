// src/bpmn-menu/initVertexMenu.ts
import { InternalEvent, type Cell, type EventObject, type Graph, UndoManager } from "@maxgraph/core";
import type { VertexMenuController, VertexActionId } from "./bpmn-menu";
import { makeDefaultHandlers } from "./bpmn-menu-handler";

export function initVertexMenuActions(
    graph: Graph,
    undoManager: UndoManager
): VertexMenuController {
    const requireSingle = true;
    const menuEl = document.getElementById("vertex-menu")!;


// -------------------------------------------------------
// Helpers

    function getBaseStyleNames(cell: Cell): string[] {
        const s = (cell as any)?.style;
        const names = s?.baseStyleNames;
        return Array.isArray(names) ? names : [];
    }

    function setActionVisible(action: string, visible: boolean) {
        const btn = menuEl.querySelector<HTMLElement>(`[data-action="${action}"]`);
        if (!btn) return;
        btn.classList.toggle("hidden", !visible);

        //éviter le focus/clavier sur un bouton masqué
        if (btn instanceof HTMLButtonElement) {
            btn.disabled = !visible;
            btn.tabIndex = visible ? 0 : -1;
        }
    }

    // -------------------------------------------------------
    // Show/Hide menu items based on cell style

    function applyMenuVisibilityForCell(cell: Cell) {

        // actions présentes dans TON menu
        const ALL_ACTIONS = [
            "add-state",
            "add-task",
            "add-gateway",
            "connect",
            "config",
            "color",
            "add-annotations",
            "delete",
            "search",
            "rotate",
            "menu-break"
        ] as const;


        // défaut: tout visible (
        const actions = new Set<string>(ALL_ACTIONS as unknown as string[]);

        const baseStyles = getBaseStyleNames(cell);

        // 1) si c'est une edge: pas d'ajout de vertex
        if (cell.isEdge()) {
            actions.delete("add-state");
            actions.delete("add-task");
            actions.delete("add-gateway");
            actions.delete("connect");
            actions.delete("search");
            actions.delete("rotate");

            // not config for comments, data and database
            const sourceBaseStyles = cell.source ? getBaseStyleNames(cell.source): null;
            const targetBaseStyles = cell.target ? getBaseStyleNames(cell.target): null;
            if (sourceBaseStyles?.includes('annotation')||
                targetBaseStyles?.includes('annotation')||
                sourceBaseStyles?.includes('data')||
                targetBaseStyles?.includes('data')||
                sourceBaseStyles?.includes('database')||
                targetBaseStyles?.includes('database')
                )
                actions.delete("config");
        }

        // 2) si c'est une "state": pas de search
        else if (baseStyles.includes("state")||baseStyles.includes("stateIcon")) {
            actions.delete("search");
            actions.delete("rotate");
        }
        // 3) exemple: sur un "gateway" tu interdis la couleur
        else if (baseStyles.includes("gateway")) {
            actions.delete("search");
            actions.delete("rotate");
        }

        // 4) exemple: sur un "database" tu interdis add-gateway et add-state
        else if (baseStyles.includes("data") ||
            baseStyles.includes("database")) {
            actions.delete("add-gateway");
            actions.delete("add-state");
            actions.delete("rotate");
        }

        else if (baseStyles.includes("annotation")) {
            actions.delete("add-state");
            actions.delete("add-task");
            actions.delete("add-gateway");
            actions.delete("config");
            actions.delete("add-annotations");
            actions.delete("search");
            actions.delete("rotate");
        }

        else if (baseStyles.includes("lane")) {
            actions.delete("add-state");
            actions.delete("add-task");
            actions.delete("add-gateway");
            actions.delete("connect");
            actions.delete("config");
        }
        else if (baseStyles.includes("process")) {
            actions.delete("rotate");
        }
        else if (baseStyles.includes("conversation")) {
            actions.delete("add-state");
            actions.delete("add-task");
            actions.delete("add-gateway");
            actions.delete("config");
            actions.delete("rotate");
            actions.delete("menu-break");
        }
        else
            console.warn("Unknown cell style: ", cell.style);

        // applique
        for (const action of ALL_ACTIONS) {
            setActionVisible(action, actions.has(action));
        }
    }

    // parent “logique” pour les insertions
    const parent =
        (graph as any).getDefaultParent?.() ??
        (graph as any).getDefaultParent?.call(graph) ??
        (graph as any).getDefaultParent ??
        graph.getDefaultParent?.() ??
        (graph as any).getDefaultParent?.();

    let currentCell: Cell | null = null;

    // 🔥 On accepte vertex OU edge
    const isMenuCell = (c: Cell | null | undefined) => !!c && (c.isVertex() || c.isEdge());

    // Sert à "replacer" le menu quand la vue bouge
    let lastAnchor: { x: number; y: number } | null = null;

    const hide = () => {
        currentCell = null;
        lastAnchor = null;
        setPaletteOpen(false);
        menuEl.classList.add("hidden");
    };

    const ensureMenuInContainer = () => {
        if (menuEl.parentElement !== graph.container) graph.container.appendChild(menuEl);
    };

    // Convertit un MouseEvent en coords relatives au container du graph
    const getLocalPointFromMouse = (e: MouseEvent) => {
        const rect = graph.container.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        return { x, y };
    };

    const placeMenuAt = (x: number, y: number) => {
        ensureMenuInContainer();

        // Récupérer le rect du container pour avoir sa position dans la viewport
        const rect = graph.container.getBoundingClientRect();

        // Tenir compte du scroll du container si il est scrollable
        const scrollLeft = graph.container.scrollLeft || 0;
        const scrollTop = graph.container.scrollTop || 0;

        // Convertir les coordonnées locales en coordonnées absolues du container
        // en ajoutant le scroll
        const absoluteX = x + scrollLeft;
        const absoluteY = y + scrollTop;

        // Offset pour ne pas être pile sous le curseur
        const OFFSET_X = 8;
        const OFFSET_Y = 8;

        // Clamp pour que le menu reste visible dans le container
        const maxX = rect.width + scrollLeft - menuEl.offsetWidth - 4;
        const maxY = rect.height + scrollTop - menuEl.offsetHeight - 4;

        const left = Math.max(scrollLeft, Math.min(Math.round(absoluteX + OFFSET_X), maxX));
        const top = Math.max(scrollTop, Math.min(Math.round(absoluteY + OFFSET_Y), maxY));

        menuEl.style.left = `${left}px`;
        menuEl.style.top = `${top}px`;
        menuEl.classList.remove("hidden");

        lastAnchor = { x: left, y: top };
    };
    // Fallback quand on n'a pas de MouseEvent (ex: reposition sur zoom/pan)
    const showForCellFallback = (cell: Cell) => {
        const state = graph.view.getState(cell);
        if (!state) return;

        applyMenuVisibilityForCell(cell);

        // No menu for icons
        if (cell?.style?.baseStyleNames?.includes("bpmnIcon")) return;

        // Si edge: on tente de viser le milieu
        if (cell.isEdge()) {
            const pts = (state as any).absolutePoints as Array<{ x: number; y: number }> | null | undefined;
            if (pts && pts.length >= 2) {
                const midIdx = Math.floor(pts.length / 2);
                const a = pts[midIdx - 1] ?? pts[0];
                const b = pts[midIdx] ?? pts[pts.length - 1];
                const mx = (a.x + b.x) / 2;
                const my = (a.y + b.y) / 2;

                // mx/my sont en coords "graph view", mais ton menu est dans container.
                // Comme tu utilises déjà state.x/state.y en pixels container pour les vertices,
                // on reste cohérent : state.absolutePoints est aussi en pixels (view coords) dans MaxGraph.
                placeMenuAt(mx, my);
                return;
            }
        }

        // Vertex: à droite comme avant
        if (cell.isVertex()) {
            const left = state.x + state.width + 8;
            const top = state.y;

            placeMenuAt(left, top);
            return;
        }

        // Si on arrive là: pas de placement fiable
        hide();
    };

    // Placement prioritaire: près du clic
    const showForCellAtMouse = (cell: Cell, e: MouseEvent) => {
        // No menu for icons
        if (cell?.style?.baseStyleNames?.includes("bpmnIcon")) return;
        applyMenuVisibilityForCell(cell);
        const p = getLocalPointFromMouse(e);
        placeMenuAt(p.x, p.y);
    };

    const updateFromSelection = () => {
        const cells = graph.getSelectionCells() as Cell[];

        if (requireSingle) {
            if (cells.length !== 1 || !isMenuCell(cells[0])) return hide();
            currentCell = cells[0];

            // Si on a un ancrage (dernier clic), on garde l'emplacement
            if (lastAnchor) {
                ensureMenuInContainer();
                menuEl.style.left = `${lastAnchor.x}px`;
                menuEl.style.top = `${lastAnchor.y}px`;
                menuEl.classList.remove("hidden");
                return;
            }

            showForCellFallback(currentCell);
            return;
        }

        const first = cells.find((c) => isMenuCell(c));
        if (!first) return hide();
        currentCell = first;

        if (lastAnchor) {
            ensureMenuInContainer();
            menuEl.style.left = `${lastAnchor.x}px`;
            menuEl.style.top = `${lastAnchor.y}px`;
            menuEl.classList.remove("hidden");
            return;
        }

        showForCellFallback(currentCell);
    };

    const defaultHandlers = makeDefaultHandlers();
    const handlers = { ...defaultHandlers, ...({} as any) };

    // 1) sélection
    const onSelectionChange = () => updateFromSelection();
    graph.getSelectionModel().addListener(InternalEvent.CHANGE, onSelectionChange);

    // 2) zoom/pan/redraw
    const onViewChanged = () => {
        if (!currentCell) return;

        // si on a un anchor (clic), on laisse tel quel (menu "collé" au clic),
        // sinon on recalcule via fallback
        if (!lastAnchor) showForCellFallback(currentCell);
    };
    graph.view.addListener(InternalEvent.SCALE, onViewChanged);
    graph.view.addListener(InternalEvent.TRANSLATE, onViewChanged);
    graph.view.addListener(InternalEvent.SCALE_AND_TRANSLATE, onViewChanged);
    graph.view.addListener(InternalEvent.DOWN, onViewChanged);
    graph.view.addListener(InternalEvent.UP, onViewChanged);

    // Palette de couleur
    const paletteEl = menuEl.querySelector<HTMLElement>('[data-role="color-palette"]');
    const colorBtn = menuEl.querySelector<HTMLElement>('[data-action="color"]');

    function setPaletteOpen(open: boolean) {
        if (!paletteEl || !colorBtn) return;
        paletteEl.classList.toggle("hidden", !open);
        colorBtn.setAttribute("aria-expanded", open ? "true" : "false");
        paletteEl.setAttribute("aria-hidden", open ? "false" : "true");
    }

    function togglePalette() {
        if (!paletteEl) return;
        const isOpen = !paletteEl.classList.contains("hidden");
        setPaletteOpen(!isOpen);
    }

    // 3) click menu
    const onMenuClick = (e: MouseEvent) => {
        const target = e.target as HTMLElement | null;
        if (!target) return;

        e.preventDefault();
        e.stopPropagation();

        if (!currentCell || !isMenuCell(currentCell)) return;

        const swatch = target.closest<HTMLElement>("[data-color]");
        if (swatch) {
            handlers["color"]?.({
                graph,
                undoManager,
                cell: currentCell,
                parent: (graph as any).getDefaultParent?.() ?? (graph as any).getDefaultParent?.call?.(graph),
                menuEl: target,
                event: e,
            });

            hide();
            return;
        }

        const action = target.closest<HTMLElement>("[data-action]")?.dataset.action as
            | VertexActionId
            | undefined;
        if (!action) return;

        if (action === "color") {
            togglePalette();
            return;
        }

        setPaletteOpen(false);

        handlers[action]?.({
            graph,
            undoManager,
            cell: currentCell,
            parent: (graph as any).getDefaultParent?.() ?? (graph as any).getDefaultParent?.call?.(graph),
            menuEl,
            event: e,
        });
    };

    menuEl.addEventListener("click", onMenuClick);

    // 4) click dans le graph: 🔥 on affiche le menu aussi sur les edges + près du clic
    const onGraphClick = (_sender: unknown, evt: EventObject) => {
        const cell = evt.getProperty("cell") as Cell | null;
        const me = evt.getProperty("event") as MouseEvent | null; // MouseEvent natif

        if (!cell) {
            hide();
            return;
        }

        if (!isMenuCell(cell)) {
            hide();
            return;
        }

        currentCell = cell;

        // Priorité au clic exact
        if (me) {
            showForCellAtMouse(cell, me);
            return;
        }

        // Fallback
        lastAnchor = null;
        showForCellFallback(cell);
    };
    graph.addListener(InternalEvent.CLICK, onGraphClick);

    // 5) stop gestures
    const stop = (ev: Event) => {
        ev.preventDefault();
        ev.stopPropagation();
    };
    menuEl.addEventListener("mousedown", stop, true);
    menuEl.addEventListener("pointerdown", stop, true);

    hide();

    const destroy = () => {
        graph.getSelectionModel().removeListener(onSelectionChange as any);
        graph.view.removeListener(onViewChanged as any);
        graph.removeListener(onGraphClick as any);

        menuEl.removeEventListener("click", onMenuClick);
        menuEl.removeEventListener("mousedown", stop, true);
        menuEl.removeEventListener("pointerdown", stop, true);
    };

    return {
        destroy,
        getCurrentCell: () => currentCell,
        showForCell: (cell: Cell) => {
            if (!isMenuCell(cell)) return;
            currentCell = cell;
            lastAnchor = null;
            showForCellFallback(cell);
        },
        hide,
    };
}
