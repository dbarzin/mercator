<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassStoreSavedQueryRequest;
use App\Http\Requests\MassUpdateSavedQueryRequest;
use App\Http\Requests\StoreSavedQueryRequest;
use App\Http\Requests\UpdateSavedQueryRequest;
use App\Models\SavedQuery;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class QueryController extends APIController
{
    protected string $modelClass     = SavedQuery::class;
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('query_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    public function store(StoreSavedQueryRequest $request)
    {
        abort_if(Gate::denies('query_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = SavedQuery::query()->create($request->all());

        return response()->json($query, Response::HTTP_CREATED);
    }

    public function show(SavedQuery $query): JsonResource
    {
        abort_if(Gate::denies('query_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // On encapsule le modèle dans une JsonResource pour rester cohérent
        return $this->asJsonResource($query);
    }

    public function update(UpdateSavedQueryRequest $request, SavedQuery $query)
    {
        abort_if(Gate::denies('query_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query->update($request->all());

        return response()->json();
    }

    public function destroy(SavedQuery $query)
    {
        abort_if(Gate::denies('query_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->destroyResource($query);

        return response()->json();
    }

}