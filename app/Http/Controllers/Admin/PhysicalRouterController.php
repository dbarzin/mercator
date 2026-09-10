<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalRouterRequest;
use App\Http\Requests\StorePhysicalRouterRequest;
use App\Http\Requests\UpdatePhysicalRouterRequest;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\PhysicalRouter;
use App\Models\Router;
use App\Models\Site;
use App\Models\Vlan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalRouterController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('physical_router_access') ? null : Cartographer::allowedIdsFor($user, PhysicalRouter::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $physicalRouters = PhysicalRouter::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (PhysicalRouter::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.physicalRouters.index', compact('physicalRouters'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');

        $type_list = PhysicalRouter::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.physicalRouters.create',
            compact('sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'routers', 'vlans', 'type_list', 'attributes_list')
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');

        $type_list = PhysicalRouter::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Get PhysicalRouter
        $physicalRouter = PhysicalRouter::find($request['id']);

        // PhysicalRouter not found
        abort_if($physicalRouter === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $physicalRouter->only($physicalRouter->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->merge(['vlans' => $physicalRouter->vlans()->pluck('id')->unique()->toArray()]);
        $request->merge(['routers' => $physicalRouter->routers()->pluck('id')->unique()->toArray()]);
        $request->flash();

        return view(
            'admin.physicalRouters.create',
            compact('sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'routers', 'vlans', 'type_list', 'attributes_list')
        );
    }

    public function store(StorePhysicalRouterRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $physicalRouter = PhysicalRouter::create($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));
        $physicalRouter->routers()->sync($request->input('routers', []));

        return redirect()->route('admin.physical-routers.index');
    }

    public function edit(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('edit-object', $physicalRouter), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $bayBuildingMap = Bay::pluck('building_id', 'id');

        $type_list = PhysicalRouter::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $physicalRouter->load('site', 'building', 'bay', 'vlans');

        return view(
            'admin.physicalRouters.edit',
            compact('sites', 'buildings', 'bays', 'buildingSiteMap', 'bayBuildingMap', 'vlans', 'physicalRouter', 'routers', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdatePhysicalRouterRequest $request, PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('edit-object', $physicalRouter), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $physicalRouter->update($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));
        $physicalRouter->routers()->sync($request->input('routers', []));

        return redirect()->route('admin.physical-routers.index');
    }

    public function show(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('show-object', $physicalRouter), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->load('site', 'building', 'bay', 'vlans');

        return view('admin.physicalRouters.show', compact('physicalRouter'));
    }

    public function destroy(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->delete();

        return redirect()->route('admin.physical-routers.index');
    }

    public function massDestroy(MassDestroyPhysicalRouterRequest $request)
    {
        PhysicalRouter::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = PhysicalRouter::query()
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
