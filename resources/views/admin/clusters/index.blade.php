@extends('layouts.admin')
@section('content')
@can('cluster_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.clusters.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.cluster.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.cluster.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.cluster.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.cluster.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.cluster.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clusters as $cluster)
                        <tr data-entry-id="{{ $cluster->id }}"
                        @if(
                            ($cluster->description==null)||
                            ($cluster->type==null)
                            )
                                class="table-warning"
                        @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.clusters.show', $cluster->id) }}">
                                {{ $cluster->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $cluster->type ?? '' !!}
                            </td>
                            <td>
                                {!! $cluster->description ?? '' !!}
                            </td>
                            <td nowrap>
                                @can('cluster_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.clusters.show', $cluster->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('cluster_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.clusters.edit', $cluster->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('cluster_delete')
                                    <form action="{{ route('admin.clusters.destroy', $cluster->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.cluster.title_singular"),
    'URL' => route('admin.clusters.massDestroy'),
    'canDelete' => auth()->user()->can('cluster_delete') ? true : false
));
</script>
@endsection
