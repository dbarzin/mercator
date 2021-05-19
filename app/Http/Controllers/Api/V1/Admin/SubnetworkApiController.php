<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSubnetworkRequest;
use App\Http\Requests\UpdateSubnetworkRequest;
use App\Http\Resources\Admin\SubnetworkResource;
use App\Subnetwork;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubnetworkApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('subnetwork_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubnetworkResource(Subnetwork::with(['connected_subnets', 'gateway'])->get());
    }

    public function store(StoreSubnetworkRequest $request)
    {
        $subnetwork = Subnetwork::create($request->all());

        return (new SubnetworkResource($subnetwork))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubnetworkResource($subnetwork->load(['connected_subnets', 'gateway']));
    }

    public function update(UpdateSubnetworkRequest $request, Subnetwork $subnetwork)
    {
        $subnetwork->update($request->all());

        return (new SubnetworkResource($subnetwork))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Subnetwork $subnetwork)
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetwork->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
