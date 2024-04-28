<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Entity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProcessRequest;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Information;
use App\MacroProcessus;
use App\MApplication;
use App\Process;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ProcessController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::with('operations', 'activities', 'processInformation', 'macroProcess')->orderBy('name')->get();

        return view('admin.processes.index', compact('processes'));
    }

    public function create()
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::orderBy('name')->pluck('name', 'id');
        $entities = Entity::orderBy('name')->pluck('name', 'id');
        $informations = Information::orderBy('name')->pluck('name', 'id');
        $macroProcessuses = MacroProcessus::orderBy('name')->pluck('name', 'id');
        $applications = MApplication::orderBy('name')->pluck('name', 'id');
        // lists
        $owner_list = Process::select('owner')->where('owner', '<>', null)
            ->distinct()->orderBy('owner')->pluck('owner');

        return view(
            'admin.processes.create',
            compact('activities', 'entities', 'informations', 'applications', 'macroProcessuses', 'owner_list')
        );
    }

    public function store(StoreProcessRequest $request)
    {
        $process = Process::create($request->all());
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->processInformation()->sync($request->input('informations', []));
        $process->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.processes.index');
    }

    public function edit(Process $process)
    {
        abort_if(Gate::denies('process_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::orderBy('name')->pluck('name', 'id');
        $entities = Entity::orderBy('name')->pluck('name', 'id');
        $informations = Information::orderBy('name')->pluck('name', 'id');
        $macroProcessuses = MacroProcessus::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::orderBy('name')->pluck('name', 'id');
        // lists
        $owner_list = Process::select('owner')->where('owner', '<>', null)->distinct()->orderBy('owner')->pluck('owner');

        $process->load('activities', 'entities', 'processInformation', 'applications');

        return view(
            'admin.processes.edit',
            compact('activities', 'entities', 'informations', 'process', 'macroProcessuses', 'owner_list', 'applications')
        );
    }

    public function update(UpdateProcessRequest $request, Process $process)
    {
        $process->update($request->all());
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->processInformation()->sync($request->input('informations', []));
        $process->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.processes.index');
    }

    public function show(Process $process)
    {
        abort_if(Gate::denies('process_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->load('activities', 'entities', 'processInformation', 'applications', 'macroProcess');

        return view('admin.processes.show', compact('process'));
    }

    public function destroy(Process $process)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->delete();

        return redirect()->route('admin.processes.index');
    }

    public function massDestroy(MassDestroyProcessRequest $request)
    {
        Process::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
