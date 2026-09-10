<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProcessRequest;
use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Models\Activity;
use App\Models\Application;
use App\Models\Cartographer;
use App\Models\Entity;
use App\Models\Information;
use App\Models\MacroProcessus;
use App\Models\Process;
use App\Services\IconUploadService;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ProcessController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('process_access') ? null : Cartographer::allowedIdsFor($user, Process::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $processes = Process::with('operations', 'activities', 'information', 'macroProcess')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Process::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')

            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.processes.index', compact('processes'));
    }

    public function create()
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::orderBy('name')->pluck('name', 'id');
        $entities = Entity::orderBy('name')->pluck('name', 'id');
        $informations = Information::orderBy('name')->pluck('name', 'id');
        $macroProcessuses = MacroProcessus::orderBy('name')->pluck('name', 'id');
        $applications = Application::orderBy('name')->pluck('name', 'id');
        // lists
        $owner_list = Process::select('owner')->where('owner', '<>', null)
            ->distinct()->orderBy('owner')->pluck('owner');
        // Select icons
        $icons = Process::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $type_list = Process::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.processes.create',
            compact('activities', 'entities', 'informations', 'applications', 'macroProcessuses', 'owner_list', 'icons', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreProcessRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $process = Process::create($request->all());
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->information()->sync($request->input('informations', []));
        $process->applications()->sync($request->input('applications', []));

        // Save icon
        $this->iconUploadService->handle($request, $process);

        // Save process
        $process->save();

        return redirect()->route('admin.processes.index');
    }

    public function edit(Process $process)
    {
        abort_if(Gate::denies('edit-object', $process), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $activities = Activity::orderBy('name')->pluck('name', 'id');
        $entities = Entity::orderBy('name')->pluck('name', 'id');
        $informations = Information::orderBy('name')->pluck('name', 'id');
        $macroProcessuses = MacroProcessus::all()->sortBy('name')->pluck('name', 'id');
        $applications = Application::orderBy('name')->pluck('name', 'id');
        $icons = Process::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        // lists
        $owner_list = Process::select('owner')->where('owner', '<>', null)->distinct()->orderBy('owner')->pluck('owner');
        $type_list = Process::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $process->load('activities', 'entities', 'information', 'applications');

        return view(
            'admin.processes.edit',
            compact(
                'activities',
                'entities',
                'informations',
                'process',
                'macroProcessuses',
                'owner_list',
                'applications',
                'icons',
                'type_list',
                'attributes_list'
            )
        );
    }

    public function update(UpdateProcessRequest $request, Process $process)
    {
        abort_if(Gate::denies('edit-object', $process), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Save icon
        $this->iconUploadService->handle($request, $process);

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Update Process
        $process->update($request->all());

        // Relations
        $process->activities()->sync($request->input('activities', []));
        $process->entities()->sync($request->input('entities', []));
        $process->information()->sync($request->input('informations', []));
        $process->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.processes.index');
    }

    public function show(Process $process)
    {
        abort_if(Gate::denies('show-object', $process), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->load('activities', 'entities', 'information', 'applications', 'macroProcess');

        return view('admin.processes.show', compact('process'));
    }

    public function destroy(Process $process)
    {
        abort_if(Gate::denies('process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $process->delete();

        return redirect()->route('admin.processes.index');
    }

    public function massDestroy(MassDestroyProcessRequest $request)
    {
        Process::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Process::query()
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
