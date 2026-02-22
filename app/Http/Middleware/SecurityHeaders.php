<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Random\RandomException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityHeaders
{
    /**
     * @throws RandomException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // TODO : enable CSP

        // Le nonce doit être blindé AVANT $next() car la vue est rendue dans $next()
        // $nonce = base64_encode(random_bytes(16));
        // app()->instance('csp-nonce', $nonce);

        // Transmettre le même nonce au plugin Vite
        // → il l'ajoutera automatiquement sur les <script> et <link> qu'il génère
        // Vite::useCspNonce($nonce);

        $response = $next($request);

        // Les réponses binaires et streams ne supportent pas l'ajout de headers CSP
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Phase de test : Report-Only observe sans bloquer
        // $response->headers->set('Content-Security-Policy-Report-Only', $this->buildCsp($nonce));

        // Basculer vers 'Content-Security-Policy' une fois zéro violation dans les logs
        // $response->headers->set('Content-Security-Policy', $this->buildCsp($nonce));

        return $response;
    }

    private function buildCsp(string $nonce): string
    {
        $isDev = app()->environment('local');

        // Origines Vite — IPv4 ET IPv6, sans ws:// ici
        $viteOrigins = $isDev
            ? 'http://localhost:5173'
            : '';

        // WebSocket HMR — uniquement pour connect-src
        $viteWs = $isDev
            ? 'ws://localhost:5173'
            : '';

        $directives = [
            "default-src 'self'",

            // Le hash couvre le script inline HMR de Vite que Vite::useCspNonce() ne peut pas nonce-ifier
            "script-src 'self' 'nonce-{$nonce}'"
            . ($viteOrigins ? " {$viteOrigins}" : '')
            ,

            // Vite sert les CSS depuis son serveur dev
            "style-src 'self' 'unsafe-inline'"
            . ($viteOrigins ? " {$viteOrigins}" : ''),

            // Bootstrap Icons woff2 servi par Vite en dev
            "font-src 'self'"
            . ($viteOrigins ? " {$viteOrigins}" : ''),

            // WebSocket HMR uniquement ici
            "connect-src 'self'"
            . ($viteOrigins ? " {$viteOrigins}" : '')
            . ($viteWs      ? " {$viteWs}"      : ''),

            "img-src 'self' data:",
            "frame-src 'none'",
            "object-src 'none'",
            "worker-src 'self' blob:",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        return implode('; ', $directives);
    }

}
