<?php

namespace App\Http\Controllers\API;

use App\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGatewayRequest;
use App\Http\Requests\StoreGatewayRequest;
use App\Http\Requests\UpdateGatewayRequest;
use App\Http\Resources\Admin\GatewayResource;
use App\Subnetwork;
use Gate;
use Illuminate\Http\Response;

class GatewayController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('gateway_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateways = Gateway::all();

        return response()->json($gateways);
    }

    public function store(StoreGatewayRequest $request)
    {
        abort_if(Gate::denies('gateway_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateway = Gateway::create($request->all());

        Subnetwork::whereIn('id', $request->input('subnetworks', []))
            ->update(['gateway_id' => $gateway->id]);

        return response()->json($gateway, 201);
    }

    public function show(Gateway $gateway)
    {
        abort_if(Gate::denies('gateway_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GatewayResource($gateway);
    }

    public function update(UpdateGatewayRequest $request, Gateway $gateway)
    {
        abort_if(Gate::denies('gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateway->update($request->all());

        Subnetwork::where('gateway_id', $gateway->id)
            ->update(['gateway_id' => null]);

        Subnetwork::whereIn('id', $request->input('subnetworks', []))
            ->update(['gateway_id' => $gateway->id]);

        return response()->json();
    }

    public function destroy(Gateway $gateway)
    {
        abort_if(Gate::denies('gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateway->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyGatewayRequest $request)
    {
        abort_if(Gate::denies('gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Gateway::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
