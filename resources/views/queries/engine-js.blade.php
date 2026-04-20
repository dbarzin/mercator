{{--
    Partiel JS : moteur de requêtes
    À inclure à l'intérieur d'un @section('scripts').

    Variables :
      $queryDsl     array|null   – DSL à charger + exécuter au démarrage (null = aucun)
      $saveFormId   string|null  – id du <form> PUT de mise à jour (ex: 'form-save-update')
      $createRoute  string|null  – URL de création (redirect avec DSL en paramètre)
--}}
@php
    $queryDsl    = $queryDsl    ?? null;
    $saveFormId  = $saveFormId  ?? null;
    $createRoute = $createRoute ?? null;
@endphp
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

    // ── Parsing / validation ─────────────────────────────────────
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
        document.getElementById('editor-panel').classList.remove('d-none');
    }

    function clearParseError() {
        parseError.textContent = '';
        parseError.classList.add('d-none');
    }

    editor.addEventListener('input', clearParseError);

    // ── Mode sortie ──────────────────────────────────────────────
    document.querySelectorAll('input[name="output"]').forEach(r => {
        r.addEventListener('change', () => runQuery());
    });

    // ── Toggle éditeur ───────────────────────────────────────────
    btnToggle?.addEventListener('click', () => {
        document.getElementById('editor-panel').classList.toggle('d-none');
    });

    // ── Formater ────────────────────────────────────────────────
    btnFormat?.addEventListener('click', () => {
        try {
            const dsl    = parseSql(editor.value);
            editor.value = dslToSql(dsl);
            clearParseError();
        } catch (e) {
            showParseError('Erreur : ' + e.message);
        }
    });

    // ── Sauvegarder ─────────────────────────────────────────────
    @if($saveFormId || $createRoute)
    btnSave?.addEventListener('click', () => {
        try {
            const dsl = parseDsl();
            @if($saveFormId)
            document.getElementById('{{ $saveFormId }}-query-json').value = JSON.stringify(dsl);
            document.getElementById('{{ $saveFormId }}').submit();
            @elseif($createRoute)
            const params = new URLSearchParams({ dsl: JSON.stringify(dsl) });
            window.location.href = '{!! $createRoute !!}?' + params.toString();
            @endif
        } catch (e) {
            showParseError('Erreur : ' + e.message);
        }
    });
    @endif

    // ── Export SVG ───────────────────────────────────────────────
    btnExportSvg?.addEventListener('click', e => {
        e.preventDefault();
        if (!lastSvgContent) return;
        const a = Object.assign(document.createElement('a'), {
            href:     URL.createObjectURL(new Blob([lastSvgContent], { type: 'image/svg+xml' })),
            download: 'mercator-query.svg',
        });
        a.click();
    });

    // ── Exécuter ─────────────────────────────────────────────────
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

    // ── Rendu liste ──────────────────────────────────────────────
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
            data:    rows,
            columns: columns.map(c => ({
                title:          c,
                defaultContent: '',
                data:           null,
                render:         (data, type, row) => row[c] ?? '',
            })),
            order:      [[0, 'asc']],
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

        datatableInstance.buttons(0, null).container()
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
            '  graph [bgcolor="transparent" fontname="Helvetica"]',
            '  node  [shape=none fontname="Helvetica" fontsize=11 labelloc=b width=1 height=1.1]',
            '  edge  [color="#666666" arrowsize=0.7]', '',
        ];
        for (const n of nodes)
            lines.push(`  "${n.id}" [label="${esc(n.label??n.id)}" image="${n.icon}" tooltip="${esc(n.label??n.id)}" URL="${n.data?.url??''}"]`);
        lines.push('');
        for (const e of edges) lines.push(`  "${e.from}" -> "${e.to}"`);
        lines.push('}');
        return lines.join('\n');
    }

    function buildImagesArray(nodes) {
        const seen = new Set(), images = [];
        for (const n of nodes) {
            const icon = n.icon;
            if (!seen.has(icon)) { seen.add(icon); images.push({ path: icon, width: '64px', height: '64px' }); }
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

    // ── API publique (utilisée par l'index pour charger depuis la liste) ──
    window.QueryEngine = {
        load(dsl) {
            const output = dsl.output ?? 'list';
            editor.value = dslToSql(dsl);
            clearParseError();
            const radio = document.getElementById('out-' + output);
            if (radio) radio.checked = true;
            runQuery();
        }
    };

    // ── Chargement initial (contexte show) ───────────────────────
    @if($queryDsl)
    window.QueryEngine.load(@json($queryDsl));
    @endif

})();
});
</script>