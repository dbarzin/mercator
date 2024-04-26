<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalRouterRequest;
use App\Http\Requests\StorePhysicalRouterRequest;
use App\Http\Requests\UpdatePhysicalRouterRequest;
use App\Http\Resources\Admin\PhysicalRouterResource;
use App\PhysicalRouter;
use Gate;
use Illuminate\Http\Response;

class PhysicalRouterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalrouters = PhysicalRouter::all();

        return response()->json($physicalrouters);
    }

    public function store(StorePhysicalRouterRequest $request)
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalrouter = PhysicalRouter::create($request->all());
        $physicalrouter->vlans()->sync($request->input('vlans', []));
        // syncs
        // $physicalrouter->roles()->sync($request->input('roles', []));

        return response()->json($physicalrouter, 201);
    }

    public function show(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalRouterResource($physicalRouter);
    }

    public function update(UpdatePhysicalRouterRequest $request, PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->update($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));
        // syncs
        // $physicalrouter->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalRouterRequest $request)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalRouter::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
