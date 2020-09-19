<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePeripheralRequest;
use App\Http\Requests\UpdatePeripheralRequest;
use App\Http\Resources\Admin\PeripheralResource;
use App\Peripheral;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeripheralApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('peripheral_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PeripheralResource(Peripheral::with(['site', 'building', 'bay'])->get());
    }

    public function store(StorePeripheralRequest $request)
    {
        $peripheral = Peripheral::create($request->all());

        return (new PeripheralResource($peripheral))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PeripheralResource($peripheral->load(['site', 'building', 'bay']));
    }

    public function update(UpdatePeripheralRequest $request, Peripheral $peripheral)
    {
        $peripheral->update($request->all());

        return (new PeripheralResource($peripheral))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
