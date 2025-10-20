<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRouterRequest;
use App\Http\Requests\StoreRouterRequest;
use App\Http\Requests\UpdateRouterRequest;
use App\Models\NetworkSwitch;
use App\Models\PhysicalRouter;
use App\Models\Router;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class RouterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $routers = Router::all()->sortBy('name');

        return view('admin.routers.index', compact('routers'));
    }

    public function create()
    {
        abort_if(Gate::denies('router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network_switches = NetworkSwitch::orderBy('name')->pluck('name', 'id');
        $physical_routers = PhysicalRouter::all()->sortBy('name')->pluck('name', 'id');

        $type_list = Router::all()->sortBy('type')->pluck('type');

        return view(
            'admin.routers.create',
            compact('network_switches', 'physical_routers', 'type_list')
        );
    }

    public function store(StoreRouterRequest $request)
    {
        $router = Router::create($request->all());
        $router->physicalRouters()->sync($request->input('physicalRouters', []));

        return redirect()->route('admin.routers.index');
    }

    public function edit(Router $router)
    {
        abort_if(Gate::denies('router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network_switches = NetworkSwitch::orderBy('name')->pluck('name', 'id');
        $physical_routers = PhysicalRouter::all()->sortBy('name')->pluck('name', 'id');

        $type_list = Router::all()->sortBy('type')->pluck('type');

        return view(
            'admin.routers.edit',
            compact('router', 'network_switches', 'physical_routers', 'type_list')
        );
    }

    public function update(UpdateRouterRequest $request, Router $router)
    {
        $router->update($request->all());

        $router->physicalRouters()->sync($request->input('physicalRouters', []));

        return redirect()->route('admin.routers.index');
    }

    public function show(Router $router)
    {
        abort_if(Gate::denies('router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        Router::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
