<?php

namespace App\Http\Controllers\Admin;

use App\Cluster;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClusterRequest;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;
use App\LogicalServer;
use App\PhysicalServer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ClusterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cluster_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clusters = Cluster::with('physicalServers', 'logicalServers')->orderBy('name')->get();

        return view('admin.clusters.index', compact('clusters'));
    }

    public function create()
    {
        abort_if(Gate::denies('cluster_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $physical_servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');

        // List
        $type_list = Cluster::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.clusters.create',
            compact('logical_servers', 'physical_servers', 'type_list')
        );
    }

    public function store(StoreClusterRequest $request)
    {
        $cluster = Cluster::create($request->all());

        // update logical servers
        LogicalServer::whereIn('id', $request->input('logical_servers', []))
            ->update(['cluster_id' => $cluster->id]);

        // update physical servers
        PhysicalServer::whereIn('id', $request->input('physical_servers', []))
            ->update(['cluster_id' => $cluster->id]);

        return redirect()->route('admin.clusters.index');
    }

    public function edit(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $physical_servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');

        // List
        $type_list = Cluster::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.clusters.edit',
            compact('cluster', 'logical_servers', 'physical_servers', 'type_list')
        );
    }

    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        $cluster->update($request->all());

        // update logical servers
        LogicalServer::where('cluster_id', $cluster->id)
            ->update(['cluster_id' => null]);

        LogicalServer::whereIn('id', $request->input('logical_servers', []))
            ->update(['cluster_id' => $cluster->id]);

        // update physical servers
        PhysicalServer::where('cluster_id', $cluster->id)
            ->update(['cluster_id' => null]);

        PhysicalServer::whereIn('id', $request->input('physical_servers', []))
            ->update(['cluster_id' => $cluster->id]);

        return redirect()->route('admin.clusters.index');
    }

    public function show(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clusters.show', compact('cluster'));
    }

    public function destroy(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->delete();

        return redirect()->route('admin.clusters.index');
    }

    public function massDestroy(MassDestroyClusterRequest $request)
    {
        Cluster::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
