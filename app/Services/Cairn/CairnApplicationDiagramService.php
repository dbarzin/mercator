<?php

namespace App\Services\Cairn;

use App\Models\Application;
use App\Models\ApplicationBlock;
use App\Models\ApplicationFlow;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Cartographer;
use App\Models\Database;
use App\Models\Entity;
use Illuminate\Support\Collection;

/**
 * Résout une sélection d'objets Mercator (entité, application, service, module, base de
 * données, flux) en un diagramme Cairn (vue `application`) et génère le source DSL `.cairn`
 * correspondant. Aucun rendu SVG n'est effectué ici (voir Phase 3 / `@r0kshan/cairn`).
 */
class CairnApplicationDiagramService
{
    private const DIAGRAM_TITLE = 'Cairn - vue applicative';

    private const BLOCK_FILL_COLOR = '#FFF9C4';

    private const BLOCK_STROKE_COLOR = '#C9A227';

    private const BLOCK_LEGEND_NOTE = 'Le cadre jaune représente un groupe applicatif (bloc).';

    // Gris neutre, volontairement distinct du jaune des groupes ApplicationBlock ci-dessus :
    // un orphelin (service/module sans application propriétaire unique dans le graphe) n'est
    // pas un vrai groupement, la couleur ne doit donc pas être confondue avec BLOCK_FILL_COLOR.
    private const ORPHAN_FILL_COLOR = '#E8E8E8';

    private const ORPHAN_STROKE_COLOR = '#8A8A8A';

