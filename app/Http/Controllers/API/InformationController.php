<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInformationRequest;
use App\Http\Requests\StoreInformationRequest;
use App\Http\Requests\UpdateInformationRequest;
use App\Http\Resources\Admin\InformationResource;
use App\Information;
use Gate;
use Illuminate\Http\Response;

class InformationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $informations = Information::all();

        return response()->json($informations);
    }

    public function store(StoreInformationRequest $request)
    {
        abort_if(Gate::denies('information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information = Information::create($request->all());
        $information->processes()->sync($request->input('processes', []));

        return response()->json($information, 201);
    }

    public function show(Information $information)
    {
        abort_if(Gate::denies('information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InformationResource($information);
    }

    public function update(UpdateInformationRequest $request, Information $information)
    {
        abort_if(Gate::denies('information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information->update($request->all());
        $information->processes()->sync($request->input('processes', []));

        return response()->json();
    }

    public function destroy(Information $information)
    {
        abort_if(Gate::denies('information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyInformationRequest $request)
    {
        abort_if(Gate::denies('information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Information::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
