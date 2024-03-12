<?php

namespace App\Http\Controllers\API;

use App\Database;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDatabaseRequest;
use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;
use App\Http\Resources\Admin\DatabaseResource;
use Gate;
use Illuminate\Http\Response;

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

        $database = Database::create($request->all());
        // syncs
        // $database->roles()->sync($request->input('roles', []));

        return response()->json($database, 201);
    }

    public function show(Database $database)
    {
        abort_if(Gate::denies('database_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DatabaseResource($database);
    }

    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        abort_if(Gate::denies('database_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->update($request->all());
        // syncs
        // $database->roles()->sync($request->input('roles', []));

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

        Database::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
