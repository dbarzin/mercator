<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkSwitchRequest;
use App\Http\Requests\StoreNetworkSwitchRequest;
use App\Http\Requests\UpdateNetworkSwitchRequest;
use App\NetworkSwitch;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class NetworkSwitchController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('network_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitches = NetworkSwitch::all()->sortBy('name');

        return view('admin.networkSwitches.index', compact('networkSwitches'));
    }

    public function create()
    {
        abort_if(Gate::denies('network_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.networkSwitches.create');
    }

    public function store(StoreNetworkSwitchRequest $request)
    {
        $networkSwitch = NetworkSwitch::create($request->all());

        return redirect()->route('admin.network-switches.index');
    }

    public function edit(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.networkSwitches.edit', compact('networkSwitch'));
    }

    public function update(UpdateNetworkSwitchRequest $request, NetworkSwitch $networkSwitch)
    {
        $networkSwitch->update($request->all());

        return redirect()->route('admin.network-switches.index');
    }

    public function show(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.networkSwitches.show', compact('networkSwitch'));
    }

    public function destroy(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch->delete();

        return redirect()->route('admin.network-switches.index');
    }

    public function massDestroy(MassDestroyNetworkSwitchRequest $request)
    {
        NetworkSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