    /**
     * @param  array<int, array{type: string, id: int}>  $selection
     */
    public function build(array $selection): ?string
    {
        if ($selection === []) {
            return null;
        }

        $entityIds = $this->idsOfType($selection, 'entity');
        $applicationIds = $this->idsOfType($selection, 'application');
        $serviceIds = $this->idsOfType($selection, 'service');
        $moduleIds = $this->idsOfType($selection, 'module');
        $databaseIds = $this->idsOfType($selection, 'database');
        $fluxIds = $this->idsOfType($selection, 'flux');

        $entities = $entityIds === [] ? collect() : Cartographer::scopedQuery(Entity::query())
            ->select(['id', 'name'])
            ->whereIn('id', $entityIds)
            ->with(['applications:id'])
            ->get();

        // --- expansion en graines (§4) ---
        $appSeedIds = collect($applicationIds)
            ->concat($entities->flatMap(fn (Entity $entity) => $entity->applications->pluck('id')))
            ->unique()
            ->values();

        // Les services tirés par une application graine (§4 : "l'app + ses services + les
        // modules de ces services") tirent aussi leurs modules. Un service sélectionné seul
        // reste isolé et ne tire pas ses modules (défaut modifiable) : les deux origines sont
        // donc suivies séparément.
        $svcFromAppExpansion = collect();

        if ($appSeedIds->isNotEmpty()) {
            $seedApplications = Cartographer::scopedQuery(Application::query())
                ->select(['id'])
                ->whereIn('id', $appSeedIds)
                ->with(['services:id'])
                ->get();

            $svcFromAppExpansion = $seedApplications
                ->flatMap(fn (Application $app) => $app->services->pluck('id'))
                ->unique()
                ->values();
        }

        $svcSeedIds = collect($serviceIds)->concat($svcFromAppExpansion)->unique()->values();

        $modSeedIds = collect($moduleIds);

        if ($svcFromAppExpansion->isNotEmpty()) {
            $seedServices = Cartographer::scopedQuery(ApplicationService::query())
                ->select(['id'])
                ->whereIn('id', $svcFromAppExpansion)
                ->with(['modules:id'])
                ->get();

            $modSeedIds = $modSeedIds
                ->concat($seedServices->flatMap(fn (ApplicationService $svc) => $svc->modules->pluck('id')))
                ->unique()
                ->values();
        }

        $dbSeedIds = collect($databaseIds)->unique()->values();
        $fluxSelectedIds = collect($fluxIds)->unique()->values();

        // --- collecte des flux (requête unique, §4) ---
        $flows = $this->collectFlows($appSeedIds, $svcSeedIds, $modSeedIds, $dbSeedIds, $fluxSelectedIds);

        // --- registre des nœuds dessinés = graines + terminaux ---
        $drawnAppIds = $appSeedIds->all();
        $drawnSvcIds = $svcSeedIds->all();
        $drawnModIds = $modSeedIds->all();
        $drawnDbIds = $dbSeedIds->all();

        foreach ($flows as $flow) {
            foreach (['source', 'dest'] as $side) {
                $endpoint = $this->endpoint($flow, $side);

                if ($endpoint === null) {
                    continue;
                }

                [$type, $id] = $endpoint;
                match ($type) {
                    'application' => $drawnAppIds[] = $id,
                    'service' => $drawnSvcIds[] = $id,
                    'module' => $drawnModIds[] = $id,
                    'database' => $drawnDbIds[] = $id,
                    default => null,
                };
            }
        }

        $drawnAppIds = collect($drawnAppIds)->unique()->values();
        $drawnSvcIds = collect($drawnSvcIds)->unique()->values();
        $drawnModIds = collect($drawnModIds)->unique()->values();
        $drawnDbIds = collect($drawnDbIds)->unique()->values();

        // --- chargement batch des modèles dessinés (§ requêtes économes) ---
        // Requêtes scopées par cartographe : un id présent dans les graines/terminaux mais
        // inexistant ou hors périmètre ressort simplement absent des collections ci-dessous.
        $applications = $drawnAppIds->isEmpty() ? collect() : Cartographer::scopedQuery(Application::query())
            ->select(['id', 'name', 'external', 'application_block_id'])
            ->whereIn('id', $drawnAppIds)
            ->get()
            ->keyBy('id');

        $services = $drawnSvcIds->isEmpty() ? collect() : Cartographer::scopedQuery(ApplicationService::query())
            ->select(['id', 'name'])
            ->whereIn('id', $drawnSvcIds)
            ->with(['applications:id'])
            ->get()
            ->sortBy(fn (ApplicationService $s) => mb_strtolower($s->name))
            ->values();

        $modules = $drawnModIds->isEmpty() ? collect() : Cartographer::scopedQuery(ApplicationModule::query())
            ->select(['id', 'name'])
            ->whereIn('id', $drawnModIds)
            ->with(['applicationServices.applications:id'])
            ->get()
            ->sortBy(fn (ApplicationModule $m) => mb_strtolower($m->name))
            ->values();

        $databases = $drawnDbIds->isEmpty() ? collect() : Cartographer::scopedQuery(Database::query())
            ->select(['id', 'name', 'external'])
            ->whereIn('id', $drawnDbIds)
            ->get()
            ->sortBy(fn (Database $d) => mb_strtolower($d->name))
            ->values();

        $entities = $entities->sortBy(fn (Entity $e) => mb_strtolower($e->name))->values();

        // L'ensemble dessiné ne peut être connu qu'une fois les modèles réellement résolus
        // (un id peut être inexistant ou hors périmètre du cartographe) : sinon un DSL ne
        // contenant que l'en-tête et le style serait généré pour rien.
        if ($applications->isEmpty() && $services->isEmpty() && $modules->isEmpty()
            && $databases->isEmpty() && $entities->isEmpty()) {
            return null;
        }

        // --- résolution des conteneurs (fermeture, §4) ---
        // $containers[containerId] = ['model' => Application, 'children' => [[type, model], ...]]
        // Un service/module dont l'application propriétaire n'est pas unique et présente
        // dans le graphe n'entre dans aucun conteneur : il devient sa propre boîte `application`
        // de niveau racine (§ placement services/modules — jamais un `module` nu, interdit par
        // Cairn : E0213 "module outside any application").
        $containers = [];
        $orphanLines = [];
        $orphanCount = 0;

        foreach ($applications->sortBy(fn (Application $a) => mb_strtolower($a->name)) as $application) {
            $containers['APP_'.$application->id] = ['model' => $application, 'children' => []];
        }

        foreach ($services as $service) {
            $parentAppIds = $service->applications->pluck('id');
            $containerId = $this->resolveParentContainerId($parentAppIds, $applications);

            if ($containerId === null) {
                array_push($orphanLines, ...$this->renderOrphanNode('APPSERV_'.$service->id, $service->name));
                $orphanCount++;

                continue;
            }

            $containers[$containerId]['children'][] = ['type' => 'service', 'model' => $service];
        }

        foreach ($modules as $module) {
            $parentAppIds = $module->applicationServices
                ->flatMap(fn (ApplicationService $svc) => $svc->applications->pluck('id'))
                ->unique();
            $containerId = $this->resolveParentContainerId($parentAppIds, $applications);

            if ($containerId === null) {
                array_push($orphanLines, ...$this->renderOrphanNode('MOD_'.$module->id, $module->name));
                $orphanCount++;

                continue;
            }

            $containers[$containerId]['children'][] = ['type' => 'module', 'model' => $module];
        }

        // --- groupes applicatifs (ApplicationBlock) : chaque application appartenant à un
        // bloc est nichée dans un conteneur portant le nom de ce bloc ---
        $blockIds = $applications->pluck('application_block_id')->filter()->unique()->values();

        $applicationBlocks = $blockIds->isEmpty() ? collect() : Cartographer::scopedQuery(ApplicationBlock::query())
            ->select(['id', 'name'])
            ->whereIn('id', $blockIds)
            ->get()
            ->keyBy('id');

        // --- construction du DSL (§2/§3/§5/§6) ---
        $lines = [];
        $lines[] = 'diagram application '.$this->quote(self::DIAGRAM_TITLE);
        $lines[] = '';

        // Chaque conteneur est d'abord rendu isolément, puis rangé soit dans le groupe de
        // son ApplicationBlock (si l'application en a un, réellement dessiné), soit à la
        // racine. Un bloc hors périmètre du cartographe (donc non résolu ci-dessus) fait
        // retomber ses applications à la racine plutôt que de référencer un bloc jamais déclaré.
        $rootLines = [];
        $byBlock = [];

        foreach ($containers as $containerId => $container) {
            $containerLines = $this->renderContainer($containerId, $container);
            $blockId = $container['model']->application_block_id;

            if ($blockId === null || ! $applicationBlocks->has($blockId)) {
                array_push($rootLines, ...$containerLines);

                continue;
            }

            $byBlock[$blockId] ??= [];
            array_push($byBlock[$blockId], ...$containerLines);
        }

        $hasBlockGroup = false;

        foreach ($applicationBlocks->sortBy(fn (ApplicationBlock $b) => mb_strtolower($b->name)) as $block) {
            if (! isset($byBlock[$block->id])) {
                continue;
            }

            $hasBlockGroup = true;

            $lines[] = 'application BLOCK_'.$block->id.' '.$this->label($block->name).' {';
            $lines[] = '  style { fill: '.self::BLOCK_FILL_COLOR.'  stroke: '.self::BLOCK_STROKE_COLOR.' }';
            foreach ($byBlock[$block->id] as $line) {
                $lines[] = '  '.$line;
            }
            $lines[] = '}';
        }

        array_push($lines, ...$rootLines);
        array_push($lines, ...$orphanLines);

        foreach ($databases as $database) {
            $type = $this->isExternal($database->external) ? 'external' : 'datastore';
            $lines[] = $type.' DB_'.$database->id.' '.$this->label($database->name);
        }

        foreach ($entities as $entity) {
            $lines[] = 'external ENTITY_'.$entity->id.' '.$this->label($entity->name);
        }

        $lines[] = '';

        // Un flux dont une extrémité est en dehors du périmètre effectivement dessiné (hors
        // périmètre du cartographe, supprimée, etc.) ne doit pas produire d'arête pointant
        // vers un nœud jamais déclaré dans le DSL.
        $drawnServiceIds = $services->pluck('id');
        $drawnModuleIds = $modules->pluck('id');
        $drawnDatabaseIds = $databases->pluck('id');
        $nodeIsDrawn = function (string $type, int $id) use ($applications, $drawnServiceIds, $drawnModuleIds, $drawnDatabaseIds): bool {
            return match ($type) {
                'application' => $applications->has($id),
                'service' => $drawnServiceIds->contains($id),
                'module' => $drawnModuleIds->contains($id),
                'database' => $drawnDatabaseIds->contains($id),
                default => false,
            };
        };

        foreach ($flows as $flow) {
            $source = $flow->sourceId();
            $dest = $flow->destId();

            if ($source === null || $dest === null) {
                continue;
            }

            $sourceEndpoint = $this->endpoint($flow, 'source');
            $destEndpoint = $this->endpoint($flow, 'dest');

            if ($sourceEndpoint === null || $destEndpoint === null
                || ! $nodeIsDrawn($sourceEndpoint[0], $sourceEndpoint[1])
                || ! $nodeIsDrawn($destEndpoint[0], $destEndpoint[1])) {
                continue;
            }

            $label = $this->label($flow->name !== '' ? $flow->name : ($flow->type ?? ''));
            $lines[] = $source.' -> '.$dest.' : '.$label;

            if ($flow->bidirectional) {
                $lines[] = $dest.' -> '.$source.' : '.$label;
            }
        }

        foreach ($entities as $entity) {
            foreach ($entity->applications->pluck('id') as $appId) {
                if ($applications->has($appId)) {
                    $lines[] = 'ENTITY_'.$entity->id.' -> APP_'.$appId.' : "utilise" { stroke: dashed }';
                }
            }
        }

        $legendNotes = [];

        if ($hasBlockGroup) {
            $legendNotes[] = self::BLOCK_LEGEND_NOTE;
        }

        if ($orphanCount > 0) {
            $legendNotes[] = trans('cruds.cairn.fields.orphan_legend_note');
        }

        if ($legendNotes !== []) {
            $lines[] = '';
            $lines[] = 'legend {';
            foreach ($legendNotes as $note) {
                $lines[] = '  note '.$this->label($note);
            }
            $lines[] = '}';
        }

        $lines[] = '';
        $lines[] = 'style { lang: fr }';

        return implode("\n", $lines)."\n";
    }

