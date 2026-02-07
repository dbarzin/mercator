<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWanRequest;
use App\Http\Requests\MassStoreWanRequest;
use App\Http\Requests\MassUpdateWanRequest;
use App\Http\Requests\StoreWanRequest;
use App\Http\Requests\UpdateWanRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Wan;
use Symfony\Component\HttpFoundation\Response;

class WanController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('wan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Wan::query();

        // Champs autorisés pour le filtrage afin d’éviter toute injection
        $allowedFields = array_merge(
            Wan::$searchable ?? [],   // si le modèle définit $searchable
            ['id']                    // champs supplémentaires autorisés
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Format "field" ou "field__operator"
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue;
            }

            switch ($operator) {
                case 'exact':
                    $query->where($field, $value);
                    break;

                case 'contains':
                    $query->where($field, 'LIKE', "%{$value}%");
                    break;

                case 'startswith':
                    $query->where($field, 'LIKE', "{$value}%");
                    break;

                case 'endswith':
                    $query->where($field, 'LIKE', "%{$value}");
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

        return response()->json($query->get());
    }

    public function store(StoreWanRequest $request)
    {
        abort_if(Gate::denies('wan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan = Wan::create($request->all());

        return response()->json($wan, 201);
    }

    public function show(Wan $wan)
    {
        abort_if(Gate::denies('wan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($wan);
    }

    public function update(UpdateWanRequest $request, Wan $wan)
    {
        abort_if(Gate::denies('wan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->update($request->all());

        return response()->json();
    }

    public function destroy(Wan $wan)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWanRequest $request)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Wan::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreWanRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds = [];
        $fillable   = (new Wan())->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Wan $wan */
            $wan = Wan::create($attributes);
            $createdIds[] = $wan->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateWanRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data     = $request->validated();
        $fillable = (new Wan())->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Wan $wan */
            $wan = Wan::findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (!empty($attributes)) {
                $wan->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
