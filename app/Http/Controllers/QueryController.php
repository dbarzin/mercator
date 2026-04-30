<?php

namespace App\Http\Controllers;

use App\Http\Requests\MassDestroySavedQueryRequest;
use App\Http\Requests\StoreSavedQueryRequest;
use App\Http\Requests\UpdateSavedQueryRequest;
use App\Models\SavedQuery;
use App\Services\QueryEngine\GraphResult;
use App\Services\QueryEngine\QueryDslValidator;
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
                'output'   => 'list',
                'limit'    => 100,
            ])),
        ]);

        return view('queries.edit', compact('query'));
    }

    /**
     * Enregistrement d'une nouvelle requête.
     */
    public function store(StoreSavedQueryRequest $request)
    {
        abort_if(Gate::denies('query_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = SavedQuery::query()->create($request->validated());
        $query->user_id = auth()->id();
        $query->save();

        // return response()->json($query, Response::HTTP_CREATED);
        return redirect()
            ->route('admin.queries.index');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(SavedQuery $query): View
    {
        $this->authorizeOwner($query);

        return view('queries.form', compact('query'));
    }

    /**
     * Mise à jour.
     */
    public function update(UpdateSavedQueryRequest $request, SavedQuery $query): RedirectResponse
    {
        $this->authorizeOwner($query);

        $query->update($request->validated());

        return redirect()
            ->route('admin.queries.index');
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
            ->route('admin.queries.index');
    }

    /**
     * Duplication d'une requête.
     */
    public function duplicate(SavedQuery $query): RedirectResponse
    {
        $copy = $query->duplicate();
        $copy->save();

        return redirect()
            ->route('admin.queries.edit', $copy);
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
        $dsl = QueryDslValidator::validate($request->all());

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