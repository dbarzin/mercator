<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Mercator\Core\Modules\ModuleDiscovery;
use Mercator\Core\Modules\ModuleRegistry;
use Mercator\Core\Services\LicenseService;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:module_manage');
    }

    public function index(
        ModuleDiscovery $discovery,
        ModuleRegistry $registry
    ) {
        // Get license service
        $licenseService = app(LicenseService::class);
        // get the installed license
        $license = $licenseService->getLicenseInfo();

        if ($license['status'] === 'expiring_soon') {
            $message = "License is about to expire";
        }
        else
            $message = "";


        // Modules vus via Composer
        $discovered = $discovery->discover();

        // Modules enregistrés en DB (probablement des stdClass)
        $installed = collect($registry->all())
            ->keyBy('name'); // on indexe par "name" pour lookup facile

        // On fusionne les infos pour la vue
        $modules = collect($discovered)->map(function (array $meta, string $name) use ($installed) {

            // On récupère le module en DB (stdClass ou null)
            /** @var object|null $db */
            $db = $installed->get($name);

            $installedFlag = $db !== null;

            return [
                'name'       => $meta['name'],
                'label'      => $meta['label'],
                'package'    => $meta['package'],
                'version'    => $meta['version'],

                'installed'  => $installedFlag,
                'enabled'    => $installedFlag
                    ? ($db->enabled ?? false)
                    : false,
                'db_version' => $installedFlag
                    ? ($db->version ?? null)
                    : null,
            ];
        })->values();

        return view('modules',
            compact('license','modules'))
            ->with('message',$message);
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

    public function check(): RedirectResponse
    {
        // Get license service
        $licenseService = app(LicenseService::class);
        $errors = collect();

        // Validation avec le serveur de licences
        if ($licenseService->validateWithServer()) {
            $message = ('License has been validated with the Licence server.');

        } else {
            $message = "";
            $errors->add("License could not be verified with the Licence server !");
        }

        return back()
            ->with('message', $message)
            ->with('errors', $errors);
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
