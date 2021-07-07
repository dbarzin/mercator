@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bay.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bays.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
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
                            {{ $bay->room->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bays.index') }}">
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
            <a class="nav-link" href="#bay_physical_servers" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalServer.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#bay_storage_devices" role="tab" data-toggle="tab">
                {{ trans('cruds.storageDevice.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#bay_peripherals" role="tab" data-toggle="tab">
                {{ trans('cruds.peripheral.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#bay_physical_switches" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalSwitch.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#bay_physical_routers" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalRouter.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#bay_physical_security_devices" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalSecurityDevice.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="bay_physical_servers">
            @includeIf('admin.bays.relationships.bayPhysicalServers', ['physicalServers' => $bay->bayPhysicalServers])
        </div>
        <div class="tab-pane" role="tabpanel" id="bay_storage_devices">
            @includeIf('admin.bays.relationships.bayStorageDevices', ['storageDevices' => $bay->bayStorageDevices])
        </div>
        <div class="tab-pane" role="tabpanel" id="bay_peripherals">
            @includeIf('admin.bays.relationships.bayPeripherals', ['peripherals' => $bay->bayPeripherals])
        </div>
        <div class="tab-pane" role="tabpanel" id="bay_physical_switches">
            @includeIf('admin.bays.relationships.bayPhysicalSwitches', ['physicalSwitches' => $bay->bayPhysicalSwitches])
        </div>
        <div class="tab-pane" role="tabpanel" id="bay_physical_routers">
            @includeIf('admin.bays.relationships.bayPhysicalRouters', ['physicalRouters' => $bay->bayPhysicalRouters])
        </div>
        <div class="tab-pane" role="tabpanel" id="bay_physical_security_devices">
            @includeIf('admin.bays.relationships.bayPhysicalSecurityDevices', ['physicalSecurityDevices' => $bay->bayPhysicalSecurityDevices])
        </div>
    </div>
</div>

@endsection