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
use Mercator\Core\Menus\MenuRegistry;

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

        view()->composer('*', function ($view): void {
            // Get the current version of the application
            $version = '0.0.0'; // default
            $versionFile = base_path('version.txt');
            if (file_exists($versionFile) && is_readable($versionFile)) {
                $version = trim(file_get_contents($versionFile));
                }
            $view->with('appVersion', $version);
            // Get the menu
            $view->with('menu', app(MenuRegistry::class));
        });

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
