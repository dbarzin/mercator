<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOperationRequest;
use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Activity;
use App\Models\Actor;
use App\Models\Cartographer;
use App\Models\Operation;
use App\Models\Process;
use App\Models\Task;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class OperationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('operation_access') ? null : Cartographer::allowedIdsFor($user, Operation::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $operations = Operation::with(['process', 'tasks', 'actors', 'activities'])
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Operation::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.operations.index', compact('operations'));
    }

    public function create()
    {
        abort_if(Gate::denies('operation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $actors = Actor::all()->sortBy('name')->pluck('name', 'id');
        $tasks = Task::all()->sortBy('name')->pluck('name', 'id');
        $activities = Activity::all()->sortBy('name')->pluck('name', 'id');
        $type_list = Operation::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.operations.create',
            compact('processes', 'actors', 'tasks', 'activities', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreOperationRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $operation = Operation::create($request->all());
        $operation->actors()->sync($request->input('actors', []));
        $operation->tasks()->sync($request->input('tasks', []));
        $operation->activities()->sync($request->input('activities', []));

        return redirect()->route('admin.operations.index');
    }

    public function edit(Operation $operation)
    {
        abort_if(Gate::denies('edit-object', $operation), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::orderBy('name')->pluck('name', 'id');
        $actors = Actor::all()->sortBy('name')->pluck('name', 'id');
        $tasks = Task::all()->sortBy('name')->pluck('name', 'id');
        $activities = Activity::all()->sortBy('name')->pluck('name', 'id');
        $type_list = Operation::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $operation->load('actors', 'tasks', 'activities');

        return view(
            'admin.operations.edit',
            compact(
                'processes',
                'actors',
                'tasks',
                'operation',
                'activities',
                'type_list',
                'attributes_list'
            )
        );
    }

    public function update(UpdateOperationRequest $request, Operation $operation)
    {
        abort_if(Gate::denies('edit-object', $operation), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $operation->update($request->all());
        $operation->actors()->sync($request->input('actors', []));
        $operation->tasks()->sync($request->input('tasks', []));
        $operation->activities()->sync($request->input('activities', []));

        return redirect()->route('admin.operations.index');
    }

    public function show(Operation $operation)
    {
        abort_if(Gate::denies('show-object', $operation), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operation->load('actors', 'tasks', 'activities');

        return view('admin.operations.show', compact('operation'));
    }

    public function destroy(Operation $operation)
    {
        abort_if(Gate::denies('operation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $operation->delete();

        return redirect()->route('admin.operations.index');
    }

    public function massDestroy(MassDestroyOperationRequest $request)
    {
        Operation::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Operation::query()
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
