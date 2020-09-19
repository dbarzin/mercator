<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Entity;
use App\Process;
use App\Information;
use App\MacroProcessus;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProcessRequest;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProcessController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::all()->sortBy('name');

        return view('admin.processes.index', compact('processes'));
    }

    public function create()
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $informations = Information::all()->sortBy('name')->pluck('name', 'id');
        $macroProcessuses = MacroProcessus::all()->sortBy('name')->pluck('name', 'id');
        $owner_list = Process::select('owner')->where("owner","<>",null)->distinct()->orderBy('owner')->pluck('owner');

        return view('admin.processes.create', 
            compact('activities', 'entities','informations','macroProcessuses','owner_list')
            );
    }

    public function store(StoreProcessRequest $request)
    {
        $process = Process::create($request->all());
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->processInformation()->sync($request->input('informations', []));
        // TODO: only one process per macroprocess - XXXX
        // $process->processesMacroProcessuses()->sync($request->input('informations', []));

        return redirect()->route('admin.processes.index');
    }

    public function edit(Process $process)
    {
        abort_if(Gate::denies('process_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $informations = Information::all()->sortBy('name')->pluck('name', 'id');
        $macroProcessuses = MacroProcessus::all()->sortBy('name')->pluck('name', 'id');
        // lists
        $owner_list = Process::select('owner')->where("owner","<>",null)->distinct()->orderBy('owner')->pluck('owner');

        $process->load('activities', 'entities', 'processInformation');

        return view('admin.processes.edit', 
            compact('activities', 'entities', 'informations','process','macroProcessuses','owner_list'));
    }

    public function update(UpdateProcessRequest $request, Process $process)
    {
        $process->update($request->all());
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->processInformation()->sync($request->input('informations', []));

        return redirect()->route('admin.processes.index');
    }

    public function show(Process $process)
    {
        abort_if(Gate::denies('process_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->load('activities', 'entities', 'processInformation', 'processesMApplications', 'macroProcess');

        return view('admin.processes.show', compact('process'));
    }

    public function destroy(Process $process)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->delete();

        return back();
    }

    public function massDestroy(MassDestroyProcessRequest $request)
    {
        Process::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
