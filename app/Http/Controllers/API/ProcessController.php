<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProcessRequest;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Http\Resources\Admin\ProcessResource;
use App\Operation;
use App\Process;
use Gate;
use Illuminate\Http\Response;

class ProcessController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('process_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processs = Process::all();

        return response()->json($processs);
    }

    public function store(StoreProcessRequest $request)
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process = Process::create($request->all());
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->information()->sync($request->input('informations', []));
        $process->applications()->sync($request->input('applications', []));
        Operation::whereIn('id', $request->input('operations', []))
            ->update(['process_id' => $process->id]);

        return response()->json($process, 201);
    }

    public function show(Process $process)
    {
        abort_if(Gate::denies('process_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProcessResource($process);
    }

    public function update(UpdateProcessRequest $request, Process $process)
    {
        abort_if(Gate::denies('process_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->update($request->all());
        if ($request->has('activities')) {
            $process->activities()->sync($request->input('activities', []));
        }
        if ($request->has('entities')) {
            $process->entities()->sync($request->input('entities', []));
        }
        if ($request->has('informations')) {
            $process->information()->sync($request->input('informations', []));
        }
        if ($request->has('applications')) {
            $process->applications()->sync($request->input('applications', []));
        }
        if ($request->has('operations')) {
            Operation::whereIn('id', $request->input('operations', []))
                ->update(['process_id' => $process->id]);
        }

        return response()->json();
    }

    public function destroy(Process $process)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyProcessRequest $request)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Process::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
