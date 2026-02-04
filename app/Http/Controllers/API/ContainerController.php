<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyContainerRequest;
use App\Http\Requests\StoreContainerRequest;
use App\Http\Requests\UpdateContainerRequest;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Mercator\Core\Models\Container;
use Symfony\Component\HttpFoundation\Response;

class ContainerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('container_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $containers = Container::all();

        return response()->json($containers);
    }

    public function store(StoreContainerRequest $request)
    {
        abort_if(Gate::denies('container_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $container = Container::query()->create($request->all());

        $container->logicalServers()->sync($request->input('logical_servers', []));
        $container->applications()->sync($request->input('applications', []));

        Log::Debug('ContainerController:store Done');

        return response()->json($container, 201);
    }

    public function show(Container $container)
    {
        abort_if(Gate::denies('container_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($container);
    }

    public function update(UpdateContainerRequest $request, Container $container)
    {
        abort_if(Gate::denies('container_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $container->update($request->all());

        if ($request->has('logical_servers'))
            $container->logicalServers()->sync($request->input('logical_servers', []));
        if ($request->has('applications'))
            $container->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(Container $container)
    {
        abort_if(Gate::denies('container_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $container->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyContainerRequest $request)
    {
        abort_if(Gate::denies('container_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Container::query()->whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
