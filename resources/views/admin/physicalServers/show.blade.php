@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.physical-servers.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=PSERVER_{{$physicalServer->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('physical_server_edit')
        <a class="btn btn-info" href="{{ route('admin.physical-servers.edit', $physicalServer->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('physical_server_delete')
        <form action="{{ route('admin.physical-servers.destroy', $physicalServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalServer.title') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dt>{{ trans('cruds.physicalServer.fields.name') }}</dt>
                {{ $physicalServer->name }}
            </div>
            <div class="col-md-6">
                <dt>{{ trans('cruds.physicalServer.fields.type') }}</dt>
                {{ $physicalServer->type }}
            </div>
        </div>
        <div class="row">
            <div class="col-lg">
                <dt>{{ trans('cruds.physicalServer.fields.description') }}</dt>
                {!! $physicalServer->description !!}
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('cruds.physicalServer.fields.configuration') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2">
                <b>{{ trans('cruds.physicalServer.fields.cpu') }}</b>
                {!! $physicalServer->cpu !!}
            </div>
            <div class="col-sm-2">
                <b>{{ trans('cruds.physicalServer.fields.memory') }}</b>
                {!! $physicalServer->memory !!}
            </div>
            <div class="col-sm-2">
                <b>{{ trans('cruds.physicalServer.fields.disk') }}</b>
                {!! $physicalServer->disk !!}
            </div>
            <div class="col-sm-2">
                <b>{{ trans('cruds.physicalServer.fields.disk_used') }}</b>
                {!! $physicalServer->disk_used !!}
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                {!! $physicalServer->configuration !!}
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
            <div class="col-lg">
                <b>{{ trans('cruds.physicalServer.fields.applications') }}</b> :
                @foreach($physicalServer->applications as $application)
                    <a href="{{ route('admin.applications.show', $application->id) }}">
                        {{ $application->name }}
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
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm">      
                <dt>{{ trans('cruds.physicalServer.fields.operating_system') }}</dt>
                {!! $physicalServer->operating_system !!}
            </div>
            <div class="col-sm">
                <dt>{{ trans('cruds.physicalServer.fields.install_date') }}</dt>
                {!! $physicalServer->install_date !!}
            </div>
            <div class="col-sm">
                <dt>{{ trans('cruds.physicalServer.fields.update_date') }}</dt>
                {!! $physicalServer->update_date !!}
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <b>{{ trans('cruds.physicalServer.fields.address_ip') }}</b>
                {{ $physicalServer->address_ip }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <b>{{ trans('cruds.physicalServer.fields.responsible') }}</b>
                {{ $physicalServer->responsible }}
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
            <div class="col-lg">
                <b>{{ trans('cruds.physicalServer.fields.site') }}</b>
                @if ($physicalServer->site!=null)
                    <a href="{{ route('admin.sites.show', $physicalServer->site->id) }}">
                    {{ $physicalServer->site->name ?? '' }}
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg"> 
                <b>{{ trans('cruds.physicalServer.fields.building') }}</b>
                @if ($physicalServer->building!=null)
                    <a href="{{ route('admin.buildings.show', $physicalServer->building->id) }}">
                    {{ $physicalServer->building->name ?? '' }}
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg"> 
                <b>{{ trans('cruds.physicalServer.fields.bay') }}</b>
                @if ($physicalServer->bay!=null)
                    <a href="{{ route('admin.bays.show', $physicalServer->bay->id) }}">
                    {{ $physicalServer->bay->name ?? '' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $physicalServer->created_at ? $physicalServer->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $physicalServer->updated_at ? $physicalServer->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.physical-servers.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection