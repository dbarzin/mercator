<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFluxRequest;
use App\Http\Requests\StoreFluxRequest;
use App\Http\Requests\UpdateFluxRequest;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Database;
use App\Models\Flux;
use App\Models\MApplication;
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

        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $modules = ApplicationModule::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');

        // List
        $nature_list = Flux::select('nature')->where('nature', '<>', null)->distinct()->orderBy('nature')->pluck('nature');
        $attributes_list = $this->getAttributes();

        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put('APP_'.$key, $value);
        }
        foreach ($services as $key => $value) {
            $items->put('SRV_'.$key, $value);
        }
        foreach ($modules as $key => $value) {
            $items->put('MOD_'.$key, $value);
        }
        foreach ($databases as $key => $value) {
            $items->put('DB_'.$key, $value);
        }

        return view(
            'admin.fluxes.create',
            compact('items', 'nature_list', 'attributes_list')
        );
    }

    public function store(StoreFluxRequest $request)
    {
        $flux = new Flux();
        $flux->name = $request->name;
        $flux->nature = $request->nature;
        $flux->description = $request->description;
        $flux->attributes = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Source item
        if (str_starts_with($request->src_id, 'APP_')) {
            $flux->application_source_id = intval(substr($request->src_id, 4));
        } else {
            $flux->application_source_id = null;
        }

        if (str_starts_with($request->src_id, 'SRV_')) {
            $flux->service_source_id = intval(substr($request->src_id, 4));
        } else {
            $flux->service_source_id = null;
        }

        if (str_starts_with($request->src_id, 'MOD_')) {
            $flux->module_source_id = intval(substr($request->src_id, 4));
        } else {
            $flux->module_source_id = null;
        }

        if (str_starts_with($request->src_id, 'DB_')) {
            $flux->database_source_id = intval(substr($request->src_id, 3));
        } else {
            $flux->database_source_id = null;
        }

        // Dest item
        if (str_starts_with($request->dest_id, 'APP_')) {
            $flux->application_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $flux->application_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'SRV_')) {
            $flux->service_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $flux->service_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'MOD_')) {
            $flux->module_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $flux->module_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'DB_')) {
            $flux->database_dest_id = intval(substr($request->dest_id, 3));
        } else {
            $flux->database_dest_id = null;
        }

        $flux->crypted = $request->has('crypted');
        $flux->bidirectional = $request->has('bidirectional');
        $flux->save();

        return redirect()->route('admin.fluxes.index');
    }

    public function edit(Flux $flux)
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::query()->orderBy('name')->pluck('name', 'id');
        $services = ApplicationService::query()->orderBy('name')->pluck('name', 'id');
        $modules = ApplicationModule::query()->orderBy('name')->pluck('name', 'id');
        $databases = Database::query()->orderBy('name')->pluck('name', 'id');

        // List
        $nature_list = Flux::select('nature')->where('nature', '<>', null)->distinct()->orderBy('nature')->pluck('nature');
        $attributes_list = $this->getAttributes();

        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put('APP_'.$key, $value);
        }
        foreach ($services as $key => $value) {
            $items->put('SRV_'.$key, $value);
        }
        foreach ($modules as $key => $value) {
            $items->put('MOD_'.$key, $value);
        }
        foreach ($databases as $key => $value) {
            $items->put('DB_'.$key, $value);
        }

        /*
        // Source item
        if ($flux->application_source_id !== null) {
            $flux->src_id = 'APP_'.$flux->application_source_id;
        } elseif ($flux->service_source_id !== null) {
            $flux->src_id = 'SRV_'.$flux->service_source_id;
        } elseif ($flux->module_source_id !== null) {
            $flux->src_id = 'MOD_'.$flux->module_source_id;
        } elseif ($flux->database_source_id !== null) {
            $flux->src_id = 'DB_'.$flux->database_source_id;
        }

        // Dest item
        if ($flux->application_dest_id !== null) {
            $flux->dest_id = 'APP_'.$flux->application_dest_id;
        } elseif ($flux->service_dest_id !== null) {
            $flux->dest_id = 'SRV_'.$flux->service_dest_id;
        } elseif ($flux->module_dest_id !== null) {
            $flux->dest_id = 'MOD_'.$flux->module_dest_id;
        } elseif ($flux->database_dest_id !== null) {
            $flux->dest_id = 'DB_'.$flux->database_dest_id;
        }
        */
        return view(
            'admin.fluxes.edit',
            compact('items', 'nature_list', 'attributes_list', 'flux')
        );
    }

    public function update(UpdateFluxRequest $request, Flux $flux)
    {
        $flux->name = $request->get('name');
        $flux->nature = $request->nature;
        $flux->description = $request->get('description');
        $flux->attributes = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Source item
        if (str_starts_with($request->src_id, 'APP_')) {
            $flux->application_source_id = intval(substr($request->src_id, 4));
        } else {
            $flux->application_source_id = null;
        }

        if (str_starts_with($request->src_id, 'SRV_')) {
            $flux->service_source_id = intval(substr($request->src_id, 4));
        } else {
            $flux->service_source_id = null;
        }

        if (str_starts_with($request->src_id, 'MOD_')) {
            $flux->module_source_id = intval(substr($request->src_id, 4));
        } else {
            $flux->module_source_id = null;
        }

        if (str_starts_with($request->src_id, 'DB_')) {
            $flux->database_source_id = intval(substr($request->src_id, 3));
        } else {
            $flux->database_source_id = null;
        }

        // Dest item
        if (str_starts_with($request->dest_id, 'APP_')) {
            $flux->application_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $flux->application_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'SRV_')) {
            $flux->service_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $flux->service_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'MOD_')) {
            $flux->module_dest_id = intval(substr($request->dest_id, 4));
        } else {
            $flux->module_dest_id = null;
        }

        if (str_starts_with($request->dest_id, 'DB_')) {
            $flux->database_dest_id = intval(substr($request->dest_id, 3));
        } else {
            $flux->database_dest_id = null;
        }

        $flux->crypted = $request->has('crypted');
        $flux->bidirectional = $request->has('bidirectional');
        $flux->update();

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
        $attributes_list = Flux::select('attributes')
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
