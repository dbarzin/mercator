<?php

namespace App\Http\Controllers\API;

use App\Certificate;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCertificateRequest;
use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Http\Resources\Admin\CertificateResource;
use Gate;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('certificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificates = Certificate::all();

        return response()->json($certificates);
    }

    public function store(StoreCertificateRequest $request)
    {
        abort_if(Gate::denies('certificate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificate = Certificate::create($request->all());
        $certificate->logical_servers()->sync($request->input('logical_servers', []));
        $certificate->applications()->sync($request->input('applications', []));

        return response()->json($certificate, 201);
    }

    public function show(Certificate $certificate)
    {
        abort_if(Gate::denies('certificate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CertificateResource($certificate);
    }

    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        abort_if(Gate::denies('certificate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificate->update($request->all());
        $certificate->logical_servers()->sync($request->input('logical_servers', []));
        $certificate->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(Certificate $certificate)
    {
        abort_if(Gate::denies('certificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificate->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyCertificateRequest $request)
    {
        abort_if(Gate::denies('certificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Certificate::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
