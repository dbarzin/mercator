<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyManRequest;
use App\Http\Requests\StoreManRequest;
use App\Http\Requests\UpdateManRequest;
use App\Http\Resources\Admin\ManResource;
use App\Man;
use Gate;
use Illuminate\Http\Response;

class ManController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all();

        return response()->json($mans);
    }

    public function store(StoreManRequest $request)
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man = Man::create($request->all());
        // syncs
        // $man->roles()->sync($request->input('roles', []));

        return response()->json($man, 201);
    }

    public function show(Man $man)
    {
        abort_if(Gate::denies('man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ManResource($man);
    }

    public function update(UpdateManRequest $request, Man $man)
    {
        abort_if(Gate::denies('man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->update($request->all());
        // syncs
        // $man->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Man $man)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyManRequest $request)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Man::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
