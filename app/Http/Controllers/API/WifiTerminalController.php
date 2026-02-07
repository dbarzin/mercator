<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWifiTerminalRequest;
use App\Http\Requests\MassStoreWifiTerminalRequest;
use App\Http\Requests\MassUpdateWifiTerminalRequest;
use App\Http\Requests\StoreWifiTerminalRequest;
use App\Http\Requests\UpdateWifiTerminalRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\WifiTerminal;
use Symfony\Component\HttpFoundation\Response;

class WifiTerminalController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('wifi_terminal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = WifiTerminal::query();

        // Champs autorisés pour le filtrage afin d’éviter toute injection
        $allowedFields = array_merge(
            WifiTerminal::$searchable ?? [],
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

        $wifiTerminals = $query->get();

        return response()->json($wifiTerminals);
    }

    public function store(StoreWifiTerminalRequest $request)
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal = WifiTerminal::create($request->all());

        return response()->json($wifiTerminal, 201);
    }

    public function show(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($wifiTerminal);
    }

    public function update(UpdateWifiTerminalRequest $request, WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->update($request->all());

        return response()->json();
    }

    public function destroy(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyWifiTerminalRequest $request)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        WifiTerminal::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreWifiTerminalRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data = $request->validated();

        $createdIds = [];
        $fillable   = (new WifiTerminal())->getFillable();

        foreach ($data['items'] as $item) {
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var WifiTerminal $wifiTerminal */
            $wifiTerminal   = WifiTerminal::create($attributes);
            $createdIds[]   = $wifiTerminal->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateWifiTerminalRequest $request)
    {
        // L’authorize() du FormRequest protège déjà l’accès
        $data     = $request->validated();
        $fillable = (new WifiTerminal())->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var WifiTerminal $wifiTerminal */
            $wifiTerminal = WifiTerminal::findOrFail($id);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $wifiTerminal->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
