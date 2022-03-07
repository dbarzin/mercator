<?php

namespace App\Http\Controllers\API;

use App\MApplication;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Requests\MassDestroyApplicationRequest;
use App\Http\Resources\Admin\ApplicationResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $applications = MApplication::all();

    return response()->json($applications);
    }

    public function store(StoreApplicationRequest $request)
    {
        abort_if(Gate::denies('application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application = MApplication::create($request->all());
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->logical_servers()->sync($request->input('logical_servers', []));

        return response()->json($application, 201);
    }

    public function show(MApplication $application)
    {
        abort_if(Gate::denies('application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationResource($application);
    }

    public function update(UpdateApplicationRequest $request, MApplication $application)
    {     
        abort_if(Gate::denies('application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->update($request->all());
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->logical_servers()->sync($request->input('logical_servers', []));

        return response()->json();
    }

    public function destroy(MApplication $application)
    {
        abort_if(Gate::denies('application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->delete();

        return response()->json();
    }

}

