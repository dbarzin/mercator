<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNetworkSwitchRequest;
use App\Http\Requests\UpdateNetworkSwitchRequest;
use App\Http\Resources\Admin\NetworkSwitchResource;
use App\NetworkSwitch;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkSwitchApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('network_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NetworkSwitchResource(NetworkSwitch::all());
    }

    public function store(StoreNetworkSwitchRequest $request)
    {
        $networkSwitch = NetworkSwitch::create($request->all());

        return (new NetworkSwitchResource($networkSwitch))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NetworkSwitchResource($networkSwitch);
    }

    public function update(UpdateNetworkSwitchRequest $request, NetworkSwitch $networkSwitch)
    {
        $networkSwitch->update($request->all());

        return (new NetworkSwitchResource($networkSwitch))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
