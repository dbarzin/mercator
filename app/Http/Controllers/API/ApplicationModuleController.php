<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationModuleRequest;
use App\Http\Requests\MassStoreApplicationModuleRequest;
use App\Http\Requests\MassUpdateApplicationModuleRequest;
use App\Http\Requests\StoreApplicationModuleRequest;
use App\Http\Requests\UpdateApplicationModuleRequest;
use Mercator\Core\Models\ApplicationModule;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationModuleController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_module_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = ApplicationModule::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            ApplicationModule::$searchable ?? [],
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

        $applicationModules = $query->get();

        return response()->json($applicationModules);
    }

    public function store(StoreApplicationModuleRequest $request)
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule = ApplicationModule::create($request->all());

        return response()->json($applicationModule, Response::HTTP_CREATED);
    }

    public function show(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($applicationModule);
    }

    public function update(UpdateApplicationModuleRequest $request, ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->update($request->all());

        return response()->json();
    }

    public function destroy(ApplicationModule $applicationModule)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationModule->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationModuleRequest $request)
    {
        abort_if(Gate::denies('application_module_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ApplicationModule::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreApplicationModuleRequest $request)
    {
        // L’authorize() du FormRequest gère déjà l’appel Gate::denies('application_module_create')
        $data       = $request->validated();
        $createdIds = [];

        $model    = new ApplicationModule();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            // Ne garde que les colonnes du modèle
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var ApplicationModule $applicationModule */
            $applicationModule = ApplicationModule::query()->create($attributes);

            $createdIds[] = $applicationModule->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateApplicationModuleRequest $request)
    {
        // L’authorize() du FormRequest gère déjà l’appel Gate::denies('application_module_edit')
        $data     = $request->validated();
        $model    = new ApplicationModule();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var ApplicationModule $applicationModule */
            $applicationModule = ApplicationModule::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $applicationModule->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
