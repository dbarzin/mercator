<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Mercator\Core\Modules\ModuleDiscovery;
use Mercator\Core\Modules\ModuleRegistry;

class ModuleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:module_manage');
    }

    public function index(
        ModuleDiscovery $discovery,
        ModuleRegistry $registry
    ) {
        // Modules vus via Composer
        $discovered = $discovery->discover();
        // ex: [ 'dummy' => [ 'name'=>..., 'package'=>..., ...], ... ]

        // Modules enregistrés en DB (probablement des stdClass)
        $installed = collect($registry->all())
            ->keyBy('name'); // on indexe par "name" pour lookup facile

        // On fusionne les infos pour la vue
        $modules = collect($discovered)->map(function ($meta, string $name) use ($installed) {
            // $meta peut être array ou stdClass selon ton implémentation
            if (is_object($meta)) {
                $meta = (array) $meta;
            }

            // On récupère le module en DB (stdClass ou null)
            $db = $installed->get($name);

            return [
                'name'       => $meta['name']   ?? $name,
                'label'      => $meta['label']  ?? $name,
                'package'    => $meta['package'] ?? null,
                'version'    => $meta['version'] ?? null,

                'installed'  => $db !== null,
                'enabled'    => $db?->enabled ?? false,
                'db_version' => $db?->version ?? null,
            ];
        })->values();

        return view('modules', compact('modules'));
    }

    public function install(
        string $name,
        ModuleDiscovery $discovery,
        ModuleRegistry $registry
    ): RedirectResponse {
        $meta = $discovery->findByNameOrPackage($name);

        if (!$meta) {
            return back()->with('error', "Module '{$name}' introuvable.");
        }

        if (!$meta) {
            return back()->with('error', "Module '{$name}' introuvable.");
        }
        
        if (!isset($meta['name'])) {
            return back()->with('error', "Métadonnées de module invalides.");
        }

        $registry->install($meta['name'], $meta);

        return back()->with('success', "Module '{$meta['name']}' installé.");
    }

    public function enable(string $name, ModuleRegistry $registry): RedirectResponse
    {
        if (!$registry->exists($name)) {
            return back()->with('error', "Module '{$name}' non trouvé en base.");
        }

        $registry->enable($name);

        return back()->with('success', "Module '{$name}' activé.");
    }

    public function disable(string $name, ModuleRegistry $registry): RedirectResponse
    {
        if (!$registry->exists($name)) {
            return back()->with('error', "Module '{$name}' non trouvé en base.");
        }

        $registry->disable($name);

        return back()->with('success', "Module '{$name}' désactivé.");
    }

    public function uninstall(string $name, ModuleRegistry $registry): RedirectResponse
    {
        if (!$registry->exists($name)) {
            return back()->with('error', "Module '{$name}' non trouvé en base.");
        }

        $registry->uninstall($name);

        return back()->with('success', "Module '{$name}' désinstallé.");
    }
}
