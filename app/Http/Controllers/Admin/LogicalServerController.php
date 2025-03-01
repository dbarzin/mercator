<?php

namespace App\Http\Controllers\Admin;

use App\Cluster;
use App\Database;
use App\DomaineAd;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalServerRequest;
use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use App\LogicalServer;
use App\MApplication;
use App\PhysicalServer;
use App\Services\CartographerService;
use Gate;
use Symfony\Component\HttpFoundation\Response;

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

    public function index()
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServers = LogicalServer::with('applications:id,name', 'servers:id,name', 'cluster:id,name')->orderBy('name')->get();

        return view('admin.logicalServers.index', compact('logicalServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::with('cartographers')->get();
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

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
                'servers',
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

        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));
        $logicalServer->databases()->sync($request->input('databases', []));

        return redirect()->route('admin.logical-servers.index');
    }

    public function edit(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

        $applications = MApplication::with('cartographers')->get();
        // Filtre sur les cartographes si nécessaire
        $applications = $this->cartographerService->filterOnCartographers($applications);

        $type_list = LogicalServer::select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');
        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');
        $attributes_list = $this->getAttributes();

        $logicalServer->load('servers', 'applications');

        return view(
            'admin.logicalServers.edit',
            compact(
                'domains',
                'clusters',
                'servers',
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

        $logicalServer->update($request->all());

        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));
        $logicalServer->databases()->sync($request->input('databases', []));

        return redirect()->route('admin.logical-servers.index');
    }

    public function show(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->load('servers', 'applications');

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
