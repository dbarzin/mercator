<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVlanRequest;
use App\Http\Requests\UpdateVlanRequest;
use App\Http\Resources\Admin\VlanResource;
use App\Vlan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VlanApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('vlan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VlanResource(Vlan::all());
    }

    public function store(StoreVlanRequest $request)
    {
        $vlan = Vlan::create($request->all());

        return (new VlanResource($vlan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VlanResource($vlan);
    }

    public function update(UpdateVlanRequest $request, Vlan $vlan)
    {
        $vlan->update($request->all());

        return (new VlanResource($vlan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
