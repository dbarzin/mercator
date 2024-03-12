<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSwitchRequest;
use App\Http\Requests\StorePhysicalSwitchRequest;
use App\Http\Requests\UpdatePhysicalSwitchRequest;
use App\Http\Resources\Admin\PhysicalSwitchResource;
use App\PhysicalSwitch;
use Gate;
use Illuminate\Http\Response;

class PhysicalSwitchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalswitchs = PhysicalSwitch::all();

        return response()->json($physicalswitchs);
    }

    public function store(StorePhysicalSwitchRequest $request)
    {
        abort_if(Gate::denies('physical_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalswitch = PhysicalSwitch::create($request->all());

        return response()->json($physicalswitch, 201);
    }

    public function show(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSwitchResource($physicalSwitch);
    }

    public function update(UpdatePhysicalSwitchRequest $request, PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->update($request->all());

        return response()->json();
    }

    public function destroy(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalSwitchRequest $request)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
