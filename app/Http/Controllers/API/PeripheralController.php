<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPeripheralRequest;
use App\Http\Requests\StorePeripheralRequest;
use App\Http\Requests\UpdatePeripheralRequest;
use App\Http\Resources\Admin\PeripheralResource;
use App\Peripheral;
use Gate;
use Illuminate\Http\Response;

class PeripheralController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('peripheral_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripherals = Peripheral::all();

        return response()->json($peripherals);
    }

    public function store(StorePeripheralRequest $request)
    {
        abort_if(Gate::denies('peripheral_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral = Peripheral::create($request->all());
        // syncs
        // $peripheral->roles()->sync($request->input('roles', []));

        return response()->json($peripheral, 201);
    }

    public function show(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PeripheralResource($peripheral);
    }

    public function update(UpdatePeripheralRequest $request, Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->update($request->all());
        // syncs
        // $peripheral->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPeripheralRequest $request)
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Peripheral::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
