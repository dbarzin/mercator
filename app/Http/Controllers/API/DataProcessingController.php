<?php

namespace App\Http\Controllers\API;

use App\DataProcessing;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDataProcessingRequest;
use App\Http\Requests\StoreDataProcessingRequest;
use App\Http\Requests\UpdateDataProcessingRequest;
use App\Http\Resources\Admin\DataProcessingResource;
use Gate;
use Illuminate\Http\Response;

class DataProcessingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('data_processing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessings = DataProcessing::all();

        return response()->json($dataProcessings);
    }

    public function store(StoreDataProcessingRequest $request)
    {
        abort_if(Gate::denies('data_processing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing = DataProcessing::create($request->all());
        $dataProcessing->processes()->sync($request->input('processes', []));
        $dataProcessing->informations()->sync($request->input('informations', []));
        $dataProcessing->applications()->sync($request->input('applications', []));

        return response()->json($dataProcessing, 201);
    }

    public function show(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DataProcessingResource($dataProcessing);
    }

    public function update(UpdateDataProcessingRequest $request, DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing->update($request->all());
        $dataProcessing->processes()->sync($request->input('processes', []));
        $dataProcessing->informations()->sync($request->input('informations', []));
        $dataProcessing->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(DataProcessing $dataProcessing)
    {
        abort_if(Gate::denies('data_processing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessing->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDataProcessingRequest $request)
    {
        abort_if(Gate::denies('data_processing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DataProcessing::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
