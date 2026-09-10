<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActorRequest;
use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use App\Models\Actor;
use App\Models\Cartographer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ActorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('actor_access') ? null : Cartographer::allowedIdsFor($user, Actor::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $actors = Actor::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Actor::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.actors.index', compact('actors'));
    }

    public function create()
    {
        abort_if(Gate::denies('actor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributes_list = $this->getAttributes();

        return view('admin.actors.create', compact('attributes_list'));
    }

    public function store(StoreActorRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        Actor::create($request->all());

        return redirect()->route('admin.actors.index');
    }

    public function edit(Actor $actor)
    {
        abort_if(Gate::denies('edit-object', $actor), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributes_list = $this->getAttributes();

        return view('admin.actors.edit', compact('actor', 'attributes_list'));
    }

    public function update(UpdateActorRequest $request, Actor $actor)
    {
        abort_if(Gate::denies('edit-object', $actor), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $actor->update($request->all());

        return redirect()->route('admin.actors.index');
    }

    public function show(Actor $actor)
    {
        abort_if(Gate::denies('show-object', $actor), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        Actor::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Actor::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
