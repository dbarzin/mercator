// src/graph-styles.ts
import {
    Graph,
    CellStyle,
    EdgeMarkerRegistry,
} from "@maxgraph/core";
import { cloneUtils, Perimeter } from "@maxgraph/core";
import { RectangleShape, ShapeRegistry } from "@maxgraph/core";
import { BpmnDataObjectShape } from "./bpmn-shapes";
import { DoubleLineConnectorShape } from "./bpmn-double-line";

type StyleKey = keyof CellStyle;

function deriveStyle(
    base: CellStyle,
    overrides: Partial<CellStyle>,
    remove: StyleKey[] = []
): CellStyle {
    const s = cloneUtils.clone(base) as CellStyle;

    // supprime explicitement les clés qui ne doivent JAMAIS “fuiter”
    for (const k of remove) {
        // eslint-disable-next-line @typescript-eslint/no-dynamic-delete
        delete (s as any)[k];
    }

    Object.assign(s, overrides);
    return s;
}

/**
 * Applique les styles (vertex/edge + styles nommés) au stylesheet du graph.
 * À appeler une seule fois après la création du Graph.
 */
export function applyGraphStyles(graph: Graph) {

    //==================================================================
    // Customisation du style des flèches
    let style = graph.getStylesheet().getDefaultEdgeStyle();
    style.fontColor = '#000000';
    style.strokeColor = '#000000';
    style.strokeWidth = 1.5;

    //==================================================================
    const stylesheet = graph.getStylesheet();

    // =========================
    // 1) Base stable (VERTEX)
    // =========================
    // IMPORTANT : on clone le style par défaut pour s’en servir comme “socle”
    const defaultVertex = stylesheet.getDefaultVertexStyle();
    const baseVertex: CellStyle = deriveStyle(
        defaultVertex,
        {
            whiteSpace: "wrap",
            overflow: "hidden",
            strokeWidth: 2,
            verticalAlign: "middle",
            fontSize: 11,
            startSize: 22,
            fontColor: "black",
            strokeColor: "black",
            foldable: false,
            fillColor: "#ffffff",
            labelBackgroundColor: "none",
        },
    );

    // Si tu veux VRAIMENT changer le default vertex style global, fais-le explicitement
    // (selon version MaxGraph, la méthode peut varier ; sinon tu peux laisser tel quel)
    // stylesheet.putDefaultVertexStyle?.(baseVertex);
    // Sinon : on garde ton approche in-place, mais sans réutiliser "style" derrière.
    Object.assign(defaultVertex, baseVertex);

    // =========================
    // 2) Styles nommés (VERTEX)
    // =========================

    // Lane (dérive UNIQUEMENT de baseVertex)
    const laneStyle = deriveStyle(baseVertex,
        {
            shape: "swimlane",
            horizontal: false,

            // IMPORTANT : rendre la surface "peinte"
            fillColor: "#ffffff",
            swimlaneFillColor: "#ffffff",

            // pour éviter une transparence héritée
            opacity: 100,
            fillOpacity: 100,

            strokeColor: "#000000",

        });
    stylesheet.putCellStyle("lane", laneStyle);

    // ================================================
    // Process (dérive UNIQUEMENT de baseVertex)
    const processStyle = deriveStyle(
        baseVertex,
        {
            shape: "rectangle",
            fontSize: 10,
            rounded: true,
            horizontal: true,
            verticalAlign: "middle",
            labelBackgroundColor: "none",
            pointerEvents: true,

            overflow: "visible",         // "auto" peut provoquer des comportements bizarres
            whiteSpace: "wrap",          // plutôt que "normal" ici

        },
        ["startSize"] // pas de startSize sur un rectangle
    );

    stylesheet.putCellStyle("process", processStyle);

    //=============================================================
    // Activities

    const activitiesStyle = deriveStyle(
        baseVertex,
        {
            shape: "rectangle",
            strokeWidth: 1,
            fontSize: 10,
            rounded: false,
            horizontal: true,
            labelBackgroundColor: "none",

            // Centrage horizontal du label
            align: "center",

            // Label sous l'objet
            verticalLabelPosition: "bottom",
            verticalAlign: "top",

            overflow: "visible",         // "auto" peut provoquer des comportements bizarres
            whiteSpace: "wrap",          // plutôt que "normal" ici

        },
        ["startSize"] // pas de startSize sur un rectangle
    );

    stylesheet.putCellStyle("activities", activitiesStyle);

    // Condition (dérive UNIQUEMENT de baseVertex, pas de processStyle)
    const gatewayStyle = deriveStyle(
        baseVertex,
        {
            shape: "rhombus",
            perimeter: Perimeter.RhombusPerimeter,
            strokeColor: "none",
            resizable: false,

            // Label sous l'objet
            verticalLabelPosition: "bottom",
            verticalAlign: "top",

            // 🔥 FOND NON TRANSPARENT
            labelBackgroundColor: "#FFFFFF",
            labelBorderColor: "#FFFFFF",

            // Optionnel mais recommandé
            spacingTop: 0,

            // Décalage horizontal vers la gauche
            spacingLeft: -60,

            // Affichage
            overflow: "visible",
        },
        ["rounded", "startSize"] // ellipse + pas de startSize
    );
    stylesheet.putCellStyle("gateway", gatewayStyle);

    const stateStyle = deriveStyle(
        baseVertex,
        {
            shape: "ellipse",
            perimeter: Perimeter.EllipsePerimeter,
            strokeColor: "none",
            resizable: false,

            // Centrage horizontal du label
            align: "center",

            // Label sous l'objet
            verticalLabelPosition: "bottom",
            verticalAlign: "top",

            // reset héritages
            spacingTop: 0,
            spacingLeft: 0,
            spacingRight: 0,
            spacingBottom: 6,

            overflow: "visible",         // "auto" peut provoquer des comportements bizarres
            whiteSpace: "wrap",          // plutôt que "normal" ici
        },
        ["rounded", "startSize"] // ellipse + pas de startSize
    );
    stylesheet.putCellStyle("state", stateStyle);


    // Register shapes
    ShapeRegistry.add("bpmnDataObjectShape", BpmnDataObjectShape);

    // Data (style autonome : PAS de baseVertex)
    stylesheet.putCellStyle("data", {
        shape: "bpmnDataObjectShape",
        fillColor: "#ffffff",
        strokeColor: "#000000",
        strokeWidth: 2,
        fontSize: 11,
        fontColor: "#000000",
        align: "center",
        verticalAlign: "middle",
        whiteSpace: "wrap",
        spacingTop: 6,
        spacingBottom: 6,
        spacingLeft: 4,
        spacingRight: 4,
    } as CellStyle);

    // Database (style autonome : PAS de baseVertex)
    stylesheet.putCellStyle("database", {
        shape: "cylinder",
        whiteSpace: "wrap",
        fillColor: "#ffffff",
        strokeColor: "#000000",
        align: "center",
        verticalAlign: "middle",
        fontSize: 12,
        fontColor: "#000000",
    } as CellStyle);

    // ============================================
    // 3) Styles icônes en haute à gauche du vertex
    // ============================================
    const iconStyle: CellStyle = {
        fillColor: "none",
        strokeColor: "none",
        resizable: false,
        movable: false,
        rotatable: false,
        editable: false,

        align: "center",
        verticalAlign: "middle",

        fontSize: 22,
        fontColor: "#000000",
        fontFamily: "BPMN",

        // ✅ évite que le texte soit coupé
        overflow: "visible",
        whiteSpace: "nowrap",

        // ✅ donne de l’air (la fonte BPMN déborde souvent)
        spacingLeft: 4,
        spacingRight: 4,
        spacingTop: 2,
        spacingBottom: -2,

        // ✅ Désactive tous les événements pointer sur l'élément SVG
        pointerEvents: false,
    };
    stylesheet.putCellStyle("bpmnIcon", iconStyle);

    const stateIconStyle: CellStyle = {
        fillColor: "none",
        strokeColor: "none",
        resizable: false,
        movable: false,
        rotatable: false,
        editable: false,

        align: "center",
        verticalAlign: "middle",

        fontSize: 50,
        fontColor: "#000000",
        fontFamily: "BPMN",

        // ✅ évite que le texte soit coupé
        overflow: "visible",
        whiteSpace: "nowrap",

        // ✅ donne de l’air (la fonte BPMN déborde souvent)
        spacingLeft: 4,
        spacingRight: 4,
        spacingTop: 2,
        spacingBottom: -2,

        // ✅ Désactive tous les événements pointer sur l'élément SVG
        pointerEvents: false,
    };


    stylesheet.putCellStyle("stateIcon", stateIconStyle);

    // Badge en dessous au milieu du Vertex
    const badgeStyle = deriveStyle(baseVertex, {
        fillColor: "none",
        strokeColor: "none",
        resizable: false,
        movable: false,
        rotatable: false,
        editable: false,

        align: "center",
        verticalAlign: "middle",

        fontSize: 22,
        fontColor: "#000000",
        fontFamily: "BPMN",

        // ✅ évite que le texte soit coupé
        overflow: "visible",
        whiteSpace: "nowrap",

        // ✅ donne de l’air (la fonte BPMN déborde souvent)
        spacingLeft: 4,
        spacingRight: 4,
        spacingTop: 2,
        spacingBottom: -2,

        // ✅ Désactive tous les événements pointer sur l'élément SVG
        pointerEvents: false,
    });

    graph.getStylesheet().putCellStyle("bpmnBadge", badgeStyle);


    // =========================
    // 4) Base stable (EDGE)
    // =========================
    const baseEdge = cloneUtils.clone(stylesheet.getDefaultEdgeStyle()) as CellStyle;

    const bpmnEdgeStyle = deriveStyle(baseEdge, {
        strokeColor: "#000000",
        strokeWidth: 1,
        fontColor: "#000000",
        endArrow: "block",
        endFill: true,
        shadow: false,

        // Désactivation explicite du remplissage
        fillColor: "none",
        fillOpacity: 0,

        // Pour les edges orthogonaux
        shape: "connector",

        // placement du label autour de l'edge
        labelPosition: "center",
        verticalLabelPosition: "bottom",

        // FOND NON TRANSPARENT
        labelBackgroundColor: "#FFFFFF",
        labelBorderColor: "#FFFFFF",

        // Affichage
        overflow: "visible",

        // décalage (pixels)
        spacing: 8,

        edgeStyle: "orthogonalEdgeStyle", // ou "elbowEdgeStyle"

    });
    stylesheet.putCellStyle("bpmn-edge", bpmnEdgeStyle);

    // =========================
    // Add new shapes to the registry (required for BPMN annotations)
    // =========================
    ShapeRegistry.add("bpmnAnnotation", BpmnAnnotationShape);

    const annotationStyle = deriveStyle(
        baseVertex,
        {
            shape: "bpmnAnnotation",
            resizable: true,

            // pas de fond
            fillColor: "none",
            gradientColor: "none",

            // traits visibles
            strokeColor: "black",
            strokeWidth: 2,

            // texte
            fontSize: 10,
            align: "left",
            verticalAlign: "top",
            spacingLeft: 6,
            spacingTop: 4,

            overflow: "visible",
            whiteSpace: "wrap",

            // ✅ rend la cellule “cliquable” même si le rendu est transparent
            pointerEvents: true,
        },
        ["rounded", "startSize"] // on évite les héritages inutiles
    );

    stylesheet.putCellStyle("annotation", annotationStyle);

    // ===========================================================
    // Add conversation shape

    ShapeRegistry.add("pentagonShape", HexagonShape);

    const pentagonStyle = deriveStyle(
        baseVertex,
        {
            shape: "pentagonShape",
            resizable: false,

            // pas de fond
            fillColor: "none",
            gradientColor: "none",

            // traits visibles
            strokeColor: "black",
            strokeWidth: 2,

            // Centrage horizontal du label
            align: "center",

            // Label sous l'objet
            verticalLabelPosition: "bottom",
            verticalAlign: "top",

            labelBackgroundColor: "#FFFFFF",

            // reset héritages
            spacingTop: 0,
            spacingLeft: 0,
            spacingRight: 0,
            spacingBottom: 6,

            overflow: "visible",         // "auto" peut provoquer des comportements bizarres
            whiteSpace: "wrap",          // plutôt que "normal" ici
        },
        ["rounded", "startSize"] // on évite les héritages inutiles
    );

    stylesheet.putCellStyle("conversation", pentagonStyle);

    EdgeMarkerRegistry.add("bpmnSlash",
        (canvas, _shape, _type, pe, unitX, unitY, size, _source, sw, _filled) => {

            const offset = (size / 2) + sw - 20;

            // On retourne la fonction de dessin
            return () => {
                // Slash à 45° par rapport à l’edge
                const nx = -unitY;
                const ny = unitX;

                const half = size * 0.55;

                const ax = (unitX + nx) / Math.SQRT2;
                const ay = (unitY + ny) / Math.SQRT2;

                const cx = pe.x + unitX * offset;
                const cy = pe.y + unitY * offset;

                canvas.begin();
                canvas.moveTo(cx - ax * half, cy - ay * half);
                canvas.lineTo(cx + ax * half, cy + ay * half);
                canvas.stroke();
            };
        }
    );

    EdgeMarkerRegistry.add(
        "bpmnMessage",
        (canvas, shape, _type, pe, unitX, unitY, size, _source, sw, _filled) => {

            const offset = (size / 2) + sw;
            const r = size * 0.35;

            return () => {
                const cx = pe.x - (unitX / 2) * offset;
                const cy = pe.y - (unitY / 2) * offset;

                // Couleur du trait de l’edge (fallback noir)
                const stroke =
                    (shape as any).stroke ??
                    (shape as any).strokeColor ??
                    (shape as any).style?.strokeColor ??
                    "#000000";

                // --- FILL (blanc)
                canvas.setFillColor("#FFFFFF");
                canvas.begin();
                canvas.ellipse(cx - r, cy - r, r * 2, r * 2);
                canvas.fill();

                // --- STROKE (même couleur que l’edge)
                canvas.setStrokeColor(stroke);
                canvas.setStrokeWidth(Math.max(1, sw)); // évite sw=0 => invisible
                canvas.begin();
                canvas.ellipse(cx - r, cy - r, r * 2, r * 2);
                canvas.stroke();
            };
        }
    );

    // =============================================================================
    // Double line connector

    ShapeRegistry.add("bpmnConversationLink", DoubleLineConnectorShape);

    const conversationLinkStyle: CellStyle = {
        shape: "bpmnConversationLink",
        startArrow: "none",
        endArrow: "none",
        strokeWidth: 1,

        // FOND NON TRANSPARENT
        labelBackgroundColor: "#FFFFFF", // OK

        // edgeStyle: "orthogonalEdgeStyle", // si tu veux des coudes
        // curved: false,                   // garde en polyline
        // IMPORTANT: clé custom => cast en any si ton TS râle
        ...( { doubleGap: 4 } as any ),     // distance entre les 2 traits
    };

    stylesheet.putCellStyle("bpmnConversationLink", conversationLinkStyle);

    // =============================================================================
    // Marker : cercle centré sur la bordure du vertex source
    // Le centre du cercle coincide avec le point de connexion (bord du vertex).
    // La ligne de l'edge démarre sur le bord extérieur du cercle.
    // =============================================================================

    EdgeMarkerRegistry.add(
        "bpmnEventCircle",
        (_canvas, _shape, _type, pe, unitX, unitY, size, _source, _sw, _filled) => {
            const r = size * 0.5;

            // Décale pe vers l'extérieur : la ligne part du bord du cercle
            pe.x += unitX * r;
            pe.y += unitY * r;

            // Rien à dessiner : le cercle blanc est rendu par la cellule enfant
            return () => {};
        }
    );

    stylesheet.putCellStyle("bpmnEventCircleBg", {
        shape: "ellipse",
        fillColor: "#FFFFFF",
        strokeColor: "#000000",
        strokeWidth: 2,
        resizable: false,
        movable: false,
        rotatable: false,
        editable: false,
        pointerEvents: false,
    } as CellStyle);

    // Edge avec cercle sur le vertex source (ex : Message Flow BPMN)
    const eventFlowStyle = deriveStyle(bpmnEdgeStyle, {
        startArrow: "bpmnEventCircle",
        startSize: 40,      // diamètre apparent — size = 14 dans le marker
        startFill: false,   // cercle creux (blanc) ; true = cercle plein
        endArrow: "open",   // ou "block", "none", selon la sémantique BPMN
        endFill: false,
    });

    stylesheet.putCellStyle("bpmnEventCircleIcon", {
        fillColor: "#FFFFFF",
        strokeColor: "none",
        resizable: false,
        movable: false,
        rotatable: false,
        editable: false,

        align: "center",
        verticalAlign: "middle",

        fontSize: 50,           // même valeur que stateIcon
        fontColor: "#000000",
        fontFamily: "BPMN",

        overflow: "visible",
        whiteSpace: "nowrap",
        pointerEvents: false,
    } as CellStyle);

    stylesheet.putCellStyle("bpmnEventFlow", eventFlowStyle);
}

