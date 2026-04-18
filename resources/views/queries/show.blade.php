@extends('layouts.admin')

@section('title', __('Query Engine'))

@section('content')

<div class="card">
    <div class="card-header">
        @lang('Query Engine')
        @isset($query)
            — {{ $query->name }}
        @endisset
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

                    <div class="flex-grow-1"></div>

                    {{-- Toggle éditeur --}}
                    <button class="btn btn-sm btn-outline-secondary" id="btn-toggle-editor"
                            title="@lang('Afficher/masquer l\'éditeur')">
                        <i class="fas fa-code"></i> @lang('Éditeur')
                    </button>

                    {{-- Formater --}}
                    <button class="btn btn-sm btn-outline-secondary" id="btn-format"
                            title="@lang('Formater la requête')">
                        <i class="fas fa-indent"></i> @lang('Format')
                    </button>

                    {{-- Exécuter --}}
                    <button class="btn btn-sm btn-primary" id="btn-run">
                        <i class="fas fa-play"></i> @lang('Exécuter')
                    </button>
                </div>

                {{-- ── Éditeur SQL-like ─────────────────────────────── --}}
                <div id="editor-panel" class="border-bottom d-none">
                    <textarea id="query-editor"
                              class="form-control font-monospace border-0 rounded-0"
                              rows="8"
                              style="font-size: 0.85rem; resize: vertical;"
                              spellcheck="false"
                              placeholder="FROM LogicalServer
WHERE environment = &quot;production&quot;
AND (os LIKE &quot;%Linux%&quot; OR os LIKE &quot;%Windows%&quot;)
WITH applications
DEPTH 2
OUTPUT graph
LIMIT 100"
                    ></textarea>
                    {{-- Erreur de parsing --}}
                    <div id="parse-error" class="d-none px-3 py-1 bg-danger text-white small"></div>
                </div>

                {{-- ── Zone de résultat ────────────────────────────── --}}
                <div class="flex-grow-1 position-relative overflow-hidden">

                    <div id="result-placeholder" class="d-flex align-items-center justify-content-center h-100 text-muted" style="min-height:200px;">
                        <div class="text-center">
                            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                            <p>@lang('Écrivez une requête et cliquez sur Exécuter.')</p>
                        </div>
                    </div>

                    <div id="result-spinner" class="d-none d-flex align-items-center justify-content-center" style="min-height:200px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">@lang('Chargement...')</span>
                        </div>
                    </div>

                    <div id="result-error" class="d-none p-3">
                        <div class="alert alert-danger mb-0" id="result-error-msg"></div>
                    </div>

                    <div id="result-list" class="d-none overflow-auto p-2">
                        <table id="result-datatable" class="table table-sm table-hover table-striped w-100">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="result-graph" class="d-none overflow-auto text-center p-2">
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

