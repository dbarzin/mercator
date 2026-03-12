@extends('layouts.admin')

@section('title')
Analyse de dépendances
@endsection

@section('content')

{{-- ─── Panneau de contrôle (sticky) ──────────────────────────────────────── --}}
<div class="graph-card-sticky">
    <div class="card mb-3">
        <div class="card-header">
            <i class="bi bi-diagram-3"></i>&nbsp;Analyse de dépendances
        </div>

        {{-- Les filtres (vue[], attr[]) sont transmis via l'URL pour être restaurés au rechargement --}}
        <form method="GET" action="{{ url()->current() }}" id="dependency-form">

            <div class="card-body">

                <table>
                    <tr>
                        {{-- Filtre types d'objets --}}
                        <td width="400">
                            <label for="filters">Types d'objets</label>
                            <select class="form-control select2" id="filters" name="vue[]" multiple>
                                <option value="1"  @selected(in_array('1',  request('vue', [])))>{{ trans("cruds.report.cartography.ecosystem") }}</option>
                                <option value="2"  @selected(in_array('2',  request('vue', [])))>{{ trans("cruds.report.cartography.information_system") }}</option>
                                <option value="3"  @selected(in_array('3',  request('vue', [])))>{{ trans("cruds.report.cartography.applications") }}</option>
                                <option value="4"  @selected(in_array('4',  request('vue', [])))>{{ trans("cruds.report.cartography.administration") }}</option>
                                <option value="5"  @selected(in_array('5',  request('vue', [])))>{{ trans("cruds.report.cartography.logical_infrastructure") }}</option>
                                <option value="9"  @selected(in_array('9',  request('vue', [])))>{{ trans("cruds.flux.title") }}</option>
                                <option value="6"  @selected(in_array('6',  request('vue', [])))>{{ trans("cruds.report.cartography.physical_infrastructure") }}</option>
                                <option value="7"  @selected(in_array('7',  request('vue', [])))>{{ trans("cruds.report.cartography.network_infrastructure") }}</option>
                                <option value="8"  @selected(in_array('8',  request('vue', [])))>{{ trans("cruds.physicalLink.title") }}</option>
                            </select>
                            <span class="help-block">{{ trans("cruds.report.explorer.filter_helper") }}</span>
                        </td>
                        <td width="10"></td>

                        {{-- Filtre attributs --}}
                        <td width="400">
                            <label for="attr-filter" >Attributs</label>
                            <select class="form-control select2" id="attr-filter" name="attr[]" multiple>
                                {{-- Options peuplées dynamiquement via AJAX, sélection restaurée en JS --}}
                            </select>
                            <span class="help-block">Filtrer par attribut</span>
                        </td>
                        <td width="10"></td>

                        {{-- Nœud de départ --}}
                        <td width="400">
                            <div class="form-group">
                                <label for="node" >Nœud de départ</label>
                                <select name="node" id="node" class="form-control select2"
                                        data-placeholder="Chargement…">
                                    <option value=""></option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td width="140" style="vertical-align: bottom;">
                                <button type="button" id="btn-reset" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise"></i>&nbsp;Recommencer
                                </button>
                        </td>
                        <td width="10"></td>
                        {{-- Profondeur --}}
                        <td width="90">
                            <label for="depth" class="form-label fw-semibold">Profondeur</label>
                            <select name="depth" id="depth" class="form-control">
                                @foreach([1, 2, 3, 4, 5] as $d)
                                    <option value="{{ $d }}" @selected((int) request('depth', 3) === $d)>{{ $d }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td width="10"></td>
                        <td colspan="4">
                            <label class="form-label fw-semibold">Direction</label>
                            <div class="btn-group w-100" role="group" aria-label="Direction">
                                <input type="radio" class="btn-check" name="direction"
                                       id="dir-up" value="up" autocomplete="off"
                                       @checked(request('direction') === 'up')>
                                <label class="btn btn-outline-primary" for="dir-up">↑&nbsp;Amont</label>

                                <input type="radio" class="btn-check" name="direction"
                                       id="dir-down" value="down" autocomplete="off"
                                       @checked(request('direction') === 'down')>
                                <label class="btn btn-outline-primary" for="dir-down">↓&nbsp;Aval</label>

                                <input type="radio" class="btn-check" name="direction"
                                       id="dir-both" value="both" autocomplete="off"
                                       @checked(request('direction', 'both') === 'both')>
                                <label class="btn btn-outline-primary" for="dir-both">↕&nbsp;Les deux</label>
                            </div>
                        </td>
                        <td width="10"></td>

                        <td width="10"></td>

                        {{-- Boutons --}}
                        <td width="220" style="vertical-align: bottom;">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-search"></i>&nbsp;Analyser
                                </button>
                        </td>
                    </tr>
                </table>

            </div>{{-- /.card-body --}}
        </form>
    </div>
</div>

{{-- ─── Zone graphe ─────────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-body p-0">

        {{-- Indicateur de chargement --}}
        <div id="loading-indicator" class="align-items-center justify-content-center text-muted"
             style="display: none; height: 600px;">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p class="mt-2 mb-0" id="loading-text">Chargement des données…</p>
            </div>
        </div>

        {{-- Message d'invitation (aucun nœud sélectionné) --}}
        <div id="graph-placeholder" class="align-items-center justify-content-center text-muted"
             style="display: none; height: 600px;">
            <div class="text-center">
                <i class="bi bi-diagram-3 fs-1"></i>
                <p class="mt-2 mb-0">
                    Sélectionnez un nœud de départ, une direction et une profondeur,
                    puis cliquez sur <strong>Analyser</strong>.
                </p>
            </div>
        </div>

        {{-- Conteneur d3-graphviz --}}
        <div id="graph-container" style="display: none; height: 600px; overflow: auto;">
            <div class="graphviz" id="graph" style="width: 100%; height: 100%;"></div>
        </div>
        <div id="graph-resize-handle"
             style="display: none; height: 8px; cursor: ns-resize; background: linear-gradient(to bottom, #dee2e6, #adb5bd); border-top: 1px solid #ced4da;"
             title="Glisser pour redimensionner"></div>

        {{-- Message : pas de résultat --}}
        <div id="graph-empty" class="p-4 text-center text-warning" style="display: none;">
            <i class="bi bi-exclamation-triangle fs-2"></i>
            <p class="mt-2 mb-0">Aucune dépendance trouvée pour ce nœud avec ces paramètres.</p>
        </div>

    </div>

    {{-- ─── Moteur de rendu + téléchargement SVG (sous le graphe) ──────────── --}}
    <div class="card-footer" id="graph-footer" style="display: none;">
        <form method="GET" action="{{ url()->current() }}" id="engine-form">
            {{-- Conserver tous les paramètres courants --}}
            <input type="hidden" name="node"      value="{{ request('node') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'both') }}">
            <input type="hidden" name="depth"     value="{{ request('depth', 3) }}">
            @foreach(request('vue', []) as $v)
                <input type="hidden" name="vue[]" value="{{ $v }}">
            @endforeach
            @foreach(request('attr', []) as $a)
                <input type="hidden" name="attr[]" value="{{ $a }}">
            @endforeach

            <div class="d-flex align-items-center gap-3">
                <a href="#" id="downloadSvg" title="Télécharger le SVG">
                    <i class="bi bi-download"></i>
                </a>
                <span>Rendu :</span>
                @php($engines = ['dot', 'fdp', 'osage', 'circo'])
                @foreach($engines as $eng)
                    <label class="mb-0">
                        <input type="radio" name="engine" value="{{ $eng }}"
                               @checked(request('engine', 'dot') === $eng)
                               onchange="this.form.submit();">
                        {{ $eng }}
                    </label>
                @endforeach
            </div>
        </form>
    </div>

</div>

@endsection


@section('scripts')
@vite(['resources/js/d3-viz.js'])

<script>
    // ─── Paramètres courants injectés par Laravel (URL → JS) ──────────────────
    const PARAMS = {
        node:      @json(request('node')),
        direction: @json(request('direction', 'both')),
        depth:     {{ (int) request('depth', 3) }},
        engine:    @json(request('engine', 'dot')),
        vue:       @json(request('vue', [])),   // tableau de strings ex. ["1","3"]
        attr:      @json(request('attr', [])),  // tableau de strings ex. ["sensible"]
    };

    /** Données brutes chargées depuis l'API */
    let allNodes = [];
    let allEdges = [];

    // ─── Utilitaires DOM ──────────────────────────────────────────────────────
    // Utilise setProperty pour éviter tout conflit avec d'éventuels !important
    const setDisplay = (id, value) =>
        document.getElementById(id).style.setProperty('display', value);
    const show     = id => setDisplay(id, 'block');
    const hide     = id => setDisplay(id, 'none');
    const showFlex = id => setDisplay(id, 'flex');

    // ─── Lecture des filtres actifs ───────────────────────────────────────────
    function getVueFilter()  { return $('#filters').val()    ?? []; }
    function getAttrFilter() { return $('#attr-filter').val() ?? []; }

    /**
     * Vérifie si un nœud correspond aux filtres actifs.
     *   - aucun filtre vue   → tous les types passent
     *   - aucun filtre attr  → tous les attributs passent
     *   - sinon : vue dans la liste ET possède au moins un des attributs
     */
    function nodeMatchesFilters(node, vueFilter, attrFilter) {
        if (vueFilter.length > 0 && !vueFilter.includes(String(node.vue))) {
            return false;
        }
        if (attrFilter.length > 0) {
            const nodeAttrs = (node.attributes ?? '').split(/\s+/).map(a => a.trim()).filter(Boolean);
            if (!attrFilter.some(a => nodeAttrs.includes(a))) return false;
        }
        return true;
    }

    // ─── Chargement des données depuis l'API ──────────────────────────────────
    async function loadGraphData() {
        hide('graph-placeholder');
        hide('graph-container');
        hide('graph-empty');
        hide('graph-footer');
        document.getElementById('graph-resize-handle').style.setProperty('display', 'none');
        showFlex('loading-indicator');

        try {
            document.getElementById('loading-text').textContent = 'Chargement des données…';

            const [graphResp, attrResp] = await Promise.all([
                fetch('{{ route("admin.reports.explore.data") }}', {
                    headers: {
                        'Accept':           'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    credentials: 'same-origin',
                }),
                fetch('{{ route("admin.reports.explore.attributes") }}', {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                }),
            ]);

            if (!graphResp.ok) throw new Error(`HTTP ${graphResp.status} — ${graphResp.statusText}`);

            const data = await graphResp.json();
            allNodes = data.nodes ?? [];
            allEdges = data.edges ?? [];

            // Peupler le sélecteur d'attributs puis restaurer la sélection depuis l'URL
            if (attrResp.ok) {
                const attributes = await attrResp.json();
                attributes.forEach(attr => {
                    const selected = PARAMS.attr.includes(attr);
                    $('#attr-filter').append(
                        `<option value="${attr}" ${selected ? 'selected' : ''}>${attr}</option>`
                    );
                });
                $('#attr-filter').trigger('change');
            }

            populateNodeSelector();

            if (PARAMS.node) {
                renderDependencyGraph();
            } else {
                showFlex('graph-placeholder');
            }

        } catch (err) {
            console.error('[dependency] Erreur chargement :', err);
            const ph = document.getElementById('graph-placeholder');
            ph.querySelector('.text-center').innerHTML =
                `<i class="bi bi-exclamation-circle text-danger fs-2"></i>
                 <p class="mt-2 text-danger">Erreur lors du chargement des données.<br>
                 <small>${err.message}</small></p>`;
            showFlex('graph-placeholder');
        } finally {
            hide('loading-indicator');
        }
    }

    // ─── Peupler le <select> de nœuds selon les filtres actifs ───────────────
    function populateNodeSelector() {
        const select      = document.getElementById('node');
        const vueFilter   = getVueFilter();
        const attrFilter  = getAttrFilter();

        // Garder la valeur courante pour la re-sélectionner après reconstruction
        const currentVal  = select.value || PARAMS.node;

        while (select.options.length > 1) select.remove(1);

        const sorted = [...allNodes]
            .filter(n => nodeMatchesFilters(n, vueFilter, attrFilter))
            .sort((a, b) => a.label.localeCompare(b.label, undefined, { sensitivity: 'base' }));

        for (const node of sorted) {
            const opt       = document.createElement('option');
            opt.value       = node.id;
            opt.textContent = node.label;           // nom seul, sans le type
            if (node.id === currentVal) opt.selected = true;
            select.appendChild(opt);
        }

        $('#node').trigger('change');
    }

    // ─── Calcul du sous-graphe par BFS avec filtres ───────────────────────────
    function computeSubgraph(startId, direction, depth) {
        const startNode = allNodes.find(n => n.id === startId);
        if (!startNode) return { nodes: [], edges: [] };

        const vueFilter  = getVueFilter();
        const attrFilter = getAttrFilter();
        const nodeById   = new Map(allNodes.map(n => [n.id, n]));

        // Adjacence
        const adj = new Map();
        for (const node of allNodes) adj.set(node.id, { up: new Set(), down: new Set() });
        for (const edge of allEdges) {
            if (!edge.from || !edge.to) continue;
            if (!adj.has(edge.from) || !adj.has(edge.to)) continue;
            adj.get(edge.from).down.add(edge.to);
            adj.get(edge.to).up.add(edge.from);
        }

        // BFS — le nœud de départ est toujours inclus quels que soient les filtres
        const visited  = new Set([startId]);
        let   frontier = [startId];

        for (let d = 0; d < depth; d++) {
            if (frontier.length === 0) break;
            const next = [];
            for (const nodeId of frontier) {
                const neighbors = adj.get(nodeId);
                if (!neighbors) continue;

                const candidates = new Set();
                if (direction === 'up'   || direction === 'both')
                    for (const n of neighbors.up)   candidates.add(n);
                if (direction === 'down' || direction === 'both')
                    for (const n of neighbors.down) candidates.add(n);

                for (const n of candidates) {
                    if (visited.has(n)) continue;
                    const candidate = nodeById.get(n);
                    if (candidate && !nodeMatchesFilters(candidate, vueFilter, attrFilter)) continue;
                    visited.add(n);
                    next.push(n);
                }
            }
            frontier = next;
        }

        return {
            nodes: allNodes.filter(n => visited.has(n.id)),
            edges: allEdges.filter(e => e.from && e.to && visited.has(e.from) && visited.has(e.to)),
        };
    }

    // ─── Construction de la source DOT ───────────────────────────────────────

    /**
     * Types qui n'ont pas de page show dans l'admin (pas de Route::resource show).
     * Pour ces types, aucun lien ne sera généré sur le nœud.
     */
    const TYPES_WITHOUT_SHOW = new Set([
        'relations',  // pas de show dédié
    ]);

    /**
     * Retourne l'URL /admin/{type}/{id} à partir du nœud.
     * Le champ `type` retourné par l'API est déjà le segment de route
     * (ex. "logical-servers", "application-blocks", "physical-security-devices").
     * L'identifiant numérique est extrait en supprimant le préfixe alphabétique
     * de l'id composite (ex. "LSRV42" → "42").
     */
    function nodeShowUrl(node) {
        if (TYPES_WITHOUT_SHOW.has(node.type)) return null;
        const numericId = node.id.replace(/^[^0-9]+/, '');
        if (!numericId) return null;
        return `/admin/${node.type}/${numericId}`;
    }

    function buildDotSource(nodes, edges, startId) {
        const esc = s => (s ?? '').replace(/\\/g, '\\\\').replace(/"/g, '\\"').replace(/\n/g, '\\n');
        const nodeIds = new Set(nodes.map(n => n.id));

        let dot  = 'digraph {\n';
        dot +=    '  graph [bgcolor="transparent" fontname="sans-serif"];\n';
        dot +=    '  node  [fontname="sans-serif" fontsize=10];\n';
        dot +=    '  edge  [fontname="sans-serif" fontsize=9];\n\n';

        // Nœud de départ en évidence
        const startNode  = nodes.find(n => n.id === startId);
        const startLabel = startNode?.label ?? startId;
        const startImage = startNode?.image ?? '';
        const startUrl   = startNode ? nodeShowUrl(startNode) : null;
        dot += '  subgraph cluster_start {\n';
        dot += '    style=dashed; color="#f0ad4e"; penwidth=2; label="";\n';
        dot += `    "${esc(startId)}" [label="${esc(startLabel)}" shape=none labelloc="b" width=1 height=1.1 image="${esc(startImage)}"`;
        if (startUrl) dot += ` URL="${esc(startUrl)}"`;
        dot += ']\n';
        dot += '  }\n\n';

        for (const node of nodes) {
            if (node.id === startId) continue;
            const url = nodeShowUrl(node);
            dot += `  "${esc(node.id)}" [label="${esc(node.label)}" shape=none labelloc="b" width=1 height=1.1 image="${esc(node.image)}"`;
            if (url) dot += ` URL="${esc(url)}"`;
            dot += ']\n';
        }
        dot += '\n';

        for (const edge of edges) {
            if (!nodeIds.has(edge.from) || !nodeIds.has(edge.to)) continue;

            const attrs = [];

            if (edge.type === 'CABLE') {
                // Lien physique : trait épais coloré, sans flèche (dir=none)
                attrs.push(`color="${edge.color ?? 'blue'}"`);
                attrs.push('penwidth=2');
                attrs.push('dir=none');
            } else if (edge.type === 'FLUX') {
                // Flux directionnel : flèche simple ou double selon bidirectional
                if (edge.name)          attrs.push(`label="${esc(edge.name)}"`);
                if (edge.bidirectional) attrs.push('dir=both');
                if (edge.color)         attrs.push(`color="${esc(edge.color)}"`);
            } else {
                // LINK et autres : flèche simple par défaut
                if (edge.name)          attrs.push(`label="${esc(edge.name)}"`);
                if (edge.bidirectional) attrs.push('dir=both');
                if (edge.color)         attrs.push(`color="${esc(edge.color)}"`);
            }

            dot += `  "${esc(edge.from)}" -> "${esc(edge.to)}"${attrs.length ? ' [' + attrs.join(' ') + ']' : ''}\n`;
        }
        dot += '}\n';
        return dot;
    }

    // ─── Rendu principal ──────────────────────────────────────────────────────
    function renderDependencyGraph() {
        hide('graph-placeholder');
        hide('graph-empty');

        if (!PARAMS.node) {
            showFlex('graph-placeholder');
            return;
        }

        const subgraph = computeSubgraph(PARAMS.node, PARAMS.direction, PARAMS.depth);

        if (subgraph.nodes.length === 0) {
            show('graph-empty');
            return;
        }

        const dotSrc      = buildDotSource(subgraph.nodes, subgraph.edges, PARAMS.node);
        const uniqueImages = [...new Set(subgraph.nodes.map(n => n.image).filter(Boolean))];

        let viz = d3.select('#graph').graphviz().engine(PARAMS.engine);
        for (const img of uniqueImages) viz = viz.addImage(img, '64px', '64px');
        viz.renderDot(dotSrc);

        show('graph-container');
        show('graph-footer');
        document.getElementById('graph-resize-handle').style.setProperty('display', 'block');
    }

    // ─── Téléchargement SVG ───────────────────────────────────────────────────
    document.getElementById('downloadSvg').addEventListener('click', function (e) {
        e.preventDefault();
        const svg = document.querySelector('#graph svg');
        if (!svg) { alert('Aucun graphe disponible à télécharger.'); return; }
        const blob = new Blob([svg.outerHTML], { type: 'image/svg+xml;charset=utf-8' });
        const a    = Object.assign(document.createElement('a'), {
            href:     URL.createObjectURL(blob),
            download: 'dependency-graph.svg',
        });
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(a.href);
    });

    // ─── Initialisation ───────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {

        // Forcer le SVG d3-graphviz à remplir son conteneur
        const style = document.createElement('style');
        style.textContent = '#graph svg { width: 100%; height: 100%; }';
        document.head.appendChild(style);

        // ── Select2 ──────────────────────────────────────────────────────────
        $('#filters').select2({ width: '100%', placeholder: 'Tous les types…' });
        $('#attr-filter').select2({ width: '100%', placeholder: 'Tous les attributs…' });
        $('#node').select2({ width: '100%', placeholder: 'Sélectionner un nœud…', allowClear: true });

        // Restaurer les filtres de type depuis l'URL (PARAMS.vue)
        if (PARAMS.vue.length > 0) {
            $('#filters').val(PARAMS.vue).trigger('change');
        }

        // ── Changement de filtre → mise à jour de la liste des nœuds ─────────
        $('#filters, #attr-filter')
            .on('select2:select select2:unselect', () => populateNodeSelector());

        // ── Bouton Recommencer ───────────────────────────────────────────────
        document.getElementById('btn-reset').addEventListener('click', () => {
            window.location.href = '{{ url()->current() }}';
        });

        // ── Chargement AJAX des données ──────────────────────────────────────
        loadGraphData();

        // ── Resize handle ────────────────────────────────────────────────────
        const handle    = document.getElementById('graph-resize-handle');
        const container = document.getElementById('graph-container');
        let dragging = false, startY = 0, startH = 0;

        handle.addEventListener('mousedown', e => {
            dragging = true; startY = e.clientY; startH = container.offsetHeight;
            document.body.style.cssText += 'cursor:ns-resize;user-select:none;';
            e.preventDefault();
        });
        document.addEventListener('mousemove', e => {
            if (!dragging) return;
            container.style.height = Math.max(200, startH + (e.clientY - startY)) + 'px';
        });
        document.addEventListener('mouseup', () => {
            if (!dragging) return;
            dragging = false;
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        });
    });
</script>

@parent
@endsection