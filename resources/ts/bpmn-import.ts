// src/bpmn-import.ts
import {Cell, EdgeParameters, Graph, Point, VertexParameters} from '@maxgraph/core';
import {showStatus} from './bpmn-edit';
import {
    addBPMNAnnotation,
    addBPMNConnection,
    addBPMNGateway,
    addBPMNState,
    addBPMNTask,
    setIconCellValue
} from "./bpmn-helpers";
import {setMessageFlow} from "./bpmn-arrows";
import {BPMN_ICONS} from "./bpmn-icons";
import {setSubProcessMarker} from "./bpmn-badge";

export interface BpmnElements {
    lanes: any[];
    laneSets: any[];
    processes: any[];
    events: any[];
    tasks: any[];
    gateways: any[];
    flows: any[];
    messageFlow: any[];
    participants: any[];
    annotations: any[];
}

export interface BpmnPositions {
    [id: string]: {
        x: number;
        y: number;
        width: number;
        height: number;
    };
}

export interface BpmnData {
    elements: BpmnElements;
    positions: BpmnPositions;
    labelPositions: Record<string, { x: number, y: number, width: number, height: number }>;
    xmlDoc: Document;
}

let currentBpmnData: BpmnData | null = null;

export function getCurrentBpmnData(): BpmnData | null {
    return currentBpmnData;
}

// Sanitize XML (défense en profondeur)
export function sanitizeXml(xml: string): string {
    return xml
        .replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F\u007F]/g, '')
        .trim();
}

