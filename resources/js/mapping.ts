import {
    Graph,
    CellEditorHandler,
    UndoManager,
    SelectionCellsHandler,
    SelectionHandler,
    RubberBandHandler,
    GraphDataModel,
    mxEvent,
    PanningHandler
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
const div = document.createElement('div');
const graph = new Graph(container, new GraphDataModel(), plugins);

//-----------------------------------------------------------------------
// Initialiser l'UndoManager
const undoManager = new UndoManager();
const model = graph.getDataModel();

// Écouter les modifications et les ajouter à l'UndoManager
model.addListener('change', (sender, evt) => {
    undoManager.undoableEditHappened(evt.getProperty('edit'));
});

// Ajouter le gestionnaire à l'événement "undoableEditHappened"
//graph.getModel().addListener(mxEvent.UNDO, listener);
//graph.getView().addListener(mxEvent.UNDO, listener);

// Écouter les raccourcis clavier pour annulation (Ctrl+Z)
document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key === 'z') {
        console.log('undo');
        undoManager.undo(); // Exécuter l'annulation
        event.preventDefault(); // Empêcher le comportement par défaut du navigateur
    }
});

// Optionnel : Ajouter un raccourci pour refaire (Ctrl+Y)
document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key === 'y') {
        console.log('redo');
        undoManager.redo(); // Exécuter la restauration
        event.preventDefault();
    }
});

// --------------------------------------------------------------------------------
// Context menu for edges
const contextMenu = document.getElementById('context-menu');
const colorSelect = document.getElementById('edge-color-select');
const thicknessSelect = document.getElementById('edge-thickness-select');
const applyButton = document.getElementById('apply-edge-style');

let selectedEdge = null;

// Afficher le menu contextuel lors d'un clic droit sur une arête
graph.container.addEventListener('contextmenu', (event) => {
    event.preventDefault();
    const cell = graph.getCellAt(event.offsetX, event.offsetY);

    // Vérifier si l'élément cliqué est une arête
    if (cell && cell.isEdge()) {
        selectedEdge = cell;

        // Afficher le menu contextuel
        contextMenu.style.display = 'block';
        contextMenu.style.left = `${event.pageX}px`;
        contextMenu.style.top = `${event.pageY}px`;

        // Pré-remplir les valeurs du menu avec les styles actuels de l'arête
        const currentStyle = graph.getCellStyle(cell);
        colorSelect.value = currentStyle.strokeColor || '#000000';
        thicknessSelect.value = currentStyle.strokeWidth || '1';
    } else {
        contextMenu.style.display = 'none';
    }
});

// Appliquer les changements de style à l'arête sélectionnée
applyButton.addEventListener('click', () => {
    if (selectedEdge) {
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
// Données de test --------------------------------------------------------
//-------------------------------------------------------------------------

graph.batchUpdate(() => {

    const parent = graph.getDefaultParent();
    /*
   const v1 = graph.insertVertex(parent, null, 'Hello,', 20, 20, 80, 30);
   const v2 = graph.insertVertex(parent, null, 'World!', 200, 150, 80, 30);
   graph.insertEdge(parent, null, '', v1, v2);
   */

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
        group,
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
    const e6 = graph.insertEdge({ parent, value: '', source: b3, target: bay3 });

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

//-------------------------------------------------------------------------
// Ajoute de texte
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
// Gestion des événements drag & drop des icônes
const nodeIcon = document.getElementById('node-icon');
nodeIcon.addEventListener('dragstart', (event) => {
    event.dataTransfer.setData('node-type', 'icon-node'); // Type de nœud
});

container.addEventListener('dragover', (event) => {
    event.preventDefault(); // Nécessaire pour autoriser le drop
});

container.addEventListener('drop', (event) => {
    event.preventDefault();

    if (event.dataTransfer.getData('node-type')=='icon-node') {

        // Obtenir la position de la souris lors du drop
        const rect = container.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        // Ajouter un nouveau nœud à l'emplacement du drop
        graph.batchUpdate(() => {
            const parent = graph.getDefaultParent();
            graph.insertVertex({
                parent,
                value: 'New Node',
                position: [x, y],
                size: [80, 30],
            });
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
            'groupStyle'
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
