<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBayRequest;
use App\Http\Requests\StoreBayRequest;
use App\Http\Requests\UpdateBayRequest;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\Site;
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
     * @return View The 'admin.bays.index' view populated with bays (including their related site and building) ordered by name.
     */
    public function index(): View
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('bay_access') ? null : Cartographer::allowedIdsFor($user, Bay::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $bays = Bay::query()->with('site', 'building')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Bay::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.bays.index', compact('bays'));
    }

    /**
     * Display the bay creation form.
     *
     * Builds the lists of sites and buildings used to populate the form's selects and returns the admin bays create view.
     *
     * Access is aborted with HTTP 403 if the current user lacks the "bay_create" permission.
     *
     * @return View The view for creating a bay.
     */
    public function create(): View
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $type_list = Bay::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.bays.create', compact('sites', 'buildings', 'buildingSiteMap', 'type_list', 'attributes_list'));
    }

    /**
     * Display the bay creation form pre-filled with the given bay's attributes.
     *
     * The view includes sorted lists of sites and buildings and flashes the provided bay's fillable attributes
     * into the request so the creation form is pre-populated.
     *
     * @param  Bay  $bay  The bay whose attributes will be used to pre-fill the creation form.
     * @return View The bay creation view with `sites`/`buildings` lists and flashed input from `$bay`.
     */
    public function clone(Request $request, Bay $bay): View
    {
        abort_if(Gate::denies('bay_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $type_list = Bay::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $data = $bay->only($bay->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->flash();

        return view('admin.bays.create', compact('sites', 'buildings', 'buildingSiteMap', 'type_list', 'attributes_list'));
    }

    /**
     * Creates a new Bay from the request's validated data and redirects to the bays index.
     *
     * @param  StoreBayRequest  $request  The validated input used to create the Bay.
     * @return RedirectResponse Redirect to the admin.bays.index route.
     */
    public function store(StoreBayRequest $request): RedirectResponse
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        Bay::create($request->all());

        return redirect()->route('admin.bays.index');
    }

    /**
     * Show the form for editing the specified bay.
     *
     * @param  Bay  $bay  The bay instance to edit.
     * @return View The edit view populated with the bay and selectable sites/buildings.
     */
    public function edit(Bay $bay): View
    {
        abort_if(Gate::denies('edit-object', $bay), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $buildingSiteMap = Building::pluck('site_id', 'id');
        $type_list = Bay::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $bay->load('site', 'building');

        return view('admin.bays.edit', compact('sites', 'buildings', 'buildingSiteMap', 'bay', 'type_list', 'attributes_list'));
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
        abort_if(Gate::denies('edit-object', $bay), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $bay->update($request->all());

        return redirect()->route('admin.bays.index');
    }

    /**
     * Display the specified bay with its associated site, building and hardware collections.
     *
     * @param  Bay  $bay  The Bay instance to display.
     * @return View The view presenting the Bay and its loaded relationships.
     */
    public function show(Bay $bay): View
    {
        abort_if(Gate::denies('show-object', $bay), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->load('site', 'building', 'physicalServers', 'storageDevices', 'peripherals', 'physicalSwitches', 'physicalRouters', 'physicalSecurityDevices');

        return view('admin.bays.show', compact('bay'));
    }

    /**
     * Delete the given Bay and redirect to the bays index.
     *
     * @param  Bay  $bay  The Bay model to delete.
     * @return RedirectResponse Redirects to the admin.bays.index route.
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
        Bay::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Bay::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