// Parser BPMN
export function parseBPMN(xmlText: string): BpmnData {
    console.log('🔧 Début du parsing BPMN');
    const parser = new DOMParser();
    const sanitizedXmlText = sanitizeXml(xmlText);
    const xmlDoc = parser.parseFromString(sanitizedXmlText, 'application/xml');

    const parserError = xmlDoc.getElementsByTagName('parsererror')[0];
    if (parserError) {
        console.error('Erreur XML :', parserError.textContent);
        throw new Error(parserError.textContent || 'Erreur de parsing XML');
    }

    console.log('📦 Document XML parsé');

    const elements: BpmnElements = {
        lanes: [],
        laneSets: [],
        processes: [],
        events: [],
        tasks: [],
        gateways: [],
        flows: [],
        participants: [],
        annotations: [],
        messageFlow: []
    };

    //---------------------
    // Processes
    const processes = xmlDoc.querySelectorAll('process');
    console.log(`📋 Processes trouvés: ${processes.length}`);

    for (const process of Array.from(processes)) {
        const processId = process.getAttribute('id');
        const processName = process.getAttribute('name') || '';

        console.log(`  📋 Process: ${processName} (${processId})`);

        elements.processes.push({
            id: processId,
            name: processName
        });
    }

    //---------------------
    // LaneSets et Lanes
    const laneSets = xmlDoc.querySelectorAll('laneSet');
    console.log(`🏊 LaneSets trouvés: ${laneSets.length}`);

    for (const laneSet of Array.from(laneSets)) {
        const laneSetId = laneSet.getAttribute('id');

        // Récupérer le process parent
        const processParent = laneSet.parentElement;
        const processId = processParent?.getAttribute('id') || '';
        const laneSetName = processParent?.getAttribute('name') || '';

        console.log(`  📋 LaneSet: ${laneSetId}, Process parent: ${laneSetName} (${processId})`);

        const lanes: any[] = [];

        // Récupérer toutes les lanes du laneSet
        const laneElements = laneSet.querySelectorAll(':scope > lane');
        console.log(`  └─ Lanes dans ${laneSetId}: ${laneElements.length}`);

        for (const lane of Array.from(laneElements)) {
            const laneData = {
                id: lane.getAttribute('id'),
                name: lane.getAttribute('name'),
                flowNodeRefs: Array.from(lane.querySelectorAll('flowNodeRef')).map(
                    ref => ref.textContent?.trim()
                ).filter(Boolean)
            };
            lanes.push(laneData);
            console.log(`    • Lane: ${laneData.name} (${laneData.id})`);
        }

        if (lanes.length > 0) {
            elements.laneSets.push({
                id: laneSetId,
                name: laneSetName,
                processId: processId,
                lanes: lanes
            });
        }
    }

    // Parser également les lanes qui ne sont pas dans un laneSet
    const standaloneLanes = xmlDoc.querySelectorAll('process > lane, collaboration > lane');
    console.log(`🏊 Lanes standalone trouvées: ${standaloneLanes.length}`);
    for (const lane of Array.from(standaloneLanes)) {
        elements.lanes.push({
            id: lane.getAttribute('id'),
            name: lane.getAttribute('name'),
            flowNodeRefs: Array.from(lane.querySelectorAll('flowNodeRef')).map(
                ref => ref.textContent?.trim()
            ).filter(Boolean)
        });
    }


    //---------------------
    // Participants
    const participants = xmlDoc.getElementsByTagName('participant');
    console.log(`🏊 Participants trouvés: ${participants.length}`);
    for (const p of Array.from(participants)) {
        elements.participants.push({
            id: p.getAttribute('id'),
            name: p.getAttribute('name'),
            processRef: p.getAttribute('processRef'),
        });
    }

    //---------------------
    // Events
    const events = xmlDoc.querySelectorAll('startEvent, endEvent, intermediateCatchEvent, intermediateThrowEvent');
    console.log(`▶️ Events trouvés: ${events.length}`);
    for (const event of Array.from(events)) {
        elements.events.push({
            id: event.getAttribute('id'),
            name: event.getAttribute('name'),
            type: event.tagName,
            // backgroundColor: se.getElementsByTagNameNS('*', 'bpmndi:BPMNShape')[0].getAttribute('color:background-color'),
        });
    }

    //---------------------
    // Tasks
    const tasks = xmlDoc.querySelectorAll('task, userTask, manualTask, serviceTask, callActivity');
    console.log(`📋 Tasks trouvées: ${elements.tasks.length}`);
    for (const t of tasks) {
        elements.tasks.push({
            id: t.getAttribute('id'),
            name: t.getAttribute('name'),
            type: t.tagName,
        });
    }

    //--------------------
    // Gateways
    const gateways = xmlDoc.querySelectorAll('exclusiveGateway, parallelGateway, inclusiveGateway');
    console.log(`🔀 Gateways trouvés: ${gateways.length}`);
    gateways.forEach((g) => {
        elements.gateways.push({
            id: g.getAttribute('id'),
            name: g.getAttribute('name'),
            type: g.tagName,
        });
    });

    //---------------------
    // Flows
    const flows = xmlDoc.getElementsByTagNameNS('*', 'sequenceFlow');
    console.log(`➡️ Flows trouvés: ${flows.length}`);

    for (const f of Array.from(flows)) {
        const flowId = f.getAttribute('id');

        // Chercher l'élément BPMNEdge correspondant dans le diagramme
        const bpmnEdges = xmlDoc.getElementsByTagNameNS('*', 'BPMNEdge');
        let waypoints: Array<{x: number, y: number}> = [];

        for (const edge of Array.from(bpmnEdges)) {
            if (edge.getAttribute('bpmnElement') === flowId) {
                // Récupérer tous les waypoints
                const waypointElements = edge.getElementsByTagNameNS('*', 'waypoint');
                waypoints = Array.from(waypointElements).map(wp => ({
                    x: parseFloat(wp.getAttribute('x') || '0'),
                    y: parseFloat(wp.getAttribute('y') || '0')
                }));
                break;
            }
        }

        elements.flows.push({
            id: flowId,
            name: f.getAttribute('name'),
            source: f.getAttribute('sourceRef'),
            target: f.getAttribute('targetRef'),
            waypoints: waypoints
        });
    }

    //---------------------
    // messageFlow
    const messageFlows = xmlDoc.getElementsByTagNameNS('*', 'messageFlow');
    console.log(`➡️ messageFlow trouvés: ${messageFlows.length}`);

    for (const f of Array.from(messageFlows)) {
        const flowId = f.getAttribute('id');

        // Chercher l'élément BPMNEdge correspondant dans le diagramme
        const bpmnEdges = xmlDoc.getElementsByTagNameNS('*', 'BPMNEdge');
        let waypoints: Array<{x: number, y: number}> = [];

        for (const edge of Array.from(bpmnEdges)) {
            if (edge.getAttribute('bpmnElement') === flowId) {
                // Récupérer tous les waypoints
                const waypointElements = edge.getElementsByTagNameNS('*', 'waypoint');
                waypoints = Array.from(waypointElements).map(wp => ({
                    x: parseFloat(wp.getAttribute('x') || '0'),
                    y: parseFloat(wp.getAttribute('y') || '0')
                }));
                break;
            }
        }

        elements.messageFlow.push({
            id: flowId,
            source: f.getAttribute('sourceRef'),
            target: f.getAttribute('targetRef'),
            waypoints: waypoints
        });
    }

    //---------------------
    // Annotations
    const annotations = xmlDoc.getElementsByTagName('textAnnotation');
    console.log(`🏊 Annotations trouvées: ${annotations.length}`);
    for (const a of Array.from(annotations)) {
        console.log("annotation: ", a.getElementsByTagName('text')[0].textContent);
        elements.annotations.push({
            id: a.getAttribute('id'),
            text: a.getElementsByTagName('text')[0]?.textContent,
        });
    }

    //---------------------
    // Positions
    const shapes = xmlDoc.getElementsByTagNameNS('*', 'BPMNShape');
    console.log(`📐 Shapes trouvés: ${shapes.length}`);
    const positions: BpmnPositions = {};
    const labelPositions: Record<string, { x: number, y: number, width: number, height: number }> = {};

    for (const shape of Array.from(shapes)) {
        const id = shape.getAttribute('bpmnElement');
        const bounds = shape.getElementsByTagNameNS('*', 'Bounds')[0];

        if (id && bounds) {
            positions[id] = {
                x: parseFloat(bounds.getAttribute('x') || '0'),
                y: parseFloat(bounds.getAttribute('y') || '0'),
                width: parseFloat(bounds.getAttribute('width') || '0'),
                height: parseFloat(bounds.getAttribute('height') || '0'),
            };

            // Parser la position du label si elle existe
            const label = shape.getElementsByTagNameNS('*', 'BPMNLabel')[0];
            if (label) {
                const labelBounds = label.getElementsByTagNameNS('*', 'Bounds')[0];
                if (labelBounds) {
                    labelPositions[id] = {
                        x: parseFloat(labelBounds.getAttribute('x') || '0'),
                        y: parseFloat(labelBounds.getAttribute('y') || '0'),
                        width: parseFloat(labelBounds.getAttribute('width') || '0'),
                        height: parseFloat(labelBounds.getAttribute('height') || '0'),
                    };
                    console.log(`📝 Label pour ${id}: (${labelPositions[id].x}, ${labelPositions[id].y})`);
                }
            }
        }
    }

    console.log('📍 Positions récupérées:', Object.keys(positions).length);

    // Parser les positions des labels sur les BPMNEdge (sequenceFlow, messageFlow)
    const bpmnEdgeElements = xmlDoc.getElementsByTagNameNS('*', 'BPMNEdge');
    for (const edge of Array.from(bpmnEdgeElements)) {
        const flowId = edge.getAttribute('bpmnElement');
        if (flowId) {
            const label = edge.getElementsByTagNameNS('*', 'BPMNLabel')[0];
            if (label) {
                const labelBounds = label.getElementsByTagNameNS('*', 'Bounds')[0];
                if (labelBounds) {
                    labelPositions[flowId] = {
                        x: parseFloat(labelBounds.getAttribute('x') || '0'),
                        y: parseFloat(labelBounds.getAttribute('y') || '0'),
                        width: parseFloat(labelBounds.getAttribute('width') || '0'),
                        height: parseFloat(labelBounds.getAttribute('height') || '0'),
                    };
                }
            }
        }
    }

    console.log('📝 Labels récupérés:', Object.keys(labelPositions).length);
    console.log('✅ Parsing terminé avec succès');

    const data: BpmnData = { elements, positions, labelPositions, xmlDoc };
    currentBpmnData = data;

    return data;
}

