<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationModuleRequest;
use App\Http\Requests\StoreApplicationModuleRequest;
use App\Http\Requests\UpdateApplicationModuleRequest;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Cartographer;
use App\Models\Entity;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ApplicationModuleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('application_module_access') ? null : Cartographer::allowedIdsFor($user, ApplicationModule::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $applicationModules = ApplicationModule::query()
            ->with('entities', 'applicationServices')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (ApplicationModule::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.applicationModules.index', compact('applicationModules'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = ApplicationService::query()->pluck('name', 'id');
        $entities = Entity::query()->pluck('name', 'id');
        $type_list = ApplicationModule::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.applicationModules.create',
            compact('services', 'entities', 'type_list', 'attributes_list'));
    }

    public function store(StoreApplicationModuleRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $applicationModule = ApplicationModule::query()->create($request->all());

        $applicationModule->applicationServices()->sync($request->input('services', []));
        $applicationModule->entities()->sync($request->input('entities', []));

        return redirect()->route('admin.application-modules.index');
    }

    public function edit(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('edit-object', $applicationModule), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = ApplicationService::query()->pluck('name', 'id');
        $entities = Entity::query()->pluck('name', 'id');
        $type_list = ApplicationModule::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.applicationModules.edit',
            compact('applicationModule', 'services', 'entities', 'type_list', 'attributes_list'));
    }

    public function update(UpdateApplicationModuleRequest $request, ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('edit-object', $applicationModule), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $applicationModule->update($request->all());

        $applicationModule->applicationServices()->sync($request->input('services', []));
        $applicationModule->entities()->sync($request->input('entities', []));

        return redirect()->route('admin.application-modules.index');
    }

    public function show(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('show-object', $applicationModule), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->load('moduleSourceFluxes', 'moduleDestFluxes', 'applicationServices', 'entities');

        return view('admin.applicationModules.show', compact('applicationModule'));
    }

    public function destroy(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->delete();

        return redirect()->route('admin.application-modules.index');
    }

    public function massDestroy(MassDestroyApplicationModuleRequest $request)
    {
        ApplicationModule::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = ApplicationModule::query()
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
