@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.cluster.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clusters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=CLUSTER_{{$cluster->id}}">
                    {{ trans('global.explore') }}
                </a>

                @can('cluster_edit')
                    <a class="btn btn-info" href="{{ route('admin.clusters.edit', $cluster->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('cluster_delete')
                    <form action="{{ route('admin.clusters.destroy', $cluster->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.cluster.fields.name') }}
                        </th>
                        <td>
                            {{ $cluster->name }}
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.cluster.fields.type') }}
                        </th>
                        <td>
                            {{ $cluster->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.cluster.fields.description') }}
                        </th>
                        <td>
                            {!! $cluster->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.cluster.fields.logical_servers') }}
                        </th>
                        <td>
                            @foreach($cluster->logicalServers as $server)
                                <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                                    {{ $server->name }}
                                </a>
                                @if(!$loop->last)
                                <br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.cluster.fields.physical_servers') }}
                        </th>
                        <td>
                            @foreach($cluster->physicalServers as $server)
                                <a href="{{ route('admin.physical-servers.show', $server->id) }}">
                                    {{ $server->name }}
                                </a>
                                @if(!$loop->last)
                                <br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clusters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $cluster->created_at ? $cluster->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $cluster->updated_at ? $cluster->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

@endsection
