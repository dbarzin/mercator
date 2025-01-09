import {
    Graph,
    CellEditorHandler,
    UndoManager,
    SelectionCellsHandler,
    SelectionHandler,
    RubberBandHandler,
    GraphDataModel,
    mxEvent,
    PanningHandler,
    InternalEvent,
    ModelXmlSerializer,
    InternalEvent
} from '@maxgraph/core';

//-----------------------------------------------------------------------
// Import des plugins
const plugins: GraphPluginConstructor[] = [
    // L'ordre d'import est important !
    // CellEditorHandler,
    // SelectionCellsHandler,
    // SelectionHandler,
    // PanningHandler,
    //RubberBandHandler,
];

// Initialiser un graphique de base
const container = document.getElementById('graph-container');
const graph = new Graph(container, new GraphDataModel(), plugins);
const model = graph.getDataModel();

//-----------------------------------------------------------------------

// Interface pour une arête (edge)
interface Edge {
  attachedNodeId: string;
  name: string,
  edgeType: string;
  edgeDirection: string;
  bidirectional: boolean;
}

// Interface pour un nœud (node)
interface Node {
  id: string;
  vue: string;
  label: string;
  image: string;
  type: string;
  edges: Edge[];
}

// Map contenant les nœuds
type NodeMap = Map<string, Node>;

declare const _nodes : NodeMap;

//-----------------------------------------------------------------------
const defaultVertexStyle = graph.getStylesheet().getDefaultVertexStyle();
defaultVertexStyle.cursor = 'pointer'; // Définit le curseur en main

//-----------------------------------------------------------------------
// Style des liens

var style = graph.getStylesheet().getDefaultEdgeStyle();
style.labelBackgroundColor = '#FFFFFF';
style.strokeWidth = 2;
style.rounded = true;
style.entryPerimeter = false;
//style.entryY = 0.25;
//style.entryX = 0;
// After move of "obstacles" nodes, move "finish" node - edge route will be recalculated
style.edgeStyle = 'manhattanEdgeStyle';

// -----------------------------------------------------------------------
// Panning
// Permettre le déplacement de la grille
// graph.setPanning(true); // Active le panning global

// Désactive le graphe
graph.setEnabled(false);

//-----------------------------------------------------------------------
// Menu contextuer

// Désactiver le menu contextuel par défaut :
InternalEvent.disableContextMenu(container)

// Ajouter un écouteur d'événements pour le clic droit
graph.addListener('contextmenu', (evt) => {
    console.log('click');
    const cell = graph.getCellAt(evt.getProperty('event').clientX, evt.getProperty('event').clientY);
    if (cell && graph.isVertex(cell)) {
        // Afficher votre menu contextuel ici
        console.log('Menu contextuel pour le vertex:', cell);
        // Code pour afficher le menu contextuel
    }
});

//-------------------------------------------------------------------------
// LOAD / SAVE

// Fonction pour charger le graphe
export function loadGraph(xml: string) {
    new ModelXmlSerializer(model).import(xml);
}

// Rendez la fonction loadGraph accessible globalement
(window as any).loadGraph = loadGraph;

//-------------------------------------------------------------------------
// Fonction de téléchargement
function downloadSVG() {
  embedImagesInSVG(svgElement);

  setTimeout(() => {
    const serializer = new XMLSerializer();
    const svgString = serializer.serializeToString(svgElement);

    // Créer un blob pour le fichier SVG
    const blob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(blob);

    // Créer un lien pour télécharger
    const link = document.createElement('a');
    link.href = url;
    link.download = 'graph_with_images.svg';
    link.click();

    // Nettoyage
    URL.revokeObjectURL(url);
  }, 1000); // Attendre la conversion des images
}

// Ajoutez un bouton pour déclencher l'exportation
//const exportButton = document.getElementById('download-btn');
//exportButton.addEventListener('click', downloadSVG);

//--------------------------------------------------------------------------
// Gestionnaire de clic
/*
graph.addListener(InternalEvent.CLICK, (sender, evt) => {
    const cell = evt.getProperty('cell');
    if (cell && cell.isVertex()) {
        console.log('Vertex cliqué :', cell.value);
        // Ajoutez ici le code pour gérer le clic sur le vertex
        const node = _nodes.get(cell.id);
        // deleted ?
        if (node==null)
            return;
        const id = cell.id.split("_").pop();
        window.location.href = "/admin/"+node.type+"/"+id;
    }
});
*/
