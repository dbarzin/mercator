<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDatabaseRequest;
use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;
use App\Models\Application;
use App\Models\Cartographer;
use App\Models\Container;
use App\Models\Database;
use App\Models\Entity;
use App\Models\Information;
use App\Models\LogicalServer;
use App\Services\IconUploadService;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DatabaseController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('database_access') ? null : Cartographer::allowedIdsFor($user, Database::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $databases = Database::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Database::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.databases.index', compact('databases'));
    }

    public function create()
    {
        abort_if(Gate::denies('database_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::query()->orderBy('name')->pluck('name', 'id');
        $entity_resps = Entity::query()->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');
        $applications = Application::query()
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
        $attributes_list = $this->getAttributes();

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
                'responsible_list',
                'attributes_list'
            )
        );
    }

    public function store(StoreDatabaseRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

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
        abort_if(Gate::denies('edit-object', $database), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::query()->orderBy('name')->pluck('name', 'id');
        $entity_resps = Entity::query()->orderBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $informations = Information::query()->orderBy('name')->pluck('name', 'id');
        $applications = Application::query()
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
        $attributes_list = $this->getAttributes();

        $database->load('entities', 'entityResp', 'informations', 'applications');

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
                'responsible_list',
                'attributes_list'
            )
        );
    }

    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        abort_if(Gate::denies('edit-object', $database), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

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
        abort_if(Gate::denies('show-object', $database), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $database->load('entities', 'entityResp', 'informations', 'databaseSourceFluxes', 'databaseDestFluxes', 'applications');

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
        Database::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Database::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
