// src/bpmn-export.ts
import type { Graph } from '@maxgraph/core';
import type { Geometry } from '@maxgraph/core';

type SizeLike = { x?: number; y?: number; width?: number; height?: number };

function centerOf(g: SizeLike) {
  const cx = (g?.x ?? 0) + (g?.width ?? 100) / 2;
  const cy = (g?.y ?? 0) + (g?.height ?? 60) / 2;
  return { cx, cy };
}

function perimeterPoint(bpmnType: string | undefined, g: SizeLike, tx: number, ty: number) {
  const { cx, cy } = centerOf(g);
  const dx = tx - cx;
  const dy = ty - cy;
  const w = (g?.width ?? 100);
  const h = (g?.height ?? 60);
  const hw = w / 2;
  const hh = h / 2;
  if ((Math.abs(dx) + Math.abs(dy)) < 1e-6) return { x: cx, y: cy };
  const tRect = () => {
    const tx = dx !== 0 ? Math.abs(hw / dx) : Infinity;
    const ty = dy !== 0 ? Math.abs(hh / dy) : Infinity;
    const t = Math.min(tx, ty);
    return { x: cx + dx * t, y: cy + dy * t };
  };
  const tEllipse = () => {
    const denom = Math.sqrt((dx*dx)/(hw*hw) + (dy*dy)/(hh*hh));
    if (denom < 1e-6) return { x: cx, y: cy };
    return { x: cx + dx/denom, y: cy + dy/denom };
  };
  const tDiamond = () => {
    const denom = (Math.abs(dx) / hw) + (Math.abs(dy) / hh);
    if (denom < 1e-6) return { x: cx, y: cy };
    const t = 1/denom;
    return { x: cx + dx * t, y: cy + dy * t };
  };
  switch (bpmnType) {
    case 'startEvent':
    case 'endEvent': return tEllipse();
    case 'exclusiveGateway': return tDiamond();
    default: return tRect();
  }
}

function getAbsoluteGeometry(graph: Graph, cell: any): Geometry {
  const view: any = (graph as any).getView?.();
  const state = view?.getState?.(cell);
  if (state && typeof state.x === 'number' && typeof state.y === 'number') {
    return { x: state.x, y: state.y, width: state.width, height: state.height } as Geometry;
  }
  let x = 0, y = 0;
  const g: any = cell.getGeometry?.();
  if (g) { x += g.x ?? 0; y += g.y ?? 0; }
  let p = cell.getParent?.();
  while (p && p !== (graph as any).getDefaultParent?.()) {
    const pg = p.getGeometry?.();
    if (pg) { x += pg.x ?? 0; y += pg.y ?? 0; }
    p = p.getParent?.();
  }
  const w = g?.width ?? 0;
  const h = g?.height ?? 0;
  return { x, y, width: w, height: h } as Geometry;
}

function getAbsoluteEdgePoints(graph: Graph, e: any): { x: number; y: number }[] | null {
  const view: any = (graph as any).getView?.();
  const state = view?.getState?.(e);
  const pts = state?.absolutePoints;
  if (Array.isArray(pts) && pts.length >= 2 && typeof pts[0]?.x === 'number') {
    return pts.map((p: any) => ({ x: p.x, y: p.y }));
  }
  return null;
}


function getLabelBounds(graph: Graph, cell: any, fallback: {x:number;y:number;width:number;height:number}) {
  // Try to get label text bounds from view state
  const view: any = (graph as any).getView?.();
  const state = view?.getState?.(cell);
  // In maxGraph, state.text?.boundingBox (or bounds) may hold label rectangle
  const t: any = state?.text;
  const bb: any = t?.boundingBox || t?.bounds || t?.shape?.bounds;
  if (bb && typeof bb.x === 'number' && typeof bb.y === 'number' &&
      typeof bb.width === 'number' && typeof bb.height === 'number') {
    return { x: bb.x, y: bb.y, width: bb.width, height: bb.height };
  }
  // Fallback: place a small label box below the shape, centered
  const cx = fallback.x + fallback.width / 2;
  const w = Math.max(40, Math.min(160, fallback.width));
  const h = 14;
  const x = cx - w / 2;
  const y = fallback.y + fallback.height + 6;
  return { x, y, width: w, height: h };
}

function dedupe(points: {x:number;y:number}[], eps = 0.5) {
  if (!points.length) return points;
  const out: {x:number;y:number}[] = [points[0]];
  for (let i=1;i<points.length;i++) {
    const a = out[out.length-1], b = points[i];
    if (Math.hypot((a.x-b.x), (a.y-b.y)) > eps) out.push(b);
  }
  return out;
}


