<?php

namespace App\Modules;

use Illuminate\Support\Arr;

class ModuleDiscovery
{
    protected string $installedPath;

    protected ?array $cache = null;

    public function __construct(?string $installedPath = null)
    {
        $this->installedPath = $installedPath
            ?? base_path('vendor/composer/installed.json');
    }

    /**
     * Retourne la liste de tous les modules déclarés dans les packages Composer.
     *
     * @return array<string, array{package:string,name:string,label:string,version:string}>
     */
    public function discover(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

            if (!file_exists($this->installedPath)) {
            return [];
        }

        // $content = json_decode(file_get_contents($this->installedPath), true);
        $content = @file_get_contents($this->installedPath);
        if ($content === false) {
            return [];
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return [];
        }

        // Composer 2 peut mettre les packages dans "packages"
        $packages = $decoded['packages'] ?? $decoded;

        $modules = [];

        foreach ($packages as $pkg) {
            $extra = Arr::get($pkg, 'extra', []);

            if (!isset($extra['mercator-module'])) {
                continue;
            }

            $meta = $extra['mercator-module'];

            $name = $meta['name'] ?? null;
            if (!$name) {
                continue;
            }

            $modules[$name] = [
                'package' => $pkg['name'] ?? 'unknown',
                'name'    => $name,
                'label'   => $meta['label'] ?? $name,
                'version' => $meta['version'] ?? ($pkg['version'] ?? '0.0.0'),
            ];
        }

        return $this->cache = $modules;
    }

    /**
     * Retourne les métadonnées d’un module donné (ou null).
     */
    public function getMeta(string $name): ?array
    {
        $modules = $this->discover();

        return $modules[$name] ?? null;
    }
}
