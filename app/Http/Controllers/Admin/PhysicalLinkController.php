<?php

namespace App\Http\Controllers\Admin;

use App\PhysicalLink;
use App\Peripheral;
use App\Phone;
use App\PhysicalRouter;
use App\PhysicalSecurityDevice;
use App\PhysicalServer;
use App\PhysicalSwitch;
use App\StorageDevice;
use App\WifiTerminal;
use App\Workstation;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalLinkRequest;
use App\Http\Requests\StorePhysicalLinkRequest;
use App\Http\Requests\UpdatePhysicalLinkRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;

class PhysicalLinkController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_link_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // TODO: optimise loading of related objects
        $physicalLinks = PhysicalLink::all();

        return view('admin.links.index', compact('physicalLinks'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_link_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripherals = Peripheral::All()->sortBy('name')->pluck('name', 'id');
        $phones = Phone::All()->sortBy('name')->pluck('name', 'id');
        $physicalRouters = PhysicalRouter::All()->sortBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::All()->sortBy('name')->pluck('name', 'id');
        $physicalSwitches = PhysicalSwitch::All()->sortBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::All()->sortBy('name')->pluck('name', 'id');
        $wifiTerminals = WifiTerminal::All()->sortBy('name')->pluck('name', 'id');
        $workstations = Workstation::All()->sortBy('name')->pluck('name', 'id');

        $devices=Collection::make();
        foreach($peripherals as $key => $value)
            $devices->put('PER_' . $key,$value);
        foreach($phones as $key => $value)
            $devices->put('PHONE_' . $key,$value);
        foreach($physicalRouters as $key => $value)
            $devices->put('ROUTER_' . $key,$value);
        foreach($physicalSecurityDevices as $key => $value)
            $devices->put('SECDEV__' . $key,$value);
        foreach($physicalServers as $key => $value)
            $devices->put('SERV__' . $key,$value);
        foreach($physicalSwitches as $key => $value)
            $devices->put('SWITCH__' . $key,$value);
        foreach($storageDevices as $key => $value)
            $devices->put('STORAGE__' . $key,$value);
        foreach($wifiTerminals as $key => $value)
            $devices->put('WIFI__' . $key,$value);
        foreach($workstations as $key => $value)
            $devices->put('WORK__' . $key,$value);

        // dd($devices);

        return view('admin.links.create', compact('devices'));
    }

    public function store(StorePhysicalLinkRequest $request)
    {
        $physicalLink = PhysicalLink::create($request->all());

        return redirect()->route('admin.links.index');
    }

    public function edit(PhysicalLink $physicalLink)
    {
        abort_if(Gate::denies('physical_link_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripherals = Peripheral::All()->sortBy('name')->pluck('name', 'id');
        $phones = Phone::All()->sortBy('name')->pluck('name', 'id');
        $physicalRouters = PhysicalRouter::All()->sortBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::All()->sortBy('name')->pluck('name', 'id');
        $physicalSwitches = PhysicalSwitch::All()->sortBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::All()->sortBy('name')->pluck('name', 'id');
        $wifiTerminals = WifiTerminal::All()->sortBy('name')->pluck('name', 'id');
        $workstations = Workstation::All()->sortBy('name')->pluck('name', 'id');

        return view('admin.links.edit', 
            compact('peripherals', 
                'phones', 
                'physicalRouters',
                'physicalSecurityDevices',
                'physicalServers',
                'physicalSwitches',
                'storageDevices',
                'wifiTerminals',
                'workstations'
            )
        );
    }

    public function update(UpdatePhysicalLinkRequest $request, PhysicalLink $physicalLink)
    {
        $physicalLink->update($request->all());

        return redirect()->route('admin.links.index');
    }

    public function show(PhysicalLink $physicalLink)
    {
        abort_if(Gate::denies('physical_link_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.links.show', compact('physicalLink'));
    }

    public function destroy(PhysicalLink $physicalLink)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalLink->delete();

        return redirect()->route('admin.links.index');
    }

    public function massDestroy(MassDestroyPhysicalLinkRequest $request)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalLink::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
