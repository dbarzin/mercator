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
    HandleConfig,
    VertexHandlerConfig
} from '@maxgraph/core';

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
const div = document.createElement('div');
const graph = new Graph(container, new GraphDataModel(), plugins);
const model = graph.getDataModel();

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

// Changes vertex selection colors and size
VertexHandlerConfig.selectionColor = '#00a8ff';
VertexHandlerConfig.selectionStrokeWidth = 2;

//-----------------------------------------------------------------------
// Initialiser l'UndoManager

// Crée un UndoManager
const undoManager = new UndoManager();

// Fonction pour enregistrer les modifications dans l'UndoManager
function registerUndoManager(graph: Graph, undoManager: UndoManager) {
  const listener = (sender: any, evt: any) => {
    const edit = evt.getProperty('edit') as UndoableEdit;
    undoManager.undoableEditHappened(edit);
  };

  model.addListener(InternalEvent.UNDO, listener);
  graph.getView().addListener(InternalEvent.UNDO, listener);
}

// Enregistre les modifications pour Undo/Redo
registerUndoManager(graph, undoManager);

// Boutons pour Undo/Redo
const undoButton = document.getElementById('undoButton') as HTMLButtonElement;
const redoButton = document.getElementById('redoButton') as HTMLButtonElement;

undoButton.addEventListener('click', () => {
  if (undoManager.canUndo()) {
    undoManager.undo();
  }
});

redoButton.addEventListener('click', () => {
  if (undoManager.canRedo()) {
    undoManager.redo();
  }
});

// Gestionnaire pour les raccourcis clavier
document.addEventListener('keydown', (event: KeyboardEvent) => {
  if (event.ctrlKey && event.key === 'z') {
    // Ctrl+Z pour Undo
    event.preventDefault();
    if (undoManager.canUndo()) {
      undoManager.undo();
    }
  } else if (
    (event.ctrlKey && event.key === 'y') || // Ctrl+Y pour Redo
    (event.ctrlKey && event.shiftKey && event.key === 'z') // Ctrl+Shift+Z pour Redo
  ) {
    event.preventDefault();
    if (undoManager.canRedo()) {
      undoManager.redo();
    }
  }
});

// --------------------------------------------------------------------------------
// Context menu for edges
const contextMenu = document.getElementById('context-menu');
const colorSelect = document.getElementById('edge-color-select');
const thicknessSelect = document.getElementById('edge-thickness-select');
const applyButton = document.getElementById('apply-edge-style');

let selectedEdge = null;

graph.container.addEventListener('contextmenu', (event) => {
    event.preventDefault();
    const cell = graph.getCellAt(event.offsetX, event.offsetY);
    if (cell==null)
        return;

    //console.log(cell);

    // Vérifier si l'élément cliqué est une arête
    if (cell.isEdge()) {
        selectedEdge = cell;

        // Obtenir la position de la souris lors du drop
        const rect = container.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Afficher le menu contextuel
        contextMenu.style.display = 'block';
        contextMenu.style.left = `${x+75}px`;
        contextMenu.style.top = `${y+100}px`;

        // Pré-remplir les valeurs du menu avec les styles actuels de l'arête
        const currentStyle = graph.getCellStyle(cell);
        colorSelect.value = currentStyle.strokeColor || '#000000';
        thicknessSelect.value = currentStyle.strokeWidth || '1';
    }
    else if (cell.isVertex()) {
        if (cell.style.image==null) {
            selectedEdge = cell;
            // Obtenir la position de la souris lors du drop
            const rect = container.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Afficher le menu contextuel
            contextMenu.style.display = 'block';
            contextMenu.style.left = `${x+75}px`;
            contextMenu.style.top = `${y+100}px`;

            // Pré-remplir les valeurs du menu avec les styles actuels de l'arête
            const currentStyle = graph.getCellStyle(cell);
            colorSelect.value = currentStyle.strokeColor || '#000000';
            thicknessSelect.value = currentStyle.strokeWidth || '1';
        }
    }
    else {
        contextMenu.style.display = 'none';
    }
});