//=============================================
// Changes swimlane orientation while collapsed
const getStyle = function (this: Cell) {
    if (!this.isCollapsed()) {
        return this.style;
    }
    // Need to create a copy the original style as we don't want to change the original style stored in the Cell
    // Otherwise, when expanding the cell, the style will be incorrect
    const style = { ...this.style };
    style.horizontal = true;
    style.align = 'left';
    style.spacingLeft = 14;
    return style;
};

const insertVertex = (graph: Graph, options: VertexParameters) => {
    const v = graph.insertVertex(options);
    v.getStyle = getStyle;
    return v;
};

const insertEdge = (graph: Graph, options: EdgeParameters) => {
    const e = graph.insertEdge(options);
    e.getStyle = getStyle;
    return e;
};
//=============================================

// Dessiner le diagramme
export function drawDiagram(graph: Graph, data: BpmnData): void {
    console.log('🎨 Début du dessin du diagramme');
    const { elements, positions, labelPositions } = data;
    const parent = graph.getDefaultParent();
    const vertexMap: Record<string, Cell> = {};

    graph.model.beginUpdate();
    try {
        // Clear graph
        graph.removeCells(graph.getChildCells(parent));
        console.log('🧹 Cellules précédentes supprimées');

        //---------------------
        // Processes (créer des lanes pour les process non référencés par des participants)
        elements.processes.forEach((process) => {
            // Vérifier si ce process est référencé par un participant
            const isReferencedByParticipant = elements.participants.some(p => p.processRef === process.id);

            if (isReferencedByParticipant) {
                console.log(`📋 Process ${process.name} (${process.id}) déjà géré par un participant - skip`);
                return;
            }

            console.log(`📋 Traitement Process: ${process.name} (${process.id})`);

            // Trouver les laneSets qui appartiennent à ce process
            const processLaneSets = elements.laneSets.filter(ls => ls.processId === process.id);

            if (processLaneSets.length === 0) {
                // Process sans laneSet - ne pas créer de lane pour le process
                console.log(`  ℹ️ Process ${process.id} sans laneSet`);
                return;
            }

            // Collecter toutes les lanes de tous les laneSets de ce process
            const allProcessLanes: any[] = [];
            processLaneSets.forEach(ls => {
                allProcessLanes.push(...ls.lanes);
            });

            if (allProcessLanes.length === 0) {
                console.warn(`⚠️ Pas de lanes pour le process ${process.id}`);
                return;
            }

            // Calculer la position du process à partir des lanes
            const allLanePositions = allProcessLanes
                .map(lane => positions[lane.id])
                .filter(Boolean);

            if (allLanePositions.length === 0) {
                console.warn(`⚠️ Pas de position pour le process ${process.id}`);
                return;
            }

            const minX = Math.min(...allLanePositions.map(p => p.x));
            const minY = Math.min(...allLanePositions.map(p => p.y));
            const maxX = Math.max(...allLanePositions.map(p => p.x + p.width));
            const maxY = Math.max(...allLanePositions.map(p => p.y + p.height));

            const processPos = {
                x: minX,
                y: minY,
                width: maxX - minX,
                height: maxY - minY
            };

            // Créer la lane pour le process
            const processVertex = graph.insertVertex({
                parent,
                value: process.name || process.id || 'Process',
                id: process.id,
                position: [processPos.x, processPos.y],
                size: [processPos.width, processPos.height],
                style: { baseStyleNames: ['lane'] },
            });

            vertexMap[process.id] = processVertex;

            // Créer les lanes directement dans le process (sans niveau laneSet)
            allProcessLanes.forEach((lane: any) => {
                const lanePos = positions[lane.id];
                if (!lanePos) {
                    console.warn(`⚠️ Pas de position pour la lane ${lane.id}`);
                    return;
                }

                // Position relative au process parent
                const relativeX = 20;
                const relativeY = lanePos.y - processPos.y;

                console.log(`  └─ Lane: ${lane.name} (${lane.id}) - pos relative: [${relativeX}, ${relativeY}]`);

                const laneVertex = graph.insertVertex({
                    parent: processVertex,
                    value: lane.name || '',
                    id: lane.id,
                    position: [relativeX, relativeY],
                    size: [lanePos.width, lanePos.height],
                    style: { baseStyleNames: ['lane'] },
                });

                vertexMap[lane.id] = laneVertex;
            });

            // Mettre le process en arrière-plan
            graph.orderCells(true, [processVertex]);
        });

        //---------------------
        // Lanes
        elements.lanes.forEach((lane) => {
            const pos = positions[lane.id] || { x: 0, y: 0, width: 36, height: 36 };

            // Ajouter le carré
            const parent = graph.getDefaultParent();

            const vertex = graph.insertVertex({
                parent,
                value: lane.name || 'Lane',
                id: lane.id,
                position: [pos.x, pos.y],
                size: [pos.width, pos.height],
                style: { baseStyleNames: ['lane'] },
            });

            vertexMap[lane.id] = vertex;

            // Mettre en arrière plan
            graph.orderCells(true, [vertex]);


        });

        //---------------------
        // Participants (créer des lanes pour les participants avec processRef)
        elements.participants.forEach((p) => {
            const pos = positions[p.id] || { x: 100, y: 100, width: 600, height: 250 };

            // Si le participant a un processRef, il devient une lane parent pour le process
            if (p.processRef) {
                const referencedProcess = elements.processes.find(proc => proc.id === p.processRef);
                if (referencedProcess) {
                    console.log(`👤 Participant avec processRef: ${p.name} -> ${referencedProcess.name}`);

                    // Le participant devient la lane principale
                    const participantVertex = graph.insertVertex({
                        parent,
                        value: p.name || 'Participant',
                        id: p.id,
                        position: [pos.x, pos.y],
                        size: [pos.width, pos.height],
                        style: { baseStyleNames: ['lane'] },
                    });

                    vertexMap[p.id] = participantVertex;

                    // Trouver les laneSets de ce process
                    const processLaneSets = elements.laneSets.filter(ls => ls.processId === referencedProcess.id);

                    // Collecter toutes les lanes
                    const allProcessLanes: any[] = [];
                    processLaneSets.forEach(ls => {
                        allProcessLanes.push(...ls.lanes);
                    });

                    // Créer les lanes directement dans le participant
                    allProcessLanes.forEach((lane: any) => {
                        const lanePos = positions[lane.id];
                        if (!lanePos) {
                            console.warn(`⚠️ Pas de position pour la lane ${lane.id}`);
                            return;
                        }

                        // Position relative au participant parent
                        const relativeX = 20;
                        const relativeY = lanePos.y - pos.y;

                        console.log(`  └─ Lane: ${lane.name} (${lane.id}) - pos relative: [${relativeX}, ${relativeY}]`);

                        const laneVertex = graph.insertVertex({
                            parent: participantVertex,
                            value: lane.name || '',
                            id: lane.id,
                            position: [relativeX, relativeY],
                            size: [lanePos.width, lanePos.height],
                            style: { baseStyleNames: ['lane'] },
                        });

                        vertexMap[lane.id] = laneVertex;
                    });

                    // Mettre le participant en arrière-plan
                    graph.orderCells(true, [participantVertex]);
                } else {
                    // ProcessRef non trouvé, créer un participant simple
                    vertexMap[p.id] = graph.insertVertex({
                        parent,
                        value: p.name || 'Participant',
                        id: p.id,
                        position: [pos.x, pos.y],
                        size: [pos.width, pos.height],
                        style: { baseStyleNames: ['lane'] },
                    });
                }
            } else {
                // Participant sans processRef, créer une lane simple
                vertexMap[p.id] = graph.insertVertex({
                    parent,
                    value: p.name || 'Participant',
                    id: p.id,
                    position: [pos.x, pos.y],
                    size: [pos.width, pos.height],
                    style: { baseStyleNames: ['lane'] },
                });
            }
        });

        //---------------------
        // Créer une map des flowNodes vers leurs lanes parentes
        const flowNodeToLane: Record<string, { laneId: string, processId?: string }> = {};

        // Pour les lanes dans les laneSets (qui sont dans les processes)
        elements.laneSets.forEach((laneSet) => {
            laneSet.lanes.forEach((lane: any) => {
                lane.flowNodeRefs?.forEach((flowNodeId: string) => {
                    flowNodeToLane[flowNodeId] = {
                        laneId: lane.id,
                        processId: laneSet.processId
                    };
                });
            });
        });

        // Pour les lanes standalone
        elements.lanes.forEach((lane) => {
            lane.flowNodeRefs?.forEach((flowNodeId: string) => {
                flowNodeToLane[flowNodeId] = { laneId: lane.id };
            });
        });

        // Fonction helper pour obtenir le parent et la position relative d'un flowNode
        const getParentAndPosition = (flowNodeId: string, absolutePos: { x: number, y: number, width: number, height: number }) => {
            const laneInfo = flowNodeToLane[flowNodeId];
            if (!laneInfo) {
                // Pas de lane parent, utiliser le parent par défaut
                return {
                    parent: parent,
                    x: absolutePos.x,
                    y: absolutePos.y
                };
            }

            const laneVertex = vertexMap[laneInfo.laneId];
            if (!laneVertex) {
                console.warn(`⚠️ Lane ${laneInfo.laneId} introuvable pour flowNode ${flowNodeId}`);
                return {
                    parent: parent,
                    x: absolutePos.x,
                    y: absolutePos.y
                };
            }

            const lanePos = positions[laneInfo.laneId];
            if (!lanePos) {
                console.warn(`⚠️ Position de lane ${laneInfo.laneId} introuvable`);
                return {
                    parent: parent,
                    x: absolutePos.x,
                    y: absolutePos.y
                };
            }

            // Calculer la position relative
            let relativeX = absolutePos.x - lanePos.x;
            let relativeY = absolutePos.y - lanePos.y;

            // Ajuster pour les marges des swimlanes (10px par niveau)
            if (laneInfo.processId) {
                // Lane dans un process : 2 niveaux de marge = 20px
                relativeX += 20;
            } else {
                // Lane standalone : 1 niveau de marge = 10px
                relativeX += 10;
            }

            return {
                parent: laneVertex,
                x: relativeX,
                y: relativeY
            };
        };

        // Calcule le point médian exact (en longueur d'arc) d'un chemin polylignal.
        const computeEdgeMidpoint = (waypoints: Array<{ x: number; y: number }>): { x: number; y: number } => {
            if (waypoints.length === 0) return { x: 0, y: 0 };
            if (waypoints.length === 1) return waypoints[0];
            let totalLength = 0;
            const segLengths: number[] = [];
            for (let i = 1; i < waypoints.length; i++) {
                const dx = waypoints[i].x - waypoints[i - 1].x;
                const dy = waypoints[i].y - waypoints[i - 1].y;
                const len = Math.sqrt(dx * dx + dy * dy);
                segLengths.push(len);
                totalLength += len;
            }
            const half = totalLength / 2;
            let accumulated = 0;
            for (let i = 0; i < segLengths.length; i++) {
                if (accumulated + segLengths[i] >= half) {
                    const t = segLengths[i] > 0 ? (half - accumulated) / segLengths[i] : 0;
                    return {
                        x: waypoints[i].x + t * (waypoints[i + 1].x - waypoints[i].x),
                        y: waypoints[i].y + t * (waypoints[i + 1].y - waypoints[i].y)
                    };
                }
                accumulated += segLengths[i];
            }
            return waypoints[waypoints.length - 1];
        };

        // Positionne le label d'un vertex selon les coordonnées BPMN absolues.
        //
        // Pipeline MaxGraph (vérifié sur le source de @maxgraph/core@0.21.0) :
        //
        //   updateCellState() :
        //     absoluteOffset.x = scale * geometry.offset.x
        //     absoluteOffset.y = scale * geometry.offset.y
        //
        //   updateVertexLabelOffset() (appelé juste après) :
        //     si VLP='bottom' : absoluteOffset.y += state.height
        //     si VLP='top'    : absoluteOffset.y -= state.height
        //
        //   CellRenderer.getLabelBounds() :
        //     bounds.x = state.x + absoluteOffset.x
        //     bounds.y = state.y + absoluteOffset.y
        //
        //   rotateLabelBounds() (toujours appelé) :
        //     bounds.y -= textShape.margin.y * bounds.height   // margin.y = -1 si valign='top' → +bounds.height
        //     bounds.x -= textShape.margin.x * bounds.width    // margin.x = -0.5 si align='center'
        //     bounds.x += spacing.x * scale                    // spacing.x = (spacingLeft - spacingRight) / 2
        //     bounds.y += spacing.y * scale                    // spacing.y = spacingTop + baseSpacing (si valign='top')
        //
        // Pour VLP='bottom' + valign='top' (gateway, state, conversation) :
        //   bounds.y = vertexY + offset.y + vertexH + (-(-1) * labelH) + (spacingTop + baseSpacing)
        //            = vertexY + offset.y + vertexH + labelH + spacingTop + baseSpacing
        //   On veut bounds.y = labelPos.y  →
        //   offset.y = labelPos.y - vertexY - vertexH - labelH - spacingTop - baseSpacing
        //
        //   bounds.x = vertexX + offset.x + (-(-0.5) * labelW) + (spacingLeft - spacingRight) / 2
        //            = vertexX + offset.x + 0.5*labelW + (spacingLeft - spacingRight)/2
        //   On veut bounds.x = labelPos.x  →
        //   offset.x = labelPos.x - vertexX - 0.5*labelW - (spacingLeft - spacingRight)/2
        //
        // Pour VLP='middle' + valign='middle' (process/task, label interne centré) :
        //   offset = labelCenter - vertexCenter  (margin.y=-0.5, spacing s'annule)
        //
        // Les deux formules portent sur des différences de coordonnées BPMN absolues :
        // elles sont invariantes par rapport au repère du conteneur (swimlane).
        const applyLabelPosition = (
            vertex: Cell,
            elementId: string,
            elementPos: { x: number; y: number; width: number; height: number }
        ) => {
            const labelPos = labelPositions[elementId];
            if (!labelPos || !vertex) return;

            const geometry = vertex.getGeometry();
            if (!geometry) return;

            // graph.getCellStyle() résout les baseStyleNames → valeurs effectives
            const rs = graph.getCellStyle(vertex) as any;
            const vlp = (rs?.verticalLabelPosition ?? 'middle') as string;
            const baseSpacing  = (rs?.spacing      ?? 2)  as number;
            const spacingTop   = (rs?.spacingTop   ?? 0)  as number;
            const spacingLeft  = (rs?.spacingLeft  ?? 0)  as number;
            const spacingRight = (rs?.spacingRight ?? 0)  as number;

            let offsetX: number;
            let offsetY: number;

            if (vlp === 'bottom') {
                // valign='top' → margin.y = 0 → bounds.y += 0*labelH = 0 (pas de décalage margin)
                // spacing.y = spacingTop + baseSpacing
                const spacingY = spacingTop + baseSpacing;
                // spacing.x = (spacingLeft - spacingRight) / 2
                const spacingX = (spacingLeft - spacingRight) / 2;
                offsetX = labelPos.x - elementPos.x - 0.5 * labelPos.width - spacingX;
                offsetY = labelPos.y - elementPos.y - elementPos.height - labelPos.height - spacingY;
                // Fix inexplicable mais qui rend bien
                offsetY += 20;
                offsetX += labelPos.width;
            } else if (vlp === 'top') {
                const spacingY = spacingTop + baseSpacing;
                const spacingX = (spacingLeft - spacingRight) / 2;
                offsetX = labelPos.x - elementPos.x - 0.5 * labelPos.width - spacingX;
                offsetY = labelPos.y + labelPos.height - elementPos.y + elementPos.height - spacingY;
            } else {
                // VLP='middle' : valign='middle' → margin.y = -0.5, spacing.y s'annule
                // bounds.y = vertexY + offset.y + (-(-0.5)*labelH) = vertexY + offset.y + 0.5*labelH
                // On veut bounds.y = labelPos.y → offset.y = labelPos.y - vertexY - 0.5*labelH
                // Équivalent : offset = labelCenter - vertexCenter
                offsetX = (labelPos.x + labelPos.width  / 2) - (elementPos.x + elementPos.width  / 2);
                offsetY = (labelPos.y + labelPos.height / 2) - (elementPos.y + elementPos.height / 2);
            }

            geometry.offset = new Point(offsetX, offsetY);

            // labelWidth contrôle le retour à la ligne — mutation directe
            const cellStyle = vertex.getStyle();
            cellStyle.labelWidth = labelPos.width;
            vertex.setStyle(cellStyle);

            vertex.setGeometry(geometry);

            console.log(`📝 Label ${elementId} (vlp=${vlp}): offset=(${offsetX.toFixed(1)}, ${offsetY.toFixed(1)}), labelWidth=${labelPos.width}`);
        };

        //---------------------
        // Events
        elements.events.forEach((event) => {
            console.log("Add event ", event);
            const pos = positions[event.id] || { x: 200, y: 150, width: 36, height: 36 };

            // Obtenir le parent correct et la position relative
            const { parent: eventParent, x, y } = getParentAndPosition(event.id, pos);

            const vertex: Cell = addBPMNState(graph, eventParent, x, y);
            vertexMap[event.id] = vertex;
            vertex.setValue(event.name || '');
            console.log("set color ", event.backgroundColor);
            graph.setCellStyles("fillColor", event.backgroundColor, [vertex]);

            // Définir la taille selon le XML
            const geometry = vertex.getGeometry();
            if (geometry) {
                geometry.width = pos.width;
                geometry.height = pos.height;
                vertex.setGeometry(geometry);
            }

            // Appliquer la position du label
            applyLabelPosition(vertex, event.id, pos);

            switch (event.type) {
                case "startEvent":
                    setIconCellValue(graph, vertex, BPMN_ICONS.START_EVENT);
                    break;
                case "endEvent":
                    setIconCellValue(graph, vertex, BPMN_ICONS.END_EVENT);
                    break;
                case "intermediateCatchEvent":
                    setIconCellValue(graph, vertex, BPMN_ICONS.LINK_CATCH_EVENT);
                    break;
                case "intermediateThrowEvent":
                    setIconCellValue(graph, vertex, BPMN_ICONS.LINK_THROW_EVENT);
                    break;
                default:
                    console.log("Unknown event type: ", event.type, ' name: ', event.name);
            }

        });

        //---------------------
        // Tasks
        elements.tasks.forEach((t) => {
            console.log("Add task ", t);
            const pos = positions[t.id] || { x: 300, y: 130, width: 100, height: 80 };

            // Obtenir le parent correct et la position relative
            const { parent: taskParent, x, y } = getParentAndPosition(t.id, pos);

            const vertex: Cell = addBPMNTask(graph, taskParent, x, y);
            vertexMap[t.id] = vertex;
            vertex.setValue(t.name || '');

            // Définir la taille selon le XML
            const geometry = vertex.getGeometry();
            if (geometry) {
                geometry.width = pos.width;
                geometry.height = pos.height;
                vertex.setGeometry(geometry);
            }

            // Appliquer la position du label
            applyLabelPosition(vertex, t.id, pos);

            switch (t.type) {
                case "task":
                case "model:task":
                    break;
                case "manualTask":
                case "model:manualTask":
                    setIconCellValue(graph, vertex, BPMN_ICONS.MANUAL_TASK);
                    break;
                case "serviceTask":
                case "model:serviceTask":
                    setIconCellValue(graph, vertex, BPMN_ICONS.SERVICE_TASK);
                    break;
                case "userTask":
                case "model:userTask":
                    setIconCellValue(graph, vertex, BPMN_ICONS.USER_TASK);
                    break;
                case "callActivity":
                case "model:callActivity":
                    setSubProcessMarker(graph, vertex);
                    break;
                default:
                    console.log("Unknown task type: ", t.type, ' ', t.name);
            }
        });

        //---------------------
        // Gateways
        elements.gateways.forEach((g) => {
            console.log("Add gateway ", g.name);
            const pos = positions[g.id] || { x: 450, y: 145, width: 50, height: 50 };

            // Obtenir le parent correct et la position relative
            const { parent: gatewayParent, x, y } = getParentAndPosition(g.id, pos);

            const vertex = addBPMNGateway(graph, gatewayParent, x, y);
            vertexMap[g.id] = vertex;
            vertex.setValue(g.name || '');

            // Définir la taille selon le XML
            const geometry = vertex.getGeometry();
            if (geometry) {
                geometry.width = pos.width;
                geometry.height = pos.height;
                vertex.setGeometry(geometry);
            }

            // Appliquer la position du label
            applyLabelPosition(vertex, g.id, pos);

            switch (g.type) {
                case "exclusiveGateway":
                case "model:exclusiveGateway":
                    setIconCellValue(graph, vertex, BPMN_ICONS.EXCLUSIVE_GATEWAY);
                    break;
                case "parallelGateway":
                case "model:parallelGateway":
                    setIconCellValue(graph, vertex, BPMN_ICONS.PARALLEL_GATEWAY);
                    break;
                case "inclusiveGateway":
                case "model:inclusiveGateway":
                    setIconCellValue(graph, vertex, BPMN_ICONS.INCLUSIVE_GATEWAY);
                    break;
                default:
                    console.log("Unknown gateway type: ", g.type, ' ', g.name);
            }
        });

        //---------------------
        // Annotations
        elements.annotations.forEach((a) => {
            console.log("Add annotation ", a.name);
            const pos = positions[a.id] || { x: 450, y: 145, width: 50, height: 50 };

            // Obtenir le parent correct et la position relative
            const { parent: annotationParent, x, y } = getParentAndPosition(a.id, pos);

            const vertex = addBPMNAnnotation(graph, annotationParent, x, y);
            vertexMap[a.id] = vertex;
            vertex.setValue(a.text || '');

            // Définir la taille selon le XML
            const geometry = vertex.getGeometry();
            if (geometry) {
                geometry.width = pos.width;
                geometry.height = pos.height;
                vertex.setGeometry(geometry);
            }

            // Appliquer la position du label
            applyLabelPosition(vertex, a.id, pos);
        });

        //---------------------
        // Créer les edges avec waypoints
        for (const flow of elements.flows) {
            const sourceCell = vertexMap[flow.source];
            const targetCell = vertexMap[flow.target];

            if (sourceCell && targetCell) {
                const edge = addBPMNConnection(graph, sourceCell, targetCell);

                edge.setValue(flow.name || '');

                // Utiliser les waypoints pour définir la géométrie complète de l'edge
                if (flow.waypoints && flow.waypoints.length >= 2) {
                    const geometry = edge.getGeometry();
                    if (geometry) {
                        // Le parent de l'edge est le parent commun le plus profond
                        const edgeParent = edge.getParent();

                        // Calculer l'offset absolu du parent de l'edge
                        let offsetX = 0;
                        let offsetY = 0;
                        let swimlaneCount = 0;

                        // Remonter la hiérarchie pour calculer l'offset
                        let current = edgeParent;
                        while (current && current !== parent) {
                            const geo = current.getGeometry();
                            if (geo) {
                                offsetX += geo.x;
                                offsetY += geo.y;
                            }

                            // Compter les swimlanes pour l'ajustement
                            const style = current.getStyle();
                            if (style && style.baseStyleNames && style.baseStyleNames.includes('lane')) {
                                swimlaneCount++;
                            }

                            current = current.getParent();
                        }

                        // Soustraire l'ajustement fait aux objets (20px par niveau de swimlane)
                        offsetX -= (swimlaneCount * 20);

                        // Obtenir les positions des cellules source et target
                        const sourceGeo = sourceCell.getGeometry();
                        const targetGeo = targetCell.getGeometry();

                        if (sourceGeo && targetGeo) {
                            // Calculer les positions absolues des cellules
                            // La position geometry.x contient déjà l'ajustement de marge (+20px ou +10px)
                            // donc on ne doit PAS soustraire cet ajustement
                            let sourceAbsX = sourceGeo.x;
                            let sourceAbsY = sourceGeo.y;
                            let targetAbsX = targetGeo.x;
                            let targetAbsY = targetGeo.y;

                            // Remonter la hiérarchie pour obtenir les positions absolues
                            let sourceParent = sourceCell.getParent();
                            while (sourceParent && sourceParent !== parent) {
                                const parentGeo = sourceParent.getGeometry();
                                if (parentGeo) {
                                    sourceAbsX += parentGeo.x;
                                    sourceAbsY += parentGeo.y;
                                }
                                sourceParent = sourceParent.getParent();
                            }

                            let targetParent = targetCell.getParent();
                            while (targetParent && targetParent !== parent) {
                                const parentGeo = targetParent.getGeometry();
                                if (parentGeo) {
                                    targetAbsX += parentGeo.x;
                                    targetAbsY += parentGeo.y;
                                }
                                targetParent = targetParent.getParent();
                            }

                            // Premier waypoint = point de sortie exact
                            const firstWaypoint = flow.waypoints[0];
                            const lastWaypoint = flow.waypoints[flow.waypoints.length - 1];

                            // Calculer les offsets relatifs à la cellule
                            geometry.sourcePoint = new Point(
                                firstWaypoint.x - sourceAbsX,
                                firstWaypoint.y - sourceAbsY
                            );

                            geometry.targetPoint = new Point(
                                lastWaypoint.x - targetAbsX,
                                lastWaypoint.y - targetAbsY
                            );

                            // Points intermédiaires (exclure premier et dernier)
                            if (flow.waypoints.length > 2) {
                                geometry.points = flow.waypoints.slice(1, -1).map((wp: { x: number; y: number; }) =>
                                    new Point(wp.x - offsetX, wp.y - offsetY)
                                );
                            }

                            graph.model.setGeometry(edge, geometry);
                        }

                        // Label du sequenceFlow
                        const edgeLabelPos = labelPositions[flow.id];
                        if (edgeLabelPos && flow.waypoints && flow.waypoints.length >= 2) {
                            const edgeGeom = edge.getGeometry()!;
                            const mid = computeEdgeMidpoint(flow.waypoints);
                            edgeGeom.offset = new Point(
                                (edgeLabelPos.x + edgeLabelPos.width  / 2) - mid.x,
                                (edgeLabelPos.y + edgeLabelPos.height / 2) - mid.y
                            );
                            const es = edge.getStyle();
                            es.labelWidth = edgeLabelPos.width;
                            edge.setStyle(es);
                            edge.setGeometry(edgeGeom);
                        }

                        console.log(`Flow ${flow.source} -> ${flow.target}: offset = (${offsetX}, ${offsetY}), swimlanes = ${swimlaneCount}`);
                    }
                }
            }
        }

        // Créer les edges avec waypoints pour messageFlow
        for (const flow of elements.messageFlow) {
            const sourceCell = vertexMap[flow.source];
            const targetCell = vertexMap[flow.target];

            console.log("Add message flow ", flow.source, flow.target);

            if (sourceCell && targetCell) {
                const edge = addBPMNConnection(graph, sourceCell, targetCell);
                setMessageFlow(graph, edge);

                // Utiliser les waypoints pour définir la géométrie complète de l'edge
                if (flow.waypoints && flow.waypoints.length >= 2) {
                    const geometry = edge.getGeometry();
                    if (geometry) {
                        // Le parent de l'edge est le parent commun le plus profond
                        const edgeParent = edge.getParent();

                        // Calculer l'offset absolu du parent de l'edge
                        let offsetX = 0;
                        let offsetY = 0;
                        let swimlaneCount = 0;

                        // Remonter la hiérarchie pour calculer l'offset
                        let current = edgeParent;
                        while (current && current !== parent) {
                            const geo = current.getGeometry();
                            if (geo) {
                                offsetX += geo.x;
                                offsetY += geo.y;
                            }

                            // Compter les swimlanes pour l'ajustement
                            const style = current.getStyle();
                            if (style && style.baseStyleNames && style.baseStyleNames.includes('lane')) {
                                swimlaneCount++;
                            }

                            current = current.getParent();
                        }

                        // Soustraire l'ajustement fait aux objets (20px par niveau de swimlane)
                        offsetX -= (swimlaneCount * 20);

                        // Obtenir les positions des cellules source et target
                        const sourceGeo = sourceCell.getGeometry();
                        const targetGeo = targetCell.getGeometry();

                        if (sourceGeo && targetGeo) {
                            // Calculer les positions absolues des cellules
                            // La position geometry.x contient déjà l'ajustement de marge (+20px ou +10px)
                            // donc on ne doit PAS soustraire cet ajustement
                            let sourceAbsX = sourceGeo.x;
                            let sourceAbsY = sourceGeo.y;
                            let targetAbsX = targetGeo.x;
                            let targetAbsY = targetGeo.y;

                            // Remonter la hiérarchie pour obtenir les positions absolues
                            let sourceParent = sourceCell.getParent();
                            while (sourceParent && sourceParent !== parent) {
                                const parentGeo = sourceParent.getGeometry();
                                if (parentGeo) {
                                    sourceAbsX += parentGeo.x;
                                    sourceAbsY += parentGeo.y;
                                }
                                sourceParent = sourceParent.getParent();
                            }

                            let targetParent = targetCell.getParent();
                            while (targetParent && targetParent !== parent) {
                                const parentGeo = targetParent.getGeometry();
                                if (parentGeo) {
                                    targetAbsX += parentGeo.x;
                                    targetAbsY += parentGeo.y;
                                }
                                targetParent = targetParent.getParent();
                            }

                            // Premier waypoint = point de sortie exact
                            const firstWaypoint = flow.waypoints[0];
                            const lastWaypoint = flow.waypoints[flow.waypoints.length - 1];

                            // Calculer les offsets relatifs à la cellule
                            geometry.sourcePoint = new Point(
                                firstWaypoint.x - sourceAbsX,
                                firstWaypoint.y - sourceAbsY
                            );

                            geometry.targetPoint = new Point(
                                lastWaypoint.x - targetAbsX,
                                lastWaypoint.y - targetAbsY
                            );

                            // Points intermédiaires (exclure premier et dernier)
                            if (flow.waypoints.length > 2) {
                                geometry.points = flow.waypoints.slice(1, -1).map((wp: { x: number; y: number; }) =>
                                    new Point(wp.x - offsetX, wp.y - offsetY)
                                );
                            }

                            graph.model.setGeometry(edge, geometry);
                        }

                        // Label du messageFlow
                        const msgLabelPos = labelPositions[flow.id];
                        if (msgLabelPos && flow.waypoints && flow.waypoints.length >= 2) {
                            const edgeGeom = edge.getGeometry()!;
                            const mid = computeEdgeMidpoint(flow.waypoints);
                            edgeGeom.offset = new Point(
                                (msgLabelPos.x + msgLabelPos.width  / 2) - mid.x,
                                (msgLabelPos.y + msgLabelPos.height / 2) - mid.y
                            );
                            const ms = edge.getStyle();
                            ms.labelWidth = msgLabelPos.width;
                            edge.setStyle(ms);
                            edge.setGeometry(edgeGeom);
                        }

                        console.log(`MessageFlow ${flow.source} -> ${flow.target}: offset = (${offsetX}, ${offsetY}), swimlanes = ${swimlaneCount}`);
                    }
                }
            }

        }

    } finally {
        graph.model.endUpdate();
    }

    console.log('✅ Diagramme dessiné avec succès');

    setTimeout(() => {
        graph.center();
        console.log('📐 Vue centrée');
    }, 100);
}

