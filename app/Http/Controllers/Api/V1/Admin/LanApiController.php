<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanRequest;
use App\Http\Requests\UpdateLanRequest;
use App\Http\Resources\Admin\LanResource;
use App\Lan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LanResource(Lan::all());
    }

    public function store(StoreLanRequest $request)
    {
        $lan = Lan::create($request->all());

        return (new LanResource($lan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Lan $lan)
    {
        abort_if(Gate::denies('lan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LanResource($lan);
    }

    public function update(UpdateLanRequest $request, Lan $lan)
    {
        $lan->update($request->all());

        return (new LanResource($lan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Lan $lan)
    {
        abort_if(Gate::denies('lan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