// Appliquer les changements de style à l'arête sélectionnée
applyButton.addEventListener('click', () => {
    if (selectedEdge) {
        // console.log("update");
        if (selectedEdge.style.image==null) {
            graph.batchUpdate(() => {
                const style = graph.getCellStyle(selectedEdge);
                // console.log(style);
                graph.setCellStyle({
                    ...style,
                    fillColor: colorSelect.value,
                    strokeWidth: parseInt(thicknessSelect.value, 10),
                }, [selectedEdge]);
            });
        }
        else
            graph.batchUpdate(() => {
                const style = graph.getCellStyle(selectedEdge);
                graph.setCellStyle({
                    ...style,
                    strokeColor: colorSelect.value,
                    strokeWidth: parseInt(thicknessSelect.value, 10),
                }, [selectedEdge]);
            });
    }
    // Fermer le menu contextuel
    contextMenu.style.display = 'none';

});

// Cacher le menu contextuel en cliquant ailleurs
document.addEventListener('click', (event) => {
    if (!contextMenu.contains(event.target)) {
        contextMenu.style.display = 'none';
    }
});

// --------------------------------------------------------------------------------
// Configuration de la grille
graph.setGridEnabled(true); // Active la grille
graph.setGridSize(10); // Taille des cellules de la grille
/*
graph.setGridStyle({
    color: '#e0e0e0', // Couleur de la grille
    thickness: 1, // Épaisseur des lignes
    dashed: false, // Style continu ou pointillé
});
*/
// Personnaliser la grille avec CSS
container.style.backgroundImage = `
  linear-gradient(to right, #e0e0e0 1px, transparent 1px),
  linear-gradient(to bottom, #e0e0e0 1px, transparent 1px)
`;
container.style.backgroundSize = '10px 10px'; // Taille des cellules de la grille

// -----------------------------------------------------------------------
// Panning

// Permettre le déplacement de la grille
graph.setPanning(true); // Active le panning global

//-------------------------------------------------------------------------
// LOAD / SAVE

// Fonction pour charger le graphe
export function loadGraph(xml: string) {
    new ModelXmlSerializer(model).import(xml);
}

// Rendez la fonction loadGraph accessible globalement
(window as any).loadGraph = loadGraph;

async function saveGraphToDatabase(id: integer, name: string, type: string, content: string): Promise<void> {
  // console.log('saveGraphToDatabase:' + id + ' name:' + name);

  try {
    const response = await fetch('/admin/graph/save', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
      body: JSON.stringify({ id, name, type, content }),
    });

    // console.log('réponse :', response);
    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Erreur lors de la sauvegarde du graphe.');
    }

    const data = await response.json();
    console.log('Graphe sauvegardé avec succès :', data.graph);
  } catch (error) {
    console.error('Erreur lors de la sauvegarde :', error);
    alert('Erreur lors de la sauvegarde du graphe.');
  }
}

// Fonction pour récupérer le Graph en XML
function getXMLGraph() {
    return new ModelXmlSerializer(model).export();
}

// Rendez la fonction getXMLGraph accessible globalement
(window as any).getXMLGraph = getXMLGraph;

// Fonction pour sauvegarder le graphe
function saveGraph() {
    const xml = new ModelXmlSerializer(model).export();

    // saveGraphToDatabase
    saveGraphToDatabase(
        document.querySelector('#id').value,
        document.querySelector('#name').value,
        document.querySelector('#type').value,
        xml);
}

// Ajouter un bouton pour sauvegarder
const saveButton = document.getElementById('saveButton') as HTMLButtonElement;
saveButton.addEventListener('click', saveGraph);

