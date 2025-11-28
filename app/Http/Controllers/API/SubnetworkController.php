<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubnetworkRequest;
use App\Http\Requests\MassStoreSubnetworkRequest;
use App\Http\Requests\MassUpdateSubnetworkRequest;
use App\Http\Requests\StoreSubnetworkRequest;
use App\Http\Requests\UpdateSubnetworkRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Subnetwork;
use Symfony\Component\HttpFoundation\Response;

class SubnetworkController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('subnetwork_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Subnetwork::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Subnetwork::$searchable ?? [],
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

        $subnetworks = $query->get();

        return response()->json($subnetworks);
    }

    public function store(StoreSubnetworkRequest $request)
    {
        abort_if(Gate::denies('subnetwork_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Subnetwork $subnetwork */
        $subnetwork = Subnetwork::query()->create($request->all());

        return response()->json($subnetwork, Response::HTTP_CREATED);
    }

    public function show(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($subnetwork);
    }

    public function update(UpdateSubnetworkRequest $request, Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->update($request->all());

        return response()->json();
    }

    public function destroy(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySubnetworkRequest $request)
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Subnetwork::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreSubnetworkRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `subnetwork_create`
        $data = $request->validated();

        $createdIds      = [];
        $subnetworkModel = new Subnetwork();
        $fillable        = $subnetworkModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Subnetwork $subnetwork */
            $subnetwork = Subnetwork::query()->create($attributes);

            $createdIds[] = $subnetwork->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateSubnetworkRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `subnetwork_edit`
        $data           = $request->validated();
        $subnetworkModel = new Subnetwork();
        $fillable        = $subnetworkModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Subnetwork $subnetwork */
            $subnetwork = Subnetwork::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $subnetwork->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
