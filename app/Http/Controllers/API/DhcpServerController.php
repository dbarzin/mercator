<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDhcpServerRequest;
use App\Http\Requests\MassStoreDhcpServerRequest;
use App\Http\Requests\MassUpdateDhcpServerRequest;
use App\Http\Requests\StoreDhcpServerRequest;
use App\Http\Requests\UpdateDhcpServerRequest;
use Mercator\Core\Models\DhcpServer;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class DhcpServerController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('dhcp_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = DhcpServer::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            DhcpServer::$searchable ?? [],
            ['id'] // Champs supplémentaires filtrables
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // ignore les champs non autorisés
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
                    // Opérateur inconnu → filtre exact
                    $query->where($field, $value);
            }
        }

        $dhcpservers = $query->get();

        return response()->json($dhcpservers);
    }

    public function store(StoreDhcpServerRequest $request)
    {
        abort_if(Gate::denies('dhcp_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var DhcpServer $dhcpserver */
        $dhcpserver = DhcpServer::create($request->all());
        // $dhcpserver->roles()->sync($request->input('roles', []));

        return response()->json($dhcpserver, Response::HTTP_CREATED);
    }

    public function show(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($dhcpServer);
    }

    public function update(UpdateDhcpServerRequest $request, DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServer->update($request->all());
        // $dhcpServer->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServer->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDhcpServerRequest $request)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DhcpServer::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreDhcpServerRequest $request)
    {
        // L’authorize() du FormRequest gère déjà dhcp_server_create
        $data       = $request->validated();
        $createdIds = [];

        $model    = new DhcpServer();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            // $roles = $item['roles'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except([
                    // 'roles',
                ])
                ->only($fillable)
                ->toArray();

            /** @var DhcpServer $dhcpserver */
            $dhcpserver = DhcpServer::query()->create($attributes);

            // if (array_key_exists('roles', $item)) {
            //     $dhcpserver->roles()->sync($roles ?? []);
            // }

            $createdIds[] = $dhcpserver->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateDhcpServerRequest $request)
    {
        // L’authorize() du FormRequest gère déjà dhcp_server_edit
        $data     = $request->validated();
        $model    = new DhcpServer();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];
            // $roles = $rawItem['roles'] ?? null;

            /** @var DhcpServer $dhcpServer */
            $dhcpServer = DhcpServer::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except([
                    'id',
                    // 'roles',
                ])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $dhcpServer->update($attributes);
            }

            // if (array_key_exists('roles', $rawItem)) {
            //     $dhcpServer->roles()->sync($roles ?? []);
            // }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