//-------------------------------------------------------------------------
// Données de test --------------------------------------------------------
//-------------------------------------------------------------------------
/*
graph.batchUpdate(() => {

    const parent = graph.getDefaultParent();

    // const v1 = graph.insertVertex(parent, null, 'Hello,', 20, 20, 80, 30);
    // const v2 = graph.insertVertex(parent, null, 'World!', 200, 150, 80, 30);
    // graph.insertEdge(parent, null, '', v1, v2);

    const group = graph.insertVertex({
        parent,
        value: '', // Pas de texte pour le conteneur
        position: [90, 140], // Position du groupe
        size: [150, 120], // Taille du groupe
        style: {
            fillColor: '#fffacd', // Fond jaune pâle
            strokeColor: '#000000', // Bordure noire
            strokeWidth: 1, // Épaisseur de la bordure
            rounded: 2, // Coins arrondis
        },
    });

    const s1 = graph.insertVertex({
        parent,
        value: 's1', // Pas de texte affiché
        position: [150, 50], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/site.png', // URL de l'image
            // imageWidth: 32, // Largeur de l'image
            // imageHeight: 32, // Hauteur de l'image
            editable: false, //  Ne pas autoriser de changer le label
            resizable: false, // Ne pas resizer les images
            //imageBorder: 0, // Bordure facultative autour de l'image
            verticalLabelPosition: 'bottom',
            spacingTop: -15,
        },
    });

    const b1 = graph.insertVertex({
        parent,
        value: '', // Pas de texte affiché
        position: [50, 50], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/building.png', // URL de l'image
            imageWidth: 32, // Largeur de l'image
            imageHeight: 32, // Hauteur de l'image
            imageBorder: 0, // Bordure facultative autour de l'image
        },
    });

    const b2 = graph.insertVertex({
        parent,
        value: '', // Pas de texte affiché
        position: [100, 100], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/building.png', // URL de l'image
            imageWidth: 32, // Largeur de l'image
            imageHeight: 32, // Hauteur de l'image
            imageBorder: 0, // Bordure facultative autour de l'image
        },
    });

    const b3 = graph.insertVertex({
        parent,
        value: '', // Pas de texte affiché
        position: [150, 160], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/building.png', // URL de l'image
            imageWidth: 32, // Largeur de l'image
            imageHeight: 32, // Hauteur de l'image
            imageBorder: 0, // Bordure facultative autour de l'image
        },
    });

    const e1 = graph.insertEdge({ parent, value: '', source: s1, target: b1 });
    const e2 = graph.insertEdge({ parent, value: '', source: s1, target: b2 });
    const e3 = graph.insertEdge({ parent, value: '', source: s1, target: b3 });

    const bay1 = graph.insertVertex({
        group,
        value: '', // Pas de texte affiché
        position: [150, 220], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/bay.png', // URL de l'image
            imageWidth: 32, // Largeur de l'image
            imageHeight: 32, // Hauteur de l'image
            imageBorder: 0, // Bordure facultative autour de l'image
        },
    });

    const bay2 = graph.insertVertex({
        group,
        value: '', // Pas de texte affiché
        position: [100, 220], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/bay.png', // URL de l'image
            imageWidth: 32, // Largeur de l'image
            imageHeight: 32, // Hauteur de l'image
            imageBorder: 0, // Bordure facultative autour de l'image
        },
    });

    const bay3 = graph.insertVertex({
        group,
        value: '', // Pas de texte affiché
        position: [200, 220], // Position de l'image
        size: [32, 32], // Taille du nœud
        style: {
            shape: 'image', // Définir le nœud comme une image
            image: '/images/bay.png', // URL de l'image
            imageWidth: 32, // Largeur de l'image
            imageHeight: 32, // Hauteur de l'image
            imageBorder: 0, // Bordure facultative autour de l'image
        },
    });

    const e4 = graph.insertEdge({ parent, value: '', source: b3, target: bay1 });
    const e5 = graph.insertEdge({ parent, value: '', source: b3, target: bay2 });
    const e6 = graph.insertEdge(
        { parent,
            value: '',
            source: b3,
            target: bay3,
            style: {
            editable: false, //  Ne pas autoriser de changer le label
                stroke: '#FF', // Edge color
                strokeWidth: 1,
                startArrow : 'none', // pas de flèche
                endArrow : 'none' // pas de flèche
            },
        }
    );

    // Ajouter un nœud avec du texte
    graph.insertVertex({
        parent,
        value: 'mySample graph !', // Texte à afficher
        position: [0, 0], // Position du nœud
        size: [150, 50], // Taille du nœud
        style: {
            fillColor: 'none', // Pas de fond
            strokeColor: 'none', // Pas de bordure
            fontColor: '#000000', // Couleur du texte
            fontSize: 14, // Taille du texte
            align: 'center', // Alignement horizontal
            verticalAlign: 'middle', // Alignement vertical
        },
    });
});
*/

