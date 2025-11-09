<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkSwitchRequest;
use App\Http\Requests\StoreNetworkSwitchRequest;
use App\Http\Requests\UpdateNetworkSwitchRequest;
use App\Models\NetworkSwitch;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class NetworkSwitchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('network_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitches = NetworkSwitch::all();

        return response()->json($networkSwitches);
    }

    public function store(StoreNetworkSwitchRequest $request)
    {
        abort_if(Gate::denies('network_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch = NetworkSwitch::create($request->all());
        $networkSwitch->physicalSwitches()->sync($request->input('physicalSwitches', []));
        $networkSwitch->vlans()->sync($request->input('vlans', []));

        return response()->json($networkSwitch, 201);
    }

    public function show(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($networkSwitch);
    }

    public function update(UpdateNetworkSwitchRequest $request, NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch->update($request->all());
        if ($request->has('physicalSwitches'))
            $networkSwitch->physicalSwitches()->sync($request->input('physicalSwitches', []));
        if ($request->has('vlans'))
            $networkSwitch->vlans()->sync($request->input('vlans', []));

        return response()->json();
    }

    public function destroy(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyNetworkSwitchRequest $request)
    {
        abort_if(Gate::denies('network_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        NetworkSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
