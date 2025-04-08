<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLanRequest;
use App\Http\Requests\StoreLanRequest;
use App\Http\Requests\UpdateLanRequest;
use App\Lan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class LanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lans = Lan::all()->sortBy('name');

        return view('admin.lans.index', compact('lans'));
    }

    public function create()
    {
        abort_if(Gate::denies('lan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lans.create');
    }

    public function store(StoreLanRequest $request)
    {
        Lan::create($request->all());

        return redirect()->route('admin.lans.index');
    }

    public function edit(Lan $lan)
    {
        abort_if(Gate::denies('lan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lans.edit', compact('lan'));
    }

    public function update(UpdateLanRequest $request, Lan $lan)
    {
        $lan->update($request->all());

        return redirect()->route('admin.lans.index');
    }

    public function show(Lan $lan)
    {
        abort_if(Gate::denies('lan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->load('mans', 'wans');

        return view('admin.lans.show', compact('lan'));
    }

    public function destroy(Lan $lan)
    {
        abort_if(Gate::denies('lan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->delete();

        return redirect()->route('admin.lans.index');
    }

    public function massDestroy(MassDestroyLanRequest $request)
    {
        Lan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
