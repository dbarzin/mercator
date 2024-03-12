<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWifiTerminalRequest;
use App\Http\Requests\StoreWifiTerminalRequest;
use App\Http\Requests\UpdateWifiTerminalRequest;
use App\Http\Resources\Admin\WifiTerminalResource;
use App\WifiTerminal;
use Gate;
use Illuminate\Http\Response;

class WifiTerminalController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wifi_terminal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiterminals = WifiTerminal::all();

        return response()->json($wifiterminals);
    }

    public function store(StoreWifiTerminalRequest $request)
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiterminal = WifiTerminal::create($request->all());
        // syncs
        // $wifiterminal->roles()->sync($request->input('roles', []));

        return response()->json($wifiterminal, 201);
    }

    public function show(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WifiTerminalResource($wifiTerminal);
    }

    public function update(UpdateWifiTerminalRequest $request, WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->update($request->all());
        // syncs
        // $wifiTerminal->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWifiTerminalRequest $request)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        WifiTerminal::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
