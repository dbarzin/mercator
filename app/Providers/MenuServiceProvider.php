<?php

namespace App\Providers;

use App\Menus\MenuRegistry;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MenuRegistry::class, function () {
            return new MenuRegistry();
        });
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        // Definition of core sections
        $registry = $this->app->make(MenuRegistry::class);

        $registry->addSection('configuration', 'Configuration');
        $registry->addSection('options', 'Options');
        $registry->addSection('tools', 'Tools');
        $registry->addSection('help', 'Help');
    }
}
