<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\MassStoreUserRequest;
use App\Http\Requests\MassUpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = User::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            User::$searchable ?? [],
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

        $users = $query->get();

        return response()->json($users);
    }

    public function store(StoreUserRequest $request)
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var User $user */
        $user = User::query()->create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->update($request->all());

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles', []));
        }

        return response()->json();
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        User::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreUserRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `user_create`
        $data = $request->validated();

        $createdIds = [];
        $userModel  = new User();
        $fillable   = $userModel->getFillable();

        foreach ($data['items'] as $item) {
            $roles = $item['roles'] ?? null;

            // Colonnes du modèle uniquement (sans relations)
            $attributes = collect($item)
                ->except(['roles'])
                ->only($fillable)
                ->toArray();

            /** @var User $user */
            $user = User::query()->create($attributes);

            if (array_key_exists('roles', $item)) {
                $user->roles()->sync($roles ?? []);
            }

            $createdIds[] = $user->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateUserRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `user_edit`
        $data      = $request->validated();
        $userModel = new User();
        $fillable  = $userModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id    = $rawItem['id'];
            $roles = $rawItem['roles'] ?? null;

            /** @var User $user */
            $user = User::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id', 'roles'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $user->update($attributes);
            }

            if (array_key_exists('roles', $rawItem)) {
                $user->roles()->sync($roles ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