// Gestion de l’input fichier
export function bindBpmnFileInput(graph: Graph, inputId = 'file-input') {
    const input = document.getElementById(inputId) as HTMLInputElement | null;
    if (!input) {
        console.warn(`#${inputId} introuvable, pas de chargement BPMN via fichier`);
        return;
    }

    input.addEventListener('change', async (e: Event) => {
        console.log('📁 Événement file input déclenché');
        const target = e.target as HTMLInputElement;
        const file = target.files?.[0];
        if (!file) {
            console.log('❌ Pas de fichier sélectionné');
            return;
        }

        console.log('📄 Fichier:', file.name, file.size, 'bytes');
        const text = await file.text();
        console.log('📝 Contenu XML (extrait):', text.substring(0, 200) + '...');

        try {
            console.log('🔍 Parsing BPMN...');
            const data = parseBPMN(text);
            console.log('✅ Parsing réussi');

            console.log('🎨 Dessin du diagramme...');
            drawDiagram(graph, data);

            const totalElements =
                data.elements.events.length +
                data.elements.tasks.length +
                data.elements.gateways.length;

            console.log(`📊 Total: ${totalElements} éléments, ${data.elements.flows.length} flux`);

            showStatus('✓ Fichier chargé avec succès');
        } catch (error: any) {
            console.error('❌ ERREUR:', error);
            showStatus('✗ Erreur lors du chargement du fichier');
        }
    });
}