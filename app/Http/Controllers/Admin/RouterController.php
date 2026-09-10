<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRouterRequest;
use App\Http\Requests\StoreRouterRequest;
use App\Http\Requests\UpdateRouterRequest;
use App\Models\Cartographer;
use App\Models\NetworkSwitch;
use App\Models\PhysicalRouter;
use App\Models\Router;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class RouterController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('router_access') ? null : Cartographer::allowedIdsFor($user, Router::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $routers = Router::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Router::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.routers.index', compact('routers'));
    }

    public function create()
    {
        abort_if(Gate::denies('router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network_switches = NetworkSwitch::orderBy('name')->pluck('name', 'id');
        $physical_routers = PhysicalRouter::all()->sortBy('name')->pluck('name', 'id');

        $type_list = Router::all()->sortBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.routers.create',
            compact('network_switches', 'physical_routers', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreRouterRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $router = Router::create($request->all());
        $router->physicalRouters()->sync($request->input('physicalRouters', []));

        return redirect()->route('admin.routers.index');
    }

    public function edit(Router $router)
    {
        abort_if(Gate::denies('edit-object', $router), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network_switches = NetworkSwitch::orderBy('name')->pluck('name', 'id');
        $physical_routers = PhysicalRouter::all()->sortBy('name')->pluck('name', 'id');

        $type_list = Router::all()->sortBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.routers.edit',
            compact('router', 'network_switches', 'physical_routers', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdateRouterRequest $request, Router $router)
    {
        abort_if(Gate::denies('edit-object', $router), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $router->update($request->all());

        $router->physicalRouters()->sync($request->input('physicalRouters', []));

        return redirect()->route('admin.routers.index');
    }

    public function show(Router $router)
    {
        abort_if(Gate::denies('show-object', $router), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.routers.show', compact('router'));
    }

    public function destroy(Router $router)
    {
        abort_if(Gate::denies('router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $router->delete();

        return redirect()->route('admin.routers.index');
    }

    public function massDestroy(MassDestroyRouterRequest $request)
    {
        Router::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Router::query()
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