//-------------------------------------------------------------------------
// Ajout de texte
const fontIcon = document.getElementById('font-btn');

fontIcon.addEventListener('dragstart', (event) => {
    event.dataTransfer.setData('node-type', 'text-node');
});

container.addEventListener('dragover', (event) => {
    event.preventDefault(); // Nécessaire pour autoriser le drop
});

container.addEventListener('drop', (event) => {
    event.preventDefault();

    if (event.dataTransfer.getData('node-type')=='text-node') {
        // Obtenir la position de la souris lors du drop
        const rect = container.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Ajouter un nouveau nœud à l'emplacement du drop
        graph.batchUpdate(() => {
            const parent = graph.getDefaultParent();
            const vertex = graph.insertVertex({
                parent,
                value: 'Text', // Texte à afficher
                position: [x, y],
                size: [150, 30], // Taille du nœud
                style: {
                    fillColor: 'none', // Pas de fond
                    strokeColor: 'none', // Pas de bordure
                    fontColor: '#000000', // Couleur du texte
                    fontSize: 14, // Taille du texte
                    align: 'left', // Alignement horizontal
                    verticalAlign: 'middle', // Alignement vertical
                },
            });
            vertex.setAttribute('editable', 'true'); // Marqueur pour indiquer qu'il est éditable
        });
    }
});


//-------------------------------------------------------------------------
// Ajout de carré
const squareIcon = document.getElementById('square-btn');

squareIcon.addEventListener('dragstart', (event) => {
    event.dataTransfer.setData('node-type', 'square-node');
});

container.addEventListener('dragover', (event) => {
    event.preventDefault(); // Nécessaire pour autoriser le drop
});

container.addEventListener('drop', (event) => {
    event.preventDefault();

    if (event.dataTransfer.getData('node-type')=='square-node') {
        // Obtenir la position de la souris lors du drop
        const rect = container.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Ajouter un nouveau nœud à l'emplacement du drop
        graph.batchUpdate(() => {

            // Ajouter le carré
            const parent = graph.getDefaultParent();
            const vertex = graph.insertVertex({
                parent,
                // id: "square", // TODO : générer unique ID
                value: '', // Pas de texte pour le conteneur
                position: [x, y], // Position du groupe
                size: [150, 120], // Taille du groupe
                style: {
                    fillColor: '#fffacd', // Fond jaune pâle
                    strokeColor: '#000000', // Bordure noire
                    strokeWidth: 1, // Épaisseur de la bordure
                    rounded: 2, // Coins arrondis
                },
            });
            // Mettre en arrière plan
            graph.orderCells(true, [vertex]);

            // vertex.setAttribute('editable', 'true'); // Marqueur pour indiquer qu'il est éditable
        });
    }
});

//-------------------------------------------------------------------------
// Ajout d'un noeud
const nodeIcon = document.getElementById('nodeImage');
const nodeSelector = document.getElementById('node');

nodeIcon.addEventListener('dragstart', (event) => {
    event.dataTransfer.setData('node-type', 'icon-node'); // Type de nœud
});

container.addEventListener('dragover', (event) => {
    event.preventDefault(); // Nécessaire pour autoriser le drop
});

