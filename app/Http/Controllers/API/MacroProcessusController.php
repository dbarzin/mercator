<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMacroProcessusRequest;
use App\Http\Requests\MassStoreMacroProcessusRequest;
use App\Http\Requests\MassUpdateMacroProcessusRequest;
use App\Http\Requests\StoreMacroProcessusRequest;
use App\Http\Requests\UpdateMacroProcessusRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\MacroProcessus;
use Mercator\Core\Models\Process;
use Symfony\Component\HttpFoundation\Response;

class MacroProcessusController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('macro_processus_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = MacroProcessus::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            MacroProcessus::$searchable ?? [],
            ['id'] // Ajouter ici d'autres champs explicitement autorisés si nécessaire
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // Ignore les champs non autorisés
            }

            switch ($operator) {
                case 'exact':
                    $query->where($field, $value);
                    break;

                case 'contains':
                    $query->where($field, 'LIKE', '%' . $value . '%');
                    break;

                case 'startswith':
                    $query->where($field, 'LIKE', $value . '%');
                    break;

                case 'endswith':
                    $query->where($field, 'LIKE', '%' . $value);
                    break;

                case 'lt':
                    $query->where($field, '<', $value);
                    break;

                case 'lte':
                    $query->where($field, '<=', $value);
                    break;

                case 'gt':
                    $query->where($field, '>', $value);
                    break;

                case 'gte':
                    $query->where($field, '>=', $value);
                    break;

                default:
                    $query->where($field, $value);
            }
        }

        $macroProcessusList = $query->get();

        return response()->json($macroProcessusList);
    }

    public function store(StoreMacroProcessusRequest $request)
    {
        abort_if(Gate::denies('macro_processus_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var MacroProcessus $macroProcessus */
        $macroProcessus = MacroProcessus::query()->create($request->all());

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

        MacroProcessus::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreMacroProcessusRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('macro_processus_create')
        $data = $request->validated();

        $createdIds         = [];
        $macroProcessusModel = new MacroProcessus();
        $fillable            = $macroProcessusModel->getFillable();

        foreach ($data['items'] as $item) {
            $processes = $item['processes'] ?? null;

            // Colonnes du modèle uniquement (sans les relations)
            $attributes = collect($item)
                ->except(['processes'])
                ->only($fillable)
                ->toArray();

            /** @var MacroProcessus $macroProcessus */
            $macroProcessus = MacroProcessus::query()->create($attributes);

            if (array_key_exists('processes', $item) && ! empty($processes)) {
                Process::whereIn('id', $processes)
                    ->update(['macroprocess_id' => $macroProcessus->id]);
            }

            $createdIds[] = $macroProcessus->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateMacroProcessusRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('macro_processus_edit')
        $data               = $request->validated();
        $macroProcessusModel = new MacroProcessus();
        $fillable            = $macroProcessusModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id        = $rawItem['id'];
            $processes = $rawItem['processes'] ?? null;

            /** @var MacroProcessus $macroProcessus */
            $macroProcessus = MacroProcessus::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'processes'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $macroProcessus->update($attributes);
            }

            if (array_key_exists('processes', $rawItem) && ! empty($processes)) {
                Process::whereIn('id', $processes)
                    ->update(['macroprocess_id' => $macroProcessus->id]);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
