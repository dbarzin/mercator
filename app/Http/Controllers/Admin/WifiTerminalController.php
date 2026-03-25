<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWifiTerminalRequest;
use App\Http\Requests\StoreWifiTerminalRequest;
use App\Http\Requests\UpdateWifiTerminalRequest;
use Mercator\Core\Models\Building;
use Mercator\Core\Models\Site;
use Mercator\Core\Models\WifiTerminal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WifiTerminalController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wifi_terminal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminals = WifiTerminal::all()->sortBy('name');

        return view('admin.wifiTerminals.index', compact('wifiTerminals'));
    }

    public function create()
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $type_list = WifiTerminal::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.wifiTerminals.create',
            compact('type_list', 'sites', 'buildings')
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $type_list = WifiTerminal::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        // Get WifiTerminal
        $wifiTerminal = WifiTerminal::find($request['id']);

        // WifiTerminal not found
        abort_if($wifiTerminal === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $request->merge($wifiTerminal->only($wifiTerminal->getFillable()));
        $request->flash();

        return view(
            'admin.wifiTerminals.create',
            compact('type_list', 'sites', 'buildings')
        );
    }

    public function store(StoreWifiTerminalRequest $request)
    {
        WifiTerminal::create($request->all());

        return redirect()->route('admin.wifi-terminals.index');
    }

    public function edit(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $type_list = WifiTerminal::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        $wifiTerminal->load('site', 'building');

        return view(
            'admin.wifiTerminals.edit',
            compact('sites', 'buildings', 'wifiTerminal', 'type_list')
        );
    }

    public function update(UpdateWifiTerminalRequest $request, WifiTerminal $wifiTerminal)
    {
        if (! $request->has('type')) {
            $request->merge(['type' => '']);
        }

        $wifiTerminal->update($request->all());

        return redirect()->route('admin.wifi-terminals.index');
    }

    public function show(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->load('site', 'building');

        return view('admin.wifiTerminals.show', compact('wifiTerminal'));
    }

    public function destroy(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->delete();

        return redirect()->route('admin.wifi-terminals.index');
    }

    public function massDestroy(MassDestroyWifiTerminalRequest $request)
    {
        WifiTerminal::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
