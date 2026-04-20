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
              placeholder="FROM LogicalServer
WHERE environment = &quot;production&quot;
AND (os LIKE &quot;%Linux%&quot; OR os LIKE &quot;%Windows%&quot;)
WITH applications
DEPTH 2
OUTPUT graph
LIMIT 100"></textarea>
    <div id="parse-error" class="d-none px-3 py-1 bg-danger text-white small"></div>
</div>

{{-- ── Zone de résultat ────────────────────────────────────────────── --}}
<div class="flex-grow-1 position-relative overflow-hidden">

    <div id="result-placeholder"
         class="d-flex align-items-center justify-content-center h-100 text-muted"
         style="min-height:200px;">
        <div class="text-center">
            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
            <p>@lang('Écrivez une requête et cliquez sur Exécuter.')</p>
        </div>
    </div>

    <div id="result-spinner"
         class="d-none d-flex align-items-center justify-content-center"
         style="min-height:200px;">
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

{{-- ── Barre de statut ─────────────────────────────────────────────── --}}
<div class="card-footer py-1 px-3 d-flex justify-content-between align-items-center">
    <small class="text-muted" id="status-msg">—</small>
    <div class="d-flex gap-2 align-items-center">
        <small class="text-muted" id="status-count"></small>
        <a href="#" id="btn-export-svg" class="d-none small text-decoration-none">
            <i class="fas fa-download"></i> SVG
        </a>
    </div>
</div>