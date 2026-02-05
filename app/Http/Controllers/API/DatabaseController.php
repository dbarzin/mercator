<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDatabaseRequest;
use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Database;
use Symfony\Component\HttpFoundation\Response;

class DatabaseController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $databases = Database::all();

        return response()->json($databases);
    }

    public function store(StoreDatabaseRequest $request)
    {
        abort_if(Gate::denies('database_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database = Database::query()->create($request->all());
        
        $database->entities()->sync($request->input('entities', []));
        $database->informations()->sync($request->input('informations', []));
        $database->applications()->sync($request->input('applications', []));
        $database->logicalServers()->sync($request->input('logical_servers', []));

        return response()->json($database, 201);
    }

    public function show(Database $database)
    {
        abort_if(Gate::denies('database_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($database);
    }

    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        abort_if(Gate::denies('database_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->update($request->all());

        if ($request->has('entities'))
            $database->entities()->sync($request->input('entities', []));
        if ($request->has('informations'))
            $database->informations()->sync($request->input('informations', []));
        if ($request->has('applications'))
            $database->applications()->sync($request->input('applications', []));
        if ($request->has('logical_servers'))
            $database->logicalServers()->sync($request->input('logical_servers', []));

        return response()->json();
    }

    public function destroy(Database $database)
    {
        abort_if(Gate::denies('database_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDatabaseRequest $request)
    {
        abort_if(Gate::denies('database_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Database::query()->whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
