<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBayRequest;
use App\Http\Requests\StoreBayRequest;
use App\Http\Requests\UpdateBayRequest;
use App\Models\Bay;
use App\Models\Building;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BayController extends Controller
{
    public function index(): View
    {
        abort_if(Gate::denies('bay_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $bays = Bay::all()->sortBy('name');
        $bays = Bay::with('room')->orderBy('name')->get();

        return view('admin.bays.index', compact('bays'));
    }

    public function create(): View
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bays.create', compact('rooms'));
    }

    public function clone(Request $request, Bay $bay) : View
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $request->merge($bay->only($bay->getFillable()));
        $request->flash();

        return view('admin.bays.create', compact('rooms'));
    }

    public function store(StoreBayRequest $request): RedirectResponse
    {
        Bay::create($request->all());

        return redirect()->route('admin.bays.index');
    }

    public function edit(Bay $bay): View
    {
        abort_if(Gate::denies('bay_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bay->load('room');

        return view('admin.bays.edit', compact('rooms', 'bay'));
    }

    public function update(UpdateBayRequest $request, Bay $bay): RedirectResponse
    {
        $bay->update($request->all());

        return redirect()->route('admin.bays.index');
    }

    public function show(Bay $bay): View
    {
        abort_if(Gate::denies('bay_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->load('room', 'bayPhysicalServers', 'bayStorageDevices', 'bayPeripherals', 'bayPhysicalSwitches', 'bayPhysicalRouters', 'bayPhysicalSecurityDevices');

        return view('admin.bays.show', compact('bay'));
    }

    public function destroy(Bay $bay): RedirectResponse
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->delete();

        return redirect()->route('admin.bays.index');
    }

    public function massDestroy(MassDestroyBayRequest $request): Response
    {
        Bay::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
