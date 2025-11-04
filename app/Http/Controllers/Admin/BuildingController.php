<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBuildingRequest;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;
use App\Models\Site;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BuildingController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        abort_if(Gate::denies('building_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $buildings = Building::with('site')->orderBy('name')->get();

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        abort_if(Gate::denies('building_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::query()->orderBy('name')->pluck('name', 'id');
        $buildings = Building::query()->orderBy('name')->pluck('name', 'id');

        // Lists
        $attributes_list = $this->getAttributes();
        $type_list = Building::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        // Select icons
        $icons = Building::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view(
            'admin.buildings.create',
            compact('sites', 'buildings', 'icons', 'attributes_list', 'type_list')
        );
    }


    public function clone(Request $request, Building $building)
    {
        abort_if(Gate::denies('building_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $attributes_list = $this->getAttributes();
        $type_list = Building::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        // Select icons
        $icons = Building::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $request->merge($building->only($building->getFillable()));
        $request->flash();

        return view(
            'admin.buildings.create',
            compact('sites', 'buildings', 'icons', 'attributes_list', 'type_list')
        );
    }



    public function store(StoreBuildingRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $building = Building::create($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $building);

        // Save Building
        $building->save();

        // set children
        Building::whereIn('id', $request->input('buildings', []))
            ->update(['building_id' => $building->id]);

        return redirect()->route('admin.buildings.index');
    }

    public function edit(Building $building)
    {
        abort_if(Gate::denies('building_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $attributes_list = $this->getAttributes();
        $type_list = Building::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        // Select icons
        $icons = Building::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $building->load('site');

        return view(
            'admin.buildings.edit',
            compact('building', 'sites', 'buildings', 'icons', 'attributes_list', 'type_list')
        );
    }

    public function update(UpdateBuildingRequest $request, Building $building)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Clear building_id if the building is not present
        if (! $request->has('building_id')) {
            $building->building_id = null;
        }

        // Save icon
        $this->iconUploadService->handle($request, $building);

        // Save Building
        $building->update($request->all());

        // update children
        Building::where('building_id', $building->id)
            ->update(['building_id' => null]);

        Building::whereIn('id', $request->input('buildings', []))
            ->update(['building_id' => $building->id]);

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
        $attributes_list = Building::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
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
