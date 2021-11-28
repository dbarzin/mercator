<?php

namespace App\Http\Controllers\Admin;

use App\Bay;
use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSwitchRequest;
use App\Http\Requests\StorePhysicalSwitchRequest;
use App\Http\Requests\UpdatePhysicalSwitchRequest;
use App\PhysicalSwitch;
use App\Site;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSwitchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitches = PhysicalSwitch::all()->sortBy('name');

        return view('admin.physicalSwitches.index', compact('physicalSwitches'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.physicalSwitches.create', compact('sites', 'buildings', 'bays'));
    }

    public function store(StorePhysicalSwitchRequest $request)
    {
        PhysicalSwitch::create($request->all());

        return redirect()->route('admin.physical-switches.index');
    }

    public function edit(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $physicalSwitch->load('site', 'building', 'bay');

        return view('admin.physicalSwitches.edit', compact('sites', 'buildings', 'bays', 'physicalSwitch'));
    }

    public function update(UpdatePhysicalSwitchRequest $request, PhysicalSwitch $physicalSwitch)
    {
        $physicalSwitch->update($request->all());

        return redirect()->route('admin.physical-switches.index');
    }

    public function show(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->load('site', 'building', 'bay');

        return view('admin.physicalSwitches.show', compact('physicalSwitch'));
    }

    public function destroy(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->delete();

        return redirect()->route('admin.physical-switches.index');
    }

    public function massDestroy(MassDestroyPhysicalSwitchRequest $request)
    {
        PhysicalSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
