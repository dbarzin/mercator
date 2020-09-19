<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Http\Resources\Admin\ActivityResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ActivityResource(Activity::with(['operations'])->get());
    }

    public function store(StoreActivityRequest $request)
    {
        $activity = Activity::create($request->all());
        $activity->operations()->sync($request->input('operations', []));

        return (new ActivityResource($activity))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Activity $activity)
    {
        abort_if(Gate::denies('activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ActivityResource($activity->load(['operations']));
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $activity->update($request->all());
        $activity->operations()->sync($request->input('operations', []));

        return (new ActivityResource($activity))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Activity $activity)
    {
        abort_if(Gate::denies('activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
