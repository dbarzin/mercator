<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkstationRequest;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;
use App\Models\Application;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\Workstation;
use App\Services\IconUploadService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class WorkstationController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('workstation_access') ? null : Cartographer::allowedIdsFor($user, Workstation::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $workstations = Workstation::with('site', 'building')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Workstation::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.workstations.index', compact('workstations'));
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = DB::table('sites')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = DB::table('buildings')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $entities = DB::table('entities')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $domains = DB::table('domains')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = DB::table('admin_users')->select('id', 'user_id')->whereNull('deleted_at')->orderBy('user_id')->pluck('user_id', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = DB::table('networks')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Get icons
        $icons = Workstation::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $application_list = Application::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $status_list = Workstation::select('status')
            ->where('status', '<>', null)
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Configuration
        $manufacturer_list = Workstation::select('manufacturer')
            ->where('manufacturer', '<>', null)
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
        $model_list = Workstation::select('model')
            ->where('model', '<>', null)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

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

        $network_port_type_list = Workstation::select('network_port_type')
            ->where('network_port_type', '<>', null)
            ->distinct()
            ->orderBy('network_port_type')
            ->pluck('network_port_type');

        $attributes_list = $this->getAttributes();

        // Get Workstation
        $workstation = Workstation::find($request['id']);

        // Workstation not found
        abort_if($workstation === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $workstation->only($workstation->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->flash();

        return view(
            'admin.workstations.create',
            compact(
                'sites',
                'buildings',
                'buildingSiteMap',
                'icons',
                'type_list',
                'status_list',
                'manufacturer_list',
                'model_list',
                'operating_system_list',
                'cpu_list',
                'application_list',
                'entities',
                'domains',
                'users',
                'networks',
                'network_port_type_list',
                'attributes_list'
            )
        );
    }

    public function store(StoreWorkstationRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $workstation = Workstation::create($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $workstation);

        $workstation->save();

        // Sync applications
        $workstation->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.workstations.index');
    }

    public function create()
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = DB::table('sites')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = DB::table('buildings')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $entities = DB::table('entities')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $domains = DB::table('domains')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = DB::table('admin_users')->select('id', 'user_id')->whereNull('deleted_at')->orderBy('user_id')->pluck('user_id', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = DB::table('networks')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Select icons
        $icons = Workstation::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $application_list = Application::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $status_list = Workstation::select('status')
            ->where('status', '<>', null)
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Configuration
        $manufacturer_list = Workstation::select('manufacturer')
            ->where('manufacturer', '<>', null)
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
        $model_list = Workstation::select('model')
            ->where('model', '<>', null)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

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

        $network_port_type_list = Workstation::select('network_port_type')
            ->where('network_port_type', '<>', null)
            ->distinct()
            ->orderBy('network_port_type')
            ->pluck('network_port_type');

        $attributes_list = $this->getAttributes();

        return view(
            'admin.workstations.create',
            compact(
                'sites',
                'buildings',
                'buildingSiteMap',
                'icons',
                'type_list',
                'status_list',
                'manufacturer_list',
                'model_list',
                'operating_system_list',
                'cpu_list',
                'application_list',
                'entities',
                'domains',
                'users',
                'networks',
                'network_port_type_list',
                'attributes_list'
            )
        );
    }

    public function edit(Workstation $workstation)
    {
        abort_if(Gate::denies('edit-object', $workstation), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = DB::table('sites')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = DB::table('buildings')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $entities = DB::table('entities')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $domains = DB::table('domains')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = DB::table('admin_users')->select('id', 'user_id')->whereNull('deleted_at')->orderBy('user_id')->pluck('user_id', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = DB::table('networks')->select('id', 'name')->whereNull('deleted_at')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Get icons
        $icons = Workstation::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $application_list = Application::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $status_list = Workstation::select('status')
            ->where('status', '<>', null)
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Configuration
        $manufacturer_list = Workstation::select('manufacturer')
            ->where('manufacturer', '<>', null)
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
        $model_list = Workstation::select('model')
            ->where('model', '<>', null)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

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

        $network_port_type_list = Workstation::select('network_port_type')
            ->where('network_port_type', '<>', null)
            ->distinct()
            ->orderBy('network_port_type')
            ->pluck('network_port_type');

        $attributes_list = $this->getAttributes();

        $workstation->load('site', 'building');

        return view(
            'admin.workstations.edit',
            compact(
                'workstation',
                'sites',
                'buildings',
                'buildingSiteMap',
                'icons',
                'type_list',
                'status_list',
                'manufacturer_list',
                'model_list',
                'operating_system_list',
                'cpu_list',
                'application_list',
                'entities',
                'domains',
                'users',
                'networks',
                'network_port_type_list',
                'attributes_list'
            )
        );
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        abort_if(Gate::denies('edit-object', $workstation), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Save icon
        $this->iconUploadService->handle($request, $workstation);

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $workstation->update($request->all());
        $workstation->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.workstations.index');
    }

    public function show(Workstation $workstation)
    {
        abort_if(Gate::denies('show-object', $workstation), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        Workstation::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Workstation::query()
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
