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
            $devices->put('SECDEV_' . $key,$value);
        foreach($physicalServers as $key => $value)
            $devices->put('SERV_' . $key,$value);
        foreach($physicalSwitches as $key => $value)
            $devices->put('SWITCH_' . $key,$value);
        foreach($storageDevices as $key => $value)
            $devices->put('STORAGE_' . $key,$value);
        foreach($wifiTerminals as $key => $value)
            $devices->put('WIFI_' . $key,$value);
        foreach($workstations as $key => $value)
            $devices->put('WORK_' . $key,$value);

        return view('admin.links.create', compact('devices'));
    }

    public function store(StorePhysicalLinkRequest $request)
    {

        $physicalLink = new PhysicalLink;

        // Source device
        if (str_starts_with($request->src_id,'PER_'))
            $physicalLink->peripheral_src_id=intval(substr($request->src_id,4));
        elseif (str_starts_with($request->src_id,'PHONE_'))
            $physicalLink->phone_src_id=intval(substr($request->src_id,6));
        elseif (str_starts_with($request->src_id,'ROUTER_'))
            $physicalLink->physical_router_src_id=intval(substr($request->src_id,7));
        elseif (str_starts_with($request->src_id,'SECDEV_'))
            $physicalLink->physical_security_device_src_id=intval(substr($request->src_id,7));
        elseif (str_starts_with($request->src_id,'SERV_'))
            $physicalLink->physical_server_src_id=intval(substr($request->src_id,5));
        elseif (str_starts_with($request->src_id,'SWITCH_'))
            $physicalLink->physical_switch_src_id=intval(substr($request->src_id,7));
        elseif (str_starts_with($request->src_id,'STORAGE_'))
            $physicalLink->storage_device_src_id=intval(substr($request->src_id,8));
        elseif (str_starts_with($request->src_id,'WIFI_'))
            $physicalLink->wifi_terminal_src_id=intval(substr($request->src_id,5));
        elseif (str_starts_with($request->src_id,'WORK_'))
            $physicalLink->workstation_src_id=intval(substr($request->src_id,5));

        // Dest device
        if (str_starts_with($request->dest_id,'PER_'))
            $physicalLink->peripheral_dest_id=intval(substr($request->dest_id,4));
        elseif (str_starts_with($request->dest_id,'PHONE_'))
            $physicalLink->phone_dest_id=intval(substr($request->dest_id,6));
        elseif (str_starts_with($request->dest_id,'ROUTER_'))
            $physicalLink->physical_router_dest_id=intval(substr($request->dest_id,7));
        elseif (str_starts_with($request->dest_id,'SECDEV_'))
            $physicalLink->physical_security_device_dest_id=intval(substr($request->dest_id,7));
        elseif (str_starts_with($request->dest_id,'SERV_'))
            $physicalLink->physical_server_dest_id=intval(substr($request->dest_id,5));
        elseif (str_starts_with($request->dest_id,'SWITCH_'))
            $physicalLink->physical_switch_dest_id=intval(substr($request->dest_id,7));
        elseif (str_starts_with($request->dest_id,'STORAGE_'))
            $physicalLink->storage_device_dest_id=intval(substr($request->dest_id,8));
        elseif (str_starts_with($request->dest_id,'WIFI_'))
            $physicalLink->wifi_terminal_dest_id=intval(substr($request->dest_id,5));
        elseif (str_starts_with($request->dest_id,'WORK_'))
            $physicalLink->workstation_dest_id=intval(substr($request->dest_id,5));

        // Ports
        $physicalLink->src_port = $request->src_port;
        $physicalLink->dest_port = $request->dest_port;

        $physicalLink->save();

        return redirect()->route('admin.links.index');
    }

    public function edit(PhysicalLink $link)
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

        $devices=Collection::make();
        foreach($peripherals as $key => $value)
            $devices->put('PER_' . $key,$value);
        foreach($phones as $key => $value)
            $devices->put('PHONE_' . $key,$value);
        foreach($physicalRouters as $key => $value)
            $devices->put('ROUTER_' . $key,$value);
        foreach($physicalSecurityDevices as $key => $value)
            $devices->put('SECDEV_' . $key,$value);
        foreach($physicalServers as $key => $value)
            $devices->put('SERV_' . $key,$value);
        foreach($physicalSwitches as $key => $value)
            $devices->put('SWITCH_' . $key,$value);
        foreach($storageDevices as $key => $value)
            $devices->put('STORAGE_' . $key,$value);
        foreach($wifiTerminals as $key => $value)
            $devices->put('WIFI_' . $key,$value);
        foreach($workstations as $key => $value)
            $devices->put('WORK_' . $key,$value);

        // Source device
        if ($link->peripheral_src_id!=null)
            $link->src_id='PER_'.$link->peripheral_src_id;
        elseif ($link->phone_src_id!=null)
            $link->src_id= 'PHONE_' . $link->phone_src_id;
        elseif ($link->physical_router_src_id!=null)
            $link->src_id= 'ROUTER_' . $link->physical_router_src_id;
        elseif ($link->physical_security_device_src_id!=null)
            $link->src_id= 'SECDEV_' . $link->physical_security_device_src_id;
        elseif ($link->physical_server_src_id!=null)
            $link->src_id= 'SERV_' . $link->physical_server_src_id;
        elseif ($link->physical_switch_src_id!=null)
            $link->src_id= 'SWITCH_' . $link->physical_switch_src_id;
        elseif ($link->storage_device_src_id!=null)
            $link->src_id= 'STORAGE_' . $link->storage_device_src_id;
        elseif ($link->wifi_terminal_src_id!=null)
            $link->src_id= 'WIFI_' . $link->wifi_terminal_src_id;
        elseif ($link->workstation_src_id!=null)
            $link->src_id= 'WORK_' . $link->workstation_src_id;

        // Dest device
        if ($link->peripheral_dest_id!=null)
            $link->dest_id='PER_'.$link->peripheral_dest_id;
        elseif ($link->phone_dest_id!=null)
            $link->dest_id= 'PHONE_' . $link->phone_dest_id;
        elseif ($link->physical_router_dest_id!=null)
            $link->dest_id= 'ROUTER_' . $link->physical_router_dest_id;
        elseif ($link->physical_security_device_dest_id!=null)
            $link->dest_id= 'SECDEV_' . $link->physical_security_device_dest_id;
        elseif ($link->physical_server_dest_id!=null)
            $link->dest_id= 'SERV_' . $link->physical_server_dest_id;
        elseif ($link->physical_switch_dest_id!=null)
            $link->dest_id= 'SWITCH_' . $link->physical_switch_dest_id;
        elseif ($link->storage_device_dest_id!=null)
            $link->dest_id= 'STORAGE_' . $link->storage_device_dest_id;
        elseif ($link->wifi_terminal_dest_id!=null)
            $link->dest_id= 'WIFI_' . $link->wifi_terminal_dest_id;
        elseif ($link->workstation_dest_id!=null)
            $link->dest_id= 'WORK_' . $link->workstation_dest_id;

        return view('admin.links.edit', compact('devices','link'));
    }

    public function update(UpdatePhysicalLinkRequest $request, PhysicalLink $link)
    {
        // Source device
        if (str_starts_with($request->src_id,'PER_'))
            $link->peripheral_src_id=intval(substr($request->src_id,4));
        else
            $link->peripheral_src_id=null;

        if (str_starts_with($request->src_id,'PHONE_'))
            $link->phone_src_id=intval(substr($request->src_id,6));
        else
            $link->phone_src_id=null;

        if (str_starts_with($request->src_id,'ROUTER_'))
            $link->physical_router_src_id=intval(substr($request->src_id,7));
        else
            $link->physical_router_src_id=null;

        if (str_starts_with($request->src_id,'SECDEV_'))
            $link->physical_security_device_src_id=intval(substr($request->src_id,7));
        else
            $link->physical_security_device_src_id=null;

        if (str_starts_with($request->src_id,'SERV_'))
            $link->physical_server_src_id=intval(substr($request->src_id,5));
        else
            $link->physical_server_src_id=null;

        if (str_starts_with($request->src_id,'SWITCH_')) 
            $link->physical_switch_src_id=intval(substr($request->src_id,7));
        else
            $link->physical_switch_src_id=null;
        
        if (str_starts_with($request->src_id,'STORAGE_'))
            $link->storage_device_src_id=intval(substr($request->src_id,8));
        else
            $link->storage_device_src_id=null;

        if (str_starts_with($request->src_id,'WIFI_'))
            $link->wifi_terminal_src_id=intval(substr($request->src_id,5));
        else
            $link->wifi_terminal_src_id=null;
        if (str_starts_with($request->src_id,'WORK_'))
            $link->workstation_src_id=intval(substr($request->src_id,5));
        else
            $link->workstation_src_id=null;

        // Dest device
        if (str_starts_with($request->dest_id,'PER_'))
            $link->peripheral_dest_id=intval(substr($request->dest_id,4));
        else
            $link->peripheral_dest_id=null;

        if (str_starts_with($request->dest_id,'PHONE_'))
            $link->phone_dest_id=intval(substr($request->dest_id,6));
        else
            $link->phone_dest_id=null;

        if (str_starts_with($request->dest_id,'ROUTER_'))
            $link->physical_router_dest_id=intval(substr($request->dest_id,7));
        else
            $link->physical_router_dest_id=null;

        if (str_starts_with($request->dest_id,'SECDEV_'))
            $link->physical_security_device_dest_id=intval(substr($request->dest_id,7));
        else
            $link->physical_security_device_dest_id=null;

        if (str_starts_with($request->dest_id,'SERV_'))
            $link->physical_server_dest_id=intval(substr($request->dest_id,5));
        else
            $link->physical_server_dest_id=null;

        if (str_starts_with($request->dest_id,'SWITCH_')) 
            $link->physical_switch_dest_id=intval(substr($request->dest_id,7));
        else
            $link->physical_switch_dest_id=null;
        
        if (str_starts_with($request->dest_id,'STORAGE_'))
            $link->storage_device_dest_id=intval(substr($request->dest_id,8));
        else
            $link->storage_device_dest_id=null;

        if (str_starts_with($request->dest_id,'WIFI_'))
            $link->wifi_terminal_dest_id=intval(substr($request->dest_id,5));
        else
            $link->wifi_terminal_dest_id=null;
        if (str_starts_with($request->dest_id,'WORK_'))
            $link->workstation_dest_id=intval(substr($request->dest_id,5));
        else
            $link->workstation_dest_id=null;

        // Ports
        $link->src_port = $request->src_port;
        $link->dest_port = $request->dest_port;

        // Update 
        $link->update();

        return redirect()->route('admin.links.index');
    }

    public function show(PhysicalLink $link)
    {
        abort_if(Gate::denies('physical_link_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.links.show', compact('link'));
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
