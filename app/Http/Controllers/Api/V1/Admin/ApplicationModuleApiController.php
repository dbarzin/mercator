<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ApplicationModule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreApplicationModuleRequest;
use App\Http\Requests\UpdateApplicationModuleRequest;
use App\Http\Resources\Admin\ApplicationModuleResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationModuleApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationModuleResource(ApplicationModule::all());
    }

    public function store(StoreApplicationModuleRequest $request)
    {
        $applicationModule = ApplicationModule::create($request->all());

        return (new ApplicationModuleResource($applicationModule))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationModuleResource($applicationModule);
    }

    public function update(UpdateApplicationModuleRequest $request, ApplicationModule $applicationModule)
    {
        $applicationModule->update($request->all());

        return (new ApplicationModuleResource($applicationModule))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
