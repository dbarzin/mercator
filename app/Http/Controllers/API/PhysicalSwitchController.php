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
        // syncs
        // $physicalswitch->roles()->sync($request->input('roles', []));

        return response()->json($physicalswitch, 201);
    }

    public function show(PhysicalSwitch $physicalswitch)
    {
        abort_if(Gate::denies('physical_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSwitchResource($physicalswitch);
    }

    public function update(UpdatePhysicalSwitchRequest $request, PhysicalSwitch $physicalswitch)
    {
        abort_if(Gate::denies('physical_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalswitch->update($request->all());
        // syncs
        // $physicalswitch->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(PhysicalSwitch $physicalswitch)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalswitch->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalSwitchRequest $request)
    {
        PhysicalSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
