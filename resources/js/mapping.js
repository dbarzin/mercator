/*
Copyright 2024-present The maxGraph project Contributors

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/


import '/resources/css/mapping.css';
import {
  InternalMouseEvent,
  CellRenderer,
  CellState,
  Client,
  constants,
  Graph,
  InternalEvent,
  MarkerShape,
  StyleRegistry,
  ConnectionHandler,
  CellEditorHandler, SelectionCellsHandler, MyCustomConnectionHandler, SelectionHandler
} from '@maxgraph/core';

const initializeGraph = (container) => {
  // Disables the built-in context menu
  InternalEvent.disableContextMenu(container);

/*
  const graph = new Graph(
    container,
    undefined,
    [] // override default plugins, use none
  );
*/

//-----------------------------------------------------------------------

  class MyCustomConnectionHandler extends ConnectionHandler {
    // Enables connect preview for the default edge style
    createEdgeState(_me) {
      edge = this.graph.createEdge(null, null, null, null, null);
      return new CellState(this.graph.view, edge, this.graph.getCellStyle(edge));
    }
  }

  // Use a dedicated set of plugins to use MyCustomConnectionHandler and to not use extra plugins not needed here
  plugins = [CellEditorHandler, SelectionCellsHandler, MyCustomConnectionHandler, SelectionHandler];
  // Enables rubberband selection
  if (args.rubberBand) plugins.push(RubberBandHandler);
  class MyCustomGraph extends Graph {
    constructor(container) {
      super(container, undefined, plugins);
    }
    getAllConnectionConstraints = (terminal, _source) => {
      // Overridden to define per-geometry connection points
      return terminal.cell.geometry.constraints;
    };
  }
  class MyCustomGeometryClass extends Geometry {
    // Defines the default constraints for the vertices
    constraints = [new ConnectionConstraint(new Point(0.25, 0), true), new ConnectionConstraint(new Point(0.5, 0), true), new ConnectionConstraint(new Point(0.75, 0), true), new ConnectionConstraint(new Point(0, 0.25), true), new ConnectionConstraint(new Point(0, 0.5), true), new ConnectionConstraint(new Point(0, 0.75), true), new ConnectionConstraint(new Point(1, 0.25), true), new ConnectionConstraint(new Point(1, 0.5), true), new ConnectionConstraint(new Point(1, 0.75), true), new ConnectionConstraint(new Point(0.25, 1), true), new ConnectionConstraint(new Point(0.5, 1), true), new ConnectionConstraint(new Point(0.75, 1), true)];
  }

  // Edges have no connection points
  // PolylineShape.prototype.constraints = null; // not useful here

  // Creates the graph inside the given container
  graph: Graph = new MyCustomGraph(container);
  graph.setConnectable(true);

//-----------------------------------------------------------------------

  // create a dedicated style for "ellipse" to share properties
  graph.getStylesheet().putCellStyle('myEllipse', {
    perimeter: 'ellipsePerimeter',
    shape: 'ellipse',
    verticalAlign: 'top',
    verticalLabelPosition: 'bottom',
  });

  // Custom code to unregister maxGraph style defaults
  CellRenderer.defaultShapes = {};
  MarkerShape.markers = {};
  StyleRegistry.values = {};

  // Adds cells to the model in a single step
  graph.batchUpdate(() => {
    // use the legacy insertVertex method
    const vertex01 = graph.insertVertex({
      value: 'a regular rectangle',
      position: [10, 10],
      size: [100, 100],
    });
    const vertex02 = graph.insertVertex({
      value: 'a regular ellipse',
      position: [350, 90],
      size: [50, 50],
      style: {
        baseStyleNames: ['myEllipse'],
      },
    });
    graph.insertEdge({
      value: 'an orthogonal style edge',
      source: vertex01,
      target: vertex02,
      style: {
        edgeStyle: constants.EDGESTYLE.ORTHOGONAL,
        rounded: true,
      },
    });

    const vertex11 = graph.insertVertex({
      value: 'another rectangle',
      position: [20, 200],
      size: [100, 100],
      style: {
        fillColor: 'red',
        fillOpacity: 20,
      },
    });
    const vertex12 = graph.insertVertex({
      value: 'another ellipse',
      x: 150,
      y: 350,
      width: 70,
      height: 70,
      style: {
        baseStyleNames: ['myEllipse'],
        fillColor: 'orange',
      },
    });
    graph.insertEdge({
      value: 'another edge',
      source: vertex11,
      target: vertex12,
      style: { endArrow: 'block' },
    });
  });
};


