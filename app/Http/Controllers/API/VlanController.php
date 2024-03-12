<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVlanRequest;
use App\Http\Requests\StoreVlanRequest;
use App\Http\Requests\UpdateVlanRequest;
use App\Http\Resources\Admin\VlanResource;
use App\Vlan;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VlanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('vlan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlans = Vlan::all();

        return response()->json($vlans);
    }

    public function store(StoreVlanRequest $request)
    {
        abort_if(Gate::denies('vlan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan = Vlan::create($request->all());

        DB::table('subnetworks')
            ->where('vlan_id', $vlan->id)
            ->update(['vlan_id' => null]);

        DB::table('subnetworks')
            ->whereIn('id', $request->input('subnetworks', []))
            ->update(['vlan_id' => $vlan->id]);

        return response()->json($vlan, 201);
    }

    public function show(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VlanResource($vlan);
    }

    public function update(UpdateVlanRequest $request, Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->update($request->all());

        DB::table('subnetworks')
            ->where('vlan_id', $vlan->id)
            ->update(['vlan_id' => null]);

        DB::table('subnetworks')
            ->whereIn('id', $request->input('subnetworks', []))
            ->update(['vlan_id' => $vlan->id]);

        return response()->json();
    }

    public function destroy(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyVlanRequest $request)
    {
        abort_if(Gate::denies('vlan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Vlan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
