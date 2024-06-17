<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use App\Services\V3\KeycloakService as KeycloakSocialiteProvider;
use Illuminate\Support\Facades\Log;

class KeycloakProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
            Socialite::extend('keycloak', function ($app) {
                $config = $app['config']['services.keycloak'];
                return Socialite::buildProvider(KeycloakSocialiteProvider::class, $config);
            });
        
    }
}