<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
use Gate;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('roles_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();

        return response()->json($roles);
    }

    public function store(StoreRoleRequest $request)
    {
        abort_if(Gate::denies('roles_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::create($request->all());

        return response()->json($roles, 201);
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('roles_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RoleResource($role);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_if(Gate::denies('roles_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->update($request->all());

        return response()->json();
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('roles_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return response()->json();
    }
}
