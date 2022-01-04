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
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.logicalServer.fields.name') }}
                        </th>
                        <td colspan='11'>
                            {{ $logicalServer->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.description') }}
                        </th>
                        <td colspan='11'>
                            {!! $logicalServer->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th colspan='1'>
                            {{ trans('cruds.logicalServer.fields.operating_system') }}
                        </th>
                        <td colspan="3">
                            {!! $logicalServer->operating_system !!}
                        </td>

                        <th colspan='1'>
                            {{ trans('cruds.logicalServer.fields.cpu') }}
                        </th>
                        <td colspan="3">
                            {!! $logicalServer->cpu !!}
                        </td>
                        <th colspan="1">
                            {{ trans('cruds.logicalServer.fields.environment') }}
                        </th>
                        <td colspan="3">
                            {!! $logicalServer->environment !!}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1">
                            {{ trans('cruds.logicalServer.fields.address_ip') }}
                        </th>
                        <td colspan="3">
                            {!! $logicalServer->address_ip !!}
                        </td>
                        <th colspan="1">
                            {{ trans('cruds.logicalServer.fields.memory') }}
                        </th>
                        <td colspan="3">
                            {!! $logicalServer->memory !!}
                        </td>
                        <th colspan="1">
                            {{ trans('cruds.logicalServer.fields.net_services') }}
                        </th>
                        <td colspan="3">
                            {{ $logicalServer->net_services }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <th colspan="1">
                            {{ trans('cruds.logicalServer.fields.disk') }}
                        </th>
                        <td colspan="3">
                            {!! $logicalServer->disk !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.configuration') }}
                        </th>
                        <td colspan="10">
                            {!! $logicalServer->configuration !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.applications') }}
                        </th>
                        <td colspan="10">
                            @foreach($logicalServer->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                    {{ $application->name }}
                                </a>
                                @if(!$loop->last)
                                ,
                                @endif                                
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logicalServer.fields.servers') }}
                        </th>
                        <td colspan="10">
                            @foreach($logicalServer->servers as $server)
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
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $logicalServer->created_at->format(trans('global.timestamp')) }} |
        {{ trans('global.updated_at') }} {{ $logicalServer->updated_at->format(trans('global.timestamp')) }} 
    </div>
</div>
@endsection