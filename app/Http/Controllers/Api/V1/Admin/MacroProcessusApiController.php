<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMacroProcessusRequest;
use App\Http\Requests\UpdateMacroProcessusRequest;
use App\Http\Resources\Admin\MacroProcessusResource;
use App\MacroProcessus;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MacroProcessusApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('macro_processus_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MacroProcessusResource(MacroProcessus::with(['processes'])->get());
    }

    public function store(StoreMacroProcessusRequest $request)
    {
        $macroProcessus = MacroProcessus::create($request->all());
        $macroProcessus->processes()->sync($request->input('processes', []));

        return (new MacroProcessusResource($macroProcessus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macro_processus_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MacroProcessusResource($macroProcessus->load(['processes']));
    }

    public function update(UpdateMacroProcessusRequest $request, MacroProcessus $macroProcessus)
    {
        $macroProcessus->update($request->all());
        $macroProcessus->processes()->sync($request->input('processes', []));

        return (new MacroProcessusResource($macroProcessus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macro_processus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroProcessus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
