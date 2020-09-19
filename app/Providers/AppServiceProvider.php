<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {        
        Paginator::useBootstrap();
        /*
        if(env('APP_DEBUG')) {
            DB::listen(function($query) {
                \Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });    
        }
        */ 
    }
}
