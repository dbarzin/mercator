<?php

namespace App\Http\Controllers\API;

use App\Peripheral;

use App\Http\Requests\StorePeripheralRequest;
use App\Http\Requests\UpdatePeripheralRequest;
use App\Http\Requests\MassDestroyPeripheralRequest;
use App\Http\Resources\Admin\PeripheralResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

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
        Peripheral::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

