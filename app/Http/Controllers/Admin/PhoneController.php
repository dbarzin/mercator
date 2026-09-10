<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhoneRequest;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\Phone;
use App\Models\Site;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('phone_access') ? null : Cartographer::allowedIdsFor($user, Phone::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $phones = Phone::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Phone::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.phones.index', compact('phones'));
    }

    public function create()
    {
        abort_if(Gate::denies('phone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');

        $type_list = Phone::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.phones.create',
            compact('sites', 'buildings', 'buildingSiteMap', 'type_list', 'attributes_list')
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('phone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');

        $type_list = Phone::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Get Phone
        $phone = Phone::find($request['id']);

        // Vlan not found
        abort_if($phone === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $phone->only($phone->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->flash();

        return view(
            'admin.phones.create',
            compact('sites', 'buildings', 'buildingSiteMap', 'type_list', 'attributes_list')
        );
    }

    public function store(StorePhoneRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        Phone::create($request->all());

        return redirect()->route('admin.phones.index');
    }

    public function edit(Phone $phone)
    {
        abort_if(Gate::denies('edit-object', $phone), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');

        $type_list = Phone::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $phone->load('site', 'building');

        return view(
            'admin.phones.edit',
            compact('sites', 'buildings', 'buildingSiteMap', 'type_list', 'phone', 'attributes_list')
        );
    }

    public function update(UpdatePhoneRequest $request, Phone $phone)
    {
        abort_if(Gate::denies('edit-object', $phone), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $phone->update($request->all());

        return redirect()->route('admin.phones.index');
    }

    public function show(Phone $phone)
    {
        abort_if(Gate::denies('show-object', $phone), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phone->load('site', 'building');

        return view('admin.phones.show', compact('phone'));
    }

    public function destroy(Phone $phone)
    {
        abort_if(Gate::denies('phone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phone->delete();

        return redirect()->route('admin.phones.index');
    }

    public function massDestroy(MassDestroyPhoneRequest $request)
    {
        Phone::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Phone::query()
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
