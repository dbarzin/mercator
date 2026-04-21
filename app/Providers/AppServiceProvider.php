<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Container;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ignore default migration from here
        // Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Get version from file
        $versionFile = base_path('version.txt');
        $version = file_exists($versionFile) ? trim(file_get_contents($versionFile)) : '0.0.0';
        $this->app->instance('mercator.version', $version);

        // start Paginator
        Paginator::useBootstrap();

        // Enregistrer les vues avec un namespace
        // Pour pouvoir les réutiliser dans les autres packages
        $this->loadViewsFrom(resource_path('views'), 'mercator');

        // Force HTTPS:
        // null  => default: force HTTPS only in production
        // true  => always force HTTPS (all environments)
        // false => never force HTTPS
        $forceHttps = config('app.force_https');
        if ($forceHttps === true || ($forceHttps === null && App::environment('production'))) {
            URL::forceScheme('https');
        }

        if (config('app.db_trace')) {
            \Log::info('DB Trace Enabled');
            // Log SQL Queries
            \DB::listen(function ($query): void {
                \Log::info($query->time.':'.$query->sql);
            });
        }

        // Si le logging LDAP est activé, on force l’utilisation du channel "ldap"
        if (config('ldap.logging.enabled')) {
            Container::setLogger(
                \Log::channel(config('ldap.logging.channel'))
            );
        }

        // Rate limiter
        RateLimiter::for('api', function (Request $request) {
            // Pas de limite pour les admins
            if ($request->user()?->isAdmin()) {
                return Limit::none();
            }

            $limit = (int) config('api.rate_limit', 60);
            $decay = (int) config('api.rate_limit_decay', 1);

            return Limit::perMinutes($decay, $limit)
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}
