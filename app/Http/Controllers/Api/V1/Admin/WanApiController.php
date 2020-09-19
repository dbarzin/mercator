<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWanRequest;
use App\Http\Requests\UpdateWanRequest;
use App\Http\Resources\Admin\WanResource;
use App\Wan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WanApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WanResource(Wan::with(['mans', 'lans'])->get());
    }

    public function store(StoreWanRequest $request)
    {
        $wan = Wan::create($request->all());
        $wan->mans()->sync($request->input('mans', []));
        $wan->lans()->sync($request->input('lans', []));

        return (new WanResource($wan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Wan $wan)
    {
        abort_if(Gate::denies('wan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WanResource($wan->load(['mans', 'lans']));
    }

    public function update(UpdateWanRequest $request, Wan $wan)
    {
        $wan->update($request->all());
        $wan->mans()->sync($request->input('mans', []));
        $wan->lans()->sync($request->input('lans', []));

        return (new WanResource($wan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Wan $wan)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
