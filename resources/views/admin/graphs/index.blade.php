@extends('layouts.admin')
@section('content')
@can('graph_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.graphs.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.graph.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.graph.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th width="80%">
                            {{ trans('cruds.graph.fields.name') }}
                        </th>
                        <th width="20%">
                            {{ trans('cruds.graph.fields.type') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graphs as $graph)
                        <tr data-entry-id="{{ $graph->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.graphs.show', $graph->id) }}">
                                    {{ $graph->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $graph->type ?? '' }}
                            </td>
                            <td nowrap>
                                @can('graph_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.graphs.edit', $graph->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('graph_delete')
                                    <form action="{{ route('admin.graphs.destroy', $graph->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.graph.title_singular"),
    'URL' => route('admin.graphs.massDestroy'),
    'canDelete' => auth()->user()->can('graph_delete') ? true : false
));
</script>
@endsection
