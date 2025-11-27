<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActivityRequest;
use App\Http\Requests\MassStoreActivityRequest;
use App\Http\Requests\MassUpdateActivityRequest;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Activity::query();

        // Champs qu’on autorise dans les filtres (pour éviter l’injection par nom de colonne)
        $allowedFields = array_merge(
            Activity::$searchable,
            ['id', 'recovery_time_objective', 'maximum_tolerable_downtime'] # <--- exemple de champs de recherche à ajouter
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // on ignore les champs non autorisés
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
                    // si opérateur inconnu → on traite comme exact
                    $query->where($field, $value);
            }
        }

        $activities = $query->get();

        return response()->json($activities);
    }

    public function store(StoreActivityRequest $request)
    {
        abort_if(Gate::denies('activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity = Activity::create($request->all());

        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));

        return response()->json($activity, 201);
    }

    public function show(Activity $activity)
    {
        abort_if(Gate::denies('activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($activity);
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        abort_if(Gate::denies('activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->update($request->all());

        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));

        return response()->json();
    }

    public function destroy(Activity $activity)
    {
        abort_if(Gate::denies('activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyActivityRequest $request)
    {
        abort_if(Gate::denies('activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Activity::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreActivityRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('activity_create')
        $data = $request->validated();

        $createdIds    = [];
        $activityModel = new Activity();
        $fillable      = $activityModel->getFillable();

        foreach ($data['items'] as $item) {
            $operations = $item['operations'] ?? null;
            $processes  = $item['processes'] ?? null;

            // On garde uniquement les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['operations', 'processes'])
                ->only($fillable)
                ->toArray();

            /** @var \App\Models\Activity $activity */
            $activity = Activity::create($attributes);

            // Relations only si la clé était présente dans le JSON d’origine
            if (array_key_exists('operations', $item)) {
                $activity->operations()->sync($operations ?? []);
            }

            if (array_key_exists('processes', $item)) {
                $activity->processes()->sync($processes ?? []);
            }

            $createdIds[] = $activity->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }


    public function massUpdate(MassUpdateActivityRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('activity_edit')
        $data = $request->validated();

        $activityModel = new Activity();
        $fillable      = $activityModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id         = $rawItem['id'];
            $operations = $rawItem['operations'] ?? null;
            $processes  = $rawItem['processes'] ?? null;

            /** @var \App\Models\Activity $activity */
            $activity = Activity::findOrFail($id);

            // On ne garde que les colonnes du modèle, sans les relations ni l'id
            $attributes = collect($rawItem)
                ->except(['id', 'operations', 'processes'])
                ->only($fillable)
                ->toArray();

            if (!empty($attributes)) {
                $activity->update($attributes);
            }

            // Si la clé est présente dans l’item, on sync (même [] -> on vide)
            if (array_key_exists('operations', $rawItem)) {
                $activity->operations()->sync($operations ?? []);
            }

            if (array_key_exists('processes', $rawItem)) {
                $activity->processes()->sync($processes ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
