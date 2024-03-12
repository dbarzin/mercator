<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkRequest;
use App\Http\Requests\StoreNetworkRequest;
use App\Http\Requests\UpdateNetworkRequest;
use App\Http\Resources\Admin\NetworkResource;
use App\Network;
use Gate;
use Illuminate\Http\Response;

class NetworkController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('network_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networks = Network::all();

        return response()->json($networks);
    }

    public function store(StoreNetworkRequest $request)
    {
        abort_if(Gate::denies('network_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network = Network::create($request->all());
        // syncs
        // $network->roles()->sync($request->input('roles', []));

        return response()->json($network, 201);
    }

    public function show(Network $network)
    {
        abort_if(Gate::denies('network_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NetworkResource($network);
    }

    public function update(UpdateNetworkRequest $request, Network $network)
    {
        abort_if(Gate::denies('network_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network->update($request->all());
        // syncs
        // $network->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Network $network)
    {
        abort_if(Gate::denies('network_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyNetworkRequest $request)
    {
        abort_if(Gate::denies('network_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Network::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
