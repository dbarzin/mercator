<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if (env('APP_DEBUG')) {
            // Log SQL queries
            \DB::listen(function ($query) {
                \Log::info($query->time . ':' . $query->sql);
            });
        }
    }
}
