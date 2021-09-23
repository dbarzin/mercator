<?php

namespace App\Http\Controllers\Admin;

use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWifiTerminalRequest;
use App\Http\Requests\StoreWifiTerminalRequest;
use App\Http\Requests\UpdateWifiTerminalRequest;
use App\Site;
use App\WifiTerminal;
use Gate;
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

        return view('admin.wifiTerminals.create', compact('sites', 'buildings'));
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

        $wifiTerminal->load('site', 'building');

        return view('admin.wifiTerminals.edit', compact('sites', 'buildings', 'wifiTerminal'));
    }

    public function update(UpdateWifiTerminalRequest $request, WifiTerminal $wifiTerminal)
    {
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

        return back();
    }

    public function massDestroy(MassDestroyWifiTerminalRequest $request)
    {
        WifiTerminal::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
