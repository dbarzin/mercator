import {
  Graph,
  CellEditorHandler,
  SelectionCellsHandler,
  SelectionHandler,
  RubberBandHandler,
  PanningHandler,
  GraphPluginConstructor,
  ConnectionHandler,
  InternalMouseEvent,
  CellState,
  Geometry,
  ConnectionConstraint,
  Point,
} from '@maxgraph/core';

// --- ConnectionHandler personnalisé (comme dans ton snippet)
class MyCustomConnectionHandler extends ConnectionHandler {
  override createEdgeState(_me: InternalMouseEvent) {
    const edge = this.graph.createEdge(null, null as any, null, null, null);
    return new CellState(this.graph.view, edge, this.graph.getCellStyle(edge));
  }
}

// --- Géométrie avec points d’ancrage (constraints)
class MyCustomGeometryClass extends Geometry {
  constraints = [
    new ConnectionConstraint(new Point(0.25, 0), true),
    new ConnectionConstraint(new Point(0.5, 0), true),
    new ConnectionConstraint(new Point(0.75, 0), true),
    new ConnectionConstraint(new Point(0, 0.25), true),
    new ConnectionConstraint(new Point(0, 0.5), true),
    new ConnectionConstraint(new Point(0, 0.75), true),
    new ConnectionConstraint(new Point(1, 0.25), true),
    new ConnectionConstraint(new Point(1, 0.5), true),
    new ConnectionConstraint(new Point(1, 0.75), true),
    new ConnectionConstraint(new Point(0.25, 1), true),
    new ConnectionConstraint(new Point(0.5, 1), true),
    new ConnectionConstraint(new Point(0.75, 1), true),
  ];
}

// --- Graph avec tes plugins (ordre OK)
class MyCustomGraph extends Graph {
  constructor(container: HTMLElement, plugins: GraphPluginConstructor[]) {
    super(container, undefined, plugins);
  }
  override getAllConnectionConstraints(terminal: CellState | null, _source: boolean) {
    return (terminal?.cell?.geometry as MyCustomGeometryClass)?.constraints ?? null;
  }
}

// --- Init “prête à l’emploi”
function initMaxGraphDemo(container: HTMLElement) {
  // Assure-toi que le container a une taille visible
  if (container.clientWidth === 0 || container.clientHeight === 0) {
    container.style.width = '100%';
    container.style.height = '400px';
    container.style.border = '1px solid #ddd';
  }

  const plugins: GraphPluginConstructor[] = [
    CellEditorHandler,
    SelectionCellsHandler,
    MyCustomConnectionHandler,
    SelectionHandler,
    PanningHandler,
    RubberBandHandler,
  ];

const graph = new Graph(container, undefined, [CellEditorHandler, SelectionHandler, PanningHandler, RubberBandHandler]);
graph.enterStopsCellEditing = true;
graph.setConnectable(true);

  // Style d’arête par défaut (orthogonal)
  graph.getStylesheet().getDefaultEdgeStyle().edgeStyle = 'orthogonalEdgeStyle';


  const parent = graph.getDefaultParent();

  graph.batchUpdate(() => {
    const v1 = graph.insertVertex({
      parent,
      value: 'Hello,',
      position: [20, 20],
      size: [80, 30],
      geometryClass: MyCustomGeometryClass,
    });

    const v2 = graph.insertVertex({
      parent,
      value: 'World!',
      position: [200, 150],
      size: [80, 30],
      geometryClass: MyCustomGeometryClass,
    });

    graph.insertEdge({ parent, value: '', source: v1, target: v2 });
  });

  return graph;
}

// --- Monte tout une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('graph-container') as HTMLElement | null;
  if (!container) {
    console.error('Container #graph-container introuvable');
    return;
  }
  initMaxGraphDemo(container);
});
