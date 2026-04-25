{{--
    Partiel : panneau moteur de requêtes
    Fournit : toolbar (card-header), éditeur, zone de résultat, barre de statut (card-footer).
    La card englobante (et son éventuel card-header de titre) est fournie par le parent.

    Variables optionnelles :
      (aucune pour l'instant – extensible via $editorVisible si besoin)
--}}

{{-- ── Barre d'outils ─────────────────────────────────────────────── --}}
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

    <button class="btn btn-sm btn-outline-secondary" id="btn-toggle-editor"
            title="@lang('Afficher/masquer l\'éditeur')">
        <i class="fas fa-code"></i> @lang('Éditeur')
    </button>

    <button class="btn btn-sm btn-outline-secondary" id="btn-format"
            title="@lang('Formater la requête')">
        <i class="fas fa-indent"></i> @lang('Format')
    </button>

    <button class="btn btn-sm btn-primary" id="btn-run">
        <i class="fas fa-play"></i> @lang('Exécuter')
    </button>
</div>

{{-- ── Éditeur DSL ─────────────────────────────────────────────────── --}}
<div id="editor-panel" class="border-bottom d-none">
    <textarea id="query-editor"
              class="form-control font-monospace border-0 rounded-0"
              rows="8"
              style="font-size:0.85rem; resize:vertical;"
              spellcheck="false"
              placeholder="FROM logical-servers
WHERE environment = &quot;production&quot;
AND (operating_system LIKE &quot;%Linux%&quot; OR os LIKE &quot;%Windows%&quot;)
WITH applications
DEPTH 2
OUTPUT graph
LIMIT 100"></textarea>
    <div id="parse-error" class="d-none px-3 py-1 bg-danger text-white small"></div>
</div>

{{-- ── Zone de résultat ────────────────────────────────────────────── --}}
{{-- position:relative seul — pas de overflow:hidden qui bloque le scroll interne --}}
<div class="flex-grow-1 position-relative" style="min-height:0;">

    <div id="result-placeholder"
         class="d-flex align-items-center justify-content-center text-muted"
         style="position:absolute; inset:0;">
        <div class="text-center">
            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
            <p>@lang('Écrivez une requête et cliquez sur Exécuter.')</p>
        </div>
    </div>

    <div id="result-spinner"
         class="d-none align-items-center justify-content-center"
         style="position:absolute; inset:0; display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">@lang('Chargement...')</span>
        </div>
    </div>

    <div id="result-error"
         class="d-none p-3"
         style="position:absolute; inset:0; overflow:auto;">
        <div class="alert alert-danger mb-0" id="result-error-msg"></div>
    </div>

    <div id="result-list"
         class="d-none p-2"
         style="position:absolute; inset:0; overflow:auto;">
        <table id="result-datatable" class="table table-sm table-hover table-striped">
            <thead></thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="result-graph"
         class="d-none p-2 text-center"
         style="position:absolute; inset:0; overflow:auto;">
        <div id="graph-svg-container"></div>
    </div>

</div>

{{-- ── Barre de statut ─────────────────────────────────────────────── --}}
<div class="card-footer py-1 px-3 d-flex justify-content-between align-items-center flex-wrap gap-1">

    {{-- Gauche : téléchargement + sélection engine --}}
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <a href="#" id="btn-export-svg" class="d-none small text-decoration-none"
           title="@lang('Télécharger le graphe en SVG')">
            <i class="bi bi-download"></i>
        </a>

        {{-- Sélection du moteur Graphviz — visible uniquement en mode graphe --}}
        <div id="engine-panel" class="d-none d-flex align-items-center gap-2 small">
            <span class="text-muted">@lang('Rendu') :</span>
            @foreach(['dot', 'fdp', 'osage', 'circo'] as $eng)
                <label class="d-flex align-items-center gap-1 mb-0 user-select-none">
                    <input type="radio" name="graph-engine"
                           value="{{ $eng }}" id="engine-{{ $eng }}"
                           @if($eng === 'dot') checked @endif>
                    <span>{{ $eng }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Droite : statut + compteur --}}
    <div class="d-flex align-items-center gap-2">
        <small class="text-muted" id="status-msg">—</small>
        <small class="text-muted" id="status-count"></small>
    </div>

</div>