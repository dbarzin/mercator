<?php


namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(\Illuminate\Http\Request $request): ?string
    {
        if (! $request->expectsJson()) {
            return route('login', ['locale' => app()->getLocale()]);
        }

        return null;
    }
}