    /**
     * Rend un conteneur (application/external + ses modules/services imbriqués) de façon
     * isolée, sans se soucier d'un éventuel groupe ApplicationBlock englobant.
     *
     * @param  array{model: Application, children: array}  $container
     * @return array<int, string>
     */
    private function renderContainer(string $containerId, array $container): array
    {
        $model = $container['model'];
        $hasChildren = $container['children'] !== [];

        if ($this->isExternal($model->external) && ! $hasChildren) {
            return ['external '.$containerId.' '.$this->label($model->name)];
        }

        $lines = ['application '.$containerId.' '.$this->label($model->name).' {'];

        foreach ($container['children'] as $child) {
            $prefix = $child['type'] === 'service' ? 'APPSERV_' : 'MOD_';
            $lines[] = '  module '.$prefix.$child['model']->id.' '.$this->label($child['model']->name);
        }

        $lines[] = '}';

        return $lines;
    }

    /**
     * Un service/module ne rejoint le conteneur de son application parente que si cette
     * application est elle-même présente (dessinée) dans le graphe — pas seulement liée en
     * base. Une application liée mais absente du graphe ne compte pas comme parent : elle
     * n'est jamais récupérée "pour l'occasion". 0 ou plusieurs parents présents → aucun
     * conteneur : l'appelant rend alors l'enfant comme sa propre boîte de niveau racine
     * (voir `renderOrphanNode` — jamais un `module` nu, interdit par Cairn : E0213).
     *
     * @param  Collection<int, int>  $parentAppIds
     * @param  Collection<int, Application>  $applications
     */
    private function resolveParentContainerId(Collection $parentAppIds, Collection $applications): ?string
    {
        $presentParentIds = $parentAppIds->filter(fn (int $id) => $applications->has($id))->unique()->values();

        if ($presentParentIds->count() !== 1) {
            return null;
        }

        return 'APP_'.$presentParentIds->first();
    }

