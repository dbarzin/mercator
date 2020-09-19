<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreWifiTerminalRequest;
use App\Http\Requests\UpdateWifiTerminalRequest;
use App\Http\Resources\Admin\WifiTerminalResource;
use App\WifiTerminal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WifiTerminalApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('wifi_terminal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WifiTerminalResource(WifiTerminal::with(['site', 'building', 'bays'])->get());
    }

    public function store(StoreWifiTerminalRequest $request)
    {
        $wifiTerminal = WifiTerminal::create($request->all());
        $wifiTerminal->bays()->sync($request->input('bays', []));

        return (new WifiTerminalResource($wifiTerminal))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WifiTerminalResource($wifiTerminal->load(['site', 'building', 'bays']));
    }

    public function update(UpdateWifiTerminalRequest $request, WifiTerminal $wifiTerminal)
    {
        $wifiTerminal->update($request->all());
        $wifiTerminal->bays()->sync($request->input('bays', []));

        return (new WifiTerminalResource($wifiTerminal))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
