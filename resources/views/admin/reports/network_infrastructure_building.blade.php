{{-- Loop Protection --}}
@php
    /** @var \Mercator\Core\Models\Building $building */
    $visited = $visited ?? [];

    // Si déjà vu, on coupe pour éviter boucle
    if (in_array($building->id, $visited, true)) {
        return;
    }

    $visited[] = $building->id;
@endphp
{{-- Draw Room --}}
subgraph ROOM_{{ $building->id }} {

cluster=true;
label="{{ $building->name }}"
bgcolor="{{ $tableau20[$idColor++ % 20] }}"

@foreach($building->phones as $phone)
    PHONE{{ $phone->id }} [label="{{ $phone->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/phone.png" href="#{{$phone->getUID()}}"]
@endforeach

@foreach($building->workstations as $workstation)
    WORK{{ $workstation->id }} [label="{{ $workstation->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $workstation->icon_id === null ? "/images/workstation.png" : route('admin.documents.show', $workstation->icon_id) }}" href="#{{$workstation->getUID()}}"]
@endforeach

@foreach($building->wifiTerminals as $wifiTerminal)
    WIFI{{ $wifiTerminal->id }} [label="{{ $wifiTerminal->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/wifi.png" href="#{{$wifiTerminal->getUID()}}"]
@endforeach

@foreach($building->physicalSwitches as $switch)
    @if ($switch->bay_id===null)
        SWITCH{{ $switch->id }} [label="{{ $switch->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/switch.png" href="#{{$switch->getUID()}}"]
    @endif
@endforeach

@foreach($building->physicalRouters as $router)
    @if ($router->bay_id===null)
        ROUTER{{ $router->id }} [label="{{ $router->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/router.png" href="#{{$router->getUID()}}"]
    @endif
@endforeach

@foreach($building->peripherals as $peripheral)
    @if ($peripheral->bay_id===null)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $peripheral->icon_id === null ? "/images/peripheral.png" : route('admin.documents.show', $peripheral->icon_id) }}" href="#{{$peripheral->getUID()}}"]
    @endif
@endforeach

{{-- Draw Bays --}}
@foreach($building->roomBays as $bay)
    subgraph BAY_{{ $bay->id }} {
    cluster=true;
    label="{{ $bay->name }}"
    bgcolor="{{ $tableau20[$idColor++ % 20] }}"

    @foreach($bay->physicalServers as $pServer)
        PSERVER{{ $pServer->id }} [label="{{ $pServer->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $pServer->icon_id === null ? "/images/server.png" : route('admin.documents.show', $pServer->icon_id) }}" href="#{{$pServer->getUID()}}"]
    @endforeach

    @foreach($bay->storageDevices as $storageDevice)
        SD{{ $storageDevice->id }} [label="{{ $storageDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/storage.png" href="#{{$storageDevice->getUID()}}"]
    @endforeach

    @foreach($bay->physicalSwitches as $switch)
        SWITCH{{ $switch->id }} [label="{{ $switch->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/switch.png" href="#{{$switch->getUID()}}"]
    @endforeach

    @foreach($bay->physicalSecurityDevices as $physicalSecurityDevice)
        PSD{{ $physicalSecurityDevice->id }} [label="{{ $physicalSecurityDevice->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/security.png" href="#{{$physicalSecurityDevice->getUID()}}"]
    @endforeach

    @foreach($bay->physicalRouters as $router)
        ROUTER{{ $router->id }} [label="{{ $router->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/router.png" href="#{{$router->getUID()}}"]
    @endforeach

    @foreach($bay->peripherals as $peripheral)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $peripheral->icon_id === null ? "/images/peripheral.png" : route('admin.documents.show', $peripheral->icon_id) }}" href="#{{$peripheral->getUID()}}"]
    @endforeach
    }
@endforeach

{{-- Les buildings enfants (imbriqués) --}}
@foreach($buildings->where('building_id', $building->id) as $childBuilding)
    @include('admin.reports.network_infrastructure_building', [
        'building'  => $childBuilding,
        'buildings' => $buildings,
        'visited'   => $visited,
    ])
@endforeach
}
