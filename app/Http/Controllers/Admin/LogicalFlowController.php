<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalFlowRequest;
use App\Http\Requests\StoreLogicalFlowRequest;
use App\Http\Requests\UpdateLogicalFlowRequest;
use App\LogicalFlow;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class LogicalFlowController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_flow_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlows = LogicalFlow::orderBy('name')->get();

        return view('admin.logicalFlows.index', compact('logicalFlows'));
    }

    public function create()
    {
        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.logicalFlows.create');
    }

    public function store(StoreLogicalFlowRequest $request)
    {
        $logicalFlow = LogicalFlow::create($request->all());

        return redirect()->route('admin.logical-flows.index');
    }

    public function edit(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.logicalFlows.edit', compact('logicalFlow'));
    }

    public function update(UpdateLogicalFlowRequest $request, LogicalFlow $logicalFlow)
    {
        $logicalFlow->update($request->all());

        return redirect()->route('admin.logical-flows.index');
    }

    public function show(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.logicalFlows.show', compact('logicalFlow'));
    }

    public function destroy(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlow->delete();

        return redirect()->route('admin.logical-flows.index');
    }

    public function massDestroy(MassDestroyLogicalFlowRequest $request)
    {
        LogicalFlow::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
