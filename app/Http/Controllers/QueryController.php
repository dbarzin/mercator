<?php

namespace App\Http\Controllers;

use App\Http\Requests\MassDestroySavedQueryRequest;
use App\Http\Requests\StoreSavedQueryRequest;
use App\Http\Requests\UpdateSavedQueryRequest;
use App\Models\SavedQuery;
use App\Services\QueryEngine\GraphResult;
use App\Services\QueryEngine\QueryEngineIntrospector;
use App\Services\QueryEngine\QueryResolver;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class QueryController extends Controller
{

    public function __construct(protected QueryResolver $resolver) {}

    /**
     * Liste des requêtes visibles par l'utilisateur courant.
     */
    public function index(): View
    {
        $queries = SavedQuery::visibleBy(auth()->id())
            ->with('user:id,name')
            ->orderBy('name')
            ->get();

        return view('queries.index', compact('queries'));
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request): View
    {
        // Pré-remplissage depuis le query engine (DSL passé en session)
        $query = new SavedQuery([
            'query' => $request->old('query', session('qe_last_query', [
                'from'     => '',
                'select'   => [],
                'filters'  => [],
                'traverse' => [],
                'depth'    => 2,
                'output'   => 'list',
                'limit'    => 100,
            ])),
        ]);

        return view('queries.edit', compact('query'));
    }

    /**
     * Enregistrement d'une nouvelle requête.
     */
    public function store(StoreSavedQueryRequest $request): RedirectResponse
    {
        $data            = $request->validated();
        $data['user_id'] = auth()->id();

        $savedQuery = SavedQuery::create($data);

        return redirect()
            ->route('admin.queries.show', $savedQuery)
            ->with('message', __('Requête « :name » sauvegardée.', ['name' => $savedQuery->name]));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(SavedQuery $query): View
    {
        $this->authorizeOwner($query);

        return view('queries.edit', compact('query'));
    }

    /**
     * Mise à jour.
     */
    public function update(UpdateSavedQueryRequest $request, SavedQuery $query): RedirectResponse
    {
        $this->authorizeOwner($query);

        $query->update($request->validated());

        return redirect()
            ->route('admin.queries.index')
            ->with('message', __('Requête « :name » mise à jour.', ['name' => $query->name]));
    }

    /**
     * Suppression.
     */
    public function destroy(SavedQuery $query): RedirectResponse
    {
        $this->authorizeOwner($query);

        $name = $query->name;
        $query->delete();

        return redirect()
            ->route('admin.queries.index')
            ->with('message', __('Requête « :name » supprimée.', ['name' => $name]));
    }

    /**
     * Duplication d'une requête.
     */
    public function duplicate(SavedQuery $query): RedirectResponse
    {
        $copy = $query->duplicate();
        $copy->save();

        return redirect()
            ->route('admin.queries.edit', $copy)
            ->with('message', __('Requête dupliquée. Modifiez-la avant de la sauvegarder.'));
    }


    public function show(SavedQuery $query): View
    {
        return view('queries.show',
            compact('query'));
    }

    /**
     * Exécute un DSL et retourne le résultat JSON.
     */
    public function execute(Request $request): JsonResponse
    {
        $dsl = $request->validate([
            'from'                     => 'required|string|alpha_dash',
            'select'                   => 'nullable|array',
            'select.*'                 => 'string|alpha_dash',
            'filters'                  => 'nullable|array',
            'filters.*.field'          => 'required|string|alpha_dash',
            'filters.*.operator'       => 'required|string',
            'filters.*.value'          => 'required',
            'traverse'                 => 'nullable|array',
            'traverse.*'               => 'string|alpha_dash',
            'depth'                    => 'nullable|integer|min:1|max:5',
            'output'                   => 'nullable|string|in:graph,list',
            'limit'                    => 'nullable|integer|min:1|max:1000',
        ]);

        $result = $this->resolver->execute($dsl);

        $response = $result->toArray();

        // Métadonnées pour l'UI
        $response['meta'] = [
            'output' => $dsl['output'] ?? 'list',
            'from'   => $dsl['from'],
            'count'  => $result instanceof GraphResult
                ? $result->nodeCount()
                : $result->rowCount(),
        ];

        return response()->json($response);
    }

    /**
     * Liste tous les modèles disponibles.
     */
    public function schema(): JsonResponse
    {
        return response()->json([
            'models' => QueryEngineIntrospector::listModels(),
        ]);
    }

    /**
     * Décrit un modèle : colonnes + relations.
     */
    public function schemaModel(string $model): JsonResponse
    {
        return response()->json(
            QueryEngineIntrospector::describe($model)
        );
    }

    public function massDestroy(MassDestroySavedQueryRequest $request)
    {
        abort_if(Gate::denies('queries_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        SavedQuery::query()->whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ─── Private ───────────────────────────────────────────────

    private function authorizeOwner(SavedQuery $savedQuery): void
    {
        abort_if(

            $savedQuery->user_id !== auth()->id(),
            403,
            'Vous ne pouvez modifier que vos propres requêtes.'
        );
    }
}