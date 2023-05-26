<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityControlRequest;
use App\Http\Requests\StoreSecurityControlRequest;
use App\Http\Requests\UpdateSecurityControlRequest;

use App\SecurityControl;

use App\MApplication;
use App\Process;

use Gate;
use Symfony\Component\HttpFoundation\Response;
use  Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SecurityControlController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('security_controls_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controls = SecurityControl::All()->sortBy('name');

        return view('admin.securityControls.index', compact('controls'));
    }

    public function create()
    {
        abort_if(Gate::denies('security_controls_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityControls.create');
    }

    public function store(StoreSecurityControlRequest $request)
    {
        SecurityControl::create($request->all());

        return redirect()->route('admin.security-controls.index');
    }

    public function edit(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_controls_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityControls.edit', compact('securityControl'));
    }

    public function update(UpdateSecurityControlRequest $request, SecurityControl $securityControl)
    {
        $securityControl->update($request->all());

        return redirect()->route('admin.security-controls.index');
    }

    public function show(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_controls_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityControls.show', compact('securityControl'));
    }

    public function assign(Request $request)
    {
        abort_if(Gate::denies('security_controls_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::All()->sortBy('name')->pluck('name', 'id');
        $processes = Process::All()->sortBy('identifiant')->pluck('identifiant', 'id');

        // Create items
        $items = Collection::make();
        foreach ($applications as $key => $value) {
            $items->put('APP_' . $key, $value);
        }
        foreach ($processes as $key => $value) {
            $items->put('PR_' . $key, $value);
        }

        // Get all security controls
        $controls = SecurityControl::All()->sortBy('name');

        return view('admin.securityControls.assign', compact('items','controls'));
    }

    public function destroy(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_controls_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl->delete();

        return redirect()->route('admin.security-controls.index');
    }



    public function massDestroy(MassDestroySecurityControlRequest $request)
    {
        SecurityControl::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
