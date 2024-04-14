<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRelationRequest;
use App\Http\Requests\StoreRelationRequest;
use App\Http\Requests\UpdateRelationRequest;
use App\Relation;
use App\RelationValue;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RelationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('relation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $relations = Relation::with('source', 'destination')->orderBy('name')->get();

        return view('admin.relations.index', compact('relations'));
    }

    public function create()
    {
        abort_if(Gate::denies('relation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = DB::table('entities')->select(['id','name'])->whereNull('deleted_at')->orderBy('name')->get();
        $destinations = $sources;
        // lists
        $type_list = Relation::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $responsibles_list = Relation::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $res = [];
        foreach ($responsibles_list as $i) {
            foreach (explode(',', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        $responsibles_list = array_unique($res);

        session()->put('documents', []);

        return view(
            'admin.relations.create',
            compact('sources', 'destinations', 'type_list', 'responsibles_list', 'attributes_list')
        );
    }

    public function store(StoreRelationRequest $request)
    {
        $request['responsible'] = implode(', ', $request->responsibles !== null ? $request->responsibles : []);
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $request['active'] = $request->has('active');

        $relation = Relation::create($request->all());
        $relation->documents()->sync(session()->get('documents'));

        session()->forget('documents');

        // Save date - values
        $dates = $request['dates'];
        $values = $request['values'];
        if ($dates !== null) {
            for ($i = 0; $i < count($dates); $i++) {
                $relationValue = new RelationValue();
                $relationValue->relation_id = $relation->id;
                $relationValue->price = floatval($values[$i]);
                $relationValue->settDatePriceAttribute($dates[$i]);
                $relationValue->save();
            }
        }

        return redirect()->route('admin.relations.index');
    }

    public function edit(Relation $relation)
    {
        abort_if(Gate::denies('relation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sources = DB::table('entities')->select(['id','name'])->whereNull('deleted_at')->orderBy('name')->get();
        $destinations = $sources;

        // lists
        $type_list = Relation::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $responsibles_list = Relation::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $res = [];
        foreach ($responsibles_list as $i) {
            foreach (explode(',', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        $responsibles_list = array_unique($res);

        $documents = [];
        foreach ($relation->documents as $doc) {
            array_push($documents, $doc->id);
        }
        session()->put('documents', $documents);

        $values = DB::table('relation_values')->select(['date_price','price'])->where('relation_id', '=', $relation->id)->orderBy('date_price')->get();

        return view(
            'admin.relations.edit',
            compact('sources', 'destinations', 'relation', 'type_list', 'attributes_list', 'responsibles_list', 'values')
        );
    }

    public function update(UpdateRelationRequest $request, Relation $relation)
    {
        $request['responsible'] = implode(', ', $request->responsibles !== null ? $request->responsibles : []);
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $request['active'] = $request->has('active');

        $relation->update($request->all());
        $relation->documents()->sync(session()->get('documents'));

        session()->forget('documents');

        // Delete previous date-values
        RelationValue::where('relation_id', $relation->id)->delete();

        // Save date - values
        $dates = $request['dates'];
        $values = $request['values'];
        if ($dates !== null) {
            for ($i = 0; $i < count($dates); $i++) {
                $relationValue = new RelationValue();
                $relationValue->relation_id = $relation->id;
                $relationValue->price = floatval($values[$i]);
                $relationValue->settDatePriceAttribute($dates[$i]);
                $relationValue->save();
            }
        }

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

    private function getAttributes()
    {
        $attributes_list = Relation::select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
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
