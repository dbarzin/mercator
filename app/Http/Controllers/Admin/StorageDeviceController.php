<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStorageDeviceRequest;
use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use App\Models\Backup;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\LogicalServer;
use App\Models\Site;
use App\Models\StorageDevice;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StorageDeviceController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('storage_device_access') ? null : Cartographer::allowedIdsFor($user, StorageDevice::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $storageDevices = StorageDevice::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (StorageDevice::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.storageDevices.index', compact('storageDevices'));
    }

    public function create()
    {
        abort_if(Gate::denies('storage_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');
        $logicalServers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');
        $icons = StorageDevice::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $type_list = StorageDevice::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.storageDevices.create',
            compact('sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'type_list', 'logicalServers', 'icons', 'attributes_list')
        );
    }

    public function store(StoreStorageDeviceRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $storageDevice = StorageDevice::create($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $storageDevice);
        $storageDevice->save();

        // Backups
        if (Auth::user()->can('backup_create')) {
            $this->syncInlineBackupsForDevice($storageDevice, $request);
        }

        return redirect()->route('admin.storage-devices.index');
    }

    public function edit(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('edit-object', $storageDevice), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');
        $logicalServers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');
        $icons = StorageDevice::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $type_list = StorageDevice::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $storageDevice->load('site', 'building', 'bay', 'backups.logicalServers');

        return view(
            'admin.storageDevices.edit',
            compact('sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'type_list', 'storageDevice', 'logicalServers', 'icons', 'attributes_list')
        );
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('edit-object', $storageDevice), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Save icon
        $this->iconUploadService->handle($request, $storageDevice);

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $storageDevice->update($request->all());

        if (Auth::user()->can('backup_edit')) {
            // Detach and hard-delete orphaned backups linked only to this device
            $storageDevice->backups()->each(function (Backup $backup) use ($storageDevice) {
                $backup->storageDevices()->detach($storageDevice->id);
                if ($backup->storageDevices()->doesntExist() && $backup->logicalServers()->doesntExist()) {
                    $backup->forceDelete();
                }
            });

            $this->syncInlineBackupsForDevice($storageDevice, $request);
        }

        return redirect()->route('admin.storage-devices.index');
    }

    public function show(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('show-object', $storageDevice), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        StorageDevice::query()->whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = StorageDevice::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }

    private function syncInlineBackupsForDevice(StorageDevice $storageDevice, Request $request): void
    {
        $logicalServerIds = $request->input('logical_server_id', []);
        $frequencies = $request->input('backup_frequency', []);
        $cycles = $request->input('backup_cycle', []);
        $retentions = $request->input('backup_retention', []);

        if (empty($logicalServerIds)) {
            return;
        }

        $servers = LogicalServer::query()
            ->whereIn('id', $logicalServerIds)
            ->pluck('name', 'id');

        foreach ($logicalServerIds as $i => $logicalServerId) {
            $serverName = $servers[$logicalServerId] ?? $logicalServerId;
            $name = $storageDevice->name.' → '.$serverName;

            $base = mb_substr($name, 0, 240);
            $candidate = $base;
            $suffix = 1;
            while (Backup::query()->where('name', $candidate)->exists()) {
                $candidate = $base.' ('.$suffix++.')';
            }

            $backup = Backup::query()->create([
                'name' => $candidate,
                'backup_frequency' => isset($frequencies[$i]) ? (int) $frequencies[$i] : null,
                'backup_cycle' => isset($cycles[$i]) ? (int) $cycles[$i] : null,
                'backup_retention' => isset($retentions[$i]) ? (int) $retentions[$i] : null,
            ]);

            $backup->storageDevices()->attach($storageDevice->id);

            if ($logicalServerId) {
                $backup->logicalServers()->attach($logicalServerId);
            }
        }
    }
}
