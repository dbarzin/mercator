<?php

namespace App\Console\Commands;

use App\Modules\ModuleDiscovery;
use App\Modules\ModuleRegistry;
use Illuminate\Console\Command;

class MercatorModulesList extends Command
{
    protected $signature = 'mercator:modules:list';
    protected $description = 'List of available Mercator modules and their status';

    public function handle(ModuleDiscovery $discovery, ModuleRegistry $registry): int
    {
        $discovered = $discovery->discover();
        $registered = $registry->all();

        $rows = [];

        foreach ($discovered as $name => $meta) {
            $inDb   = $registered[$name] ?? null;
            $status = 'not installed';
            $enabled = '-';

            if ($inDb) {
                $status  = 'installed';
                $enabled = $inDb->enabled ? 'yes' : 'no';
            }

            $rows[] = [
                'name'     => $name,
                'label'    => $meta['label'],
                'package'  => $meta['package'],
                'version'  => $meta['version'],
                'status'   => $status,
                'enabled'  => $enabled,
            ];
        }

        // Ajouter aussi les modules enregistrés en DB mais plus dans Composer (dead)
        foreach ($registered as $name => $inDb) {
            if (isset($discovered[$name])) {
                continue;
            }
            $rows[] = [
                'name'     => $name,
                'label'    => $inDb->label,
                'package'  => '(missing)',
                'version'  => $inDb->version,
                'status'   => 'in DB only',
                'enabled'  => $inDb->enabled ? 'yes' : 'no',
            ];
        }

        $this->table(
            ['Name', 'Label', 'Package', 'Version', 'Status', 'Enabled'],
            $rows
        );

        return self::SUCCESS;
    }
}

