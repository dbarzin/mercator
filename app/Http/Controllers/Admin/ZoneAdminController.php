<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyZoneAdminRequest;
use App\Http\Requests\StoreZoneAdminRequest;
use App\Http\Requests\UpdateZoneAdminRequest;
use App\Models\Cartographer;
use App\Models\ZoneAdmin;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ZoneAdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('zone_admin_access') ? null : Cartographer::allowedIdsFor($user, ZoneAdmin::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $zoneAdmins = ZoneAdmin::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (ZoneAdmin::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.zoneAdmins.index', compact('zoneAdmins'));
    }

    public function create()
    {
        abort_if(Gate::denies('zone_admin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = ZoneAdmin::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.zoneAdmins.create', compact('type_list', 'attributes_list'));
    }

    public function store(StoreZoneAdminRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        ZoneAdmin::create($request->all());

        return redirect()->route('admin.zone-admins.index');
    }

    public function edit(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('edit-object', $zoneAdmin), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = ZoneAdmin::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.zoneAdmins.edit', compact('zoneAdmin', 'type_list', 'attributes_list'));
    }

    public function update(UpdateZoneAdminRequest $request, ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('edit-object', $zoneAdmin), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $zoneAdmin->update($request->all());

        return redirect()->route('admin.zone-admins.index');
    }

    public function show(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('show-object', $zoneAdmin), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneAdmin->load('annuaires', 'forestAds');

        return view('admin.zoneAdmins.show', compact('zoneAdmin'));
    }

    public function destroy(ZoneAdmin $zoneAdmin)
    {
        abort_if(Gate::denies('zone_admin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zoneAdmin->delete();

        return redirect()->route('admin.zone-admins.index');
    }

    public function massDestroy(MassDestroyZoneAdminRequest $request)
    {
        ZoneAdmin::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = ZoneAdmin::query()
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
