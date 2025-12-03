<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalFlowRequest;
use App\Http\Requests\StoreLogicalFlowRequest;
use App\Http\Requests\UpdateLogicalFlowRequest;
use App\Rules\Cidr;
use Gate;
use Illuminate\Support\Collection;
use Mercator\Core\Models\LogicalFlow;
use Mercator\Core\Models\LogicalServer;
use Mercator\Core\Models\Peripheral;
use Mercator\Core\Models\PhysicalSecurityDevice;
use Mercator\Core\Models\PhysicalServer;
use Mercator\Core\Models\Router;
use Mercator\Core\Models\SecurityDevice;
use Mercator\Core\Models\StorageDevice;
use Mercator\Core\Models\Subnetwork;
use Mercator\Core\Models\Workstation;
use Symfony\Component\HttpFoundation\Response;

// Models
// Framework

class LogicalFlowController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_flow_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlows = LogicalFlow::with([
            'router',
            'logicalServerSource',
            'peripheralSource',
            'physicalServerSource',
            'storageDeviceSource',
            'workstationSource',
            'physicalSecurityDeviceSource',
            'subnetworkSource',
            'logicalServerDest',
            'peripheralDest',
            'physicalServerDest',
            'storageDeviceDest',
            'workstationDest',
            'physicalSecurityDeviceDest',
            'subnetworkDest',
            ])
            ->orderby('name')
            ->get();

        return view('admin.logicalFlows.index', compact('logicalFlows'));
    }

    public function create()
    {
        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $routers = Router::query()->orderBy('name')->pluck('name', 'id');

        // Get Equipments
        $logicalServers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');
        $peripherals = Peripheral::query()->orderBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::query()->orderBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::query()->orderBy('name')->pluck('name', 'id');
        $workstations = Workstation::query()->orderBy('name')->pluck('name', 'id');
        $securityDevices = SecurityDevice::query()->orderBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::query()->orderBy('name')->pluck('name', 'id');
        $subnetworks = Subnetwork::query()->orderBy('name')->pluck('name', 'id');

        // Build device list
        $devices = Collection::make();
        foreach ($logicalServers as $key => $value) {
            $devices->put('LSERVER_'.$key, $value);
        }
        foreach ($peripherals as $key => $value) {
            $devices->put('PERIF_'.$key, $value);
        }
        foreach ($physicalServers as $key => $value) {
            $devices->put('PSERVER_'.$key, $value);
        }
        foreach ($storageDevices as $key => $value) {
            $devices->put('STORAGE_'.$key, $value);
        }
        foreach ($workstations as $key => $value) {
            $devices->put('WORK_'.$key, $value);
        }
        foreach ($physicalSecurityDevices as $key => $value) {
            $devices->put('PSECURITY_'.$key, $value);
        }
        foreach ($securityDevices as $key => $value) {
            $devices->put('LSECURITY_'.$key, $value);
        }
        foreach ($subnetworks as $key => $value) {
            $devices->put('SUBNETWORK_'.$key, $value);
        }

        // Lists
        $interface_list = LogicalFlow::select('interface')->whereNotNull('interface')->distinct()->orderBy('interface')->pluck('interface');
        $protocol_list = LogicalFlow::select('protocol')->whereNotNull('protocol')->distinct()->orderBy('protocol')->pluck('protocol');

        return view(
            'admin.logicalFlows.create',
            compact('routers', 'devices',
                'interface_list', 'protocol_list')
        );
    }

    public function store(StoreLogicalFlowRequest $request)
    {
        $this->validate($request, [
                'source_ip_range' => [
                    new Cidr,
                    'nullable',
                    'required_without:src_id',
                ],
                'src_id' => [
                    'nullable',
                    'required_without:source_ip_range',
                ],
                'dest_ip_range' => [
                    new Cidr,
                    'nullable',
                    'required_without:dest_id',
                ],
                'dest_id' => [
                    'nullable',
                    'required_without:dest_ip_range',
                ]
            ]
        );

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
            $link->workstation_source_id = intval(substr($request->src_id, 5));
        } elseif (str_starts_with($request->src_id, 'PSECURITY_')) {
            $link->physical_security_device_source_id = intval(substr($request->src_id, 10));
        } elseif (str_starts_with($request->src_id, 'LSECURITY_')) {
            $link->security_device_source_id = intval(substr($request->src_id, 10));
        } elseif (str_starts_with($request->src_id, 'SUBNETWORK_')) {
            $link->subnetwork_source_id = intval(substr($request->src_id, 11));
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
            $link->workstation_dest_id = intval(substr($request->dest_id, 5));
        } elseif (str_starts_with($request->dest_id, 'PSECURITY_')) {
            $link->physical_security_device_dest_id = intval(substr($request->dest_id, 10));
        } elseif (str_starts_with($request->dest_id, 'LSECURITY_')) {
            $link->security_device_dest_id = intval(substr($request->dest_id, 10));
        } elseif (str_starts_with($request->dest_id, 'SUBNETWORK_')) {
            $link->subnetwork_dest_id = intval(substr($request->dest_id, 11));
        }

        $link->update();

        return redirect()->route('admin.logical-flows.index');
    }

    public function edit(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $routers = Router::query()->orderBy('name')->pluck('name', 'id');

        // Get Equipments
        $logicalServers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');
        $peripherals = Peripheral::query()->orderBy('name')->pluck('name', 'id');
        $physicalServers = PhysicalServer::query()->orderBy('name')->pluck('name', 'id');
        $storageDevices = StorageDevice::query()->orderBy('name')->pluck('name', 'id');
        $workstations = Workstation::query()->orderBy('name')->pluck('name', 'id');
        $physicalSecurityDevices = PhysicalSecurityDevice::query()->orderBy('name')->pluck('name', 'id');
        $securityDevices = PhysicalSecurityDevice::query()->orderBy('name')->pluck('name', 'id');
        $subnetworks = Subnetwork::query()->orderBy('name')->pluck('name', 'id');

        // Build device list
        $devices = Collection::make();
        foreach ($logicalServers as $key => $value) {
            $devices->put('LSERVER_'.$key, $value);
        }
        foreach ($peripherals as $key => $value) {
            $devices->put('PERIF_'.$key, $value);
        }
        foreach ($physicalServers as $key => $value) {
            $devices->put('PSERVER_'.$key, $value);
        }
        foreach ($storageDevices as $key => $value) {
            $devices->put('STORAGE_'.$key, $value);
        }
        foreach ($workstations as $key => $value) {
            $devices->put('WORK_'.$key, $value);
        }
        foreach ($physicalSecurityDevices as $key => $value) {
            $devices->put('PSECURITY_'.$key, $value);
        }
        foreach ($securityDevices as $key => $value) {
            $devices->put('LSECURITY_'.$key, $value);
        }
        foreach ($subnetworks as $key => $value) {
            $devices->put('SUBNETWORK_'.$key, $value);
        }

        // Lists
        $interface_list = LogicalFlow::select('interface')->whereNotNull('interface')->distinct()->orderBy('interface')->pluck('interface');
        $protocol_list = LogicalFlow::select('protocol')->whereNotNull('protocol')->distinct()->orderBy('protocol')->pluck('protocol');

        return view(
            'admin.logicalFlows.edit',
            compact('logicalFlow', 'devices', 'routers',
                'interface_list', 'protocol_list')
        );
    }

    public function update(UpdateLogicalFlowRequest $request, LogicalFlow $logicalFlow)
    {
        $this->validate($request, [
                'source_ip_range' => [
                    new Cidr,
                    'nullable',
                    'required_without:src_id',
                ],
                'src_id' => [
                    'nullable',
                    'required_without:source_ip_range',
                ],
                'dest_ip_range' => [
                    new Cidr,
                    'nullable',
                    'required_without:dest_id',
                ],
                'dest_id' => [
                    'nullable',
                    'required_without:dest_ip_range',
                ]
            ]
        );

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
        } elseif (str_starts_with($request->src_id, 'PSECURITY_')) {
            $logicalFlow->physical_security_device_source_id = intval(substr($request->src_id, 10));
        } elseif (str_starts_with($request->src_id, 'LSECURITY_')) {
            $logicalFlow->security_device_source_id = intval(substr($request->src_id, 10));
        } elseif (str_starts_with($request->src_id, 'SUBNETWORK_')) {
            $logicalFlow->subnetwork_source_id = intval(substr($request->src_id, 11));
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
        } elseif (str_starts_with($request->dest_id, 'PSECURITY_')) {
            $logicalFlow->physical_security_device_dest_id = intval(substr($request->dest_id, 10));
        } elseif (str_starts_with($request->dest_id, 'LSECURITY_')) {
            $logicalFlow->security_device_dest_id = intval(substr($request->dest_id, 10));
        } elseif (str_starts_with($request->dest_id, 'SUBNETWORK_')) {
            $logicalFlow->subnetwork_dest_id = intval(substr($request->dest_id, 11));
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
