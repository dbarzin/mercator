<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePhysicalRouterRequest;
use App\Http\Requests\UpdatePhysicalRouterRequest;
use App\Http\Resources\Admin\PhysicalRouterResource;
use App\PhysicalRouter;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalRouterApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('physical_router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalRouterResource(PhysicalRouter::with(['site', 'building', 'bay', 'vlans'])->get());
    }

    public function store(StorePhysicalRouterRequest $request)
    {
        $physicalRouter = PhysicalRouter::create($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));

        return (new PhysicalRouterResource($physicalRouter))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalRouterResource($physicalRouter->load(['site', 'building', 'bay', 'vlans']));
    }

    public function update(UpdatePhysicalRouterRequest $request, PhysicalRouter $physicalRouter)
    {
        $physicalRouter->update($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));

        return (new PhysicalRouterResource($physicalRouter))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
