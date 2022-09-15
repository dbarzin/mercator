<?php

namespace App\Http\Controllers\Admin;

use App\Bay;
use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalServerRequest;
use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use App\PhysicalServer;
use App\Site;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PhysicalServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $physicalServers = PhysicalServer::all()->sortBy('name');
        $physicalServers = PhysicalServer::with('site','building','bay')->orderBy('name')->get();

        return view('admin.physicalServers.index', compact('physicalServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // List
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.physicalServers.create',
            compact('sites', 'buildings', 'bays', 'responsible_list', 'type_list')
        );
    }

    public function store(StorePhysicalServerRequest $request)
    {
        PhysicalServer::create($request->all());

        return redirect()->route('admin.physical-servers.index');
    }

    public function edit(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // List
        $responsible_list = PhysicalServer::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $type_list = PhysicalServer::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        $physicalServer->load('site', 'building', 'bay');

        return view(
            'admin.physicalServers.edit',
            compact(
                'sites',
                'buildings',
                'bays',
                'responsible_list',
                'type_list',
                'physicalServer'
            )
        );
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalServer)
    {
        $physicalServer->update($request->all());

        return redirect()->route('admin.physical-servers.index');
    }

    public function show(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->load('site', 'building', 'bay', 'serversLogicalServers');

        return view('admin.physicalServers.show', compact('physicalServer'));
    }

    public function destroy(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->delete();

        return redirect()->route('admin.physical-servers.index');
    }

    public function massDestroy(MassDestroyPhysicalServerRequest $request)
    {
        PhysicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
