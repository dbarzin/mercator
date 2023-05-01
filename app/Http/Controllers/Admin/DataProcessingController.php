<?php

namespace App\Http\Controllers\Admin;

use App\Information;
use App\MApplication;
use App\Process;
use App\DataProcessing;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDataProcessingRequest;
use App\Http\Requests\StoreDataProcessingRequest;
use App\Http\Requests\UpdateDataProcessingRequest;

use Gate;

use Symfony\Component\HttpFoundation\Response;

class DataProcessingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('data_processing_register_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processingRegister = DataProcessing::orderBy('name')->get();

        return view('admin.dataProcessing.index', compact('processingRegister'));
    }

    public function create()
    {
        abort_if(Gate::denies('data_processing_register_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::orderBy('identifiant')->get()->pluck('identifiant', 'id');
        $informations = Information::orderBy('name')->get()->pluck('name', 'id');
        $applications = MApplication::orderBy('name')->get()->pluck('name', 'id');

        session()->put("documents",array());

        return view('admin.dataProcessing.create', 
            compact('applications', 'informations', 'processes'));
    }

    public function store(StoreDataProcessingRequest $request)
    {
        $dataProcessing = DataProcessing::create($request->all());
        $dataProcessing->processes()->sync($request->input('processes', []));
        $dataProcessing->informations()->sync($request->input('informations', []));
        $dataProcessing->applications()->sync($request->input('applications', []));
        $dataProcessing->documents()->sync(session()->get("documents"));
        session()->forget("documents");

        return redirect()->route('admin.data-processing.index');
    }

    public function edit(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_register_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::orderBy('identifiant')->get()->pluck('identifiant', 'id');
        $informations = Information::orderBy('name')->get()->pluck('name', 'id');
        $applications = MApplication::orderBy('name')->get()->pluck('name', 'id');
        session()->put("documents", $dataProcessing->documents()->get());

//dd(session()->get("documents"));

        $dataProcessing->load('applications', 'informations', 'processes');

        return view('admin.dataProcessing.edit', 
            compact('dataProcessing', 'applications', 'informations', 'processes'));
    }

    public function update(UpdateDataProcessingRequest $request, DataProcessing $dataProcessing)
    {
        $dataProcessing->update($request->all());
        $dataProcessing->processes()->sync($request->input('processes', []));
        $dataProcessing->applications()->sync($request->input('applications', []));
        $dataProcessing->informations()->sync($request->input('informations', []));
        $dataProcessing->documents()->sync(session()->get("documents"));
        session()->forget("documents");

        return redirect()->route('admin.data-processing.index');
    }

    public function show(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_register_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing->load('applications', 'informations', 'processes');

        return view('admin.dataProcessing.show', compact('dataProcessing'));
    }

    public function destroy(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_register_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing->delete();

        return redirect()->route('admin.data-processing.index');
    }

    public function massDestroy(MassDestroyDataProcessingRequest $request)
    {
        DataProcessing::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
