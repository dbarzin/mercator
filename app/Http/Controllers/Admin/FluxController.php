<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationModule;
use App\ApplicationService;
use App\Database;
use App\Flux;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFluxRequest;
use App\Http\Requests\StoreFluxRequest;
use App\Http\Requests\UpdateFluxRequest;
use App\MApplication;
use App\Services\CartographerService;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class FluxController extends Controller
{
    protected CartographerService $cartographerService;

    /**
     * Automatic Injection for Service
     *
     * @return void
     */
    public function __construct(CartographerService $cartographerService)
    {
        $this->cartographerService = $cartographerService;
    }

    public function index()
    {
        abort_if(Gate::denies('flux_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fluxes = Flux::all()->sortBy('name');

        return view('admin.fluxes.index', compact('fluxes'));
    }

    public function create()
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application_sources = MApplication::with('cartographers')->get();
        // Filtre sur les cartographes si nécessaire
        $application_sources = $this->cartographerService->filterOnCartographers($application_sources)->prepend(trans('global.pleaseSelect'), '');
        // Pour les flux, on restreint seulement la source !
        $application_dests = MApplication::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $service_sources = ApplicationService::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $module_sources = ApplicationModule::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $database_sources = Database::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $service_dests = ApplicationService::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $module_dests = ApplicationModule::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $database_dests = Database::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.fluxes.create', compact('application_sources', 'service_sources', 'module_sources', 'database_sources', 'application_dests', 'service_dests', 'module_dests', 'database_dests'));
    }

    public function store(StoreFluxRequest $request)
    {
        Flux::create($request->all());

        return redirect()->route('admin.fluxes.index');
    }

    public function edit(Flux $flux)
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application_sources = MApplication::with('cartographers')->get();
        // Filtre sur les cartographes si nécessaire
        $application_sources = $this->cartographerService->filterOnCartographers($application_sources)->prepend(trans('global.pleaseSelect'), '');
        // Pour les flux, on restreint seulement la source !
        $application_dests = MApplication::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $service_sources = ApplicationService::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $module_sources = ApplicationModule::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $database_sources = Database::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $service_dests = ApplicationService::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $module_dests = ApplicationModule::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $database_dests = Database::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $flux->load('application_source', 'service_source', 'module_source', 'database_source', 'application_dest', 'service_dest', 'module_dest', 'database_dest');

        return view('admin.fluxes.edit', compact('application_sources', 'service_sources', 'module_sources', 'database_sources', 'application_dests', 'service_dests', 'module_dests', 'database_dests', 'flux'));
    }

    public function update(UpdateFluxRequest $request, Flux $flux)
    {
        $flux->crypted = $request->has('crypted');
        $flux->bidirectional = $request->has('bidirectional');
        $flux->update($request->all());

        return redirect()->route('admin.fluxes.index');
    }

    public function show(Flux $flux)
    {
        abort_if(Gate::denies('flux_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->load('application_source', 'service_source', 'module_source', 'database_source', 'application_dest', 'service_dest', 'module_dest', 'database_dest');

        return view('admin.fluxes.show', compact('flux'));
    }

    public function destroy(Flux $flux)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->delete();

        return redirect()->route('admin.fluxes.index');
    }

    public function massDestroy(MassDestroyFluxRequest $request)
    {
        Flux::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
