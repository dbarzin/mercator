<?php

namespace App\Http\Controllers\API;

use App\Cluster;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClusterRequest;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;
use App\Http\Resources\Admin\ClusterResource;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ClusterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalservers = Cluster::all();

        return response()->json($logicalservers);
    }

    public function store(StoreClusterRequest $request)
    {
        Log::Debug('ClusterController:store Start');

        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster = Cluster::create($request->all());
        #$cluster->servers()->sync($request->input('servers', []));
        #$cluster->applications()->sync($request->input('applications', []));

        Log::Debug('ClusterController:store Done');

        return response()->json($cluster, 201);
    }

    public function show(Cluster $cluster)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClusterResource($cluster);
    }

    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->update($request->all());
        #$cluster->servers()->sync($request->input('servers', []));
        #$cluster->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(Cluster $cluster)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyClusterRequest $request)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Cluster::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
