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
        {{ trans('global.show') }} {{ trans('cruds.logicalServer.title') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dt>{{ trans('cruds.logicalServer.fields.name') }}</dt>
                {{ $logicalServer->name }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <dt>{{ trans('cruds.logicalServer.fields.description') }}</dt>
                {!! $logicalServer->description !!}
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">    
        <div class="row">
            <div class="col-sm">      
                <dt>{{ trans('cruds.logicalServer.fields.operating_system') }}</dt>
                {!! $logicalServer->operating_system !!}
            </div>
            <div class="col-sm">
                <dt>{{ trans('cruds.logicalServer.fields.install_date') }}</dt>
                {!! $logicalServer->install_date !!}
            </div>
            <div class="col-sm">
                <dt>{{ trans('cruds.logicalServer.fields.update_date') }}</dt>
                {!! $logicalServer->update_date !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <dt>{{ trans('cruds.logicalServer.fields.environment') }}</dt>
                {!! $logicalServer->environment !!}
            </div>
            <div class="col-md-8">
                <dt>{{ trans('cruds.logicalServer.fields.address_ip') }}</dt>
                {!! $logicalServer->address_ip !!}
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <dt>{{ trans('cruds.logicalServer.fields.net_services') }}</dt>
                {{ $logicalServer->net_services }}
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.logicalServer.fields.configuration") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">    
        <div class="row">
            <div class="col-sm-2">
                <b>{{ trans('cruds.logicalServer.fields.cpu') }}</b>
                {!! $logicalServer->cpu !!}
            </div>
            <div class="col-sm-2">
                <b>{{ trans('cruds.logicalServer.fields.memory') }}</b>
                {!! $logicalServer->memory !!}
            </div>
            <div class="col-sm-2">
                <b>{{ trans('cruds.logicalServer.fields.disk') }}</b>
                {!! $logicalServer->disk !!}
            </div>
            <div class="col-sm-2">
                <b>{{ trans('cruds.logicalServer.fields.disk') }}</b>
                {!! $logicalServer->disk !!}
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                {!! $logicalServer->configuration !!}
            </div>

        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <dt>{{ trans('cruds.logicalServer.fields.applications') }}</dt>
                @foreach($logicalServer->applications as $application)
                    <a href="{{ route('admin.applications.show', $application->id) }}">
                        {{ $application->name }}
                    </a>
                    @if(!$loop->last)
                    ,
                    @endif                                
                @endforeach
            </div>
            <div class="col-sm">
                <dt>{{ trans('cruds.logicalServer.fields.databases') }}</dt>
                @foreach($logicalServer->databases as $database)
                    <a href="{{ route('admin.databases.show', $database->id) }}">
                        {{ $database->name }}
                    </a>
                    @if(!$loop->last)
                    ,
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">    
        <div class="row">
            <div class="col-sm">
                <dt>{{ trans('cruds.logicalServer.fields.servers') }}</dt>
                @foreach($logicalServer->servers as $server)
                    <a href="{{ route('admin.physical-servers.show', $server->id) }}">
                        {{ $server->name }}
                    </a>
                    @if(!$loop->last)
                    ,
                    @endif                                
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $logicalServer->created_at ? $logicalServer->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $logicalServer->updated_at ? $logicalServer->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
        {{ trans('global.back_to_list') }}
        </a>
    </div>
    </div>
</div>

@endsection