container.addEventListener('drop', (event) => {
    event.preventDefault();

    if ((event.dataTransfer.getData('node-type')=='icon-node')
        &&(nodeIcon.src!=''))
        {

        // Obtenir la position de la souris lors du drop
        const rect = container.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Ajouter un nouveau nœud à l'emplacement du drop
        graph.batchUpdate(() => {
            const parent = graph.getDefaultParent();

            // Check cell already exists
            const cell = model.getCell(nodeSelector.value);
            if (cell!=null) {
                // sélectionne la cell
                graph.setSelectionCells(cell);
                //graph.refresh(cell);
            }
            else {
                const node = _nodes.get(nodeSelector.value);
                const newNode = graph.insertVertex({
                    parent,
                    id: nodeSelector.value,
                    value: node.label,
                    position: [x-16, y-16], // Position de l'image
                    size: [32, 32], // Taille du nœud
                    style: {
                        shape: 'image', // Définir le nœud comme une image
                        image: nodeIcon.src,
                        // image: 'http://127.0.0.1:8000/images/application.png', // URL de l'image
                        // imageWidth: 32, // Largeur de l'image
                        // imageHeight: 32, // Hauteur de l'image
                        editable: false, //  Ne pas autoriser de changer le label
                        resizable: false, // Ne pas resizer les images
                        //imageBorder: 0, // Bordure facultative autour de l'image
                        verticalLabelPosition: 'bottom',
                        spacingTop: -15,
                    },
                });
                // Add edges
                node.edges.forEach(function (edge) {
                    // console.log(edge);
                    // Check target cell present
                    const targetNode = model.getCell(edge.attachedNodeId);
                    if (targetNode!=null) {
                        // console.log("add edge to "+edge.attachedNodeId+" ");
                        // add edge
                        graph.insertEdge(
                            { parent,
                                value: '',
                                source: newNode,
                                target: targetNode,
                                style: {
                                editable: false, //  Ne pas autoriser de changer le label
                                    stroke: '#FF', // Edge color
                                    strokeWidth: 1,
                                    startArrow : 'none', // pas de flèche
                                    endArrow : 'none' // pas de flèche
                                },
                            });
                        }
                });
            }
        });
    }
});

//-------------------------------------------------------------------------
// Gestion des boutons de zoom
const zoomInButton = document.getElementById('zoom-in-btn');
const zoomOutButton = document.getElementById('zoom-out-btn');

zoomInButton.addEventListener('click', () => {
    graph.zoom(1.2); // Zoom in (agrandit le graphique de 20%)
});

zoomOutButton.addEventListener('click', () => {
    graph.zoom(0.8); // Zoom out (réduit le graphique de 20%)
});

//-------------------------------------------------------------------------
// Activer la suppression avec la touche Delete
document.addEventListener('keydown', (event) => {
    if (event.key === 'Delete') {
        // Récupérer les objets sélectionnés
        const cells = graph.getSelectionCells();

        // Supprimer les objets sélectionnés
        if (cells.length > 0) {
            graph.removeCells(cells);
        }
    }
});

//-------------------------------------------------------------------------
// Empêcher les flèches de se déconnecter
graph.setConnectable(false); // Désactive les nouvelles connexions
graph.isCellDisconnectable = function () {
    return false; // Empêche la déconnexion des arêtes existantes
};

//-------------------------------------------------------------------------
// Empêcher l'édition des points d'attache
//graph.isCellEditable = function () {
//    return false; // Désactive la modification des arêtes
//};

//-------------------------------------------------------------------------

/* A utiliser pour zoom in/out
// Gérer les actions dans le menu
const addNodeButton = document.getElementById('add-node-btn');
if (addNodeButton) {
    addNodeButton.addEventListener('click', () => {
        graph.batchUpdate(() => {
            const parent = graph.getDefaultParent();
            graph.insertVertex({
                parent,
                value: 'New Node',
                position: [100, 100], // Position par défaut
                size: [80, 30],
            });
        });
    });
}
*/

//-------------------------------------------------------------------------
// Gestion des groupes
const groupButton = document.getElementById('group-btn');
const ungroupButton = document.getElementById('ungroup-btn');

groupButton.addEventListener('click', () => {
    // Obtenir les cellules sélectionnées
    const cells = graph.getSelectionCells();

    if (cells.length > 0) {
        // Créer un nouveau conteneur pour le groupe
        const group = graph.createVertex(
            null,
            null,
            '',
            0,
            0,
            100,
            100,
            'mxGroup'
        );

        // Ajouter le conteneur au graphe
        graph.addCell(group);

        // Déplacer les cellules sélectionnées dans le conteneur
        graph.groupCells(group, 0, cells);
        }
    });

