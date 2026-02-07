<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDatabaseRequest;
use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;
use App\Services\IconUploadService;
use Gate;
use Mercator\Core\Models\Container;
use Mercator\Core\Models\Database;
use Mercator\Core\Models\Entity;
use Mercator\Core\Models\Information;
use Mercator\Core\Models\LogicalServer;
use Mercator\Core\Models\MApplication;
use Symfony\Component\HttpFoundation\Response;

class DatabaseController extends Controller
{

    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        abort_if(Gate::denies('database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $databases = Database::all()->sortBy('name');

        return view('admin.databases.index', compact('databases'));
    }

    public function create()
    {
        abort_if(Gate::denies('database_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::query()->orderBy('name')->pluck('name', 'id');
        $entity_resps = Entity::query()->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');
        $applications = MApplication::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');
        $logical_servers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');
        $containers = Container::query()->orderBy('name')->pluck('name', 'id');

        // Select icons
        $icons = Database::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // lists
        $type_list = Database::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $external_list = Database::query()->select('external')->where('external', '<>', null)->distinct()->orderBy('external')->pluck('external');
        $responsible_list = Database::query()->select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');

        return view(
            'admin.databases.create',
            compact(
                'entities',
                'icons',
                'entity_resps',
                'informations',
                'applications',
                'logical_servers',
                'containers',
                'type_list',
                'external_list',
                'responsible_list'
            )
        );
    }

    public function store(StoreDatabaseRequest $request)
    {
        $database = Database::create($request->all());
        $database->entities()->sync($request->input('entities', []));
        $database->informations()->sync($request->input('informations', []));
        $database->applications()->sync($request->input('applications', []));
        $database->logicalServers()->sync($request->input('logical_servers', []));
        $database->containers()->sync($request->input('containers', []));

        // Save icon
        $this->iconUploadService->handle($request, $database);

        // Save Database
        $database->save();

        return redirect()->route('admin.databases.index');
    }

    public function edit(Database $database)
    {
        abort_if(Gate::denies('database_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::query()->orderBy('name')->pluck('name', 'id');
        $entity_resps = Entity::query()->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');
        $applications = MApplication::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');
        $logical_servers = LogicalServer::query()->orderBy('name')->pluck('name', 'id');
        $containers = Container::query()->orderBy('name')->pluck('name', 'id');

        // Select icons
        $icons = Database::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // lists
        $type_list = Database::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $external_list = Database::select('external')->where('external', '<>', null)->distinct()->orderBy('external')->pluck('external');
        $responsible_list = Database::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');

        $database->load('entities', 'entity_resp', 'informations', 'applications');

        return view(
            'admin.databases.edit',
            compact(
                'entities',
                'icons',
                'entity_resps',
                'informations',
                'applications',
                'logical_servers',
                'containers',
                'database',
                'type_list',
                'external_list',
                'responsible_list'
            )
        );
    }

    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        // Save request
        $database->update($request->all());

        // Sync lists
        $database->entities()->sync($request->input('entities', []));
        $database->informations()->sync($request->input('informations', []));
        $database->applications()->sync($request->input('applications', []));
        $database->logicalServers()->sync($request->input('logical_servers', []));
        $database->containers()->sync($request->input('containers', []));

        // Save icon
        $this->iconUploadService->handle($request, $database);
        $database->save();

        return redirect()->route('admin.databases.index');
    }

    public function show(Database $database)
    {
        abort_if(Gate::denies('database_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->load('entities', 'entity_resp', 'informations', 'databaseSourceFluxes', 'databaseDestFluxes', 'applications');

        return view('admin.databases.show', compact('database'));
    }

    public function destroy(Database $database)
    {
        abort_if(Gate::denies('database_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->delete();

        return redirect()->route('admin.databases.index');
    }

    public function massDestroy(MassDestroyDatabaseRequest $request)
    {
        Database::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
