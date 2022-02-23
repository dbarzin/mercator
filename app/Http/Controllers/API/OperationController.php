<?php

namespace App\Http\Controllers\API;

use App\Operation;

use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Http\Requests\MassDestroyOperationRequest;
use App\Http\Resources\Admin\OperationResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('operation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $operations = Operation::all();

    return response()->json($operations);
    }

    public function store(StoreOperationRequest $request)
    {
        abort_if(Gate::denies('operation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operation = Operation::create($request->all());
        // syncs
        // $operation->roles()->sync($request->input('roles', []));

        return response()->json($operation, 201);
    }

    public function show(Operation $operation)
    {
        abort_if(Gate::denies('operation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OperationResource($operation);
    }

    public function update(UpdateOperationRequest $request, Operation $operation)
    {     
        abort_if(Gate::denies('operation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operation->update($request->all());
        // syncs
        // $operation->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Operation $operation)
    {
        abort_if(Gate::denies('operation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operation->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyOperationRequest $request)
    {
        Operation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

