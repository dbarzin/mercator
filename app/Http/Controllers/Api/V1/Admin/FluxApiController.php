<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Flux;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFluxRequest;
use App\Http\Requests\UpdateFluxRequest;
use App\Http\Resources\Admin\FluxResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FluxApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('flux_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FluxResource(Flux::with(['application_source', 'service_source', 'module_source', 'database_source', 'application_dest', 'service_dest', 'module_dest', 'database_dest'])->get());
    }

    public function store(StoreFluxRequest $request)
    {
        $flux = Flux::create($request->all());

        return (new FluxResource($flux))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Flux $flux)
    {
        abort_if(Gate::denies('flux_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FluxResource($flux->load(['application_source', 'service_source', 'module_source', 'database_source', 'application_dest', 'service_dest', 'module_dest', 'database_dest']));
    }

    public function update(UpdateFluxRequest $request, Flux $flux)
    {
        $flux->update($request->all());

        return (new FluxResource($flux))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Flux $flux)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
