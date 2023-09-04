<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\MApplication;
use App\LogicalServer;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActivityRequest;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PatchingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('patching_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // All patching groups
        $patchingGroups = LogicalServer::select('patching_group')->where('patching_group', '<>', null)->distinct()->orderBy('patching_group')->pluck('patching_group');

        // TODO : Physical servers
        $servers = LogicalServer::All();

        return view('admin.patching.index', compact('servers','patchingGroups'));
    }


    public function edit(Request $request)
    {
        abort_if(Gate::denies('patching_make'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $server = LogicalServer::find($request->id);
        // Lists
        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');

        return view('admin.patching.edit', compact('server','operating_system_list','environment_list'));
    }

    public function update(Request $request)
    {
        $activity->update($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->activitiesProcesses()->sync($request->input('processes', []));

        return redirect()->route('admin.activities.index');
    }

}
