<?php

namespace App\Console\Commands;

use App\Modules\ModuleDiscovery;
use App\Modules\ModuleRegistry;
use Illuminate\Console\Command;

class MercatorModuleInstall extends Command
{
    protected $signature = 'mercator:module:install {name} {--no-migrate}';
    protected $description = 'Install and activate a Mercator module (DB + migrations)';

    public function handle(
        ModuleDiscovery $discovery,
        ModuleRegistry $registry
    ): int {
        $name = $this->argument('name');

        $meta = $discovery->getMeta($name);
        if (!$meta) {
            $this->error("Module '{$name}' not found in Composer packages.");
            $this->line("Ensure you have done the following: compose require <vendor>/<package>");
            return self::FAILURE;
        }

        if ($registry->exists($name)) {
            $this->warn("Module '{$name}' already registered in the database. I will reactivate it and update the metadata.");
        }

        // Enregistre/active dans la DB
        $registry->install($name, $meta);

        $this->info("Module '{$name}' enregistré et activé en DB.");

        if (!$this->option('no-migrate')) {
            $this->info("Launch of migrations (all modules and core).");
            $this->call('migrate', ['--force' => true]);
        }

        $this->info("Module '{$name}' installed.");
        return self::SUCCESS;
    }
}

