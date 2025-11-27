<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mercator\Core\Modules\ModuleRegistry;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleRegistry::class, function () {
            return new ModuleRegistry();
        });
    }

    public function boot(): void
    {
        //
    }
}