// =============================================================================
// AnnotationShape
//
// Dessine un rectangle avec bords ouverts sur la droite
//

export class BpmnAnnotationShape extends RectangleShape {
    override paintVertexShape(c: any, x: number, y: number, w: number, h: number): void {
        // --- zone de capture invisible (hit area) ---
        c.save();
        c.begin();
        c.rect(x, y, w, h);
        c.fill();
        c.restore();

        const x1 = x;
        const y1 = y;
        const x2 = x + (w/3);
        const y2 = y + h;

        c.begin();

        // top
        c.moveTo(x1, y1);
        c.lineTo(x2, y1);

        // left
        c.moveTo(x1, y1);
        c.lineTo(x1, y2);

        // bottom
        c.moveTo(x1, y2);
        c.lineTo(x2, y2);

        c.stroke();
    }
}

// =============================================================================
// HexagonShape
//
// Dessine un hexagone inscrit dans le rectangle de la cellule.
//
//    ___
//   /   \
//  |     |
//   \___/
//
export class HexagonShape extends RectangleShape {

    override paintVertexShape(c: any, x: number, y: number, w: number, h: number): void {

        // Calcul des points de l'hexagone
        const topLeftX = x + w * 0.25;
        const topLeftY = y;

        const topRightX = x + w * 0.75;
        const topRightY = y;

        const rightX = x + w;
        const rightY = y + h / 2;

        const bottomRightX = x + w * 0.75;
        const bottomRightY = y + h;

        const bottomLeftX = x + w * 0.25;
        const bottomLeftY = y + h;

        const leftX = x;
        const leftY = y + h / 2;

        c.begin();
        c.moveTo(topLeftX, topLeftY);
        c.lineTo(topRightX, topRightY);
        c.lineTo(rightX, rightY);
        c.lineTo(bottomRightX, bottomRightY);
        c.lineTo(bottomLeftX, bottomLeftY);
        c.lineTo(leftX, leftY);
        c.close();

        c.fillAndStroke();
    }
}

//=====================================================

function svgToDataUri(svg: string): string {
    // Encodage safe pour data:image/svg+xml
    const encoded = encodeURIComponent(svg)
        .replace(/'/g, "%27")
        .replace(/"/g, "%22");
    return `data:image/svg+xml;charset=utf-8,${encoded}`;
}


