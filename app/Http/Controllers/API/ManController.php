<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyManRequest;
use App\Http\Requests\MassStoreManRequest;
use App\Http\Requests\MassUpdateManRequest;
use App\Http\Requests\StoreManRequest;
use App\Http\Requests\UpdateManRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Man;
use Symfony\Component\HttpFoundation\Response;

class ManController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Man::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Man::$searchable ?? [],
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

        $mans = $query->get();

        return response()->json($mans);
    }

    public function store(StoreManRequest $request)
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Man $man */
        $man = Man::query()->create($request->all());

        return response()->json($man, 201);
    }

    public function show(Man $man)
    {
        abort_if(Gate::denies('man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($man);
    }

    public function update(UpdateManRequest $request, Man $man)
    {
        abort_if(Gate::denies('man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->update($request->all());

        return response()->json();
    }

    public function destroy(Man $man)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $man->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyManRequest $request)
    {
        abort_if(Gate::denies('man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Man::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreManRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('man_create')
        $data = $request->validated();

        $createdIds = [];
        $manModel   = new Man();
        $fillable   = $manModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Man $man */
            $man = Man::query()->create($attributes);

            $createdIds[] = $man->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateManRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('man_edit')
        $data    = $request->validated();
        $manModel = new Man();
        $fillable = $manModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Man $man */
            $man = Man::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $man->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
