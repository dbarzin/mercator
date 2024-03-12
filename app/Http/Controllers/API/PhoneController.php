<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhoneRequest;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Resources\Admin\PhoneResource;
use App\Phone;
use Gate;
use Illuminate\Http\Response;

class PhoneController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('phone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phones = Phone::all();

        return response()->json($phones);
    }

    public function store(StorePhoneRequest $request)
    {
        abort_if(Gate::denies('phone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phone = Phone::create($request->all());
        // syncs
        // $phone->roles()->sync($request->input('roles', []));

        return response()->json($phone, 201);
    }

    public function show(Phone $phone)
    {
        abort_if(Gate::denies('phone_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhoneResource($phone);
    }

    public function update(UpdatePhoneRequest $request, Phone $phone)
    {
        abort_if(Gate::denies('phone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phone->update($request->all());
        // syncs
        // $phone->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Phone $phone)
    {
        abort_if(Gate::denies('phone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $phone->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhoneRequest $request)
    {
        abort_if(Gate::denies('phone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Phone::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
