<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ApplicationService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreApplicationServiceRequest;
use App\Http\Requests\UpdateApplicationServiceRequest;
use App\Http\Resources\Admin\ApplicationServiceResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationServiceApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationServiceResource(ApplicationService::with(['modules'])->get());
    }

    public function store(StoreApplicationServiceRequest $request)
    {
        $applicationService = ApplicationService::create($request->all());
        $applicationService->modules()->sync($request->input('modules', []));

        return (new ApplicationServiceResource($applicationService))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationServiceResource($applicationService->load(['modules']));
    }

    public function update(UpdateApplicationServiceRequest $request, ApplicationService $applicationService)
    {
        $applicationService->update($request->all());
        $applicationService->modules()->sync($request->input('modules', []));

        return (new ApplicationServiceResource($applicationService))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationService->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
