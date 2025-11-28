<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCertificateRequest;
use App\Http\Requests\MassStoreCertificateRequest;
use App\Http\Requests\MassUpdateCertificateRequest;
use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Certificate;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('certificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Certificate::query();

        // Champs autorisés pour le filtrage afin d’éviter toute injection
        $allowedFields = array_merge(
            Certificate::$searchable ?? [],
            ['id']
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Format "field" ou "field__operator"
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue;
            }

            switch ($operator) {
                case 'exact':
                    $query->where($field, $value);
                    break;

                case 'contains':
                    $query->where($field, 'LIKE', '%' . $value . '%');
                    break;

                case 'startswith':
                    $query->where($field, 'LIKE', $value . '%');
                    break;

                case 'endswith':
                    $query->where($field, 'LIKE', '%' . $value);
                    break;

                case 'lt':
                    $query->where($field, '<', $value);
                    break;

                case 'lte':
                    $query->where($field, '<=', $value);
                    break;

                case 'gt':
                    $query->where($field, '>', $value);
                    break;

                case 'gte':
                    $query->where($field, '>=', $value);
                    break;

                default:
                    $query->where($field, $value);
            }
        }

        $certificates = $query->get();

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

        return new JsonResource($certificate);
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

        Certificate::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreCertificateRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds      = [];
        $certificateModel = new Certificate();
        $fillable        = $certificateModel->getFillable();

        foreach ($data['items'] as $item) {
            $logicalServers = $item['logical_servers'] ?? null;
            $applications   = $item['applications'] ?? null;

            // On garde uniquement les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['logical_servers', 'applications'])
                ->only($fillable)
                ->toArray();

            /** @var Certificate $certificate */
            $certificate = Certificate::query()->create($attributes);

            // Relations uniquement si la clé était présente dans le JSON d’origine
            if (array_key_exists('logical_servers', $item)) {
                $certificate->logical_servers()->sync($logicalServers ?? []);
            }

            if (array_key_exists('applications', $item)) {
                $certificate->applications()->sync($applications ?? []);
            }

            $createdIds[] = $certificate->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateCertificateRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data            = $request->validated();
        $certificateModel = new Certificate();
        $fillable        = $certificateModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id             = $rawItem['id'];
            $logicalServers = $rawItem['logical_servers'] ?? null;
            $applications   = $rawItem['applications'] ?? null;

            /** @var Certificate $certificate */
            $certificate = Certificate::query()->findOrFail($id);

            // On ne garde que les colonnes du modèle, sans les relations ni l'id
            $attributes = collect($rawItem)
                ->except(['id', 'logical_servers', 'applications'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $certificate->update($attributes);
            }

            // Si la clé est présente dans l’item, on sync (même [] -> on vide)
            if (array_key_exists('logical_servers', $rawItem)) {
                $certificate->logical_servers()->sync($logicalServers ?? []);
            }

            if (array_key_exists('applications', $rawItem)) {
                $certificate->applications()->sync($applications ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
