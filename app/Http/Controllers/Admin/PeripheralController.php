<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPeripheralRequest;
use App\Http\Requests\StorePeripheralRequest;
use App\Http\Requests\UpdatePeripheralRequest;
use App\Models\Application;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\Entity;
use App\Models\Peripheral;
use App\Models\Site;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PeripheralController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('peripheral_access') ? null : Cartographer::allowedIdsFor($user, Peripheral::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $peripherals = Peripheral::with(['site', 'building', 'bay', 'provider', 'domain'])
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Peripheral::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.peripherals.index', compact('peripherals'));
    }

    public function create()
    {
        abort_if(Gate::denies('peripheral_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $applications = Application::all()->sortBy('name')->pluck('name', 'id');
        $icons = Peripheral::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // lists
        $type_list = Peripheral::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domains = DB::table('domains')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $responsible_list = Peripheral::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.peripherals.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'buildingSiteMap',
                'bayBuildingMap',
                'entities',
                'applications',
                'icons',
                'type_list',
                'domains',
                'responsible_list',
                'attributes_list'
            )
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('peripheral_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::query()->orderBy('name')->pluck('name', 'id');
        $buildings = Building::query()->orderBy('name')->pluck('name', 'id');
        $bays = Bay::query()->orderBy('name')->pluck('name', 'id');
        $entities = Entity::query()->orderBy('name')->pluck('name', 'id');
        $applications = Application::query()->orderBy('name')->pluck('name', 'id');
        $icons = Peripheral::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // lists
        $type_list = Peripheral::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domains = DB::table('domains')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $responsible_list = Peripheral::query()->select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $attributes_list = $this->getAttributes();

        // Get Peripheral
        $peripheral = Peripheral::find($request['id']);

        // Vlan not found
        abort_if($peripheral === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $peripheral->only($peripheral->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->merge(['applications' => $peripheral->applications()->pluck('id')->unique()->toArray()]);
        $request->flash();

        return view(
            'admin.peripherals.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'buildingSiteMap',
                'bayBuildingMap',
                'entities',
                'applications',
                'icons',
                'type_list',
                'domains',
                'responsible_list',
                'attributes_list'
            )
        );
    }

    public function store(StorePeripheralRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Get fields
        $peripheral = Peripheral::create($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $peripheral);

        // Save Peripheral
        $peripheral->save();

        // Save links
        $peripheral->applications()->sync($request->input('applications', []));

        // Redirect
        return redirect()->route('admin.peripherals.index');
    }

    public function edit(Peripheral $peripheral)
    {
        abort_if(Gate::denies('edit-object', $peripheral), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $applications = Application::all()->sortBy('name')->pluck('name', 'id');
        $icons = Peripheral::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        // lists
        $type_list = Peripheral::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domains = DB::table('domains')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $responsible_list = Peripheral::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $attributes_list = $this->getAttributes();

        $peripheral->load('site', 'building', 'bay', 'domain');

        return view(
            'admin.peripherals.edit',
            compact(
                'sites',
                'buildings',
                'bays',
                'buildingSiteMap',
                'bayBuildingMap',
                'entities',
                'applications',
                'icons',
                'peripheral',
                'type_list',
                'domains',
                'responsible_list',
                'attributes_list'
            )
        );
    }

    public function update(UpdatePeripheralRequest $request, Peripheral $peripheral)
    {
        abort_if(Gate::denies('edit-object', $peripheral), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Save icon
        $this->iconUploadService->handle($request, $peripheral);

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Get fields
        $peripheral->update($request->all());

        // Update links
        $peripheral->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.peripherals.index');
    }

    public function show(Peripheral $peripheral)
    {
        abort_if(Gate::denies('show-object', $peripheral), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->load('site', 'building', 'bay', 'domain');

        return view('admin.peripherals.show', compact('peripheral'));
    }

    public function destroy(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->delete();

        return redirect()->route('admin.peripherals.index');
    }

    public function massDestroy(MassDestroyPeripheralRequest $request)
    {
        Peripheral::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Peripheral::query()
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
