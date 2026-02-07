<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAnnuaireRequest;
use App\Http\Requests\MassStoreAnnuaireRequest;
use App\Http\Requests\MassUpdateAnnuaireRequest;
use App\Http\Requests\StoreAnnuaireRequest;
use App\Http\Requests\UpdateAnnuaireRequest;
use Mercator\Core\Models\Annuaire;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class AnnuaireController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('annuaire_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Annuaire::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            Annuaire::$searchable,
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

        $annuaires = $query->get();

        return response()->json($annuaires);
    }

    public function store(StoreAnnuaireRequest $request)
    {
        abort_if(Gate::denies('annuaire_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire = Annuaire::create($request->all());

        return response()->json($annuaire, Response::HTTP_CREATED);
    }

    public function show(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($annuaire);
    }

    public function update(UpdateAnnuaireRequest $request, Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->update($request->all());

        return response()->json();
    }

    public function destroy(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyAnnuaireRequest $request)
    {
        abort_if(Gate::denies('annuaire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Annuaire::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreAnnuaireRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('annuaire_create')
        $data       = $request->validated();
        $createdIds = [];

        $model    = new Annuaire();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            // Ne garde que les colonnes du modèle (ignore les champs inconnus)
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Annuaire $annuaire */
            $annuaire = Annuaire::query()->create($attributes);

            $createdIds[] = $annuaire->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateAnnuaireRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('annuaire_edit')
        $data    = $request->validated();
        $model   = new Annuaire();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Annuaire $annuaire */
            $annuaire = Annuaire::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $annuaire->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
