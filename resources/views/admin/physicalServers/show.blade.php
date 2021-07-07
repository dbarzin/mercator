@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalServer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.physicalServer.fields.name') }}
                        </th>
                        <td>
                            {{ $physicalServer->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.descrition') }}
                        </th>
                        <td>
                            {!! $physicalServer->descrition !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.type') }}
                        </th>
                        <td>
                            {{ $physicalServer->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.configuration') }}
                        </th>
                        <td>
                            {!! $physicalServer->configuration !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.site') }}
                        </th>
                        <td>
                            {{ $physicalServer->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.building') }}
                        </th>
                        <td>
                            {{ $physicalServer->building->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.bay') }}
                        </th>
                        <td>
                            {{ $physicalServer->bay->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.physical_switch') }}                    
                        </th>
                        <td>
                            {{ $physicalServer->physicalSwitch->name ?? '' }}
                        </td>
                    </tr>


                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.responsible') }}
                        </th>
                        <td>
                            {{ $physicalServer->responsible }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#servers_logical_servers" role="tab" data-toggle="tab">
                {{ trans('cruds.logicalServer.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="servers_logical_servers">
            @includeIf('admin.physicalServers.relationships.serversLogicalServers', ['logicalServers' => $physicalServer->serversLogicalServers])
        </div>
    </div>
</div>

@endsection