<?php

namespace App\Http\Controllers\API;

use App\Permission;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Resources\Admin\PermissionResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $permissions = Permission::all();

    return response()->json($permissions);
    }

    public function store(StorePermissionRequest $request)
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission = Permission::create($request->all());
        // syncs
        // $permissions->roles()->sync($request->input('roles', []));

        return response()->json($permission, 201);
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PermissionResource($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {     
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->update($request->all());
        // syncs
        // $permissions->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return response()->json();
    }

}

