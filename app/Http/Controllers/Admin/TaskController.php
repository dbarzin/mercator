<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Cartographer;
use App\Models\Task;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('task_access') ? null : Cartographer::allowedIdsFor($user, Task::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $tasks = Task::with('operations')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Task::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = Task::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.tasks.create', compact('type_list', 'attributes_list'));
    }

    public function store(StoreTaskRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        Task::create($request->all());

        return redirect()->route('admin.tasks.index');
    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('edit-object', $task), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = Task::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.tasks.edit', compact('task', 'type_list', 'attributes_list'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        abort_if(Gate::denies('edit-object', $task), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $task->update($request->all());

        return redirect()->route('admin.tasks.index');
    }

    public function show(Task $task)
    {
        abort_if(Gate::denies('show-object', $task), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return redirect()->route('admin.tasks.index');
    }

    public function massDestroy(MassDestroyTaskRequest $request)
    {
        Task::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Task::query()
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
