<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateApiOrWeb
{
    public function handle($request, Closure $next)
    {
        // Essayer de charger l'utilisateur depuis la session manuellement
        if ($request->hasSession()) {
            $userId = $request->session()->get('login_web_' . sha1(static::class));

            // Si pas trouvé avec le hash de classe, essayer avec le guard name
            if (!$userId) {
                $userId = $request->session()->get('login_web_' . sha1('Illuminate\Auth\SessionGuard'));
            }

        }

        // Essayer web (session) d'abord
        if (Auth::guard('web')->check()) {
            Auth::shouldUse('web');
            return $next($request);
        }

        // Puis api (Passport token)
        if (Auth::guard('api')->check()) {
            Auth::shouldUse('api');
            return $next($request);
        }

        abort(401, 'Unauthenticated');
    }
}