<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActorRequest;
use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Actor;
use Symfony\Component\HttpFoundation\Response;

class ActorController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('actor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actors = Actor::all();

        return response()->json($actors);
    }

    public function store(StoreActorRequest $request)
    {
        abort_if(Gate::denies('actor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor = Actor::create($request->all());
        $actor->operations()->sync($request->input('operations', []));

        return response()->json($actor, 201);
    }

    public function show(Actor $actor)
    {
        abort_if(Gate::denies('actor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor['operations'] = $actor->operations()->pluck('id');

        return new JsonResource($actor);
    }

    public function update(UpdateActorRequest $request, Actor $actor)
    {
        abort_if(Gate::denies('actor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor->update($request->all());
        if ($request['operations'] !== null)
            $actor->operations()->sync($request->input('operations', []));

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

        Actor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
