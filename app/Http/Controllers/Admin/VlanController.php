<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\MassDestroyVlanRequest;
use App\Http\Requests\StoreVlanRequest;
use App\Http\Requests\UpdateVlanRequest;

use App\Vlan;
use App\PhysicalRouter;

use Gate;
use Illuminate\Http\Request;
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

        $physicalRouters = PhysicalRouter::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.vlans.create',compact('physicalRouters'));
    }

    public function store(StoreVlanRequest $request)
    {
        $vlan = Vlan::create($request->all());

        $vlan->vlanPhysicalRouters()->sync($request->input('physicalRouters', []));

        return redirect()->route('admin.vlans.index');
    }

    public function edit(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $linkToPhysicalRouters = PhysicalRouter::all()->sortBy('name')->pluck('name', 'id');

        $vlan->load('vlanPhysicalRouters');

        return view('admin.vlans.edit', compact('vlan','linkToPhysicalRouters'));
    }

    public function update(UpdateVlanRequest $request, Vlan $vlan)
    {
        $vlan->update($request->all());

        $vlan->vlanPhysicalRouters()->sync($request->input('linkToPhysicalRouters', []));

        return redirect()->route('admin.vlans.index');
    }

    public function show(Vlan $vlan)
    {
        abort_if(Gate::denies('vlan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlan->load('vlanPhysicalRouters');

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
