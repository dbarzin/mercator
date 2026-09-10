<?php

namespace App\Services\Report;

use App\Contracts\HasUniqueIdentifierContract;
use App\Models\Application;
use App\Models\ApplicationBlock;
use App\Models\ApplicationEvent;
use App\Models\ApplicationFlow;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Cartographer;
use App\Models\Database;
use App\Services\Graph\ApplicationFlowGraphBuilder;
use App\Services\Graph\ApplicationGraphBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;

class ApplicationSection implements ReportSection
{
    private const FLUX_ENDPOINT_RELATIONS = [
        'applicationSource', 'serviceSource', 'moduleSource', 'databaseSource',
        'applicationDest', 'serviceDest', 'moduleDest', 'databaseDest',
        'informations',
    ];

    public function build(Section $section, WordHelper $helper, array $selectedVues): void
    {
        $section->addTitle(trans('cruds.menu.application.title'), 1);

        $applicationBlocks = Cartographer::scopedQuery(ApplicationBlock::query())
            ->with('applications')
            ->get()
            ->sortBy(fn (ApplicationBlock $item) => mb_strtolower((string) $item->name));

        $applications = Cartographer::scopedQuery(Application::query())
            ->with([
                'applicationBlock', 'entityResp', 'entities', 'administrators', 'databases', 'services',
                'events.user', 'processes', 'activities', 'logicalServers', 'containers', 'securityDevices', 'documents',
                ...$this->fluxWith('applicationSourceFluxes'), ...$this->fluxWith('applicationDestFluxes'),
            ])
            ->get()
            ->sortBy(fn (Application $item) => mb_strtolower((string) $item->name));

        $applicationServices = Cartographer::scopedQuery(ApplicationService::query())
            ->with(['modules', ...$this->fluxWith('serviceSourceFluxes'), ...$this->fluxWith('serviceDestFluxes')])
            ->get()
            ->sortBy(fn (ApplicationService $item) => mb_strtolower((string) $item->name));

        $applicationModules = Cartographer::scopedQuery(ApplicationModule::query())
            ->with(['entities', 'applicationServices', ...$this->fluxWith('moduleSourceFluxes'), ...$this->fluxWith('moduleDestFluxes')])
            ->get()
            ->sortBy(fn (ApplicationModule $item) => mb_strtolower((string) $item->name));

        $databases = Cartographer::scopedQuery(Database::query())
            ->with([
                'entities', 'entityResp', 'informations', 'applications', 'logicalServers', 'containers',
                ...$this->fluxWith('databaseSourceFluxes'), ...$this->fluxWith('databaseDestFluxes'),
            ])
            ->get()
            ->sortBy(fn (Database $item) => mb_strtolower((string) $item->name));

        $applicationFlows = Cartographer::scopedQuery(ApplicationFlow::query())
            ->with([...self::FLUX_ENDPOINT_RELATIONS])
            ->get()
            ->sortBy(fn (ApplicationFlow $item) => mb_strtolower((string) $item->name));

        $this->addApplicationBlocks($section, $helper, $applicationBlocks, $applications, $applicationServices, $applicationModules, $databases, $applicationFlows, $selectedVues);
        $this->addApplications($section, $helper, $applications, $selectedVues);
        $this->addApplicationServices($section, $helper, $applicationServices, $selectedVues);
        $this->addApplicationModules($section, $helper, $applicationModules, $selectedVues);
        $this->addDatabases($section, $helper, $databases, $selectedVues);
        $this->addApplicationFlows($section, $helper, $applicationFlows, $selectedVues);
    }

    /**
     * @return array<int, string>
     */
    private function fluxWith(string $relation): array
    {
        return array_map(fn (string $endpoint) => $relation.'.'.$endpoint, self::FLUX_ENDPOINT_RELATIONS);
    }

