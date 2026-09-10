<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyForestAdRequest;
use App\Http\Requests\StoreForestAdRequest;
use App\Http\Requests\UpdateForestAdRequest;
use App\Models\Cartographer;
use App\Models\Domain;
use App\Models\ForestAd;
use App\Models\ZoneAdmin;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ForestAdController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('forest_ad_access') ? null : Cartographer::allowedIdsFor($user, ForestAd::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $forestAds = ForestAd::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (ForestAd::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.forestAds.index', compact('forestAds'));
    }

    public function create()
    {
        abort_if(Gate::denies('forest_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone_admins = ZoneAdmin::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domains = Domain::all()->sortBy('name')->pluck('name', 'id');
        $type_list = ForestAd::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.forestAds.create', compact('zone_admins', 'domains', 'type_list', 'attributes_list'));
    }

    public function store(StoreForestAdRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $forestAd = ForestAd::create($request->all());
        $forestAd->domains()->sync($request->input('domains', []));

        return redirect()->route('admin.forest-ads.index');
    }

    public function edit(ForestAd $forestAd)
    {
        abort_if(Gate::denies('edit-object', $forestAd), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone_admins = ZoneAdmin::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domains = Domain::all()->sortBy('name')->pluck('name', 'id');
        $type_list = ForestAd::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $forestAd->load('zoneAdmin', 'domains');

        return view('admin.forestAds.edit', compact('zone_admins', 'domains', 'forestAd', 'type_list', 'attributes_list'));
    }

    public function update(UpdateForestAdRequest $request, ForestAd $forestAd)
    {
        abort_if(Gate::denies('edit-object', $forestAd), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $forestAd->update($request->all());
        $forestAd->domains()->sync($request->input('domains', []));

        return redirect()->route('admin.forest-ads.index');
    }

    public function show(ForestAd $forestAd)
    {
        abort_if(Gate::denies('show-object', $forestAd), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->load('zoneAdmin', 'domains');

        return view('admin.forestAds.show', compact('forestAd'));
    }

    public function destroy(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->delete();

        return redirect()->route('admin.forest-ads.index');
    }

    public function massDestroy(MassDestroyForestAdRequest $request)
    {
        ForestAd::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = ForestAd::query()
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
