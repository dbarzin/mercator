<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProcessRequest;
use App\Http\Requests\MassStoreProcessRequest;
use App\Http\Requests\MassUpdateProcessRequest;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Operation;
use Mercator\Core\Models\Process;
use Symfony\Component\HttpFoundation\Response;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Process::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Process::$searchable ?? [],
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

        $processes = $query->get();

        return response()->json($processes);
    }

    public function store(StoreProcessRequest $request)
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Process $process */
        $process = Process::query()->create($request->all());

        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->information()->sync($request->input('informations', []));
        $process->applications()->sync($request->input('applications', []));

        Operation::whereIn('id', $request->input('operations', []))
            ->update(['process_id' => $process->id]);

        return response()->json($process, Response::HTTP_CREATED);
    }

    public function show(Process $process)
    {
        abort_if(Gate::denies('process_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($process);
    }

    public function update(UpdateProcessRequest $request, Process $process)
    {
        abort_if(Gate::denies('process_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->update($request->all());

        if ($request->has('activities')) {
            $process->activities()->sync($request->input('activities', []));
        }

        if ($request->has('entities')) {
            $process->entities()->sync($request->input('entities', []));
        }

        if ($request->has('informations')) {
            $process->information()->sync($request->input('informations', []));
        }

        if ($request->has('applications')) {
            $process->applications()->sync($request->input('applications', []));
        }

        if ($request->has('operations')) {
            Operation::whereIn('id', $request->input('operations', []))
                ->update(['process_id' => $process->id]);
        }

        return response()->json();
    }

    public function destroy(Process $process)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyProcessRequest $request)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Process::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreProcessRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `process_create`
        $data = $request->validated();

        $createdIds    = [];
        $processModel  = new Process();
        $fillable      = $processModel->getFillable();

        foreach ($data['items'] as $item) {
            $activities   = $item['activities'] ?? null;
            $entities     = $item['entities'] ?? null;
            $informations = $item['informations'] ?? null;
            $applications = $item['applications'] ?? null;
            $operations   = $item['operations'] ?? null;

            // Colonnes du modèle uniquement (sans les relations)
            $attributes = collect($item)
                ->except(['activities', 'entities', 'informations', 'applications', 'operations'])
                ->only($fillable)
                ->toArray();

            /** @var Process $process */
            $process = Process::query()->create($attributes);

            if (array_key_exists('activities', $item)) {
                $process->activities()->sync($activities ?? []);
            }

            if (array_key_exists('entities', $item)) {
                $process->entities()->sync($entities ?? []);
            }

            if (array_key_exists('informations', $item)) {
                $process->information()->sync($informations ?? []);
            }

            if (array_key_exists('applications', $item)) {
                $process->applications()->sync($applications ?? []);
            }

            if (array_key_exists('operations', $item) && ! empty($operations)) {
                Operation::whereIn('id', $operations)
                    ->update(['process_id' => $process->id]);
            }

            $createdIds[] = $process->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateProcessRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `process_edit`
        $data        = $request->validated();
        $processModel = new Process();
        $fillable     = $processModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id           = $rawItem['id'];
            $activities   = $rawItem['activities'] ?? null;
            $entities     = $rawItem['entities'] ?? null;
            $informations = $rawItem['informations'] ?? null;
            $applications = $rawItem['applications'] ?? null;
            $operations   = $rawItem['operations'] ?? null;

            /** @var Process $process */
            $process = Process::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'activities', 'entities', 'informations', 'applications', 'operations'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $process->update($attributes);
            }

            if (array_key_exists('activities', $rawItem)) {
                $process->activities()->sync($activities ?? []);
            }

            if (array_key_exists('entities', $rawItem)) {
                $process->entities()->sync($entities ?? []);
            }

            if (array_key_exists('informations', $rawItem)) {
                $process->information()->sync($informations ?? []);
            }

            if (array_key_exists('applications', $rawItem)) {
                $process->applications()->sync($applications ?? []);
            }

            if (array_key_exists('operations', $rawItem) && ! empty($operations)) {
                Operation::whereIn('id', $operations)
                    ->update(['process_id' => $process->id]);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
