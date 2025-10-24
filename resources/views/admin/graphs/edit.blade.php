@extends('layouts.admin')
@section('content')
    <form method="POST" action='{{ route("admin.graphs.update", [$id]) }}' enctype="multipart/form-data" id="grahForm">
        @method('PUT')
        @csrf
        <input name='id' type='hidden' value='{{$id}}' id="id"/>
        <input name='content' type='hidden' value='' id="content"/>
        <div class="card">
            <div class="card-header">
                Cartographier
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.graph.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', $name) }}" required autofocus
                                   maxlength="64"/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">{{ trans('cruds.graph.fields.type') }}</label>
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
                </div>
                <div class="row resizable-div" id="myDiv">
                    <div class="col-lg-12">
                        <table width="100%">
                            <tr>
                                <td width="400">
                                    <div class="form-group">
                                        <select class="form-control select2" id="filters" multiple>
                                            <option value="1">{{ trans("cruds.report.cartography.ecosystem") }}</option>
                                            <option value="2">{{ trans("cruds.report.cartography.information_system") }}</option>
                                            <option value="3">{{ trans("cruds.report.cartography.applications") }}</option>
                                            <option value="4">{{ trans("cruds.report.cartography.administration") }}</option>
                                            <option value="5">{{ trans("cruds.report.cartography.logical_infrastructure") }}</option>
                                            <option value="9">{{ trans("cruds.flux.title") }}</option>
                                            <option value="6">{{ trans("cruds.report.cartography.physical_infrastructure") }}</option>
                                            <option value="7">{{ trans("cruds.report.cartography.network_infrastructure") }}</option>
                                            <option value="8">{{ trans("cruds.physicalLink.title") }}</option>
                                        </select>
                                        <span class="help-block">{{ trans("cruds.report.explorer.filter_helper") }}</span>
                                    </div>
                                </td>
                                <td width=10>
                                </td>
                                <td width="400">
                                    <div class="form-group">
                                        <select class="form-control select2" id="node">
                                            <option></option>
                                            @foreach($nodes as $node)
                                                <option value="{{ $node['id'] }}">{{ $node["label"] }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">{{ trans("cruds.report.explorer.object_helper") }}</span>
                                    </div>
                                </td>
                                <td style="text-align: center; vertical-align: top; width=100px;">
                                    <div
                                            style="
                                      display: flex;
                                      justify-content: center;
                                      align-items: center;
                                      width: 50px;
                                      height: 40px;
                                      border: 0 solid #007bff;
                                      border-radius: 8px;">
                                        <img id="nodeImage" src=""
                                             style="width: 32px; cursor: grab;"/>
                                    </div>
                                </td>
                                <td style="vertical-align: top; ">
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
                                </td>
                            </tr>
                        </table>

                        <div id="app-container" style="display: flex;">
                            <div id="sidebar" style="
    width: 60px; /* élargi un peu pour les icônes plus grandes */
    background: #ffffff;
    border-right: 1px solid #ddd;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center; /* centre les icônes */
    gap: 12px; /* espace entre les icônes */
                                ">
                                <i id="saveButton" title="Save"
                                   class="mapping-icon bi bi-floppy-fill {{ $id === '-1' ? 'disabled' : ''}}"></i>
                                <i id="undoButton" title="Undo" class="mapping-icon bi bi-arrow-counterclockwise"></i>
                                <i id="redoButton" title="Redo" class="mapping-icon bi bi-arrow-clockwise"></i>
                                <i id="font-btn" title="Text" class="mapping-icon bi bi-fonts" draggable="true"></i>
                                <i id="square-btn" title="Border" class="mapping-icon bi bi-bounding-box"
                                   draggable="true"></i>
                                <i id="group-btn" title="Group" class="mapping-icon bi bi-plus-square-dotted"></i>
                                <i id="ungroup-btn" title="Ungroup" class="mapping-icon bi bi-dash-square-dotted"></i>
                                <i id="zoom-in-btn" title="Zoom in" class="mapping-icon bi bi-zoom-in"></i>
                                <i id="zoom-out-btn" title="Zoom out" class="mapping-icon bi bi-zoom-out"></i>
                                <i id="layout-btn" title="Layout" class="mapping-icon bi bi-magic"></i>
                                <i id="update-btn" title="Update" class="mapping-icon bi bi-lightning-fill"></i>
                                <i id="download-btn" title="Export" class="mapping-icon bi bi-download"></i>

                            </div>

                            <!-- Contextual menu for edges -->
                            <div id="edge-context-menu"
                                 style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; z-index: 1000; padding: 10px;">
                                <label for="edge-color-select"
                                       style="margin-right: 5px;">{{ trans('global.color') }}</label>
                                <input type="color" id="edge-color-select" name="favorite-color" value="#ff0000">
                                <label for="edge-thickness-select"
                                       style="margin-left: 5px; margin-right: 5px;">Border</label>
                                <select id="edge-thickness-select">
                                    <option value="1">1 px</option>
                                    <option value="2">2 px</option>
                                    <option value="3">3 px</option>
                                    <option value="4">4 px</option>
                                    <option value="5">5 px</option>
                                </select>
                                <br>
                                <button id="apply-edge-style">{{ trans('global.apply') }}</button>
                            </div>

                            <!-- Contextual menu for text -->
                            <div id="text-context-menu"
                                 style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; z-index: 1000; padding: 10px;">
                                <label for="text-font-select">Font </label>
                                <select id="text-font-select">
                                    <option>Arial</option>
                                    <option>Helvetica</option>
                                    <option>Times New Roman</option>
                                    <option>Courier New</option>
                                    <option>Verdana</option>
                                    <option>Georgia</option>
                                </select>
                                <label for="text-size-select">Size </label>
                                <select id="text-size-select">
                                    <option value="8">8 px</option>
                                    <option value="10">10 px</option>
                                    <option value="12">12 px</option>
                                    <option value="14">14 px</option>
                                    <option value="16">16 px</option>
                                    <option value="18">18 px</option>
                                    <option value="20">20 px</option>
                                    <option value="22">22 px</option>
                                    <option value="24">24 px</option>
                                    <option value="26">26 px</option>
                                </select>
                                <br>
                                <label for="text-color-select">{{ trans('global.color') }}</label>
                                <input type="color" id="text-color-select" name="favorite-color" value="#ff0000">

                                <button type="button" id="text-bold-select" class="button"
                                        style="margin-left: 20px; font-weight: bold;">B
                                </button>
                                <button type="button" id="text-italic-select" class="button"
                                        style="font-style: italic;">I
                                </button>
                                <button type="button" id="text-underline-select" class="button"
                                        style="text-decoration: underline;">U
                                </button>
                                <br>
                                <button id="apply-text-style">{{ trans('global.apply') }}</button>
                            </div>

                            <div id="graph-container"
                                 style="position: relative; overflow: hidden; width: 100%; height: 600px; cursor: default; touch-action: none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.graphs.index') }}">
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
        .button {
            width: 35px;
            cursor: pointer;
            border: 2px solid #ccc;
            background-color: #f0f0f0;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .button.selected {
            background-color: #aaa;
        }
    </style>
@endsection

@section('scripts')
    <script>
        // TODO : optimize me
        let _nodes = new Map();
        @foreach($nodes as $node)
        _nodes.set("{{ $node["id"] }}", {
            id: "{{ $node["id"]}}",
            vue: "{{ $node["vue"]}}",
            label: {!! json_encode($node["label"]) !!},
            {!! array_key_exists('title',$node) ? ('title: ' . json_encode($node["title"]) . ',') : "" !!} image: "{{ $node["image"] }}",
            type: "{{ $node["type"] }}",
            edges: [ <?php
                         foreach ($edges as $edge) {
                             if ($edge["from"] == $node["id"])
                                 echo '{attachedNodeId:"' . $edge["to"] . '"' . ($edge["name"] !== null ? ',name:' . json_encode($edge["name"]) : "") . ',edgeType:"' . $edge["type"] . '", edgeDirection: "TO", bidirectional:' . ($edge["bidirectional"] ? "true" : "false") . '},';
                             if ($edge["to"] == $node["id"])
                                 echo '{attachedNodeId:"' . $edge["from"] . '"' . ($edge["name"] !== null ? ',name:' . json_encode($edge["name"]) : "") . ',edgeType:"' . $edge["type"] . '", edgeDirection: "FROM", bidirectional:' . ($edge["bidirectional"] ? "true" : "false") . '},';
                         } ?> ]
        });
        @endforeach

        document.addEventListener("DOMContentLoaded", function () {

            function apply_filter() {
                // Get current filter
                cur_filter = $('#filters').val();

                // Get filter size
                if (cur_filter.length == 0) {
                    for (let [node, value] of _nodes)
                        $("#node").append('<option value="' + value.id + '">' + value.label + '</option>');
                } else {
                    // filter nodes
                    let activated = 0, disabled = 0;
                    $("#node").empty();
                    for (let [node, value] of _nodes) {
                        if (cur_filter.includes(value.vue)) {
                            $("#node").append('<option value="' + value.id + '">' + value.label + '</option>');
                            activated++;
                        } else
                            disabled++;
                    }
                }
                // clear node
                $('#node').val(null).trigger("change");
                // clear image
                document.getElementById('nodeImage').src = '';
            }

            // clear selections
            $('#filters').val(null).trigger('change');
            $('#node').val(null);

            $('#filters')
                .on('select2:select', function (e) {
                    apply_filter();
                });

            $('#filters')
                .on('select2:unselect', function (e) {
                    apply_filter();
                });

            $('#node')
                .on('select2:select', function (e) {
                    // Get current filter
                    cur_node = $('#node').val();
                    // console.log(cur_node);
                    document.getElementById('nodeImage').src = _nodes.get(cur_node).image;
                });

            $('#node')
                .on('select2:unselect', function (e) {
                    document.getElementById('nodeImage').src = '';
                });

            //--------------------------------------------------------------
            // Maximisation
            document.getElementById('maximizeBtn').addEventListener('click', function () {
                const div = document.getElementById('myDiv');
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
            //--------------------------------------------------------------
            // Chargement du graphe
            loadGraph(`{!! $content !!}`);

            //--------------------------------------------------------------
            // Save graph
            form = document.getElementById('grahForm');
            form.addEventListener('submit', function (event) {
                console.log("save called !");
                // Prevent the form from submitting immediately
                event.preventDefault();

                document.getElementById('content').value = getXMLGraph();

                // Now submit the form
                form.submit();
            });

        });
    </script>

    @vite('resources/js/map.edit.ts')

@endsection
