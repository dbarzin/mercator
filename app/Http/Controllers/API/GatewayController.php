<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGatewayRequest;
use App\Http\Requests\MassStoreGatewayRequest;
use App\Http\Requests\MassUpdateGatewayRequest;
use App\Http\Requests\StoreGatewayRequest;
use App\Http\Requests\UpdateGatewayRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Gateway;
use Mercator\Core\Models\Subnetwork;
use Symfony\Component\HttpFoundation\Response;

class GatewayController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('gateway_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Gateway::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Gateway::$searchable ?? [],
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

        $gateways = $query->get();

        return response()->json($gateways);
    }

    public function store(StoreGatewayRequest $request)
    {
        abort_if(Gate::denies('gateway_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Gateway $gateway */
        $gateway = Gateway::query()->create($request->all());

        Subnetwork::whereIn('id', $request->input('subnetworks', []))
            ->update(['gateway_id' => $gateway->id]);

        return response()->json($gateway, 201);
    }

    public function show(Gateway $gateway)
    {
        abort_if(Gate::denies('gateway_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($gateway);
    }

    public function update(UpdateGatewayRequest $request, Gateway $gateway)
    {
        abort_if(Gate::denies('gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateway->update($request->all());

        // Réinitialise les anciens liens
        Subnetwork::where('gateway_id', $gateway->id)
            ->update(['gateway_id' => null]);

        // Associe les nouveaux subnetworks
        Subnetwork::whereIn('id', $request->input('subnetworks', []))
            ->update(['gateway_id' => $gateway->id]);

        return response()->json();
    }

    public function destroy(Gateway $gateway)
    {
        abort_if(Gate::denies('gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateway->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyGatewayRequest $request)
    {
        abort_if(Gate::denies('gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Gateway::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreGatewayRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('gateway_create')
        $data = $request->validated();

        $createdIds  = [];
        $gatewayModel = new Gateway();
        $fillable     = $gatewayModel->getFillable();

        foreach ($data['items'] as $item) {
            $subnetworks = $item['subnetworks'] ?? null;

            // On garde uniquement les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['subnetworks'])
                ->only($fillable)
                ->toArray();

            /** @var Gateway $gateway */
            $gateway = Gateway::query()->create($attributes);

            if (array_key_exists('subnetworks', $item) && ! empty($subnetworks)) {
                Subnetwork::whereIn('id', $subnetworks)
                    ->update(['gateway_id' => $gateway->id]);
            }

            $createdIds[] = $gateway->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateGatewayRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('gateway_edit')
        $data        = $request->validated();
        $gatewayModel = new Gateway();
        $fillable     = $gatewayModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id          = $rawItem['id'];
            $subnetworks = $rawItem['subnetworks'] ?? null;

            /** @var Gateway $gateway */
            $gateway = Gateway::query()->findOrFail($id);

            // Mise à jour des attributs simples
            $attributes = collect($rawItem)
                ->except(['id', 'subnetworks'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $gateway->update($attributes);
            }

            // Si la clé subnetworks est présente, on réapplique les liens
            if (array_key_exists('subnetworks', $rawItem)) {
                // Réinitialise les anciens liens
                Subnetwork::where('gateway_id', $gateway->id)
                    ->update(['gateway_id' => null]);

                if (! empty($subnetworks)) {
                    Subnetwork::whereIn('id', $subnetworks)
                        ->update(['gateway_id' => $gateway->id]);
                }
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
