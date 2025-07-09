<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalFlowRequest;
use App\Http\Requests\StoreLogicalFlowRequest;
use App\Http\Requests\UpdateLogicalFlowRequest;
// Models
use App\LogicalFlow;
use App\LogicalServer;
use App\Peripheral;
use App\PhysicalSecurityDevice;
use App\PhysicalServer;
use App\Router;
use App\StorageDevice;
use App\Workstation;
// Framework
use Gate;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class LogicalFlowController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_flow_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlows = LogicalFlow::orderBy('name')->get();

        return view('admin.logicalFlows.index', compact('logicalFlows'));
    }

    public function create()
    {
        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $routers = Router::all()->sortBy('name')->pluck('name', 'id');

        // Get Equipemnts
        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $peripherals = Peripheral::all()->sortBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::all()->sortBy('name')->pluck('name', 'id');
        $workstations = Workstation::all()->sortBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        // Build device list
        $devices = Collection::make();
        foreach ($logicalServers as $key => $value) {
            $devices->put('LSERVER_' . $key, $value);
        }
        foreach ($peripherals as $key => $value) {
            $devices->put('PERIF_' . $key, $value);
        }
        foreach ($physicalServers as $key => $value) {
            $devices->put('PSERVER_' . $key, $value);
        }
        foreach ($storageDevices as $key => $value) {
            $devices->put('STORAGE_' . $key, $value);
        }
        foreach ($workstations as $key => $value) {
            $devices->put('WORK_' . $key, $value);
        }
        foreach ($physicalSecurityDevices as $key => $value) {
            $devices->put('SECURITY_' . $key, $value);
        }

        // Lists
        $protocol_list = LogicalFlow::select('protocol')->whereNotNull('protocol')->distinct()->orderBy('protocol')->pluck('protocol');

        return view(
            'admin.logicalFlows.create',
            compact('routers', 'devices', 'protocol_list')
        );
    }

    public function store(StoreLogicalFlowRequest $request)
    {
        $link = LogicalFlow::create($request->all());

        // Source device
        if (str_starts_with($request->src_id, 'LSERVER_')) {
            $link->logical_server_source_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'PERIF_')) {
            $link->peripheral_source_id = intval(substr($request->src_id, 6));
        } elseif (str_starts_with($request->src_id, 'PSERVER_')) {
            $link->physical_server_source_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'STORAGE_')) {
            $link->storage_device_source_id = intval(substr($request->src_id, 4));
        } elseif (str_starts_with($request->src_id, 'WORK_')) {
            $link->workstation_source_id = intval(substr($request->src_id, 4));
        } elseif (str_starts_with($request->src_id, 'SECURITY_')) {
            $link->physical_security_device_source_id = intval(substr($request->src_id, 9));
        }
        // Dest device
        if (str_starts_with($request->dest_id, 'LSERVER_')) {
            $link->logical_server_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'PERIF_')) {
            $link->peripheral_dest_id = intval(substr($request->dest_id, 6));
        } elseif (str_starts_with($request->dest_id, 'PSERVER_')) {
            $link->physical_server_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'STORAGE_')) {
            $link->storage_device_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'WORK_')) {
            $link->workstation_dest_id = intval(substr($request->dest_id, 4));
        } elseif (str_starts_with($request->dest_id, 'SECURITY_')) {
            $link->physical_security_device_dest_id = intval(substr($request->dest_id, 9));
        }

        $link->update();

        return redirect()->route('admin.logical-flows.index');
    }

    public function edit(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $routers = Router::all()->sortBy('name')->pluck('name', 'id');

        // Get Equipemnts
        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $peripherals = Peripheral::all()->sortBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::all()->sortBy('name')->pluck('name', 'id');
        $workstations = Workstation::all()->sortBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        // Build device list
        $devices = Collection::make();
        foreach ($logicalServers as $key => $value) {
            $devices->put('LSERVER_' . $key, $value);
        }
        foreach ($peripherals as $key => $value) {
            $devices->put('PERIF_' . $key, $value);
        }
        foreach ($physicalServers as $key => $value) {
            $devices->put('PSERVER_' . $key, $value);
        }
        foreach ($storageDevices as $key => $value) {
            $devices->put('STORAGE_' . $key, $value);
        }
        foreach ($workstations as $key => $value) {
            $devices->put('WORK_' . $key, $value);
        }
        foreach ($physicalSecurityDevices as $key => $value) {
            $devices->put('SECURITY_' . $key, $value);
        }

        // Lists
        $protocol_list = LogicalFlow::select('protocol')->whereNotNull('protocol')->distinct()->orderBy('protocol')->pluck('protocol');

        return view(
            'admin.logicalFlows.edit',
            compact('logicalFlow', 'devices', 'routers', 'protocol_list')
        );
    }

    public function update(UpdateLogicalFlowRequest $request, LogicalFlow $logicalFlow)
    {
        // Source device
        if (str_starts_with($request->src_id, 'LSERVER_')) {
            $logicalFlow->logical_server_source_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'PERIF_')) {
            $logicalFlow->peripheral_source_id = intval(substr($request->src_id, 6));
        } elseif (str_starts_with($request->src_id, 'PSERVER_')) {
            $logicalFlow->physical_server_source_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'STORAGE_')) {
            $logicalFlow->storage_device_source_id = intval(substr($request->src_id, 8));
        } elseif (str_starts_with($request->src_id, 'WORK_')) {
            $logicalFlow->workstation_source_id = intval(substr($request->src_id, 5));
        } elseif (str_starts_with($request->src_id, 'SECURITY_')) {
            $logicalFlow->physical_security_device_source_id = intval(substr($request->src_id, 9));
        }
        // Dest device
        if (str_starts_with($request->dest_id, 'LSERVER_')) {
            $logicalFlow->logical_server_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'PERIF_')) {
            $logicalFlow->peripheral_dest_id = intval(substr($request->dest_id, 6));
        } elseif (str_starts_with($request->dest_id, 'PSERVER_')) {
            $logicalFlow->physical_server_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'STORAGE_')) {
            $logicalFlow->storage_device_dest_id = intval(substr($request->dest_id, 8));
        } elseif (str_starts_with($request->dest_id, 'WORK_')) {
            $logicalFlow->workstation_dest_id = intval(substr($request->dest_id, 5));
        } elseif (str_starts_with($request->dest_id, 'SECURITY_')) {
            $logicalFlow->physical_security_device_dest_id = intval(substr($request->dest_id, 9));
        }

        $logicalFlow->update($request->all());

        return redirect()->route('admin.logical-flows.index');
    }

    public function show(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.logicalFlows.show', compact('logicalFlow'));
    }

    public function destroy(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlow->delete();

        return redirect()->route('admin.logical-flows.index');
    }

    public function massDestroy(MassDestroyLogicalFlowRequest $request)
    {
        LogicalFlow::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
