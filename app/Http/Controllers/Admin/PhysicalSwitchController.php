<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSwitchRequest;
use App\Http\Requests\StorePhysicalSwitchRequest;
use App\Http\Requests\UpdatePhysicalSwitchRequest;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\NetworkSwitch;
use App\Models\PhysicalSwitch;
use App\Models\Site;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSwitchController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('physical_switch_access') ? null : Cartographer::allowedIdsFor($user, PhysicalSwitch::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $physicalSwitches = PhysicalSwitch::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (PhysicalSwitch::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.physicalSwitches.index', compact('physicalSwitches'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Select icons
        $icons = PhysicalSwitch::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // Location
        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // NetworkSwitches
        $networkSwitches = NetworkSwitch::all()->sortBy('name')->pluck('name', 'id');

        // Types
        $type_list = PhysicalSwitch::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.physicalSwitches.create',
            compact('icons', 'sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'networkSwitches', 'type_list', 'attributes_list')
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('physical_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Select icons
        $icons = PhysicalSwitch::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // Location
        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // NetworkSwitches
        $networkSwitches = NetworkSwitch::all()->sortBy('name')->pluck('name', 'id');

        // Types
        $type_list = PhysicalSwitch::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Get PhysicalSwitch
        $physicalSwitch = PhysicalSwitch::find($request['id']);

        // Vlan not found
        abort_if($physicalSwitch === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $physicalSwitch->only($physicalSwitch->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->merge(['networkSwitches' => $physicalSwitch->networkSwitches()->pluck('id')->unique()->toArray()]);
        $request->flash();

        return view(
            'admin.physicalSwitches.create',
            compact('icons', 'sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'networkSwitches', 'type_list', 'attributes_list')
        );
    }

    public function store(StorePhysicalSwitchRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $physicalSwitch = PhysicalSwitch::create($request->all());

        $physicalSwitch->networkSwitches()->sync($request->input('networkSwitches', []));

        // Save icon
        $this->iconUploadService->handle($request, $physicalSwitch);
        $physicalSwitch->save();

        return redirect()->route('admin.physical-switches.index');
    }

    public function edit(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('edit-object', $physicalSwitch), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Select icons
        $icons = PhysicalSwitch::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // Location
        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // NetworkSwitches
        $networkSwitches = NetworkSwitch::all()->sortBy('name')->pluck('name', 'id');

        // Types
        $type_list = PhysicalSwitch::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $physicalSwitch->load('site', 'building', 'bay');

        return view(
            'admin.physicalSwitches.edit',
            compact('icons', 'sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'physicalSwitch', 'networkSwitches', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdatePhysicalSwitchRequest $request, PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('edit-object', $physicalSwitch), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $physicalSwitch->update($request->all());

        $physicalSwitch->networkSwitches()->sync($request->input('networkSwitches', []));

        // Save icon
        $this->iconUploadService->handle($request, $physicalSwitch);
        $physicalSwitch->save();

        return redirect()->route('admin.physical-switches.index');
    }

    public function show(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('show-object', $physicalSwitch), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        PhysicalSwitch::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = PhysicalSwitch::query()
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
