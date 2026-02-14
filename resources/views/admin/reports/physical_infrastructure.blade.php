@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.menu.physical_infrastructure.title") }}
                </div>
                <form action="/admin/report/physical_infrastructure">

                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-sm-4">
                            <table class="table table-bordered table-striped"
                                   style="max-width: 600px; width: 100%;">
                                <tr>
                                    <td style="width: 50%;">
                                        {{ trans("cruds.site.title_singular") }} :
                                        <select name="site" id="site"
                                                onchange="this.form.building.value='';this.form.submit()"
                                                class="form-control select2">
                                            <option value="">-- All sites --</option>
                                            @foreach($all_sites as $id => $name)
                                                <option value="{{$id}}" {{ Session::get('site')==$id ? "selected" : "" }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="width: 50%;">
                                        {{ trans("cruds.building.title_singular") }} :
                                        <select name="building" id="building" onchange="this.form.submit()"
                                                class="form-control select2">
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
                        </div>
                        <div id="graph-container">
                            <div id="graph" class="graphviz"></div>
                            <div class="graph-resize-handle"></div>
                        </div>

                        <div class="row p-1">
                            <div class="col-4">
                                @php
                                    $engines = ["dot", "fdp", "osage", "circo"];
                                    $engine = request()->get('engine', 'dot');
                                @endphp

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
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.site.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.site.description") }}</p>
                            @foreach($sites as $site)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.sites._details', [
                                            'site' => $site,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
            @endcan

            @can('building_access')
                @if ($buildings->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.building.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.building.description") }}</p>
                            @foreach($buildings as $building)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.buildings._details', [
                                            'building' => $building,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('bay_access')
                @if ($bays->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.bay.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.bay.description") }}</p>
                            @foreach($bays as $bay)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.bays._details', [
                                            'bay' => $bay,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_server_access')
                @if ($physicalServers->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalServer.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalServer.description") }}</p>
                            @foreach($physicalServers as $physicalServer)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.physicalServers._details', [
                                            'physicalServer' => $physicalServer,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('workstation_access')
                @if ($workstations->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.workstation.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.workstation.description") }}</p>
                            @foreach($workstations as $workstation)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.workstations._details', [
                                            'workstation' => $workstation,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('storage_device_access')
                @if ($storageDevices->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.storageDevice.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.storageDevice.description") }}</p>
                            @foreach($storageDevices as $storageDevice)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.storageDevices._details', [
                                            'storageDevice' => $storageDevice,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('peripheral_access')
                @if ($peripherals->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.peripheral.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.peripheral.description") }}</p>
                            @foreach($peripherals as $peripheral)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.peripherals._details', [
                                            'peripheral' => $peripheral,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('phone_access')
                @if ($phones->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.phone.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.phone.description") }}</p>
                            @foreach($phones as $phone)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.phones._details', [
                                            'phone' => $phone,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_switch_access')
                @if ($physicalSwitches->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalSwitch.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalSwitch.description") }}</p>
                            @foreach($physicalSwitches as $physicalSwitch)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.physicalSwitches._details', [
                                            'physicalSwitch' => $physicalSwitch,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_router_access')
                @if ($physicalRouters->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalRouter.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalRouter.description") }}</p>
                            @foreach($physicalRouters as $physicalRouter)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.physicalRouters._details', [
                                            'physicalRouter' => $physicalRouter,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('wifi_terminal_access')
                @if ($wifiTerminals->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.wifiTerminal.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.wifiTerminal.description") }}</p>
                            @foreach($wifiTerminals as $wifiTerminal)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.wifiTerminals._details', [
                                            'wifiTerminal' => $wifiTerminal,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('physical_security_device_access')
                @if ($physicalSecurityDevices->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.physicalSecurityDevice.title") }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans("cruds.physicalSecurityDevice.description") }}</p>
                            @foreach($physicalSecurityDevices as $physicalSecurityDevice)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.physicalSecurityDevices._details', [
                                            'physicalSecurityDevice' => $physicalSecurityDevice,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
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
let dotSrc = `
digraph  {
@can('site_access')
@if (!Session::get('building'))
@foreach($sites as $site)
S{{ $site->id }} [label="{{ $site->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $site->icon_id === null ? '/images/site.png' : route('admin.documents.show', $site->icon_id) }}" href="#{{$site->getUID()}}"]
@endforEach
@endif
@endcan
@can('building_access')
@foreach($buildings as $building)
B{{ $building->id }} [label="{{ $building->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $building->icon_id === null ? '/images/building.png' : route('admin.documents.show', $building->icon_id) }}" href="#{{$building->getUID()}}"]
@if ($building->building_id!==null)
@if ($buildings->contains('id', $building->building_id))
B{{ $building->building_id }} -> B{{ $building->id }}
@endif
@elseif ((!Session::get('building')) && ($building->site_id!==null))
S{{ $building->site_id }} -> B{{ $building->id }}
@endif
@foreach($building->roomBays as $bay)
B{{ $building->id }} -> BAY{{ $bay->id }}
@endforeach
@can('workstation_access')
@if ($building->workstations()->count()>=5)
WG{{ $building->workstations()->first()->id }} [label="{{ $building->workstations()->count() }} {{ trans("cruds.workstation.title")}}" shape=none labelloc="b"  width=1 height=1.1 image="/images/workstation.png" href="#{{$workstation->getUID()}}"]
B{{ $building->id }} -> WG{{ $building->workstations()->first()->id }}
@else
@foreach($building->workstations as $workstation)
W{{ $workstation->id }} [label="{{ $workstation->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $workstation->icon_id === null ? '/images/workstation.png' : route('admin.documents.show', $workstation->icon_id) }}" href="#{{$workstation->getUID()}}"]
B{{ $building->id }} -> W{{ $workstation->id }}
@endforEach
@endif
@endcan
@endforEach
@endcan
@can('bay_access')
@foreach($bays as $bay)
BAY{{ $bay->id }} [label="{{ $bay->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/bay.png" href="#{{$bay->getUID()}}"]
@endforeach
@endcan
@can('physical_server_access')
@foreach($physicalServers as $pServer)
PSERVER{{ $pServer->id }} [label="{{ $pServer->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/server.png" href="#{{$pServer->getUID()}}"]
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
SD{{ $storageDevice->id }} [label="{{ $storageDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/storage.png" href="#{{$storageDevice->getUID()}}"]
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
PER{{ $peripheral->id }} [label="{{ $peripheral->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $peripheral->icon_id === null ? '/images/peripheral.png' : route('admin.documents.show', $peripheral->icon_id) }}" href="#{{$peripheral->getUID()}}"]
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
PHONE{{ $phone->id }} [label="{{ $phone->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/phone.png" href="#{{$phone->getUID()}}"]
@if ($phone->building!=null)
B{{ $phone->building->id }} -> PHONE{{ $phone->id }}
@elseif ($phone->site!=null)
S{{ $phone->site->id }} -> PHONE{{ $phone->id }}
@endif
@endforeach
@endcan
@can('physical_switch_access')
@foreach($physicalSwitches as $switch)
SWITCH{{ $switch->id }} [label="{{ $switch->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/switch.png" href="#{{$switch->getUID()}}"]
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
ROUTER{{ $router->id }} [label="{{ $router->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/router.png" href="#{{$router->getUID()}}"]
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
WIFI{{ $wifiTerminal->id }} [label="{{ $wifiTerminal->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/wifi.png" href="#{{$wifiTerminal->getUID()}}"]
@if ($wifiTerminal->building!=null)
B{{ $wifiTerminal->building->id }} -> WIFI{{ $wifiTerminal->id }}
@elseif ($wifiTerminal->site!=null)
S{{ $wifiTerminal->site->id }} -> WIFI{{ $wifiTerminal->id }}
@endif
@endforeach
@endcan
@can('physical_security_device_access')
@foreach($physicalSecurityDevices as $physicalSecurityDevice)
PSD{{ $physicalSecurityDevice->id }} [label="{{ $physicalSecurityDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/security.png" href="#{{$physicalSecurityDevice->getUID()}}"]
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

document.addEventListener('DOMContentLoaded', () => {
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
    @foreach($buildings as $building)
    @if ($building->icon_id!==null)
    .addImage("{{ route('admin.documents.show', $building->icon_id) }}", "64px", "64px")
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
