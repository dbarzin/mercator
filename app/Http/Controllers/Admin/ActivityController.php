<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyActivityRequest;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\ActivityImpact;
use App\Models\Application;
use App\Models\Cartographer;
use App\Models\Graph;
use App\Models\Operation;
use App\Models\Process;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('activity_access') ? null : Cartographer::allowedIdsFor($user, Activity::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $activities = Activity::with('operations', 'processes')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Activity::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        abort_if(
            Gate::denies('activity_create'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        $operations = Operation::all()->sortBy('name')->pluck('name', 'id');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $applications = Application::all()->sortBy('name')->pluck('name', 'id');

        $types = ActivityImpact::select('impact_type')
            ->whereNotNull('impact_type')
            ->distinct()
            ->orderBy('impact_type')
            ->pluck('impact_type');

        $type_list = Activity::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.activities.create',
            compact('operations', 'processes', 'applications', 'types', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreActivityRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $activity = Activity::create($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));
        $activity->applications()->sync($request->input('applications', []));

        // Compute RTO - RPO...
        $activity->recovery_time_objective = $request->recovery_time_objective_days * 60 * 24 + $request->recovery_time_objective_hours * 60 + $request->recovery_time_objective_minutes;
        $activity->recovery_point_objective = $request->recovery_point_objective_days * 60 * 24 + $request->recovery_point_objective_hours * 60 + $request->recovery_point_objective_minutes;
        $activity->maximum_tolerable_downtime = $request->maximum_tolerable_downtime_days * 60 * 24 + $request->maximum_tolerable_downtime_hours * 60 + $request->maximum_tolerable_downtime_minutes;
        $activity->maximum_tolerable_data_loss = $request->maximum_tolerable_data_loss_days * 60 * 24 + $request->maximum_tolerable_data_loss_hours * 60 + $request->maximum_tolerable_data_loss_minutes;
        $activity->save();

        // Save impact_type - gravity
        $impact_types = $request['impact_types'];
        $severities = $request['severities'];

        if ($impact_types !== null) {
            for ($i = 0; $i < count($impact_types); $i++) {
                $activityImpact = new ActivityImpact;
                $activityImpact->activity_id = $activity->id;
                $activityImpact->impact_type = $impact_types[$i];
                $activityImpact->severity = $severities[$i];
                $activityImpact->save();
            }
        }

        return redirect()->route('admin.activities.index');
    }

    public function edit(Activity $activity)
    {
        abort_if(Gate::denies('edit-object', $activity), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operations = Operation::all()->sortBy('name')->pluck('name', 'id');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $applications = Application::all()->sortBy('name')->pluck('name', 'id');

        $types = ActivityImpact::select('impact_type')
            ->whereNotNull('impact_type')
            ->distinct()
            ->orderBy('impact_type')
            ->pluck('impact_type');

        $type_list = Activity::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $activity->load('operations', 'processes', 'applications', 'impacts');

        return view(
            'admin.activities.edit',
            compact('operations', 'activity', 'processes', 'applications', 'types', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        abort_if(Gate::denies('edit-object', $activity), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $activity->update($request->all());
        $activity->operations()->sync($request->input('operations', []));
        $activity->processes()->sync($request->input('processes', []));
        $activity->applications()->sync($request->input('applications', []));

        // Compute RTO - RPO...
        $activity->recovery_time_objective = $request->recovery_time_objective_days * 60 * 24 + $request->recovery_time_objective_hours * 60 + $request->recovery_time_objective_minutes;
        $activity->recovery_point_objective = $request->recovery_point_objective_days * 60 * 24 + $request->recovery_point_objective_hours * 60 + $request->recovery_point_objective_minutes;
        $activity->maximum_tolerable_downtime = $request->maximum_tolerable_downtime_days * 60 * 24 + $request->maximum_tolerable_downtime_hours * 60 + $request->maximum_tolerable_downtime_minutes;
        $activity->maximum_tolerable_data_loss = $request->maximum_tolerable_data_loss_days * 60 * 24 + $request->maximum_tolerable_data_loss_hours * 60 + $request->maximum_tolerable_data_loss_minutes;
        $activity->save();

        // Delete previous date-values
        ActivityImpact::where('activity_id', $activity->id)->delete();

        // Save impact_type - gravity
        $impact_types = $request['impact_types'];
        $severities = $request['severities'];

        if ($impact_types !== null) {
            for ($i = 0; $i < count($impact_types); $i++) {
                $activityImpact = new ActivityImpact;
                $activityImpact->activity_id = $activity->id;
                $activityImpact->impact_type = $impact_types[$i];
                $activityImpact->severity = $severities[$i];
                $activityImpact->save();
            }
        }

        return redirect()->route('admin.activities.index');
    }

    public function show(Activity $activity)
    {
        abort_if(Gate::denies('show-object', $activity), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->load('operations', 'processes');

        // Select BPMN graphs
        $BPMNGraphs = Graph::query()
            ->select('id', 'name')
            ->where('class', '=', 2)
            ->whereLike('content', '%"#'.$activity->getUID().'"%')
            ->get();

        return view('admin.activities.show',
            compact('activity', 'BPMNGraphs'));
    }

    public function destroy(Activity $activity)
    {
        abort_if(Gate::denies('activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activity->delete();

        return redirect()->route('admin.activities.index');
    }

    public function massDestroy(MassDestroyActivityRequest $request)
    {
        Activity::query()->whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Activity::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
