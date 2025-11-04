<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMacroProcessusRequest;
use App\Http\Requests\StoreMacroProcessusRequest;
use App\Http\Requests\UpdateMacroProcessusRequest;
use App\Models\MacroProcessus;
use App\Models\Process;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class MacroProcessusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('macro_processus_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroprocessuss = MacroProcessus::all();

        return response()->json($macroprocessuss);
    }

    public function store(StoreMacroProcessusRequest $request)
    {
        abort_if(Gate::denies('macro_processus_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroProcessus = MacroProcessus::create($request->all());
        Process::whereIn('id', $request->input('processes', []))
            ->update(['macroprocess_id' => $macroProcessus->id]);

        return response()->json($macroProcessus, 201);
    }

    public function show(MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macro_processus_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($macroProcessus);
    }

    public function update(UpdateMacroProcessusRequest $request, MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macro_processus_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroProcessus->update($request->all());
        if ($request->has('processes')) {
            Process::whereIn('id', $request->input('processes', []))
                ->update(['macroprocess_id' => $macroProcessus->id]);
        }

        return response()->json();
    }

    public function destroy(MacroProcessus $macroProcessus)
    {
        abort_if(Gate::denies('macro_processus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $macroProcessus->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyMacroProcessusRequest $request)
    {
        abort_if(Gate::denies('macro_processus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        MacroProcessus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
