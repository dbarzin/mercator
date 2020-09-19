<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSubnetwordRequest;
use App\Http\Requests\UpdateSubnetwordRequest;
use App\Http\Resources\Admin\SubnetwordResource;
use App\Subnetword;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubnetwordApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('subnetword_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubnetwordResource(Subnetword::with(['connected_subnets', 'gateway'])->get());
    }

    public function store(StoreSubnetwordRequest $request)
    {
        $subnetword = Subnetword::create($request->all());

        return (new SubnetwordResource($subnetword))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Subnetword $subnetword)
    {
        abort_if(Gate::denies('subnetword_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubnetwordResource($subnetword->load(['connected_subnets', 'gateway']));
    }

    public function update(UpdateSubnetwordRequest $request, Subnetword $subnetword)
    {
        $subnetword->update($request->all());

        return (new SubnetwordResource($subnetword))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Subnetword $subnetword)
    {
        abort_if(Gate::denies('subnetword_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetword->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
