<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Permission;
use App\Role;
use Gate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    /**
     * Triage des permissions pour un meilleur affichage dans la vue Blade
     *
     * @param Collection $permissions Tableau des permissions sur lesquelles le triage sera effectué
     *
     * @return array Tableau avec les permissions triées
     */
    public function getSortedPerms(Collection $permissions): array
    {
        $permissions_sorted = [];

        foreach ($permissions as $id => $permission) {
            $explode = explode('_', $permission);
            if (count($explode) >= 2) {
                $sliced = array_slice($explode, 0, -1);
                $name = implode('_', $sliced);
                $action = $explode[count($explode) - 1];
            } else {
                $name = $explode[0];
                $action = $name;
            }
            $actionTab = [$id, $action];
            if (! isset($permissions_sorted[$name])) {
                $permissions_sorted[$name] = ['name' => $name, 'actions' => []];
            }
            array_push($permissions_sorted[$name]['actions'], $actionTab);
        }

        return $permissions_sorted;
    }

    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = DB::table('roles')
            ->leftJoin('role_user', 'role_user.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.title', DB::raw('count(role_user.user_id) as count'))
            ->groupBy('roles.id', 'roles.title')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Chargement de toutes les permissions et triage
        $permissions = Permission::all()->sortBy('title')->pluck('title', 'id');
        $permissions_sorted = $this->getSortedPerms($permissions);

        return view('admin.roles.create', compact('permissions_sorted'));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Chargement de toutes les permissions et triage
        $permissions = Permission::all()->sortBy('title')->pluck('title', 'id');
        $permissions_sorted = $this->getSortedPerms($permissions);
        // Chargement des permissions du rôle
        $role->load('permissions');

        return view('admin.roles.edit', compact('permissions_sorted', 'role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->sortBy('title')->pluck('title', 'id');
        $permissions_sorted = $this->getSortedPerms($permissions);
        // Chargement des permissions du rôle
        $role->load('permissions');

        return view('admin.roles.show', compact('permissions_sorted', 'role'));
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Role is used
        abort_if($role->users()->count() > 0, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return redirect()->route('admin.roles.index');
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
