<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationServiceRequest;
use App\Http\Requests\StoreApplicationServiceRequest;
use App\Http\Requests\UpdateApplicationServiceRequest;
use App\Models\Application;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Cartographer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ApplicationServiceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('application_service_access') ? null : Cartographer::allowedIdsFor($user, ApplicationService::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $applicationServices = ApplicationService::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (ApplicationService::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.applicationServices.index', compact('applicationServices'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');

        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $exposition_list = ApplicationService::select('exposition')->where('exposition', '<>', null)->distinct()->orderBy('exposition')->pluck('exposition');
        $type_list = ApplicationService::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.applicationServices.create',
            compact('modules', 'applications', 'exposition_list', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreApplicationServiceRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $applicationService = ApplicationService::create($request->all());
        $applicationService->modules()->sync($request->input('modules', []));
        $applicationService->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.application-services.index');
    }

    public function edit(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('edit-object', $applicationService), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');

        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $exposition_list = ApplicationService::select('exposition')->where('exposition', '<>', null)->distinct()->orderBy('exposition')->pluck('exposition');
        $type_list = ApplicationService::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $applicationService->load('modules', 'applications');

        return view(
            'admin.applicationServices.edit',
            compact('modules', 'applications', 'exposition_list', 'applicationService', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdateApplicationServiceRequest $request, ApplicationService $applicationService)
    {
        abort_if(Gate::denies('edit-object', $applicationService), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $applicationService->update($request->all());
        $applicationService->modules()->sync($request->input('modules', []));
        $applicationService->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.application-services.index');
    }

    public function show(ApplicationService $applicationService)
    {
        abort_if(Gate::denies('show-object', $applicationService), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        ApplicationService::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = ApplicationService::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
