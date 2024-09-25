@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.menu.physical_infrastructure.title") }}
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <div class="col-sm-4">
                    <form action="/admin/report/physical_infrastructure">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>
                                    {{ trans("cruds.site.title_singular") }} :
                                    <select name="site" onchange="this.form.building.value='';this.form.submit()">
                                        <option value="">-- All sites --</option>
                                        @foreach($all_sites as $id => $name)
                                            <option value="{{$id}}" {{ Session::get('site')==$id ? "selected" : "" }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ trans("cruds.building.title_singular") }} :
                                    <select name="building" onchange="this.form.submit()">
                                        <option value="">-- All buildings --</option>
                                        @if ($all_buildings!=null)
                                            @foreach($all_buildings as $id => $name)
                                                <option value="{{$id}}" {{ Session::get('building')==$id ? "selected" : "" }}>{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="graph"></div>
            </div>
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
                            <table class="table table-bordered table-striped table-hover">
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
                            <table id="BUILDING{{ $building->id }}" class="table table-bordered table-striped table-hover">
                                <thead >
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
                            <table class="table table-bordered table-striped table-hover">
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
                                @if ($bay->bayPhysicalServers->count()>0)
                                <tr>
                                    <th>{{ trans("cruds.bay.fields.physical_servers") }}</th>
                                    <td>
                                    @foreach($bay->bayPhysicalServers as $physicalServer)
                                        <a href="#PSERVER{{$physicalServer->id}}">{{ $physicalServer->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ($bay->bayPhysicalRouters->count()>0)
                                <tr>
                                    <th>{{ trans("cruds.bay.fields.physical_routers") }}</th>
                                    <td>
                                    @foreach($bay->bayPhysicalRouters as $physicalRouter)
                                        <a href="#ROUTER{{$physicalRouter->id}}">{{ $physicalRouter->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                    </td>
                                </tr>
                                @endif

                                @if ($bay->bayPhysicalSwitches->count()>0)
                                <tr>
                                    <th>{{ trans("cruds.bay.fields.physical_switches") }}</th>
                                    <td>
                                    @foreach($bay->bayPhysicalSwitches as $bayPhysicalSwitch)
                                        <a href="#SWITCH{{$bayPhysicalSwitch->id}}">{{ $bayPhysicalSwitch->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                    </td>
                                </tr>
                                @endif

                                @if ($bay->bayStorageDevices->count()>0)
                                <tr>
                                    <th>{{ trans("cruds.bay.fields.storage_devices") }}</th>
                                    <td>
                                    @foreach($bay->bayStorageDevices as $bayStorageDevice)
                                        <a href="#STORAGEDEVICE{{$bayStorageDevice->id}}">{{ $bayStorageDevice->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                    </td>
                                </tr>
                                @endif

                                @if ($bay->bayPhysicalSecurityDevices->count()>0)
                                <tr>
                                    <th>{{ trans("cruds.bay.fields.physical_security_devices") }}</th>
                                    <td>
                                    @foreach($bay->bayPhysicalSecurityDevices as $physicalSecurityDevice)
                                        <a href="#PSD{{$physicalSecurityDevice->id}}">{{ $physicalSecurityDevice->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                    </td>
                                </tr>
                                @endif

                                @if ($bay->bayPeripherals->count()>0)
                                <tr>
                                    <th>{{ trans("cruds.bay.fields.peripherals") }}</th>
                                    <td>
                                    @foreach($bay->bayPeripherals as $peripheral)
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
                            <table class="table table-bordered table-striped table-hover">
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
                                        @foreach($pserver->serversLogicalServers as $logicalServer)
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
            @if ($workstations->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.workstation.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.workstation.description") }}</p>
                      @foreach($workstations as $workstation)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
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
            @if ((auth()->user()->granularity>=2)&&($storageDevices->count()>0))
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.storageDevice.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.storageDevice.description") }}</p>
                      @foreach($storageDevices as $storageDevice)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#BUILDING{{ $storageDevice->building->id }}">{{ $storageDevice->building->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.storageDevice.fields.bay") }}</th>
                                    <td>
                                        @if ($storageDevice->bay!=null)
                                            <a href="#BAY{{ $storageDevice->bay->id }}">{{ $storageDevice->bay->name }}</a><br>
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
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#SITE{{ $peripheral->site->id }}">{{ $peripheral->site->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.peripheral.fields.building") }}</th>
                                    <td>
                                        @if ($peripheral->building!=null)
                                            <a href="#BUILDING{{ $peripheral->building->id }}">{{ $peripheral->building->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.peripheral.fields.bay") }}</th>
                                    <td>
                                        @if ($peripheral->bay!=null)
                                            <a href="#BAY{{ $peripheral->bay->id }}">{{ $peripheral->bay->name }}</a><br>
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
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#SITE{{ $phone->site->id }}">{{ $phone->site->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.phone.fields.building") }}</th>
                                    <td>
                                        @if ($phone->building!=null)
                                            <a href="#BUILDING{{ $phone->building->id }}">{{ $phone->building->name }}</a><br>
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
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#SITE{{ $switch->site->id }}">{{ $switch->site->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.physicalSwitch.fields.building") }}</th>
                                    <td>
                                        @if ($switch->building!=null)
                                            <a href="#BUILDING{{ $switch->building->id }}">{{ $switch->building->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.physicalSwitch.fields.bay") }}</th>
                                    <td>
                                        @if ($switch->bay!=null)
                                            <a href="#BAY{{ $switch->bay->id }}">{{ $switch->bay->name }}</a><br>
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
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#SITE{{ $router->site->id }}">{{ $router->site->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.physicalRouter.fields.building") }}</th>
                                    <td>
                                        @if ($router->building!=null)
                                            <a href="#BUILDING{{ $router->building->id }}">{{ $router->building->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.physicalRouter.fields.bay") }}</th>
                                    <td>
                                        @if ($router->bay!=null)
                                            <a href="#BAY{{ $router->bay->id }}">{{ $router->bay->name }}</a><br>
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
            @if ((auth()->user()->granularity>=2)&&($wifiTerminals->count()>0))
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.wifiTerminal.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.wifiTerminal.description") }}</p>
                      @foreach($wifiTerminals as $wifiTerminal)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#SITE{{ $wifiTerminal->site->id }}">{{ $wifiTerminal->site->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.wifiTerminal.fields.building") }}</th>
                                    <td>
                                        @if ($wifiTerminal->building!=null)
                                            <a href="#BUILDING{{ $wifiTerminal->building->id }}">{{ $wifiTerminal->building->name }}</a><br>
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
                            <table class="table table-bordered table-striped table-hover">
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
                                            <a href="#SITE{{ $physicalSecurityDevice->site->id }}">{{ $physicalSecurityDevice->site->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.physicalSecurityDevice.fields.building") }}</th>
                                    <td>
                                        @if ($physicalSecurityDevice->building!=null)
                                            <a href="#BUILDING{{ $physicalSecurityDevice->building->id }}">{{ $physicalSecurityDevice->building->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.physicalSecurityDevice.fields.bay") }}</th>
                                    <td>
                                        @if ($physicalSecurityDevice->bay!=null)
                                            <a href="#BAY{{ $physicalSecurityDevice->bay->id }}">{{ $physicalSecurityDevice->bay->name }}</a><br>
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- //d3js.org/d3.v5.min.js -->
<script src="/js/d3.v5.min.js"></script>
<!-- https://unpkg.com/@hpcc-js/wasm@0.3.11/dist/index.min.js -->
<script src="/js/index.min.js"></script>
<!-- https://unpkg.com/d3-graphviz@3.0.5/build/d3-graphviz.js -->
<script src="/js/d3-graphviz.js"></script>

<script>
let dotSrc=`
digraph  {
    @can('site_access')
    @foreach($sites as $site)
        S{{ $site->id }} [label="{{ $site->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $site->icon_id === null ? '/images/$site.png' : route('admin.documents.show', $site->icon_id) }}" href="#SITE{{$site->id}}"]
    @endforEach
    @endcan
    @can('building_access')
    @foreach($buildings as $building)
        B{{ $building->id }} [label="{{ $building->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/building.png" href="#BUILDING{{$building->id}}"]
        S{{ $building->site_id }} -> B{{ $building->id }}
        @foreach($building->roomBays as $bay)
            B{{ $building->id }} -> BAY{{ $bay->id }}
        @endforeach
        @can('workstation_access')
            @if ($building->buildingWorkstations()->count()>=5)
                WG{{ $building->buildingWorkstations()->first()->id }} [label="{{ $building->buildingWorkstations()->count() }} {{ trans("cruds.workstation.title")}}" shape=none labelloc="b"  width=1 height=1.1 image="/images/workstation.png" href="#WORKSTATION{{$workstation->id}}"]
                B{{ $building->id }} -> WG{{ $building->buildingWorkstations()->first()->id }}
            @else
                @foreach($building->buildingWorkstations as $workstation)
                   W{{ $workstation->id }} [label="{{ $workstation->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/workstation.png" href="#WORKSTATION{{$workstation->id}}"]
                   B{{ $building->id }} -> W{{ $workstation->id }}
                @endforEach
            @endif
        @endcan
    @endforEach
    @endcan
    @can('bay_access')
    @foreach($bays as $bay)
        BAY{{ $bay->id }} [label="{{ $bay->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/bay.png" href="#BAY{{$bay->id}}"]
    @endforeach
    @endcan
    @can('physical_server_access')
    @foreach($physicalServers as $pServer)
        PSERVER{{ $pServer->id }} [label="{{ $pServer->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/server.png" href="#PSERVER{{$pServer->id}}"]
        @if ($pServer->bay!=null)
             BAY{{ $pServer->bay->id }} -> PSERVER{{ $pServer->id }}
        @elseif ($pServer->building!=null)
             B{{ $pServer->building->id }} -> PSERVER{{ $pServer->id }}
        @elseif ($pServer->site!=null)
             S{{ $pServer->site->id }} -> PSERVER{{ $pServer->id }}
        @endif
    @endforeach
    @endcan
    @can('storage_device_access')
    @foreach($storageDevices as $storageDevice)
        SD{{ $storageDevice->id }} [label="{{ $storageDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/storage.png" href="#STORAGEDEVICE{{$storageDevice->id}}"]
        @if ($storageDevice->bay!=null)
             BAY{{ $storageDevice->bay->id }} -> SD{{ $storageDevice->id }}
        @elseif ($storageDevice->building!=null)
             B{{ $storageDevice->building->id }} -> SD{{ $storageDevice->id }}
        @elseif ($storageDevice->site!=null)
             S{{ $storageDevice->site->id }} -> SD{{ $storageDevice->id }}
        @endif
    @endforeach
    @endcan
    @can('peripheral_access')
    @foreach($peripherals as $peripheral)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $peripheral->icon_id === null ? '/images/$peripheral.png' : route('admin.documents.show', $peripheral->icon_id) }}" href="#PERIPHERAL{{$peripheral->id}}"]
        @if ($peripheral->bay!=null)
             BAY{{ $peripheral->bay->id }} -> PER{{ $peripheral->id }}
        @elseif ($peripheral->building!=null)
             B{{ $peripheral->building->id }} -> PER{{ $peripheral->id }}
        @elseif ($peripheral->site!=null)
             S{{ $peripheral->site->id }} -> PER{{ $peripheral->id }}
        @endif
    @endforeach
    @endcan
    @can('phone_access')
    @foreach($phones as $phone)
        PHONE{{ $phone->id }} [label="{{ $phone->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/phone.png" href="#PHONE{{$phone->id}}"]
        @if ($phone->building!=null)
             B{{ $phone->building->id }} -> PHONE{{ $phone->id }}
        @elseif ($phone->site!=null)
             S{{ $phone->site->id }} -> PHONE{{ $phone->id }}
        @endif
    @endforeach
    @endcan
    @can('physical_switch_access')
    @foreach($physicalSwitches as $switch)
        SWITCH{{ $switch->id }} [label="{{ $switch->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/switch.png" href="#SWITCH{{$switch->id}}"]
        @if ($switch->bay!=null)
             BAY{{ $switch->bay->id }} -> SWITCH{{ $switch->id }}
        @elseif ($switch->building!=null)
             B{{ $switch->building->id }} -> SWITCH{{ $switch->id }}
        @elseif ($switch->site!=null)
             S{{ $switch->site->id }} -> SWITCH{{ $switch->id }}
        @endif
    @endforeach
    @endcan
    @can('physical_router_access')
    @foreach($physicalRouters as $router)
        ROUTER{{ $router->id }} [label="{{ $router->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/router.png" href="#ROUTER{{$router->id}}"]
        @if ($router->bay!=null)
             BAY{{ $router->bay->id }} -> ROUTER{{ $router->id }}
        @elseif ($router->building!=null)
             B{{ $router->building->id }} -> ROUTER{{ $router->id }}
        @elseif ($router->site!=null)
             S{{ $router->site->id }} -> ROUTER{{ $router->id }}
        @endif
    @endforeach
    @endcan
    @can('wifi_terminal_access')
    @foreach($wifiTerminals as $wifiTerminal)
        WIFI{{ $wifiTerminal->id }} [label="{{ $wifiTerminal->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/wifi.png" href="#WIFI{{$wifiTerminal->id}}"]
        @if ($wifiTerminal->building!=null)
             B{{ $wifiTerminal->building->id }} -> WIFI{{ $wifiTerminal->id }}
        @elseif ($wifiTerminal->site!=null)
             S{{ $wifiTerminal->site->id }} -> WIFI{{ $wifiTerminal->id }}
        @endif
    @endforeach
    @endcan
    @can('physical_security_device_access')
    @foreach($physicalSecurityDevices as $physicalSecurityDevice)
        PSD{{ $physicalSecurityDevice->id }} [label="{{ $physicalSecurityDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/security.png" href="#PSD{{$physicalSecurityDevice->id}}"]
        @if ($physicalSecurityDevice->bay!=null)
             BAY{{ $physicalSecurityDevice->bay->id }} -> PSD{{ $physicalSecurityDevice->id }}
        @elseif ($physicalSecurityDevice->building!=null)
             B{{ $physicalSecurityDevice->building->id }} -> PSD{{ $physicalSecurityDevice->id }}
        @elseif ($physicalSecurityDevice->site!=null)
             S{{ $physicalSecurityDevice->site->id }} -> PSD{{ $physicalSecurityDevice->id }}
        @endif
    @endforeach
    @endcan
}`;

d3.select("#graph").graphviz()
    .addImage("/images/site.png", "64px", "64px")
    @foreach($sites as $site)
       @if ($site->icon_id!==null)
       .addImage("{{ route('admin.documents.show', $site->icon_id) }}", "64px", "64px")
       @endif
    @endforeach
    .addImage("/images/building.png", "64px", "64px")
    .addImage("/images/bay.png", "64px", "64px")
    .addImage("/images/server.png", "64px", "64px")
    .addImage("/images/workstation.png", "64px", "64px")
    .addImage("/images/storage.png", "64px", "64px")
    .addImage("/images/peripheral.png", "64px", "64px")
    .addImage("/images/phone.png", "64px", "64px")
    .addImage("/images/switch.png", "64px", "64px")
    .addImage("/images/router.png", "64px", "64px")
    .addImage("/images/wifi.png", "64px", "64px")
    .addImage("/images/security.png", "64px", "64px")
    @foreach($peripherals as $peripheral)
       @if ($peripheral->icon_id!==null)
       .addImage("{{ route('admin.documents.show', $peripheral->icon_id) }}", "64px", "64px")
       @endif
    @endforeach
    .renderDot(dotSrc);
</script>
@parent
@endsection
