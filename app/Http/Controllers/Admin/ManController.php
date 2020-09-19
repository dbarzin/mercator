<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyManRequest;
use App\Http\Requests\StoreManRequest;
use App\Http\Requests\UpdateManRequest;
use App\Lan;
use App\Man;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all()->sortBy('name');

        return view('admin.men.index', compact('mans'));
    }

    public function create()
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lans = Lan::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.men.create', compact('lans'));
    }

    public function store(StoreManRequest $request)
    {
        $man = Man::create($request->all());
        $man->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.men.index');
    }

    public function edit(Man $man)
    {
        abort_if(Gate::denies('man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lans = Lan::all()->pluck('name', 'id');

        $man->load('lans');

        return view('admin.men.edit', compact('lans', 'man'));
    }

    public function update(UpdateManRequest $request, Man $man)
    {
        $man->update($request->all());
        $man->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.men.index');
    }

    public function show(Man $man)
    {
        abort_if(Gate::denies('man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->load('lans', 'mansWans');

        return view('admin.men.show', compact('man'));
    }

    public function destroy(Man $man)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->delete();

        return back();
    }

    public function massDestroy(MassDestroyManRequest $request)
    {
        Man::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
