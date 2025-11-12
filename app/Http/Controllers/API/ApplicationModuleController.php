<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationModuleRequest;
use App\Http\Requests\StoreApplicationModuleRequest;
use App\Http\Requests\UpdateApplicationModuleRequest;
use App\Models\ApplicationModule;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationModuleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModules = ApplicationModule::all();

        return response()->json($applicationModules);
    }

    public function store(StoreApplicationModuleRequest $request)
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule = ApplicationModule::create($request->all());

        return response()->json($applicationModule, 201);
    }

    public function show(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($applicationModule);
    }

    public function update(UpdateApplicationModuleRequest $request, ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->update($request->all());

        return response()->json();
    }

    public function destroy(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationModuleRequest $request)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ApplicationModule::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
