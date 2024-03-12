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
        abort_if(Gate::denies('data_processing_register_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataProcessings = DataProcessing::all();

        return response()->json($dataProcessings);
    }

    public function store(StoreDataProcessingRequest $request)
    {
        abort_if(Gate::denies('data_processing_register_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $DataProcessing = DataProcessing::create($request->all());
        // syncs
        // $DataProcessing->roles()->sync($request->input('roles', []));

        return response()->json($DataProcessing, 201);
    }

    public function show(DataProcessing $DataProcessing)
    {
        abort_if(Gate::denies('data_processing_register_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DataProcessingResource($DataProcessing);
    }

    public function update(UpdateDataProcessingRequest $request, DataProcessing $DataProcessing)
    {
        abort_if(Gate::denies('data_processing_register_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $DataProcessing->update($request->all());
        // syncs
        // $DataProcessing->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(DataProcessing $DataProcessing)
    {
        abort_if(Gate::denies('data_processing_register_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $DataProcessing->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDataProcessingRequest $request)
    {
        abort_if(Gate::denies('data_processing_register_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DataProcessing::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
