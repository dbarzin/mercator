@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.menu.logical_infrastructure.title") }}
            </div>
            <form action="/admin/report/logical_infrastructure">

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-sm-4">
                        <table class="table table-bordered table-striped" style="width: 600px;">
                            <tr>
                                <td style="width: 300px;">
                                    {{ trans("cruds.network.title_singular") }} :
                                    <select name="network" id="network"
                                            onchange="this.form.subnetwork.value='';this.form.submit()"
                                            class="form-control select2">
                                        <option value="">-- All networks --</option>
                                        @foreach($all_networks as $id => $name)
                                            <option value="{{$id}}" {{ Session::get('network')==$id ? "selected" : "" }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 300px;">
                                    {{ trans("cruds.subnetwork.title_singular") }} :
                                    <select name="subnetwork" id="subnetwork" onchange="this.form.submit()"
                                            class="form-control select2">
                                        <option value="">-- All subnetworks --</option>
                                        @if ($all_subnetworks!==null)
                                            @foreach($all_subnetworks as $id => $name)
                                                <option value="{{$id}}" {{ Session::get('subnetwork')==$id ? "selected" : "" }}>{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div class="col-sm-8">
                            <input name="show_ip" id='show_ip' type="checkbox" value="1" class="form-check-input"
                                   {{ Session::get('show_ip') ? 'checked' : '' }} onchange="this.form.subnetwork.value='';this.form.submit()">
                            <label for="is_external">Afficher les adresses IP</label>
                        </div>
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

        @can('network_access')
            @if ($networks->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.network.title") }}
                    </div>

                    <div class="card-body">
                        <p>{{ trans("cruds.network.description") }}</p>

                        @foreach($networks as $network)
                            <div class="row">
                                <div class="col">
                                    @include('admin.networks._details', [
                                        'network' => $network,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('subnetwork_access')
            @if ($subnetworks->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.subnetwork.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.subnetwork.description") }}</p>

                        @foreach($subnetworks as $subnetwork)
                            <div class="row">
                                <div class="col">
                                    @include('admin.subnetworks._details', [
                                        'subnetwork' => $subnetwork,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can("gateway_access")
            @if ($gateways->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.gateway.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.gateway.description") }}</p>
                        @foreach($gateways as $gateway)
                            <div class="row">
                                <div class="col">
                                    @include('admin.gateways._details', [
                                        'gateway' => $gateway,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('external_connected_entity_access')
            @if ($externalConnectedEntities->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.externalConnectedEntity.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.externalConnectedEntity.description") }}</p>
                        @foreach($externalConnectedEntities as $entity)
                            <div class="row">
                                <div class="col">
                                    @include('admin.externalConnectedEntities._details', [
                                        'externalConnectedEntity' => $entity,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can("router_access")
            @if ($routers->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.router.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.router.description") }}</p>
                        @foreach($routers as $router)
                            <div class="row">
                                <div class="col">
                                    @include('admin.routers._details', [
                                        'router' => $router,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can("network_switch_access")
            @if ($networkSwitches->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.networkSwitch.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.networkSwitch.description") }}</p>
                        @foreach($networkSwitches as $networkSwitch)
                            <div class="row">
                                <div class="col">
                                    @include('admin.networkSwitches._details', [
                                        'networkSwitch' => $networkSwitch,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('cluster_access')
            @if ($clusters->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.cluster.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.cluster.description") }}</p>
                        @foreach($clusters as $cluster)
                            <div class="row">
                                <div class="col">
                                    @include('admin.clusters._details', [
                                        'cluster' => $cluster,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('logical_server_access')
            @if ($logicalServers->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.logicalServer.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.logicalServer.description") }}</p>
                        @foreach($logicalServers as $logicalServer)
                            <div class="row">
                                <div class="col">
                                    @include('admin.logicalServers._details', [
                                        'logicalServer' => $logicalServer,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('container_access')
            @if ($containers->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.container.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.container.description") }}</p>
                        @foreach($containers as $container)
                            <div class="row">
                                <div class="col">
                                    @include('admin.containers._details', [
                                        'container' => $container,
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

        @can('phone_access')
            @if ($phones->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.phone.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.phones.description") }}</p>
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
                                    @include('admin.physicalSwitches._details', [
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

        @can('wifi_terminal_access')
            @if ($wifiTerminals->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.wifiTerminal.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.workstation.description") }}</p>
                        @foreach($wifiTerminals as $wifiTerminal)
                            <div class="row">
                                <div class="col">
                                    @include('admin.physicalSwitches._details', [
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
                                <div class="col-sm-6">
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

        @can('dhcp_server_access')
            @if ($dhcpServers->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.dhcpServer.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.dhcpServer.description") }}</p>
                        @foreach($dhcpServers as $dhcpServer)
                            <div class="row">
                                <div class="col">
                                    @include('admin.dhcpServers._details', [
                                        'dhcpServer' => $dhcpServer,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('dnsserver_access')
            @if ($dnsservers->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.dnsserver.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.dnsserver.description") }}</p>
                        @foreach($dnsservers as $dnsserver)
                            <div class="row">
                                <div class="col">
                                    @include('admin.dnsservers._details', [
                                        'dnsserver' => $dnsserver,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('certificate_access')
            @if ($certificates->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.certificate.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.certificate.description") }}</p>
                        @foreach($certificates as $certificate)
                            <div class="row">
                                <div class="col">
                                    @include('admin.certificates._details', [
                                        'certificate' => $certificate,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan

        @can('vlan_access')
            @if ($vlans->count()>0)
                <br>
                <div class="card">
                    <div class="card-header">
                        {{ trans("cruds.vlan.title") }}
                    </div>
                    <div class="card-body">
                        <p>{{ trans("cruds.vlan.description") }}</p>
                        @foreach($vlans as $vlan)
                            <div class="row">
                                <div class="col">
                                    @include('admin.vlans._details', [
                                        'vlan' => $vlan,
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
@can('network_access')
    @foreach($networks as $network)
    NET{{ $network->id }} [label="{{ $network->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/cloud.png" href="#{{$network->getUID()}}"]
    @endforeach
@endcan

@can('gateway_access')
    @foreach($gateways as $gateway)
    GATEWAY{{ $gateway->id }} [label="{{ $gateway->name }} {{ Session::get('show_ip') ? chr(13) . $gateway->ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip')&&($gateway->ip!=null) ? '1.5' :'1.1' }} image="/images/gateway.png" href="#{{$gateway->getUID()}}"]
    @endforeach
@endcan

@can('subnetwork_access')
    @foreach($subnetworks as $subnetwork)
        SUBNET{{ $subnetwork->id }} [label="{{ $subnetwork->name }} {{ Session::get('show_ip') ? chr(13) . $subnetwork->address : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip')&&($subnetwork->address!=null) ? '1.7' :'1.1' }} image="/images/network.png" href="#{{$subnetwork->getUID()}}"]
        @if ($subnetwork->vlan_id!==null)
            SUBNET{{ $subnetwork->id }} -> VLAN{{ $subnetwork->vlan_id }}
        @endif
        @if ($subnetwork->subnetwork_id!==null)
            @if ($subnetworks->contains('id', $subnetwork->subnetwork_id))
                SUBNET{{ $subnetwork->subnetwork_id }} -> SUBNET{{ $subnetwork->id }}
            @else
                NET{{ $subnetwork->network_id }} -> SUBNET{{ $subnetwork->id }}
            @endif
        @elseif ($subnetwork->network_id!==null)
            @if ($networks->contains('id', $subnetwork->network_id))
            NET{{ $subnetwork->network_id }} -> SUBNET{{ $subnetwork->id }}
            @endif
        @endif
        @if ($subnetwork->gateway_id!==null)
            SUBNET{{ $subnetwork->id }} -> GATEWAY{{ $subnetwork->gateway_id }}
        @endif
    @endforeach
@endcan

@can('external_connected_entity_access')
    @foreach($externalConnectedEntities as $entity)
        E{{ $entity->id }} [label="{{ $entity->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/entity.png" href="#{{$entity->getUID()}}"]
        @if($entity->network_id!==null)
            E{{ $entity->id }} -> NET{{ $entity->network_id }}
        @endif
    @endforeach
@endcan

@can('cluster_access')
    @php
        $usedClusterIds = collect($logicalServers)
            ->flatMap(fn($logicalServer) => $logicalServer->clusters->pluck('id'))
            ->unique()
            ->toArray();
    @endphp

    @foreach($clusters as $cluster)
        @if (in_array($cluster->id, $usedClusterIds))
            CLUSTER{{ $cluster->id}} [label="{{ $cluster->name }} {{ Session::get('show_ip') ? chr(13) . $cluster->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($cluster->address_ip!==null) ? '1.7' :'1.1' }} image="/images/cluster.png" href="#{{$cluster->getUID()}}"]
            @can('logical_server_access')
                @foreach($cluster->logicalServers as $logicalServer)
                    LOGICAL_SERVER{{ $logicalServer->id }} -> CLUSTER{{ $cluster->id }}
                @endforeach
            @endcan
        @endif
    @endforeach
@endcan

@can('logical_server_access')
    @foreach($logicalServers as $logicalServer)
        LOGICAL_SERVER{{ $logicalServer->id }} [label="{{ $logicalServer->name }} {{ Session::get('show_ip') ? chr(13) . $logicalServer->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($logicalServer->address_ip!=null) ? '1.5' :'1.1' }} image="{{ $logicalServer->icon_id === null ? '/images/lserver.png' : route('admin.documents.show', $logicalServer->icon_id) }}" href="#{{$logicalServer->getUID()}}"]
        @if ($logicalServer->address_ip!==null)
            @foreach($subnetworks as $subnetwork)
                @foreach(explode(',',$logicalServer->address_ip) as $address)
                    @if ($subnetwork->contains($address))
                        SUBNET{{ $subnetwork->id }} -> LOGICAL_SERVER{{ $logicalServer->id }}
                        @break(2)
                    @endif
                @endforeach
            @endforeach
        @endif
        @can('cluster_access')
            @if ($logicalServer->cluster_id!==null)
            LOGICAL_SERVER{{ $logicalServer->id }} -> CLUSTER{{ $logicalServer->cluster_id }}
            @endif
        @endcan

        @foreach($logicalServer->certificates as $certificate)
            LOGICAL_SERVER{{ $logicalServer->id }} -> CERT{{ $certificate->id }}
        @endforeach
    @endforeach
@endcan

@can('dhcp_server_access')
    @foreach($dhcpServers as $dhcpServer)
    DHCP_SERVER{{ $dhcpServer->id }} [label="{{ $dhcpServer->name }} {{ Session::get('show_ip') ? chr(13) . $dhcpServer->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($dhcpServer->address_ip!=null) ? '1.5' :'1.1' }} image="/images/lserver.png" href="#{{$dhcpServer->getUID()}}"]
    @if ($dhcpServer->address_ip!==null)
        @foreach($subnetworks as $subnetwork)
            @if ($subnetwork->contains($dhcpServer->address_ip))
            SUBNET{{ $subnetwork->id }} -> DHCP_SERVER{{ $dhcpServer->id }}
            @break
        @endif
    @endforeach
    @endif
    @endforeach
@endcan

@can('dnsserver_access')
    @foreach($dnsservers as $dnsserver)
    DNS_SERVER{{ $dnsserver->id }} [label="{{ $dnsserver->name }} {{ Session::get('show_ip') ? chr(13) . $dnsserver->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($dnsserver->address_ip!=null) ? '1.5' :'1.1' }} image="/images/lserver.png" href="#{{$dnsserver->getUID()}}"]
        @if ($dnsserver->address_ip!==null)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($dnsserver->address_ip))
                    SUBNET{{ $subnetwork->id }} -> DNS_SERVER{{ $dnsserver->id }}
                    @break
                @endif
            @endforeach
        @endif
    @endforeach
@endcan

@can('certificate_access')
    @foreach($certificates as $certificate)
    @if ($certificate->logical_servers->count()>0)
    CERT{{ $certificate->id }} [label="{{ $certificate->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/certificate.png" href="#CERT{{$certificate->id}}"]
    @endif
    @endforeach
@endcan

@can('container_access')
    @foreach($containers as $container)
        @if ($container->logicalServers->count()>0)
            CONT{{ $container->id }} [label="{{ $container->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $container->icon_id === null ? '/images/container.png' : route('admin.documents.show', $container->icon_id) }}" href="#{{$container->getUID()}}"]
            @foreach($container->logicalServers as $logicalServer)
                LOGICAL_SERVER{{ $logicalServer->id }} -> CONT{{ $container->id }}
            @endforeach
        @endif
    @endforeach
@endcan

    @can('workstation_access')
        @foreach($workstations as $workstation)
            WS{{ $workstation->id }} [label="{{ $workstation->name }} {{ Session::get('show_ip') ? chr(13) . $workstation->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($workstation->address_ip!==null) ? '1.5' :'1.1' }} image="{{ $workstation->icon_id === null ? '/images/workstation.png' : route('admin.documents.show', $workstation->icon_id) }}" href="#{{$workstation->getUID()}}"]
            @foreach(explode(',',$workstation->address_ip) as $address)
                @foreach($subnetworks as $subnetwork)
                    @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> WS{{ $workstation->id }}
                    @break(2)
                    @endif
           @endforeach
        @endforeach
    @endforeach
@endcan

@can('wifi_terminal_access')
    @foreach($wifiTerminals as $wifiTerminal)
        WIFI{{ $wifiTerminal->id }} [label="{{ $wifiTerminal->name }} {{ Session::get('show_ip') ? chr(13) . $wifiTerminal->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($wifiTerminal->address_ip!==null) ? '1.5' :'1.1' }} image="/images/wifi.png" href="#WIFI{{$wifiTerminal->id}}"]
        @foreach(explode(',',$wifiTerminal->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                SUBNET{{ $subnetwork->id }} -> WIFI{{ $wifiTerminal->id }}
                @break(2)
                @endif
            @endforeach
        @endforeach
    @endforeach
@endcan

@can('phone_access')
    @foreach($phones as $phone)
    PHONE{{ $phone->id }} [label="{{ $phone->name }} {{ Session::get('show_ip') ? chr(13) . $phone->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($phone->address_ip!==null) ? '1.5' :'1.1' }} image="/images/phone.png" href="#PHONE{{$phone->id}}"]
    @foreach(explode(',',$phone->address_ip) as $address)
        @foreach($subnetworks as $subnetwork)
            @if ($subnetwork->contains($address))
            SUBNET{{ $subnetwork->id }} -> PHONE{{ $phone->id }}
            @break(2)
            @endif
            @endforeach
        @endforeach
    @endforeach
@endcan

@can('physical_security_device_access')
    @foreach($physicalSecurityDevices as $physicalSecurityDevice)
        SECURITY{{ $physicalSecurityDevice->id }} [label="{{ $physicalSecurityDevice->name }} {{ Session::get('show_ip') ? chr(13) . $physicalSecurityDevice->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($physicalSecurityDevice->address_ip!=null) ? '1.5' :'1.1' }} image="/images/securitydevice.png" href="#{{$physicalSecurityDevice->getUID()}}"]
        @foreach(explode(',',$physicalSecurityDevice->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                SUBNET{{ $subnetwork->id }} -> SECURITY{{ $physicalSecurityDevice->id }}
                @break(2)
                @endif
            @endforeach
        @endforeach
    @endforeach
@endcan

@can('peripheral_access')
    @foreach($peripherals as $peripheral)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }} {{ Session::get('show_ip') ? chr(13) . $peripheral->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($peripheral->address_ip!==null) ? '1.5' :'1.1' }} image="{{ $peripheral->icon_id === null ? '/images/peripheral.png' : route('admin.documents.show', $peripheral->icon_id) }}" href="#{{$peripheral->getUID()}}"]
        @foreach(explode(',',$peripheral->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                SUBNET{{ $subnetwork->id }} -> PER{{ $peripheral->id }}
                @break(2)
                @endif
            @endforeach
        @endforeach
    @endforeach
@endcan

@can('storage_device_access')
    @foreach($storageDevices as $storageDevice)
        STOR{{ $storageDevice->id }} [label="{{ $storageDevice->name }} {{ Session::get('show_ip') ? chr(13) . $storageDevice->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($storageDevice->address_ip!==null) ? '1.5' :'1.1' }} image="/images/storagedev.png" href="#{{$storageDevice->getUID()}}"]
        @foreach(explode(',',$storageDevice->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                SUBNET{{ $subnetwork->id }} -> STOR{{ $storageDevice->id }}
                @break(2)
                @endif
            @endforeach
        @endforeach
    @endforeach
@endcan

@can('router_access')
    @foreach($routers as $router)
        R{{ $router->id }} [label="{{ $router->name }} {{ Session::get('show_ip') ? chr(13) . $router->ip_addresses : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($router->ip_addresses!==null) ? '1.5' :'1.1' }} image="/images/router.png" href="#{{$router->getUID()}}"]
        @foreach(explode(',',$router->ip_addresses) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                SUBNET{{ $subnetwork->id }} -> R{{ $router->id }}
                @break(2)
                @endif
            @endforeach
        @endforeach
    @endforeach
@endcan

@can('network_switch_access')
    @foreach($networkSwitches as $networkSwitch)
        SW{{ $networkSwitch->id }} [label="{{ $networkSwitch->name }} {{ Session::get('show_ip') ? chr(13) . $networkSwitch->ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($networkSwitch->ip!==null) ? '1.5' :'1.1' }} image="/images/switch.png" href="#{{$networkSwitch->getUID()}}"]
        @if ($networkSwitch->vlans->count()>0)
            @foreach($networkSwitch->vlans as $vlan)
                VLAN{{ $vlan->id }} -> SW{{ $networkSwitch->id }}
            @endforeach
        @else
            @foreach(explode(',',$networkSwitch->ip) as $address)
                @foreach($subnetworks as $subnetwork)
                    @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> SW{{ $networkSwitch->id }}
                    @break(2)
                    @endif
                @endforeach
            @endforeach
        @endif
    @endforeach
@endcan

@can('vlan_access')
    @foreach($vlans as $vlan)
    VLAN{{ $vlan->id }} [label="{{ $vlan->name }}" shape=none labelloc="b" width=1 height=1.1 image="/images/vlan.png" href="#{{$vlan->getUID()}}"]
    @endforeach
@endcan
}`;

document.addEventListener('DOMContentLoaded', () => {
    d3.select("#graph").graphviz()
        .addImage("/images/cloud.png", "64px", "64px")
        .addImage("/images/network.png", "64px", "64px")
        .addImage("/images/gateway.png", "64px", "64px")
        .addImage("/images/entity.png", "64px", "64px")
        .addImage("/images/lserver.png", "64px", "64px")
        .addImage("/images/router.png", "64px", "64px")
        .addImage("/images/switch.png", "64px", "64px")
        .addImage("/images/cluster.png", "64px", "64px")
        .addImage("/images/container.png", "64px", "64px")
        .addImage("/images/certificate.png", "64px", "64px")
        .addImage("/images/workstation.png", "64px", "64px")
        .addImage("/images/phone.png", "64px", "64px")
        .addImage("/images/securitydevice.png", "64px", "64px")
        .addImage("/images/storagedev.png", "64px", "64px")
        .addImage("/images/peripheral.png", "64px", "64px")
        .addImage("/images/wifi.png", "64px", "64px")
        .addImage("/images/vlan.png", "64px", "64px")
        @foreach($containers as $container)
        @if ($container->icon_id!==null)
        .addImage("{{ route('admin.documents.show', $container->icon_id) }}", "64px", "64px")
        @endif
        @endforeach
        @foreach($logicalServers as $logicalServer)
        @if ($logicalServer->icon_id!==null)
        .addImage("{{ route('admin.documents.show', $logicalServer->icon_id) }}", "64px", "64px")
        @endif
        @endforeach
        @foreach($peripherals as $peripheral)
        @if ($peripheral->icon_id!==null)
        .addImage("{{ route('admin.documents.show', $peripheral->icon_id) }}", "64px", "64px")
        @endif
        @endforeach
        @foreach($workstations as $workstation)
        @if ($workstation->icon_id!==null)
        .addImage("{{ route('admin.documents.show', $workstation->icon_id) }}", "64px", "64px")
        @endif
        @endforeach
        .engine("{{ $engine }}")
        .renderDot(dotSrc);
    });
</script>
@parent
@endsection
