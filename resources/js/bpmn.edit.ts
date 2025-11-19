// src/maxgraph-ui.ts
import {
    AbstractGraph,
    ConnectionHandler,
    EdgeHandler,
    ElbowEdgeHandler,
    Graph,
    RubberBandHandler,
} from '@maxgraph/core';

import DOMPurify from 'dompurify';

console.log('🚀 Initialisation de l\'éditeur BPMN avec MaxGraph');
console.log('📦 MaxGraph disponible:', typeof maxgraph !== 'undefined');

// Empêche le tree-shaking des handlers d'arêtes
void EdgeHandler;
void ElbowEdgeHandler;
void ConnectionHandler;

type BpmnNodeType = Parameters<typeof sizeFor>[0];

const container = document.getElementById('graph-container') as HTMLElement;
if (!container) throw new Error('#graph-container introuvable');
container.tabIndex = 0; // optionnel: focusable


let graph: AbstractGraph;
let bpmnData: { elements: any; xmlDoc?: any; positions?: {}; } | null = null;
let fileName = 'diagram.bpmn';

// Initialisation du graphe
function initGraph() {
    console.log('🎨 Initialisation du graphe MaxGraph');
    const container: HTMLElement = <HTMLElement>document.getElementById('graph-container');

    graph = new Graph(container);
    graph.setPanning(true);
    graph.setConnectable(false);
    graph.setCellsEditable(false);
    graph.setCellsResizable(false);
    graph.setCellsMovable(true);
    graph.setAllowDanglingEdges(false);

    // Active la sélection multiple
    new RubberBandHandler(graph);

    // Grille
    graph.gridSize = 10;
    graph.gridEnabled = true;

    console.log('✅ Graphe MaxGraph initialisé');
    return graph;
}

// Parser BPMN
function parseBPMN(xmlText: string) {
    console.log('🔧 Début du parsing BPMN');
    const parser = new DOMParser();
    const sanitizedXmlText = sanitizeXml(xmlText);

    const xmlDoc = parser.parseFromString(sanitizedXmlText, 'text/xml');

    // Vérifier les erreurs de parsing
    const parserError = xmlDoc.getElementsByTagName('parsererror');
    if (parserError.length > 0) {
        console.error('❌ Erreur de parsing XML:', parserError[0].textContent);
        throw new Error('Erreur de parsing XML');
    }

    console.log('📦 Document XML parsé');

    const elements = {
        startEvents: [],
        tasks: [],
        gateways: [],
        endEvents: [],
        flows: [],
        participants: []
    };

    // Récupérer les participants/lanes
    const participants = xmlDoc.getElementsByTagName('participant');
    console.log(`🏊 Participants trouvés: ${participants.length}`);
    for (let p of participants) {
        elements.participants.push({
            id: p.getAttribute('id'),
            name: p.getAttribute('name'),
            processRef: p.getAttribute('processRef')
        });
    }

    // Récupérer les éléments du process
    const startEvents = xmlDoc.getElementsByTagNameNS('*', 'startEvent');
    console.log(`▶️ Start events trouvés: ${startEvents.length}`);
    for (let se of startEvents) {
        elements.startEvents.push({
            id: se.getAttribute('id'),
            name: se.getAttribute('name')
        });
    }

    const tasks = xmlDoc.getElementsByTagNameNS('*', 'task');
    console.log(`📋 Tasks trouvées: ${tasks.length}`);
    for (let t of tasks) {
        elements.tasks.push({
            id: t.getAttribute('id'),
            name: t.getAttribute('name')
        });
    }

    const gateways = xmlDoc.querySelectorAll('exclusiveGateway, parallelGateway, inclusiveGateway');
    console.log(`🔀 Gateways trouvés: ${gateways.length}`);
    for (let g of gateways) {
        elements.gateways.push({
            id: g.getAttribute('id'),
            name: g.getAttribute('name'),
            type: g.tagName.replace(/Gateway$/, '')
        });
    }

    const endEvents = xmlDoc.getElementsByTagNameNS('*', 'endEvent');
    console.log(`⏹️ End events trouvés: ${endEvents.length}`);
    for (let ee of endEvents) {
        elements.endEvents.push({
            id: ee.getAttribute('id'),
            name: ee.getAttribute('name')
        });
    }

    const flows = xmlDoc.getElementsByTagNameNS('*', 'sequenceFlow');
    console.log(`➡️ Flows trouvés: ${flows.length}`);
    for (let f of flows) {
        elements.flows.push({
            id: f.getAttribute('id'),
            source: f.getAttribute('sourceRef'),
            target: f.getAttribute('targetRef')
        });
    }

    // Récupérer les positions du diagramme
    const shapes = xmlDoc.getElementsByTagNameNS('*', 'BPMNShape');
    console.log(`📐 Shapes trouvés: ${shapes.length}`);
    const positions = {};

    for (let shape of shapes) {
        const id = shape.getAttribute('bpmnElement');
        const bounds = shape.getElementsByTagNameNS('*', 'Bounds')[0];

        if (bounds) {
            positions[id] = {
                x: parseFloat(bounds.getAttribute('x')),
                y: parseFloat(bounds.getAttribute('y')),
                width: parseFloat(bounds.getAttribute('width')),
                height: parseFloat(bounds.getAttribute('height'))
            };
        }
    }

    console.log('📍 Positions récupérées:', Object.keys(positions).length);
    console.log('✅ Parsing terminé avec succès');

    return {elements, positions, xmlDoc};
}

