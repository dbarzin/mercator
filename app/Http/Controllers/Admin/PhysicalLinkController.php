<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalLinkRequest;
use App\Http\Requests\StorePhysicalLinkRequest;
use App\Http\Requests\UpdatePhysicalLinkRequest;
use App\LogicalServer;
use App\NetworkSwitch;
use App\Peripheral;
use App\Phone;
use App\PhysicalLink;
use App\PhysicalRouter;
use App\PhysicalSecurityDevice;
use App\PhysicalServer;
use App\PhysicalSwitch;
use App\Router;
use App\StorageDevice;
use App\WifiTerminal;
use App\Workstation;
use Gate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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

        // physical devices
        $peripherals = Peripheral::All()->sortBy('name')->pluck('name', 'id');
        $phones = Phone::All()->sortBy('name')->pluck('name', 'id');
        $physicalRouters = PhysicalRouter::All()->sortBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::All()->sortBy('name')->pluck('name', 'id');
        $physicalSwitches = PhysicalSwitch::All()->sortBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::All()->sortBy('name')->pluck('name', 'id');
        $wifiTerminals = WifiTerminal::All()->sortBy('name')->pluck('name', 'id');
        $workstations = Workstation::All()->sortBy('name')->pluck('name', 'id');

        // logical devices
        $routers = Router::All()->sortBy('name')->pluck('name', 'id');
        $networkSwitches = NetworkSwitch::All()->sortBy('name')->pluck('name', 'id');
        $logicalServers = LogicalServer::All()->sortBy('name')->pluck('name', 'id');

        $devices = Collection::make();
        foreach ($peripherals as $key => $value) {
            $devices->put('PER_' . $key, $value);
        }
        foreach ($phones as $key => $value) {
            $devices->put('PHONE_' . $key, $value);
        }
        foreach ($physicalRouters as $key => $value) {
            $devices->put('ROUTER_' . $key, $value);
        }
        foreach ($physicalSecurityDevices as $key => $value) {
            $devices->put('SECDEV_' . $key, $value);
        }
        foreach ($physicalServers as $key => $value) {
            $devices->put('SERV_' . $key, $value);
        }
        foreach ($physicalSwitches as $key => $value) {
            $devices->put('SWITCH_' . $key, $value);
        }
        foreach ($storageDevices as $key => $value) {
            $devices->put('STORAGE_' . $key, $value);
        }
        foreach ($wifiTerminals as $key => $value) {
            $devices->put('WIFI_' . $key, $value);
        }
        foreach ($workstations as $key => $value) {
            $devices->put('WORK_' . $key, $value);
        }

        foreach ($routers as $key => $value) {
            $devices->put('LROUTER_' . $key, $value);
        }
        foreach ($networkSwitches as $key => $value) {
            $devices->put('LSWITCH_' . $key, $value);
        }
        foreach ($logicalServers as $key => $value) {
            $devices->put('LSERV_' . $key, $value);
        }

        return view('admin.links.create', compact('devices'));
    }

    public function store(StorePhysicalLinkRequest $request)
    {
        $link = new PhysicalLink();

        // Source device
        if (str_starts_with($request->src_id, 'PER_')) {
            $link->peripheral_src_id = intval(substr($request->src_id, 4));
        } elseif (str_starts_with($request->src_id, 'PHONE_')) {
            $link->phone_src_id = intval(substr($request->src_id, 6));
        } elseif (str_starts_with($request->src_id, 'ROUTER_')) {
            $link->physical_router_src_id = intval(substr($request->src_id, 7));
        } elseif (str_starts_with($request->src_id, 'SECDEV_')) {
            $link->physical_security_device_src_id = intval(substr($request->src_id, 7));
        } elseif (str_starts_with($request->src_id, 'SERV_')) {
            $link->physical_server_src_id = intval(substr($request->src_id, 5));
        } elseif (str_starts_with($request->src_id, 'SWITCH_')) {
            $link->physical_switch_src_id = intval(substr($request->src_id, 7));
        } elseif (str_starts_with($request->src_id, 'STORAGE_')) {
            $link->storage_device_src_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'WIFI_')) {
            $link->wifi_terminal_src_id = intval(substr($request->src_id, 5));
        } elseif (str_starts_with($request->src_id, 'WORK_')) {
            $link->workstation_src_id = intval(substr($request->src_id, 5));
        } elseif (str_starts_with($request->src_id, 'LROUTER_')) {
            $link->router_src_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'LSWITCH_')) {
            $link->network_switch_src_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'LSERV_')) {
            $link->logical_server_src_id = intval(substr($request->src_id, 6));
        }

        // Dest device
        if (str_starts_with($request->dest_id, 'PER_')) {
            $link->peripheral_dest_id = intval(substr($request->dest_id, 4));
        } elseif (str_starts_with($request->dest_id, 'PHONE_')) {
            $link->phone_dest_id = intval(substr($request->dest_id, 6));
        } elseif (str_starts_with($request->dest_id, 'ROUTER_')) {
            $link->physical_router_dest_id = intval(substr($request->dest_id, 7));
        } elseif (str_starts_with($request->dest_id, 'SECDEV_')) {
            $link->physical_security_device_dest_id = intval(substr($request->dest_id, 7));
        } elseif (str_starts_with($request->dest_id, 'SERV_')) {
            $link->physical_server_dest_id = intval(substr($request->dest_id, 5));
        } elseif (str_starts_with($request->dest_id, 'SWITCH_')) {
            $link->physical_switch_dest_id = intval(substr($request->dest_id, 7));
        } elseif (str_starts_with($request->dest_id, 'STORAGE_')) {
            $link->storage_device_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'WIFI_')) {
            $link->wifi_terminal_dest_id = intval(substr($request->dest_id, 5));
        } elseif (str_starts_with($request->dest_id, 'WORK_')) {
            $link->workstation_dest_id = intval(substr($request->dest_id, 5));
        } elseif (str_starts_with($request->dest_id, 'LROUTER_')) {
            $link->router_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'LSWITCH_')) {
            $link->network_switch_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'LSERV_')) {
            $link->logical_server_dest_id = intval(substr($request->dest_id, 6));
        }

        // Ports
        $link->src_port = $request->src_port;
        $link->dest_port = $request->dest_port;

        // Empty validator
        $validator = Validator::make($request->all(), []);

        // Validate
        $validator->after(function ($validator) use ($link) {
            if (($link->src_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('src_port', $link->src_port)
                ->where('peripheral_src_id', $link->peripheral_src_id)
                ->where('phone_src_id', $link->phone_src_id)
                ->where('physical_router_src_id', $link->physical_router_src_id)
                ->where('physical_security_device_src_id', $link->physical_security_device_src_id)
                ->where('physical_server_src_id', $link->physical_server_src_id)
                ->where('physical_switch_src_id', $link->physical_switch_src_id)
                ->where('storage_device_src_id', $link->storage_device_src_id)
                ->where('wifi_terminal_src_id', $link->wifi_terminal_src_id)
                ->where('workstation_src_id', $link->workstation_src_id)
                ->where('logical_server_src_id', $link->logical_server_src_id)
                ->where('network_switch_src_id', $link->network_switch_src_id)
                ->where('router_src_id', $link->router_src_id)
                ->exists())) {
                $validator->errors()->add('src_port', 'Source port already used !');
            }
            if (($link->dest_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('src_port', $link->dest_port)
                ->where('peripheral_src_id', $link->peripheral_dest_id)
                ->where('phone_src_id', $link->phone_dest_id)
                ->where('physical_router_src_id', $link->physical_router_dest_id)
                ->where('physical_security_device_src_id', $link->physical_security_device_dest_id)
                ->where('physical_server_src_id', $link->physical_server_dest_id)
                ->where('physical_switch_src_id', $link->physical_switch_dest_id)
                ->where('storage_device_src_id', $link->storage_device_dest_id)
                ->where('wifi_terminal_src_id', $link->wifi_terminal_dest_id)
                ->where('workstation_src_id', $link->workstation_dest_id)
                ->where('logical_server_src_id', $link->logical_server_dest_id)
                ->where('network_switch_src_id', $link->network_switch_dest_id)
                ->where('router_src_id', $link->router_dest_id)
                ->exists())) {
                $validator->errors()->add('dest_port', 'Destination port already used 1!');
            }
            if (($link->dest_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('dest_port', $link->dest_port)
                ->where('peripheral_dest_id', $link->peripheral_dest_id)
                ->where('phone_dest_id', $link->phone_dest_id)
                ->where('physical_router_dest_id', $link->physical_router_dest_id)
                ->where('physical_security_device_dest_id', $link->physical_security_device_dest_id)
                ->where('physical_server_dest_id', $link->physical_server_dest_id)
                ->where('physical_switch_dest_id', $link->physical_switch_dest_id)
                ->where('storage_device_dest_id', $link->storage_device_dest_id)
                ->where('wifi_terminal_dest_id', $link->wifi_terminal_dest_id)
                ->where('workstation_dest_id', $link->workstation_dest_id)
                ->where('logical_server_dest_id', $link->logical_server_dest_id)
                ->where('network_switch_dest_id', $link->network_switch_dest_id)
                ->where('router_dest_id', $link->router_dest_id)
                ->exists())) {
                $validator->errors()->add('dest_port', 'Destination port already used 2!');
            }
            if (($link->src_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('dest_port', $link->src_port)
                ->where('peripheral_dest_id', $link->peripheral_src_id)
                ->where('phone_dest_id', $link->phone_src_id)
                ->where('physical_router_dest_id', $link->physical_router_src_id)
                ->where('physical_security_device_dest_id', $link->physical_security_device_src_id)
                ->where('physical_server_dest_id', $link->physical_server_src_id)
                ->where('physical_switch_dest_id', $link->physical_switch_src_id)
                ->where('storage_device_dest_id', $link->storage_device_src_id)
                ->where('wifi_terminal_dest_id', $link->wifi_terminal_src_id)
                ->where('workstation_dest_id', $link->workstation_src_id)
                ->where('logical_server_dest_id', $link->logical_server_src_id)
                ->where('network_switch_dest_id', $link->network_switch_src_id)
                ->where('router_dest_id', $link->router_src_id)
                ->exists())) {
                $validator->errors()->add('src_port', 'Source port already used !');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $link->save();

        return redirect()->route('admin.links.index');
    }

    public function edit(PhysicalLink $link)
    {
        abort_if(Gate::denies('physical_link_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Physical devices
        $peripherals = Peripheral::All()->sortBy('name')->pluck('name', 'id');
        $phones = Phone::All()->sortBy('name')->pluck('name', 'id');
        $physicalRouters = PhysicalRouter::All()->sortBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::All()->sortBy('name')->pluck('name', 'id');
        $physicalSwitches = PhysicalSwitch::All()->sortBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::All()->sortBy('name')->pluck('name', 'id');
        $wifiTerminals = WifiTerminal::All()->sortBy('name')->pluck('name', 'id');
        $workstations = Workstation::All()->sortBy('name')->pluck('name', 'id');

        // logical devices
        $routers = Router::All()->sortBy('name')->pluck('name', 'id');
        $networkSwitches = NetworkSwitch::All()->sortBy('name')->pluck('name', 'id');
        $logicalServers = LogicalServer::All()->sortBy('name')->pluck('name', 'id');

        $devices = Collection::make();
        foreach ($peripherals as $key => $value) {
            $devices->put('PER_' . $key, $value);
        }
        foreach ($phones as $key => $value) {
            $devices->put('PHONE_' . $key, $value);
        }
        foreach ($physicalRouters as $key => $value) {
            $devices->put('ROUTER_' . $key, $value);
        }
        foreach ($physicalSecurityDevices as $key => $value) {
            $devices->put('SECDEV_' . $key, $value);
        }
        foreach ($physicalServers as $key => $value) {
            $devices->put('SERV_' . $key, $value);
        }
        foreach ($physicalSwitches as $key => $value) {
            $devices->put('SWITCH_' . $key, $value);
        }
        foreach ($storageDevices as $key => $value) {
            $devices->put('STORAGE_' . $key, $value);
        }
        foreach ($wifiTerminals as $key => $value) {
            $devices->put('WIFI_' . $key, $value);
        }
        foreach ($workstations as $key => $value) {
            $devices->put('WORK_' . $key, $value);
        }
        foreach ($routers as $key => $value) {
            $devices->put('LROUTER_' . $key, $value);
        }
        foreach ($networkSwitches as $key => $value) {
            $devices->put('LSWITCH_' . $key, $value);
        }
        foreach ($logicalServers as $key => $value) {
            $devices->put('LSERV_' . $key, $value);
        }

        // Source device
        if ($link->peripheral_src_id !== null) {
            $link->src_id = 'PER_'.$link->peripheral_src_id;
        } elseif ($link->phone_src_id !== null) {
            $link->src_id = 'PHONE_' . $link->phone_src_id;
        } elseif ($link->physical_router_src_id !== null) {
            $link->src_id = 'ROUTER_' . $link->physical_router_src_id;
        } elseif ($link->physical_security_device_src_id !== null) {
            $link->src_id = 'SECDEV_' . $link->physical_security_device_src_id;
        } elseif ($link->physical_server_src_id !== null) {
            $link->src_id = 'SERV_' . $link->physical_server_src_id;
        } elseif ($link->physical_switch_src_id !== null) {
            $link->src_id = 'SWITCH_' . $link->physical_switch_src_id;
        } elseif ($link->storage_device_src_id !== null) {
            $link->src_id = 'STORAGE_' . $link->storage_device_src_id;
        } elseif ($link->wifi_terminal_src_id !== null) {
            $link->src_id = 'WIFI_' . $link->wifi_terminal_src_id;
        } elseif ($link->workstation_src_id !== null) {
            $link->src_id = 'WORK_' . $link->workstation_src_id;
        } elseif ($link->router_src_id !== null) {
            $link->src_id = 'LROUTER_' . $link->router_src_id;
        } elseif ($link->network_switch_src_id !== null) {
            $link->src_id = 'LSWITCH_' . $link->network_switch_src_id;
        } elseif ($link->logical_server_src_id !== null) {
            $link->src_id = 'LSERV_' . $link->logical_server_src_id;
        }

        // Dest device
        if ($link->peripheral_dest_id !== null) {
            $link->dest_id = 'PER_'.$link->peripheral_dest_id;
        } elseif ($link->phone_dest_id !== null) {
            $link->dest_id = 'PHONE_' . $link->phone_dest_id;
        } elseif ($link->physical_router_dest_id !== null) {
            $link->dest_id = 'ROUTER_' . $link->physical_router_dest_id;
        } elseif ($link->physical_security_device_dest_id !== null) {
            $link->dest_id = 'SECDEV_' . $link->physical_security_device_dest_id;
        } elseif ($link->physical_server_dest_id !== null) {
            $link->dest_id = 'SERV_' . $link->physical_server_dest_id;
        } elseif ($link->physical_switch_dest_id !== null) {
            $link->dest_id = 'SWITCH_' . $link->physical_switch_dest_id;
        } elseif ($link->storage_device_dest_id !== null) {
            $link->dest_id = 'STORAGE_' . $link->storage_device_dest_id;
        } elseif ($link->wifi_terminal_dest_id !== null) {
            $link->dest_id = 'WIFI_' . $link->wifi_terminal_dest_id;
        } elseif ($link->workstation_dest_id !== null) {
            $link->dest_id = 'WORK_' . $link->workstation_dest_id;
        } elseif ($link->router_dest_id !== null) {
            $link->dest_id = 'LROUTER_' . $link->router_dest_id;
        } elseif ($link->network_switch_dest_id !== null) {
            $link->dest_id = 'LSWITCH_' . $link->network_switch_dest_id;
        } elseif ($link->logical_server_dest_id !== null) {
            $link->dest_id = 'LSERV_' . $link->logical_server_dest_id;
        }

        return view('admin.links.edit', compact('devices', 'link'));
    }

    public function update(UpdatePhysicalLinkRequest $request, PhysicalLink $link)
    {
        // Source device
        if (str_starts_with($request->src_id, 'PER_')) {
            $link->peripheral_src_id = intval(substr($request->src_id, 4));
        } else {
            $link->peripheral_src_id = null;
        }

        if (str_starts_with($request->src_id, 'PHONE_')) {
            $link->phone_src_id = intval(substr($request->src_id, 6));
        } else {
            $link->phone_src_id = null;
        }

        if (str_starts_with($request->src_id, 'ROUTER_')) {
            $link->physical_router_src_id = intval(substr($request->src_id, 7));
        } else {
            $link->physical_router_src_id = null;
        }

        if (str_starts_with($request->src_id, 'SECDEV_')) {
            $link->physical_security_device_src_id = intval(substr($request->src_id, 7));
        } else {
            $link->physical_security_device_src_id = null;
        }

        if (str_starts_with($request->src_id, 'SERV_')) {
            $link->physical_server_src_id = intval(substr($request->src_id, 5));
        } else {
            $link->physical_server_src_id = null;
        }

        if (str_starts_with($request->src_id, 'SWITCH_')) {
            $link->physical_switch_src_id = intval(substr($request->src_id, 7));
        } else {
            $link->physical_switch_src_id = null;
        }

        if (str_starts_with($request->src_id, 'STORAGE_')) {
            $link->storage_device_src_id = intval(substr($request->src_id, 8));
        } else {
            $link->storage_device_src_id = null;
        }

        if (str_starts_with($request->src_id, 'WIFI_')) {
            $link->wifi_terminal_src_id = intval(substr($request->src_id, 5));
        } else {
            $link->wifi_terminal_src_id = null;
        }

        if (str_starts_with($request->src_id, 'WORK_')) {
            $link->workstation_src_id = intval(substr($request->src_id, 5));
        } else {
            $link->workstation_src_id = null;
        }

        if (str_starts_with($request->src_id, 'LROUTER_')) {
            $link->router_src_id = intval(substr($request->src_id, 8));
        } else {
            $link->router_src_id = null;
        }

        if (str_starts_with($request->src_id, 'LSWITCH_')) {
            $link->network_switch_src_id = intval(substr($request->src_id, 8));
        } else {
            $link->network_switch_src_id = null;
        }

        if (str_starts_with($request->src_id, 'LSERV_')) {
            $link->logical_server_src_id = intval(substr($request->src_id, 6));
        } else {
            $link->logical_server_src_id = null;
        }

        // Dest device
        if (str_starts_with($request->dest_id, 'PER_')) {
            $link->peripheral_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $link->peripheral_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'PHONE_')) {
            $link->phone_dest_id = intval(substr($request->dest_id, 6));
        } else {
            $link->phone_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'ROUTER_')) {
            $link->physical_router_dest_id = intval(substr($request->dest_id, 7));
        } else {
            $link->physical_router_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'SECDEV_')) {
            $link->physical_security_device_dest_id = intval(substr($request->dest_id, 7));
        } else {
            $link->physical_security_device_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'SERV_')) {
            $link->physical_server_dest_id = intval(substr($request->dest_id, 5));
        } else {
            $link->physical_server_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'SWITCH_')) {
            $link->physical_switch_dest_id = intval(substr($request->dest_id, 7));
        } else {
            $link->physical_switch_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'STORAGE_')) {
            $link->storage_device_dest_id = intval(substr($request->dest_id, 8));
        } else {
            $link->storage_device_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'WIFI_')) {
            $link->wifi_terminal_dest_id = intval(substr($request->dest_id, 5));
        } else {
            $link->wifi_terminal_dest_id = null;
        }
        if (str_starts_with($request->dest_id, 'WORK_')) {
            $link->workstation_dest_id = intval(substr($request->dest_id, 5));
        } else {
            $link->workstation_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'LROUTER_')) {
            $link->router_dest_id = intval(substr($request->dest_id, 8));
        } else {
            $link->router_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'LSWITCH_')) {
            $link->network_switch_dest_id = intval(substr($request->dest_id, 8));
        } else {
            $link->network_switch_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'LSERV_')) {
            $link->logical_server_dest_id = intval(substr($request->dest_id, 6));
        } else {
            $link->logical_server_dest_id = null;
        }

        // Ports
        $link->src_port = $request->src_port;
        $link->dest_port = $request->dest_port;

        // Empty validator
        $validator = Validator::make($request->all(), []);

        // Validate
        $validator->after(function ($validator) use ($link) {
            if (($link->src_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('src_port', $link->src_port)
                ->where('peripheral_src_id', $link->peripheral_src_id)
                ->where('phone_src_id', $link->phone_src_id)
                ->where('physical_router_src_id', $link->physical_router_src_id)
                ->where('physical_security_device_src_id', $link->physical_security_device_src_id)
                ->where('physical_server_src_id', $link->physical_server_src_id)
                ->where('physical_switch_src_id', $link->physical_switch_src_id)
                ->where('storage_device_src_id', $link->storage_device_src_id)
                ->where('wifi_terminal_src_id', $link->wifi_terminal_src_id)
                ->where('workstation_src_id', $link->workstation_src_id)
                ->where('logical_server_src_id', $link->logical_server_src_id)
                ->where('network_switch_src_id', $link->network_switch_src_id)
                ->where('router_src_id', $link->router_src_id)
                ->exists())) {
                $validator->errors()->add('src_port', 'Source port already used !');
            }
            if (($link->dest_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('src_port', $link->dest_port)
                ->where('peripheral_src_id', $link->peripheral_dest_id)
                ->where('phone_src_id', $link->phone_dest_id)
                ->where('physical_router_src_id', $link->physical_router_dest_id)
                ->where('physical_security_device_src_id', $link->physical_security_device_dest_id)
                ->where('physical_server_src_id', $link->physical_server_dest_id)
                ->where('physical_switch_src_id', $link->physical_switch_dest_id)
                ->where('storage_device_src_id', $link->storage_device_dest_id)
                ->where('wifi_terminal_src_id', $link->wifi_terminal_dest_id)
                ->where('workstation_src_id', $link->workstation_dest_id)
                ->where('logical_server_src_id', $link->logical_server_dest_id)
                ->where('network_switch_src_id', $link->network_switch_dest_id)
                ->where('router_src_id', $link->router_dest_id)
                ->exists())) {
                $validator->errors()->add('dest_port', 'Destination port already used 1!');
            }
            if (($link->dest_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('dest_port', $link->dest_port)
                ->where('peripheral_dest_id', $link->peripheral_dest_id)
                ->where('phone_dest_id', $link->phone_dest_id)
                ->where('physical_router_dest_id', $link->physical_router_dest_id)
                ->where('physical_security_device_dest_id', $link->physical_security_device_dest_id)
                ->where('physical_server_dest_id', $link->physical_server_dest_id)
                ->where('physical_switch_dest_id', $link->physical_switch_dest_id)
                ->where('storage_device_dest_id', $link->storage_device_dest_id)
                ->where('wifi_terminal_dest_id', $link->wifi_terminal_dest_id)
                ->where('workstation_dest_id', $link->workstation_dest_id)
                ->where('logical_server_dest_id', $link->logical_server_dest_id)
                ->where('network_switch_dest_id', $link->network_switch_dest_id)
                ->where('router_dest_id', $link->router_dest_id)
                ->exists())) {
                $validator->errors()->add('dest_port', 'Destination port already used 2!');
            }
            if (($link->src_port !== null) && (DB::table('physical_links')
                ->where('id', '!=', $link->id)
                ->where('dest_port', $link->src_port)
                ->where('peripheral_dest_id', $link->peripheral_src_id)
                ->where('phone_dest_id', $link->phone_src_id)
                ->where('physical_router_dest_id', $link->physical_router_src_id)
                ->where('physical_security_device_dest_id', $link->physical_security_device_src_id)
                ->where('physical_server_dest_id', $link->physical_server_src_id)
                ->where('physical_switch_dest_id', $link->physical_switch_src_id)
                ->where('storage_device_dest_id', $link->storage_device_src_id)
                ->where('wifi_terminal_dest_id', $link->wifi_terminal_src_id)
                ->where('workstation_dest_id', $link->workstation_src_id)
                ->where('logical_server_dest_id', $link->logical_server_src_id)
                ->where('network_switch_dest_id', $link->network_switch_src_id)
                ->where('router_dest_id', $link->router_src_id)
                ->exists())) {
                $validator->errors()->add('src_port', 'Source port already used !');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update
        $link->update();

        return redirect()->route('admin.links.index');
    }

    public function show(PhysicalLink $link)
    {
        abort_if(Gate::denies('physical_link_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.links.show', compact('link'));
    }

    public function destroy(PhysicalLink $link)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $link->delete();

        return redirect()->route('admin.links.index');
    }

    public function massDestroy(MassDestroyPhysicalLinkRequest $request)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalLink::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
