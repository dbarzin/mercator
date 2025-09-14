<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMApplicationRequest;
use App\Http\Requests\UpdateMApplicationRequest;
use App\Http\Resources\Admin\ApplicationResource;
use App\Models\MApplication;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = QueryBuilder::for(\App\Models\MApplication::query(), $request)
            ->allowedFilters([
                'name',
                'application_block_id',
                'description',
                'vendor',
                'product',
                'version',
                'entity_resp_id',
                'functional_referent',
                'editor',
                'technology',
                'documentation',
                'type',
                'users',
                'responsible',
                'security_need_c',
                'security_need_i',
                'security_need_a',
                'security_need_t',
                'security_need_auth',
                'rto',
                'rpo',
                'external',
                'attributes',
                'patching_frequency',
                'install_date',
                'update_date',
                'next_update',
            ])
            ->get();

        return response()->json($applications);
    }

    public function store(StoreMApplicationRequest $request)
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application = MApplication::create($request->all());
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('application_services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->logicalServers()->sync($request->input('logical_servers', []));
        $application->activities()->sync($request->input('activities', []));

        return response()->json($application, 201);
    }

    public function show(MApplication $application)
    {
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->entities = $application->entities()->pluck('id');
        $application->processes = $application->processes()->pluck('id');
        $application->services = $application->services()->pluck('id');
        $application->databases = $application->databases()->pluck('id');
        $application->logicalServers = $application->logicalServers()->pluck('id');
        $application->activities = $application->activities()->pluck('id');

        return new ApplicationResource($application);
    }

    public function update(UpdateMApplicationRequest $request, MApplication $application)
    {
        abort_if(Gate::denies('m_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->update($request->all());

        if ($request->has('entities')) {
            $application->entities()->sync($request->input('entities', []));
        }
        if ($request->has('processes')) {
            $application->processes()->sync($request->input('processes', []));
        }
        if ($request->has('activities')) {
            $application->activities()->sync($request->input('activities', []));
        }
        if ($request->has('databases')) {
            $application->databases()->sync($request->input('databases', []));
        }
        if ($request->has('logical_servers')) {
            $application->logicalServers()->sync($request->input('logical_servers', []));
        }
        if ($request->has('application_services')) {
            $application->services()->sync($request->input('application_services', []));
        }

        return response()->json();
    }

    public function destroy(MApplication $application)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->delete();

        return response()->json();
    }
}
