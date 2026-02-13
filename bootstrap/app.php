<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Active les sessions partagées entre web et API
        $middleware->statefulApi();

        // Middleware globaux
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // Middlewares spécifiques au groupe 'web'
        $middleware->web(append: [
            \App\Http\Middleware\ForceXForwardedProto::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\UseCachedAuthUser::class,
            // \App\Http\Middleware\AuthGates::class,  // ✅ Actif pour la sécurité
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\LicenseWarning::class,
        ]);

        $middleware->api(prepend: [
            "throttle:api",
        ]);

        $middleware->api(append: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\UseCachedAuthUser::class,
        ]);

        // Alias de middlewares
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'auth.multi' => \App\Http\Middleware\AuthenticateApiOrWeb::class,
            'gates' => \App\Http\Middleware\AuthGates::class,  // ✅ Alias pour utilisation manuelle
        ]);

        // Groupes de middlewares personnalisés
        $middleware->appendToGroup('api.protected', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'auth.multi',
            'gates',
        ]);

        $middleware->appendToGroup('web.protected', [
            'auth',
            'gates',
        ]);
        
        // Configurer les trusted proxies
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();