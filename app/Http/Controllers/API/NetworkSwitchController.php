<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkSwitchRequest;
use App\Http\Requests\StoreNetworkSwitchRequest;
use App\Http\Requests\UpdateNetworkSwitchRequest;
use App\Http\Resources\Admin\NetworkSwitchResource;
use App\NetworkSwitch;
use Gate;
use Illuminate\Http\Response;

class NetworkSwitchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('networkswitch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkswitchs = NetworkSwitch::all();

        return response()->json($networkswitchs);
    }

    public function store(StoreNetworkSwitchRequest $request)
    {
        abort_if(Gate::denies('networkswitch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkswitch = NetworkSwitch::create($request->all());
        // syncs
        // $networkswitch->roles()->sync($request->input('roles', []));

        return response()->json($networkswitch, 201);
    }

    public function show(NetworkSwitch $networkswitch)
    {
        abort_if(Gate::denies('networkswitch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NetworkSwitchResource($networkswitch);
    }

    public function update(UpdateNetworkSwitchRequest $request, NetworkSwitch $networkswitch)
    {
        abort_if(Gate::denies('networkswitch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkswitch->update($request->all());
        // syncs
        // $networkswitch->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('networkswitch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyNetworkSwitchRequest $request)
    {
        NetworkSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
