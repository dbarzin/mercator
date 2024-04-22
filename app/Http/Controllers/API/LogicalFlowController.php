<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalFlowRequest;
use App\Http\Requests\StoreLogicalFlowRequest;
use App\Http\Requests\UpdateLogicalFlowRequest;
use App\Http\Resources\Admin\logicalFlowResource;
use App\LogicalFlow;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogicalFlowController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_flow_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlows = logicalFlow::all();

        return response()->json($logicalFlows);
    }

    public function store(StoreLogicalFlowRequest $request)
    {
        Log::Debug('LogicalFlowController:store Start');

        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlow = logicalFlow::create($request->all());
        #$logicalFlow->servers()->sync($request->input('servers', []));
        #$logicalFlow->applications()->sync($request->input('applications', []));

        Log::Debug('LogicalFlowController:store Done');

        return response()->json($logicalFlow, 201);
    }

    public function show(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new logicalFlowResource($logicalFlow);
    }

    public function update(UpdateLogicalFlowRequest $request, logicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlow->update($request->all());

        return response()->json();
    }

    public function destroy(LogicalFlow $logicalFlow)
    {
        abort_if(Gate::denies('logical_flow_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalFlow->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyLogicalFlowRequest $request)
    {
        abort_if(Gate::denies('logical_flow_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        logicalFlow::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
