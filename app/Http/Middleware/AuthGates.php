<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        // ⚠️ Ici, Auth::user() ne doit plus taper la DB
        // grâce à ton middleware UseCachedAuthUser placé AVANT dans Kernel.php
        $user = Auth::user();

        if ($user) {
            // 🧠 On met en cache le mapping permission → [roles_ids]
            $permissionsArray = Cache::rememberForever('permissions_roles_map', function () {
                $roles = Role::with('permissions')->get();
                $map = [];

                foreach ($roles as $role) {
                    foreach ($role->permissions as $permission) {
                        $map[$permission->title][] = $role->id;
                    }
                }

                return $map;
            });

            foreach ($permissionsArray as $title => $roles) {
                Gate::define($title, function (User $user) use ($roles) {
                    // on reste sur ta logique actuelle
                    return $user->roles
                        ->pluck('id')
                        ->intersect($roles)
                        ->isNotEmpty();
                });
            }
        }

        return $next($request);
    }
}
