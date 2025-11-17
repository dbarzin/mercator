<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGraphRequest;
use App\Models\Graph;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BPMNController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('graph_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $graphs = Graph::orderBy('name')
            ->where('class','=', 2)
            ->get();

        return view('admin.graphs.index', compact('graphs'));
    }

    public function create()
    {
        abort_if(Gate::denies('graph_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        abort_if($graph->class !== 2, Response::HTTP_NOT_ACCEPTABLE, '406 Not BPMN');

        // get nodes and edges from the explorer
        [$nodes, $edges] = app('App\Http\Controllers\Admin\ExplorerController')->getData();

        // Get types
        $type_list = Graph::select('type')
            ->whereNotNull('type')
            ->where('class','=', 2)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

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

        Graph::create($request->all());

        return redirect()->route('admin.graphs.index');
    }

    public function edit(Graph $graph)
    {
        abort_if(Gate::denies('graph_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if($graph->class !== 2, Response::HTTP_NOT_ACCEPTABLE, '406 Not BPMN');

        // Get types
        $type_list = Graph::select('type')
            ->whereNotNull('type')
            ->where('class','=', 2)
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

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

    public function update(Request $request)
    {
        abort_if(Gate::denies('graph_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->id == '-1') {
            $graph = Graph::create($request->all());
        } else {
            $graph = Graph::find($request->id);
            $graph->update($request->all());
        }
        $graph->class=2;
        $graph->save();

        return redirect()->route('admin.bpmn.index');
    }

    public function show(Graph $graph)
    {
        abort_if(Gate::denies('graph_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if($graph->class !== 2, Response::HTTP_NOT_ACCEPTABLE, '406 Not a graph');

        return view('admin.bpmn.show', compact('graph', 'nodes'));
    }

    public function destroy(Graph $graph)
    {
        abort_if(Gate::denies('graph_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $graph->delete();

        return redirect()->route('admin.bpmn.index');
    }

    public function massDestroy(MassDestroyGraphRequest $request)
    {
        Graph::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
