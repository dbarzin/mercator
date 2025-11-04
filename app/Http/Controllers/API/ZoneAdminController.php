<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyZoneAdminRequest;
use App\Http\Requests\StoreZoneAdminRequest;
use App\Http\Requests\UpdateZoneAdminRequest;
use App\Models\ZoneAdmin;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

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

        return response()->json($zoneadmin, 201);
    }

    public function show(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($zoneAdmin);
    }

    public function update(UpdateZoneAdminRequest $request, ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneAdmin->update($request->all());

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
