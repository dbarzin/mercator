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
    ModelXmlSerializer
} from '@maxgraph/core';

//-----------------------------------------------------------------------
// Import des plugins
const plugins: GraphPluginConstructor[] = [
    // L'ordre d'import est important !
    CellEditorHandler,
    SelectionCellsHandler,
    SelectionHandler,
    PanningHandler,
    RubberBandHandler,
];

// Initialiser un graphique de base
const container = document.getElementById('graph-container');
const graph = new Graph(container, new GraphDataModel(), plugins);
const model = graph.getDataModel();

// --------------------------------------------------------------------------------
// Configuration de la grille
//graph.setGridEnabled(true); // Active la grille
//graph.setGridSize(10); // Taille des cellules de la grille

// Personnaliser la grille avec CSS
/*
container.style.backgroundImage = `
  linear-gradient(to right, #e0e0e0 1px, transparent 1px),
  linear-gradient(to bottom, #e0e0e0 1px, transparent 1px)
  `;
container.style.backgroundSize = '10px 10px'; // Taille des cellules de la grille
*/
// -----------------------------------------------------------------------
// Panning

// Permettre le déplacement de la grille
graph.setPanning(true); // Active le panning global
graph.setEnabled(false);

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
