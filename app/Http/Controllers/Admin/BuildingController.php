<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBuildingRequest;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;
use App\Models\Site;
use Gate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BuildingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('building_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $buildings = Building::with('site')->orderBy('name')->get();

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        abort_if(Gate::denies('building_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $attributes_list = $this->getAttributes();

        return view('admin.buildings.create', compact('sites', 'attributes_list'));
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('building_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $attributes_list = $this->getAttributes();

        // Get building
        $building = Building::find($request->id);

        // Building not found
        abort_if($building === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $request->merge($building->only($building->getFillable()));
        $request->flash();

        return view('admin.buildings.create', compact('sites', 'attributes_list'));
    }

    public function store(StoreBuildingRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $building = Building::create($request->all());

        return redirect()->route('admin.buildings.index');
    }

    public function edit(Building $building)
    {
        abort_if(Gate::denies('building_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $attributes_list = $this->getAttributes();

        $building->load('site');

        return view('admin.buildings.edit', compact('sites', 'attributes_list', 'building'));
    }

    public function update(UpdateBuildingRequest $request, Building $building)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $building->update($request->all());

        return redirect()->route('admin.buildings.index');
    }

    public function show(Building $building)
    {
        abort_if(Gate::denies('building_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $building->load('site', 'roomBays', 'buildingPhysicalServers', 'buildingWorkstations', 'buildingStorageDevices', 'buildingPeripherals', 'buildingPhones', 'buildingPhysicalSwitches');

        return view('admin.buildings.show', compact('building'));
    }

    public function destroy(Building $building)
    {
        abort_if(Gate::denies('building_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $building->delete();

        return redirect()->route('admin.buildings.index');
    }

    public function massDestroy(MassDestroyBuildingRequest $request)
    {
        Building::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Building::select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