// Dessiner le diagramme
function drawDiagram(data) {
    console.log('🎨 Début du dessin du diagramme');
    const {elements, positions} = data;
    const parent = graph.getDefaultParent();
    const vertexMap = {};

    console.log('🗺️ Parent du graphe:', parent);

    graph.getModel().beginUpdate();
    try {
        graph.removeCells(graph.getChildCells(parent));
        console.log('🧹 Cellules précédentes supprimées');

        // Dessiner les participants (swimlanes)
        elements.participants.forEach(p => {
            const pos = positions[p.id] || {x: 100, y: 100, width: 600, height: 250};
            console.log(`🏊 Dessin participant: ${p.name} à (${pos.x}, ${pos.y})`);
            const lane = graph.insertVertex(
                parent,
                p.id,
                p.name || 'Lane',
                pos.x,
                pos.y,
                pos.width,
                pos.height,
                'swimlane;startSize=30;fillColor=#f0f0f0;strokeColor=#666;'
            );
            vertexMap[p.id] = lane;
        });

        // Dessiner les start events
        elements.startEvents.forEach(se => {
            const pos = positions[se.id] || {x: 200, y: 150, width: 36, height: 36};
            console.log(`▶️ Dessin start event: ${se.name} à (${pos.x}, ${pos.y})`);
            const vertex = graph.insertVertex(
                parent,
                se.id,
                se.name || '',
                pos.x,
                pos.y,
                pos.width,
                pos.height,
                'ellipse;fillColor=#c8e6c9;strokeColor=#205022;strokeWidth=2;'
            );
            vertexMap[se.id] = vertex;
        });

        // Dessiner les tâches
        elements.tasks.forEach(t => {
            const pos = positions[t.id] || {x: 300, y: 130, width: 100, height: 80};
            console.log(`📋 Dessin task: ${t.name} à (${pos.x}, ${pos.y})`);
            const vertex = graph.insertVertex(
                parent,
                t.id,
                t.name || 'Task',
                pos.x,
                pos.y,
                pos.width,
                pos.height,
                'rounded=1;fillColor=#bbdefb;strokeColor=#0d4372;strokeWidth=2;'
            );
            vertexMap[t.id] = vertex;
        });

        // Dessiner les gateways
        elements.gateways.forEach(g => {
            const pos = positions[g.id] || {x: 450, y: 145, width: 50, height: 50};
            console.log(`🔀 Dessin gateway: ${g.name} à (${pos.x}, ${pos.y})`);
            const vertex = graph.insertVertex(
                parent,
                g.id,
                g.name || '',
                pos.x,
                pos.y,
                pos.width,
                pos.height,
                'rhombus;fillColor=#fff59d;strokeColor=#f57f17;strokeWidth=2;'
            );
            vertexMap[g.id] = vertex;
        });

        // Dessiner les end events
        elements.endEvents.forEach(ee => {
            const pos = positions[ee.id] || {x: 550, y: 152, width: 36, height: 36};
            console.log(`⏹️ Dessin end event: ${ee.name} à (${pos.x}, ${pos.y})`);
            const vertex = graph.insertVertex(
                parent,
                ee.id,
                ee.name || '',
                pos.x,
                pos.y,
                pos.width,
                pos.height,
                'ellipse;fillColor=#ffcdd2;strokeColor=#831311;strokeWidth=3;'
            );
            vertexMap[ee.id] = vertex;
        });

        // Dessiner les flux
        elements.flows.forEach(f => {
            const source = vertexMap[f.source];
            const target = vertexMap[f.target];

            if (source && target) {
                console.log(`➡️ Dessin flow: ${f.source} -> ${f.target}`);
                graph.insertEdge(
                    parent,
                    f.id,
                    '',
                    source,
                    target,
                    'strokeColor=#666;strokeWidth=2;'
                );
            } else {
                console.warn(`⚠️ Flow ignoré (source ou target manquant): ${f.id}`);
            }
        });
    } finally {
        graph.getModel().endUpdate();
    }

    console.log('✅ Diagramme dessiné avec succès');

    // Ajuster la vue
    setTimeout(() => {
        graph.fit();
        graph.center();
        console.log('📐 Vue ajustée et centrée');
    }, 100);
}

