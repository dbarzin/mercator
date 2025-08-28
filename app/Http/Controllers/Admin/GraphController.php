<?php

namespace App\Http\Controllers\Admin;

use App\Graph;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGraphRequest;
use Gate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GraphController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('graph_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $graphs = Graph::orderBy('name')->get();

        return view('admin.graphs.index', compact('graphs'));
    }

    public function create()
    {
        abort_if(Gate::denies('graph_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Generate a graph name
        // $lastId = Graph::max('id') ?? 0;
        // $name = 'Map#' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // create the graph
        // $graph = Graph::create(['name' => $name, 'type' => null, 'content' => '<GraphDataModel></GraphDataModel>']);

        // get nodes and edges from the explorer
        [$nodes, $edges] = app('App\Http\Controllers\Admin\ExplorerController')->getData();

        // Get types
        $type_list = Graph::select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.graphs.edit',
            compact('type_list', 'nodes', 'edges')
        )
            ->with('id', '-1')
            ->with('type', '')
            ->with('name', '')
            ->with('content', '<GraphDataModel></GraphDataModel>');
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('graph_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get graph
        $graph = Graph::find($request->id);

        // Graph not found
        abort_if($graph === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        // Clone the graph
        // $lastId = Graph::max('id') ?? 0;
        // $name = 'Map#' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // Create graph
        // $graph = Graph::create(['name' => $name, 'type' => $graph->type, 'content' => $graph->content]);

        // get nodes and edges from the explorer
        [$nodes, $edges] = app('App\Http\Controllers\Admin\ExplorerController')->getData();

        // Get types
        $type_list = Graph::select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.graphs.edit',
            compact('type_list', 'nodes', 'edges')
        )
            ->with('id', '-1')
            ->with('name', $graph->name)
            ->with('type', $graph->type)
            ->with('content', $graph->content);
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('graph_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //dd("Not implemented");

        return redirect()->route('admin.graphs.index');
    }

    public function edit(Graph $graph)
    {
        abort_if(Gate::denies('graph_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get types
        $type_list = Graph::select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');

        // get nodes and edges from the explorer
        [$nodes, $edges] = app('App\Http\Controllers\Admin\ExplorerController')->getData();

        // return
        return view(
            'admin.graphs.edit',
            compact('type_list', 'nodes', 'edges')
        )
            ->with('id', $graph->id)
            ->with('name', $graph->name)
            ->with('type', $graph->type)
            ->with('content', $graph->content);
    }

    public function save(Request $request)
    {
        abort_if(Gate::denies('graph_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $graph = Graph::find($request->id);

        // Control not found
        abort_if($graph === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        // set value
        $graph->name = $request->name;
        $graph->type = $request->type;
        $graph->content = $request->content;
        $graph->save();

        return true;
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('graph_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get the graph
        if ($request->id === '-1') {
            $graph = Graph::create($request->all());
        } else {
            $graph = Graph::find($request->id);

            // Graph not found
            abort_if($graph === null, Response::HTTP_NOT_FOUND, '404 Not Found');

            // set value
            $graph->name = $request->name;
            $graph->type = $request->type;
            $graph->content = $request->content;
            $graph->save();
        }

        return redirect()->route('admin.graphs.index');
    }

    public function show(Graph $graph)
    {
        abort_if(Gate::denies('graph_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // get nodes and edges from the explorer
        [$nodes, $edges] = app('App\Http\Controllers\Admin\ExplorerController')->getData();

        return view('admin.graphs.show', compact('graph', 'nodes'));
    }

    public function destroy(Graph $graph)
    {
        abort_if(Gate::denies('graph_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $graph->delete();

        return redirect()->route('admin.graphs.index');
    }

    public function massDestroy(MassDestroyGraphRequest $request)
    {
        Graph::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
