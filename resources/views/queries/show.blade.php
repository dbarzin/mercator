@extends('layouts.admin')

@section('title', __('Query Engine'))

@section('content')

<div class="card">
    <div class="card-header">
        @lang('Query Engine') — {{ $query->name }}
    </div>
    <div class="card-body">

        <div class="col-12">
            <div class="card h-100 d-flex flex-column">

                {{-- ── Barre d'outils ──────────────────────────────── --}}
                <div class="card-header py-2 d-flex gap-2 align-items-center flex-wrap">

                    {{-- Mode sortie --}}
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="output" id="out-list"  value="list"  autocomplete="off" checked>
                        <label class="btn btn-outline-secondary" for="out-list">
                            <i class="fas fa-list"></i> @lang('Liste')
                        </label>
                        <input type="radio" class="btn-check" name="output" id="out-graph" value="graph" autocomplete="off">
                        <label class="btn btn-outline-info" for="out-graph">
                            <i class="fas fa-project-diagram"></i> @lang('Graphe')
                        </label>
                    </div>

                    {{-- Profondeur --}}
                    <div id="depth-control" class="d-none d-flex align-items-center gap-1">
                        <small class="text-muted">@lang('Profondeur')</small>
                        <select id="depth-select" class="form-select form-select-sm" style="width:60px;">
                            <option value="1">1</option>
                            <option value="2" selected>2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="flex-grow-1"></div>

                    {{-- Toggle éditeur --}}
                    <button class="btn btn-sm btn-outline-secondary" id="btn-toggle-editor"
                            title="@lang('Afficher/masquer l\'éditeur JSON')">
                        <i class="fas fa-code"></i>
                        Editeur
                    </button>

                    {{-- Formater --}}
                    <button class="btn btn-sm btn-outline-secondary" id="btn-format"
                            title="@lang('Formater le JSON')">
                        <i class="fas fa-indent"></i>
                        Format
                    </button>

                    {{-- Sauvegarder --}}
                    @isset($query)
                        @if (auth()->id() === $query->user_id)
                            <button class="btn btn-sm btn-outline-primary" id="btn-save">
                                <i class="fas fa-save"></i> @lang('global.save')
                            </button>
                        @endif
                    @else
                        <button class="btn btn-sm btn-outline-primary" id="btn-save">
                            <i class="fas fa-bookmark"></i> @lang('global.save')
                        </button>
                    @endisset

                    {{-- Exécuter --}}
                    <button class="btn btn-sm btn-primary" id="btn-run">
                        <i class="fas fa-play"></i> @lang('Exécuter')
                    </button>
                </div>

                {{-- ── Éditeur JSON ─────────────────────────────────── --}}
                <div id="editor-panel" class="border-bottom">
                    <textarea id="query-editor"
                              class="form-control font-monospace border-0 rounded-0"
                              rows="6"
                              style="font-size: 0.82rem; resize: vertical;"
                              spellcheck="false"
                              placeholder='{"from": "LogicalServer", "filters": [], "traverse": ["applications"], "depth": 2, "output": "list"}'
                    >@isset($query){{ json_encode($query->query, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}@endisset</textarea>
                </div>

                {{-- ── Zone de résultat ────────────────────────────── --}}
                <div class="flex-grow-1 position-relative overflow-hidden">

                    <div id="result-placeholder" class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <div class="text-center">
                            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                            <p>@lang('Écrivez un DSL et cliquez sur Exécuter.')</p>
                        </div>
                    </div>

                    <div id="result-spinner" class="d-none d-flex align-items-center justify-content-center h-100">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">@lang('Chargement...')</span>
                        </div>
                    </div>

                    <div id="result-error" class="d-none p-3">
                        <div class="alert alert-danger mb-0" id="result-error-msg"></div>
                    </div>

                    <div id="result-list" class="d-none h-100 overflow-auto p-2">
                        <table id="result-datatable" class="table table-sm table-hover table-striped w-100">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="result-graph" class="d-none h-100 overflow-auto text-center p-2">
                        <div id="graph-svg-container"></div>
                    </div>

                </div>

                {{-- ── Barre de statut ──────────────────────────────── --}}
                <div class="card-footer py-1 px-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="status-msg">—</small>
                    <div class="d-flex gap-2 align-items-center">
                        <small class="text-muted" id="status-count"></small>
                        <a href="#" id="btn-export-svg" class="d-none small text-decoration-none">
                            <i class="fas fa-download"></i> SVG
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Formulaire PUT caché pour la sauvegarde de la requête courante --}}
@isset($query)
    @if (auth()->id() === $query->user_id)
        <form id="form-save-update"
              action="{!! route('admin.queries.update', $query) !!}"
              method="POST" class="d-none">
            @csrf
            @method('PUT')
            <input type="hidden" name="name"        value="{{ $query->name }}">
            <input type="hidden" name="description" value="{{ $query->description }}">
            <input type="hidden" name="is_public"   value="{{ $query->is_public ? '1' : '0' }}">
            <input type="hidden" name="query_json"  id="save-update-query-json">
        </form>
    @endif
@endisset
@endsection

@section('scripts')
@vite(['resources/js/graphviz.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
(function () {
    'use strict';

    const editor       = document.getElementById('query-editor');
    const btnRun       = document.getElementById('btn-run');
    const btnFormat    = document.getElementById('btn-format');
    const btnSave      = document.getElementById('btn-save');
    const btnToggle    = document.getElementById('btn-toggle-editor');
    const btnExportSvg = document.getElementById('btn-export-svg');
    const depthControl = document.getElementById('depth-control');
    const depthSelect  = document.getElementById('depth-select');
    const statusMsg    = document.getElementById('status-msg');
    const statusCount  = document.getElementById('status-count');

    let datatableInstance = null;
    let lastSvgContent    = '';
    let graphvizReady     = false;

    document.addEventListener('graphvizReady', () => { graphvizReady = true; });

    // ── Images par type d'objet ──────────────────────────────────
    const IMAGE_MAP = {
        'Application':        '/images/application.png',
        'ApplicationModule':  '/images/application.png',
        'ApplicationService': '/images/service.png',
        'Database':           '/images/database.png',
        'Flux':               '/images/flux.png',
        'LogicalServer':      '/images/server.png',
        'Certificate':        '/images/certificate.png',
        'Vlan':               '/images/vlan.png',
        'Network':            '/images/network.png',
        'NetworkSwitch':      '/images/switch.png',
        'Router':             '/images/router.png',
        'Firewall':           '/images/firewall.png',
        'PhysicalServer':     '/images/physical_server.png',
        'Site':               '/images/site.png',
        'Building':           '/images/building.png',
        'Bay':                '/images/bay.png',
        'PhysicalSwitch':     '/images/switch.png',
        'PhysicalRouter':     '/images/router.png',
        'PhysicalFirewall':   '/images/firewall.png',
        'StorageDevice':      '/images/storage.png',
        'Workstation':        '/images/workstation.png',
        'Entity':             '/images/entity.png',
        'Process':            '/images/process.png',
        'Activity':           '/images/activity.png',
        'Actor':              '/images/actor.png',
    };
    function nodeImage(group) { return IMAGE_MAP[group] ?? '/images/object.png'; }

    // ── Mode sortie ─────────────────────────────────────────────
    document.querySelectorAll('input[name="output"]').forEach(radio => {
        radio.addEventListener('change', () => {
            depthControl.classList.toggle('d-none', radio.value !== 'graph');
            syncOutputToEditor();
            runQuery();
        });
    });

    function syncOutputToEditor() {
        try {
            const dsl  = parseDsl();
            dsl.output = document.querySelector('input[name="output"]:checked')?.value ?? 'list';
            dsl.depth  = parseInt(depthSelect.value);
            editor.value = JSON.stringify(dsl, null, 2);
        } catch (_) {}
    }
    depthSelect.addEventListener('change', syncOutputToEditor);

    // ── Toggle éditeur ──────────────────────────────────────────
    btnToggle?.addEventListener('click', () => {
        document.getElementById('editor-panel').classList.toggle('d-none');
    });

    // ── Formater ────────────────────────────────────────────────
    btnFormat?.addEventListener('click', () => {
        try { editor.value = JSON.stringify(JSON.parse(editor.value), null, 2); }
        catch (e) { alert('JSON invalide : ' + e.message); }
    });

    // ── Sauvegarder ─────────────────────────────────────────────
    btnSave?.addEventListener('click', () => {
        try {
            const dsl = parseDsl();

            @isset($query)
                @if (auth()->id() === $query->user_id)
                // Requête existante dont on est propriétaire : PUT silencieux
                document.getElementById('save-update-query-json').value = JSON.stringify(dsl);
                document.getElementById('form-save-update').submit();
                @endif
            @else
                // Pas de requête chargée : redirection vers le formulaire de création
                const params = new URLSearchParams({ dsl: JSON.stringify(dsl) });
                window.location.href = '{!! route('admin.queries.create') !!}?' + params.toString();
            @endisset

        } catch (e) { alert('JSON invalide : ' + e.message); }
    });

    // ── Export SVG ──────────────────────────────────────────────
    btnExportSvg?.addEventListener('click', e => {
        e.preventDefault();
        if (!lastSvgContent) return;
        const a = Object.assign(document.createElement('a'), {
            href: URL.createObjectURL(new Blob([lastSvgContent], { type: 'image/svg+xml' })),
            download: 'mercator-query.svg',
        });
        a.click();
    });

    // ── Exécuter ────────────────────────────────────────────────
    btnRun.addEventListener('click', runQuery);

    async function runQuery() {
        let dsl;
        try { dsl = parseDsl(); }
        catch (e) { showError('JSON invalide : ' + e.message); return; }

        showSpinner();
        setStatus('Exécution en cours…');

        try {
            const res = await fetch('{!! route('admin.queries.execute') !!}', {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept':       'application/json',
                },
                body: JSON.stringify(dsl),
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({ message: res.statusText }));
                throw new Error(err.message ?? JSON.stringify(err));
            }

            const data = await res.json();
            dsl.output === 'graph' ? renderGraph(data) : renderList(data);
            setStatus('Terminé');
            setCount(data.meta?.count ?? 0, dsl.output);

        } catch (e) { showError(e.message); setStatus('Erreur'); }
    }

    // ── Rendu liste ─────────────────────────────────────────────
    function renderList({ columns, rows }) {
        hideAll();
        document.getElementById('result-list').classList.remove('d-none');
        btnExportSvg.classList.add('d-none');

        if (datatableInstance) { datatableInstance.destroy(); datatableInstance = null; }

        const table = document.getElementById('result-datatable');
        table.querySelector('thead').innerHTML =
            '<tr>' + columns.map(c => `<th>${c}</th>`).join('') + '</tr>';
        table.querySelector('tbody').innerHTML = '';

        datatableInstance = new DataTable(table, {
            data:     rows,
            columns:  columns.map(c => ({ data: c, defaultContent: '' })),
            language: window.datatables_lang ?? {},
            dom:      'Bfrtip',
            buttons:  ['copy', 'csv', 'excel'],
            scrollX:  true,
        });
    }

    // ── Rendu graphe ─────────────────────────────────────────────
    function renderGraph({ nodes, edges }) {
        if (!graphvizReady) {
            document.addEventListener('graphvizReady', () => renderGraph({ nodes, edges }), { once: true });
            return;
        }
        try {
            const svgStr = window.graphviz.layout(buildDot(nodes, edges), 'svg', 'dot', { images: buildImagesArray(nodes) });
            lastSvgContent = svgStr;
            hideAll();
            document.getElementById('graph-svg-container').innerHTML = svgStr;
            document.getElementById('result-graph').classList.remove('d-none');
            btnExportSvg.classList.remove('d-none');
        } catch (e) { showError('Erreur Graphviz : ' + e.message); }
    }

    // ── DOT ──────────────────────────────────────────────────────
    function buildDot(nodes, edges) {
        const lines = [
            'digraph G {',
            '  graph [rankdir=LR bgcolor="transparent" fontname="Helvetica"]',
            '  node  [shape=none fontname="Helvetica" fontsize=11 labelloc=b width=1 height=1.1]',
            '  edge  [color="#666666" arrowsize=0.7]', '',
        ];
        for (const n of nodes)
            lines.push(`  "${n.id}" [label="${escapeDot(n.label??n.id)}" image="${nodeImage(n.group)}" tooltip="${escapeDot(n.label??n.id)}" URL="${n.data?.url??''}"]`);
        lines.push('');
        for (const e of edges) lines.push(`  "${e.from}" -> "${e.to}"`);
        lines.push('}');
        return lines.join('\n');
    }

    function buildImagesArray(nodes) {
        const seen = new Set(), images = [];
        for (const n of nodes) {
            const p = nodeImage(n.group);
            if (!seen.has(p)) { seen.add(p); images.push({ path: p, width: '64px', height: '64px' }); }
        }
        return images;
    }

    // ── Helpers ──────────────────────────────────────────────────
    function parseDsl() {
        const dsl = JSON.parse(editor.value);
        const r   = document.querySelector('input[name="output"]:checked');
        if (r) dsl.output = r.value;
        dsl.depth = parseInt(depthSelect.value) || 2;
        return dsl;
    }
    function escapeDot(s) { return String(s).replace(/\\/g,'\\\\').replace(/"/g,'\\"').replace(/\n/g,' '); }
    function showSpinner() { hideAll(); document.getElementById('result-spinner').classList.remove('d-none'); }
    function showError(msg) {
        hideAll();
        document.getElementById('result-error').classList.remove('d-none');
        document.getElementById('result-error-msg').textContent = msg;
    }
    function hideAll() {
        ['result-placeholder','result-spinner','result-error','result-list','result-graph']
            .forEach(id => document.getElementById(id).classList.add('d-none'));
    }
    function setStatus(msg) { statusMsg.textContent = msg; }
    function setCount(n, output) {
        statusCount.textContent = `${n} ${output === 'graph' ? 'nœud(s)' : 'ligne(s)'}`;
    }

    // ── Pré-sélection au chargement ─────────────────────────────
    @isset($query)
    (function () {
        const output = @json($query->query['output'] ?? 'list');
        const radio  = document.getElementById('out-' + output);
        if (radio) radio.checked = true;
        depthControl.classList.toggle('d-none', output !== 'graph');
        depthSelect.value = {!! (int)($query->query['depth'] ?? 2) !!};
        runQuery();
    })();
    @endisset

})();
});
</script>
@parent
@endsection