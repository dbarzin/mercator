<?php

namespace App\Console\Commands;

use App\Modules\ModuleRegistry;
use Illuminate\Console\Command;

class MercatorModuleEnable extends Command
{
    protected $signature = 'mercator:module:enable {name}';
    protected $description = 'Activates an already installed Mercator module';

    public function handle(ModuleRegistry $registry): int
    {
        $name = $this->argument('name');

        if (!$registry->exists($name)) {
            $this->error("Module '{$name}' not found in DB. Use mercator:module:install first.");
            return self::FAILURE;
        }

        $registry->enable($name);

        $this->info("Module '{$name}' activated.");
        return self::SUCCESS;
    }
}

