<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNetworkRequest;
use App\Http\Requests\StoreNetworkRequest;
use App\Http\Requests\UpdateNetworkRequest;
use App\Network;
use App\Subnetwork;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class NetworkController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('network_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networks = Network::all()->sortBy('name');

        return view('admin.networks.index', compact('networks'));
    }

    public function create()
    {
        abort_if(Gate::denies('network_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.networks.create', compact('subnetworks'));
    }

    public function store(StoreNetworkRequest $request)
    {
        $network = Network::create($request->all());
        $network->subnetworks()->sync($request->input('subnetworks', []));

        return redirect()->route('admin.networks.index');
    }

    public function edit(Network $network)
    {
        abort_if(Gate::denies('network_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::all()->sortBy('name')->pluck('name', 'id');

        $network->load('subnetworks');

        return view('admin.networks.edit', compact('subnetworks', 'network'));
    }

    public function update(UpdateNetworkRequest $request, Network $network)
    {
        $network->update($request->all());
        $network->subnetworks()->sync($request->input('subnetworks', []));

        return redirect()->route('admin.networks.index');
    }

    public function show(Network $network)
    {
        abort_if(Gate::denies('network_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network->load('subnetworks', 'connectedNetworksExternalConnectedEntities');

        return view('admin.networks.show', compact('network'));
    }

    public function destroy(Network $network)
    {
        abort_if(Gate::denies('network_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $network->delete();

        return back();
    }

    public function massDestroy(MassDestroyNetworkRequest $request)
    {
        Network::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
