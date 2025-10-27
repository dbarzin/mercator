<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMApplicationRequest;
use App\Http\Requests\StoreMApplicationRequest;
use App\Http\Requests\UpdateMApplicationRequest;
use App\Models\Activity;
use App\Models\AdminUser;
use App\Models\ApplicationBlock;
use App\Models\ApplicationService;
use App\Models\Database;
use App\Models\Document;
use App\Models\Entity;
use App\Models\LogicalServer;
use App\Models\MApplication;
use App\Models\Process;
use App\Models\SecurityDevice;
use App\Models\User;
use Gate;
use Symfony\Component\HttpFoundation\Response;

// use App\Services\CartographerService;
// CoreUI Gates
// Laravel Gate

class MApplicationController extends Controller
{
    /**
     * Services
     */
    // protected CartographerService $cartographerService;

    public function index()
    {
        abort_if(Gate::denies('m_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::with(
            'applicationBlock:id,name',
            'entityResp:id,name',
            'entities:id,name',
            'processes:id,name'
        )
            ->orderBy('name')->get();

        return view('admin.applications.index', compact('applications'));
    }

    public function create()
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $activities = Activity::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $security_devices = SecurityDevice::all()->sortBy('name')->pluck('name', 'id');
        $applicationBlocks = ApplicationBlock::all()->sortBy('name')->pluck('name', 'id');
        $icons = MApplication::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $users = AdminUser::all()->sortBy('user_id')->pluck('user_id', 'id');

        // lists
        $type_list = MApplication::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $technology_list = MApplication::select('technology')->where('technology', '<>', null)->distinct()->orderBy('technology')->pluck('technology');
        $users_list = MApplication::select('users')->where('users', '<>', null)->distinct()->orderBy('users')->pluck('users');
        $external_list = MApplication::select('external')->where('external', '<>', null)->distinct()->orderBy('external')->pluck('external');

        // Get Attributes
        $attributes_list = MApplication::select('attributes')
            ->whereNotNull('attributes')
            ->distinct()
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
        $attributes_list = array_unique($res);

        // Get Reponsibles
        $responsible_list = MApplication::select('responsible')
            ->whereNotNull('responsible')
            ->distinct()
            ->orderBy('responsible')
            ->pluck('responsible');
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
                'processes',
                'activities',
                'services',
                'databases',
                'logical_servers',
                'security_devices',
                'applicationBlocks',
                'icons',
                'users',
                'type_list',
                'technology_list',
                'users_list',
                'external_list',
                'responsible_list',
                'referent_list',
                'editor_list',
                'cartographers_list',
                'attributes_list'
            )
        );
    }

    public function store(StoreMApplicationRequest $request)
    {
        $request->merge(['responsible' => implode(', ', $request->responsibles !== null ? $request->responsibles : [])]);
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Create application
        $application = MApplication::create($request->all());

        // Compute RTO - RPO
        $application->rto = $request->rto_days * 60 * 24 + $request->rto_hours * 60 + $request->rto_minutes;
        $application->rpo = $request->rpo_days * 60 * 24 + $request->rpo_hours * 60 + $request->rpo_minutes;

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $application->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $application->icon_id = intval($request->iconSelect);
        } else {
            $application->icon_id = null;
        }

        // Save application
        $application->save();

        // Save relations
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->activities()->sync($request->input('activities', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->cartographers()->sync($request->input('cartographers', []));
        $application->logicalServers()->sync($request->input('logical_servers', []));
        $application->securityDevices()->sync($request->input('security_devices', []));
        $application->administrators()->sync($request->input('administrators', []));

        // Attribution du role pour les nouveaux cartographes
        // $this->cartographerService->attributeCartographerRole($application);

        return redirect()->route('admin.applications.index');
    }

    public function edit(MApplication $application)
    {
        abort_if(Gate::denies('m_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check for cartographers
        // LaravelGate::authorize('is-cartographer-m-application', $application);

        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $processes = Process::all()->sortBy('name')->pluck('name', 'id');
        $activities = Activity::all()->sortBy('name')->pluck('name', 'id');
        $services = ApplicationService::all()->sortBy('name')->pluck('name', 'id');
        $databases = Database::all()->sortBy('name')->pluck('name', 'id');
        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $security_devices = SecurityDevice::all()->sortBy('name')->pluck('name', 'id');
        $applicationBlocks = ApplicationBlock::all()->sortBy('name')->pluck('name', 'id');
        $icons = MApplication::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');
        $users = AdminUser::all()->sortBy('user_id')->pluck('user_id', 'id');

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

        // Get Attributes
        $attributes_list = MApplication::select('attributes')
            ->whereNotNull('attributes')
            ->distinct()
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
        $attributes_list = array_unique($res);

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

        $application->load('entities', 'entityResp', 'processes', 'services', 'databases', 'logicalServers', 'applicationBlock', 'cartographers');

        return view(
            'admin.applications.edit',
            compact(
                'entities',
                'processes',
                'activities',
                'services',
                'databases',
                'logical_servers',
                'security_devices',
                'applicationBlocks',
                'icons',
                'users',
                'application',
                'type_list',
                'technology_list',
                'users_list',
                'external_list',
                'responsible_list',
                'referent_list',
                'editor_list',
                'cartographers_list',
                'attributes_list'
            )
        );
    }

    public function update(UpdateMApplicationRequest $request, MApplication $application)
    {
        $application->responsible = implode(', ', $request->responsibles !== null ? $request->responsibles : []);
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // RTO-RPO
        $application->rto = $request->rto_days * 60 * 24 + $request->rto_hours * 60 + $request->rto_minutes;
        $application->rpo = $request->rpo_days * 60 * 24 + $request->rpo_hours * 60 + $request->rpo_minutes;

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $application->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $application->icon_id = intval($request->iconSelect);
        } else {
            $application->icon_id = null;
        }

        // Other fields
        $application->update($request->all());

        // Relations
        $application->entities()->sync($request->input('entities', []));
        $application->processes()->sync($request->input('processes', []));
        $application->activities()->sync($request->input('activities', []));
        $application->services()->sync($request->input('services', []));
        $application->databases()->sync($request->input('databases', []));
        $application->cartographers()->sync($request->input('cartographers', []));
        $application->logicalServers()->sync($request->input('logical_servers', []));
        $application->securityDevices()->sync($request->input('security_devices', []));
        $application->administrators()->sync($request->input('administrators', []));

        // Attribution du role pour les nouveaux cartographes
        // $this->cartographerService->attributeCartographerRole($application);

        return redirect()->route('admin.applications.index');
    }

    public function show(MApplication $application)
    {
        abort_if(Gate::denies('m_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->load('entities', 'entityResp', 'processes', 'services', 'databases', 'logicalServers', 'applicationBlock', 'applicationSourceFluxes', 'applicationDestFluxes', 'cartographers');

        return view('admin.applications.show', compact('application'));
    }

    public function destroy(MApplication $application)
    {
        abort_if(Gate::denies('m_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // Check for cartographers
        // LaravelGate::authorize('is-cartographer-m-application', $application);

        $application->delete();

        return redirect()->route('admin.applications.index');
    }

    // Return the icon of the application as a PNG file

    public function icon(int $id)
    {
        // Get applications
        $application = MApplication::find($id, ['icon']);
        if ($application === null) {
            return null;
        }

        // Get base 64 image
        $base64Image = $application->icon;

        // Décoder l'image encodée en base64
        $imageData = base64_decode($base64Image);

        // Retourner une réponse avec le contenu de l'image et le type MIME 'image/png'
        return response($imageData)
            ->header('Content-Type', 'image/png');
    }

    public function massDestroy(MassDestroyMApplicationRequest $request)
    {
        MApplication::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
