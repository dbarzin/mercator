<?php

namespace App\Http\Controllers\API;

use App\PhysicalServer;

use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use App\Http\Requests\MassDestroyPhysicalServerRequest;
use App\Http\Resources\Admin\PhysicalServerResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class PhysicalServerController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('physical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $physicalservers = PhysicalServer::all();

    return response()->json($physicalservers);
    }

    public function store(StorePhysicalServerRequest $request)
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalserver = PhysicalServer::create($request->all());
        // syncs
        // $physicalserver->roles()->sync($request->input('roles', []));

        return response()->json($physicalserver, 201);
    }

    public function show(PhysicalServer $physicalserver)
    {
        abort_if(Gate::denies('physical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalServerResource($physicalserver);
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalserver)
    {     
        abort_if(Gate::denies('physical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalserver->update($request->all());
        // syncs
        // $physicalserver->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(PhysicalServer $physicalserver)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalserver->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalServerRequest $request)
    {
        PhysicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

