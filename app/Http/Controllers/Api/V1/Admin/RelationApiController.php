<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRelationRequest;
use App\Http\Requests\UpdateRelationRequest;
use App\Http\Resources\Admin\RelationResource;
use App\Relation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RelationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('relation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RelationResource(Relation::with(['source', 'destination'])->get());
    }

    public function store(StoreRelationRequest $request)
    {
        $relation = Relation::create($request->all());

        return (new RelationResource($relation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Relation $relation)
    {
        abort_if(Gate::denies('relation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RelationResource($relation->load(['source', 'destination']));
    }

    public function update(UpdateRelationRequest $request, Relation $relation)
    {
        $relation->update($request->all());

        return (new RelationResource($relation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Relation $relation)
    {
        abort_if(Gate::denies('relation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
