<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMApplicationRequest;
use App\Http\Requests\MassStoreMApplicationRequest;
use App\Http\Requests\MassUpdateMApplicationRequest;
use App\Http\Requests\StoreMApplicationRequest;
use App\Http\Requests\UpdateMApplicationRequest;
use Mercator\Core\Models\MApplication;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('m_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = MApplication::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            MApplication::$searchable ?? [],
            ['id'] // Champs supplémentaires filtrables
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // ignore les champs non autorisés
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
                    // Opérateur inconnu → traité comme un filtre exact
                    $query->where($field, $value);
            }
        }

        $applications = $query->get();

        return response()->json($applications);
    }

    public function store(StoreMApplicationRequest $request)
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var MApplication $application */
        $application = MApplication::create($request->all());
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('application_services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->logicalServers()->sync($request->input('logical_servers', []));
        $application->activities()->sync($request->input('activities', []));

        return response()->json($application, Response::HTTP_CREATED);
    }

    public function show(MApplication $application)
    {
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application['entities']       = $application->entities()->pluck('id');
        $application['processes']      = $application->processes()->pluck('id');
        $application['services']       = $application->services()->pluck('id');
        $application['databases']      = $application->databases()->pluck('id');
        $application['logicalServers'] = $application->logicalServers()->pluck('id');
        $application['activities']     = $application->activities()->pluck('id');

        return new JsonResource($application);
    }

    public function update(UpdateMApplicationRequest $request, MApplication $application)
    {
        abort_if(Gate::denies('m_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->update($request->all());

        if ($request->has('entities')) {
            $application->entities()->sync($request->input('entities', []));
        }
        if ($request->has('processes')) {
            $application->processes()->sync($request->input('processes', []));
        }
        if ($request->has('activities')) {
            $application->activities()->sync($request->input('activities', []));
        }
        if ($request->has('databases')) {
            $application->databases()->sync($request->input('databases', []));
        }
        if ($request->has('logical_servers')) {
            $application->logicalServers()->sync($request->input('logical_servers', []));
        }
        if ($request->has('application_services')) {
            $application->services()->sync($request->input('application_services', []));
        }

        return response()->json();
    }

    public function destroy(MApplication $application)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyMApplicationRequest $request)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        MApplication::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreMApplicationRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('m_application_create')
        $data       = $request->validated();
        $createdIds = [];

        $model    = new MApplication();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            $entities            = $item['entities'] ?? null;
            $processes           = $item['processes'] ?? null;
            $activities          = $item['activities'] ?? null;
            $databases           = $item['databases'] ?? null;
            $logicalServersIds   = $item['logical_servers'] ?? null;
            $applicationServices = $item['application_services'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except([
                    'entities',
                    'processes',
                    'activities',
                    'databases',
                    'logical_servers',
                    'application_services',
                ])
                ->only($fillable)
                ->toArray();

            /** @var MApplication $application */
            $application = MApplication::query()->create($attributes);

            // Relations uniquement si la clé est présente dans l’item
            if (array_key_exists('entities', $item)) {
                $application->entities()->sync($entities ?? []);
            }
            if (array_key_exists('processes', $item)) {
                $application->processes()->sync($processes ?? []);
            }
            if (array_key_exists('activities', $item)) {
                $application->activities()->sync($activities ?? []);
            }
            if (array_key_exists('databases', $item)) {
                $application->databases()->sync($databases ?? []);
            }
            if (array_key_exists('logical_servers', $item)) {
                $application->logicalServers()->sync($logicalServersIds ?? []);
            }
            if (array_key_exists('application_services', $item)) {
                $application->services()->sync($applicationServices ?? []);
            }

            $createdIds[] = $application->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateMApplicationRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('m_application_edit')
        $data    = $request->validated();
        $model   = new MApplication();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id                 = $rawItem['id'];
            $entities           = $rawItem['entities'] ?? null;
            $processes          = $rawItem['processes'] ?? null;
            $activities         = $rawItem['activities'] ?? null;
            $databases          = $rawItem['databases'] ?? null;
            $logicalServersIds  = $rawItem['logical_servers'] ?? null;
            $applicationServices = $rawItem['application_services'] ?? null;

            /** @var MApplication $application */
            $application = MApplication::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except([
                    'id',
                    'entities',
                    'processes',
                    'activities',
                    'databases',
                    'logical_servers',
                    'application_services',
                ])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $application->update($attributes);
            }

            // Si la clé est présente dans l’item, on sync (même [] -> vide la relation)
            if (array_key_exists('entities', $rawItem)) {
                $application->entities()->sync($entities ?? []);
            }
            if (array_key_exists('processes', $rawItem)) {
                $application->processes()->sync($processes ?? []);
            }
            if (array_key_exists('activities', $rawItem)) {
                $application->activities()->sync($activities ?? []);
            }
            if (array_key_exists('databases', $rawItem)) {
                $application->databases()->sync($databases ?? []);
            }
            if (array_key_exists('logical_servers', $rawItem)) {
                $application->logicalServers()->sync($logicalServersIds ?? []);
            }
            if (array_key_exists('application_services', $rawItem)) {
                $application->services()->sync($applicationServices ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
