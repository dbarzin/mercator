<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalServerRequest;
use App\Http\Requests\MassStoreLogicalServerRequest;
use App\Http\Requests\MassUpdateLogicalServerRequest;
use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\LogicalServer;
use Symfony\Component\HttpFoundation\Response;

class LogicalServerController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = LogicalServer::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            LogicalServer::$searchable ?? [],
            ['id'] // Ajouter ici d'autres champs explicitement autorisés si nécessaire
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // Ignore les champs non autorisés
            }

            switch ($operator) {
                case 'exact':
                    $query->where($field, $value);
                    break;

                case 'contains':
                    $query->where($field, 'LIKE', '%' . $value . '%');
                    break;

                case 'startswith':
                    $query->where($field, 'LIKE', $value . '%');
                    break;

                case 'endswith':
                    $query->where($field, 'LIKE', '%' . $value);
                    break;

                case 'lt':
                    $query->where($field, '<', $value);
                    break;

                case 'lte':
                    $query->where($field, '<=', $value);
                    break;

                case 'gt':
                    $query->where($field, '>', $value);
                    break;

                case 'gte':
                    $query->where($field, '>=', $value);
                    break;

                default:
                    $query->where($field, $value);
            }
        }

        $logicalServers = $query->get();

        return response()->json($logicalServers);
    }

    public function store(StoreLogicalServerRequest $request)
    {
        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var LogicalServer $logicalServer */
        $logicalServer = LogicalServer::query()->create($request->all());

        if ($request->has('physicalServers')) {
            $logicalServer->physicalServers()->sync($request->input('physicalServers', []));
        }

        if ($request->has('applications')) {
            $logicalServer->applications()->sync($request->input('applications', []));
        }

        if ($request->has('databases')) {
            $logicalServer->databases()->sync($request->input('databases', []));
        }

        return response()->json($logicalServer, 201);
    }

    public function show(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer['physicalServers'] = $logicalServer->physicalServers()->pluck('id');
        $logicalServer['applications']    = $logicalServer->applications()->pluck('id');
        $logicalServer['databases']       = $logicalServer->databases()->pluck('id');

        return new JsonResource($logicalServer);
    }

    public function update(UpdateLogicalServerRequest $request, LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->update($request->all());

        if ($request->has('physicalServers')) {
            $logicalServer->physicalServers()->sync($request->input('physicalServers', []));
        }

        if ($request->has('applications')) {
            $logicalServer->applications()->sync($request->input('applications', []));
        }

        if ($request->has('databases')) {
            $logicalServer->databases()->sync($request->input('databases', []));
        }

        return response()->json();
    }

    public function destroy(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyLogicalServerRequest $request)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        LogicalServer::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreLogicalServerRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('logical_server_create')
        $data = $request->validated();

        $createdIds         = [];
        $logicalServerModel = new LogicalServer();
        $fillable           = $logicalServerModel->getFillable();

        foreach ($data['items'] as $item) {
            $physicalServers = $item['physicalServers'] ?? null;
            $applications    = $item['applications'] ?? null;
            $databases       = $item['databases'] ?? null;

            // Colonnes du modèle uniquement (sans les relations)
            $attributes = collect($item)
                ->except(['physicalServers', 'applications', 'databases'])
                ->only($fillable)
                ->toArray();

            /** @var LogicalServer $logicalServer */
            $logicalServer = LogicalServer::query()->create($attributes);

            if (array_key_exists('physicalServers', $item)) {
                $logicalServer->physicalServers()->sync($physicalServers ?? []);
            }

            if (array_key_exists('applications', $item)) {
                $logicalServer->applications()->sync($applications ?? []);
            }

            if (array_key_exists('databases', $item)) {
                $logicalServer->databases()->sync($databases ?? []);
            }

            $createdIds[] = $logicalServer->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateLogicalServerRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('logical_server_edit')
        $data               = $request->validated();
        $logicalServerModel = new LogicalServer();
        $fillable           = $logicalServerModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id              = $rawItem['id'];
            $physicalServers = $rawItem['physicalServers'] ?? null;
            $applications    = $rawItem['applications'] ?? null;
            $databases       = $rawItem['databases'] ?? null;

            /** @var LogicalServer $logicalServer */
            $logicalServer = LogicalServer::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'physicalServers', 'applications', 'databases'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $logicalServer->update($attributes);
            }

            if (array_key_exists('physicalServers', $rawItem)) {
                $logicalServer->physicalServers()->sync($physicalServers ?? []);
            }

            if (array_key_exists('applications', $rawItem)) {
                $logicalServer->applications()->sync($applications ?? []);
            }

            if (array_key_exists('databases', $rawItem)) {
                $logicalServer->databases()->sync($databases ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
