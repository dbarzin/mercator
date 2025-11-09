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

    /**
     * Create a new NetworkSwitch resource and persist its relationships.
     *
     * Creates a NetworkSwitch from validated request data and synchronizes its
     * `physicalSwitches` and `vlans` relationships using the corresponding
     * request inputs (defaults to empty arrays if not provided).
     *
     * @param StoreNetworkSwitchRequest $request Validated input for creating the network switch.
     * @return \Illuminate\Http\JsonResponse The created NetworkSwitch serialized as JSON with HTTP status 201.
     */
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

    /**
     * Update the specified NetworkSwitch with request data and, when provided, synchronize its related physical switches and VLANs.
     *
     * Aborts with HTTP 403 if the current user lacks the `network_switch_edit` permission.
     *
     * @param \App\Http\Requests\UpdateNetworkSwitchRequest $request Request containing fields to update; may include `physicalSwitches` and/or `vlans` arrays to sync relationships.
     * @param \App\Models\NetworkSwitch $networkSwitch The NetworkSwitch model to update.
     * @return \Illuminate\Http\JsonResponse An empty JSON response with HTTP 200 status.
     */
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