// Générer le XML BPMN mis à jour
function generateBPMN() {
    if (!bpmnData) return null;

    console.log('💾 Génération du XML BPMN mis à jour');
    const {xmlDoc, elements} = bpmnData;
    const model = graph.getModel();

    // Mettre à jour les positions dans le XML
    const shapes = xmlDoc.getElementsByTagNameNS('*', 'BPMNShape');

    for (let shape of shapes) {
        const elementId = shape.getAttribute('bpmnElement');
        const cell = model.getCell(elementId);

        if (cell && cell.geometry) {
            const bounds = shape.getElementsByTagNameNS('*', 'Bounds')[0];
            if (bounds) {
                bounds.setAttribute('x', Math.round(cell.geometry.x));
                bounds.setAttribute('y', Math.round(cell.geometry.y));
                bounds.setAttribute('width', Math.round(cell.geometry.width));
                bounds.setAttribute('height', Math.round(cell.geometry.height));
                console.log(`📍 Position mise à jour: ${elementId} -> (${Math.round(cell.geometry.x)}, ${Math.round(cell.geometry.y)})`);
            }
        }
    }

    const serializer = new XMLSerializer();
    const xml = serializer.serializeToString(xmlDoc);
    console.log('✅ XML BPMN généré');
    return xml;
}

// Afficher un message de statut
function showStatus(message, duration = 2000) {
    const status = document.getElementById('status');
    status.textContent = message;
    status.classList.add('show');

    setTimeout(() => {
        status.classList.remove('show');
    }, duration);
}


// Défense-in-depth:
// Sanitize input XML to remove dangerous tags, e.g., <script>, <iframe>—adapt whitelist as needed.
function sanitizeXml(xmlText: string): string {
    return DOMPurify.sanitize(xmlText, {ALLOWED_TAGS: ['bpmn', 'BPMNShape', 'Bounds'], ALLOWED_ATTR: false});
}

// Gestion des événements
// @ts-ignore
document.getElementById('file-input').addEventListener('change', async (e) => {
    console.log('📁 Événement file input déclenché');
    const file = e.target.files[0];
    if (!file) {
        console.log('❌ Pas de fichier sélectionné');
        return;
    }

    console.log('📄 Fichier:', file.name, file.size, 'bytes');
    fileName = file.name;
    const text = await file.text();
    console.log('📝 Contenu XML (extrait):', text.substring(0, 200) + '...');

    try {
        console.log('🔍 Parsing BPMN...');
        bpmnData = parseBPMN(text);
        console.log('✅ Parsing réussi:', bpmnData);

        console.log('🎨 Dessin du diagramme...');
        drawDiagram(bpmnData);
        console.log('✅ Diagramme dessiné');

        const totalElements =
            bpmnData.elements.startEvents.length +
            bpmnData.elements.tasks.length +
            bpmnData.elements.gateways.length +
            bpmnData.elements.endEvents.length;

        console.log(`📊 Total: ${totalElements} éléments, ${bpmnData.elements.flows.length} flux`);

        document.getElementById('info').textContent =
            `${fileName} - ${totalElements} éléments, ${bpmnData.elements.flows.length} flux`;

        showStatus('✓ Fichier chargé avec succès');
    } catch (error) {
        console.error('❌ ERREUR:', error);
        console.error('Stack:', error.stack);
        showStatus('✗ Erreur lors du chargement du fichier');
    }
});

/*
document.getElementById('save-btn').addEventListener('click', () => {
    if (!bpmnData) {
        showStatus('⚠ Chargez d\'abord un fichier BPMN');
        return;
    }

    const xml = generateBPMN();
    const blob = new Blob([xml], {type: 'application/xml'});
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = fileName;
    a.click();

    URL.revokeObjectURL(url);
    showStatus('✓ Fichier sauvegardé');
    console.log('💾 Fichier BPMN sauvegardé');
});
*/
document.getElementById('zoom-in-btn')?.addEventListener('click', () => {
    graph.zoomIn();
    console.log('🔍 Zoom in');
});

document.getElementById('zoom-out-btn')?.addEventListener('click', () => {
    graph.zoomOut();
    console.log('🔍 Zoom out');
});

document.getElementById('fit-in-btn')?.addEventListener('click', () => {
    graph.fit();
    graph.center();
    console.log('⬜ Vue ajustée');
});

// Initialiser
try {
    initGraph();
    showStatus('👋 Bienvenue ! Chargez un fichier BPMN', 3000);
    console.log('✅ Éditeur BPMN initialisé et prêt');
} catch (error) {
    console.error('❌ Erreur lors de l\'initialisation:', error);
    showStatus('❌ Erreur d\'initialisation', 5000);
}


