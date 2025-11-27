<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationServiceRequest;
use App\Http\Requests\StoreApplicationServiceRequest;
use App\Http\Requests\UpdateApplicationServiceRequest;
use Mercator\Core\Models\ApplicationService;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationServiceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationServices = ApplicationService::all();

        return response()->json($applicationServices);
    }

    public function store(StoreApplicationServiceRequest $request)
    {
        abort_if(Gate::denies('application_service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationService = ApplicationService::create($request->all());
        $applicationService->modules()->sync($request->input('modules', []));
        $applicationService->applications()->sync($request->input('applications', []));

        return response()->json($applicationService, 201);
    }

    public function show(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($applicationService);
    }

    public function update(UpdateApplicationServiceRequest $request, ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationService->update($request->all());
        $applicationService->modules()->sync($request->input('modules', []));
        $applicationService->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationService->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationServiceRequest $request)
    {
        abort_if(Gate::denies('application_service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ApplicationService::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
