<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalServerRequest;
use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use App\Models\Cluster;
use App\Models\Database;
use App\Models\DomaineAd;
use App\Models\LogicalServer;
use App\Models\MApplication;
use App\Models\PhysicalServer;
use App\Services\CartographerService;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class LogicalServerController extends Controller
{
    protected CartographerService $cartographerService;

    /**
     * Automatic Injection for Service
     *
     * @return void
     */
    public function __construct(CartographerService $cartographerService)
    {
        $this->cartographerService = $cartographerService;
    }

    public function getData(Request $request)
    {
        $logicalServers = LogicalServer::select('id', 'name', 'type', 'attributes', 'description')->get();

        return DataTables::of($logicalServers)->make(true);
    }

    public function index()
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /*
        // Normal code
        $logicalServers = LogicalServer
            ::with('applications:id,name', 'physicalServers:id,name', 'cluster:id,name')
            ->orderBy('name')
            ->get();
        */

        // Optimized code
        $result = DB::table('logical_servers as ls')
            ->select(
                'ls.*',
                'ma.id as m_application_id',
                'ma.name as m_application_name',
                'ps.id as physical_server_id',
                'ps.name as physical_server_name',
                'cl.id as cluster_id',
                'cl.name as cluster_name',
            )
            ->leftJoin('logical_server_m_application as lsma', 'ls.id', '=', 'lsma.logical_server_id')
            ->leftJoin('m_applications as ma', function ($join): void {
                $join->on('lsma.m_application_id', '=', 'ma.id')
                    ->whereNull('ma.deleted_at');
            })
            ->leftJoin('logical_server_physical_server as lsps', 'ls.id', '=', 'lsps.logical_server_id')
            ->leftJoin('cluster_logical_server as cls', 'ls.id', '=', 'cls.logical_server_id')
            ->leftJoin('clusters as cl', function ($join): void {
                $join->on('cls.cluster_id', '=', 'cl.id')
                    ->whereNull('ma.deleted_at');
            })
            ->leftJoin('physical_servers as ps', function ($join): void {
                $join->on('lsps.physical_server_id', '=', 'ps.id')
                    ->whereNull('ps.deleted_at');
            })
            ->whereNull('ls.deleted_at')
            ->orderBy('ls.name', 'asc')
            ->get();

        // Start Grouping Objects
        /*
        // Code v1
        $logicalServers = collect();
        $curLogicalServer = null;
        foreach ($result as $res) {
            if (($curLogicalServer === null) || ($curLogicalServer->id !== $res->id)) {
                $curLogicalServerId = $res;
                $curLogicalServer = (object) [
                    'id' => $res->id,
                    'name' => $res->name,
                    'description' => $res->description,
                    'active' => $res->active,
                    'operating_system' => $res->operating_system,
                    'environment' => $res->environment,
                    'type' => $res->type,
                    'attributes' => $res->attributes,
                    'configuration' => $res->configuration,
                    'address_ip' => $res->address_ip,
                    'cluster' => $res->cluster_id === null ? null : (object) ['id' => $res->cluster_id, 'name' => $res->cluster_name ],
                        //    ...
                    'applications' => collect(),
                    'physicalServers' => collect(),
                ];
                $logicalServers->push($curLogicalServer);
            }
            // add application to list if not already in
            if (($res->m_application_id !== null) && ! $curLogicalServer->applications->contains(function ($item) use ($res) {
                return $item->id === $res->m_application_id;
            })) {
                $curLogicalServer->applications->push(
                    (object) [
                        'id' => $res->m_application_id,
                        'name' => $res->m_application_name,
                    ]
                );
            }

            // add physical server to list if not already in
            if (($res->physical_server_id !== null) && ! $curLogicalServer->physicalServers->contains(function ($item) use ($res) {
                return $item->id === $res->physical_server_id;
            })) {
                //dd($curLogicalServer);
                $curLogicalServer->physicalServers->push((object) [
                    'id' => $res->physical_server_id,
                    'name' => $res->physical_server_name,
                ]);
            }
        }
        */

        // Code v2
        $logicalServers = $result->groupBy('id')->map(function ($items) {
            $logicalServer = $items->first();

            return (object) [
                'id' => $logicalServer->id,
                'name' => $logicalServer->name,
                'description' => $logicalServer->description,
                'active' => $logicalServer->active,
                'operating_system' => $logicalServer->operating_system,
                'environment' => $logicalServer->environment,
                'type' => $logicalServer->type,
                'attributes' => $logicalServer->attributes,
                'configuration' => $logicalServer->configuration,
                'address_ip' => $logicalServer->address_ip,
                'applications' => $items->filter(function ($item) {
                    return ! is_null($item->m_application_id);
                })->unique('m_application_id')->map(function ($item) {
                    return (object) ['id' => $item->m_application_id, 'name' => $item->m_application_name];
                })->values(),
                'clusters' => $items->filter(function ($item) {
                    return ! is_null($item->cluster_id);
                })->unique('cluster_id')->map(function ($item) {
                    return (object) ['id' => $item->cluster_id, 'name' => $item->cluster_name];
                })->values(),
                'physicalServers' => $items->filter(function ($item) {
                    return ! is_null($item->physical_server_id);
                })->unique('physical_server_id')->map(function ($item) {
                    return (object) ['id' => $item->physical_server_id, 'name' => $item->physical_server_name];
                })->values(),
            ];
        })->values();

        return view('admin.logicalServers.index', compact('logicalServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::with('cartographers')->get();
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');
        $icons = LogicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // Filtre sur les cartographes si nécessaire
        $applications = $this->cartographerService->filterOnCartographers($applications);

        $type_list = LogicalServer::select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');
        $operating_system_list = LogicalServer::select('operating_system')->whereNotNull('operating_system')->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->whereNotNull('environment')->distinct()->orderBy('environment')->pluck('environment');
        $attributes_list = $this->getAttributes();

        // default active
        $active = true;

        return view(
            'admin.logicalServers.create',
            compact(
                'domains',
                'clusters',
                'icons',
                'physicalServers',
                'applications',
                'databases',
                'type_list',
                'environment_list',
                'operating_system_list',
                'attributes_list',
                'active'
            )
        );
    }

    public function store(StoreLogicalServerRequest $request)
    {
        $request['active'] = $request->has('active');
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $logicalServer = LogicalServer::create($request->all());

        // Save icon
        $this->handleIconUpload($request, $logicalServer);

        // Save LogicalServer
        $logicalServer->save();

        // Relations
        $logicalServer->physicalServers()->sync($request->input('physicalServers', []));
        $logicalServer->applications()->sync($request->input('applications', []));
        $logicalServer->databases()->sync($request->input('databases', []));
        $logicalServer->clusters()->sync($request->input('clusters', []));

        return redirect()->route('admin.logical-servers.index');
    }

    public function edit(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');
        $icons = LogicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $applications = MApplication::with('cartographers')->get();
        // Filtre sur les cartographes si nécessaire
        $applications = $this->cartographerService->filterOnCartographers($applications);

        $type_list = LogicalServer::select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');
        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');
        $attributes_list = $this->getAttributes();

        $logicalServer->load('physicalServers', 'applications');

        return view(
            'admin.logicalServers.edit',
            compact(
                'domains',
                'icons',
                'clusters',
                'physicalServers',
                'applications',
                'databases',
                'type_list',
                'operating_system_list',
                'environment_list',
                'attributes_list',
                'logicalServer'
            )
        );
    }

    public function update(UpdateLogicalServerRequest $request, LogicalServer $logicalServer)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $request['active'] = $request->has('active');

        // Save icon
        $this->handleIconUpload($request, $logicalServer);

        // Save LogicalServer
        $logicalServer->update($request->all());

        // Relations
        $logicalServer->physicalServers()->sync($request->input('physicalServers', []));
        $logicalServer->applications()->sync($request->input('applications', []));
        $logicalServer->databases()->sync($request->input('databases', []));
        $logicalServer->clusters()->sync($request->input('clusters', []));

        return redirect()->route('admin.logical-servers.index');
    }

    public function show(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->load('physicalServers', 'applications');

        return view('admin.logicalServers.show', compact('logicalServer'));
    }

    public function destroy(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->delete();

        return redirect()->route('admin.logical-servers.index');
    }

    public function massDestroy(MassDestroyLogicalServerRequest $request)
    {
        LogicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = LogicalServer::select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
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
