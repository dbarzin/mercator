<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubnetworkRequest;
use App\Http\Requests\StoreSubnetworkRequest;
use App\Http\Requests\UpdateSubnetworkRequest;
use App\Http\Resources\Admin\SubnetworkResource;
use App\Subnetwork;
use Gate;
use Illuminate\Http\Response;

class SubnetworkController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('subnetwork_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::all();

        return response()->json($subnetworks);
    }

    public function store(StoreSubnetworkRequest $request)
    {
        abort_if(Gate::denies('subnetwork_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork = Subnetwork::create($request->all());
        // syncs
        // $subnetwork->roles()->sync($request->input('roles', []));

        return response()->json($subnetwork, 201);
    }

    public function show(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubnetworkResource($subnetwork);
    }

    public function update(UpdateSubnetworkRequest $request, Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->update($request->all());
        // syncs
        // $subnetwork->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySubnetworkRequest $request)
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Subnetwork::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
