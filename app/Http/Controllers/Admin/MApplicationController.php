<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationBlock;
use App\ApplicationService;
use App\Database;
use App\Entity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMApplicationRequest;
use App\Http\Requests\StoreMApplicationRequest;
use App\Http\Requests\UpdateMApplicationRequest;
use App\LogicalServer;
use App\MApplication;
use App\Process;
use App\Services\CartographerService;
use App\Services\EventService;
use App\User;
// CoreUI Gates
use Gate;
// Laravel Gate
use Illuminate\Support\Facades\Gate as LaravelGate;
use Symfony\Component\HttpFoundation\Response;

class MApplicationController extends Controller
{
    /**
     * Services
     */
    protected CartographerService $cartographerService;
    protected EventService $eventService;

    /**
     * Automatic Injection for Service
     *
     * @return void
     */
    public function __construct(CartographerService $cartographerService, EventService $eventService)
    {
        $this->cartographerService = $cartographerService;
        $this->eventService = $eventService;
    }

    public function index()
    {
        abort_if(Gate::denies('m_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::with('application_block', 'entity_resp', 'entities', 'processes')
            ->orderBy('name')->get();

        return view('admin.applications.index', compact('applications'));
    }

    public function create()
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $entity_resps = Entity::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $application_blocks = ApplicationBlock::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // lists
        $type_list = MApplication::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $technology_list = MApplication::select('technology')->where('technology', '<>', null)->distinct()->orderBy('technology')->pluck('technology');
        $users_list = MApplication::select('users')->where('users', '<>', null)->distinct()->orderBy('users')->pluck('users');
        $external_list = MApplication::select('external')->where('external', '<>', null)->distinct()->orderBy('external')->pluck('external');

        $responsible_list = MApplication::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $res = [];
        foreach ($responsible_list as $i) {
            foreach (explode(',', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        $responsible_list = array_unique($res);

        $referent_list = MApplication::select('functional_referent')->where('functional_referent', '<>', null)->distinct()->orderBy('functional_referent')->pluck('functional_referent');
        $editor_list = MApplication::select('editor')->where('editor', '<>', null)->distinct()->orderBy('editor')->pluck('editor');
        $cartographers_list = User::all()->sortBy('name')->pluck('name', 'id');

        return view(
            'admin.applications.create',
            compact(
                'entities',
                'entity_resps',
                'processes',
                'services',
                'databases',
                'logical_servers',
                'application_blocks',
                'type_list',
                'technology_list',
                'users_list',
                'external_list',
                'responsible_list',
                'referent_list',
                'editor_list',
                'cartographers_list'
            )
        );
    }

    public function store(StoreMApplicationRequest $request)
    {
        $request->merge(['responsible' => implode(', ', $request->responsibles !== null ? $request->responsibles : [])]);

        $application = MApplication::create($request->all());

        // rto-rpo
        $application->rto = $request->rto_days * 60 * 24 + $request->rto_hours * 60 + $request->rto_minutes;
        $application->rpo = $request->rpo_days * 60 * 24 + $request->rpo_hours * 60 + $request->rpo_minutes;
        $application->save();

        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->cartographers()->sync($request->input('cartographers', []));
        $application->logical_servers()->sync($request->input('logical_servers', []));

        // Attribution du role pour les nouveaux cartographes
        $this->cartographerService->attributeCartographerRole($application);

        return redirect()->route('admin.applications.index');
    }

    public function edit(MApplication $application)
    {
        abort_if(Gate::denies('m_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check for cartographers
        LaravelGate::authorize('is-cartographer-m-application', $application);

        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $entity_resps = Entity::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $application_blocks = ApplicationBlock::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // rto-rpo
        $application->rto_days = intdiv($application->rto, 60 * 24);
        $application->rto_hours = intdiv($application->rto, 60) % 24;
        $application->rto_minutes = $application->rto % 60;

        $application->rpo_days = intdiv($application->rpo, 60 * 24);
        $application->rpo_hours = intdiv($application->rpo, 60) % 24;
        $application->rpo_minutes = $application->rpo % 60;

        // lists
        $type_list = MApplication::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $technology_list = MApplication::select('technology')->where('technology', '<>', null)->distinct()->orderBy('technology')->pluck('technology');
        $users_list = MApplication::select('users')->where('users', '<>', null)->distinct()->orderBy('users')->pluck('users');
        $external_list = MApplication::select('external')->where('external', '<>', null)->distinct()->orderBy('external')->pluck('external');

        $responsible_list = MApplication::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');
        $res = [];
        foreach ($responsible_list as $i) {
            foreach (explode(',', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        $responsible_list = array_unique($res);

        $referent_list = MApplication::select('functional_referent')->where('functional_referent', '<>', null)->distinct()->orderBy('functional_referent')->pluck('functional_referent');
        $editor_list = MApplication::select('editor')->where('editor', '<>', null)->distinct()->orderBy('editor')->pluck('editor');
        $cartographers_list = User::all()->sortBy('name')->pluck('name', 'id');

        $application->load('entities', 'entity_resp', 'processes', 'services', 'databases', 'logical_servers', 'application_block', 'cartographers');
        // Chargement des évènements
        $this->eventService->getLoadAppEvents($application);

        return view(
            'admin.applications.edit',
            compact(
                'entities',
                'entity_resps',
                'processes',
                'services',
                'databases',
                'logical_servers',
                'application_blocks',
                'application',
                'type_list',
                'technology_list',
                'users_list',
                'external_list',
                'responsible_list',
                'referent_list',
                'editor_list',
                'cartographers_list'
            )
        );
    }

    public function update(UpdateMApplicationRequest $request, MApplication $application)
    {
        $application->responsible = implode(', ', $request->responsibles !== null ? $request->responsibles : []);

        // rto-rpo
        $application->rto = $request->rto_days * 60 * 24 + $request->rto_hours * 60 + $request->rto_minutes;
        $application->rpo = $request->rpo_days * 60 * 24 + $request->rpo_hours * 60 + $request->rpo_minutes;

        // other fields
        $application->update($request->all());

        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->cartographers()->sync($request->input('cartographers', []));
        $application->logical_servers()->sync($request->input('logical_servers', []));

        // Attribution du role pour les nouveaux cartographes
        $this->cartographerService->attributeCartographerRole($application);

        return redirect()->route('admin.applications.index');
    }

    public function show(MApplication $application)
    {
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->load('entities', 'entity_resp', 'processes', 'services', 'databases', 'logical_servers', 'application_block', 'applicationSourceFluxes', 'applicationDestFluxes', 'cartographers');
        // Chargement des évènements
        $this->eventService->getLoadAppEvents($application);

        return view('admin.applications.show', compact('application'));
    }

    public function destroy(MApplication $application)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Check for cartographers
        LaravelGate::authorize('is-cartographer-m-application', $application);

        $application->delete();

        return redirect()->route('admin.applications.index');
    }

    public function massDestroy(MassDestroyMApplicationRequest $request)
    {
        MApplication::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
