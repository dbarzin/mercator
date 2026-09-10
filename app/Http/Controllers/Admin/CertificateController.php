<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCertificateRequest;
use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Models\Application;
use App\Models\Cartographer;
use App\Models\Certificate;
use App\Models\LogicalServer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('certificate_access') ? null : Cartographer::allowedIdsFor($user, Certificate::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $certificates = Certificate::query()
            ->with('applications', 'logicalServers')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Certificate::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        abort_if(Gate::denies('certificate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $applications = Application::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // List
        $type_list = Certificate::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.certificates.create',
            compact('logicalServers', 'applications', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreCertificateRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $certificate = Certificate::create($request->all());
        $certificate->logicalServers()->sync($request->input('logicalServers', []));
        $certificate->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.certificates.index');
    }

    public function edit(Certificate $certificate)
    {
        abort_if(Gate::denies('edit-object', $certificate), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $applications = Application::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        // List
        $type_list = Certificate::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.certificates.edit',
            compact('certificate', 'logicalServers', 'type_list', 'applications', 'attributes_list')
        );
    }

    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        abort_if(Gate::denies('edit-object', $certificate), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $certificate->update($request->all());
        $certificate->logicalServers()->sync($request->input('logicalServers', []));
        $certificate->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.certificates.index');
    }

    public function show(Certificate $certificate)
    {
        abort_if(Gate::denies('show-object', $certificate), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        Certificate::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Certificate::query()
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
