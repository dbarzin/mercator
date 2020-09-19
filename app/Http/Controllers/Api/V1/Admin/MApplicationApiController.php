<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMApplicationRequest;
use App\Http\Requests\UpdateMApplicationRequest;
use App\Http\Resources\Admin\MApplicationResource;
use App\MApplication;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MApplicationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('m_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MApplicationResource(MApplication::with(['entities', 'entity_resp', 'processes', 'services', 'databases', 'logical_servers', 'application_block'])->get());
    }

    public function store(StoreMApplicationRequest $request)
    {
        $mApplication = MApplication::create($request->all());
        $mApplication->entities()->sync($request->input('entities', []));
        $mApplication->processes()->sync($request->input('processes', []));
        $mApplication->services()->sync($request->input('services', []));
        $mApplication->databases()->sync($request->input('databases', []));
        $mApplication->logical_servers()->sync($request->input('logical_servers', []));

        return (new MApplicationResource($mApplication))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MApplication $mApplication)
    {
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MApplicationResource($mApplication->load(['entities', 'entity_resp', 'processes', 'services', 'databases', 'logical_servers', 'application_block']));
    }

    public function update(UpdateMApplicationRequest $request, MApplication $mApplication)
    {
        $mApplication->update($request->all());
        $mApplication->entities()->sync($request->input('entities', []));
        $mApplication->processes()->sync($request->input('processes', []));
        $mApplication->services()->sync($request->input('services', []));
        $mApplication->databases()->sync($request->input('databases', []));
        $mApplication->logical_servers()->sync($request->input('logical_servers', []));

        return (new MApplicationResource($mApplication))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(MApplication $mApplication)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mApplication->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
