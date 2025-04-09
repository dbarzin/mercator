<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyManRequest;
use App\Http\Requests\StoreManRequest;
use App\Http\Requests\UpdateManRequest;
use App\Lan;
use App\Man;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ManController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all()->sortBy('name');

        return view('admin.mans.index', compact('mans'));
    }

    public function create()
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lans = Lan::pluck('name', 'id')->sortBy('name');

        return view('admin.mans.create', compact('lans'));
    }

    public function store(StoreManRequest $request)
    {
        $man = Man::create($request->all());
        $man->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.mans.index');
    }

    public function edit(Man $man)
    {
        abort_if(Gate::denies('man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lans = Lan::pluck('name', 'id')->sortBy('name');

        $man->load('lans');

        return view('admin.mans.edit', compact('lans', 'man'));
    }

    public function update(UpdateManRequest $request, Man $man)
    {
        $man->update($request->all());
        $man->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.mans.index');
    }

    public function show(Man $man)
    {
        abort_if(Gate::denies('man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->load('lans', 'wans');

        return view('admin.mans.show', compact('man'));
    }

    public function destroy(Man $man)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->delete();

        return redirect()->route('admin.mans.index');
    }

    public function massDestroy(MassDestroyManRequest $request)
    {
        Man::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
