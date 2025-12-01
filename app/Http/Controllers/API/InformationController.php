<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInformationRequest;
use App\Http\Requests\MassStoreInformationRequest;
use App\Http\Requests\MassUpdateInformationRequest;
use App\Http\Requests\StoreInformationRequest;
use App\Http\Requests\UpdateInformationRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Information;
use Symfony\Component\HttpFoundation\Response;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Information::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Information::$searchable ?? [],
            ['id'] // Ajouter ici d'autres champs si nécessaire
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

        $informations = $query->get();

        return response()->json($informations);
    }

    public function store(StoreInformationRequest $request)
    {
        abort_if(Gate::denies('information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Information $information */
        $information = Information::query()->create($request->all());

        $information->processes()->sync($request->input('processes', []));

        return response()->json($information, 201);
    }

    public function show(Information $information)
    {
        abort_if(Gate::denies('information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($information);
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

        Information::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreInformationRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('information_create')
        $data = $request->validated();

        $createdIds      = [];
        $informationModel = new Information();
        $fillable         = $informationModel->getFillable();

        foreach ($data['items'] as $item) {
            $processes = $item['processes'] ?? null;

            // Colonnes du modèle uniquement (sans les relations)
            $attributes = collect($item)
                ->except(['processes'])
                ->only($fillable)
                ->toArray();

            /** @var Information $information */
            $information = Information::query()->create($attributes);

            if (array_key_exists('processes', $item)) {
                $information->processes()->sync($processes ?? []);
            }

            $createdIds[] = $information->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateInformationRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('information_edit')
        $data            = $request->validated();
        $informationModel = new Information();
        $fillable         = $informationModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id        = $rawItem['id'];
            $processes = $rawItem['processes'] ?? null;

            /** @var Information $information */
            $information = Information::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'processes'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $information->update($attributes);
            }

            if (array_key_exists('processes', $rawItem)) {
                $information->processes()->sync($processes ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
