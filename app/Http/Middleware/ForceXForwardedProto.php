<?php


namespace App\Http\Middleware;

use Closure;

class ForceXForwardedProto
{
    public function handle($request, Closure $next)
    {
        if ($request->header('X-Forwarded-Proto') === 'https') {
            $request->server->set('HTTPS', 'on');
        } else {
            $request->server->set('HTTPS', 'off');
        }

        return $next($request);
    }
}
