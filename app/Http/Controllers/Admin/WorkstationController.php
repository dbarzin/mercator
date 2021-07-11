<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Building;
use App\Site;
use App\Workstation;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkstationRequest;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkstationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('workstation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstations = Workstation::all()->sortBy('name');

        return view('admin.workstations.index', compact('workstations'));
    }

    public function create()
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.workstations.create', compact('sites', 'buildings'));
    }

    public function store(StoreWorkstationRequest $request)
    {
        $workstation = Workstation::create($request->all());

        return redirect()->route('admin.workstations.index');
    }

    public function edit(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $workstation->load('site', 'building');

        return view('admin.workstations.edit', compact('sites', 'buildings', 'workstation'));
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        $workstation->update($request->all());

        return redirect()->route('admin.workstations.index');
    }

    public function show(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->load('site', 'building');

        return view('admin.workstations.show', compact('workstation'));
    }

    public function destroy(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->delete();

        return back();
    }

    public function massDestroy(MassDestroyWorkstationRequest $request)
    {
        Workstation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
