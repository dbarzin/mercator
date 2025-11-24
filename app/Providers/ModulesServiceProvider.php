<?php

namespace App\Providers;

use App\Modules\ModuleRegistry;
use Illuminate\Support\ServiceProvider;

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
