<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWanRequest;
use App\Http\Requests\StoreWanRequest;
use App\Http\Requests\UpdateWanRequest;
use App\Http\Resources\Admin\WanResource;
use App\Wan;
use Gate;
use Illuminate\Http\Response;

class WanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wans = Wan::all();

        return response()->json($wans);
    }

    public function store(StoreWanRequest $request)
    {
        abort_if(Gate::denies('wan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan = Wan::create($request->all());
        // syncs
        // $wan->roles()->sync($request->input('roles', []));

        return response()->json($wan, 201);
    }

    public function show(Wan $wan)
    {
        abort_if(Gate::denies('wan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WanResource($wan);
    }

    public function update(UpdateWanRequest $request, Wan $wan)
    {
        abort_if(Gate::denies('wan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->update($request->all());
        // syncs
        // $wan->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Wan $wan)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWanRequest $request)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Wan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
