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
    /**
     * Display the admin listing of bays.
     *
     * @return View The 'admin.bays.index' view populated with bays (including their related room) ordered by name.
     */
    public function index(): View
    {
        abort_if(Gate::denies('bay_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $bays = Bay::all()->sortBy('name');
        $bays = Bay::query()->with('room')->orderBy('name')->get();

        return view('admin.bays.index', compact('bays'));
    }

    /**
     * Display the bay creation form.
     *
     * Builds a list of buildings keyed by id with a leading "please select" option and returns the admin bays create view.
     *
     * Access is aborted with HTTP 403 if the current user lacks the "bay_create" permission.
     *
     * @return \Illuminate\View\View The view for creating a bay.
     */
    public function create(): View
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bays.create', compact('rooms'));
    }

    /**
     * Display the bay creation form pre-filled with the given bay's attributes.
     *
     * The view includes a sorted list of buildings (rooms) and flashes the provided bay's fillable attributes
     * into the request so the creation form is pre-populated.
     *
     * @param  Bay  $bay  The bay whose attributes will be used to pre-fill the creation form.
     * @return View The bay creation view with a `rooms` list and flashed input from `$bay`.
     */
    public function clone(Request $request, Bay $bay): View
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $request->merge($bay->only($bay->getFillable()));
        $request->flash();

        return view('admin.bays.create', compact('rooms'));
    }

    /**
     * Creates a new Bay from the request's validated data and redirects to the bays index.
     *
     * @param  StoreBayRequest  $request  The validated input used to create the Bay.
     * @return RedirectResponse Redirect to the admin.bays.index route.
     */
    public function store(StoreBayRequest $request): RedirectResponse
    {
        Bay::create($request->all());

        return redirect()->route('admin.bays.index');
    }

    /**
     * Show the form for editing the specified bay.
     *
     * @param  \App\Models\Bay  $bay  The bay instance to edit.
     * @return \Illuminate\View\View The edit view populated with the bay and selectable rooms.
     */
    public function edit(Bay $bay): View
    {
        abort_if(Gate::denies('bay_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bay->load('room');

        return view('admin.bays.edit', compact('rooms', 'bay'));
    }

    /**
     * Update the given Bay with validated request data and redirect to the bays index.
     *
     * @param  UpdateBayRequest  $request  Validated input for updating the bay.
     * @param  Bay  $bay  The Bay model instance to update.
     * @return RedirectResponse A redirect response to the admin.bays.index route.
     */
    public function update(UpdateBayRequest $request, Bay $bay): RedirectResponse
    {
        $bay->update($request->all());

        return redirect()->route('admin.bays.index');
    }

    /**
     * Display the specified bay with its associated room and hardware collections.
     *
     * @param  \App\Models\Bay  $bay  The Bay instance to display.
     * @return \Illuminate\View\View The view presenting the Bay and its loaded relationships.
     */
    public function show(Bay $bay): View
    {
        abort_if(Gate::denies('bay_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->load('room', 'physicalServers', 'storageDevices', 'peripherals', 'physicalSwitches', 'physicalRouters', 'physicalSecurityDevices');

        return view('admin.bays.show', compact('bay'));
    }

    /**
     * Delete the given Bay and redirect to the bays index.
     *
     * @param  \App\Models\Bay  $bay  The Bay model to delete.
     * @return \Illuminate\Http\RedirectResponse Redirects to the admin.bays.index route.
     */
    public function destroy(Bay $bay): RedirectResponse
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->delete();

        return redirect()->route('admin.bays.index');
    }

    /**
     * Deletes multiple Bay records identified by IDs supplied in the request.
     *
     * @param  MassDestroyBayRequest  $request  The request containing an `ids` array of Bay record IDs to delete.
     * @return Response An empty response with HTTP 204 No Content.
     */
    public function massDestroy(MassDestroyBayRequest $request): Response
    {
        Bay::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
