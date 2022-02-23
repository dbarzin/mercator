<?php

namespace App\Http\Controllers\API;

use App\ZoneAdmin;

use App\Http\Requests\StoreZoneAdminRequest;
use App\Http\Requests\UpdateZoneAdminRequest;
use App\Http\Requests\MassDestroyZoneAdminRequest;
use App\Http\Resources\Admin\ZoneAdminResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class ZoneAdminController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('zoneadmin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $zoneadmins = ZoneAdmin::all();

    return response()->json($zoneadmins);
    }

    public function store(StoreZoneAdminRequest $request)
    {
        abort_if(Gate::denies('zoneadmin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneadmin = ZoneAdmin::create($request->all());
        // syncs
        // $zoneadmin->roles()->sync($request->input('roles', []));

        return response()->json($zoneadmin, 201);
    }

    public function show(ZoneAdmin $zoneadmin)
    {
        abort_if(Gate::denies('zoneadmin_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ZoneAdminResource($zoneadmin);
    }

    public function update(UpdateZoneAdminRequest $request, ZoneAdmin $zoneadmin)
    {     
        abort_if(Gate::denies('zoneadmin_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneadmin->update($request->all());
        // syncs
        // $zoneadmin->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(ZoneAdmin $zoneadmin)
    {
        abort_if(Gate::denies('zoneadmin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneadmin->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyZoneAdminRequest $request)
    {
        ZoneAdmin::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

