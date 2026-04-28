// bpmp-api.ts

// Call the API to get the object list
import {BpmnElementDef} from "./bpmn-menu-select";

export async function fetchGraphObjects(): Promise<BpmnElementDef[]> {
    const res = await fetch("/admin/bpmn/objects", {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin", // important si session Laravel / cookies
    });

    if (!res.ok) {
        const body = await res.text().catch(() => "");
        throw new Error(`GET /admin/graph/objects failed (${res.status}): ${body}`);
    }

    const data: unknown = await res.json();

    if (!Array.isArray(data)) {
        throw new Error("Unexpected response: expected an array");
    }

    // parsing/validation minimaliste
    return data
        .filter(
            (o): o is { id: string; name: string; type: string; url: string} =>
                typeof o === "object" &&
                o !== null &&
                "id" in o &&
                "name" in o  &&
                "url" in o
        )
        .map((o): BpmnElementDef => ({
            id: o.id,
            name: o.name,
            glyph: o.id[0],
            url: o.url
        })
    );
}

export async function fetchInformationObjects(): Promise<BpmnElementDef[]> {
    const res = await fetch("/admin/bpmn/information", {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin", // important si session Laravel / cookies
    });

    if (!res.ok) {
        const body = await res.text().catch(() => "");
        throw new Error(`GET /admin/graph/information failed (${res.status}): ${body}`);
    }

    const data: unknown = await res.json();

    if (!Array.isArray(data)) {
        throw new Error("Unexpected response: expected an array");
    }

    // parsing/validation minimaliste
    return data
        .filter(
            (o): o is { id: string; name: string; type: string; url: string} =>
                typeof o === "object" &&
                o !== null &&
                "id" in o &&
                "name" in o  &&
                "url" in o
        )
        .map((o): BpmnElementDef => ({
                id: o.id,
                name: o.name,
                glyph: o.id[0],
                url: o.url
            })
        );
}

export async function fetchActorObjects(): Promise<BpmnElementDef[]> {
    const res = await fetch("/admin/bpmn/actors", {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin", // important si session Laravel / cookies
    });

    if (!res.ok) {
        const body = await res.text().catch(() => "");
        throw new Error(`GET /admin/graph/actors failed (${res.status}): ${body}`);
    }

    const data: unknown = await res.json();

    if (!Array.isArray(data)) {
        throw new Error("Unexpected response: expected an array");
    }

    // parsing/validation minimaliste
    return data
        .filter(
            (o): o is { id: string; name: string; type: string; url: string} =>
                typeof o === "object" &&
                o !== null &&
                "id" in o &&
                "name" in o  &&
                "url" in o
        )
        .map((o): BpmnElementDef => ({
                id: o.id,
                name: o.name,
                glyph: o.id[0],
                url: o.url
            })
        );
}

export async function fetchProcessObjects(): Promise<BpmnElementDef[]> {
    const res = await fetch("/admin/bpmn/process", {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin", // important si session Laravel / cookies
    });

    if (!res.ok) {
        const body = await res.text().catch(() => "");
        throw new Error(`GET /admin/graph/process failed (${res.status}): ${body}`);
    }

    const data: unknown = await res.json();

    if (!Array.isArray(data)) {
        throw new Error("Unexpected response: expected an array");
    }

    // parsing/validation minimaliste
    return data
        .filter(
            (o): o is { id: string; name: string; type: string; url: string} =>
                typeof o === "object" &&
                o !== null &&
                "id" in o &&
                "name" in o  &&
                "url" in o
        )
        .map((o): BpmnElementDef => ({
                id: o.id,
                name: o.name,
                glyph: o.id[0],
                url: o.url
            })
        );
}

