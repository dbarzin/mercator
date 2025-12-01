<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRelationRequest;
use App\Http\Requests\MassStoreRelationRequest;
use App\Http\Requests\MassUpdateRelationRequest;
use App\Http\Requests\StoreRelationRequest;
use App\Http\Requests\UpdateRelationRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Relation;
use Symfony\Component\HttpFoundation\Response;

class RelationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('relation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Relation::query();

        $allowedFields = array_merge(
            Relation::$searchable ?? [],
            ['id']
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue;
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

        $relations = $query->get();

        return response()->json($relations);
    }

    public function store(StoreRelationRequest $request)
    {
        abort_if(Gate::denies('relation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Relation $relation */
        $relation = Relation::query()->create($request->all());

        return response()->json($relation, Response::HTTP_CREATED);
    }

    public function show(Relation $relation)
    {
        abort_if(Gate::denies('relation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($relation);
    }

    public function update(UpdateRelationRequest $request, Relation $relation)
    {
        abort_if(Gate::denies('relation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relation->update($request->all());

        return response()->json();
    }

    public function destroy(Relation $relation)
    {
        abort_if(Gate::denies('relation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relation->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyRelationRequest $request)
    {
        abort_if(Gate::denies('relation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Relation::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreRelationRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `relation_create`
        $data = $request->validated();

        $createdIds    = [];
        $relationModel = new Relation();
        $fillable      = $relationModel->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Relation $relation */
            $relation = Relation::query()->create($attributes);

            $createdIds[] = $relation->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateRelationRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `relation_edit`
        $data          = $request->validated();
        $relationModel = new Relation();
        $fillable      = $relationModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Relation $relation */
            $relation = Relation::query()->findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $relation->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
