<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
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

        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        if (false && config('app.debug')) {
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
            $version = trim(file_get_contents(base_path('version.txt')));
            $view->with('appVersion', $version);
            // Get the menu
            $view->with('menu', app(MenuRegistry::class));
        });
    }
}
