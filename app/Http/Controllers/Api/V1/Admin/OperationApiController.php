<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Http\Resources\Admin\OperationResource;
use App\Operation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('operation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OperationResource(Operation::with(['actors', 'tasks'])->get());
    }

    public function store(StoreOperationRequest $request)
    {
        $operation = Operation::create($request->all());
        $operation->actors()->sync($request->input('actors', []));
        $operation->tasks()->sync($request->input('tasks', []));

        return (new OperationResource($operation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Operation $operation)
    {
        abort_if(Gate::denies('operation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OperationResource($operation->load(['actors', 'tasks']));
    }

    public function update(UpdateOperationRequest $request, Operation $operation)
    {
        $operation->update($request->all());
        $operation->actors()->sync($request->input('actors', []));
        $operation->tasks()->sync($request->input('tasks', []));

        return (new OperationResource($operation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Operation $operation)
    {
        abort_if(Gate::denies('operation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
