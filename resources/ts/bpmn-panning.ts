
// Create custom PanningHandler that only activates on background
import {EventSource, Graph, InternalMouseEvent, PanningHandler} from "@maxgraph/core";
import type {AbstractGraph} from "@maxgraph/core/lib/esm/view/AbstractGraph";

export class BackgroundOnlyPanningHandler extends PanningHandler {
    constructor(graph: AbstractGraph) {
        super(graph);
        // Use left button for panning
        this.useLeftButtonForPanning = true;
        this.usePopupTrigger = false;
        this.ignoreCell = false;
        this.consumePanningTrigger = true;
    }

    // Override isPanningTrigger to only pan when clicking on background
    isPanningTrigger(me: InternalMouseEvent) {
        const evt = me.getEvent();

        // Check if left button is pressed
        if (evt.button !== 0) {
            return false;
        }

        // Check if we're clicking on empty space (no cell)
        const cell = me.getCell();

        // Only pan if there's no cell at this position
        if (cell == null) {
            // CRITICAL: Mark that we want to consume this event
            this.consumePanningTrigger = true;
            return true;
        }

        return false;
    }

    // Override mouseDown to properly prevent other handlers
    mouseDown(sender: EventSource, me: InternalMouseEvent) {
        if (this.isPanningTrigger(me)) {
            // Start panning
            super.mouseDown(sender, me);

            // CRITICAL: Consume the event to prevent selection/rubberband
            me.consume();
        }
    }

    // Override mouseMove for panning
    mouseMove(sender: EventSource, me: InternalMouseEvent) {
        if (this.active) {
            super.mouseMove(sender, me);
            me.consume();
        }
    }

    // Override mouseUp to ensure clean state reset
    mouseUp(sender: EventSource, me: InternalMouseEvent) {
        if (this.active) {
            super.mouseUp(sender, me);
            me.consume();

            // Reset any lingering selection state
            this.reset();
        }
    }

    reset() {
        // Reset selection handler if it exists
        if (this.graph.selectionCellsHandler) {
            this.graph.selectionCellsHandler.reset();
        }

        // Reset rubberband by finding it in handlers
        const handlers = this.graph.handlers || [];
        for (let handler of handlers) {
            if (handler.constructor.name === 'RubberBandHandler' && handler.reset) {
                handler.reset();
            }
        }
    }
}
