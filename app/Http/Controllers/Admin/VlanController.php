<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\MassDestroyVlanRequest;
use App\Http\Requests\StoreVlanRequest;
use App\Http\Requests\UpdateVlanRequest;

use App\Vlan;
use App\Subnetwork;

use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class VlanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('vlan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlans = Vlan::all()->sortBy('name');

        return view('admin.vlans.index', compact('vlans'));
    }

    public function create()
    {
        abort_if(Gate::denies('vlan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::all()->sortBy("name")->pluck("name","id");

        return view('admin.vlans.create',compact('subnetworks'));
    }

    public function store(StoreVlanRequest $request)
    {
        $vlan = Vlan::create($request->all());

       DB::table('subnetworks')
              ->where('vlan_id', $vlan->id)
              ->update(['vlan_id' => null]);

        DB::table('subnetworks')
              ->whereIn('id', $request->input('subnetworks', []))
              ->update(['vlan_id' => $vlan->id]);

        return redirect()->route('admin.vlans.index');
    }

    public function edit(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->load('subnetworks');

        $subnetworks = Subnetwork::all()->sortBy("name")->pluck("name","id");

        return view('admin.vlans.edit', compact('vlan','subnetworks'));
    }

    public function update(UpdateVlanRequest $request, Vlan $vlan)
    {
        $vlan->update($request->all());
        
       DB::table('subnetworks')
              ->where('vlan_id', $vlan->id)
              ->update(['vlan_id' => null]);

        DB::table('subnetworks')
              ->whereIn('id', $request->input('subnetworks', []))
              ->update(['vlan_id' => $vlan->id]);

        return redirect()->route('admin.vlans.index');
    }

    public function show(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->load('subnetworks');

        return view('admin.vlans.show', compact('vlan'));
    }

    public function destroy(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->delete();

        return back();
    }

    public function massDestroy(MassDestroyVlanRequest $request)
    {
        Vlan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
