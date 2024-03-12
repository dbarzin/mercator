<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRouterRequest;
use App\Http\Requests\StoreRouterRequest;
use App\Http\Requests\UpdateRouterRequest;
use App\Http\Resources\Admin\RouterResource;
use App\Router;
use Gate;
use Illuminate\Http\Response;

class RouterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $routers = Router::all();

        return response()->json($routers);
    }

    public function store(StoreRouterRequest $request)
    {
        abort_if(Gate::denies('router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $router = Router::create($request->all());
        // syncs
        // $router->roles()->sync($request->input('roles', []));

        return response()->json($router, 201);
    }

    public function show(Router $router)
    {
        abort_if(Gate::denies('router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RouterResource($router);
    }

    public function update(UpdateRouterRequest $request, Router $router)
    {
        abort_if(Gate::denies('router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $router->update($request->all());
        // syncs
        // $router->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Router $router)
    {
        abort_if(Gate::denies('router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $router->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyRouterRequest $request)
    {
        abort_if(Gate::denies('router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Router::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
