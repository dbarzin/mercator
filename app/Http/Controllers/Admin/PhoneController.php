<?php

namespace App\Http\Controllers\Admin;

use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhoneRequest;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Phone;
use App\Site;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('phone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phones = Phone::all();

        return view('admin.phones.index', compact('phones'));
    }

    public function create()
    {
        abort_if(Gate::denies('phone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.phones.create', compact('sites', 'buildings'));
    }

    public function store(StorePhoneRequest $request)
    {
        $phone = Phone::create($request->all());

        return redirect()->route('admin.phones.index');
    }

    public function edit(Phone $phone)
    {
        abort_if(Gate::denies('phone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $phone->load('site', 'building');

        return view('admin.phones.edit', compact('sites', 'buildings', 'phone'));
    }

    public function update(UpdatePhoneRequest $request, Phone $phone)
    {
        $phone->update($request->all());

        return redirect()->route('admin.phones.index');
    }

    public function show(Phone $phone)
    {
        abort_if(Gate::denies('phone_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        Phone::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
