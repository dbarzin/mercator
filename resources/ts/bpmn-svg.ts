import { SvgCanvas2D, ImageExport } from "@maxgraph/core";

export function exportGraphToSvg(graph: any): SVGSVGElement {
    // 1) Valide la vue
    graph.getView().validate();

    const bounds = graph.getGraphBounds();
    const scale = graph.getView().scale;

    // 2) Crée la racine SVG
    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    svg.setAttribute("version", "1.1");
    svg.setAttribute("width", String(Math.ceil(bounds.width * scale)));
    svg.setAttribute("height", String(Math.ceil(bounds.height * scale)));
    svg.setAttribute("viewBox", `0 0 ${bounds.width} ${bounds.height}`);

    // Fond blanc
    const bg = document.createElementNS("http://www.w3.org/2000/svg", "rect");
    bg.setAttribute("x", "0");
    bg.setAttribute("y", "0");
    bg.setAttribute("width", String(bounds.width));
    bg.setAttribute("height", String(bounds.height));
    bg.setAttribute("fill", "#fff");
    svg.appendChild(bg);

    // 3) Canvas SVG personnalisé qui force fill="none" sur les edges
    const canvas = new CustomSvgCanvas2D(svg, true, graph);
    canvas.scale(scale);
    canvas.translate(-bounds.x, -bounds.y);

    const exporter = new ImageExport();

    // 4) Dessine
    const model = graph.getDataModel?.() ?? graph.getModel?.();
    const root = model.getRoot();
    const rootState = graph.getView().getState(root);

    if (rootState) {
        exporter.drawState(rootState, canvas);
    }

    return svg;
}

// Canvas SVG personnalisé qui intercepte la création des paths
class CustomSvgCanvas2D extends SvgCanvas2D {
    private graph: any;

    constructor(root: SVGElement, styleEnabled: boolean, graph: any) {
        super(root, styleEnabled);
        this.graph = graph;
    }

    // Surcharge la méthode qui ajoute un path au SVG
    addNode(filled: boolean, stroked: boolean): void {
        const node = this.node;
        const s = this.state;

        if (node != null) {
            if (node.nodeName === 'path') {
                // Pour tous les paths, force fill="none" sauf si c'est explicitement une forme remplie
                if (s.fillColor == null || s.fillColor === 'none') {
                    node.setAttribute('fill', 'none');
                } else if (filled) {
                    node.setAttribute('fill', s.fillColor);
                } else {
                    node.setAttribute('fill', 'none');
                }

                // Gère le stroke
                if (stroked && s.strokeColor != null) {
                    node.setAttribute('stroke', s.strokeColor);
                    if (s.strokeWidth != null) {
                        node.setAttribute('stroke-width', String(s.strokeWidth));
                    }
                }
            }

            // Appel à la méthode parente pour le reste du traitement
            super.addNode(filled, stroked);
        }
    }
}

export function downloadSvg(svg: SVGSVGElement, filename = "diagram.svg") {
    const svgString = new XMLSerializer().serializeToString(svg);
    const blob = new Blob([svgString], { type: "image/svg+xml;charset=utf-8" });
    const url = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    a.click();

    URL.revokeObjectURL(url);
}


function arrayBufferToBase64(buf: ArrayBuffer): string {
    const bytes = new Uint8Array(buf);
    let binary = "";
    for (let i = 0; i < bytes.length; i++) binary += String.fromCharCode(bytes[i]);
    return btoa(binary);
}

export async function embedFontInSvg(
    svg: SVGSVGElement,
    options: {
        fontUrl: string;          // ex: "/fonts/MyFont.woff2" (même origine ou CORS ok)
        fontFamily: string;       // ex: "MyFont"
        mime?: string;            // ex: "font/woff2" (ou "font/woff", "font/ttf")
    }
) {
    const mime = options.mime ?? "font/ttf";

    const res = await fetch(options.fontUrl);
    if (!res.ok) throw new Error(`Impossible de charger la font: ${res.status} ${res.statusText}`);

    const buf = await res.arrayBuffer();
    const b64 = arrayBufferToBase64(buf);

    // <defs><style>...</style></defs>
    const defs =
        svg.querySelector("defs") ??
        svg.insertBefore(document.createElementNS("http://www.w3.org/2000/svg", "defs"), svg.firstChild);

    const style = document.createElementNS("http://www.w3.org/2000/svg", "style");
    style.setAttribute("type", "text/css");

    style.textContent = `
@font-face {
  font-family: "${options.fontFamily}";
  src: url("data:${mime};base64,${b64}") format("${mime.includes("woff2") ? "woff2" : mime.includes("woff") ? "woff" : "truetype"}");
  font-weight: normal;
  font-style: normal;
}
  `.trim();

    defs.appendChild(style);
}