ungroupButton.addEventListener('click', () => {
    // Obtenir les cellules sélectionnées
    const cells = graph.getSelectionCells();
    if (cells.length > 0) {
        // Dégrouper les cellules sélectionnées
        graph.ungroupCells(cells);

        // Sélectionner les cellules résultantes après le dégrouper
        graph.setSelectionCells(cells);
    }
});

//---------------------------------------------------------------------------
// Fonction pour déplacer une cellule
function moveSelectedVertex(graph, dx, dy) {
  const selectedCell = graph.getSelectionCell(); // Obtenir la cellule sélectionnée
  if (selectedCell && selectedCell.isVertex()) {
      graph.batchUpdate(() => {
      const geo = selectedCell.getGeometry();
      if (geo) {
          // Déplace la cellule
          geo.translate(dx, dy);
          // Rafraîchit la vue
          graph.refresh();
        }
    });
  }
}

// Écouteur pour les touches directionnelles
document.addEventListener('keydown', (event) => {
  const step = 1; // Déplacement de 1 pixel
  // console.log('keydown='+event.key);
  switch (event.key) {
    case 'ArrowUp':
      moveSelectedVertex(graph, 0, -step); // Déplacer vers le haut
      break;
    case 'ArrowDown':
      moveSelectedVertex(graph, 0, step); // Déplacer vers le bas
      break;
    case 'ArrowLeft':
      moveSelectedVertex(graph, -step, 0); // Déplacer vers la gauche
      break;
    case 'ArrowRight':
      moveSelectedVertex(graph, step, 0); // Déplacer vers la droite
      break;
    default:
      break;
  }
});

//---------------------------------------------------------------------------
// Double click

type Point = {
    x: number;
    y: number;
};

function placeObjectsOnCircle(center: Point, radius: number, numberOfObjects: number): Point[] {
    const points: Point[] = [];
    const angleStep = (2 * Math.PI) / numberOfObjects; // Angle between each object

    for (let i = 0; i < numberOfObjects; i++) {
        const angle = i * angleStep; // Current angle
        const objectX = center.x + radius * Math.cos(angle); // X-coordinate
        const objectY = center.y + radius * Math.sin(angle); // Y-coordinate
        points.push({ x: objectX, y: objectY });
    }

    return points;
}

// Get filtered entries
function getFilter(){
    let filter = [];
    for (let option of document.getElementById('filters').options)
        if (option.selected)
          filter.push(option.value);
    return filter
}

// Check edge already present
function hasEdge(src : Vertex, dest : Vertex, name: string) : boolean {
    let found = false;
    const edges = graph.getEdges(src);
    edges.forEach(edge => {
        if ((edge.target==dest)&&((name==null)||(edge.value==name))) {
            found = true;
            return;
        }
    });
    if (!found) {
        const edges = graph.getEdges(dest);
        edges.forEach(edge => {
            if ((edge.target==src)&&((name==null)||(edge.value==name))) {
                found = true;
                return;
            }
        });
    }
    return found;
}

