// src/bpmn-save.ts
import { AbstractGraph, ModelXmlSerializer } from '@maxgraph/core';
import { showStatus } from './bpmn-edit';

// Expose helpers globaux comme avant
declare global {
    interface Window {
        loadGraph?: (xml: string) => void;
        getXMLGraph?: () => string;
    }
}

// Import d’un XML MaxGraph dans le modèle
export function loadGraphXml(graph: AbstractGraph, xml: string) {
    new ModelXmlSerializer(graph.model).import(xml);
}

// Générer le XML BPMN mis à jour (positions)
export function getXMLGraph(graph: AbstractGraph) {

    console.log('💾 Génération du XML BPMN mis à jour');

    const xml = new ModelXmlSerializer(graph.model).export();

    console.log('✅ XML BPMN généré');
    return xml;
}

export async function saveGraphToDatabase(
    id: number,
    name: string,
    type: string,
    content: string
): Promise<number> {  // Retourne l'ID du graphe (nouveau ou existant)
    console.log('saveGraphToDatabase:', id, 'name:', name);

    // Validation du nom
    if (!name || name.trim() === '') {
        const errorMsg = 'Le nom du graphe est obligatoire.';
        console.error(errorMsg);
        alert(errorMsg);
        throw new Error(errorMsg);
    }

    try {
        const response = await fetch(`/admin/bpmn/${id}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content') ?? '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                _method: 'PUT',
                id,
                name,
                type,
                content,
            }),
        });

        console.log('réponse :', response.status);
        if (response.status !== 200) {
            let errorMsg = 'Erreur lors de la sauvegarde du graphe.';
            try {
                const error = await response.json();
                errorMsg = error.message || errorMsg;
            } catch (_) {
                // ignore JSON parse error
            }
            throw new Error(errorMsg);
        }

        // Récupérer l'ID du graphe depuis la réponse
        const data = await response.json();
        const graphId = data.graph_id;

        // Mettre à jour l'input hidden si présent dans le DOM
        const idInput = document.getElementById('id') as HTMLInputElement;
        if (idInput && graphId) {
            idInput.value = graphId.toString();
        }

        // Mettre à jour l'URL sans recharger la page
        if (id === -1 && graphId) {
            window.history.replaceState({}, '', `/admin/bpmn/${graphId}`);
        }

        showStatus('✓ Graphe sauvegardé', 2000);

        return graphId; // Retourner l'ID pour l'appelant

    } catch (error) {
        console.error('Erreur lors de la sauvegarde :', error);
        alert('Erreur lors de la sauvegarde du graphe.');
        throw error; // Relancer l'erreur pour que l'appelant puisse la gérer
    }
}

// Câbler le bouton "save"
export function bindSaveButton(graph: AbstractGraph, buttonId = 'save-btn') {
    const btn = document.getElementById(buttonId);
    if (!btn) {
        console.warn(`#${buttonId} introuvable, bouton de sauvegarde non câblé`);
        return;
    }

    btn.addEventListener('click', () => {
        const id = Number((window.document.querySelector('#id') as HTMLInputElement | null)?.value);
        const name = (window.document.querySelector('#name') as HTMLInputElement | null)?.value ?? '';
        const type = (window.document.querySelector('#type') as HTMLInputElement | null)?.value ?? '';

        const xml = getXMLGraph(graph);
        if (!xml) {
            showStatus('✗ Impossible de générer le BPMN', 3000);
            return;
        }

        saveGraphToDatabase(id, name, type, xml).then(() =>
            console.log('Graphe sauvegardé avec succès')
        );
    });

    console.log('💾 Bouton de sauvegarde câblé');
}

// Exposer les helpers sur window (comme avant)
export function exposeGraphHelpers(graph: AbstractGraph) {
    window.loadGraph = (xml: string) => loadGraphXml(graph, xml);
    window.getXMLGraph = () => getXMLGraph(graph);
}
