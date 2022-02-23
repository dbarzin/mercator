<?php

namespace App\Http\Controllers\API;

use App\RelationMacroProcessus;

use App\Http\Requests\StoreRelationMacroProcessusRequest;
use App\Http\Requests\UpdateRelationMacroProcessusRequest;
use App\Http\Requests\MassDestroyRelationMacroProcessusRequest;
use App\Http\Resources\Admin\RelationMacroProcessusResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class RelationMacroProcessusController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('relationmacroprocessus_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $relationmacroprocessuss = RelationMacroProcessus::all();

    return response()->json($relationmacroprocessuss);
    }

    public function store(StoreRelationMacroProcessusRequest $request)
    {
        abort_if(Gate::denies('relationmacroprocessus_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relationmacroprocessus = RelationMacroProcessus::create($request->all());
        // syncs
        // $relationmacroprocessus->roles()->sync($request->input('roles', []));

        return response()->json($relationmacroprocessus, 201);
    }

    public function show(RelationMacroProcessus $relationmacroprocessus)
    {
        abort_if(Gate::denies('relationmacroprocessus_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RelationMacroProcessusResource($relationmacroprocessus);
    }

    public function update(UpdateRelationMacroProcessusRequest $request, RelationMacroProcessus $relationmacroprocessus)
    {     
        abort_if(Gate::denies('relationmacroprocessus_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relationmacroprocessus->update($request->all());
        // syncs
        // $relationmacroprocessus->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(RelationMacroProcessus $relationmacroprocessus)
    {
        abort_if(Gate::denies('relationmacroprocessus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relationmacroprocessus->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyRelationMacroProcessusRequest $request)
    {
        RelationMacroProcessus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

