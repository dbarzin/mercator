<?php

namespace App\Http\Controllers\Admin;

use App\Entity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRelationRequest;
use App\Http\Requests\StoreRelationRequest;
use App\Http\Requests\UpdateRelationRequest;
use App\Relation;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class RelationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('relation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relations = Relation::with('source','destination')->orderBy('name')->get();

        return view('admin.relations.index', compact('relations'));
    }

    public function create()
    {
        abort_if(Gate::denies('relation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = Entity::pluck('name', 'id')->sortBy('name')->prepend(trans('global.pleaseSelect'), '');
        $destinations = Entity::pluck('name', 'id')->sortBy('name')->prepend(trans('global.pleaseSelect'), '');
        // lists
        $name_list = Relation::select('name')->where('name', '<>', null)->distinct()->orderBy('name')->pluck('name');
        $type_list = Relation::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.relations.create',
            compact('sources', 'destinations', 'name_list', 'type_list')
        );
    }

    public function store(StoreRelationRequest $request)
    {
        Relation::create($request->all());

        return redirect()->route('admin.relations.index');
    }

    public function edit(Relation $relation)
    {
        abort_if(Gate::denies('relation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = Entity::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $destinations = Entity::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // lists
        $name_list = Relation::select('name')->where('name', '<>', null)->distinct()->orderBy('name')->pluck('name');
        $type_list = Relation::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        $relation->load('source', 'destination');

        return view(
            'admin.relations.edit',
            compact('sources', 'destinations', 'relation', 'type_list', 'name_list')
        );
    }

    public function update(UpdateRelationRequest $request, Relation $relation)
    {
        $relation->update($request->all());

        return redirect()->route('admin.relations.index');
    }

    public function show(Relation $relation)
    {
        abort_if(Gate::denies('relation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relation->load('source', 'destination');

        return view('admin.relations.show', compact('relation'));
    }

    public function destroy(Relation $relation)
    {
        abort_if(Gate::denies('relation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relation->delete();

        return redirect()->route('admin.relations.index');
    }

    public function massDestroy(MassDestroyRelationRequest $request)
    {
        Relation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