    /**
     * Un service/module sans application propriétaire unique présente dans le graphe devient
     * sa propre boîte `application` de niveau racine, en réutilisant son UID (`APPSERV_<id>`
     * / `MOD_<id>`) — jamais un `module` nu (Cairn : E0213 "module outside any application"),
     * jamais de bloc conteneur générique partagé.
     *
     * @return array<int, string>
     */
    private function renderOrphanNode(string $uid, string $name): array
    {
        return [
            'application '.$uid.' '.$this->label($name).' {',
            '  style { fill: '.self::ORPHAN_FILL_COLOR.'  stroke: '.self::ORPHAN_STROKE_COLOR.' }',
            '}',
        ];
    }

    /**
     * @param  Collection<int, int>  $appSeeds
     * @param  Collection<int, int>  $svcSeeds
     * @param  Collection<int, int>  $modSeeds
     * @param  Collection<int, int>  $dbSeeds
     * @param  Collection<int, int>  $fluxSelected
     * @return Collection<int, ApplicationFlow>
     */
    private function collectFlows(Collection $appSeeds, Collection $svcSeeds, Collection $modSeeds, Collection $dbSeeds, Collection $fluxSelected): Collection
    {
        if ($appSeeds->isEmpty() && $svcSeeds->isEmpty() && $modSeeds->isEmpty() && $dbSeeds->isEmpty() && $fluxSelected->isEmpty()) {
            return collect();
        }

        $flows = Cartographer::scopedQuery(ApplicationFlow::query())
            ->select([
                'id', 'name', 'type', 'bidirectional',
                'application_source_id', 'service_source_id', 'module_source_id', 'database_source_id',
                'application_dest_id', 'service_dest_id', 'module_dest_id', 'database_dest_id',
            ])
            ->where(function ($query) use ($appSeeds, $svcSeeds, $modSeeds, $dbSeeds, $fluxSelected) {
                $query
                    ->orWhereIn('application_source_id', $appSeeds)
                    ->orWhereIn('application_dest_id', $appSeeds)
                    ->orWhereIn('service_source_id', $svcSeeds)
                    ->orWhereIn('service_dest_id', $svcSeeds)
                    ->orWhereIn('module_source_id', $modSeeds)
                    ->orWhereIn('module_dest_id', $modSeeds)
                    ->orWhereIn('database_source_id', $dbSeeds)
                    ->orWhereIn('database_dest_id', $dbSeeds)
                    ->orWhereIn('id', $fluxSelected);
            })
            ->get();

        return $flows->sortBy(fn (ApplicationFlow $f) => mb_strtolower($f->name))->values();
    }

