<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManRequest;
use App\Http\Requests\UpdateManRequest;
use App\Http\Resources\Admin\ManResource;
use App\Man;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ManResource(Man::with(['lans'])->get());
    }

    public function store(StoreManRequest $request)
    {
        $man = Man::create($request->all());
        $man->lans()->sync($request->input('lans', []));

        return (new ManResource($man))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Man $man)
    {
        abort_if(Gate::denies('man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ManResource($man->load(['lans']));
    }

    public function update(UpdateManRequest $request, Man $man)
    {
        $man->update($request->all());
        $man->lans()->sync($request->input('lans', []));

        return (new ManResource($man))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Man $man)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
