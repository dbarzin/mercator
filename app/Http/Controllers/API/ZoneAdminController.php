<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyZoneAdminRequest;
use App\Http\Requests\StoreZoneAdminRequest;
use App\Http\Requests\UpdateZoneAdminRequest;
use App\Http\Resources\Admin\ZoneAdminResource;
use App\ZoneAdmin;
use Gate;
use Illuminate\Http\Response;

class ZoneAdminController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('zone_admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneadmins = ZoneAdmin::all();

        return response()->json($zoneadmins);
    }

    public function store(StoreZoneAdminRequest $request)
    {
        abort_if(Gate::denies('zone_admin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneadmin = ZoneAdmin::create($request->all());
        // syncs
        // $zoneadmin->roles()->sync($request->input('roles', []));

        return response()->json($zoneadmin, 201);
    }

    public function show(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ZoneAdminResource($zoneAdmin);
    }

    public function update(UpdateZoneAdminRequest $request, ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneAdmin->update($request->all());
        // syncs
        // $zoneAdmin->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneAdmin->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyZoneAdminRequest $request)
    {
        abort_if(Gate::denies('zone_admin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ZoneAdmin::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
