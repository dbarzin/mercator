<?php

namespace App\Http\Controllers\API;

use App\Actor;

use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use App\Http\Requests\MassDestroyActorRequest;
use App\Http\Resources\Admin\ActorResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

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

        return response()->json($actor, 201);
    }

    public function show(Actor $actor)
    {
        abort_if(Gate::denies('actor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ActorResource($actor);
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
        Actor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

