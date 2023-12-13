<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LogicalServer;
use Gate;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatchingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('patching_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // All patching groups
        $patching_group_list = LogicalServer::select('patching_group')
            ->whereNull('deleted_at')
            ->where('patching_group', '<>', null)
            ->distinct()
            ->orderBy('patching_group')
            ->pluck('patching_group');

        // TODO : Physical servers
        $group = $request->group;
        if ($group === 'None') {
            $servers = LogicalServer::All();
            session()->forget('patching_group');
        } elseif ($group === null) {
            $group = session()->get('patching_group');
            if ($group === null) {
                $servers = LogicalServer::All();
            } else {
                $servers = LogicalServer::where('patching_group', '=', $group)->get();
            }
        } else {
            $servers = LogicalServer::where('patching_group', '=', $group)->get();
            session()->put('patching_group', $group);
        }

        return view('admin.patching.index', compact('servers', 'patching_group_list'));
    }

    public function edit(Request $request)
    {
        abort_if(Gate::denies('patching_make'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $server = LogicalServer::find($request->id);
        // Lists
        $patching_group_list = LogicalServer::select('patching_group')->where('patching_group', '<>', null)->distinct()->orderBy('patching_group')->pluck('patching_group');
        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');

        // Documents
        $documents = [];
        foreach ($server->documents as $doc) {
            array_push($documents, $doc->id);
        }
        session()->put('documents', $documents);

        return view('admin.patching.edit', compact('server', 'operating_system_list', 'environment_list', 'patching_group_list'));
    }

    public function update(Request $request)
    {
        $logicalServer = LogicalServer::find($request->id);
        $logicalServer->update($request->all());
        $logicalServer->documents()->sync(session()->get('documents'));
        session()->forget('documents');

        // Update frequency
        if ($request->get('global_periodicity')!=null) {
            $lservers = LogicalServer::where('patching_group','=',$logicalServer->patching_group)->get();
            foreach ($lservers as $s) {
                $s->patching_frequency = $logicalServer->patching_frequency;
                if ($s->update_date!=null)
                    $s->next_update = Carbon::createFromFormat(config('panel.date_format'),$s->update_date)->addMonth($logicalServer->patching_frequency)->format(config('panel.date_format'));
                $s->save();
            }
        }

        return redirect()->route('admin.patching.index');
    }

    public function dashboard(Request $request) {

        // All patching groups
        $patching_group_list = LogicalServer::select('patching_group')
            ->where('patching_group', '<>', null)
            ->whereNull('deleted_at')
            ->distinct()
            ->orderBy('patching_group')
            ->pluck('patching_group');

        // Get pacthes
        $patches = LogicalServer::select('id','name','patching_group','update_date','next_update');
        if ($request->get('group')!=null)
            $patches = $patches->where('patching_group','=',$request->get('group'));
        $patches = $patches->get();

        return view('admin.patching.dashboard', compact('patches','patching_group_list'));
    }

}
