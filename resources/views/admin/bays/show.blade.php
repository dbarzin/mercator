@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.bays.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=BAY_{{$bay->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('bay_edit')
        <a class="btn btn-info" href="{{ route('admin.bays.edit', $bay->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('bay_create')
        <a class="btn btn-warning" href="{{ route('admin.bays.clone', $bay->id) }}">
            {{ trans('global.clone') }}
        </a>
    @endcan

    @can('bay_delete')
        <form action="{{ route('admin.bays.destroy', $bay->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bay.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.bay.fields.name') }}
                    </th>
                    <td>
                        {{ $bay->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.bay.fields.description') }}
                    </th>
                    <td>
                        {!! $bay->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.bay.fields.room') }}
                    </th>
                    <td>
                        @if ($bay->room!=null)
                            <a href="{{ route('admin.buildings.show', $bay->room->id) }}">
                            {{ $bay->room->name ?? '' }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.menu.physical_infrastructure.title_short') }}
                    </th>
                    <td>
                    @foreach($bay->bayPhysicalServers as $physicalServer)
                        <a href="{{ route('admin.physical-servers.show', $physicalServer->id) }}">{{ $physicalServer->name }}</a>
                        @if(!$loop->last)
                        ,
                        @else
                        <br>
                        @endif
                    @endforeach

                    @foreach($bay->bayStorageDevices as $storageDevice)
                        <a href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">{{ $storageDevice->name }}</a>
                        @if(!$loop->last)
                        ,
                        @else
                        <br>
                        @endif
                    @endforeach

                    @foreach($bay->bayPeripherals as $peripheral)
                        <a href="{{ route('admin.peripherals.show', $peripheral->id) }}">{{ $peripheral->name }}</a>
                        @if(!$loop->last)
                        ,
                        @else
                        <br>
                        @endif
                    @endforeach

                    @foreach($bay->bayPhysicalSwitches as $physicalSwitch)
                        <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">{{ $physicalSwitch->name }}</a>
                        @if(!$loop->last)
                        ,
                        @else
                        <br>
                        @endif
                    @endforeach

                    @foreach($bay->bayPhysicalRouters as $physicalRouter)
                        <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">{{ $physicalRouter->name }}</a>
                        @if(!$loop->last)
                        ,
                        @else
                        <br>
                        @endif
                    @endforeach

                    @foreach($bay->bayPhysicalSecurityDevices as $physicalSecurityDevice)
                        <a href="{{ route('admin.physical-security-devices.show', $physicalSecurityDevice->id) }}">{{ $physicalSecurityDevice->name }}</a>
                        @if(!$loop->last)
                        ,
                        @else
                        <br>
                        @endif
                    @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $bay->created_at ? $bay->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $bay->updated_at ? $bay->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.bays.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