{{-- ── Boutons d'action ─────────────────────────────────────────── --}}
<div class="form-group mt-2">
    <a class="btn btn-default" href="{{ route('admin.queries.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @isset($query)
        @if (auth()->id() === $query->user_id)
            <button class="btn btn-success" id="btn-save">
                <i class="fas fa-save"></i> {{ trans('global.save') }}
            </button>
        @endif
    @else
        <button class="btn btn-success" id="btn-save">
            <i class="fas fa-bookmark"></i> {{ trans('global.save') }}
        </button>
    @endisset
</div>

{{-- Formulaire PUT caché --}}
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
@vite(['resources/js/graphviz.js', 'resources/js/sql-parser.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
(function () {
    'use strict';

    const parseSql  = (sql) => window.MercatorQuery.parse(sql);
    const dslToSql  = (dsl) => window.MercatorQuery.dslToSql(dsl);

    const editor       = document.getElementById('query-editor');
    const parseError   = document.getElementById('parse-error');
    const btnRun       = document.getElementById('btn-run');
    const btnFormat    = document.getElementById('btn-format');
    const btnSave      = document.getElementById('btn-save');
    const btnToggle    = document.getElementById('btn-toggle-editor');
    const btnExportSvg = document.getElementById('btn-export-svg');
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
        'StorageDevice':      '/images/storage.png',
        'Workstation':        '/images/workstation.png',
        'Entity':             '/images/entity.png',
        'Process':            '/images/process.png',
        'Activity':           '/images/activity.png',
        'Actor':              '/images/actor.png',
    };
    function nodeImage(group) { return IMAGE_MAP[group] ?? '/images/object.png'; }

    // ── Parsing et validation ────────────────────────────────────

    function parseDsl() {
        const sql = editor.value.trim();
        if (!sql) throw new Error('Requête vide.');
        const dsl = parseSql(sql);
        const radio = document.querySelector('input[name="output"]:checked');
        if (radio) dsl.output = radio.value;
        return dsl;
    }

    function showParseError(msg) {
        parseError.textContent = msg;
        parseError.classList.remove('d-none');
        // Afficher l'éditeur si caché
        document.getElementById('editor-panel').classList.remove('d-none');
    }

    function clearParseError() {
        parseError.classList.add('d-none');
        parseError.textContent = '';
    }

    editor.addEventListener('input', clearParseError);

    // ── Mode sortie ─────────────────────────────────────────────
    document.querySelectorAll('input[name="output"]').forEach(radio => {
        radio.addEventListener('change', () => runQuery());
    });

    // ── Toggle éditeur ──────────────────────────────────────────
    btnToggle?.addEventListener('click', () => {
        document.getElementById('editor-panel').classList.toggle('d-none');
    });

    // ── Formater ────────────────────────────────────────────────
    btnFormat?.addEventListener('click', () => {
        try {
            const dsl       = parseSql(editor.value);
            editor.value    = dslToSql(dsl);
            clearParseError();
        } catch (e) {
            showParseError('Erreur : ' + e.message);
        }
    });

    // ── Sauvegarder ─────────────────────────────────────────────
    btnSave?.addEventListener('click', () => {
        try {
            const dsl = parseDsl();

            @isset($query)
                @if (auth()->id() === $query->user_id)
                document.getElementById('save-update-query-json').value = JSON.stringify(dsl);
                document.getElementById('form-save-update').submit();
                @endif
            @else
                const params = new URLSearchParams({ dsl: JSON.stringify(dsl) });
                window.location.href = '{!! route('admin.queries.create') !!}?' + params.toString();
            @endisset

        } catch (e) {
            showParseError('Erreur : ' + e.message);
        }
    });

    // ── Export SVG ──────────────────────────────────────────────
    btnExportSvg?.addEventListener('click', e => {
        e.preventDefault();
        if (!lastSvgContent) return;
        const a = Object.assign(document.createElement('a'), {
            href:     URL.createObjectURL(new Blob([lastSvgContent], { type: 'image/svg+xml' })),
            download: 'mercator-query.svg',
        });
        a.click();
    });

    // ── Exécuter ────────────────────────────────────────────────
    btnRun.addEventListener('click', runQuery);

    async function runQuery() {
        let dsl;
        try {
            dsl = parseDsl();
            clearParseError();
        } catch (e) {
            showParseError('Erreur de syntaxe : ' + e.message);
            return;
        }

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

        } catch (e) {
            showError(e.message);
            setStatus('Erreur');
        }
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

        datatableInstance = $('#result-datatable').DataTable({
            data:      rows,
            columns:   columns.map(c => ({
                title:          c,
                defaultContent: '',
                data:           null,
                render:         (data, type, row) => row[c] ?? '',
            })),
            order:     [[0, 'asc']],
            pageLength: 100,
            buttons: [
                { extend: 'colvis', className: 'btn-default' },
                { extend: 'copy',   className: 'btn-default' },
                { extend: 'csv',    className: 'btn-default' },
                { extend: 'excel',  className: 'btn-default' },
            ],
            layout:  { paging: true },
            scrollX: true,
        });

        datatableInstance
            .buttons(0, null)
            .container()
            .prependTo(datatableInstance.table().container());
    }

    // ── Rendu graphe ─────────────────────────────────────────────
    function renderGraph({ nodes, edges }) {
        if (!graphvizReady) {
            document.addEventListener('graphvizReady', () => renderGraph({ nodes, edges }), { once: true });
            return;
        }
        try {
            const svgStr = window.graphviz.layout(
                buildDot(nodes, edges), 'svg', 'dot', { images: buildImagesArray(nodes) }
            );
            lastSvgContent = svgStr;
            hideAll();
            document.getElementById('graph-svg-container').innerHTML = svgStr;
            document.getElementById('result-graph').classList.remove('d-none');
            btnExportSvg.classList.remove('d-none');
        } catch (e) {
            showError('Erreur Graphviz : ' + e.message);
        }
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
            lines.push(`  "${n.id}" [label="${esc(n.label??n.id)}" image="${nodeImage(n.group)}" tooltip="${esc(n.label??n.id)}" URL="${n.data?.url??''}"]`);
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
    function esc(s) { return String(s).replace(/\\/g,'\\\\').replace(/"/g,'\\"').replace(/\n/g,' '); }

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

    // ── Chargement initial ───────────────────────────────────────
    @isset($query)
    (function () {
        const dsl    = @json($query->query);
        const output = dsl.output ?? 'list';
        editor.value = dslToSql(dsl);
        const radio = document.getElementById('out-' + output);
        if (radio) radio.checked = true;
        runQuery();
    })();
    @endisset

})();
});
</script>
@parent
@endsection