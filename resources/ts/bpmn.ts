// src/bpmn.ts
import { initBpmnEditor, wireEditorUi, showStatus, enableArrowKeyMovement } from './bpmn-edit';
import { bindBpmnFileInput } from './bpmn-import';
import { bindSaveButton, exposeGraphHelpers } from './bpmn-save';
import { initVertexMenuActions } from "./bpmn-menu-init";
import { installEdgeRules } from "./bpmn-arrows";
import { setupBpmnMenuSelect } from "./bpmn-menu-select";

import { installDropInActivitiesParent } from "./bpmn-parent";
import {initCopyPaste} from "./bpmn-copy-paste";
import {initZOrder, setupBringToFrontKeyBinding} from "./bpmn-zorder";

console.log("🚀 Initialisation de l'éditeur BPMN");

// Init éditeur (graph + undo/redo)
const {graph, undoManager} = initBpmnEditor('graph-container');

// UI (zoom, undo/redo, raccourcis)
wireEditorUi(graph, undoManager);

setupBringToFrontKeyBinding(graph, graph.container);

initCopyPaste(graph);

// Mouvement avec les flèches
enableArrowKeyMovement(graph);

// Dashed edges for database vertices
installEdgeRules(graph);

// Gestion des groupes de vertex
installDropInActivitiesParent(graph);

// Chargement BPMN via input fichier
bindBpmnFileInput(graph, 'file-input');

// Sauvegarde / helpers globaux
bindSaveButton(graph, 'save-btn');

// Pour load/save
exposeGraphHelpers(graph);

// Menu and Actions
const controller = initVertexMenuActions(graph, undoManager);

// Menu select
setupBpmnMenuSelect(graph);

// Init ZOrder - automatically place vertices in the right order
initZOrder(graph);

// Message de bienvenue
showStatus('👋 Bienvenue ! Charge un fichier BPMN', 3000);

console.log('✅ Éditeur BPMN initialisé et prêt');
