<?php

namespace App\Http\Controllers\Admin;

use App\Actor;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActorRequest;
use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ActorController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('actor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actors = Actor::all()->sortBy('name');

        return view('admin.actors.index', compact('actors'));
    }

    public function create()
    {
        abort_if(Gate::denies('actor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.actors.create');
    }

    public function store(StoreActorRequest $request)
    {
        $actor = Actor::create($request->all());

        return redirect()->route('admin.actors.index');
    }

    public function edit(Actor $actor)
    {
        abort_if(Gate::denies('actor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.actors.edit', compact('actor'));
    }

    public function update(UpdateActorRequest $request, Actor $actor)
    {
        $actor->update($request->all());

        return redirect()->route('admin.actors.index');
    }

    public function show(Actor $actor)
    {
        abort_if(Gate::denies('actor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.actors.show', compact('actor'));
    }

    public function destroy(Actor $actor)
    {
        abort_if(Gate::denies('actor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $actor->delete();

        return redirect()->route('admin.actors.index');
    }

    public function massDestroy(MassDestroyActorRequest $request)
    {
        Actor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
