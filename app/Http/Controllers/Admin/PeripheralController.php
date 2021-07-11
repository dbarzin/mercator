<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Bay;
use App\Building;
use App\Peripheral;
use App\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPeripheralRequest;
use App\Http\Requests\StorePeripheralRequest;
use App\Http\Requests\UpdatePeripheralRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeripheralController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('peripheral_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripherals = Peripheral::all()->sortBy('name');

        return view('admin.peripherals.index', compact('peripherals'));
    }

    public function create()
    {
        abort_if(Gate::denies('peripheral_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.peripherals.create', compact('sites', 'buildings', 'bays'));
    }

    public function store(StorePeripheralRequest $request)
    {
        $peripheral = Peripheral::create($request->all());

        return redirect()->route('admin.peripherals.index');
    }

    public function edit(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $peripheral->load('site', 'building', 'bay');

        return view('admin.peripherals.edit', compact('sites', 'buildings', 'bays', 'peripheral'));
    }

    public function update(UpdatePeripheralRequest $request, Peripheral $peripheral)
    {
        $peripheral->update($request->all());

        return redirect()->route('admin.peripherals.index');
    }

    public function show(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->load('site', 'building', 'bay');

        return view('admin.peripherals.show', compact('peripheral'));
    }

    public function destroy(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->delete();

        return back();
    }

    public function massDestroy(MassDestroyPeripheralRequest $request)
    {
        Peripheral::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
