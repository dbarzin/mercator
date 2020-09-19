<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ApplicationBlock;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreApplicationBlockRequest;
use App\Http\Requests\UpdateApplicationBlockRequest;
use App\Http\Resources\Admin\ApplicationBlockResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationBlockApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationBlockResource(ApplicationBlock::all());
    }

    public function store(StoreApplicationBlockRequest $request)
    {
        $applicationBlock = ApplicationBlock::create($request->all());

        return (new ApplicationBlockResource($applicationBlock))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationBlockResource($applicationBlock);
    }

    public function update(UpdateApplicationBlockRequest $request, ApplicationBlock $applicationBlock)
    {
        $applicationBlock->update($request->all());

        return (new ApplicationBlockResource($applicationBlock))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
