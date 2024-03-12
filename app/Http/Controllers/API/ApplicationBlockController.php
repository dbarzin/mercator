<?php

namespace App\Http\Controllers\API;

use App\ApplicationBlock;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationBlockRequest;
use App\Http\Requests\StoreApplicationBlockRequest;
use App\Http\Requests\UpdateApplicationBlockRequest;
use App\Http\Resources\Admin\ApplicationBlockResource;
use Gate;
use Illuminate\Http\Response;

class ApplicationBlockController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationblocks = ApplicationBlock::all();

        return response()->json($applicationblocks);
    }

    public function store(StoreApplicationBlockRequest $request)
    {
        abort_if(Gate::denies('application_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationblock = ApplicationBlock::create($request->all());

        return response()->json($applicationblock, 201);
    }

    public function show(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationBlockResource($applicationBlock);
    }

    public function update(UpdateApplicationBlockRequest $request, ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->update($request->all());

        return response()->json();
    }

    public function destroy(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationBlockRequest $request)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ApplicationBlock::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
