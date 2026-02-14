<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassDestroyWanRequest;
use App\Http\Requests\MassStoreWanRequest;
use App\Http\Requests\MassUpdateWanRequest;
use App\Http\Requests\StoreWanRequest;
use App\Http\Requests\UpdateWanRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Wan;
use Symfony\Component\HttpFoundation\Response;

class WanController extends APIController
{
    protected string $modelClass = Wan::class;

    public function index(Request $request)
    {
        abort_if(Gate::denies('wan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    public function store(StoreWanRequest $request)
    {
        abort_if(Gate::denies('wan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan = Wan::create($request->all());

        return response()->json($wan, 201);
    }

    public function show(Wan $wan)
    {
        abort_if(Gate::denies('wan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($wan);
    }

    public function update(UpdateWanRequest $request, Wan $wan)
    {
        abort_if(Gate::denies('wan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->update($request->all());

        return response()->json();
    }

    public function destroy(Wan $wan)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWanRequest $request)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Wan::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreWanRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds = [];
        $fillable   = (new Wan())->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var Wan $wan */
            $wan = Wan::create($attributes);
            $createdIds[] = $wan->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateWanRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data     = $request->validated();
        $fillable = (new Wan())->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var Wan $wan */
            $wan = Wan::findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (!empty($attributes)) {
                $wan->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
