<?php

// app/Http/Middleware/SecurityHeaders.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Security-Policy', $this->buildCsp());

        return $response;
    }

    private function buildCsp(): string
    {
        $directives = [
            "default-src 'self'",

            // Scripts : soi-même uniquement
            // 'unsafe-inline' est nécessaire si tu as des <script> inline dans les Blades
            // À terme, viser à les supprimer pour retirer unsafe-inline
            "script-src 'self' 'unsafe-inline'",

            // Styles : soi-même + inline (CKEditor en a besoin)
            "style-src 'self' 'unsafe-inline'",

            // Images : soi-même + data: (pour les images base64 éventuelles)
            "img-src 'self' data:",

            // Fontes
            "font-src 'self'",

            // Connexions AJAX/fetch : soi-même uniquement
            "connect-src 'self'",

            // Interdit les iframes externes
            "frame-src 'none'",

            // Interdit les objets Flash/plugins
            "object-src 'none'",

            // Bloque les workers non autorisés
            "worker-src 'self' blob:",

            // Prevents this page from being embedded in foreign frames (modern replacement for X-Frame-Options)
            "frame-ancestors 'self'",

            // Restricts form submissions to same origin
            "form-action 'self'",
            ];

        return implode('; ', $directives);
    }
}