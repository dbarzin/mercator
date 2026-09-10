<?php

namespace App\Services\Graph;

use App\Models\Application;
use App\Models\ApplicationFlow;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Cartographer;
use App\Models\Database;
use Illuminate\Support\Collection;

class ApplicationFlowGraphBuilder
{
    /**
     * @param  Collection<int, Application>  $applications
     * @param  Collection<int, ApplicationService>  $applicationServices
     * @param  Collection<int, ApplicationModule>  $applicationModules
     * @param  Collection<int, Database>  $databases
     * @param  Collection<int, ApplicationFlow>  $flows
     * @param  array{withHref?: bool, iconResolver?: callable(?int, string): string}  $options
     */
    public function buildDot(
        Collection $applications,
        Collection $applicationServices,
        Collection $applicationModules,
        Collection $databases,
        Collection $flows,
        array $options = []
    ): string {
        $withHref = $options['withHref'] ?? true;
        $iconResolver = $options['iconResolver'] ?? fn (?int $iconId, string $fallback) => $iconId === null
            ? $fallback
            : route('admin.documents.show', $iconId);

        $lines = ['digraph  {'];

        if (Cartographer::canAccess(Application::class)) {
            foreach ($applications as $application) {
                $image = $iconResolver($application->icon_id, '/images/application.png');
                $lines[] = DotNode::withImage('A'.$application->id, $image, [e($application->name)], $this->href($application, $withHref));
            }
        }

        if (Cartographer::canAccess(ApplicationService::class)) {
            foreach ($applicationServices as $service) {
                $lines[] = DotNode::withImage('S'.$service->id, $iconResolver(null, '/images/applicationservice.png'), [e($service->name)], $this->href($service, $withHref));
            }
        }

        if (Cartographer::canAccess(ApplicationModule::class)) {
            foreach ($applicationModules as $module) {
                $lines[] = DotNode::withImage('M'.$module->id, $iconResolver(null, '/images/applicationmodule.png'), [e($module->name)], $this->href($module, $withHref));
            }
        }

        if (Cartographer::canAccess(Database::class)) {
            foreach ($databases as $database) {
                $lines[] = DotNode::withImage('DB'.$database->id, $iconResolver(null, '/images/database.png'), [e($database->name)], $this->href($database, $withHref));
            }
        }

        if (Cartographer::canAccess(ApplicationFlow::class)) {
            foreach ($flows as $flow) {
                $source = $this->endpointNode($flow->database_source_id, $flow->module_source_id, $flow->service_source_id, $flow->application_source_id);
                $dest = $this->endpointNode($flow->database_dest_id, $flow->module_dest_id, $flow->service_dest_id, $flow->application_dest_id);

                if ($source === null || $dest === null) {
                    continue;
                }

                $edge = $source.' -> '.$dest.' [ label="'.e($flow->type).'"';
                if ($flow->bidirectional) {
                    $edge .= ' dir="both"';
                }
                $edge .= $withHref ? ' href="#'.$flow->getUID().'"]' : ']';
                $lines[] = $edge;
            }
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    /**
     * @param  Collection<int, Application>  $applications
     * @return array<int, array{path: string, width: string, height: string}>
     */
    public function imageManifest(Collection $applications): array
    {
        $manifest = [
            ['path' => '/images/application.png', 'width' => '64px', 'height' => '64px'],
        ];

        foreach ($applications as $application) {
            if ($application->icon_id !== null) {
                $manifest[] = ['path' => route('admin.documents.show', $application->icon_id), 'width' => '64px', 'height' => '64px'];
            }
        }

        $manifest[] = ['path' => '/images/applicationservice.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/applicationmodule.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/database.png', 'width' => '64px', 'height' => '64px'];

        return $manifest;
    }

    private function endpointNode(?int $databaseId, ?int $moduleId, ?int $serviceId, ?int $applicationId): ?string
    {
        if ($databaseId !== null) {
            return 'DB'.$databaseId;
        }
        if ($moduleId !== null) {
            return 'M'.$moduleId;
        }
        if ($serviceId !== null) {
            return 'S'.$serviceId;
        }
        if ($applicationId !== null) {
            return 'A'.$applicationId;
        }

        return null;
    }

    private function href(Application|ApplicationService|ApplicationModule|Database $model, bool $withHref): string
    {
        return $withHref ? ' href="#'.$model->getUID().'"' : '';
    }
}
