<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalLinkRequest;
use App\Http\Requests\MassStorePhysicalLinkRequest;
use App\Http\Requests\MassUpdatePhysicalLinkRequest;
use App\Http\Requests\StorePhysicalLinkRequest;
use App\Http\Requests\UpdatePhysicalLinkRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\PhysicalLink;
use Symfony\Component\HttpFoundation\Response;

class PhysicalLinkController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('physical_link_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PhysicalLink::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            PhysicalLink::$searchable ?? [],
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

        $physicalLinks = $query->get();

        return response()->json($physicalLinks);
    }

    public function store(StorePhysicalLinkRequest $request)
    {
        abort_if(Gate::denies('physical_link_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var PhysicalLink $physicalLink */
        $physicalLink = PhysicalLink::query()->create($request->all());

        return response()->json($physicalLink, 201);
    }

    public function show(PhysicalLink $physicalLink)
    {
        abort_if(Gate::denies('physical_link_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($physicalLink);
    }

    public function update(UpdatePhysicalLinkRequest $request, PhysicalLink $physicalLink)
    {
        abort_if(Gate::denies('physical_link_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalLink->update($request->all());

        return response()->json();
    }

    public function destroy(PhysicalLink $physicalLink)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalLink->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalLinkRequest $request)
    {
        abort_if(Gate::denies('physical_link_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalLink::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStorePhysicalLinkRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_link_create`
        $data = $request->validated();

        $createdIds        = [];
        $physicalLinkModel = new PhysicalLink();
        $fillable           = $physicalLinkModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var PhysicalLink $physicalLink */
            $physicalLink = PhysicalLink::query()->create($attributes);

            $createdIds[] = $physicalLink->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdatePhysicalLinkRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_link_edit`
        $data             = $request->validated();
        $physicalLinkModel = new PhysicalLink();
        $fillable          = $physicalLinkModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var PhysicalLink $physicalLink */
            $physicalLink = PhysicalLink::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $physicalLink->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
