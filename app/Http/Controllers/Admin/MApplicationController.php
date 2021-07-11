<?php

namespace App\Http\Controllers\Admin;

use Gate;

use App\ApplicationBlock;
use App\ApplicationService;
use App\Database;
use App\Entity;
use App\LogicalServer;
use App\MApplication;
use App\Process;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMApplicationRequest;
use App\Http\Requests\StoreMApplicationRequest;
use App\Http\Requests\UpdateMApplicationRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MApplicationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('m_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::all()->sortBy('name');

        return view('admin.applications.index', compact('applications'));
    }

    public function create()
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $entity_resps = Entity::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $processes = Process::all()->sortBy('identifiant')->pluck('identifiant', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $application_blocks = ApplicationBlock::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // lists
        $type_list = MApplication::select('type')->where("type","<>",null)->distinct()->orderBy('type')->pluck('type');
        $technology_list = MApplication::select('technology')->where("technology","<>",null)->distinct()->orderBy('technology')->pluck('technology');
        $users_list = MApplication::select('users')->where("users","<>",null)->distinct()->orderBy('users')->pluck('users');
        $external_list = MApplication::select('external')->where("external","<>",null)->distinct()->orderBy('external')->pluck('external');
        $responsible_list = MApplication::select('responsible')->where("responsible","<>",null)->distinct()->orderBy('responsible')->pluck('responsible');

        return view('admin.applications.create', 
            compact('entities', 'entity_resps', 'processes', 'services', 'databases', 'logical_servers', 'application_blocks',
                'type_list','technology_list', 'users_list','external_list','responsible_list'
            ));
    }

    public function store(StoreMApplicationRequest $request)
    {
        $application = MApplication::create($request->all());
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->logical_servers()->sync($request->input('logical_servers', []));

        return redirect()->route('admin.applications.index');
    }

    public function edit(MApplication $application)
    {
        abort_if(Gate::denies('m_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $entity_resps = Entity::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $processes = Process::all()->sortBy('identificant')->pluck('identifiant', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $application_blocks = ApplicationBlock::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // lists
        $type_list = MApplication::select('type')->where("type","<>",null)->distinct()->orderBy('type')->pluck('type');
        $technology_list = MApplication::select('technology')->where("technology","<>",null)->distinct()->orderBy('technology')->pluck('technology');
        $users_list = MApplication::select('users')->where("users","<>",null)->distinct()->orderBy('users')->pluck('users');
        $external_list = MApplication::select('external')->where("external","<>",null)->distinct()->orderBy('external')->pluck('external');
        $responsible_list = MApplication::select('responsible')->where("responsible","<>",null)->distinct()->orderBy('responsible')->pluck('responsible');

        $application->load('entities', 'entity_resp', 'processes', 'services', 'databases', 'logical_servers', 'application_block');

        return view('admin.applications.edit', 
            compact('entities', 'entity_resps', 'processes', 'services', 'databases', 'logical_servers', 'application_blocks', 'application',
                    'type_list','technology_list', 'users_list','external_list','responsible_list'
            ));
    }

    public function update(UpdateMApplicationRequest $request, MApplication $application)
    {
        $application->update($request->all());
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->logical_servers()->sync($request->input('logical_servers', []));

        return redirect()->route('admin.applications.index');
    }

    public function show(MApplication $Application)
    {
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $Application->load('entities', 'entity_resp', 'processes', 'services', 'databases', 'logical_servers', 'application_block', 'applicationSourceFluxes', 'applicationDestFluxes');

        return view('admin.applications.show', compact('Application'));
    }

    public function destroy(MApplication $application)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->delete();

        return back();
    }

    public function massDestroy(MassDestroyMApplicationRequest $request)
    {
        MApplication::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
