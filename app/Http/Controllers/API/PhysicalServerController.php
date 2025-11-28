<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalServerRequest;
use App\Http\Requests\MassStorePhysicalServerRequest;
use App\Http\Requests\MassUpdatePhysicalServerRequest;
use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\PhysicalServer;
use Symfony\Component\HttpFoundation\Response;

class PhysicalServerController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('physical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PhysicalServer::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            PhysicalServer::$searchable ?? [],
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

        $physicalServers = $query->get();

        return response()->json($physicalServers);
    }

    public function store(StorePhysicalServerRequest $request)
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var PhysicalServer $physicalServer */
        $physicalServer = PhysicalServer::query()->create($request->all());

        if ($request->has('applications')) {
            $physicalServer->applications()->sync($request->input('applications', []));
        }

        // Association des serveurs logiques via l’API
        if ($request->has('logicalServers')) {
            $logicalServerIds = $request->input('logicalServers', []);
            $physicalServer->logicalServers()->sync($logicalServerIds);
        }

        return response()->json($physicalServer, 201);
    }

    public function show(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($physicalServer);
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Met à jour les champs simples; les relations sont gérées séparément
        $physicalServer->update($request->except('logicalServers'));

        if ($request->has('applications')) {
            $physicalServer->applications()->sync($request->input('applications', []));
        }

        // Association des serveurs logiques via l’API
        if ($request->has('logicalServers')) {
            $logicalServerIds = $request->input('logicalServers', []);
            \Log::info("Physical server {$physicalServer->name} - syncing logical servers: " . json_encode($logicalServerIds));
            $physicalServer->logicalServers()->sync($logicalServerIds);
        }

        return response()->json();
    }

    public function destroy(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalServerRequest $request)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalServer::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStorePhysicalServerRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_server_create`
        $data = $request->validated();

        $createdIds          = [];
        $physicalServerModel = new PhysicalServer();
        $fillable            = $physicalServerModel->getFillable();

        foreach ($data['items'] as $item) {
            $applications    = $item['applications'] ?? null;
            $logicalServers  = $item['logicalServers'] ?? null;

            // Colonnes du modèle uniquement (sans les relations)
            $attributes = collect($item)
                ->except(['applications', 'logicalServers'])
                ->only($fillable)
                ->toArray();

            /** @var PhysicalServer $physicalServer */
            $physicalServer = PhysicalServer::query()->create($attributes);

            if (array_key_exists('applications', $item)) {
                $physicalServer->applications()->sync($applications ?? []);
            }

            if (array_key_exists('logicalServers', $item)) {
                $physicalServer->logicalServers()->sync($logicalServers ?? []);
            }

            $createdIds[] = $physicalServer->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdatePhysicalServerRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_server_edit`
        $data                = $request->validated();
        $physicalServerModel = new PhysicalServer();
        $fillable            = $physicalServerModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id             = $rawItem['id'];
            $applications   = $rawItem['applications'] ?? null;
            $logicalServers = $rawItem['logicalServers'] ?? null;

            /** @var PhysicalServer $physicalServer */
            $physicalServer = PhysicalServer::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'applications', 'logicalServers'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $physicalServer->update($attributes);
            }

            if (array_key_exists('applications', $rawItem)) {
                $physicalServer->applications()->sync($applications ?? []);
            }

            if (array_key_exists('logicalServers', $rawItem)) {
                $physicalServer->logicalServers()->sync($logicalServers ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
