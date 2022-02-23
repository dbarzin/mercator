<?php

namespace App\Http\Controllers\API;

use App\Permissions;

use App\Http\Requests\StorePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use App\Http\Requests\MassDestroyPermissionsRequest;
use App\Http\Resources\Admin\PermissionsResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class PermissionsController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('permissions_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $permissionss = Permissions::all();

    return response()->json($permissionss);
    }

    public function store(StorePermissionsRequest $request)
    {
        abort_if(Gate::denies('permissions_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permissions::create($request->all());
        // syncs
        // $permissions->roles()->sync($request->input('roles', []));

        return response()->json($permissions, 201);
    }

    public function show(Permissions $permissions)
    {
        abort_if(Gate::denies('permissions_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PermissionsResource($permissions);
    }

    public function update(UpdatePermissionsRequest $request, Permissions $permissions)
    {     
        abort_if(Gate::denies('permissions_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions->update($request->all());
        // syncs
        // $permissions->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Permissions $permissions)
    {
        abort_if(Gate::denies('permissions_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPermissionsRequest $request)
    {
        Permissions::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