export function exportGraphToBpmn(graph: Graph): string {
  const parent   = graph.getDefaultParent();
  const vertices = graph.getChildVertices(parent);
  const edges    = graph.getChildEdges(parent);

  const xmlNodes: string[] = [];
  const xmlFlows: string[] = [];
  const diShapes: string[] = [];
  const diEdges: string[]  = [];

  const ensureId = (c: any, idx: number, prefix: string) => (c.getId?.() ?? `${prefix}_${idx}`);

  // Noeuds
  vertices.forEach((v, i) => {
    const type  = (v as any).bpmnType as string | undefined;
    const id    = ensureId(v, i, 'Node');
    const label = String(v.getValue?.() ?? type ?? 'Node');

    let tag = 'task';
    if (type == 'userTask') tag = 'userTask';
    else if (type === 'startEvent') tag = 'startEvent';
    else if (type === 'endEvent') tag = 'endEvent';
    else if (type === 'exclusiveGateway') tag = 'exclusiveGateway';

    xmlNodes.push(`<${tag} id="${id}" name="${escapeXml(label)}"/>`);

    const geo = v.getGeometry() as Geometry;
    const { x, y, width, height } = geo ?? ({} as Geometry);

    // Label bounds (from state when possible)
    const lb = getLabelBounds(graph, v, { x: x ?? 0, y: y ?? 0, width: width ?? 100, height: height ?? 60 });

    diShapes.push(
      `<bpmndi:BPMNShape id="Shape_${id}" bpmnElement="${id}">
         <dc:Bounds x="${x ?? 0}" y="${y ?? 0}" width="${width ?? 100}" height="${height ?? 60}"/>
         <bpmndi:BPMNLabel>
           <dc:Bounds x="${lb.x}" y="${lb.y}" width="${lb.width}" height="${lb.height}"/>
         </bpmndi:BPMNLabel>
       </bpmndi:BPMNShape>`
    );

  });

  // Flux
  edges.forEach((e, i) => {
    const id    = ensureId(e, i, 'Flow');
    const label = String(e.getValue?.() ?? '');
    const s = (e as any).getTerminal?.(true);
    const t = (e as any).getTerminal?.(false);
    if (!s || !t) return;
    const sid = s.getId?.() ?? `Node_${vertices.indexOf(s)}`;
    const tid = t.getId?.() ?? `Node_${vertices.indexOf(t)}`;

    xmlFlows.push(
      `<sequenceFlow id="${id}" sourceRef="${sid}" targetRef="${tid}"${label ? ` name="${escapeXml(label)}"` : ''}/>`
    );

    // waypoints: use absolutePoints if available (includes bends and clipped endpoints)
    let waypoints = getAbsoluteEdgePoints(graph, e);

    if (!waypoints) {
      // fallback: build from geometry points + perimeter intersections
      const sAbs = getAbsoluteGeometry(graph, s);
      const tAbs = getAbsoluteGeometry(graph, t);
      const sType = (s as any).bpmnType as string | undefined;
      const tType = (t as any).bpmnType as string | undefined;

      const geo: any = e.getGeometry?.();
      const route = (geo?.points ?? []) as Array<{x:number;y:number}>;

      const approachS: any = route.length ? route[0] : centerOf(tAbs);
      const approachT: any = route.length ? route[route.length - 1] : centerOf(sAbs);

      const sp = perimeterPoint(sType, sAbs, approachS.x ?? approachS.cx, approachS.y ?? approachS.cy);
      const tp = perimeterPoint(tType, tAbs, approachT.x ?? approachT.cx, approachT.y ?? approachT.cy);

      waypoints = [sp, ...route, tp];
    }

    waypoints = dedupe(waypoints);

    diEdges.push(
      `<bpmndi:BPMNEdge id="Edge_${id}" bpmnElement="${id}">
         ${waypoints.map(p => `<di:waypoint x="${p.x}" y="${p.y}"/>`).join('')}
       </bpmndi:BPMNEdge>`
    );
  });

  return `<?xml version="1.0" encoding="UTF-8"?>
<definitions xmlns="http://www.omg.org/spec/BPMN/20100524/MODEL"
             xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI"
             xmlns:dc="http://www.omg.org/spec/DD/20100524/DC"
             xmlns:di="http://www.omg.org/spec/DD/20100524/DI"
             id="Defs_1"
             targetNamespace="http://example.bpmn">
  <process id="Process_1" name="Exported from MaxGraph" isExecutable="false">
    ${xmlNodes.join('\n    ')}
    ${xmlFlows.join('\n    ')}
  </process>
  <bpmndi:BPMNDiagram id="BPMNDiagram_1">
    <bpmndi:BPMNPlane id="BPMNPlane_1" bpmnElement="Process_1">
      ${diShapes.join('\n      ')}
      ${diEdges.join('\n      ')}
    </bpmndi:BPMNPlane>
  </bpmndi:BPMNDiagram>
</definitions>`;
}

function escapeXml(s: string): string {
  return s.replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&apos;');
}