    /**
     * @return ?array{0: string, 1: int}
     */
    private function endpoint(ApplicationFlow $flow, string $side): ?array
    {
        $columns = $side === 'source'
            ? ['application' => $flow->application_source_id, 'service' => $flow->service_source_id, 'module' => $flow->module_source_id, 'database' => $flow->database_source_id]
            : ['application' => $flow->application_dest_id, 'service' => $flow->service_dest_id, 'module' => $flow->module_dest_id, 'database' => $flow->database_dest_id];

        foreach ($columns as $type => $id) {
            if ($id !== null) {
                return [$type, $id];
            }
        }

        return null;
    }

    /**
     * @return array<int, int>
     */
    private function idsOfType(array $selection, string $type): array
    {
        return collect($selection)
            ->filter(fn (array $item) => ($item['type'] ?? null) === $type)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Assainit puis échappe un label pour l'insérer dans le DSL Cairn (§6).
     */
    private function label(?string $value): string
    {
        return $this->quote($this->sanitizeLabel($value));
    }

    private function sanitizeLabel(?string $value): string
    {
        $value ??= '';
        $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);
        $value = str_replace(['"', '#'], '', $value);

        return trim($value);
    }

    private function quote(string $value): string
    {
        return '"'.$value.'"';
    }

    /**
     * `external` est un champ texte libre (select2-free), pas un booléen : seule une valeur
     * contenant "extern" (insensible à la casse — "Externe", "EXTERNAL", ...) est considérée
     * comme "externe". Une valeur renseignée mais sans ce motif (ex. faute de saisie "Inerne")
     * reste interne.
     */
    private function isExternal(?string $external): bool
    {
        return $external !== null && str_contains(mb_strtolower($external), 'extern');
    }
}
