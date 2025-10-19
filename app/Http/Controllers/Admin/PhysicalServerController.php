<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalServerRequest;
use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Cluster;
use App\Models\Document;
use App\Models\LogicalServer;
use App\Models\MApplication;
use App\Models\PhysicalServer;
use App\Models\Site;
use Gate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Laravel

class PhysicalServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServers = PhysicalServer::with('site', 'building', 'bay')->orderBy('name')->get();

        return view('admin.physicalServers.index', compact('physicalServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $icons = PhysicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // List
        $application_list = MApplication::orderBy('name')->pluck('name', 'id');
        $operating_system_list = PhysicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $logical_server_list = LogicalServer::orderBy('name')->pluck('name', 'id');

        return view(
            'admin.physicalServers.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'clusters',
                'icons',
                'application_list',
                'operating_system_list',
                'responsible_list',
                'type_list',
                'logical_server_list'
            )
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $icons = PhysicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // List
        $application_list = MApplication::orderBy('name')->pluck('name', 'id');
        $operating_system_list = PhysicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $logical_server_list = LogicalServer::orderBy('name')->pluck('name', 'id');

        // Get PhysicalServer
        $physicalServer = PhysicalServer::find($request->id);

        // PhysicalServer not found
        abort_if($physicalServer === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $request->merge($physicalServer->only($physicalServer->getFillable()));
        $request->merge(['applications' => $physicalServer->applications()->pluck('id')->unique()->toArray()]);
        $request->merge(['logicalServers' => $physicalServer->logicalServers()->pluck('id')->unique()->toArray()]);
        $request->merge(['clusters' => $physicalServer->clusters()->pluck('id')->unique()->toArray()]);
        $request->flash();

        return view(
            'admin.physicalServers.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'icons',
                'clusters',
                'application_list',
                'operating_system_list',
                'responsible_list',
                'type_list',
                'logical_server_list'
            )
        );
    }

    public function store(StorePhysicalServerRequest $request)
    {
        $physicalServer = PhysicalServer::create($request->all());

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

            $physicalServer->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $physicalServer->icon_id = intval($request->iconSelect);
        } else {
            $physicalServer->icon_id = null;
        }

        // Save LogicalServer
        $physicalServer->save();

        // Seave Relations
        $physicalServer->applications()->sync($request->input('applications', []));
        $physicalServer->logicalServers()->sync($request->input('logicalServers', []));
        $physicalServer->clusters()->sync($request->input('clusters', []));

        return redirect()->route('admin.physical-servers.index');
    }

    public function edit(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $clusters = Cluster::all()->sortBy('name')->pluck('name', 'id');
        $icons = PhysicalServer::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // List
        $operating_system_list = PhysicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $application_list = MApplication::orderBy('name')->pluck('name', 'id');
        $logical_server_list = LogicalServer::orderBy('name')->pluck('name', 'id');

        $physicalServer->load('site', 'building', 'bay');

        return view(
            'admin.physicalServers.edit',
            compact(
                'sites',
                'buildings',
                'bays',
                'clusters',
                'icons',
                'application_list',
                'logical_server_list',
                'responsible_list',
                'operating_system_list',
                'type_list',
                'physicalServer'
            )
        );
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalServer)
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

            $physicalServer->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $physicalServer->icon_id = intval($request->iconSelect);
        } else {
            $physicalServer->icon_id = null;
        }

        // Other fields
        $physicalServer->update($request->all());

        // Relations
        $physicalServer->applications()->sync($request->input('applications', []));
        $physicalServer->logicalServers()->sync($request->input('logicalServers', []));
        $physicalServer->clusters()->sync($request->input('clusters', []));

        return redirect()->route('admin.physical-servers.index');
    }

    public function show(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->load('site', 'building', 'bay', 'logicalServers');

        return view('admin.physicalServers.show', compact('physicalServer'));
    }

    public function destroy(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->delete();

        return redirect()->route('admin.physical-servers.index');
    }

    public function massDestroy(MassDestroyPhysicalServerRequest $request)
    {
        PhysicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
