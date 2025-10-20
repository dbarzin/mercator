<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWanRequest;
use App\Http\Requests\StoreWanRequest;
use App\Http\Requests\UpdateWanRequest;
use App\Models\Lan;
use App\Models\Man;
use App\Models\Wan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class WanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wans = Wan::all()->sortBy('name');

        return view('admin.wans.index', compact('wans'));
    }

    public function store(StoreWanRequest $request)
    {
        $wan = Wan::create($request->all());
        $wan->mans()->sync($request->input('mans', []));
        $wan->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.wans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all()->sortBy('name')->pluck('name', 'id');

        $lans = Lan::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.wans.create', compact('mans', 'lans'));
    }

    public function edit(Wan $wan)
    {
        abort_if(Gate::denies('wan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all()->sortBy('name')->pluck('name', 'id');
        $lans = Lan::all()->sortBy('name')->pluck('name', 'id');
        $wan->load('mans', 'lans');

        return view('admin.wans.edit', compact('mans', 'lans', 'wan'));
    }

    public function update(UpdateWanRequest $request, Wan $wan)
    {
        $wan->update($request->all());
        $wan->mans()->sync($request->input('mans', []));
        $wan->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.wans.index');
    }

    public function show(Wan $wan)
    {
        abort_if(Gate::denies('wan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->load('mans', 'lans');

        return view('admin.wans.show', compact('wan'));
    }

    public function destroy(Wan $wan)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->delete();

        return redirect()->route('admin.wans.index');
    }

    public function massDestroy(MassDestroyWanRequest $request)
    {
        Wan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
