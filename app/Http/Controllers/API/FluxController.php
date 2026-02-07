<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFluxRequest;
use App\Http\Requests\MassStoreFluxRequest;
use App\Http\Requests\MassUpdateFluxRequest;
use App\Http\Requests\StoreFluxRequest;
use App\Http\Requests\UpdateFluxRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Flux;
use Symfony\Component\HttpFoundation\Response;

class FluxController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('flux_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Flux::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Flux::$searchable ?? [],
            ['id'] // Ajouter ici d'autres champs si nécessaire
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
                    // Opérateur inconnu → fallback exact
                    $query->where($field, $value);
            }
        }

        return response()->json($query->get());
    }

    public function store(StoreFluxRequest $request)
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux = Flux::query()->create($request->all());

        return response()->json($flux, 201);
    }

    public function show(Flux $flux)
    {
        abort_if(Gate::denies('flux_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($flux);
    }

    public function update(UpdateFluxRequest $request, Flux $flux)
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->update($request->all());

        return response()->json();
    }

    public function destroy(Flux $flux)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $flux->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyFluxRequest $request)
    {
        abort_if(Gate::denies('flux_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Flux::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreFluxRequest $request)
    {
        $data = $request->validated();

        $createdIds = [];
        $model      = new Flux();
        $fillable   = $model->getFillable();

        foreach ($data['items'] as $item) {
            // Filtrer uniquement les colonnes du modèle
            $attributes = collect($item)->only($fillable)->toArray();

            /** @var Flux $flux */
            $flux = Flux::query()->create($attributes);

            $createdIds[] = $flux->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateFluxRequest $request)
    {
        $data     = $request->validated();
        $model    = new Flux();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Flux $flux */
            $flux = Flux::query()->findOrFail($id);

            // Exclure l'id
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $flux->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
