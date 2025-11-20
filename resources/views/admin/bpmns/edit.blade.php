@extends('layouts.admin')
@section('content')
    <form method="POST" action='{{ route("admin.bpmns.update", [$id]) }}' enctype="multipart/form-data" id="grahForm">
        @method('PUT')
        @csrf
        <input name='id' type='hidden' value='{{$id}}' id="id"/>
        <input name='content' type='hidden' value='' id="content"/>
        <div class="card">
            <div class="card-header">
                BPMN
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="label-required" for="name">{{ trans('cruds.bpmns.fields.name') }}</label>
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
                            <label for="type">{{ trans('cruds.bpmns.fields.type') }}</label>
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

                <div id="app-container" style="display: flex;">
                    <div id="sidebar" style="
    width: 60px; /* élargi un peu pour les icônes plus grandes */
    background: #ffffff;
    border-right: 1px solid #ddd;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center; /* centre les icônes */
    gap: 12px; /* espace entre les icônes */"/>
                    <label for="file-input" title="Import"
                           class="mapping-icon bi bi-box-arrow-down">
                        <input type="file" id="file-input" accept=".bpmn,.xml"/>
                    </label>
                    <i id="undoButton" title="Undo" class="mapping-icon bi bi-arrow-counterclockwise"></i>
                    <i id="redoButton" title="Redo" class="mapping-icon bi bi-arrow-clockwise"></i>
                    <i id="font-btn" title="Text" class="mapping-icon bi bi-fonts" draggable="true"></i>
                    <i id="square-btn" title="Border" class="mapping-icon bi bi-bounding-box"
                       draggable="true"></i>
                    <i id="group-btn" title="Group" class="mapping-icon bi bi-plus-square-dotted"></i>
                    <i id="ungroup-btn" title="Ungroup" class="mapping-icon bi bi-dash-square-dotted"></i>
                    <i id="zoom-in-btn" title="Zoom in" class="mapping-icon bi bi-zoom-in"></i>
                    <i id="zoom-out-btn" title="Zoom out" class="mapping-icon bi bi-zoom-out"></i>
                    <i id="fit-in-btn" title="Fit" class="mapping-icon bi bi-magic"></i>
                    <i id="update-btn" title="Update" class="mapping-icon bi bi-lightning-fill"></i>
                    <i id="download-btn" title="Export" class="mapping-icon bi bi-download"></i>

                </div>

                <div id="graph-container"></div>
                <div class="status" id="status"></div>
            </div>
        </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.bpmns.index') }}">
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
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

        #graph-container {
            flex: 1;
            background: white;
            position: relative;
            overflow: hidden;
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
    </style>

@endsection

@section('scripts')

    @vite('resources/js/bpmn.edit.ts')

@endsection
