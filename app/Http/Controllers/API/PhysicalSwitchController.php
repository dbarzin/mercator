<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSwitchRequest;
use App\Http\Requests\MassStorePhysicalSwitchRequest;
use App\Http\Requests\MassUpdatePhysicalSwitchRequest;
use App\Http\Requests\StorePhysicalSwitchRequest;
use App\Http\Requests\UpdatePhysicalSwitchRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\PhysicalSwitch;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSwitchController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('physical_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PhysicalSwitch::query();

        $allowedFields = array_merge(
            PhysicalSwitch::$searchable ?? [],
            ['id']
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

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

        $physicalSwitches = $query->get();

        return response()->json($physicalSwitches);
    }

    public function store(StorePhysicalSwitchRequest $request)
    {
        abort_if(Gate::denies('physical_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var PhysicalSwitch $physicalSwitch */
        $physicalSwitch = PhysicalSwitch::query()->create($request->all());

        return response()->json($physicalSwitch, 201);
    }

    public function show(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($physicalSwitch);
    }

    public function update(UpdatePhysicalSwitchRequest $request, PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->update($request->all());

        return response()->json();
    }

    public function destroy(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalSwitchRequest $request)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalSwitch::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStorePhysicalSwitchRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_switch_create`
        $data = $request->validated();

        $createdIds            = [];
        $physicalSwitchModel   = new PhysicalSwitch();
        $fillable              = $physicalSwitchModel->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var PhysicalSwitch $physicalSwitch */
            $physicalSwitch = PhysicalSwitch::query()->create($attributes);

            $createdIds[] = $physicalSwitch->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdatePhysicalSwitchRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_switch_edit`
        $data               = $request->validated();
        $physicalSwitchModel = new PhysicalSwitch();
        $fillable            = $physicalSwitchModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var PhysicalSwitch $physicalSwitch */
            $physicalSwitch = PhysicalSwitch::query()->findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $physicalSwitch->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
