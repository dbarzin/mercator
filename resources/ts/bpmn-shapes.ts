import { Shape, CellState, AbstractCanvas2D } from "@maxgraph/core";

export class BpmnDataObjectShape extends Shape {
    override paintVertexShape(
        c: AbstractCanvas2D,
        x: number,
        y: number,
        w: number,
        h: number
    ) {
        const fold = Math.min(w, h) * 0.2;

        c.begin();
        c.moveTo(x, y);
        c.lineTo(x + w - fold, y);
        c.lineTo(x + w, y + fold);
        c.lineTo(x + w, y + h);
        c.lineTo(x, y + h);
        c.close();
        c.fillAndStroke();

        // coin replié
        c.begin();
        c.moveTo(x + w - fold, y);
        c.lineTo(x + w - fold, y + fold);
        c.lineTo(x + w, y + fold);
        c.stroke();
    }
}

