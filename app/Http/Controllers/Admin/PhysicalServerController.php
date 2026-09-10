<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalServerRequest;
use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use App\Models\Application;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\Cluster;
use App\Models\LogicalServer;
use App\Models\PhysicalServer;
use App\Models\Site;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Laravel

class PhysicalServerController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('physical_server_access') ? null : Cartographer::allowedIdsFor($user, PhysicalServer::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $physicalServers = PhysicalServer::with('site', 'building', 'bay')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (PhysicalServer::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.physicalServers.index', compact('physicalServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $icons = PhysicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // List
        $application_list = Application::orderBy('name')->pluck('name', 'id');
        $operating_system_list = PhysicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $logical_server_list = LogicalServer::orderBy('name')->pluck('name', 'id');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.physicalServers.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'clusters',
                'icons',
                'buildingSiteMap',
                'bayBuildingMap',
                'application_list',
                'operating_system_list',
                'responsible_list',
                'type_list',
                'logical_server_list',
                'attributes_list'
            )
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $icons = PhysicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // List
        $application_list = Application::orderBy('name')->pluck('name', 'id');
        $operating_system_list = PhysicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $logical_server_list = LogicalServer::orderBy('name')->pluck('name', 'id');
        $attributes_list = $this->getAttributes();

        // Get PhysicalServer
        $physicalServer = PhysicalServer::find($request['id']);

        // PhysicalServer not found
        abort_if($physicalServer === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $physicalServer->only($physicalServer->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->merge(['applications' => $physicalServer->applications()->pluck('id')->unique()->toArray()]);
        $request->merge(['logicalServers' => $physicalServer->logicalServers()->pluck('id')->unique()->toArray()]);
        $request->merge(['clusters' => $physicalServer->clusters()->pluck('id')->unique()->toArray()]);
        $request->flash();

        return view(
            'admin.physicalServers.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'icons',
                'clusters',
                'buildingSiteMap',
                'bayBuildingMap',
                'application_list',
                'operating_system_list',
                'responsible_list',
                'type_list',
                'logical_server_list',
                'attributes_list'
            )
        );
    }

    public function store(StorePhysicalServerRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $physicalServer = PhysicalServer::query()->create($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $physicalServer);

        // Save LogicalServer
        $physicalServer->save();

        // Save Relations
        $physicalServer->applications()->sync($request->input('applications', []));
        $physicalServer->logicalServers()->sync($request->input('logicalServers', []));
        $physicalServer->clusters()->sync($request->input('clusters', []));

        return redirect()->route('admin.physical-servers.index');
    }

    public function edit(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('edit-object', $physicalServer), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $icons = PhysicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // List
        $operating_system_list = PhysicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $application_list = Application::orderBy('name')->pluck('name', 'id');
        $logical_server_list = LogicalServer::orderBy('name')->pluck('name', 'id');
        $attributes_list = $this->getAttributes();

        $physicalServer->load('site', 'building', 'bay');

        return view(
            'admin.physicalServers.edit',
            compact(
                'sites',
                'buildings',
                'bays',
                'clusters',
                'icons',
                'buildingSiteMap',
                'bayBuildingMap',
                'application_list',
                'logical_server_list',
                'responsible_list',
                'operating_system_list',
                'type_list',
                'physicalServer',
                'attributes_list'
            )
        );
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('edit-object', $physicalServer), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Save icon
        $this->iconUploadService->handle($request, $physicalServer);

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Update PhysicalServer
        $physicalServer->update($request->all());

        // Relations
        $physicalServer->applications()->sync($request->input('applications', []));
        $physicalServer->logicalServers()->sync($request->input('logicalServers', []));
        $physicalServer->clusters()->sync($request->input('clusters', []));

        return redirect()->route('admin.physical-servers.index');
    }

    public function show(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('show-object', $physicalServer), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->load('site', 'building', 'bay', 'logicalServers');

        return view('admin.physicalServers.show', compact('physicalServer'));
    }

    public function destroy(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->delete();

        return redirect()->route('admin.physical-servers.index');
    }

    public function massDestroy(MassDestroyPhysicalServerRequest $request)
    {
        PhysicalServer::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = PhysicalServer::query()
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
}
