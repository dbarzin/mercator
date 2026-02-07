<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkstationRequest;
use App\Http\Requests\MassStoreWorkstationRequest;
use App\Http\Requests\MassUpdateWorkstationRequest;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Workstation;
use Symfony\Component\HttpFoundation\Response;

class WorkstationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('workstation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Workstation::query();

        // Champs autorisés pour le filtrage afin d’éviter toute injection
        $allowedFields = array_merge(
            Workstation::$searchable ?? [],
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

        $workstations = $query->get();

        return response()->json($workstations);
    }

    public function store(StoreWorkstationRequest $request)
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation = Workstation::create($request->all());

        return response()->json($workstation, 201);
    }

    public function show(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($workstation);
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->update($request->all());

        return response()->json();
    }

    public function destroy(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWorkstationRequest $request)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Workstation::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreWorkstationRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds = [];
        $fillable   = (new Workstation())->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Workstation $workstation */
            $workstation = Workstation::create($attributes);
            $createdIds[] = $workstation->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateWorkstationRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data     = $request->validated();
        $fillable = (new Workstation())->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Workstation $workstation */
            $workstation = Workstation::findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $workstation->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
