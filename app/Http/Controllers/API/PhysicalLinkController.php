<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalLinkRequest;
use App\Http\Requests\StorePhysicalLinkRequest;
use App\Http\Resources\Admin\PhysicalLinkResource;
use App\PhysicalLink;
use Gate;
use Illuminate\Http\Response;

class PhysicalLinkController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_link_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physical_links = PhysicalLink::all();

        return response()->json($physical_links);
    }

    public function store(StorePhysicalLinkRequest $request)
    {
        abort_if(Gate::denies('physical_link_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physical_link = PhysicalLink::create($request->all());

        return response()->json($physical_link, 201);
    }

    public function show(PhysicalLink $physical_link)
    {
        abort_if(Gate::denies('physical_link_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalLinkResource($physical_link);
    }

    public function update(StorePhysicalLinkRequest $request, PhysicalLink $physical_link)
    {
        abort_if(Gate::denies('physical_link_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physical_link->update($request->all());

        return response()->json();
    }

    public function destroy(PhysicalLink $link)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $link->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalLinkRequest $request)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalLink::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
