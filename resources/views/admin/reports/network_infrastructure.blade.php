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
                            <div class="graph-resize-handle"></div>
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
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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
                @if ((auth()->user()->granularity>=2)&&($workstations->count()>0))
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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
                @if ((auth()->user()->granularity>=2)&&($peripherals->count()>0))
                    <div class="card mt-2">
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
                @if ((auth()->user()->granularity>=2)&&($phones->count()>0))
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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
                    <div class="card mt-2">
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

            @can('physical_link_access')
                @if ($physicalLinks->count()>0)
                    <div class="card mt-2">
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
        bgcolor = "{{ $tableau20[$idColor++ % 20] }}"

        <?php
            // Tous les buildings de ce site
            $siteBuildings = $buildings->where('site_id', $site->id);
        ?>

        {{-- Buildings racine : pas de building parent --}}
        @foreach($siteBuildings->whereNull('building_id') as $building)
        @include('admin.reports.network_infrastructure_building', [
            'building'  => $building,
            'buildings' => $siteBuildings,
            'visited'   => [],
        ])
        @endforeach
        }
@endforeach

@foreach($physicalLinks as $link)
        <?php
        // Déterminer la source
        $srcNode = null;
        if($link->peripheral_src_id && $peripherals->contains("id", $link->peripheral_src_id)) {
            $srcNode = "PER{$link->peripheral_src_id}";
        } elseif($link->physical_router_src_id && $physicalRouters->contains("id", $link->physical_router_src_id)) {
            $srcNode = "ROUTER{$link->physical_router_src_id}";
        } elseif($link->phone_src_id && $phones->contains("id", $link->phone_src_id)) {
            $srcNode = "PHONE{$link->phone_src_id}";
        } elseif($link->physical_security_device_src_id && $physicalSecurityDevices->contains("id", $link->physical_security_device_src_id)) {
            $srcNode = "PSD{$link->physical_security_device_src_id}";
        } elseif($link->physical_server_src_id && $physicalServers->contains("id", $link->physical_server_src_id)) {
            $srcNode = "PSERVER{$link->physical_server_src_id}";
        } elseif($link->physical_switch_src_id && $physicalSwitches->contains("id", $link->physical_switch_src_id)) {
            $srcNode = "SWITCH{$link->physical_switch_src_id}";
        } elseif($link->storage_device_src_id && $storageDevices->contains("id", $link->storage_device_src_id)) {
            $srcNode = "SD{$link->storage_device_src_id}";
        } elseif($link->wifi_terminal_src_id && $wifiTerminals->contains("id", $link->wifi_terminal_src_id)) {
            $srcNode = "WIFI{$link->wifi_terminal_src_id}";
        } elseif($link->workstation_src_id && $workstations->contains("id", $link->workstation_src_id)) {
            $srcNode = "WORK{$link->workstation_src_id}";
        }

        // Déterminer la destination
        $destNode = null;
        if($link->peripheral_dest_id && $peripherals->contains("id", $link->peripheral_dest_id)) {
            $destNode = "PER{$link->peripheral_dest_id}";
        } elseif($link->physical_router_dest_id && $physicalRouters->contains("id", $link->physical_router_dest_id)) {
            $destNode = "ROUTER{$link->physical_router_dest_id}";
        } elseif($link->phone_dest_id && $phones->contains("id", $link->phone_dest_id)) {
            $destNode = "PHONE{$link->phone_dest_id}";
        } elseif($link->physical_security_device_dest_id && $physicalSecurityDevices->contains("id", $link->physical_security_device_dest_id)) {
            $destNode = "PSD{$link->physical_security_device_dest_id}";
        } elseif($link->physical_server_dest_id && $physicalServers->contains("id", $link->physical_server_dest_id)) {
            $destNode = "PSERVER{$link->physical_server_dest_id}";
        } elseif($link->physical_switch_dest_id && $physicalSwitches->contains("id", $link->physical_switch_dest_id)) {
            $destNode = "SWITCH{$link->physical_switch_dest_id}";
        } elseif($link->storage_device_dest_id && $storageDevices->contains("id", $link->storage_device_dest_id)) {
            $destNode = "SD{$link->storage_device_dest_id}";
        } elseif($link->wifi_terminal_dest_id && $wifiTerminals->contains("id", $link->wifi_terminal_dest_id)) {
            $destNode = "WIFI{$link->wifi_terminal_dest_id}";
        } elseif($link->workstation_dest_id && $workstations->contains("id", $link->workstation_dest_id)) {
            $destNode = "WORK{$link->workstation_dest_id}";
        }

        // Vérifier qu'on a bien une source ET une destination, et pas de liens virtuels
        $isPhysicalLink =
            $link->router_src_id === null &&
            $link->router_dest_id === null &&
            $link->logical_server_src_id === null &&
            $link->logical_server_dest_id === null &&
            $link->network_switch_src_id === null &&
            $link->network_switch_dest_id === null;
        ?>

    @if($isPhysicalLink && $srcNode && $destNode)
    {{ $srcNode }} -> {{ $destNode }} [arrowhead=none,taillabel="{{ $link->src_port }}", headlabel="{{ $link->dest_port }}", href="{{ route('admin.links.show', $link->id) }}"];
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
    @foreach($physicalServers as $server)
    @if ($server->icon_id!==null)
    .addImage("{{ route('admin.documents.show', $server->icon_id) }}", "64px", "64px")
    @endif
    @endforeach
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