/*
graph.setPanning(true);
new PanningHandler(graph);
new SelectionHandler(graph);
new RubberBandHandler(graph);

graph.setCellsBendable(true);        // autoriser les bends
graph.setDisconnectOnMove(false);    // éviter de détacher lors du drag
graph.setAllowDanglingEdges(false);  // pas d’arêtes orphelines


// ---------------- UndoManager ----------------
const undoManager = new UndoManager();

const undoListener = (sender: any, evt: any) => {
  const edit = evt.getProperty('edit');
  if (edit) undoManager.undoableEditHappened(edit);
};

graph.getDataModel().addListener(InternalEvent.UNDO, undoListener);
graph.getView().addListener(InternalEvent.UNDO, undoListener);

// ---------------- Shortcuts (document-level) ----------------
declare global { interface Window { __mxShortcutsInit?: boolean } }

function isTypingTarget(ev: KeyboardEvent): boolean {
  const t = ev.target as HTMLElement | null;
  const tag = (t?.tagName || '').toLowerCase();
  return tag === 'input' || tag === 'textarea' || tag === 'select' || (t as any)?.isContentEditable;
}

function deleteSelection() {
  const cells = graph.getSelectionCells();
  if (!cells?.length) return;
  graph.getDataModel().beginUpdate();
  try { graph.removeCells(cells, true); } finally { graph.getDataModel().endUpdate(); }
}

// Register once (important in dev/HMR)
if (!window.__mxShortcutsInit) {
  window.__mxShortcutsInit = true;

  document.addEventListener('keydown', (event: KeyboardEvent) => {
    if (isTypingTarget(event)) return;

    const ctrl = event.ctrlKey || event.metaKey;
    const key = event.key;

    // Ctrl/Cmd + Z => undo (Shift+Z => redo)
    if (ctrl && (key === 'z' || key === 'Z')) {
      event.preventDefault();
      if (event.shiftKey) { if (undoManager.canRedo()) undoManager.redo(); }
      else { if (undoManager.canUndo()) undoManager.undo(); }
      return;
    }

    // Ctrl/Cmd + Y => redo
    if (ctrl && (key === 'y' || key === 'Y')) {
      event.preventDefault();
      if (undoManager.canRedo()) undoManager.redo();
      return;
    }

    // Ctrl/Cmd + A => select all
    if (ctrl && (key === 'a' || key === 'A')) {
      event.preventDefault();
      graph.selectAll();
      return;
    }

    // Delete / Backspace => remove selection
    if (key === 'Delete' || key === 'Backspace') {
      // éviter de faire back/forward navigateur
      event.preventDefault();
      deleteSelection();
      return;
    }
  }, { capture: true }); // capture pour garantir la réception avant des handlers internes
}

// --------------- Palette (si boutons présents) ---------------
bindBtn('addTaskBtn',    () => addVertex('userTask', 'Task', 80, 80));
bindBtn('addGatewayBtn', () => addVertex('exclusiveGateway', 'XOR', 240, 120));
bindBtn('addStartBtn',   () => addVertex('startEvent', 'Start', 40, 200));
bindBtn('addEndBtn',     () => addVertex('endEvent', 'End', 420, 200));

// --------------- Import BPMN ---------------
const fileInput = document.getElementById('fileInput') as HTMLInputElement | null;
fileInput?.addEventListener('change', async () => {
  const file = fileInput.files?.[0];
  if (!file) return;
  const xml = await file.text();
  const { containers, nodes, edges } = parseBpmn(xml);
  renderToGraph(graph, nodes, edges, containers);
});

// --------------- Export BPMN ---------------
bindBtn('exportBtn', () => {
  const xml = exportGraphToBpmn(graph);
  const blob = new Blob([xml], { type: 'application/xml' });
  downloadBlob(blob, 'export.bpmn');
});

// --------------- Helpers ---------------
function addVertex(type: BpmnNodeType, label: string, x: number, y: number) {
  const parent = graph.getDefaultParent();
  graph.getDataModel().beginUpdate();
  try {
    const [w, h] = sizeFor(type);
    graph.insertVertex({
      parent,
      value: label,
      position: [x, y],
      size: [w, h],
      style: styleFor(type) as CellStyle,
    });
  } finally {
    graph.getDataModel().endUpdate();
  }
}

function bindBtn(id: string, fn: () => void) {
  const el = document.getElementById(id);
  if (el) el.addEventListener('click', fn);
}

function downloadBlob(blob: Blob, filename: string) {
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = filename; document.body.appendChild(a); a.click();
  a.remove(); URL.revokeObjectURL(url);
}
*/
