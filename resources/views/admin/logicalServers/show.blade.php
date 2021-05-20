@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.logicalServer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.logicalServer.fields.name') }}
                        </th>
                        <td>
                            {{ $logicalServer->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.description') }}
                        </th>
                        <td>
                            {!! $logicalServer->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.cpu') }}
                        </th>
                        <td>
                            {!! $logicalServer->cpu !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.operating_system') }}
                        </th>
                        <td>
                            {!! $logicalServer->operating_system !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.address_ip') }}
                        </th>
                        <td>
                            {!! $logicalServer->address_ip !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.memory') }}
                        </th>
                        <td>
                            {!! $logicalServer->memory !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.disk') }}
                        </th>
                        <td>
                            {!! $logicalServer->disk !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.environment') }}
                        </th>
                        <td>
                            {!! $logicalServer->environment !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.configuration') }}
                        </th>
                        <td>
                            {!! $logicalServer->configuration !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.net_services') }}
                        </th>
                        <td>
                            {{ $logicalServer->net_services }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.applications') }}
                        </th>
                        <td>
                            @foreach($logicalServer->applications as $key => $applications)
                                <span class="label label-info">{{ $applications->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.servers') }}
                        </th>
                        <td>
                            @foreach($logicalServer->servers as $key => $servers)
                                <span class="label label-info">{{ $servers->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection