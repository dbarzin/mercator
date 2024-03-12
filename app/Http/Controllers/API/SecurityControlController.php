<?php
namespace App\Http\Controllers\API;

use App\SecurityControl;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityControlRequest;
use App\Http\Requests\StoreSecurityControlRequest;
use App\Http\Requests\UpdateSecurityControlRequest;
use App\Http\Resources\Admin\SecurityControlResource;
use Gate;
use Illuminate\Http\Response;

class SecurityControlController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('security_controls_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $securityControls = SecurityControl::all();

        return response()->json($securityControls);
    }

    public function store(StoreSecurityControlRequest $request)
    {
        abort_if(Gate::denies('security_controls_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl = SecurityControl::create($request->all());

        return response()->json($securityControl, 201);
    }

    public function show(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_controls_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SecurityControlResource($securityControl);
    }

    public function update(UpdateSecurityControlRequest $request, SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_controls_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl->update($request->all());

        return response()->json();
    }

    public function destroy(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_controls_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySecurityControlRequest $request)
    {
        abort_if(Gate::denies('security_controls_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        SecurityControl::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
