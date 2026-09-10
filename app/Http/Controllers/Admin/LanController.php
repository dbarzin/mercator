<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLanRequest;
use App\Http\Requests\StoreLanRequest;
use App\Http\Requests\UpdateLanRequest;
use App\Models\Cartographer;
use App\Models\Lan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class LanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('lan_access') ? null : Cartographer::allowedIdsFor($user, Lan::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $lans = Lan::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Lan::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.lans.index', compact('lans'));
    }

    public function create()
    {
        abort_if(Gate::denies('lan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = Lan::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.lans.create', compact('type_list', 'attributes_list'));
    }

    public function store(StoreLanRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        Lan::create($request->all());

        return redirect()->route('admin.lans.index');
    }

    public function edit(Lan $lan)
    {
        abort_if(Gate::denies('edit-object', $lan), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = Lan::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.lans.edit', compact('lan', 'type_list', 'attributes_list'));
    }

    public function update(UpdateLanRequest $request, Lan $lan)
    {
        abort_if(Gate::denies('edit-object', $lan), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $lan->update($request->all());

        return redirect()->route('admin.lans.index');
    }

    public function show(Lan $lan)
    {
        abort_if(Gate::denies('show-object', $lan), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->load('mans', 'wans');

        return view('admin.lans.show', compact('lan'));
    }

    public function destroy(Lan $lan)
    {
        abort_if(Gate::denies('lan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lan->delete();

        return redirect()->route('admin.lans.index');
    }

    public function massDestroy(MassDestroyLanRequest $request)
    {
        Lan::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Lan::query()
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
