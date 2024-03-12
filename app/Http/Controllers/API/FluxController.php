<?php

namespace App\Http\Controllers\API;

use App\Flux;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFluxRequest;
use App\Http\Requests\StoreFluxRequest;
use App\Http\Requests\UpdateFluxRequest;
use App\Http\Resources\Admin\FluxResource;
use Gate;
use Illuminate\Http\Response;

class FluxController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('flux_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fluxs = Flux::all();

        return response()->json($fluxs);
    }

    public function store(StoreFluxRequest $request)
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux = Flux::create($request->all());

        return response()->json($flux, 201);
    }

    public function show(Flux $flux)
    {
        abort_if(Gate::denies('flux_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FluxResource($flux);
    }

    public function update(UpdateFluxRequest $request, Flux $flux)
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->update($request->all());

        return response()->json();
    }

    public function destroy(Flux $flux)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyFluxRequest $request)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Flux::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
