<?php

namespace App\Http\Controllers\Admin;

use App\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubnetworkRequest;
use App\Http\Requests\StoreSubnetworkRequest;
use App\Http\Requests\UpdateSubnetworkRequest;
use App\Network;
use App\Subnetwork;
use App\Vlan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class SubnetworkController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('subnetwork_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::all()->sortBy('name');

        return view('admin.subnetworks.index', compact('subnetworks'));
    }

    public function create()
    {
        abort_if(Gate::denies('subnetwork_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $connected_subnets = Subnetwork::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $gateways = Gateway::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = Network::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // lists
        $ip_allocation_type_list = Subnetwork::select('ip_allocation_type')->where('ip_allocation_type', '<>', null)->distinct()->orderBy('ip_allocation_type')->pluck('ip_allocation_type');
        $responsible_exp_list = Subnetwork::select('responsible_exp')->where('responsible_exp', '<>', null)->distinct()->orderBy('responsible_exp')->pluck('responsible_exp');
        $dmz_list = Subnetwork::select('dmz')->where('dmz', '<>', null)->distinct()->orderBy('dmz')->pluck('dmz');
        $wifi_list = Subnetwork::select('wifi')->where('wifi', '<>', null)->distinct()->orderBy('wifi')->pluck('wifi');
        $zone_list = Subnetwork::select('zone')->where('zone', '<>', null)->distinct()->orderBy('zone')->pluck('zone');

        return view(
            'admin.subnetworks.create',
            compact(
                'gateways',
                'vlans',
                'networks',
                'ip_allocation_type_list',
                'responsible_exp_list',
                'dmz_list',
                'wifi_list',
                'zone_list'
            )
        );
    }

    public function store(StoreSubnetworkRequest $request)
    {
        $subnetwork = Subnetwork::create($request->all());

        return redirect()->route('admin.subnetworks.index');
    }

    public function edit(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $connected_subnets = Subnetwork::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $gateways = Gateway::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $networks = Network::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // lists
        $ip_allocation_type_list = Subnetwork::select('ip_allocation_type')->where('ip_allocation_type', '<>', null)->distinct()->orderBy('ip_allocation_type')->pluck('ip_allocation_type');
        $responsible_exp_list = Subnetwork::select('responsible_exp')->where('responsible_exp', '<>', null)->distinct()->orderBy('responsible_exp')->pluck('responsible_exp');
        $dmz_list = Subnetwork::select('dmz')->where('dmz', '<>', null)->distinct()->orderBy('dmz')->pluck('dmz');
        $wifi_list = Subnetwork::select('wifi')->where('wifi', '<>', null)->distinct()->orderBy('wifi')->pluck('wifi');
        $zone_list = Subnetwork::select('zone')->where('zone', '<>', null)->distinct()->orderBy('zone')->pluck('zone');

        $subnetwork->load('connected_subnets', 'gateway');

        return view(
            'admin.subnetworks.edit',
            compact(
                'subnetwork',
                'gateways',
                'vlans',
                'networks',
                'ip_allocation_type_list',
                'responsible_exp_list',
                'dmz_list',
                'wifi_list',
                'zone_list'
            )
        );
    }

    public function update(UpdateSubnetworkRequest $request, Subnetwork $subnetwork)
    {
        $subnetwork->update($request->all());

        return redirect()->route('admin.subnetworks.index');
    }

    public function show(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->load('connected_subnets', 'gateway');

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
