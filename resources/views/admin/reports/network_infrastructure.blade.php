@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.menu.network_schema.title") }}
                </div>
                <form action="/admin/report/network_infrastructure">

                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-sm-6" style="max-width: 800px; width: 100%;">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td style="width: 300px;">
                                        {{ trans("cruds.site.title_singular") }} :
                                        <select name="site" id="site" class="form-control select2">
                                            <option></option>
                                            @foreach($all_sites as $id => $name)
                                                <option value="{{$id}}" {{ Session::get('site')==$id ? "selected" : "" }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="width: 500px;">
                                        {{ trans("cruds.building.title_singular") }} :
                                        <select name="buildings[]" id="buildings" class="form-control select2" multiple>
                                            @if ($all_buildings!=null)
                                                @foreach($all_buildings as $id => $name)
                                                    <option value="{{$id}}" {{ (Session::get('buildings')!=null) && in_array($id, Session::get('buildings')) ? "selected" : "" }}>{{ $name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="graph-container">
                            <div id="graph" class="graphviz"></div>
                        </div>
                        <div class="row p-1">
                            <div class="col-4">

                                @php($engines=["dot", "fdp",  "osage", "circo" ])
                                @php($engine = request()->get('engine', 'dot'))

                                <label class="inline-flex items-center ps-1 pe-1">
                                    <a href="#" id="downloadSvg"><i class="bi bi-download"></i></a>
                                </label>

                                <label class="inline-flex items-center">
                                    Rendu :
                                </label>
                                @foreach($engines as $value)
                                    <label class="inline-flex items-center ps-1">
                                        <input
                                                type="radio"
                                                name="engine"
                                                value="{{ $value }}"
                                                @checked($engine === $value)
                                                onchange="this.form.submit();"
                                        >
                                        <span>{{ $value }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @can('site_access')
                @if ($sites->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.site.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.site.description") }}</p>
                            @foreach($sites as $site)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="SITE{{ $site->id }}">
                                            <th colspan="2">
                                                <a href="/admin/sites/{{ $site->id }}">{{ $site->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td width="20%">
                                                    {{ trans("cruds.site.fields.description") }}
                                                </td>
                                                <td>
                                                    {!! $site->description !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    {{ trans("cruds.site.fields.buildings") }}
                                                </th>
                                                <td>
                                                    @foreach($site->buildings as $building)
                                                        <a href="#BUILDING{{$building->id}}">{{$building->name}}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
            @endcan

            @can('building_access')
                @if ($buildings->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.building.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.building.description") }}</p>
                            @foreach($buildings as $building)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="BUILDING{{ $building->id }}">
                                            <th colspan="2">
                                                <a href="/admin/buildings/{{ $building->id }}">{{ $building->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td width="20%">{{ trans("cruds.building.fields.description") }}</td>
                                                <td>{!! $building->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.building.fields.bays") }}</th>
                                                <td>
                                                    @foreach($building->roomBays as $bay)
                                                        <a href="#BAY{{$bay->id}}">{{$bay->name}}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('bay_access')
                @if ($bays->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.bay.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.bay.description") }}</p>
                            @foreach($bays as $bay)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="BAY{{ $bay->id }}">
                                            <th colspan="2">
                                                <a href="/admin/bays/{{ $bay->id }}">{{ $bay->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.bay.fields.description") }}</th>
                                                <td>{!! $bay->description !!}</td>
                                            </tr>
                                            @if ($bay->physicalServers->count()>0)
                                                <tr>
                                                    <th>{{ trans("cruds.bay.fields.physical_servers") }}</th>
                                                    <td>
                                                        @foreach($bay->physicalServers as $physicalServer)
                                                            <a href="#PSERVER{{$physicalServer->id}}">{{ $physicalServer->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($bay->physicalRouters->count()>0)
                                                <tr>
                                                    <th>{{ trans("cruds.bay.fields.physical_routers") }}</th>
                                                    <td>
                                                        @foreach($bay->physicalRouters as $physicalRouter)
                                                            <a href="#ROUTER{{$physicalRouter->id}}">{{ $physicalRouter->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($bay->physicalSwitches->count()>0)
                                                <tr>
                                                    <th>{{ trans("cruds.bay.fields.physical_switches") }}</th>
                                                    <td>
                                                        @foreach($bay->physicalSwitches as $bayPhysicalSwitch)
                                                            <a href="#SWITCH{{$bayPhysicalSwitch->id}}">{{ $bayPhysicalSwitch->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($bay->storageDevices->count()>0)
                                                <tr>
                                                    <th>{{ trans("cruds.bay.fields.storage_devices") }}</th>
                                                    <td>
                                                        @foreach($bay->storageDevices as $bayStorageDevice)
                                                            <a href="#STORAGEDEVICE{{$bayStorageDevice->id}}">{{ $bayStorageDevice->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($bay->physicalSecurityDevices->count()>0)
                                                <tr>
                                                    <th>{{ trans("cruds.bay.fields.physical_security_devices") }}</th>
                                                    <td>
                                                        @foreach($bay->physicalSecurityDevices as $physicalSecurityDevice)
                                                            <a href="#PSD{{$physicalSecurityDevice->id}}">{{ $physicalSecurityDevice->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($bay->peripherals->count()>0)
                                                <tr>
                                                    <th>{{ trans("cruds.bay.fields.peripherals") }}</th>
                                                    <td>
                                                        @foreach($bay->peripherals as $peripheral)
                                                            <a href="#PERIPHERAL{{$peripheral->id}}">{{ $peripheral->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_server_access')
                @if ($physicalServers->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalServer.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalServer.description") }}</p>
                            @foreach($physicalServers as $pserver)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="PSERVER{{ $pserver->id }}">
                                            <th colspan="2">
                                                <a href="/admin/physical-servers/{{ $pserver->id }}">{{ $pserver->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.physicalServer.fields.type") }}</th>
                                                <td>{{ $pserver->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.description") }}</th>
                                                <td>{!! $pserver->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.configuration") }}</th>
                                                <td>{!! $pserver->configuration !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.site") }}</th>
                                                <td>
                                                    @if ($pserver->site!=null)
                                                        <a href="#SITE{{$pserver->site->id}}">{{ $pserver->site->name }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.building") }}</th>
                                                <td>
                                                    @if ($pserver->building!=null)
                                                        <a href="#BUILDING{{ $pserver->building->id }}">{{ $pserver->building->name }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.bay") }}</th>
                                                <td>
                                                    @if ($pserver->bay!=null)
                                                        <a href="#BAY{{ $pserver->bay->id }}">{{ $pserver->bay->name }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.responsible") }}</th>
                                                <td>{{ $pserver->responsible }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalServer.fields.logical_servers") }}</th>
                                                <td>
                                                    @foreach($pserver->logicalServers as $logicalServer)
                                                        <a href="/admin/report/logical_infrastructure#LOGICAL_SERVER{{ $logicalServer->id }}">{{ $logicalServer->name }}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('workstation_access')
                @if ((auth()->user()->granularity>=2)&&($workstations->count()>0))
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.workstation.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.workstation.description") }}</p>
                            @foreach($workstations as $workstation)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="WORKSTATION{{ $workstation->id }}">
                                            <th colspan="2">
                                                <a href="/admin/workstations/{{ $workstation->id }}">{{ $workstation->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.workstation.fields.type") }}</th>
                                                <td>{{ $workstation->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.workstation.fields.description") }}</th>
                                                <td>{!! $workstation->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.workstation.fields.site") }}</th>
                                                <td>
                                                    @if ($workstation->site!=null)
                                                        <a href="#SITE{{$workstation->site->id}}">{{ $workstation->site->name }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.workstation.fields.building") }}</th>
                                                <td>
                                                    @if ($workstation->building!=null)
                                                        <a href="#BUILDING{{ $workstation->building->id }}">{{ $workstation->building->name }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('storage_device_access')
                @if ($storageDevices->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.storageDevice.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.storageDevice.description") }}</p>
                            @foreach($storageDevices as $storageDevice)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="STORAGEDEVICE{{ $storageDevice->id }}">
                                            <th colspan="2">
                                                <a href="/admin/storage-devices/{{ $storageDevice->id }}">{{ $storageDevice->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.storageDevice.fields.description") }}</th>
                                                <td>{!! $storageDevice->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.storageDevice.fields.site") }}</th>
                                                <td>
                                                    @if ($storageDevice->site!=null)
                                                        <a href="#SITE{{$storageDevice->site->id}}">{{ $storageDevice->site->name }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.storageDevice.fields.building") }}</th>
                                                <td>
                                                    @if ($storageDevice->building!=null)
                                                        <a href="#BUILDING{{ $storageDevice->building->id }}">{{ $storageDevice->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.storageDevice.fields.bay") }}</th>
                                                <td>
                                                    @if ($storageDevice->bay!=null)
                                                        <a href="#BAY{{ $storageDevice->bay->id }}">{{ $storageDevice->bay->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('peripheral_access')
                @if ((auth()->user()->granularity>=2)&&($peripherals->count()>0))
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.peripheral.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.peripheral.description") }}</p>
                            @foreach($peripherals as $peripheral)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="PERIPHERAL{{ $peripheral->id }}">
                                            <th colspan="2">
                                                <a href="/admin/peripherals/{{ $peripheral->id }}">{{ $peripheral->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.peripheral.fields.description") }}</th>
                                                <td>{!! $peripheral->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.peripheral.fields.type") }}</th>
                                                <td>{{ $peripheral->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.peripheral.fields.responsible") }}</th>
                                                <td>{{ $peripheral->responsible }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.peripheral.fields.site") }}</th>
                                                <td>
                                                    @if ($peripheral->site!=null)
                                                        <a href="#SITE{{ $peripheral->site->id }}">{{ $peripheral->site->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.peripheral.fields.building") }}</th>
                                                <td>
                                                    @if ($peripheral->building!=null)
                                                        <a href="#BUILDING{{ $peripheral->building->id }}">{{ $peripheral->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.peripheral.fields.bay") }}</th>
                                                <td>
                                                    @if ($peripheral->bay!=null)
                                                        <a href="#BAY{{ $peripheral->bay->id }}">{{ $peripheral->bay->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('phone_access')
                @if ((auth()->user()->granularity>=2)&&($phones->count()>0))
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.phone.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.phone.description") }}</p>
                            @foreach($phones as $phone)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="PHONE{{ $phone->id }}">
                                            <th colspan="2">
                                                <a href="/admin/phones/{{ $phone->id }}">{{ $phone->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.phone.fields.description") }}</th>
                                                <td>{!! $phone->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.phone.fields.type") }}</th>
                                                <td>{{ $phone->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.phone.fields.site") }}</th>
                                                <td>
                                                    @if ($phone->site!=null)
                                                        <a href="#SITE{{ $phone->site->id }}">{{ $phone->site->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.phone.fields.building") }}</th>
                                                <td>
                                                    @if ($phone->building!=null)
                                                        <a href="#BUILDING{{ $phone->building->id }}">{{ $phone->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_switch_access')
                @if ($physicalSwitches->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalSwitch.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalSwitch.description") }}</p>
                            @foreach($physicalSwitches as $switch)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="SWITCH{{ $switch->id }}">
                                            <th colspan="2">
                                                <a href="/admin/physical-switches/{{ $switch->id }}">{{ $switch->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.physicalSwitch.fields.description") }}</th>
                                                <td>{!! $switch->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSwitch.fields.type") }}</th>
                                                <td>{{ $switch->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSwitch.fields.site") }}</th>
                                                <td>
                                                    @if ($switch->site!=null)
                                                        <a href="#SITE{{ $switch->site->id }}">{{ $switch->site->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSwitch.fields.building") }}</th>
                                                <td>
                                                    @if ($switch->building!=null)
                                                        <a href="#BUILDING{{ $switch->building->id }}">{{ $switch->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSwitch.fields.bay") }}</th>
                                                <td>
                                                    @if ($switch->bay!=null)
                                                        <a href="#BAY{{ $switch->bay->id }}">{{ $switch->bay->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_router_access')
                @if ($physicalRouters->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalRouter.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalRouter.description") }}</p>
                            @foreach($physicalRouters as $router)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="ROUTER{{ $router->id }}">
                                            <th colspan="2">
                                                <a href="/admin/physical-routers/{{ $router->id }}">{{ $router->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.physicalRouter.fields.description") }}</th>
                                                <td>{!! $router->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalRouter.fields.type") }}</th>
                                                <td>{{ $router->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalRouter.fields.site") }}</th>
                                                <td>
                                                    @if ($router->site!=null)
                                                        <a href="#SITE{{ $router->site->id }}">{{ $router->site->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalRouter.fields.building") }}</th>
                                                <td>
                                                    @if ($router->building!=null)
                                                        <a href="#BUILDING{{ $router->building->id }}">{{ $router->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalRouter.fields.bay") }}</th>
                                                <td>
                                                    @if ($router->bay!=null)
                                                        <a href="#BAY{{ $router->bay->id }}">{{ $router->bay->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('wifi_terminal_access')
                @if ($wifiTerminals->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.wifiTerminal.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.wifiTerminal.description") }}</p>
                            @foreach($wifiTerminals as $wifiTerminal)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="WIFI{{ $wifiTerminal->id }}">
                                            <th colspan="2">
                                                <a href="/admin/wifi-terminals/{{ $wifiTerminal->id }}">{{ $wifiTerminal->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.wifiTerminal.fields.description") }}</th>
                                                <td>{!! $wifiTerminal->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.wifiTerminal.fields.type") }}</th>
                                                <td>{{ $wifiTerminal->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.wifiTerminal.fields.site") }}</th>
                                                <td>
                                                    @if ($wifiTerminal->site!=null)
                                                        <a href="#SITE{{ $wifiTerminal->site->id }}">{{ $wifiTerminal->site->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.wifiTerminal.fields.building") }}</th>
                                                <td>
                                                    @if ($wifiTerminal->building!=null)
                                                        <a href="#BUILDING{{ $wifiTerminal->building->id }}">{{ $wifiTerminal->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_security_device_access')
                @if ($physicalSecurityDevices->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalSecurityDevice.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalSecurityDevice.description") }}</p>
                            @foreach($physicalSecurityDevices as $physicalSecurityDevice)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-hover"
                                               style="max-width: 800px; width: 100%;">
                                            <thead id="PSD{{ $physicalSecurityDevice->id }}">
                                            <th colspan="2">
                                                <a href="/admin/physical-security-devices/{{ $physicalSecurityDevice->id }}">{{ $physicalSecurityDevice->name }}</a>
                                            </th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th width="20%">{{ trans("cruds.physicalSecurityDevice.fields.description") }}</th>
                                                <td>{!! $physicalSecurityDevice->description !!}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSecurityDevice.fields.type") }}</th>
                                                <td>{{ $physicalSecurityDevice->type }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSecurityDevice.fields.site") }}</th>
                                                <td>
                                                    @if ($physicalSecurityDevice->site!=null)
                                                        <a href="#SITE{{ $physicalSecurityDevice->site->id }}">{{ $physicalSecurityDevice->site->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSecurityDevice.fields.building") }}</th>
                                                <td>
                                                    @if ($physicalSecurityDevice->building!=null)
                                                        <a href="#BUILDING{{ $physicalSecurityDevice->building->id }}">{{ $physicalSecurityDevice->building->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ trans("cruds.physicalSecurityDevice.fields.bay") }}</th>
                                                <td>
                                                    @if ($physicalSecurityDevice->bay!=null)
                                                        <a href="#BAY{{ $physicalSecurityDevice->bay->id }}">{{ $physicalSecurityDevice->bay->name }}</a>
                                                        <br>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_link_access')
                @if ($physicalLinks->count()>0)
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalLink.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalLink.description") }}</p>

                            <div class="row">
                                <div class="col-sm-6">

                                    <table class="table table-bordered table-striped table-hover"
                                           style="max-width: 800px; width: 100%;">
                                        <thead>
                                        <th></th>
                                        <th width='40%'>
                                            {{ trans('cruds.physicalLink.fields.src') }}
                                        </th>
                                        <th width='10%'>
                                            {{ trans('cruds.physicalLink.fields.src_port') }}
                                        </th>
                                        <th width='40%'>
                                            {{ trans('cruds.physicalLink.fields.dest') }}
                                        </th>
                                        <th width='10%'>
                                            {{ trans('cruds.physicalLink.fields.dest_port') }}
                                        </th>
                                        </thead>

                                        @foreach($physicalLinks as $physicalLink)
                                            <tr data-entry-id="{{ $physicalLink->id }}">
                                                <td>
                                                    <a href="/admin/links/{{ $physicalLink->id }}">&#9741;</a>
                                                </td>
                                                <td>
                                                    @if ($physicalLink->peripheralSrc!=null)
                                                        <a href="{{ route('admin.peripherals.show', $physicalLink->peripheral_src_id) }}">
                                                            {{ $physicalLink->peripheralSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->phoneSrc!=null)
                                                        <a href="{{ route('admin.phones.show', $physicalLink->phone_src_id) }}">
                                                            {{ $physicalLink->phoneSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalRouterSrc!=null)
                                                        <a href="{{ route('admin.physical-routers.show', $physicalLink->physical_router_src_id) }}">
                                                            {{ $physicalLink->physicalRouterSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalSecurityDeviceSrc!=null)
                                                        <a href="{{ route('admin.physical-security-devices.show', $physicalLink->physical_security_device_src_id) }}">
                                                            {{ $physicalLink->physicalSecurityDeviceSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalServerSrc!=null)
                                                        <a href="{{ route('admin.physical-servers.show', $physicalLink->physical_server_src_id) }}">
                                                            {{ $physicalLink->physicalServerSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalSwitchSrc!=null)
                                                        <a href="{{ route('admin.physical-switches.show', $physicalLink->physical_switch_src_id) }}">
                                                            {{ $physicalLink->physicalSwitchSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->storageDeviceSrc!=null)
                                                        <a href="{{ route('admin.storage-devices.show', $physicalLink->storage_device_src_id) }}">
                                                            {{ $physicalLink->storageDeviceSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->wifiTerminalSrc!=null)
                                                        <a href="{{ route('admin.wifi-terminals.show', $physicalLink->wifi_terminal_src_id) }}">
                                                            {{ $physicalLink->wifiTerminalSrc->name }}
                                                        </a>
                                                    @elseif ($physicalLink->workstationSrc!=null)
                                                        <a href="{{ route('admin.workstations.show', $physicalLink->workstation_src_id) }}">
                                                            {{ $physicalLink->workstationSrc->name }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $physicalLink->src_port }}
                                                </td>
                                                <td>
                                                    @if ($physicalLink->peripheralDest!=null)
                                                        <a href="{{ route('admin.peripherals.show', $physicalLink->peripheral_dest_id) }}">
                                                            {{ $physicalLink->peripheralDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->phoneDest!=null)
                                                        <a href="{{ route('admin.phones.show', $physicalLink->phone_dest_id) }}">
                                                            {{ $physicalLink->phoneDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalRouterDest!=null)
                                                        <a href="{{ route('admin.physical-routers.show', $physicalLink->physical_router_dest_id) }}">
                                                            {{ $physicalLink->physicalRouterDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalSecurityDeviceDest!=null)
                                                        <a href="{{ route('admin.physical-security-devices.show', $physicalLink->physical_security_device_dest_id) }}">
                                                            {{ $physicalLink->physicalSecurityDeviceDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalServerDest!=null)
                                                        <a href="{{ route('admin.physical-servers.show', $physicalLink->physical_server_dest_id) }}">
                                                            {{ $physicalLink->physicalServerDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->physicalSwitchDest!=null)
                                                        <a href="{{ route('admin.physical-switches.show', $physicalLink->physical_switch_dest_id) }}">
                                                            {{ $physicalLink->physicalSwitchDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->storageDeviceDest!=null)
                                                        <a href="{{ route('admin.storage-devices.show', $physicalLink->storage_device_dest_id) }}">
                                                            {{ $physicalLink->storageDeviceDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->wifiTerminalDest!=null)
                                                        <a href="{{ route('admin.wifi-terminals.show', $physicalLink->wifi_terminal_dest_id) }}">
                                                            {{ $physicalLink->wifiTerminalDest->name }}
                                                        </a>
                                                    @elseif ($physicalLink->workstationDest!=null)
                                                        <a href="{{ route('admin.workstations.show', $physicalLink->workstation_dest_id) }}">
                                                            {{ $physicalLink->workstationDest->name }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $physicalLink->dest_port }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/d3-viz.js'])
    <script>
            <?php
            // Lighter colors
            $tableau20 = array(
                "#99cbed", "#dfe9f6", "#ffcc9f", "#ffe4c9",
                "#9fe59f", "#d6f2d0", "#efa8a8", "#ffd6d5",
                "#d4c2e5", "#e8dfee", "#d3b9d6", "#e7d7d4",
                "#f4c9e7", "#fce2ed", "#cccccc", "#e9e9e9",
                "#eded9e", "#f1f1d1", "#9aecf4", "#d8f0f5");
            $idColor = 0;
            ?>

        let dotSrc = `
digraph  {
    fontcolor=black;

    @foreach($sites as $site)
        subgraph SITE_{{ $site->id }}  {
            cluster=true;
            label = "{{ $site->name }}"
            bgcolor="{{ $tableau20[$idColor++ % 20] }}"

        @foreach($buildings as $building)
        @if ($building->site_id === $site->id)

        subgraph ROOM_{{ $building->id }} {
            cluster=true;
            label="{{ $building->name }}"
            bgcolor="{{ $tableau20[$idColor++ % 20] }}"

        @foreach($building->phones as $phone)
        PHONE{{ $phone->id }} [label="{{ $phone->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/phone.png" href="#PHONE{{$phone->id}}"]
        @endforeach

        @foreach($building->workstations as $workstation)
        WORK{{ $workstation->id }} [label="{{ $workstation->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/workstation.png" href="#WORKSTATION{{$workstation->id}}"]
        @endforeach

        @foreach($building->wifiTerminals as $wifiTerminal)
        WIFI{{ $wifiTerminal->id }} [label="{{ $wifiTerminal->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/wifi.png" href="#WIFI{{$wifiTerminal->id}}"]
        @endforeach

        @foreach($building->physicalSwitches as $switch)
        @if ($switch->bay_id===null)
        SWITCH{{ $switch->id }} [label="{{ $switch->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/switch.png" href="#SWITCH{{$switch->id}}"]
        @endif
        @endforeach

        @foreach($building->physicalRouters as $router)
        @if ($router->bay_id===null)
        ROUTER{{ $router->id }} [label="{{ $router->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/router.png" href="#ROUTER{{$router->id}}"]
        @endif
        @endforeach

        @foreach($building->peripherals as $peripheral)
        @if ($peripheral->bay_id===null)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/peripheral.png" href="#PERIPHERAL{{$peripheral->id}}"]
        @endif
        @endforeach

        @foreach($building->roomBays as $bay)
        subgraph BAY_{{ $bay->id }} {
                cluster=true;
                label="{{ $bay->name }}"
                bgcolor="{{ $tableau20[$idColor++ % 20] }}"

        @foreach($bay->physicalServers as $pServer)
        PSERVER{{ $pServer->id }} [label="{{ $pServer->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/server.png" href="#PSERVER{{$pServer->id}}"]
        @endforeach

        @foreach($bay->storageDevices as $storageDevice)
        SD{{ $storageDevice->id }} [label="{{ $storageDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/storage.png" href="#STORAGEDEVICE{{$storageDevice->id}}"]
        @endforeach

        @foreach($bay->physicalSwitches as $switch)
        SWITCH{{ $switch->id }} [label="{{ $switch->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/switch.png" href="#SWITCH{{$switch->id}}"]
        @endforeach

        @foreach($bay->physicalSecurityDevices as $physicalSecurityDevice)
        PSD{{ $physicalSecurityDevice->id }} [label="{{ $physicalSecurityDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/security.png" href="#PSD{{$physicalSecurityDevice->id}}"]
        @endforeach

        @foreach($bay->physicalRouters as $router)
        ROUTER{{ $router->id }} [label="{{ $router->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/router.png" href="#ROUTER{{$router->id}}"]
        @endforeach

        @foreach($bay->peripherals as $peripheral)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/peripheral.png" href="#PERIPHERAL{{$peripheral->id}}"]
        @endforeach
        }
@endforeach
        }
@endif
        @endforeach
        }
    @endforeach

        @foreach($physicalLinks as $link)

        @if (
        // Virtual objects
            ($link->router_src_id === null) &&
            ($link->router_dest_id === null) &&
            ($link->logical_server_src_id === null) &&
            ($link->logical_server_dest_id === null) &&
            ($link->network_switch_src_id === null) &&
            ($link->network_switch_dest_id === null) &&

        // Physical Objects
            (($link->peripheral_src_id === null) || ($peripherals->contains("id",$link->peripheral_src_id))) &&
            (($link->peripheral_dest_id === null) || ($peripherals->contains("id",$link->peripheral_dest_id))) &&

            (($link->physical_router_src_id === null) || ($physicalRouters->contains("id",$link->physical_router_src_id))) &&
            (($link->physical_router_dest_id === null) || ($physicalRouters->contains("id",$link->physical_router_dest_id))) &&

            (($link->phone_src_id === null) || ($phones->contains("id",$link->phone_src_id))) &&
            (($link->phone_dest_id === null) || ($phones->contains("id",$link->phone_dest_id))) &&

            (($link->physical_security_device_src_id === null) || ($physicalSecurityDevices->contains("id",$link->physical_security_device_src_id))) &&
            (($link->physical_security_device_dest_id === null) || ($physicalSecurityDevices->contains("id",$link->physical_security_device_dest_id))) &&

            (($link->physical_server_src_id === null) || ($physicalServers->contains("id",$link->physical_server_src_id))) &&
            (($link->physical_server_dest_id === null) || ($physicalServers->contains("id",$link->physical_server_dest_id))) &&

            (($link->physical_switch_src_id === null) || ($physicalSwitches->contains("id",$link->physical_switch_src_id))) &&
            (($link->physical_switch_dest_id === null) || ($physicalSwitches->contains("id",$link->physical_switch_dest_id))) &&

            (($link->storage_device_src_id === null) || ($storageDevices->contains("id",$link->storage_device_src_id))) &&
            (($link->storage_device_dest_id === null) || ($storageDevices->contains("id",$link->storage_device_dest_id))) &&

            (($link->wifi_terminal_src_id === null) || ($wifiTerminals->contains("id",$link->wifi_terminal_src_id))) &&
            (($link->wifi_terminal_dest_id === null) || ($wifiTerminals->contains("id",$link->wifi_terminal_dest_id))) &&

            (($link->workstation_src_id === null) || ($workstations->contains("id",$link->workstation_src_id))) &&
            (($link->workstation_dest_id === null) || ($workstations->contains("id",$link->workstation_dest_id)))
            )

        @if($link->peripheral_src_id!=null)
        PER{{$link->peripheral_src_id }}
        @elseif($link->physical_router_src_id!=null)
        ROUTER{{$link->physical_router_src_id}}
        @elseif($link->phone_src_id!=null)
        PHONE{{$link->phone_src_id}}
        @elseif($link->physical_security_device_src_id!=null)
        PSD{{$link->physical_security_device_src_id}}
        @elseif($link->physical_server_src_id!=null)
        PSERVER{{$link->physical_server_src_id}}
        @elseif($link->physical_switch_src_id!=null)
        SWITCH{{$link->physical_switch_src_id}}
        @elseif($link->storage_device_src_id!=null)
        SD{{$link->storage_device_src_id}}
        @elseif($link->wifi_terminal_src_id!=null)
        WIFI{{$link->wifi_terminal_src_id}}
        @elseif($link->workstation_src_id!=null)
        WORK{{$link->workstation_src_id}}
        @endif
        ->
        @if($link->peripheral_dest_id!=null)
        PER{{$link->peripheral_dest_id}}
        @elseif($link->physical_router_dest_id!=null)
        ROUTER{{$link->physical_router_dest_id}}
        @elseif($link->phone_dest_id!=null)
        PHONE{{$link->phone_dest_id}}
        @elseif($link->physical_security_device_dest_id!=null)
        PSD{{$link->physical_security_device_dest_id}}
        @elseif($link->physical_server_dest_id!=null)
        PSERVER{{$link->physical_server_dest_id}}
        @elseif($link->physical_switch_dest_id!=null)
        SWITCH{{$link->physical_switch_dest_id}}
        @elseif($link->storage_device_dest_id!=null)
        SD{{$link->storage_device_dest_id}}
        @elseif($link->wifi_terminal_dest_id!=null)
        WIFI{{$link->wifi_terminal_dest_id}}
        @elseif($link->workstation_dest_id!=null)
        WORK{{$link->workstation_dest_id}}
        @endif
        [arrowhead=none,taillabel="{{$link->src_port}}", headlabel="{{$link->dest_port}}", href="{{ route('admin.links.show', $link->id) }}"];
        @endif
        @endforeach

        }`;

        document.addEventListener("DOMContentLoaded", function () {
            $('#site').on('change', function () {
                if (this.form.building)
                    this.form.building.value = '';
                this.form.submit();
            });

            $('#buildings').on('change', function () {
                this.form.submit();
            });

            d3.select("#graph").graphviz()
                .addImage("/images/site.png", "64px", "64px")
                .addImage("/images/building.png", "64px", "64px")
                .addImage("/images/bay.png", "64px", "64px")
                .addImage("/images/server.png", "64px", "64px")
                .addImage("/images/workstation.png", "64px", "64px")
                @foreach($workstations as $workstation)
                @if ($workstation->icon_id!==null)
                .addImage("{{ route('admin.documents.show', $workstation->icon_id) }}", "64px", "64px")
                @endif
                @endforeach
                .addImage("/images/storage.png", "64px", "64px")
                .addImage("/images/peripheral.png", "64px", "64px")
                @foreach($peripherals as $peripheral)
                @if ($peripheral->icon_id!==null)
                .addImage("{{ route('admin.documents.show', $peripheral->icon_id) }}", "64px", "64px")
                @endif
                @endforeach
                .addImage("/images/phone.png", "64px", "64px")
                .addImage("/images/switch.png", "64px", "64px")
                .addImage("/images/router.png", "64px", "64px")
                .addImage("/images/wifi.png", "64px", "64px")
                .addImage("/images/security.png", "64px", "64px")
                .engine("{{ $engine }}")
                .renderDot(dotSrc);
        });

    </script>
    @parent
@endsection
