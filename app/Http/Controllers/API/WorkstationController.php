<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkstationRequest;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;
use App\Http\Resources\Admin\WorkstationResource;
use App\Workstation;
use Gate;
use Illuminate\Http\Response;

class WorkstationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('workstation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstations = Workstation::all();

        return response()->json($workstations);
    }

    public function store(StoreWorkstationRequest $request)
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation = Workstation::create($request->all());

        return response()->json($workstation, 201);
    }

    public function show(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkstationResource($workstation);
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->update($request->all());

        return response()->json();
    }

    public function destroy(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWorkstationRequest $request)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Workstation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
