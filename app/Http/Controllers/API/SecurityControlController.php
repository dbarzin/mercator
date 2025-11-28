<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityControlRequest;
use App\Http\Requests\MassStoreSecurityControlRequest;
use App\Http\Requests\MassUpdateSecurityControlRequest;
use App\Http\Requests\StoreSecurityControlRequest;
use App\Http\Requests\UpdateSecurityControlRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\SecurityControl;
use Symfony\Component\HttpFoundation\Response;

class SecurityControlController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('security_control_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = SecurityControl::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            SecurityControl::$searchable ?? [],
            ['id'] // Ajouter ici d'autres champs explicitement autorisés si nécessaire
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // Ignore les champs non autorisés
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

        $securityControls = $query->get();

        return response()->json($securityControls);
    }

    public function store(StoreSecurityControlRequest $request)
    {
        abort_if(Gate::denies('security_control_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var SecurityControl $securityControl */
        $securityControl = SecurityControl::query()->create($request->all());

        return response()->json($securityControl, Response::HTTP_CREATED);
    }

    public function show(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_control_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($securityControl);
    }

    public function update(UpdateSecurityControlRequest $request, SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_control_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl->update($request->all());

        return response()->json();
    }

    public function destroy(SecurityControl $securityControl)
    {
        abort_if(Gate::denies('security_control_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityControl->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySecurityControlRequest $request)
    {
        abort_if(Gate::denies('security_control_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        SecurityControl::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreSecurityControlRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `security_control_create`
        $data = $request->validated();

        $createdIds          = [];
        $securityControlModel = new SecurityControl();
        $fillable             = $securityControlModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var SecurityControl $securityControl */
            $securityControl = SecurityControl::query()->create($attributes);

            $createdIds[] = $securityControl->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateSecurityControlRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `security_control_edit`
        $data                = $request->validated();
        $securityControlModel = new SecurityControl();
        $fillable             = $securityControlModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var SecurityControl $securityControl */
            $securityControl = SecurityControl::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $securityControl->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
