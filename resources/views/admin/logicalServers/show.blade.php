@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=LSERVER_{{$logicalServer->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('logical_server_edit')
        <a class="btn btn-info" href="{{ route('admin.logical-servers.edit', $logicalServer->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('logical_server_delete')
        <form action="{{ route('admin.logical-servers.destroy', $logicalServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('cruds.logicalServer.title_singular') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.logicalServer.fields.name') }}
                    </th>
                    <td>
                        {{ $logicalServer->name }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.logicalServer.fields.type') }}
                    </th>
                    <td>
                        {{ $logicalServer->type }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.logicalServer.fields.attributes') }}
                    </th>
                    <td>
                        @foreach(explode(" ",$logicalServer->attributes) as $attribute)
                        <span class="badge badge-info">{{ $attribute }}</span>
                        @endforeach
                    </td>
                    <td width=10%>
                        {{ $logicalServer->active ? "Active" : "" }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.logicalServer.fields.description') }}
                    </th>
                    <td colspan=5>
                        {!! $logicalServer->description !!}
                    </td>
                    <td width="10%">
                        @if ($logicalServer->icon_id === null)
                        <img src='/images/lserver.png' width='120' height='120'>
                        @else
                        <img src='{{ route('admin.documents.show', $logicalServer->icon_id) }}' width='100' height='100'>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="16%">
                        {{ trans('cruds.logicalServer.fields.operating_system') }}
                    </th>
                    <td width="16%">
                        {!! $logicalServer->operating_system !!}
                    </td>
                    <th width="16%">
                        {{ trans('cruds.logicalServer.fields.install_date') }}
                    </th>
                    <td width="16%">
                        {!! $logicalServer->install_date !!}
                    </td>
                    <th width="16%">
                        {{ trans('cruds.logicalServer.fields.update_date') }}
                    </th>
                    <td width="20%">
                        {!! $logicalServer->update_date !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.logicalServer.fields.cluster') }}
                    </th>
                    <td>
                        @if ($logicalServer->cluster!=null)
                        <a href="{{ route('admin.clusters.show', $logicalServer->cluster_id) }}">
                            {!! $logicalServer->cluster->name ?? "" !!}
                        </a>
                        @endif
                    </td>
                    <th>
                        {{ trans('cruds.logicalServer.fields.environment') }}
                    </th>
                    <td>
                        {!! $logicalServer->environment !!}
                    </td>
                    <th>
                        {{ trans('cruds.logicalServer.fields.address_ip') }}
                    </th>
                    <td>
                        {!! $logicalServer->address_ip !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.logicalServer.fields.net_services') }}
                    </th>
                    <td colspan="5">
                        {{ $logicalServer->net_services }}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.logicalServer.fields.configuration") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="12%">
                        {{ trans('cruds.logicalServer.fields.cpu') }}
                    </th>
                    <td width="12%">
                        {!! $logicalServer->cpu !!}
                    </td>
                    <th width="12%">
                        <b>{{ trans('cruds.logicalServer.fields.memory') }}</b>
                    </th>
                    <td width="12%">
                        {!! $logicalServer->memory !!}
                    </td>
                    <th width="12%">
                        <b>{{ trans('cruds.logicalServer.fields.disk') }}</b>
                    </th>
                    <td width="12%">
                        {!! $logicalServer->disk !!}
                    </td>
                    <th width="12%">
                        <b>{{ trans('cruds.logicalServer.fields.disk_used') }}</b>
                    </th>
                    <td width="12%">
                        {!! $logicalServer->disk !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.logicalServer.fields.configuration') }}
                    </th>
                    <td colspan="7">
                        {!! $logicalServer->configuration !!}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        <dt>{{ trans('cruds.logicalServer.fields.applications') }}</dt>
                    </th>
                    <td width="40%">
                        @foreach($logicalServer->applications as $application)
                            <a href="{{ route('admin.applications.show', $application->id) }}">
                                {{ $application->name }}
                            </a>
                            @if(!$loop->last)
                            ,
                            @endif
                        @endforeach
                    </td>
                    <th width="10%">
                        <dt>{{ trans('cruds.logicalServer.fields.databases') }}</dt>
                    </th>
                    <td width="40%">
                        @foreach($logicalServer->databases as $database)
                            <a href="{{ route('admin.databases.show', $database->id) }}">
                                {{ $database->name }}
                            </a>
                            @if(!$loop->last)
                            ,
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.administration.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        <dt>{{ trans('cruds.logicalServer.fields.domain') }}</dt>
                    </th>
                    <td>
                        @if ($logicalServer->domain_id!==null)
                        <a href="{{ route('admin.domaine-ads.show', $logicalServer->domain_id) }}">
                            {{ $logicalServer->domain->name }}
                        </a>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        <dt>{{ trans('cruds.logicalServer.fields.servers') }}</dt>
                    </th>
                    <td>
                        @foreach($logicalServer->physicalServers as $server)
                            <a href="{{ route('admin.physical-servers.show', $server->id) }}">
                                {{ $server->name }}
                            </a>
                            @if(!$loop->last)
                            ,
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $logicalServer->created_at ? $logicalServer->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $logicalServer->updated_at ? $logicalServer->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
