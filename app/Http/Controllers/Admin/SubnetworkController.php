<?php

namespace App\Http\Controllers\Admin;

use App\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySubnetworkRequest;
use App\Http\Requests\StoreSubnetworkRequest;
use App\Http\Requests\UpdateSubnetworkRequest;
use App\Subnetwork;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SubnetworkController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('subnetwork_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::all()->sortBy('name');

        return view('admin.subnetworks.index', compact('subnetworks'));
    }

    public function create()
    {
        abort_if(Gate::denies('subnetwork_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $connected_subnets = Subnetwork::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $gateways = Gateway::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subnetworks.create', compact('connected_subnets', 'gateways'));
    }

    public function store(StoreSubnetworkRequest $request)
    {
        $subnetwork = Subnetwork::create($request->all());

        return redirect()->route('admin.subnetworks.index');
    }

    public function edit(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $connected_subnets = Subnetwork::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $gateways = Gateway::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subnetwork->load('connected_subnets', 'gateway');

        return view('admin.subnetworks.edit', compact('connected_subnets', 'gateways', 'subnetwork'));
    }

    public function update(UpdateSubnetworkRequest $request, Subnetwork $subnetwork)
    {
        $subnetwork->update($request->all());

        return redirect()->route('admin.subnetworks.index');
    }

    public function show(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->load('connected_subnets', 'gateway', 'connectedSubnetsSubnetworks', 'subnetworksNetworks');

        return view('admin.subnetworks.show', compact('subnetwork'));
    }

    public function destroy(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->delete();

        return back();
    }

    public function massDestroy(MassDestroySubnetworkRequest $request)
    {
        Subnetwork::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
