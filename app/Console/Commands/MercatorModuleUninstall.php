<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mercator\Core\Modules\ModuleRegistry;

class MercatorModuleUninstall extends Command
{
    protected $signature = 'mercator:module:uninstall {name} {--force}';
    protected $description = 'Uninstalls a Mercator module (deletes its entry in the database)';

    public function handle(ModuleRegistry $registry): int
    {
        $name = $this->argument('name');

        if (!$registry->exists($name)) {
            $this->error("Module '{$name}' not found in DB.");
            return self::FAILURE;
        }

        if (!$this->option('force') &&
            !$this->confirm("Are you sure you want to uninstall the '{$name}' module? The data in the database will remain, but it will be completely disconnected from Mercator.")
        ) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        $registry->uninstall($name);

        $this->info("Module '{$name}' uninstalled on the Mercator side.");
        $this->line("The Composer package is probably still present. Remember to do:");
        $this->line("   composer remove <vendor>/<package>");

        return self::SUCCESS;
    }
}

