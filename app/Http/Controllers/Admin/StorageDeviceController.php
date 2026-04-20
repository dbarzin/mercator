<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStorageDeviceRequest;
use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Backup;
use App\Models\Bay;
use App\Models\Building;
use App\Models\LogicalServer;
use App\Models\Site;
use App\Models\StorageDevice;
use Symfony\Component\HttpFoundation\Response;

class StorageDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('storage_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevices = StorageDevice::all()->sortBy('name');

        return view('admin.storageDevices.index', compact('storageDevices'));
    }

    public function create()
    {
        abort_if(Gate::denies('storage_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $logicalServers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');

        $type_list = StorageDevice::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.storageDevices.create',
            compact('sites', 'buildings', 'bays', 'type_list', 'logicalServers')
        );
    }

    public function store(StoreStorageDeviceRequest $request)
    {
        $storageDevice = StorageDevice::create($request->all());

        // Backups
        if (Auth::user()->can('backup_create')) {
            $logicalServerId = $request['logical_server_id'];
            $backupFrequency = $request['backup_frequency'];
            $backupCycle     = $request['backup_cycle'];
            $backupRetention = $request['backup_retention'];

            if ($logicalServerId !== null) {
                for ($i = 0; $i < count($logicalServerId); $i++) {
                    $backup = new Backup;
                    $backup->storage_device_id  = $storageDevice->id;
                    $backup->logical_server_id  = $logicalServerId[$i];
                    $backup->backup_frequency   = (int) $backupFrequency[$i];
                    $backup->backup_cycle       = (int) $backupCycle[$i];
                    $backup->backup_retention   = (int) $backupRetention[$i];
                    $backup->save();
                }
            }
        }

        return redirect()->route('admin.storage-devices.index');
    }

    public function edit(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $logicalServers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');

        $type_list = StorageDevice::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        $storageDevice->load('site', 'building', 'bay', 'backups');

        return view(
            'admin.storageDevices.edit',
            compact('sites', 'buildings', 'bays', 'type_list', 'storageDevice', 'logicalServers')
        );
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storageDevice)
    {
        $storageDevice->update($request->all());

        if (Auth::user()->can('backup_edit')) {
            // Suppression des anciens backups pour ce storage device
            Backup::query()->where('storage_device_id', $storageDevice->id)->delete();

            // Sauvegarde des nouveaux backups
            $logicalServerId = $request['logical_server_id'];
            $backupFrequency = $request['backup_frequency'];
            $backupCycle     = $request['backup_cycle'];
            $backupRetention = $request['backup_retention'];

            if ($logicalServerId !== null) {
                for ($i = 0; $i < count($logicalServerId); $i++) {
                    $backup = new Backup;
                    $backup->storage_device_id  = $storageDevice->id;
                    $backup->logical_server_id  = $logicalServerId[$i];
                    $backup->backup_frequency   = (int) $backupFrequency[$i];
                    $backup->backup_cycle       = (int) $backupCycle[$i];
                    $backup->backup_retention   = (int) $backupRetention[$i];
                    $backup->save();
                }
            }
        }

        return redirect()->route('admin.storage-devices.index');
    }

    public function show(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->load('site', 'building', 'bay', 'backups');

        return view('admin.storageDevices.show', compact('storageDevice'));
    }

    public function destroy(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->delete();

        return redirect()->route('admin.storage-devices.index');
    }

    public function massDestroy(MassDestroyStorageDeviceRequest $request)
    {
        StorageDevice::query()->whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}