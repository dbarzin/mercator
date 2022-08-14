<?php

namespace App\Http\Controllers\API;

use App\MacroProcessus;

use App\Http\Requests\StoreMacroProcessusRequest;
use App\Http\Requests\UpdateMacroProcessusRequest;
use App\Http\Requests\MassDestroyMacroProcessusRequest;
use App\Http\Resources\Admin\MacroProcessusResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class MacroProcessusController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('macroprocessus_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $macroprocessuss = MacroProcessus::all();

    return response()->json($macroprocessuss);
    }

    public function store(StoreMacroProcessusRequest $request)
    {
        abort_if(Gate::denies('macroprocessus_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroprocessus = MacroProcessus::create($request->all());
        // syncs
        // $macroprocessus->roles()->sync($request->input('roles', []));

        return response()->json($macroprocessus, 201);
    }

    public function show(MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macroprocessus_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MacroProcessusResource($macroprocessus);
    }

    public function update(UpdateMacroProcessusRequest $request, MacroProcessus $macroProcessus)
    {     
        abort_if(Gate::denies('macroprocessus_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroProcessus->update($request->all());
        // syncs
        // $macroProcessus->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macroprocessus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroProcessus->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyMacroProcessusRequest $request)
    {
        MacroProcessus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

