<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassDestroyForestAdRequest;
use App\Http\Requests\MassStoreForestAdRequest;
use App\Http\Requests\MassUpdateForestAdRequest;
use App\Http\Requests\StoreForestAdRequest;
use App\Http\Requests\UpdateForestAdRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\ForestAd;
use Symfony\Component\HttpFoundation\Response;

class ForestAdController extends APIController
{
    protected string $modelClass = ForestAd::class;

    public function index(Request $request)
    {
        abort_if(Gate::denies('forest_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    public function store(StoreForestAdRequest $request)
    {
        abort_if(Gate::denies('forest_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd = ForestAd::query()->create($request->all());

        $forestAd->domaines()->sync($request->input('domaines', []));

        return response()->json($forestAd, 201);
    }

    public function show(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd['domaines'] = $forestAd->domaines()->pluck('id');

        return new JsonResource($forestAd);
    }

    public function update(UpdateForestAdRequest $request, ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->update($request->all());

        if ($request->has('domaines')) {
            $forestAd->domaines()->sync($request->input('domaines', []));
        }

        return response()->json();
    }

    public function destroy(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyForestAdRequest $request)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ForestAd::query()->whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreForestAdRequest $request)
    {
        $data = $request->validated();

        $createdIds = [];
        $model      = new ForestAd();
        $fillable   = $model->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)->only($fillable)->toArray();

            /** @var ForestAd $forestAd */
            $forestAd = ForestAd::query()->create($attributes);

            $createdIds[] = $forestAd->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateForestAdRequest $request)
    {
        $data     = $request->validated();
        $model    = new ForestAd();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var ForestAd $forestAd */
            $forestAd = ForestAd::query()->findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $forestAd->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
