<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Requests\MassStorePermissionRequest;
use App\Http\Requests\MassUpdatePermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Permission::query();

        // Explicitly allowed fields for filtering
        $allowedFields = array_merge(
            Permission::$searchable ?? [],
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

        $permissions = $query->get();

        return response()->json($permissions);
    }

    public function store(StorePermissionRequest $request)
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Permission $permission */
        $permission = Permission::query()->create($request->all());

        return response()->json($permission, 201);
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->update($request->all());

        return response()->json();
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPermissionRequest $request)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Permission::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStorePermissionRequest $request)
    {
        // authorize() in the FormRequest already checks `permission_create`
        $data = $request->validated();

        $createdIds      = [];
        $permissionModel = new Permission();
        $fillable        = $permissionModel->getFillable();

        foreach ($data['items'] as $item) {
            // Model columns only
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Permission $permission */
            $permission = Permission::query()->create($attributes);

            $createdIds[] = $permission->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdatePermissionRequest $request)
    {
        // authorize() in the FormRequest already checks `permission_edit`
        $data           = $request->validated();
        $permissionModel = new Permission();
        $fillable        = $permissionModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Permission $permission */
            $permission = Permission::query()->findOrFail($id);

            // Model columns only (no id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $permission->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
