<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWanRequest;
use App\Http\Requests\StoreWanRequest;
use App\Http\Requests\UpdateWanRequest;
use App\Models\Cartographer;
use App\Models\Lan;
use App\Models\Man;
use App\Models\Wan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class WanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('wan_access') ? null : Cartographer::allowedIdsFor($user, Wan::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $wans = Wan::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Wan::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.wans.index', compact('wans'));
    }

    public function store(StoreWanRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $wan = Wan::create($request->all());
        $wan->mans()->sync($request->input('mans', []));
        $wan->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.wans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('wan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all()->sortBy('name')->pluck('name', 'id');

        $lans = Lan::all()->sortBy('name')->pluck('name', 'id');
        $type_list = Wan::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.wans.create', compact('mans', 'lans', 'type_list', 'attributes_list'));
    }

    public function edit(Wan $wan)
    {
        abort_if(Gate::denies('edit-object', $wan), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mans = Man::all()->sortBy('name')->pluck('name', 'id');
        $lans = Lan::all()->sortBy('name')->pluck('name', 'id');
        $wan->load('mans', 'lans');
        $type_list = Wan::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.wans.edit', compact('mans', 'lans', 'wan', 'type_list', 'attributes_list'));
    }

    public function update(UpdateWanRequest $request, Wan $wan)
    {
        abort_if(Gate::denies('edit-object', $wan), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $wan->update($request->all());
        $wan->mans()->sync($request->input('mans', []));
        $wan->lans()->sync($request->input('lans', []));

        return redirect()->route('admin.wans.index');
    }

    public function show(Wan $wan)
    {
        abort_if(Gate::denies('show-object', $wan), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->load('mans', 'lans');

        return view('admin.wans.show', compact('wan'));
    }

    public function destroy(Wan $wan)
    {
        abort_if(Gate::denies('wan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wan->delete();

        return redirect()->route('admin.wans.index');
    }

    public function massDestroy(MassDestroyWanRequest $request)
    {
        Wan::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Wan::query()
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
