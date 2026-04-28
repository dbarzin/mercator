<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationServiceRequest;
use App\Http\Requests\StoreApplicationServiceRequest;
use App\Http\Requests\UpdateApplicationServiceRequest;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\MApplication;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ApplicationServiceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationServices = ApplicationService::all()->sortBy('name');

        return view('admin.applicationServices.index', compact('applicationServices'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');

        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $exposition_list = ApplicationService::select('exposition')->where('exposition', '<>', null)->distinct()->orderBy('exposition')->pluck('exposition');

        return view(
            'admin.applicationServices.create',
            compact('modules', 'applications', 'exposition_list')
        );
    }

    public function store(StoreApplicationServiceRequest $request)
    {
        $applicationService = ApplicationService::create($request->all());
        $applicationService->modules()->sync($request->input('modules', []));
        $applicationService->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.application-services.index');
    }

    public function edit(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');

        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $exposition_list = ApplicationService::select('exposition')->where('exposition', '<>', null)->distinct()->orderBy('exposition')->pluck('exposition');

        $applicationService->load('modules', 'applications');

        return view(
            'admin.applicationServices.edit',
            compact('modules', 'applications', 'exposition_list', 'applicationService')
        );
    }

    public function update(UpdateApplicationServiceRequest $request, ApplicationService $applicationService)
    {
        $applicationService->update($request->all());
        $applicationService->modules()->sync($request->input('modules', []));
        $applicationService->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.application-services.index');
    }

    public function show(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationService->load('modules', 'serviceSourceFluxes', 'serviceDestFluxes', 'applications');

        return view('admin.applicationServices.show', compact('applicationService'));
    }

    public function destroy(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('application_service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationService->delete();

        return redirect()->route('admin.application-services.index');
    }

    public function massDestroy(MassDestroyApplicationServiceRequest $request)
    {
        ApplicationService::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
