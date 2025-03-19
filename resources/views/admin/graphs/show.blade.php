@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.graphs.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    @can('graph_edit')
        <a class="btn btn-info" href="{{ route('admin.graphs.edit', $graph->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('graph_create')
        <a class="btn btn-warning" href="{{ route('admin.graphs.clone', $graph->id) }}">
            {{ trans('global.clone') }}
        </a>
    @endcan

    @can('graph_delete')
        <form action="{{ route('admin.graphs.destroy', $graph->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        Cartographier
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <b>{{ $graph->name }}</b>
                </div>
            </div>
        </div>
                <div class="row resizable-div" id="myDiv">
                    <div class="col-lg-12">
                        <div id="graph-container" style="position: relative; overflow: hidden; width: 100%; height: 600px; cursor: default; touch-action: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.graphs.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection

@section('styles')
@vite('resources/css/mapping.css')
@endsection

@section('scripts')
<script>
// TODO : optimize me
let _nodes = new Map();
@foreach($nodes as $node)
    _nodes.set( "{{ $node["id"] }}" ,{ type: "{{ $node["type"] }}"});
@endforeach

document.addEventListener("DOMContentLoaded", function () {
    const xmlContent = `{!! $graph->content !!}`;
    loadGraph(xmlContent);
});
</script>
@vite('resources/js/map.show.ts')
@endsection
