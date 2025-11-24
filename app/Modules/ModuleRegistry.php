<?php

namespace App\Modules;

use Illuminate\Support\Facades\DB;

class ModuleRegistry
{
    /** @var array<string, object> */
    protected array $modules = [];

    public function __construct()
    {
        $this->reload();
    }

    protected function reload(): void
    {
        $this->modules = DB::table('mercator_modules')
            ->get()
            ->keyBy('name')
            ->all(); // ->all() pour avoir un array normal
    }

    /**
     * Retourne tous les modules connus en DB.
     *
     * @return array<string, object>
     */
    public function all(): array
    {
        return $this->modules;
    }

    public function isEnabled(string $name): bool
    {
        return isset($this->modules[$name]) && (bool) $this->modules[$name]->enabled;
    }

    public function exists(string $name): bool
    {
        return isset($this->modules[$name]);
    }

    public function install(string $name, array $meta): void
    {
        DB::table('mercator_modules')->updateOrInsert(
            ['name' => $name],
            [
                'label'      => $meta['label'] ?? $name,
                'version'    => $meta['version'] ?? '0.0.0',
                'enabled'    => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->reload();
    }

    public function enable(string $name): void
    {
        DB::table('mercator_modules')
            ->where('name', $name)
            ->update([
                'enabled'    => true,
                'updated_at' => now(),
            ]);

        $this->reload();
    }

    public function disable(string $name): void
    {
        DB::table('mercator_modules')
            ->where('name', $name)
            ->update([
                'enabled'    => false,
                'updated_at' => now(),
            ]);

        $this->reload();
    }

    public function uninstall(string $name): void
    {
        DB::table('mercator_modules')
            ->where('name', $name)
            ->delete();

        $this->reload();
    }
}
