<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyInformationRequest;
use App\Http\Requests\StoreInformationRequest;
use App\Http\Requests\UpdateInformationRequest;
use App\Information;
use App\Process;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class InformationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information = Information::all()->sortBy('name');

        return view('admin.information.index', compact('information'));
    }

    public function create()
    {
        abort_if(Gate::denies('information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::all()->sortBy('identifiant')->pluck('identifiant', 'id');

        return view('admin.information.create', compact('processes'));
    }

    public function store(StoreInformationRequest $request)
    {
        $information = Information::create($request->all());
        $information->processes()->sync($request->input('processes', []));

        return redirect()->route('admin.information.index');
    }

    public function edit(Information $information)
    {
        abort_if(Gate::denies('information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::all()->sortBy('identifiant')->pluck('identifiant', 'id');

        $information->load('processes');

        return view('admin.information.edit', compact('processes', 'information'));
    }

    public function update(UpdateInformationRequest $request, Information $information)
    {
        $information->update($request->all());
        $information->processes()->sync($request->input('processes', []));

        return redirect()->route('admin.information.index');
    }

    public function show(Information $information)
    {
        abort_if(Gate::denies('information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information->load('processes', 'informationsDatabases');

        return view('admin.information.show', compact('information'));
    }

    public function destroy(Information $information)
    {
        abort_if(Gate::denies('information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information->delete();

        return back();
    }

    public function massDestroy(MassDestroyInformationRequest $request)
    {
        Information::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
