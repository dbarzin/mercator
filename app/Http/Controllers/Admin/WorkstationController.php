<?php

namespace App\Http\Controllers\Admin;

use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkstationRequest;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;
use App\MApplication;
use App\Site;
use App\Workstation;
use Gate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkstationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('workstation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstations = Workstation::with('site', 'building')->orderBy('name')->get();

        return view('admin.workstations.index', compact('workstations'));
    }

    public function create()
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $operating_system_list = Workstation::select('operating_system')
            ->where('operating_system', '<>', null)
            ->distinct()
            ->orderBy('operating_system')
            ->pluck('operating_system');
        $cpu_list = Workstation::select('cpu')
            ->where('cpu', '<>', null)
            ->distinct()
            ->orderBy('cpu')
            ->pluck('cpu');

        return view(
            'admin.workstations.create',
            compact('sites', 'buildings', 'type_list', 'operating_system_list', 'cpu_list', 'application_list')
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $operating_system_list = Workstation::select('operating_system')
            ->where('operating_system', '<>', null)
            ->distinct()
            ->orderBy('operating_system')
            ->pluck('operating_system');
        $cpu_list = Workstation::select('cpu')
            ->where('cpu', '<>', null)
            ->distinct()
            ->orderBy('cpu')
            ->pluck('cpu');

        // Get Workstation
        $workstation = Workstation::find($request->id);

        // Workstation not found
        abort_if($workstation === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $request->merge($workstation->only($workstation->getFillable()));
        $request->flash();

        return view(
            'admin.workstations.create',
            compact('sites', 'buildings', 'type_list', 'operating_system_list', 'cpu_list', 'application_list')
        );
    }

    public function store(StoreWorkstationRequest $request)
    {
        $workstation = Workstation::create($request->all());
        $workstation->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.workstations.index');
    }

    public function edit(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $operating_system_list = Workstation::select('operating_system')
            ->where('operating_system', '<>', null)
            ->distinct()
            ->orderBy('operating_system')
            ->pluck('operating_system');
        $cpu_list = Workstation::select('cpu')
            ->where('cpu', '<>', null)
            ->distinct()
            ->orderBy('cpu')
            ->pluck('cpu');

        $workstation->load('site', 'building');

        return view(
            'admin.workstations.edit',
            compact('sites', 'buildings', 'workstation', 'type_list', 'operating_system_list', 'cpu_list', 'application_list')
        );
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        $workstation->update($request->all());
        $workstation->applications()->sync($request->input('applications', []));

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

        return redirect()->route('admin.workstations.index');
    }

    public function massDestroy(MassDestroyWorkstationRequest $request)
    {
        Workstation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