//----------------------------------------------------------------
// Gestionnaire pour l'événement double-clic sur icône
graph.addListener(InternalEvent.DOUBLE_CLICK, (sender, evt) => {
    // Get the cell
    const cell = evt.getProperty('cell');
    // Check it is an image
    if (cell && cell.isVertex() && (cell.style.shape=="image")) {
        // get the node
        const node = _nodes.get(cell.id);
        // node deleted
        if (node==null)
            return;
        // console.log(node);
        // Batch Update
        graph.batchUpdate(() => {
            // Get the new nodes
            let newEdges: edge[] = [];
            //
            const parent = graph.getDefaultParent();
            const filter = getFilter();
            // console.log("filter= "+filter);
            // Loop on edges
            node.edges.forEach(function (edge) {
                // Get destination node
                let targetNode = _nodes.get(edge.attachedNodeId);
                // Node deleted ?
                if (targetNode != null) {
                    // Check node already present
                    const vertex = model.getCell(edge.attachedNodeId);
                    if (vertex==null) {
                        // apply filter on nodes
                        if (
                            ((filter.length == 0) || filter.includes(targetNode.vue))
                            ||
                            (filter.includes("8") && (edge.edgeType === 'CABLE'))
                            ||
                            (filter.includes("9") && (edge.edgeType === 'FLUX'))
                        ) {
                            // add it to the new nodes
                            newEdges.push(edge);
                            // console.log(edge.attachedNodeId);
                            }
                    }
                    else {
                        // check edge already present ?
                        if (!hasEdge(cell, vertex, edge.name)) {
                            // add edge
                            graph.insertEdge(
                                {   parent,
                                    value: edge.name,
                                    source: cell,
                                    target: vertex,
                                    style: {
                                    editable: false, //  Ne pas autoriser de changer le label
                                        stroke: '#FF', // Edge color
                                        strokeWidth: 1,
                                        startArrow : ((edge.edgeType=='FLUX') && ((edge.bidirectional)||(edge.edgeDirection=='FROM'))) ? 'classic' : 'none',
                                        endArrow : ((edge.edgeType=='FLUX') && ((edge.bidirectional)||(edge.edgeDirection=='TO'))) ? 'classic' : 'none',
                                    },
                                });
                            }
                        }
                    }
                });
            // Compute new nodes positions
            const rect = container.getBoundingClientRect();
            const positions = placeObjectsOnCircle({ x: cell.getGeometry().x, y: cell.getGeometry().y } , 80, newEdges.length);
            // console.log(positions);

            // Place les objects
            for (let i = 0; i < positions.length; i++) {
                // get new target node
                const edge = newEdges[i];
                const newNode = _nodes.get(edge.attachedNodeId);
                // console.log('add newNode id=' + newNode.id +" label="+ newNode.label);
                // console.log('add edge type=' + edge.edgeType);

                const vertex = graph.insertVertex({
                    parent,
                    id: newNode.id,
                    value: newNode.label,
                    position: [ positions[i].x, positions[i].y ], // Position de l'image
                    size: [32, 32], // Taille du nœud
                    style: {
                        shape: 'image', // Définir le nœud comme une image
                        image: newNode.image,
                        // image: 'http://127.0.0.1:8000/images/application.png', // URL de l'image
                        // imageWidth: 32, // Largeur de l'image
                        // imageHeight: 32, // Hauteur de l'image
                        editable: false, //  Ne pas autoriser de changer le label
                        resizable: false, // Ne pas resizer les images
                        //imageBorder: 0, // Bordure facultative autour de l'image
                        verticalLabelPosition: 'bottom',
                        spacingTop: -15,
                    },
                });

                // Insert edge with clicked one
                graph.insertEdge(
                    { parent,
                        value: newEdges[i].name,
                        source: cell,
                        target: vertex,
                        style: {
                        editable: false, //  Ne pas autoriser de changer le label
                            stroke: '#FF', // Edge color
                            strokeWidth: 1,
                            startArrow : ((edge.edgeType=='FLUX') && ((edge.bidirectional)||(edge.edgeDirection=='FROM'))) ? 'classic' : 'none',
                            endArrow : ((edge.edgeType=='FLUX') && ((edge.bidirectional)||(edge.edgeDirection=='TO'))) ? 'classic' : 'none',
                        },
                    });

                // Insert edge with other existing objects
                // TODO.....

                }
        });
    }
});

//---------------------------------------------------------------------------
// Export SVG

// Exporter en SVG avec intégration des images
const svgElement = graph.container.querySelector('svg');

function embedImagesInSVG(svgElement) {
  const images = svgElement.querySelectorAll('image');

  images.forEach((img) => {
    const href = img.getAttribute('xlink:href');

    // Charger l'image et convertir en base64
    fetch(href)
      .then((response) => response.blob())
      .then((blob) => {
        const reader = new FileReader();
        reader.onloadend = () => {
          img.setAttribute('xlink:href', reader.result); // Intègre l'image base64
        };
        reader.readAsDataURL(blob);
      });
  });
}

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
const exportButton = document.getElementById('download-btn');
exportButton.addEventListener('click', downloadSVG);

//---------------------------------------------------------------------------
// Menu
