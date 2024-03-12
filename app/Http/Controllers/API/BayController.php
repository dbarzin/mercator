<?php

namespace App\Http\Controllers\API;

use App\Bay;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBayRequest;
use App\Http\Requests\StoreBayRequest;
use App\Http\Requests\UpdateBayRequest;
use App\Http\Resources\Admin\BayResource;
use Gate;
use Illuminate\Http\Response;

class BayController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('bay_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bays = Bay::all();

        return response()->json($bays);
    }

    public function store(StoreBayRequest $request)
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay = Bay::create($request->all());

        return response()->json($bay, 201);
    }

    public function show(Bay $bay)
    {
        abort_if(Gate::denies('bay_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BayResource($bay);
    }

    public function update(UpdateBayRequest $request, Bay $bay)
    {
        abort_if(Gate::denies('bay_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->update($request->all());

        return response()->json();
    }

    public function destroy(Bay $bay)
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyBayRequest $request)
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Bay::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
