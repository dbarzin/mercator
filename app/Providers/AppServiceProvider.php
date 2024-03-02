<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Ignore default migration from here
        // Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        // Log SQL queries
        // if (false) {
        if (env('APP_DEBUG')) {
            \DB::listen(function ($query) {
                \Log::info($query->time . ':' . $query->sql);
            });
        }
    }
}
