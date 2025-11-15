<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UseCachedAuthUser
{
    public function handle($request, Closure $next) : mixed
    {
        // Warning: Do not call Auth::user() / Auth::check() as it triggers SQL queries
        if (session()->has('auth_user')) {
            Auth::setUser(session('auth_user'));
        }

        return $next($request);
    }
}
