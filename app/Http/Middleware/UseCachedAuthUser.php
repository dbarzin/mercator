<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UseCachedAuthUser
{
    public function handle($request, Closure $next)
    {
        // ⚠️ NE PAS APPELER Auth::user() / Auth::check(), ça déclencherait la requête SQL
        if (session()->has('auth_user')) {
            Auth::setUser(session('auth_user'));
        }

        return $next($request);
    }
}
