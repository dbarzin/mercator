<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Bay;
use App\Building;
use App\PhysicalRouter;
use App\Site;
use App\Vlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalRouterRequest;
use App\Http\Requests\StorePhysicalRouterRequest;
use App\Http\Requests\UpdatePhysicalRouterRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalRouterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouters = PhysicalRouter::all();

        return view('admin.physicalRouters.index', compact('physicalRouters'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.physicalRouters.create', compact('sites', 'buildings', 'bays', 'vlans'));
    }

    public function store(StorePhysicalRouterRequest $request)
    {
        $physicalRouter = PhysicalRouter::create($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));

        return redirect()->route('admin.physical-routers.index');
    }

    public function edit(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');

        $physicalRouter->load('site', 'building', 'bay', 'vlans');

        return view('admin.physicalRouters.edit', compact('sites', 'buildings', 'bays', 'vlans', 'physicalRouter'));
    }

    public function update(UpdatePhysicalRouterRequest $request, PhysicalRouter $physicalRouter)
    {
        $physicalRouter->update($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));

        return redirect()->route('admin.physical-routers.index');
    }

    public function show(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->load('site', 'building', 'bay', 'vlans');

        return view('admin.physicalRouters.show', compact('physicalRouter'));
    }

    public function destroy(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->delete();

        return back();
    }

    public function massDestroy(MassDestroyPhysicalRouterRequest $request)
    {
        PhysicalRouter::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
