<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalRouterRequest;
use App\Http\Requests\MassStorePhysicalRouterRequest;
use App\Http\Requests\MassUpdatePhysicalRouterRequest;
use App\Http\Requests\StorePhysicalRouterRequest;
use App\Http\Requests\UpdatePhysicalRouterRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\PhysicalRouter;
use Symfony\Component\HttpFoundation\Response;

class PhysicalRouterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('physical_router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PhysicalRouter::query();

        // Explicitly allowed fields for filtering
        $allowedFields = array_merge(
            PhysicalRouter::$searchable ?? [],
            ['id'] // Add more fields here if needed
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field or field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // Ignore non-authorized fields
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

        $physicalRouters = $query->get();

        return response()->json($physicalRouters);
    }

    public function store(StorePhysicalRouterRequest $request)
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var PhysicalRouter $physicalRouter */
        $physicalRouter = PhysicalRouter::query()->create($request->all());

        $physicalRouter->vlans()->sync($request->input('vlans', []));

        return response()->json($physicalRouter, 201);
    }

    public function show(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($physicalRouter);
    }

    public function update(UpdatePhysicalRouterRequest $request, PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->update($request->all());

        if ($request->has('vlans')) {
            $physicalRouter->vlans()->sync($request->input('vlans', []));
        }

        return response()->json();
    }

    public function destroy(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalRouterRequest $request)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalRouter::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStorePhysicalRouterRequest $request)
    {
        // authorize() in the FormRequest already checks `physical_router_create`
        $data = $request->validated();

        $createdIds          = [];
        $physicalRouterModel = new PhysicalRouter();
        $fillable            = $physicalRouterModel->getFillable();

        foreach ($data['items'] as $item) {
            $vlans = $item['vlans'] ?? null;

            // Model columns only (no relations)
            $attributes = collect($item)
                ->except(['vlans'])
                ->only($fillable)
                ->toArray();

            /** @var PhysicalRouter $physicalRouter */
            $physicalRouter = PhysicalRouter::query()->create($attributes);

            if (array_key_exists('vlans', $item)) {
                $physicalRouter->vlans()->sync($vlans ?? []);
            }

            $createdIds[] = $physicalRouter->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdatePhysicalRouterRequest $request)
    {
        // authorize() in the FormRequest already checks `physical_router_edit`
        $data               = $request->validated();
        $physicalRouterModel = new PhysicalRouter();
        $fillable            = $physicalRouterModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id    = $rawItem['id'];
            $vlans = $rawItem['vlans'] ?? null;

            /** @var PhysicalRouter $physicalRouter */
            $physicalRouter = PhysicalRouter::query()->findOrFail($id);

            // Model columns only (no id or relations)
            $attributes = collect($rawItem)
                ->except(['id', 'vlans'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $physicalRouter->update($attributes);
            }

            if (array_key_exists('vlans', $rawItem)) {
                $physicalRouter->vlans()->sync($vlans ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
