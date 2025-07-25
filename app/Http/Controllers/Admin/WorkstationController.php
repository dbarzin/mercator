<?php

namespace App\Http\Controllers\Admin;

use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWorkstationRequest;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;
use App\MApplication;
use App\Workstation;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkstationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('workstation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstations = Workstation::with('site', 'building')->orderBy('name')->get();

        return view('admin.workstations.index', compact('workstations'));
    }

    public function create()
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = DB::table('sites')->select('id', 'name')->orderBy('name')->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = DB::table('buildings')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $entities = DB::table('entities')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $domains = DB::table('domaine_ads')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = DB::table('admin_users')->select('id', 'user_id')->orderBy('user_id')->pluck('user_id', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = DB::table('networks')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Select icons
        $icons = Workstation::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $status_list = Workstation::select('status')
            ->where('status', '<>', null)
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Configuration
        $manufacturer_list = Workstation::select('manufacturer')
            ->where('manufacturer', '<>', null)
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
        $model_list = Workstation::select('model')
            ->where('model', '<>', null)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        $operating_system_list = Workstation::select('operating_system')
            ->where('operating_system', '<>', null)
            ->distinct()
            ->orderBy('operating_system')
            ->pluck('operating_system');
        $cpu_list = Workstation::select('cpu')
            ->where('cpu', '<>', null)
            ->distinct()
            ->orderBy('cpu')
            ->pluck('cpu');

        $network_port_type_list = Workstation::select('network_port_type')
            ->where('network_port_type', '<>', null)
            ->distinct()
            ->orderBy('network_port_type')
            ->pluck('network_port_type');

        return view(
            'admin.workstations.create',
            compact(
                'sites',
                'buildings',
                'icons',
                'type_list',
                'status_list',
                'manufacturer_list',
                'model_list',
                'operating_system_list',
                'cpu_list',
                'application_list',
                'entities',
                'domains',
                'users',
                'networks',
                'network_port_type_list'
            )
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('workstation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = DB::table('sites')->select('id', 'name')->orderBy('name')->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = DB::table('buildings')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $entities = DB::table('entities')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $domains = DB::table('domaine_ads')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = DB::table('admin_users')->select('id', 'user_id')->orderBy('user_id')->pluck('user_id', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = DB::table('networks')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Get icons
        $icons = Workstation::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $status_list = Workstation::select('status')
            ->where('status', '<>', null)
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Configuration
        $manufacturer_list = Workstation::select('manufacturer')
            ->where('manufacturer', '<>', null)
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
        $model_list = Workstation::select('model')
            ->where('model', '<>', null)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        $operating_system_list = Workstation::select('operating_system')
            ->where('operating_system', '<>', null)
            ->distinct()
            ->orderBy('operating_system')
            ->pluck('operating_system');
        $cpu_list = Workstation::select('cpu')
            ->where('cpu', '<>', null)
            ->distinct()
            ->orderBy('cpu')
            ->pluck('cpu');

        $network_port_type_list = Workstation::select('network_port_type')
            ->where('network_port_type', '<>', null)
            ->distinct()
            ->orderBy('network_port_type')
            ->pluck('network_port_type');

        // Get Workstation
        $workstation = Workstation::find($request->id);

        // Workstation not found
        abort_if($workstation === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $request->merge($workstation->only($workstation->getFillable()));
        $request->flash();

        return view(
            'admin.workstations.create',
            compact(
                'sites',
                'buildings',
                'icons',
                'type_list',
                'status_list',
                'manufacturer_list',
                'model_list',
                'operating_system_list',
                'cpu_list',
                'application_list',
                'entities',
                'domains',
                'users',
                'networks',
                'network_port_type_list'
            )
        );
    }

    public function store(StoreWorkstationRequest $request)
    {
        $workstation = Workstation::create($request->all());

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $workstation->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $workstation->icon_id = intval($request->iconSelect);
        } else {
            $workstation->icon_id = null;
        }
        $workstation->save();

        // Sync applications
        $workstation->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.workstations.index');
    }

    public function edit(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = DB::table('sites')->select('id', 'name')->orderBy('name')->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = DB::table('buildings')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $entities = DB::table('entities')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $domains = DB::table('domaine_ads')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $users = DB::table('admin_users')->select('id', 'user_id')->orderBy('user_id')->pluck('user_id', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = DB::table('networks')->select('id', 'name')->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // Get icons
        $icons = Workstation::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $application_list = MApplication::orderBy('name')->pluck('name', 'id');

        $type_list = Workstation::select('type')
            ->where('type', '<>', null)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
        $status_list = Workstation::select('status')
            ->where('status', '<>', null)
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Configuration
        $manufacturer_list = Workstation::select('manufacturer')
            ->where('manufacturer', '<>', null)
            ->distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');
        $model_list = Workstation::select('model')
            ->where('model', '<>', null)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        $operating_system_list = Workstation::select('operating_system')
            ->where('operating_system', '<>', null)
            ->distinct()
            ->orderBy('operating_system')
            ->pluck('operating_system');
        $cpu_list = Workstation::select('cpu')
            ->where('cpu', '<>', null)
            ->distinct()
            ->orderBy('cpu')
            ->pluck('cpu');

        $network_port_type_list = Workstation::select('network_port_type')
            ->where('network_port_type', '<>', null)
            ->distinct()
            ->orderBy('network_port_type')
            ->pluck('network_port_type');

        $workstation->load('site', 'building');

        return view(
            'admin.workstations.edit',
            compact(
                'workstation',
                'sites',
                'buildings',
                'icons',
                'type_list',
                'status_list',
                'manufacturer_list',
                'model_list',
                'operating_system_list',
                'cpu_list',
                'application_list',
                'entities',
                'domains',
                'users',
                'networks',
                'network_port_type_list'
            )
        );
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $workstation->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $workstation->icon_id = intval($request->iconSelect);
        } else {
            $workstation->icon_id = null;
        }

        $workstation->update($request->all());
        $workstation->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.workstations.index');
    }

    public function show(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->load('site', 'building');

        return view('admin.workstations.show', compact('workstation'));
    }

    public function destroy(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->delete();

        return redirect()->route('admin.workstations.index');
    }

    public function massDestroy(MassDestroyWorkstationRequest $request)
    {
        Workstation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
