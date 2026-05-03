<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFluxRequest;
use App\Http\Requests\StoreFluxRequest;
use App\Http\Requests\UpdateFluxRequest;
use App\Models\Application;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Database;
use App\Models\Flux;
use App\Models\Information;
use Gate;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class FluxController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('flux_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fluxes = Flux::all()->sortBy('name');

        return view('admin.fluxes.index', compact('fluxes'));
    }

    public function create()
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');

        // List
        $nature_list = Flux::select('nature')->where('nature', '<>', null)->distinct()->orderBy('nature')->pluck('nature');
        $attributes_list = $this->getAttributes();

        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put(Application::$prefix . $key, $value . ' [Application]');
        }
        foreach ($services as $key => $value) {
            $items->put(ApplicationService::$prefix . $key, $value . ' [Service]');
        }
        foreach ($modules as $key => $value) {
            $items->put(ApplicationModule::$prefix . $key, $value . ' [Module]');
        }
        foreach ($databases as $key => $value) {
            $items->put(Database::$prefix . $key, $value . ' [Database]');
        }

        return view(
            'admin.fluxes.create',
            compact('items', 'nature_list', 'informations', 'attributes_list')
        );
    }

    public function store(StoreFluxRequest $request)
    {
        $flux = new Flux;
        $flux->name = $request->name;
        $flux->nature = $request->nature;
        $flux->description = $request->description;
        $flux->attributes = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Source item
        if (str_starts_with($request->src_id, Application::$prefix)) {
            $flux->application_source_id = intval(substr($request->src_id, strlen(Application::$prefix)));
        } else {
            $flux->application_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationService::$prefix)) {
            $flux->service_source_id = intval(substr($request->src_id, strlen(ApplicationService::$prefix)));
        } else {
            $flux->service_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationModule::$prefix)) {
            $flux->module_source_id = intval(substr($request->src_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flux->module_source_id = null;
        }

        if (str_starts_with($request->src_id, Database::$prefix)) {
            $flux->database_source_id = intval(substr($request->src_id, strlen(Database::$prefix)));
        } else {
            $flux->database_source_id = null;
        }

        // Dest item
        if (str_starts_with($request->dest_id, Application::$prefix)) {
            $flux->application_dest_id = intval(substr($request->dest_id, strlen(Application::$prefix)));
        } else {
            $flux->application_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationService::$prefix)) {
            $flux->service_dest_id = intval(substr($request->dest_id, strlen(ApplicationService::$prefix)));
        } else {
            $flux->service_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationModule::$prefix)) {
            $flux->module_dest_id = intval(substr($request->dest_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flux->module_dest_id = null;
        }

        if (str_starts_with($request->dest_id, Database::$prefix)) {
            $flux->database_dest_id = intval(substr($request->dest_id, strlen(Database::$prefix)));
        } else {
            $flux->database_dest_id = null;
        }

        $flux->crypted = $request->has('crypted');
        $flux->bidirectional = $request->has('bidirectional');
        $flux->save();

        $flux->informations()->sync($request->get('informations'));

        return redirect()->route('admin.fluxes.index');
    }

    public function edit(Flux $flux)
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::query()->orderBy('name')->pluck('name', 'id');
        $services = ApplicationService::query()->orderBy('name')->pluck('name', 'id');
        $modules = ApplicationModule::query()->orderBy('name')->pluck('name', 'id');
        $databases = Database::query()->orderBy('name')->pluck('name', 'id');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');

        // List
        $nature_list = Flux::select('nature')->where('nature', '<>', null)->distinct()->orderBy('nature')->pluck('nature');
        $attributes_list = $this->getAttributes();

        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put( Application::$prefix . $key, $value . ' [Application]');
        }
        foreach ($services as $key => $value) {
            $items->put(ApplicationService::$prefix . $key, $value . ' [Service]');
        }
        foreach ($modules as $key => $value) {
            $items->put(ApplicationModule::$prefix . $key, $value . ' [Module]');
        }
        foreach ($databases as $key => $value) {
            $items->put(Database::$prefix . $key, $value . ' [Database]');
        }

        return view(
            'admin.fluxes.edit',
            compact('items', 'nature_list', 'informations', 'attributes_list', 'flux')
        );
    }

    public function update(UpdateFluxRequest $request, Flux $flux)
    {
        $flux->name = $request->get('name');
        $flux->nature = $request->nature;
        $flux->description = $request->get('description');
        $flux->attributes = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Source item
        if (str_starts_with($request->src_id, Application::$prefix)) {
            $flux->application_source_id = intval(substr($request->src_id, strlen(Application::$prefix)));
        } else {
            $flux->application_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationService::$prefix)) {
            $flux->service_source_id = intval(substr($request->src_id, strlen(ApplicationService::$prefix)));
        } else {
            $flux->service_source_id = null;
        }

        if (str_starts_with($request->src_id, ApplicationModule::$prefix)) {
            $flux->module_source_id = intval(substr($request->src_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flux->module_source_id = null;
        }

        if (str_starts_with($request->src_id, Database::$prefix)) {
            $flux->database_source_id = intval(substr($request->src_id, strlen(Database::$prefix)));
        } else {
            $flux->database_source_id = null;
        }

        // Dest item
        if (str_starts_with($request->dest_id, Application::$prefix)) {
            $flux->application_dest_id = intval(substr($request->dest_id, strlen(Application::$prefix)));
        } else {
            $flux->application_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationService::$prefix)) {
            $flux->service_dest_id = intval(substr($request->dest_id, strlen(ApplicationService::$prefix)));
        } else {
            $flux->service_dest_id = null;
        }

        if (str_starts_with($request->dest_id, ApplicationModule::$prefix)) {
            $flux->module_dest_id = intval(substr($request->dest_id, strlen(ApplicationModule::$prefix)));
        } else {
            $flux->module_dest_id = null;
        }

        if (str_starts_with($request->dest_id, Database::$prefix)) {
            $flux->database_dest_id = intval(substr($request->dest_id, strlen(Database::$prefix)));
        } else {
            $flux->database_dest_id = null;
        }
        
        $flux->crypted = $request->has('crypted');
        $flux->bidirectional = $request->has('bidirectional');
        $flux->update();

        $flux->informations()->sync($request->get('informations'));

        return redirect()->route('admin.fluxes.index');
    }

    public function show(Flux $flux)
    {
        abort_if(Gate::denies('flux_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->load(
            'application_source',
            'service_source',
            'module_source',
            'database_source',
            'application_dest',
            'service_dest',
            'module_dest',
            'database_dest'
        );

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

    private function getAttributes()
    {
        $attributes_list = Flux::query()->select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
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
