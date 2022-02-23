<?php

namespace App\Http\Controllers\API;

use App\Roles;

use App\Http\Requests\StoreRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use App\Http\Requests\MassDestroyRolesRequest;
use App\Http\Resources\Admin\RolesResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('roles_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $roless = Roles::all();

    return response()->json($roless);
    }

    public function store(StoreRolesRequest $request)
    {
        abort_if(Gate::denies('roles_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Roles::create($request->all());
        // syncs
        // $roles->roles()->sync($request->input('roles', []));

        return response()->json($roles, 201);
    }

    public function show(Roles $roles)
    {
        abort_if(Gate::denies('roles_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RolesResource($roles);
    }

    public function update(UpdateRolesRequest $request, Roles $roles)
    {     
        abort_if(Gate::denies('roles_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles->update($request->all());
        // syncs
        // $roles->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Roles $roles)
    {
        abort_if(Gate::denies('roles_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyRolesRequest $request)
    {
        Roles::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

