<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActivityRequest;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\MApplication;
use App\Operation;
use App\Process;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::with('operations', 'processes')->orderBy('name')->get();

        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        abort_if(Gate::denies('activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operations = Operation::all()->sortBy('name')->pluck('name', 'id');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.activities.create', compact('operations', 'processes', 'applications'));
    }

    public function store(StoreActivityRequest $request)
    {
        $activity = Activity::create($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));
        $activity->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.activities.index');
    }

    public function edit(Activity $activity)
    {
        abort_if(Gate::denies('activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operations = Operation::all()->sortBy('name')->pluck('name', 'id');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        $activity->load('operations', 'processes', 'applications');

        return view(
            'admin.activities.edit',
            compact('operations', 'activity', 'processes', 'applications')
        );
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $activity->update($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));
        $activity->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.activities.index');
    }

    public function show(Activity $activity)
    {
        abort_if(Gate::denies('activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->load('operations', 'processes');

        return view('admin.activities.show', compact('activity'));
    }

    public function destroy(Activity $activity)
    {
        abort_if(Gate::denies('activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->delete();

        return redirect()->route('admin.activities.index');
    }

    public function massDestroy(MassDestroyActivityRequest $request)
    {
        Activity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
