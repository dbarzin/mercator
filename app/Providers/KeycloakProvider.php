<?php


namespace App\Providers;

use App\Services\KeycloakProviderService as KeycloakSocialiteProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class KeycloakProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        Socialite::extend('keycloak', function ($app) {
            $config = $app['config']['services.keycloak'];

            return Socialite::buildProvider(KeycloakSocialiteProvider::class, $config);
        });
    }
}
