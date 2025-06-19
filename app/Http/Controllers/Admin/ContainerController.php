<?php

namespace App\Http\Controllers\Admin;

use App\Container;
use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyContainerRequest;
use App\Http\Requests\StoreContainerRequest;
use App\Http\Requests\UpdateContainerRequest;
use App\LogicalServer;
use App\Database;
use App\MApplication;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ContainerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('container_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $containers = Container::orderBy('name')->get();

        return view('admin.containers.index', compact('containers'));
    }

    public function create()
    {
        abort_if(Gate::denies('container_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get lists
        $icons = Container::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $type_list = Container::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.containers.create', compact('icons', 'type_list', 'logical_servers', 'applications', 'databases'));
    }

    public function store(StoreContainerRequest $request)
    {
        $container = Container::create($request->all());
        $container->applications()->sync($request->input('applications', []));
        $container->logicalServers()->sync($request->input('logical_servers', []));
        $container->databases()->sync($request->input('databases', []));

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

            $container->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $container->icon_id = intval($request->iconSelect);
        } else {
            $container->icon_id = null;
        }
        $container->save();

        return redirect()->route('admin.containers.index');
    }

    public function edit(Container $container)
    {
        abort_if(Gate::denies('container_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $icons = Container::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $type_list = Container::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');

        return view(
            'admin.containers.edit',
            compact('container', 'icons', 'type_list', 'logical_servers', 'applications', 'databases')
        );
    }

    public function update(UpdateContainerRequest $request, Container $container)
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

            $container->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $container->icon_id = intval($request->iconSelect);
        } else {
            $container->icon_id = null;
        }

        $container->update($request->all());
        $container->applications()->sync($request->input('applications', []));
        $container->logicalServers()->sync($request->input('logical_servers', []));
        $container->databases()->sync($request->input('databases', []));

        return redirect()->route('admin.containers.index');
    }

    public function show(Container $container)
    {
        abort_if(Gate::denies('container_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $container->load('applications', 'logicalServers', 'databases');

        return view('admin.containers.show', compact('container'));
    }

    public function destroy(Container $container)
    {
        abort_if(Gate::denies('container_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $container->delete();

        return redirect()->route('admin.containers.index');
    }

    public function massDestroy(MassDestroyContainerRequest $request)
    {
        Container::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
