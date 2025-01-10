@extends('layouts.admin')
@section('content')
@can('logical_server_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.logical-servers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.logicalServer.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.logicalServer.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.attributes') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.configuration') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.applications') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.cluster') }} /
                            {{ trans('cruds.logicalServer.fields.servers') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logicalServers as $key => $logicalServer)
                        <tr data-entry-id="{{ $logicalServer->id }}"
                        @if ($logicalServer->active!=1)
                            class="table-secondary"
                        @elseif (
                            ($logicalServer->description==null)||
                            ($logicalServer->operating_system==null)||
                            ($logicalServer->environment==null)||
                            ($logicalServer->address_ip==null)||
                            ($logicalServer->applications->count()==0)||
                            (
                                ($logicalServer->servers->count()==0) && ($logicalServer->cluster_id==null)
                            ))
                                class="table-warning"
                        @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                                {{ $logicalServer->name }}
                                </a>
                            </td>
                            <td>
                                {{ $logicalServer->type }}
                            </td>
                            <td>
                                @foreach(explode(" ",$logicalServer->attributes) as $attribute)
                                <span class="badge badge-info">{{ $attribute }}</span>
                                @endforeach
                            </td>
                            <td>
                                {!! $logicalServer->description !!}
                            </td>
                            <td>
                                {!! $logicalServer->configuration !!}
                            </td>
                            <td>
                              @foreach($logicalServer->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                  {{ $application->name }}
                                </a>
                                  @if(!$loop->last)
                                  ,
                                  @endif
                              @endforeach
                            </td>
                            <td>
                                @if ($logicalServer->cluster!==null)
                                <a href="{{ route('admin.clusters.show', $logicalServer->cluster_id) }}">
                                  {{ $logicalServer->cluster->name }}
                                </a>
                                @endif
                                @if(count($logicalServer->servers)>0)
                                    @if ($logicalServer->cluster_id!==null)
                                    /
                                    @endif
                                    @foreach($logicalServer->servers as $server)
                                    <a href="{{ route('admin.physical-servers.show', $server->id) }}">
                                        {{ $server->name }}
                                    </a>
                                        @if(!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td nowrap>
                                @can('logical_server_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('logical_server_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.logical-servers.edit', $logicalServer->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('logical_server_delete')
                                    <form action="{{ route('admin.logical-servers.destroy', $logicalServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.logicalServer.title_singular"),
    'URL' => route('admin.logical-servers.massDestroy'),
    'canDelete' => auth()->user()->can('logical_server_delete') ? true : false
));
</script>
@endsection
