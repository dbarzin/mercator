<?php

namespace App\Http\Controllers\Admin;

use App\Certificate;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCertificateRequest;
use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\LogicalServer;
use App\MApplication;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('certificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificates = Certificate::all()->sortBy('name');

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        abort_if(Gate::denies('certificate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // List
        $type_list = Certificate::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.certificates.create',
            compact('logical_servers', 'applications', 'type_list')
        );
    }

    public function store(StoreCertificateRequest $request)
    {
        $certificate = Certificate::create($request->all());

        $certificate->logical_servers()->sync($request->input('logical_servers', []));
        $certificate->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.certificates.index');
    }

    public function edit(Certificate $certificate)
    {
        abort_if(Gate::denies('certificate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logical_servers = LogicalServer::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // List
        $type_list = Certificate::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.certificates.edit',
            compact('certificate', 'logical_servers', 'type_list', 'applications')
        );
    }

    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        $certificate->update($request->all());

        $certificate->logical_servers()->sync($request->input('logical_servers', []));
        $certificate->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.certificates.index');
    }

    public function show(Certificate $certificate)
    {
        abort_if(Gate::denies('certificate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.certificates.show', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        abort_if(Gate::denies('certificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificate->delete();

        return redirect()->route('admin.certificates.index');
    }

    public function massDestroy(MassDestroyCertificateRequest $request)
    {
        Certificate::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
