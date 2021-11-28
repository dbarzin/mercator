<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationModule;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationModuleRequest;
use App\Http\Requests\StoreApplicationModuleRequest;
use App\Http\Requests\UpdateApplicationModuleRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ApplicationModuleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModules = ApplicationModule::all()->sortBy('name');

        return view('admin.applicationModules.index', compact('applicationModules'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.applicationModules.create');
    }

    public function store(StoreApplicationModuleRequest $request)
    {
        ApplicationModule::create($request->all());

        return redirect()->route('admin.application-modules.index');
    }

    public function edit(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.applicationModules.edit', compact('applicationModule'));
    }

    public function update(UpdateApplicationModuleRequest $request, ApplicationModule $applicationModule)
    {
        $applicationModule->update($request->all());

        return redirect()->route('admin.application-modules.index');
    }

    public function show(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->load('moduleSourceFluxes', 'moduleDestFluxes', 'applicationServices');

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
        ApplicationModule::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
