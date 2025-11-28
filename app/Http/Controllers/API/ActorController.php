<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActorRequest;
use App\Http\Requests\MassStoreActorRequest;
use App\Http\Requests\MassUpdateActorRequest;
use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Actor;
use Symfony\Component\HttpFoundation\Response;

class ActorController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('actor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Actor::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            Actor::$searchable,
            ['id'] // Exemple de champs supplémentaires à rendre filtrables
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

        $actors = $query->get();

        return response()->json($actors);
    }

    public function store(StoreActorRequest $request)
    {
        abort_if(Gate::denies('actor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor = Actor::create($request->all());

        return response()->json($actor, Response::HTTP_CREATED);
    }

    public function show(Actor $actor)
    {
        abort_if(Gate::denies('actor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($actor);
    }

    public function update(UpdateActorRequest $request, Actor $actor)
    {
        abort_if(Gate::denies('actor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor->update($request->all());

        return response()->json();
    }

    public function destroy(Actor $actor)
    {
        abort_if(Gate::denies('actor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyActorRequest $request)
    {
        abort_if(Gate::denies('actor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Actor::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreActorRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('actor_create')
        $data        = $request->validated();
        $createdIds  = [];
        $actorModel  = new Actor();
        $fillable    = $actorModel->getFillable();

        foreach ($data['items'] as $item) {
            // Ne garde que les colonnes du modèle (ignore les champs inconnus)
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Actor $actor */
            $actor = Actor::query()->create($attributes);

            $createdIds[] = $actor->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateActorRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('actor_edit')
        $data       = $request->validated();
        $actorModel = new Actor();
        $fillable   = $actorModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Actor $actor */
            $actor = Actor::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $actor->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
