<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDataProcessingRequest;
use App\Http\Requests\MassStoreDataProcessingRequest;
use App\Http\Requests\MassUpdateDataProcessingRequest;
use App\Http\Requests\StoreDataProcessingRequest;
use App\Http\Requests\UpdateDataProcessingRequest;
use Mercator\Core\Models\DataProcessing;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class DataProcessingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('data_processing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = DataProcessing::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            DataProcessing::$searchable ?? [],
            ['id'] // Champs supplémentaires filtrables
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // ignore les champs non autorisés
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
                    // Opérateur inconnu → filtre exact
                    $query->where($field, $value);
            }
        }

        $dataProcessings = $query->get();

        return response()->json($dataProcessings);
    }

    public function store(StoreDataProcessingRequest $request)
    {
        abort_if(Gate::denies('data_processing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var DataProcessing $dataProcessing */
        $dataProcessing = DataProcessing::create($request->all());
        $dataProcessing->processes()->sync($request->input('processes', []));
        $dataProcessing->informations()->sync($request->input('informations', []));
        $dataProcessing->applications()->sync($request->input('applications', []));

        return response()->json($dataProcessing, Response::HTTP_CREATED);
    }

    public function show(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($dataProcessing);
    }

    public function update(UpdateDataProcessingRequest $request, DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing->update($request->all());
        $dataProcessing->processes()->sync($request->input('processes', []));
        $dataProcessing->informations()->sync($request->input('informations', []));
        $dataProcessing->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDataProcessingRequest $request)
    {
        abort_if(Gate::denies('data_processing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DataProcessing::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreDataProcessingRequest $request)
    {
        // L’authorize() du FormRequest gère déjà data_processing_create
        $data       = $request->validated();
        $createdIds = [];

        $model    = new DataProcessing();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            $processes    = $item['processes'] ?? null;
            $informations = $item['informations'] ?? null;
            $applications = $item['applications'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['processes', 'informations', 'applications'])
                ->only($fillable)
                ->toArray();

            /** @var DataProcessing $dataProcessing */
            $dataProcessing = DataProcessing::query()->create($attributes);

            if (array_key_exists('processes', $item)) {
                $dataProcessing->processes()->sync($processes ?? []);
            }
            if (array_key_exists('informations', $item)) {
                $dataProcessing->informations()->sync($informations ?? []);
            }
            if (array_key_exists('applications', $item)) {
                $dataProcessing->applications()->sync($applications ?? []);
            }

            $createdIds[] = $dataProcessing->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateDataProcessingRequest $request)
    {
        // L’authorize() du FormRequest gère déjà data_processing_edit
        $data     = $request->validated();
        $model    = new DataProcessing();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id           = $rawItem['id'];
            $processes    = $rawItem['processes'] ?? null;
            $informations = $rawItem['informations'] ?? null;
            $applications = $rawItem['applications'] ?? null;

            /** @var DataProcessing $dataProcessing */
            $dataProcessing = DataProcessing::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except(['id', 'processes', 'informations', 'applications'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $dataProcessing->update($attributes);
            }

            if (array_key_exists('processes', $rawItem)) {
                $dataProcessing->processes()->sync($processes ?? []);
            }
            if (array_key_exists('informations', $rawItem)) {
                $dataProcessing->informations()->sync($informations ?? []);
            }
            if (array_key_exists('applications', $rawItem)) {
                $dataProcessing->applications()->sync($applications ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
