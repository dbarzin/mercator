<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRouterRequest;
use App\Http\Requests\MassStoreRouterRequest;
use App\Http\Requests\MassUpdateRouterRequest;
use App\Http\Requests\StoreRouterRequest;
use App\Http\Requests\UpdateRouterRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Router;
use Symfony\Component\HttpFoundation\Response;

class RouterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Router::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            Router::$searchable ?? [],
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

        $routers = $query->get();

        return response()->json($routers);
    }

    public function store(StoreRouterRequest $request)
    {
        abort_if(Gate::denies('router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Router $router */
        $router = Router::query()->create($request->all());
        $router->physicalRouters()->sync($request->input('physicalRouters', []));

        return response()->json($router, Response::HTTP_CREATED);
    }

    public function show(Router $router)
    {
        abort_if(Gate::denies('router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($router);
    }

    public function update(UpdateRouterRequest $request, Router $router)
    {
        abort_if(Gate::denies('router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $router->update($request->all());

        if ($request->has('physicalRouters')) {
            $router->physicalRouters()->sync($request->input('physicalRouters', []));
        }

        return response()->json();
    }

    public function destroy(Router $router)
    {
        abort_if(Gate::denies('router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $router->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyRouterRequest $request)
    {
        abort_if(Gate::denies('router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Router::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreRouterRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `router_create`
        $data = $request->validated();

        $createdIds  = [];
        $routerModel = new Router();
        $fillable    = $routerModel->getFillable();

        foreach ($data['items'] as $item) {
            $physicalRouters = $item['physicalRouters'] ?? null;

            // Colonnes du modèle uniquement (sans les relations)
            $attributes = collect($item)
                ->except(['physicalRouters'])
                ->only($fillable)
                ->toArray();

            /** @var Router $router */
            $router = Router::query()->create($attributes);

            if (array_key_exists('physicalRouters', $item)) {
                $router->physicalRouters()->sync($physicalRouters ?? []);
            }

            $createdIds[] = $router->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateRouterRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `router_edit`
        $data       = $request->validated();
        $routerModel = new Router();
        $fillable    = $routerModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id              = $rawItem['id'];
            $physicalRouters = $rawItem['physicalRouters'] ?? null;

            /** @var Router $router */
            $router = Router::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'physicalRouters'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $router->update($attributes);
            }

            if (array_key_exists('physicalRouters', $rawItem)) {
                $router->physicalRouters()->sync($physicalRouters ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
