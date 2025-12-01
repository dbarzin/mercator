<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBayRequest;
use App\Http\Requests\MassStoreBayRequest;
use App\Http\Requests\MassUpdateBayRequest;
use App\Http\Requests\StoreBayRequest;
use App\Http\Requests\UpdateBayRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Bay;
use Symfony\Component\HttpFoundation\Response;

class BayController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('bay_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Bay::query();

        // Champs autorisés pour le filtrage afin d’éviter toute injection
        $allowedFields = array_merge(
            Bay::$searchable ?? [],
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

        $bays = $query->get();

        return response()->json($bays);
    }

    public function store(StoreBayRequest $request)
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay = Bay::create($request->all());

        return response()->json($bay, 201);
    }

    public function show(Bay $bay)
    {
        abort_if(Gate::denies('bay_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($bay);
    }

    public function update(UpdateBayRequest $request, Bay $bay)
    {
        abort_if(Gate::denies('bay_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->update($request->all());

        return response()->json();
    }

    public function destroy(Bay $bay)
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyBayRequest $request)
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Bay::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreBayRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds = [];
        $fillable   = (new Bay())->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Bay $bay */
            $bay = Bay::create($attributes);
            $createdIds[] = $bay->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateBayRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data     = $request->validated();
        $fillable = (new Bay())->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Bay $bay */
            $bay = Bay::findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $bay->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
