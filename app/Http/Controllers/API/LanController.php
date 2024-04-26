<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLanRequest;
use App\Http\Requests\StoreLanRequest;
use App\Http\Requests\UpdateLanRequest;
use App\Http\Resources\Admin\LanResource;
use App\Lan;
use Gate;
use Illuminate\Http\Response;

class LanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lans = Lan::all();

        return response()->json($lans);
    }

    public function store(StoreLanRequest $request)
    {
        abort_if(Gate::denies('lan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan = Lan::create($request->all());
        $lan->lansMen()->sync($request->input('mans', []));
        $lan->lansWans()->sync($request->input('wans', []));
        // syncs
        // $lan->roles()->sync($request->input('roles', []));

        return response()->json($lan, 201);
    }

    public function show(Lan $lan)
    {
        abort_if(Gate::denies('lan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LanResource($lan);
    }

    public function update(UpdateLanRequest $request, Lan $lan)
    {
        abort_if(Gate::denies('lan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->update($request->all());
        $lan->lansMen()->sync($request->input('mans', []));
        $lan->lansWans()->sync($request->input('wans', []));
        // syncs
        // $lan->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Lan $lan)
    {
        abort_if(Gate::denies('lan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyLanRequest $request)
    {
        abort_if(Gate::denies('lan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Lan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
