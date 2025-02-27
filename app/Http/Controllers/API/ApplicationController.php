<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\Admin\ApplicationResource;
use App\MApplication;
use Gate;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('m_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::all();

        return response()->json($applications);
    }

    public function store(StoreApplicationRequest $request)
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ApplicationResource($application);
    }

    public function update(UpdateApplicationRequest $request, MApplication $application)
    {
        abort_if(Gate::denies('m_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->delete();

        return response()->json();
    }
}
