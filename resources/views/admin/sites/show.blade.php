@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.site.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sites.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.site.fields.id') }}
                        </th>
                        <td>
                            {{ $site->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.site.fields.name') }}
                        </th>
                        <td>
                            {{ $site->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.site.fields.description') }}
                        </th>
                        <td>
                            {!! $site->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sites.index') }}">
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
            <a class="nav-link" href="#site_buildings" role="tab" data-toggle="tab">
                {{ trans('cruds.building.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#site_physical_servers" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalServer.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#site_workstations" role="tab" data-toggle="tab">
                {{ trans('cruds.workstation.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#site_storage_devices" role="tab" data-toggle="tab">
                {{ trans('cruds.storageDevice.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#site_peripherals" role="tab" data-toggle="tab">
                {{ trans('cruds.peripheral.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#site_phones" role="tab" data-toggle="tab">
                {{ trans('cruds.phone.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#site_physical_switches" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalSwitch.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="site_buildings">
            @includeIf('admin.sites.relationships.siteBuildings', ['buildings' => $site->siteBuildings])
        </div>
        <div class="tab-pane" role="tabpanel" id="site_physical_servers">
            @includeIf('admin.sites.relationships.sitePhysicalServers', ['physicalServers' => $site->sitePhysicalServers])
        </div>
        <div class="tab-pane" role="tabpanel" id="site_workstations">
            @includeIf('admin.sites.relationships.siteWorkstations', ['workstations' => $site->siteWorkstations])
        </div>
        <div class="tab-pane" role="tabpanel" id="site_storage_devices">
            @includeIf('admin.sites.relationships.siteStorageDevices', ['storageDevices' => $site->siteStorageDevices])
        </div>
        <div class="tab-pane" role="tabpanel" id="site_peripherals">
            @includeIf('admin.sites.relationships.sitePeripherals', ['peripherals' => $site->sitePeripherals])
        </div>
        <div class="tab-pane" role="tabpanel" id="site_phones">
            @includeIf('admin.sites.relationships.sitePhones', ['phones' => $site->sitePhones])
        </div>
        <div class="tab-pane" role="tabpanel" id="site_physical_switches">
            @includeIf('admin.sites.relationships.sitePhysicalSwitches', ['physicalSwitches' => $site->sitePhysicalSwitches])
        </div>
    </div>
</div>

@endsection