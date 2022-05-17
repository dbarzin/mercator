<?php

namespace App\Http\Controllers\API;

use App\ApplicationModule;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationModuleRequest;
use App\Http\Requests\StoreApplicationModuleRequest;
use App\Http\Requests\UpdateApplicationModuleRequest;
use App\Http\Resources\Admin\ApplicationModuleResource;
use Gate;
use Illuminate\Http\Response;

class ApplicationModuleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationmodules = ApplicationModule::all();

        return response()->json($applicationmodules);
    }

    public function store(StoreApplicationModuleRequest $request)
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationmodule = ApplicationModule::create($request->all());
        // syncs
        // $applicationmodule->roles()->sync($request->input('roles', []));

        return response()->json($applicationmodule, 201);
    }

    public function show(ApplicationModule $applicationmodule)
    {
        abort_if(Gate::denies('application_module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationModuleResource($applicationmodule);
    }

    public function update(UpdateApplicationModuleRequest $request, ApplicationModule $applicationmodule)
    {
        abort_if(Gate::denies('application_module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationmodule->update($request->all());
        // syncs
        // $applicationmodule->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(ApplicationModule $applicationmodule)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationmodule->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationModuleRequest $request)
    {
        ApplicationModule::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
