@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.peripherals.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @can('peripheral_edit')
        <a class="btn btn-info" href="{{ route('admin.peripherals.edit', $peripheral->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('peripheral_delete')
        <form action="{{ route('admin.peripherals.destroy', $peripheral->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.peripheral.title_singular') }}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dt>{{ trans('cruds.peripheral.fields.name') }}</dt>
                {{ $peripheral->name }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <dt>{{ trans('cruds.peripheral.fields.description') }}</dt>
                {!! $peripheral->description !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <dt>{{ trans('cruds.peripheral.fields.domain') }}</dt>
                {{ $peripheral->domain }}
            </div>
            <div class="col-md-10">
                <dt>{{ trans('cruds.peripheral.fields.type') }}</dt>
                {{ $peripheral->type }}
            </div>
        </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.ecosystem.title_short") }}        
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dt>{{ trans('cruds.peripheral.fields.provider') }}</dt>
                @if($peripheral->provider!=null)
                    <a href="{{ route('admin.entities.show', $peripheral->provider->id) }}">
                        {{ $peripheral->provider->name }}
                    </a>
                @endif
            </div>
            <div class="col-md-6">
                <dt>{{ trans('cruds.peripheral.fields.responsible') }}</dt>
                {{ $peripheral->responsible ?? '' }}
            </div>
        </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}        
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <dt>{{ trans('cruds.peripheral.fields.applications') }}</dt>
            @foreach($peripheral->applications as $application)
                <a href="{{ route('admin.applications.show', $application->id) }}">
                {{ $application->name }}
                </a>
                @if(!$loop->last), @endif
            @endforeach
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        Common Plateforme Enumeration (CPE)
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <dt>{{ trans('cruds.peripheral.fields.vendor') }}</dt>
                {{ $peripheral->vendor }}
            </div>
            <div class="col-md-4">
                <dt>{{ trans('cruds.peripheral.fields.product') }}</dt>
                {{ $peripheral->product }}
            </div>
            <div class="col-md-4">
                <dt>{{ trans('cruds.peripheral.fields.version') }}</dt>
                {{ $peripheral->pversion }}
            </div>
        </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <dt>{{ trans('cruds.peripheral.fields.site') }}</dt>
                @if($peripheral->site!=null)
                    <a href="{{ route('admin.sites.show', $peripheral->site->id) }}">
                    {{ $peripheral->site->name }}
                    </a>
                @endif
            </div>
            <div class="col-md-4">
                <dt>{{ trans('cruds.peripheral.fields.building') }}</dt>
                @if($peripheral->building!=null)
                    <a href="{{ route('admin.buildings.show', $peripheral->building->id) }}">
                        {{ $peripheral->building->name }}
                    </a>
                @endif
            </div>
            <div class="col-md-4">
                <dt>{{ trans('cruds.peripheral.fields.bay') }}</dt>
                @if($peripheral->bays!=null)
                    <a href="{{ route('admin.bays.show', $peripheral->bay->id) }}">
                        {{ $peripheral->bay->name }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $peripheral->created_at ? $peripheral->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $peripheral->updated_at ? $peripheral->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
<a class="btn btn-default" href="{{ route('admin.peripherals.index') }}">
    {{ trans('global.back_to_list') }}
</a>
@endsection