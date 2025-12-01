<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClusterRequest;
use App\Http\Requests\MassStoreClusterRequest;
use App\Http\Requests\MassUpdateClusterRequest;
use App\Http\Requests\StoreClusterRequest;
use App\Http\Requests\UpdateClusterRequest;
use Mercator\Core\Models\Cluster;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ClusterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Cluster::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            Cluster::$searchable ?? [],
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
                    // Opérateur inconnu → filtre exact
                    $query->where($field, $value);
            }
        }

        $logicalservers = $query->get();

        return response()->json($logicalservers);
    }

    public function store(StoreClusterRequest $request)
    {
        Log::debug('ClusterController:store Start');

        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Cluster $cluster */
        $cluster = Cluster::create($request->all());
        // $cluster->servers()->sync($request->input('servers', []));
        // $cluster->applications()->sync($request->input('applications', []));

        Log::debug('ClusterController:store Done');

        return response()->json($cluster, Response::HTTP_CREATED);
    }

    public function show(Cluster $cluster)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($cluster);
    }

    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->update($request->all());
        // $cluster->servers()->sync($request->input('servers', []));
        // $cluster->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(Cluster $cluster)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cluster->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyClusterRequest $request)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Cluster::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreClusterRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('logical_server_create')
        $data       = $request->validated();
        $createdIds = [];

        $model    = new Cluster();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            // Ne garde que les colonnes du modèle (les relations sont ignorées pour le moment)
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Cluster $cluster */
            $cluster = Cluster::query()->create($attributes);

            // Exemple si besoin plus tard :
            // if (array_key_exists('servers', $item)) {
            //     $cluster->servers()->sync($item['servers'] ?? []);
            // }
            // if (array_key_exists('applications', $item)) {
            //     $cluster->applications()->sync($item['applications'] ?? []);
            // }

            $createdIds[] = $cluster->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateClusterRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('logical_server_edit')
        $data     = $request->validated();
        $model    = new Cluster();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Cluster $cluster */
            $cluster = Cluster::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $cluster->update($attributes);
            }

            // Exemple si besoin plus tard :
            // if (array_key_exists('servers', $rawItem)) {
            //     $cluster->servers()->sync($rawItem['servers'] ?? []);
            // }
            // if (array_key_exists('applications', $rawItem)) {
            //     $cluster->applications()->sync($rawItem['applications'] ?? []);
            // }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
