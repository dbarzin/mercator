@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.building.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.buildings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.building.fields.id') }}
                        </th>
                        <td>
                            {{ $building->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.building.fields.name') }}
                        </th>
                        <td>
                            {{ $building->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.building.fields.description') }}
                        </th>
                        <td>
                            {!! $building->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.building.fields.site') }}
                        </th>
                        <td>
                            {{ $building->site->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.buildings.index') }}">
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
            <a class="nav-link" href="#room_bays" role="tab" data-toggle="tab">
                {{ trans('cruds.bay.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#building_physical_servers" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalServer.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#building_workstations" role="tab" data-toggle="tab">
                {{ trans('cruds.workstation.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#building_storage_devices" role="tab" data-toggle="tab">
                {{ trans('cruds.storageDevice.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#building_peripherals" role="tab" data-toggle="tab">
                {{ trans('cruds.peripheral.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#building_phones" role="tab" data-toggle="tab">
                {{ trans('cruds.phone.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#building_physical_switches" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalSwitch.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="room_bays">
            @includeIf('admin.buildings.relationships.roomBays', ['bays' => $building->roomBays])
        </div>
        <div class="tab-pane" role="tabpanel" id="building_physical_servers">
            @includeIf('admin.buildings.relationships.buildingPhysicalServers', ['physicalServers' => $building->buildingPhysicalServers])
        </div>
        <div class="tab-pane" role="tabpanel" id="building_workstations">
            @includeIf('admin.buildings.relationships.buildingWorkstations', ['workstations' => $building->buildingWorkstations])
        </div>
        <div class="tab-pane" role="tabpanel" id="building_storage_devices">
            @includeIf('admin.buildings.relationships.buildingStorageDevices', ['storageDevices' => $building->buildingStorageDevices])
        </div>
        <div class="tab-pane" role="tabpanel" id="building_peripherals">
            @includeIf('admin.buildings.relationships.buildingPeripherals', ['peripherals' => $building->buildingPeripherals])
        </div>
        <div class="tab-pane" role="tabpanel" id="building_phones">
            @includeIf('admin.buildings.relationships.buildingPhones', ['phones' => $building->buildingPhones])
        </div>
        <div class="tab-pane" role="tabpanel" id="building_physical_switches">
            @includeIf('admin.buildings.relationships.buildingPhysicalSwitches', ['physicalSwitches' => $building->buildingPhysicalSwitches])
        </div>
    </div>
</div>

@endsection