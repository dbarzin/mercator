<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mercator\Core\Modules\ModuleRegistry;

class MercatorModuleDisable extends Command
{
    protected $signature = 'mercator:module:disable {name}';
    protected $description = 'Disables a Mercator module (data remains)';

    public function handle(ModuleRegistry $registry): int
    {
        $name = $this->argument('name');

        if (!$registry->exists($name)) {
            $this->error("Module '{$name}' not found in DB.");
            return self::FAILURE;
        }

        $registry->disable($name);

        $this->info("Module '{$name}' disabled (code still present, no routes/menus).");
        return self::SUCCESS;
    }
}

