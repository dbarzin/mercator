// bpmn-conversation-link.ts
import {
    AbstractCanvas2D,
    ConnectorShape,
    Point,
    Rectangle,
} from "@maxgraph/core";

/**
 * Edge shape that draws two parallel strokes (BPMN conversation link look).
 * Style option: doubleGap (px) = distance between the two lines.
 */
export class DoubleLineConnectorShape extends ConnectorShape {
    override paintEdgeShape(c: AbstractCanvas2D, pts: Point[]): void {
        if (!pts || pts.length < 2) return;

        const style: any = this.style ?? {};
        const gap = Number(style.doubleGap ?? 4); // px between the two lines
        const half = (gap / 2) * (this.scale ?? 1);

        const ptsA = offsetPolyline(pts, +half);
        const ptsB = offsetPolyline(pts, -half);

        // Draw both lines (no center line)
        this.paintLine(c, ptsA, this.isRounded);
        this.paintLine(c, ptsB, this.isRounded);

        // Intentionnel : pas de markers/arrows ici (conversation link = double trait)
        // Si un jour tu veux des flèches, il faudra aussi custom le dessin des markers.
    }

    // Optional: enlarge bbox so selection/hitbox isn't too tight
    override augmentBoundingBox(bbox: Rectangle): void {
        super.augmentBoundingBox(bbox);
        const style: any = this.style ?? {};
        const gap = Number(style.doubleGap ?? 4) * (this.scale ?? 1);
        bbox.x -= gap;
        bbox.y -= gap;
        bbox.width += 2 * gap;
        bbox.height += 2 * gap;
    }
}

function offsetPolyline(pts: Point[], offset: number): Point[] {
    const out: Point[] = [];
    const n = pts.length;

    for (let i = 0; i < n; i++) {
        // Average normals of previous + next segment for a decent corner offset
        let nx = 0;
        let ny = 0;

        if (i > 0) {
            const dx = pts[i].x - pts[i - 1].x;
            const dy = pts[i].y - pts[i - 1].y;
            const len = Math.hypot(dx, dy) || 1;
            nx += -dy / len;
            ny += dx / len;
        }

        if (i < n - 1) {
            const dx = pts[i + 1].x - pts[i].x;
            const dy = pts[i + 1].y - pts[i].y;
            const len = Math.hypot(dx, dy) || 1;
            nx += -dy / len;
            ny += dx / len;
        }

        const nlen = Math.hypot(nx, ny) || 1;
        nx /= nlen;
        ny /= nlen;

        out.push(new Point(pts[i].x + nx * offset, pts[i].y + ny * offset));
    }

    return out;
}
