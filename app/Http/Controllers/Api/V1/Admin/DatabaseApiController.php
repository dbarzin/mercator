<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Database;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;
use App\Http\Resources\Admin\DatabaseResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DatabaseApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DatabaseResource(Database::with(['entities', 'entity_resp', 'informations'])->get());
    }

    public function store(StoreDatabaseRequest $request)
    {
        $database = Database::create($request->all());
        $database->entities()->sync($request->input('entities', []));
        $database->informations()->sync($request->input('informations', []));

        return (new DatabaseResource($database))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Database $database)
    {
        abort_if(Gate::denies('database_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DatabaseResource($database->load(['entities', 'entity_resp', 'informations']));
    }

    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        $database->update($request->all());
        $database->entities()->sync($request->input('entities', []));
        $database->informations()->sync($request->input('informations', []));

        return (new DatabaseResource($database))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Database $database)
    {
        abort_if(Gate::denies('database_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