// display the maxGraph version in the footer
const footer = document.querySelector('footer');
//footer.innerText = `Built with maxGraph ${Client.VERSION}`;

// Creates the graph inside the given container
initializeGraph(document.querySelector('#graph-container'));



/*


Search for components


Introduction


Default


Default

({
  label,
  ...args
}: Record<string, string>) => {
  configureImagesBasePath();
  const container = createGraphContainer(args);
  class MyCustomConnectionHandler extends ConnectionHandler {
    // Enables connect preview for the default edge style
    createEdgeState(_me: InternalMouseEvent) {
      const edge = this.graph.createEdge(null, null!, null, null, null);
      return new CellState(this.graph.view, edge, this.graph.getCellStyle(edge));
    }
  }

  // Use a dedicated set of plugins to use MyCustomConnectionHandler and to not use extra plugins not needed here
  const plugins: GraphPluginConstructor[] = [CellEditorHandler, SelectionCellsHandler, MyCustomConnectionHandler, SelectionHandler];
  // Enables rubberband selection
  if (args.rubberBand) plugins.push(RubberBandHandler);
  class MyCustomGraph extends Graph {
    constructor(container: HTMLElement) {
      super(container, undefined, plugins);
    }
    getAllConnectionConstraints = (terminal: CellState | null, _source: boolean) => {
      // Overridden to define per-geometry connection points
      return (terminal?.cell?.geometry as MyCustomGeometryClass)?.constraints ?? null;
    };
  }
  class MyCustomGeometryClass extends Geometry {
    // Defines the default constraints for the vertices
    constraints = [new ConnectionConstraint(new Point(0.25, 0), true), new ConnectionConstraint(new Point(0.5, 0), true), new ConnectionConstraint(new Point(0.75, 0), true), new ConnectionConstraint(new Point(0, 0.25), true), new ConnectionConstraint(new Point(0, 0.5), true), new ConnectionConstraint(new Point(0, 0.75), true), new ConnectionConstraint(new Point(1, 0.25), true), new ConnectionConstraint(new Point(1, 0.5), true), new ConnectionConstraint(new Point(1, 0.75), true), new ConnectionConstraint(new Point(0.25, 1), true), new ConnectionConstraint(new Point(0.5, 1), true), new ConnectionConstraint(new Point(0.75, 1), true)];
  }

  // Edges have no connection points
  // PolylineShape.prototype.constraints = null; // not useful here

  // Creates the graph inside the given container
  const graph: Graph = new MyCustomGraph(container);
  graph.setConnectable(true);

  // Specifies the default edge style
  graph.getStylesheet().getDefaultEdgeStyle().edgeStyle = 'orthogonalEdgeStyle';

  // Gets the default parent for inserting new cells. This
  // is normally the first child of the root (ie. layer 0).
  const parent = graph.getDefaultParent();

  // Adds cells to the model in a single step
  graph.batchUpdate(() => {
    const v1 = graph.insertVertex({
      parent,
      value: 'Hello,',
      position: [20, 20],
      size: [80, 30],
      geometryClass: MyCustomGeometryClass
    });
    const v2 = graph.insertVertex({
      parent,
      value: 'World!',
      position: [200, 150],
      size: [80, 30],
      geometryClass: MyCustomGeometryClass
    });
    graph.insertEdge({
      parent,
      value: '',
      source: v1,
      target: v2
    });
  });
  return container;
}
*/
