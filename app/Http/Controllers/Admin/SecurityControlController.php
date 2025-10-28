<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityControlRequest;
use App\Http\Requests\StoreSecurityControlRequest;
use App\Http\Requests\UpdateSecurityControlRequest;
use App\Models\MApplication;
use App\Models\Process;
use App\Models\SecurityControl;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class SecurityControlController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('security_control_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controls = SecurityControl::All()->sortBy('name');

        return view('admin.securityControls.index', compact('controls'));
    }

    public function create()
    {
        abort_if(Gate::denies('security_control_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityControls.create');
    }

    public function store(StoreSecurityControlRequest $request)
    {
        SecurityControl::create($request->all());

        return redirect()->route('admin.security-controls.index');
    }

    public function edit(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_control_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityControls.edit', compact('securityControl'));
    }

    public function update(UpdateSecurityControlRequest $request, SecurityControl $securityControl)
    {
        $securityControl->update($request->all());

        return redirect()->route('admin.security-controls.index');
    }

    public function show(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_control_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityControls.show', compact('securityControl'));
    }

    public function assign(Request $request)
    {
        abort_if(Gate::denies('security_control_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::All()->sortBy('name')->pluck('name', 'id');
        $processes = Process::All()->sortBy('name')->pluck('name', 'id');

        // Create items
        $apps = Collection::make();
        foreach ($applications as $key => $value) {
            $apps->put('APP_'.$key, $value);
        }
        $procs = Collection::make();
        foreach ($processes as $key => $value) {
            $procs->put('PR_'.$key, $value);
        }

        // Get all security controls
        $controls = SecurityControl::All()->sortBy('name');

        return view('admin.securityControls.assign', compact('apps', 'procs', 'controls'));
    }

    public function associate(Request $request): Response
    {
        abort_if(Gate::denies('security_control_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $controls = [];
        foreach ($request->request as $key => $value) {
            if (str_starts_with($key, 'CTRL_')) {
                $controls[] = substr($key, 5);
            }
        }

        $source = $request->get('source');
        if (str_starts_with($source, 'APP_')) {
            $app = MApplication::query()->where('id', substr($source, 4))->first();
            $app->securityControls()->sync($controls);
        } elseif (str_starts_with($source, 'PR_')) {
            $process = Process::query()->where('id', substr($source, 3))->first();
            $process->securityControls()->sync($controls);
        } else {
            return back()->withErrors(['associate' => 'Invalid ID'])->setStatusCode(422);
        }

        return redirect()->route('admin.security-controls.assign');
    }

    public function list(Request $request): Response
    {
        abort_if(Gate::denies('security_control_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // http://127.0.0.1:8000/admin/security-controls-list?id=APP_2

        // Get control list of object base on the ID
        if (str_starts_with($request->id, 'APP_')) {
            $list = MApplication::query()->where('id', substr($request->id, 4))->first()->securityControls;
        } elseif (str_starts_with($request->id, 'PR_')) {
            $list = Process::query()->where('id', substr($request->id, 3))->first()->securityControls;
        } else {
            // Invalid ID
            return back()->withErrors(['associate' => 'Invalid ID'])->setStatusCode(422);
        }

        // Construct the control list
        $controls = [];
        foreach ($list as $item) {
            $controls[] = 'CTRL_' . $item->id;
        }

        // return JSON
        return response()->json(compact('controls'));
    }

    public function destroy(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_control_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl->delete();

        return redirect()->route('admin.security-controls.index');
    }

    public function massDestroy(MassDestroySecurityControlRequest $request)
    {
        SecurityControl::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
