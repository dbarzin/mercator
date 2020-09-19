<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actor;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use App\Http\Resources\Admin\ActorResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('actor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ActorResource(Actor::all());
    }

    public function store(StoreActorRequest $request)
    {
        $actor = Actor::create($request->all());

        return (new ActorResource($actor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Actor $actor)
    {
        abort_if(Gate::denies('actor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ActorResource($actor);
    }

    public function update(UpdateActorRequest $request, Actor $actor)
    {
        $actor->update($request->all());

        return (new ActorResource($actor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Actor $actor)
    {
        abort_if(Gate::denies('actor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
