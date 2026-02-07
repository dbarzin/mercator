<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationBlockRequest;
use App\Http\Requests\MassStoreApplicationBlockRequest;
use App\Http\Requests\MassUpdateApplicationBlockRequest;
use App\Http\Requests\StoreApplicationBlockRequest;
use App\Http\Requests\UpdateApplicationBlockRequest;
use Mercator\Core\Models\ApplicationBlock;
use Mercator\Core\Models\MApplication;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationBlockController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = ApplicationBlock::query();

        // Champs autorisés pour les filtres (évite l’injection par nom de colonne)
        $allowedFields = array_merge(
            ApplicationBlock::$searchable ?? [],
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
                    // Opérateur inconnu → traité comme un filtre exact
                    $query->where($field, $value);
            }
        }

        $applicationBlocks = $query->get();

        return response()->json($applicationBlocks);
    }

    public function store(StoreApplicationBlockRequest $request)
    {
        abort_if(Gate::denies('application_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var ApplicationBlock $applicationBlock */
        $applicationBlock = ApplicationBlock::create($request->all());

        // Mise à jour des applications liées
        $applications = $request->input('applications', []);
        if (! empty($applications)) {
            MApplication::whereIn('id', $applications)
                ->update(['application_block_id' => $applicationBlock->id]);
        }

        return response()->json($applicationBlock, Response::HTTP_CREATED);
    }

    public function show(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($applicationBlock);
    }

    public function update(UpdateApplicationBlockRequest $request, ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->update($request->all());

        if ($request->has('applications')) {
            $applications = $request->input('applications', []);

            // Même logique que le contrôleur existant : ne fait qu’un update sur les IDs fournis
            if (! empty($applications)) {
                MApplication::whereIn('id', $applications)
                    ->update(['application_block_id' => $applicationBlock->id]);
            }
        }

        return response()->json();
    }

    public function destroy(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationBlockRequest $request)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ApplicationBlock::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreApplicationBlockRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('application_block_create')
        $data          = $request->validated();
        $createdIds    = [];
        $model         = new ApplicationBlock();
        $fillable      = $model->getFillable();

        foreach ($data['items'] as $item) {
            $applications = $item['applications'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['applications'])
                ->only($fillable)
                ->toArray();

            /** @var ApplicationBlock $applicationBlock */
            $applicationBlock = ApplicationBlock::query()->create($attributes);

            if (array_key_exists('applications', $item) && ! empty($applications)) {
                MApplication::whereIn('id', $applications)
                    ->update(['application_block_id' => $applicationBlock->id]);
            }

            $createdIds[] = $applicationBlock->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateApplicationBlockRequest $request)
    {
        // L’authorize() du FormRequest gère déjà le Gate::denies('application_block_edit')
        $data     = $request->validated();
        $model    = new ApplicationBlock();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id           = $rawItem['id'];
            $applications = $rawItem['applications'] ?? null;

            /** @var ApplicationBlock $applicationBlock */
            $applicationBlock = ApplicationBlock::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except(['id', 'applications'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $applicationBlock->update($attributes);
            }

            // Si la clé est présente dans l’item, applique la même logique que update()
            if (array_key_exists('applications', $rawItem) && ! empty($applications)) {
                MApplication::whereIn('id', $applications)
                    ->update(['application_block_id' => $applicationBlock->id]);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
