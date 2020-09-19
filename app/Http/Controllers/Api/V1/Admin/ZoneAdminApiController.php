<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreZoneAdminRequest;
use App\Http\Requests\UpdateZoneAdminRequest;
use App\Http\Resources\Admin\ZoneAdminResource;
use App\ZoneAdmin;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZoneAdminApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('zone_admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ZoneAdminResource(ZoneAdmin::all());
    }

    public function store(StoreZoneAdminRequest $request)
    {
        $zoneAdmin = ZoneAdmin::create($request->all());

        return (new ZoneAdminResource($zoneAdmin))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ZoneAdminResource($zoneAdmin);
    }

    public function update(UpdateZoneAdminRequest $request, ZoneAdmin $zoneAdmin)
    {
        $zoneAdmin->update($request->all());

        return (new ZoneAdminResource($zoneAdmin))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneAdmin->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
