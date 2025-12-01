<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkRequest;
use App\Http\Requests\MassStoreNetworkRequest;
use App\Http\Requests\MassUpdateNetworkRequest;
use App\Http\Requests\StoreNetworkRequest;
use App\Http\Requests\UpdateNetworkRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Network;
use Symfony\Component\HttpFoundation\Response;

class NetworkController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('network_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Network::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Network::$searchable ?? [],
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

        $networks = $query->get();

        return response()->json($networks);
    }

    public function store(StoreNetworkRequest $request)
    {
        abort_if(Gate::denies('network_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Network $network */
        $network = Network::query()->create($request->all());

        // syncs
        // $network->roles()->sync($request->input('roles', []));

        return response()->json($network, 201);
    }

    public function show(Network $network)
    {
        abort_if(Gate::denies('network_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($network);
    }

    public function update(UpdateNetworkRequest $request, Network $network)
    {
        abort_if(Gate::denies('network_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network->update($request->all());
        // syncs
        // $network->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Network $network)
    {
        abort_if(Gate::denies('network_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyNetworkRequest $request)
    {
        abort_if(Gate::denies('network_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Network::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreNetworkRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('network_create')
        $data = $request->validated();

        $createdIds = [];
        $model      = new Network();
        $fillable   = $model->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Network $network */
            $network = Network::query()->create($attributes);

            $createdIds[] = $network->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateNetworkRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('network_edit')
        $data    = $request->validated();
        $model   = new Network();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Network $network */
            $network = Network::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $network->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
