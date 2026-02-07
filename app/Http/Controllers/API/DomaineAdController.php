<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDomaineAdRequest;
use App\Http\Requests\MassStoreDomaineAdRequest;
use App\Http\Requests\MassUpdateDomaineAdRequest;
use App\Http\Requests\StoreDomaineAdRequest;
use App\Http\Requests\UpdateDomaineAdRequest;
use Mercator\Core\Models\DomaineAd;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class DomaineAdController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = DomaineAd::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            DomaineAd::$searchable ?? [],
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

        $domaineAd = $query->get();

        return response()->json($domaineAd);
    }

    public function store(StoreDomaineAdRequest $request)
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var DomaineAd $domaineAd */
        $domaineAd = DomaineAd::create($request->all());

        if ($request->has('forestAds') && $request->input('forestAds') !== null) {
            $domaineAd->forestAds()->sync($request->input('forestAds', []));
        }

        return response()->json($domaineAd, Response::HTTP_CREATED);
    }

    public function show(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($domaineAd);
    }

    public function update(UpdateDomaineAdRequest $request, DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->update($request->all());

        if ($request->has('forestAds') && $request->input('forestAds') !== null) {
            $domaineAd->forestAds()->sync($request->input('forestAds', []));
        }

        return response()->json();
    }

    public function destroy(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDomaineAdRequest $request)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DomaineAd::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreDomaineAdRequest $request)
    {
        // L’authorize() du FormRequest gère déjà domaine_ad_create
        $data       = $request->validated();
        $createdIds = [];

        $model    = new DomaineAd();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            $forestAds = $item['forestAds'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['forestAds'])
                ->only($fillable)
                ->toArray();

            /** @var DomaineAd $domaineAd */
            $domaineAd = DomaineAd::query()->create($attributes);

            if (array_key_exists('forestAds', $item) && $forestAds !== null) {
                $domaineAd->forestAds()->sync($forestAds ?? []);
            }

            $createdIds[] = $domaineAd->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateDomaineAdRequest $request)
    {
        // L’authorize() du FormRequest gère déjà domaine_ad_edit
        $data     = $request->validated();
        $model    = new DomaineAd();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id        = $rawItem['id'];
            $forestAds = $rawItem['forestAds'] ?? null;

            /** @var DomaineAd $domaineAd */
            $domaineAd = DomaineAd::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except(['id', 'forestAds'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $domaineAd->update($attributes);
            }

            if (array_key_exists('forestAds', $rawItem) && $forestAds !== null) {
                $domaineAd->forestAds()->sync($forestAds ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
