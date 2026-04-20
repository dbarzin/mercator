@extends('layouts.admin')
@section('content')
    <form method="POST" action='{{ route("admin.bpmn.update", [$id]) }}' enctype="multipart/form-data" id="graph-form">
        @method('PUT')
        @csrf
        <input name='id' type='hidden' value='{{$id}}' id="id""/>
        <input name='class' type='hidden' value='2'/>
        <input name='content' type='hidden' value='' id="content"/>
        <div class="card" >
            <div class="card-header">
                BPMN
            </div>
            <div class="card-body p-0 ps-2" id="editor">
                <div class="row">
                    <div class="col-md-5"  style="border-left:3px solid #ddd; padding-left:68px;">
                        <div class="form-group">
                            <label class="label-required" for="name">{{ trans('bpmn::content.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', $name) }}" required
                                   maxlength="64"/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 ps-0">
                        <div class="form-group">
                            <label for="type">{{ trans('bpmn::content.fields.type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                    name="type" id="type">
                                @if (!$type_list->contains(old('type')))
                                    <option> {{ old('type') }}</option>
                                @endif
                                @foreach($type_list as $t)
                                    <option {{ (old('type') ? old('type') : $type) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="maximizeBtn"
                             style="
                          text-align: right;
                          display: flex;
                          justify-content: right;
                          font-size: 20px;
                          align-items: center;
                          width: 100%;
                          height: 40px;
                          border-radius: 8px;
                          cursor: pointer;
                          color: #7C123E">&#8613;
                        </div>
                    </div>
                </div>

                <div id="app-container" class="bpmn-app p-0">
                    <div id="sidebar" class="bpmn-sidebar p-1">

                    <i title="State" id="state-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE845;</i>
                    <i title="Task" id="task-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE821;</i>
                    <i title="Gateway" id="gateway-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE834;</i>
                    <i title="Data" id="data-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE84B;</i>
                    <i title="Lane" id="lane-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE85C;</i>
                    <i title="Activities" id="activities-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE861</i>
                    <i title="Annotation" id="annotation-btn" class="mapping-icon bpmn-icon" draggable="true">&#xE86B;</i>
                    <i title="Conversation" id="conversation-btn" class="mapping-icon bi bi-hexagon" draggable="true"></i>
                    <i id="zoom-in-btn" title="Zoom in" class="mapping-icon bi bi-zoom-in"></i>
                    <i id="zoom-out-btn" title="Zoom out" class="mapping-icon bi bi-zoom-out"></i>
                    ---
                    <u id="save-btn" title="Save" class="mapping-icon bi bi-floppy-fill"></u>
                    <label for="file-input" title="Import BPMN"
                           class="mapping-icon bi bi-box-arrow-in-down">
                        <input type="file" id="file-input" accept=".bpmn,.xml"/>
                    </label>
                    <i id="download-svg" title="Download SVG" class="mapping-icon bi bi-card-image"></i>
                    <i id="download-btn" title="Export BPMN" class="disabled mapping-icon bi bi-download"></i>

                </div>

                <div id="graph-container" tabindex="0"></div>
                <div class="status" id="status"></div>

                <div id="vertex-menu" class="vertex-menu hidden">

                    <button data-action="add-state" title="State">
                        <i class="bi bi-circle"></i>
                    </button>
                    <button data-action="add-task" title="Task">
                        <i class="bi bi-app"></i>
                    </button>
                    <button data-action="add-gateway" title="Condition">
                        <i class="bi bi-diamond"></i>
                    </button>
                    <button data-action="connect" title="Lier">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    <span data-action="menu-break" class="menu-break"></span>
                    <button data-action="config" title="Configuration">
                        <i class="bi bi-wrench-adjustable"></i>
                    </button>
                        <div class="menu-item menu-color">
                            <button data-action="color" title="Couleur" class="color-btn">
                                <i class="bi bi-palette-fill"></i>
                            </button>

  <!-- sous-menu palette -->
  <div class="color-palette hidden" data-role="color-palette" aria-hidden="true">
    <button type="button" class="color-swatch" data-color="#ffffff" style="background:#ffffff"></button>

    <!-- 11 pastels -->
    <!-- "#99cbed", "#dfe9f6", "#ffcc9f", "#ffe4c9" -->
    <button type="button" class="color-swatch" data-color="#99cbed" style="background:#99cbed"></button>
    <button type="button" class="color-swatch" data-color="#dfe9f6" style="background:#dfe9f6"></button>
    <button type="button" class="color-swatch" data-color="#ffcc9f" style="background:#ffcc9f"></button>
    <button type="button" class="color-swatch" data-color="#dfe9f6" style="background:#dfe9f6"></button>

    <!-- #9fe59f", "#d6f2d0", "#efa8a8", "#ffd6d5", -->
    <button type="button" class="color-swatch" data-color="#9fe59f" style="background:#9fe59f"></button>
    <button type="button" class="color-swatch" data-color="#d6f2d0" style="background:#d6f2d0"></button>
    <button type="button" class="color-swatch" data-color="#efa8a8" style="background:#efa8a8"></button>
    <button type="button" class="color-swatch" data-color="#ffd6d5" style="background:#ffd6d5"></button>

    <!-- "#d4c2e5", "#e8dfee", "#d3b9d6", "#e7d7d4", -->
    <button type="button" class="color-swatch" data-color="#d4c2e5" style="background:#d4c2e5"></button>
    <button type="button" class="color-swatch" data-color="#e8dfee" style="background:#e8dfee"></button>
    <button type="button" class="color-swatch" data-color="#d3b9d6" style="background:#d3b9d6"></button>
    <button type="button" class="color-swatch" data-color="#e7d7d4" style="background:#e7d7d4"></button>

    <!-- "#f4c9e7", "#fce2ed", "#cccccc", "#e9e9e9", -->
    <button type="button" class="color-swatch" data-color="#f4c9e7" style="background:#f4c9e7"></button>
    <button type="button" class="color-swatch" data-color="#fce2ed" style="background:#fce2ed"></button>
    <button type="button" class="color-swatch" data-color="#cccccc" style="background:#cccccc"></button>
    <button type="button" class="color-swatch" data-color="#e9e9e9" style="background:#e9e9e9"></button>

    <!-- "#eded9e", "#f1f1d1", "#9aecf4", "#d8f0f5" -->
    <button type="button" class="color-swatch" data-color="#eded9e" style="background:#eded9e"></button>
    <button type="button" class="color-swatch" data-color="#f1f1d1" style="background:#f1f1d1"></button>
    <button type="button" class="color-swatch" data-color="#9aecf4" style="background:#9aecf4"></button>
    <button type="button" class="color-swatch" data-color="#d8f0f5" style="background:#d8f0f5"></button>

  </div>
                   </div>
                    <button data-action="rotate" title="Rotate">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <button data-action="add-annotations" title="Supprimer">
                        <i class="mapping-icon bpmn-icon bi">&#xE86B;</i>
                    </button>
                    <button data-action="delete" title="Supprimer">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                    <span class="menu-break"></span>
                    <button data-action="search" title="Search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a id="btn-no-cancel" class="btn btn-default" href="{{ route('admin.bpmn.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button id="btn-save" class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection

@section('styles')
@vite('resources/css/mapping.css')

<style>

@font-face {
    font-family: "bpmn";
    src: url("/vendor/mercator-bpmn/fonts/bpmn.ttf") format("truetype");
    font-display: block;
}

.bpmn-icon {
    font-family: "bpmn";
    font-size: 20px;
    line-height: 1;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
}

#conversation-btn::before {
    transform: rotate(30deg);
}

/* Le card-body doit pouvoir étirer son contenu */
.card-body {
    display: flex;
    flex-direction: column;
    min-height: 0; /* critique pour que les enfants puissent scroller/étirer */
}

/* 2) Zone app (sidebar + graphe) prend tout l’espace dispo du card-body */
.bpmn-app {
    display: flex;
    flex: 1;
    min-height: 0;      /* critique */
    height: 100%;       /* si le parent le permet */
}

/* 3) Sidebar propre (au lieu du inline style) */
.bpmn-sidebar {
    width: 60px;
    background: #fff;
    border-right: 1px solid #ddd;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

/* 4) Graph : occupe tout, et on autorise le déplacement “feuille” via scroll si besoin */
#graph-container {
    flex: 1;
    min-width: 0;
    min-height: 0;
    height: 800px;
    position: relative;

    /* IMPORTANT : pour panning/feuille, préfère auto (pas hidden) */
    overflow: auto;

    background-color: #fff;

    /* Maillage léger (gris clair) */
    background-image:
        linear-gradient(to right, rgba(0,0,0,0.06) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.06) 1px, transparent 1px);
    background-size: 20px 20px; /* taille du maillage */
}

/* 5) Supprimer le ring/bord bleu quand le container a le focus */
#graph-container:focus,
#graph-container:focus-visible {
    outline: none !important;
    box-shadow: none !important;
}


.toolbar {
    background: white;
    padding: 12px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    gap: 10px;
    align-items: center;
}

button {
    padding: 8px 16px;
    background: #2196F3;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

button:hover {
    background: #1976D2;
}

button:active {
    background: #1565C0;
}

input[type="file"] {
    display: none;
}

.file-label {
    padding: 8px 16px;
    background: #4CAF50;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

.file-label:hover {
    background: #45a049;
}

.info {
    margin-left: auto;
    color: #666;
    font-size: 13px;
}


.status {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #333;
    color: white;
    padding: 12px 20px;
    border-radius: 4px;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 1000;
}

.status.show {
    opacity: 1;
}

/* Menu contextuel des vertex */

.vertex-menu {
    position: absolute;
    display: inline-flex;
    flex-wrap: wrap;      /* ← autorise le retour à la ligne */
    max-width: 105px;      /* largeur contrôlée (à ajuster) */
    align-items: center;
    gap: 4px;
    padding: 2px 4px;
    background: #ffffff;
    border: none;
    box-shadow: none;
    z-index: 1000;
}

.menu-break {
    flex-basis: 100%;
    height: 0;
}

.vertex-menu.hidden {
    display: none;
}

.vertex-menu button {
    background: #ffffff;
    border: none;
    padding: 2px;
    cursor: pointer;
}

.vertex-menu i.bi {
    font-size: 16px;      /* TAILLE DE L’ICÔNE */
    line-height: 1;
    color: #333;
    opacity: 0.75;
    transition: opacity 120ms ease, transform 120ms ease, color 120ms ease;
}

.vertex-menu button:hover i.bi {
    background: #eeeeee;
    opacity: 1;
    transform: scale(1.15);
}

.vertex-menu button:active i.bi {
    transform: scale(0.95);
}

/* Palette de couleurs */

/* le conteneur color est le point d’ancrage */
.menu-color {
    position: relative; /* 🔑 référence locale */
    display: inline-flex;
}

/* palette SOUS le bouton color */
.menu-color .color-palette {
    position: absolute;
    top: calc(100% + 6px);     /* ✅ juste en dessous du bouton */
    left: 50%;
    transform: translateX(-50%);

    z-index: 1001;
    margin: 0;

    padding: 4px;
    border-radius: 6px;
    background: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);

    display: grid;
    grid-template-columns: repeat(5, 22px);
    gap: 4px;
}

.color-swatch {
  width: 22px;
  height: 22px;
  border-radius: 5px;
  border: 1px solid #000;
  cursor: pointer;
  padding: 0;
  outline: 1px solid #000;
}

/* Hover discret, pas de zoom agressif */
.color-swatch:hover {
  outline: 1px solid #000;
  outline-offset: -2px;
}

/* Blanc : contraste garanti */
.color-swatch[data-color="#ffffff"] {
  background: #ffffff;
}

.hidden { display: none !important; }

</style>

@endsection

@section('scripts')

<script type="module" src="{{ asset('vendor/mercator-bpmn/js/bpmn.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    //--------------------------------------------------------------
    // Save graph
    //--------------------------------------------------------------
    const form = document.getElementById('graph-form');
    form.addEventListener('submit', function (event) {
        console.log("save called !");
        // Prevent the form from submitting immediately
        event.preventDefault();

        document.getElementById('content').value = getXMLGraph();

        // Now submit the form
        form.submit();
    });

    //--------------------------------------------------------------
    // Chargement du graphe
    //--------------------------------------------------------------
    @if (!empty($content))
        loadGraph(`{!! $content !!}`);
    @endif

    //--------------------------------------------------------------
    // Maximisation
    document.getElementById('maximizeBtn').addEventListener('click', function () {
        const div = document.getElementById('editor');
        const sidebar = document.getElementById('sidebar');
        const sidebarFooter = document.querySelector('.sidebar-footer');

        if (div.classList.contains('maximized')) {
            div.classList.remove('maximized');
            if (sidebar) sidebar.style.display = 'block';
            if (sidebarFooter) sidebarFooter.style.display = 'block';
            document.getElementById('maximizeBtn').innerHTML = "&#8613;";
        } else {
            div.classList.add('maximized');
            if (sidebar) sidebar.style.display = 'none';
            if (sidebarFooter) sidebarFooter.style.display = 'none';
            document.getElementById('maximizeBtn').innerHTML = "&#8615;";
        }
    });

});
</script>
@endsection
