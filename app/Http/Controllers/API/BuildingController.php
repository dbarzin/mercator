<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBuildingRequest;
use App\Http\Requests\MassStoreBuildingRequest;
use App\Http\Requests\MassUpdateBuildingRequest;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Building;
use Symfony\Component\HttpFoundation\Response;

class BuildingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('building_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Building::query();

        // Champs autorisés pour le filtrage afin d’éviter toute injection
        $allowedFields = array_merge(
            Building::$searchable ?? [],
            ['id']
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

        $buildings = $query->get();

        return response()->json($buildings);
    }

    public function store(StoreBuildingRequest $request)
    {
        abort_if(Gate::denies('building_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $building = Building::create($request->all());

        return response()->json($building, 201);
    }

    public function show(Building $building)
    {
        abort_if(Gate::denies('building_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($building);
    }

    public function update(UpdateBuildingRequest $request, Building $building)
    {
        abort_if(Gate::denies('building_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $building->update($request->all());

        return response()->json();
    }

    public function destroy(Building $building)
    {
        abort_if(Gate::denies('building_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $building->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyBuildingRequest $request)
    {
        abort_if(Gate::denies('building_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Building::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreBuildingRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds = [];
        $fillable   = (new Building())->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Building $building */
            $building = Building::create($attributes);
            $createdIds[] = $building->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateBuildingRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data     = $request->validated();
        $fillable = (new Building())->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Building $building */
            $building = Building::findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $building->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
