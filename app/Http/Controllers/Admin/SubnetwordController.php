<?php

namespace App\Http\Controllers\Admin;

use App\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySubnetwordRequest;
use App\Http\Requests\StoreSubnetwordRequest;
use App\Http\Requests\UpdateSubnetwordRequest;
use App\Subnetword;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SubnetwordController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('subnetword_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwords = Subnetword::all()->sortBy('name');

        return view('admin.subnetwords.index', compact('subnetwords'));
    }

    public function create()
    {
        abort_if(Gate::denies('subnetword_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $connected_subnets = Subnetword::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $gateways = Gateway::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subnetwords.create', compact('connected_subnets', 'gateways'));
    }

    public function store(StoreSubnetwordRequest $request)
    {
        $subnetword = Subnetword::create($request->all());

        return redirect()->route('admin.subnetwords.index');
    }

    public function edit(Subnetword $subnetword)
    {
        abort_if(Gate::denies('subnetword_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $connected_subnets = Subnetword::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $gateways = Gateway::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subnetword->load('connected_subnets', 'gateway');

        return view('admin.subnetwords.edit', compact('connected_subnets', 'gateways', 'subnetword'));
    }

    public function update(UpdateSubnetwordRequest $request, Subnetword $subnetword)
    {
        $subnetword->update($request->all());

        return redirect()->route('admin.subnetwords.index');
    }

    public function show(Subnetword $subnetword)
    {
        abort_if(Gate::denies('subnetword_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetword->load('connected_subnets', 'gateway', 'connectedSubnetsSubnetwords', 'subnetworksNetworks');

        return view('admin.subnetwords.show', compact('subnetword'));
    }

    public function destroy(Subnetword $subnetword)
    {
        abort_if(Gate::denies('subnetword_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetword->delete();

        return back();
    }

    public function massDestroy(MassDestroySubnetwordRequest $request)
    {
        Subnetword::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
