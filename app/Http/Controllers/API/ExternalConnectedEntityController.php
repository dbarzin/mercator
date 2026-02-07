<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExternalConnectedEntityRequest;
use App\Http\Requests\MassStoreExternalConnectedEntityRequest;
use App\Http\Requests\MassUpdateExternalConnectedEntityRequest;
use App\Http\Requests\StoreExternalConnectedEntityRequest;
use App\Http\Requests\UpdateExternalConnectedEntityRequest;
use Mercator\Core\Models\ExternalConnectedEntity;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ExternalConnectedEntityController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('external_connected_entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = ExternalConnectedEntity::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            ExternalConnectedEntity::$searchable ?? [],
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

        $entities = $query->get();

        return response()->json($entities);
    }

    public function store(StoreExternalConnectedEntityRequest $request)
    {
        abort_if(Gate::denies('external_connected_entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var ExternalConnectedEntity $externalConnectedEntity */
        $externalConnectedEntity = ExternalConnectedEntity::create($request->all());
        // $externalConnectedEntity->roles()->sync($request->input('roles', []));

        return response()->json($externalConnectedEntity, Response::HTTP_CREATED);
    }

    public function show(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($externalConnectedEntity);
    }

    public function update(UpdateExternalConnectedEntityRequest $request, ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->update($request->all());
        // $externalConnectedEntity->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyExternalConnectedEntityRequest $request)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ExternalConnectedEntity::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreExternalConnectedEntityRequest $request)
    {
        // L’authorize() du FormRequest gère déjà external_connected_entity_create
        $data       = $request->validated();
        $createdIds = [];

        $model    = new ExternalConnectedEntity();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            // $roles = $item['roles'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except([
                    // 'roles',
                ])
                ->only($fillable)
                ->toArray();

            /** @var ExternalConnectedEntity $externalConnectedEntity */
            $externalConnectedEntity = ExternalConnectedEntity::query()->create($attributes);

            // if (array_key_exists('roles', $item)) {
            //     $externalConnectedEntity->roles()->sync($roles ?? []);
            // }

            $createdIds[] = $externalConnectedEntity->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateExternalConnectedEntityRequest $request)
    {
        // L’authorize() du FormRequest gère déjà external_connected_entity_edit
        $data     = $request->validated();
        $model    = new ExternalConnectedEntity();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];
            // $roles = $rawItem['roles'] ?? null;

            /** @var ExternalConnectedEntity $externalConnectedEntity */
            $externalConnectedEntity = ExternalConnectedEntity::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except([
                    'id',
                    // 'roles',
                ])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $externalConnectedEntity->update($attributes);
            }

            // if (array_key_exists('roles', $rawItem)) {
            //     $externalConnectedEntity->roles()->sync($roles ?? []);
            // }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