    /**
     * @param  Collection<int, ApplicationBlock>  $applicationBlocks
     * @param  Collection<int, Application>  $applications
     * @param  Collection<int, ApplicationService>  $applicationServices
     * @param  Collection<int, ApplicationModule>  $applicationModules
     * @param  Collection<int, Database>  $databases
     * @param  Collection<int, ApplicationFlow>  $applicationFlows
     * @param  array<int, string>  $selectedVues
     */
    private function addApplicationBlocks(
        Section $section,
        WordHelper $helper,
        Collection $applicationBlocks,
        Collection $applications,
        Collection $applicationServices,
        Collection $applicationModules,
        Collection $databases,
        Collection $applicationFlows,
        array $selectedVues
    ): void {
        if ($applicationBlocks->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.applicationBlock.title'), 2);

        $structureGraphBuilder = new ApplicationGraphBuilder;
        $flowGraphBuilder = new ApplicationFlowGraphBuilder;
        $iconResolver = fn (?int $iconId, string $fallback) => $helper->resolveIconPath($iconId, $fallback);

        foreach ($applicationBlocks as $applicationBlock) {
            $helper->addBookmarkedTitle($section, $applicationBlock->getUID(), (string) $applicationBlock->name, 3);
            $this->addApplicationGroupGraphs(
                $section, $helper, $structureGraphBuilder, $flowGraphBuilder, $iconResolver,
                new Collection([$applicationBlock]),
                $applications->where('application_block_id', $applicationBlock->id),
                $applicationServices, $applicationModules, $databases, $applicationFlows
            );

            $table = $helper->addTable($section, (string) $applicationBlock->name);

            $helper->addHTMLRow($table, trans('cruds.applicationBlock.fields.description'), $applicationBlock->description);
            $helper->addTextRow($table, trans('cruds.applicationBlock.fields.responsible'), $applicationBlock->responsible);

            if ($applicationBlock->applications->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.applicationBlock.fields.applications'), $applicationBlock->applications, $selectedVues);
            }
        }

        // Applications outside any ApplicationBlock (and their own services/modules/databases) get
        // one more graph so nothing the old single global graph used to show disappears.
        $this->addApplicationGroupGraphs(
            $section, $helper, $structureGraphBuilder, $flowGraphBuilder, $iconResolver,
            new Collection,
            $applications->whereNull('application_block_id'),
            $applicationServices, $applicationModules, $databases, $applicationFlows
        );
    }

    /**
     * Structural + flow subgraphs for one grouping of applications (one ApplicationBlock, or the
     * unassigned bucket when $applicationBlocks is empty), scoped to just these applications and
     * whatever they reach (their own services/modules/databases and the flows between them).
     * Skipped entirely when there are no applications to draw.
     *
     * @param  callable(?int, string): string  $iconResolver
     * @param  Collection<int, ApplicationBlock>  $applicationBlocks
     * @param  Collection<int, Application>  $ownApplications
     * @param  Collection<int, ApplicationService>  $applicationServices
     * @param  Collection<int, ApplicationModule>  $applicationModules
     * @param  Collection<int, Database>  $databases
     * @param  Collection<int, ApplicationFlow>  $applicationFlows
     */
    private function addApplicationGroupGraphs(
        Section $section,
        WordHelper $helper,
        ApplicationGraphBuilder $structureGraphBuilder,
        ApplicationFlowGraphBuilder $flowGraphBuilder,
        callable $iconResolver,
        Collection $applicationBlocks,
        Collection $ownApplications,
        Collection $applicationServices,
        Collection $applicationModules,
        Collection $databases,
        Collection $applicationFlows
    ): void {
        if ($ownApplications->isEmpty()) {
            return;
        }

        $serviceIds = $ownApplications->flatMap(fn (Application $app) => $app->services->pluck('id'))->unique();
        $ownServices = $applicationServices->whereIn('id', $serviceIds);

        $moduleIds = $ownServices->flatMap(fn (ApplicationService $service) => $service->modules->pluck('id'))->unique();
        $ownModules = $applicationModules->whereIn('id', $moduleIds);

        $databaseIds = $ownApplications->flatMap(fn (Application $app) => $app->databases->pluck('id'))->unique();
        $ownDatabases = $databases->whereIn('id', $databaseIds);

        $structureDot = $structureGraphBuilder->buildDot($applicationBlocks, $ownApplications, $ownServices, $ownModules, $ownDatabases, [
            'withHref' => false,
            'iconResolver' => $iconResolver,
        ]);
        $helper->insertGraph($section, $structureDot);

        $appIds = $ownApplications->pluck('id');
        $serviceIdSet = $ownServices->pluck('id');
        $moduleIdSet = $ownModules->pluck('id');
        $databaseIdSet = $ownDatabases->pluck('id');

        $ownFlows = $applicationFlows->filter(function (ApplicationFlow $flow) use ($appIds, $serviceIdSet, $moduleIdSet, $databaseIdSet) {
            $sourceIn = ($flow->application_source_id !== null && $appIds->contains($flow->application_source_id))
                || ($flow->service_source_id !== null && $serviceIdSet->contains($flow->service_source_id))
                || ($flow->module_source_id !== null && $moduleIdSet->contains($flow->module_source_id))
                || ($flow->database_source_id !== null && $databaseIdSet->contains($flow->database_source_id));
            $destIn = ($flow->application_dest_id !== null && $appIds->contains($flow->application_dest_id))
                || ($flow->service_dest_id !== null && $serviceIdSet->contains($flow->service_dest_id))
                || ($flow->module_dest_id !== null && $moduleIdSet->contains($flow->module_dest_id))
                || ($flow->database_dest_id !== null && $databaseIdSet->contains($flow->database_dest_id));

            return $sourceIn && $destIn;
        });

        if ($ownFlows->isNotEmpty()) {
            $flowDot = $flowGraphBuilder->buildDot($ownApplications, $ownServices, $ownModules, $ownDatabases, $ownFlows, [
                'withHref' => false,
                'iconResolver' => $iconResolver,
            ]);
            $helper->insertGraph($section, $flowDot);
        }
    }

    /**
     * @param  Collection<int, Application>  $applications
     * @param  array<int, string>  $selectedVues
     */
    private function addApplications(Section $section, WordHelper $helper, Collection $applications, array $selectedVues): void
    {
        if ($applications->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.application.title'), 2);

        foreach ($applications as $application) {
            $helper->addBookmarkedTitle($section, $application->getUID(), (string) $application->name, 3);
            $table = $helper->addTable($section, (string) $application->name);

            if ($application->applicationBlock !== null) {
                $run = $helper->addTextRunRow($table, trans('cruds.application.fields.application_block'));
                $helper->linkOrText($run, $application->applicationBlock, $selectedVues);
            }

            $helper->addTextRow($table, trans('cruds.application.fields.attributes'), $application->attributes);
            $helper->addDescriptionCellWithIcon($table, trans('cruds.application.fields.description'), $application->description, $application, '/images/application.png');

            if (config('mercator.parameters.application_documents') && $application->documents->isNotEmpty()) {
                $helper->addDocumentLinksRow($table, trans('cruds.application.fields.documents'), $application->documents);
            }

            $helper->addTextRow($table, trans('cruds.application.fields.responsible'), $application->responsible);

            if ($application->entityResp !== null) {
                $run = $helper->addTextRunRow($table, trans('cruds.application.fields.entity_resp'));
                $helper->linkOrText($run, $application->entityResp, $selectedVues);
            }

            if ($application->entities->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.entities'), $application->entities, $selectedVues);
            }

            $helper->addTextRow($table, trans('cruds.application.fields.functional_referent'), $application->functional_referent);
            $helper->addTextRow($table, trans('cruds.application.fields.editor'), $application->editor);
            $helper->addTextRow($table, trans('cruds.application.fields.users'), $application->users);

            if ($application->administrators->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.administrators'), $application->administrators, $selectedVues, fn (Model&HasUniqueIdentifierContract $adminUser) => (string) $adminUser->getAttribute('user_id'));
            }

            $helper->addTextRow($table, trans('cruds.application.fields.technology'), $application->technology);
            $helper->addTextRow($table, trans('cruds.application.fields.type'), $application->type);
            $helper->addTextRow($table, trans('cruds.application.fields.external'), $application->external);

            $helper->addTextRow($table, trans('cruds.application.fields.hosting'), $application->hosting);
            $helper->addTextRow($table, trans('cruds.application.fields.urls'), $application->urls);

            $helper->addTextRow($table, trans('cruds.application.fields.status'), $application->status);
            if ($application->prod_date !== null) {
                $helper->addTextRow($table, trans('cruds.application.fields.prod_date'), $application->prod_date->format('Y-m-d'));
            }
            if ($application->install_date !== null) {
                $helper->addTextRow($table, trans('cruds.application.fields.install_date'), $application->install_date->format('Y-m-d'));
            }

            if ($application->update_date !== null) {
                $helper->addTextRow($table, trans('cruds.application.fields.update_date'), $application->update_date->format('Y-m-d'));
            }

            if ($application->events->isNotEmpty()) {
                $helper->addNestedTableRow(
                    $table,
                    trans('cruds.application.fields.events'),
                    [trans('cruds.relation.fields.date'), trans('cruds.auditLog.fields.user_id'), trans('cruds.auditLog.fields.description')],
                    $application->events->sortByDesc('created_at')->map(fn (ApplicationEvent $event) => [
                        (string) $event->created_at,
                        (string) ($event->user->name ?? ''),
                        (string) $event->message,
                    ])
                );
            }

            $this->addUrlOrTextRow($table, $helper, trans('cruds.application.fields.documentation'), $application->documentation);

            if ($application->databases->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.databases'), $application->databases, $selectedVues);
            }

            if ($application->services->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.services'), $application->services, $selectedVues);
            }

            $helper->addSecurityNeedRow(
                $table,
                trans('cruds.application.fields.security_need'),
                $application->security_need_c,
                $application->security_need_i,
                $application->security_need_a,
                $application->security_need_t,
                $application->security_need_auth
            );
            $helper->addTextRow($table, trans('cruds.application.fields.RTO'), $helper->formatMinutesDuration($application->rto));
            $helper->addTextRow($table, trans('cruds.application.fields.RPO'), $helper->formatMinutesDuration($application->rpo));
            $helper->addTextRow($table, trans('cruds.application.fields.vendor'), $application->vendor);
            $helper->addTextRow($table, trans('cruds.application.fields.product'), $application->product);
            $helper->addTextRow($table, trans('cruds.application.fields.version'), $application->version);

            if ($application->processes->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.processes'), $application->processes, $selectedVues);
            }

            if ($application->activities->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.activities'), $application->activities, $selectedVues);
            }

            $this->addFluxTable($table, $helper, $application->applicationSourceFluxes, $application->applicationDestFluxes, $selectedVues);

            if ($application->logicalServers->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.logical_servers'), $application->logicalServers, $selectedVues);
            }

            if ($application->containers->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.containers'), $application->containers, $selectedVues);
            }

            if ($application->securityDevices->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.application.fields.security_devices'), $application->securityDevices, $selectedVues);
            }
        }
    }

    /**
     * @param  Collection<int, ApplicationService>  $applicationServices
     * @param  array<int, string>  $selectedVues
     */
    private function addApplicationServices(Section $section, WordHelper $helper, Collection $applicationServices, array $selectedVues): void
    {
        if ($applicationServices->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.applicationService.title'), 2);

        foreach ($applicationServices as $service) {
            $helper->addBookmarkedTitle($section, $service->getUID(), (string) $service->name, 3);
            $table = $helper->addTable($section, (string) $service->name);

            $helper->addHTMLRow($table, trans('cruds.applicationService.fields.description'), $service->description);
            $helper->addTextRow($table, trans('cruds.applicationService.fields.exposition'), $service->exposition);

            if ($service->modules->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.applicationService.fields.modules'), $service->modules, $selectedVues);
            }

            $this->addFluxTable($table, $helper, $service->serviceSourceFluxes, $service->serviceDestFluxes, $selectedVues);
        }
    }

    /**
     * @param  Collection<int, ApplicationModule>  $applicationModules
     * @param  array<int, string>  $selectedVues
     */
    private function addApplicationModules(Section $section, WordHelper $helper, Collection $applicationModules, array $selectedVues): void
    {
        if ($applicationModules->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.applicationModule.title'), 2);

        foreach ($applicationModules as $module) {
            $helper->addBookmarkedTitle($section, $module->getUID(), (string) $module->name, 3);
            $table = $helper->addTable($section, (string) $module->name);

            $helper->addHTMLRow($table, trans('cruds.applicationModule.fields.description'), $module->description);

            if ($module->entities->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.applicationModule.fields.entities'), $module->entities, $selectedVues);
            }

            if ($module->applicationServices->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.applicationModule.fields.services'), $module->applicationServices, $selectedVues);
            }

            $helper->addTextRow($table, trans('cruds.application.fields.vendor'), $module->vendor);
            $helper->addTextRow($table, trans('cruds.application.fields.product'), $module->product);
            $helper->addTextRow($table, trans('cruds.application.fields.version'), $module->version);

            $this->addFluxTable($table, $helper, $module->moduleSourceFluxes, $module->moduleDestFluxes, $selectedVues);
        }
    }

    /**
     * @param  Collection<int, Database>  $databases
     * @param  array<int, string>  $selectedVues
     */
    private function addDatabases(Section $section, WordHelper $helper, Collection $databases, array $selectedVues): void
    {
        if ($databases->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.database.title'), 2);

        foreach ($databases as $database) {
            $helper->addBookmarkedTitle($section, $database->getUID(), (string) $database->name, 3);
            $table = $helper->addTable($section, (string) $database->name);

            $helper->addTextRow($table, trans('cruds.database.fields.type'), $database->type);
            $helper->addDescriptionCellWithIcon($table, trans('cruds.database.fields.description'), $database->description, $database, '/images/database.png');

            if ($database->entities->isNotEmpty()) {
                $helper->addTextRow($table, trans('cruds.database.fields.entities'), $database->entities->pluck('name')->implode(', '));
            }

            $helper->addTextRow($table, trans('cruds.database.fields.entity_resp'), $database->entityResp->name ?? '');
            $helper->addTextRow($table, trans('cruds.database.fields.responsible'), $database->responsible);

            if ($database->informations->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.database.fields.informations'), $database->informations, $selectedVues);
            }

            if ($database->applications->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.database.fields.applications'), $database->applications, $selectedVues);
            }

            if ($database->logicalServers->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.database.fields.logical_servers'), $database->logicalServers, $selectedVues);
            }

            $helper->addTextRow($table, trans('cruds.database.fields.external'), $database->external);

            if ($database->containers->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.database.fields.containers'), $database->containers, $selectedVues);
            }

            $helper->addSecurityNeedRow(
                $table,
                trans('cruds.database.fields.security_need'),
                $database->security_need_c,
                $database->security_need_i,
                $database->security_need_a,
                $database->security_need_t,
                $database->security_need_auth
            );

            $this->addFluxTable($table, $helper, $database->databaseSourceFluxes, $database->databaseDestFluxes, $selectedVues);
        }
    }

    /**
     * @param  Collection<int, ApplicationFlow>  $applicationFlows
     * @param  array<int, string>  $selectedVues
     */
    private function addApplicationFlows(Section $section, WordHelper $helper, Collection $applicationFlows, array $selectedVues): void
    {
        if ($applicationFlows->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.applicationFlow.title'), 2);

        foreach ($applicationFlows as $flow) {
            $helper->addBookmarkedTitle($section, $flow->getUID(), (string) $flow->name, 3);
            $table = $helper->addTable($section, (string) $flow->name);

            $helper->addTextRow($table, trans('cruds.applicationFlow.fields.type'), $flow->type);
            $helper->addTextRow($table, trans('cruds.applicationFlow.fields.attributes'), $this->formatAttributes($flow->attributes));
            $helper->addHTMLRow($table, trans('cruds.applicationFlow.fields.description'), $flow->description);

            $sourceRun = $helper->addTextRunRow($table, trans('cruds.applicationFlow.fields.source'));
            $this->addTaggedEndpoint($sourceRun, $helper, $flow->applicationSource, '[Application]', $selectedVues);
            $this->addTaggedEndpoint($sourceRun, $helper, $flow->serviceSource, '[Service]', $selectedVues);
            $this->addTaggedEndpoint($sourceRun, $helper, $flow->moduleSource, '[Module]', $selectedVues);
            $this->addTaggedEndpoint($sourceRun, $helper, $flow->databaseSource, '[Database]', $selectedVues);

            $destRun = $helper->addTextRunRow($table, trans('cruds.applicationFlow.fields.destination'));
            $this->addTaggedEndpoint($destRun, $helper, $flow->applicationDest, '[Application]', $selectedVues);
            $this->addTaggedEndpoint($destRun, $helper, $flow->serviceDest, '[Service]', $selectedVues);
            $this->addTaggedEndpoint($destRun, $helper, $flow->moduleDest, '[Module]', $selectedVues);
            $this->addTaggedEndpoint($destRun, $helper, $flow->databaseDest, '[Database]', $selectedVues);

            if ($flow->informations->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.applicationFlow.fields.information'), $flow->informations, $selectedVues);
            }

            $helper->addTextRow($table, trans('cruds.applicationFlow.fields.crypted'), $flow->crypted ? trans('global.yes') : trans('global.no'));
            $helper->addTextRow($table, trans('cruds.applicationFlow.fields.bidirectional'), $flow->bidirectional ? trans('global.yes') : trans('global.no'));
        }
    }

    /**
     * Renders one flow endpoint (of the 4 mutually exclusive polymorphic source/dest columns) as
     * "Name [Type]", matching ApplicationFlow's own standalone _details.blade.php.
     *
     * @param  (Model&HasUniqueIdentifierContract)|null  $endpoint
     * @param  array<int, string>  $selectedVues
     */
    private function addTaggedEndpoint(TextRun $run, WordHelper $helper, (Model&HasUniqueIdentifierContract)|null $endpoint, string $tag, array $selectedVues): void
    {
        if ($endpoint === null) {
            return;
        }

        $helper->linkOrText($run, $endpoint, $selectedVues);
        $run->addText(' '.$tag.' ');
    }

    /**
     * Renders the "flux" nested sub-table shared by Application/ApplicationService/ApplicationModule/Database:
     * name/type/attributes/source/dest/information, with the source/dest endpoint resolved from
     * whichever of Application/Service/Module/Database is set (no type tag here, matching the
     * embedded rendering used by every parent's show.blade.php).
     *
     * @param  Collection<int, ApplicationFlow>  $sourceFluxes
     * @param  Collection<int, ApplicationFlow>  $destFluxes
     * @param  array<int, string>  $selectedVues
     */
    private function addFluxTable(Table $table, WordHelper $helper, Collection $sourceFluxes, Collection $destFluxes, array $selectedVues): void
    {
        $flows = $sourceFluxes->concat($destFluxes)->unique('id');
        if ($flows->isEmpty()) {
            return;
        }

        $table->addRow();
        $table->addCell(2000, WordHelper::NO_SPACE)->addText(trans('cruds.applicationFlow.title'), WordHelper::FANCY_LEFT_TABLE_CELL_STYLE, WordHelper::NO_SPACE);
        $nested = $table->addCell(6000)->addTable(['borderSize' => 1, 'borderColor' => '999999', 'cellMargin' => 50]);

        $nested->addRow();
        foreach ([
            trans('cruds.applicationFlow.fields.name'),
            trans('cruds.applicationFlow.fields.type'),
            trans('cruds.applicationFlow.fields.attributes'),
            trans('cruds.applicationFlow.fields.module_source'),
            trans('cruds.applicationFlow.fields.module_dest'),
            trans('cruds.applicationFlow.fields.information'),
        ] as $header) {
            $nested->addCell(1500)->addText($header, WordHelper::FANCY_LEFT_TABLE_CELL_STYLE, WordHelper::NO_SPACE);
        }

        foreach ($flows as $flow) {
            $nested->addRow();

            $nameRun = $nested->addCell(1500)->addTextRun();
            $helper->linkOrText($nameRun, $flow, $selectedVues);

            $nested->addCell(1500)->addText((string) $flow->type);
            $nested->addCell(1500)->addText($this->formatAttributes($flow->attributes) ?? '');

            $sourceRun = $nested->addCell(1500)->addTextRun();
            $source = $flow->applicationSource ?? $flow->serviceSource ?? $flow->moduleSource ?? $flow->databaseSource;
            if ($source !== null) {
                $helper->linkOrText($sourceRun, $source, $selectedVues);
            }

            $destRun = $nested->addCell(1500)->addTextRun();
            $dest = $flow->applicationDest ?? $flow->serviceDest ?? $flow->moduleDest ?? $flow->databaseDest;
            if ($dest !== null) {
                $helper->linkOrText($destRun, $dest, $selectedVues);
            }

            $infoRun = $nested->addCell(1500)->addTextRun();
            $informations = $flow->informations;
            $i = 0;
            $count = $informations->count();
            foreach ($informations as $information) {
                $helper->linkOrText($infoRun, $information, $selectedVues);
                if (++$i < $count) {
                    $infoRun->addText(', ');
                }
            }
        }
    }

    private function formatAttributes(?string $attributes): ?string
    {
        if ($attributes === null || trim($attributes) === '') {
            return $attributes;
        }

        return implode(', ', array_filter(explode(' ', $attributes)));
    }

    private function addUrlOrTextRow(Table $table, WordHelper $helper, string $title, ?string $value): void
    {
        if ($value !== null && filter_var($value, FILTER_VALIDATE_URL)) {
            $run = $helper->addTextRunRow($table, $title);
            $run->addLink($value, $value, WordHelper::FANCY_LINK_STYLE, null, false);

            return;
        }

        $helper->addTextRow($table, $title, $value);
    }
}
