<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClusterRequest;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;
use App\Models\Cluster;
use App\Models\Document;
use App\Models\LogicalServer;
use App\Models\PhysicalServer;
use App\Models\Router;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ClusterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cluster_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clusters = Cluster::query()->orderBy('name')->get();

        return view('admin.clusters.index', compact('clusters'));
    }

    public function create()
    {
        abort_if(Gate::denies('cluster_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $physical_servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');

        // List
        $type_list = Cluster::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Select icons
        $icons = Cluster::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view(
            'admin.clusters.create',
            compact('logical_servers', 'physical_servers', 'type_list', 'attributes_list', 'routers', 'icons')
        );
    }

    public function store(StoreClusterRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $cluster = Cluster::create($request->all());

        $cluster->logicalServers()->sync($request->input('logical_servers', []));
        $cluster->physicalServers()->sync($request->input('physical_servers', []));
        $cluster->routers()->sync($request->input('routers', []));

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

            $cluster->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $cluster->icon_id = intval($request->iconSelect);
        } else {
            $cluster->icon_id = null;
        }
        $cluster->save();

        return redirect()->route('admin.clusters.index');
    }

    public function edit(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $physical_servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');

        // List
        $type_list = Cluster::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Select icons
        $icons = Cluster::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view(
            'admin.clusters.edit',
            compact('cluster', 'logical_servers', 'physical_servers', 'type_list', 'attributes_list', 'routers','icons')
        );
    }

    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

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

            $cluster->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $cluster->icon_id = intval($request->iconSelect);
        } else {
            $cluster->icon_id = null;
        }

        $cluster->update($request->all());

        $cluster->logicalServers()->sync($request->input('logical_servers', []));
        $cluster->physicalServers()->sync($request->input('physical_servers', []));
        $cluster->routers()->sync($request->input('routers', []));

        return redirect()->route('admin.clusters.index');
    }

    public function show(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clusters.show', compact('cluster'));
    }

    public function destroy(Cluster $cluster)
    {
        abort_if(Gate::denies('cluster_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->delete();

        return redirect()->route('admin.clusters.index');
    }

    public function massDestroy(MassDestroyClusterRequest $request)
    {
        Cluster::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Cluster::query()
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
