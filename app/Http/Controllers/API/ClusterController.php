<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClusterRequest;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Mercator\Core\Models\Cluster;
use Symfony\Component\HttpFoundation\Response;

class ClusterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cluster_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clusters = Cluster::all();

        return response()->json($clusters);
    }

    public function store(StoreClusterRequest $request)
    {
        Log::Debug('ClusterController:store Start');

        abort_if(Gate::denies('cluster_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster = Cluster::query()->create($request->all());

        $cluster->logicalServers()->sync($request->input('logical_servers', []));
        $cluster->physicalServers()->sync($request->input('physical_servers', []));
        $cluster->routers()->sync($request->input('routers', []));

        Log::Debug('ClusterController:store Done');

        return response()->json($cluster, 201);
    }

    public function show(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($cluster);
    }

    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->update($request->all());

        if ($request->has('logical_servers'))
            $cluster->logicalServers()->sync($request->input('logical_servers', []));
        if ($request->has('physical_servers'))
            $cluster->physicalServers()->sync($request->input('physical_servers', []));
        if ($request->has('routers'))
           $cluster->routers()->sync($request->input('routers', []));

        return response()->json();
    }

    public function destroy(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyClusterRequest $request)
    {
        abort_if(Gate::denies('cluster_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Cluster::query()->whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
