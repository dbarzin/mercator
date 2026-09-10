<?php

namespace App\Services\Report;

use App\Models\Cartographer;
use App\Models\Entity;
use App\Models\Relation;
use App\Models\RelationValue;
use App\Services\Graph\EcosystemGraphBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Number;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;

class EcosystemSection implements ReportSection
{
    public function build(Section $section, WordHelper $helper, array $selectedVues): void
    {
        $section->addTitle(trans('cruds.menu.ecosystem.title'), 1);

        $entities = Cartographer::scopedQuery(Entity::query())
            ->with(['parentEntity', 'entities', 'sourceRelations.destination', 'destinationRelations.source', 'processes', 'respApplications', 'databases'])
            ->get()
            ->sortBy(fn (Entity $entity) => mb_strtolower((string) $entity->name));

        $relations = Cartographer::scopedQuery(Relation::query())
            ->with(['source', 'destination', 'values', 'documents'])
            ->get()
            ->sortBy(fn (Relation $relation) => mb_strtolower((string) $relation->name));

        $this->addEntities($section, $helper, $entities, $selectedVues);
        $this->addRelations($section, $helper, $relations, $selectedVues);
    }

    /**
     * @param  Collection<int, Entity>  $entities
     * @param  array<int, string>  $selectedVues
     */
    private function addEntities(Section $section, WordHelper $helper, Collection $entities, array $selectedVues): void
    {
        if ($entities->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.entity.title'), 2);

        $graphBuilder = new EcosystemGraphBuilder;

        foreach ($entities as $entity) {
            $helper->addBookmarkedTitle($section, $entity->getUID(), (string) $entity->name, 3);
            $this->addEntityFamilyGraph($section, $helper, $graphBuilder, $entity);

            $table = $helper->addTable($section, (string) $entity->name);

            $helper->addTextRow($table, trans('cruds.entity.fields.type'), $entity->type);

            if ($entity->parentEntity !== null) {
                $run = $helper->addTextRunRow($table, trans('cruds.entity.fields.parent_entity'));
                $helper->linkOrText($run, $entity->parentEntity, $selectedVues);
            }

            if ($entity->entities->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.entity.fields.subsidiaries'), $entity->entities, $selectedVues);
            }

            $helper->addDescriptionCellWithIcon($table, trans('cruds.entity.fields.description'), $entity->description, $entity, '/images/application.png');

            $helper->addHTMLRow($table, trans('cruds.entity.fields.security_level'), $entity->security_level);
            $helper->addHTMLRow($table, trans('cruds.entity.fields.contact_point'), $entity->contact_point);

            $this->addEntityRelationsRow($table, $helper, $entity, $selectedVues);

            if ($entity->processes->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.entity.fields.processes'), $entity->processes, $selectedVues);
            }

            $exploits = $entity->respApplications->concat($entity->databases);
            if ($exploits->isNotEmpty()) {
                $helper->addLinkListRow($table, trans('cruds.entity.fields.exploits'), $exploits, $selectedVues);
            }
        }
    }

    /**
     * Per-entity subgraph: the entity itself plus its direct parent and children in the
     * Entity.parent_entity_id self-hierarchy — not the generic Relation model. Skipped when the
     * entity has neither (a single isolated node adds nothing beyond its own table row).
     */
    private function addEntityFamilyGraph(Section $section, WordHelper $helper, EcosystemGraphBuilder $graphBuilder, Entity $entity): void
    {
        if ($entity->parentEntity === null && $entity->entities->isEmpty()) {
            return;
        }

        $family = collect([$entity]);
        if ($entity->parentEntity !== null) {
            $family->push($entity->parentEntity);
        }
        $family = $family->concat($entity->entities);

        $dot = $graphBuilder->buildDot($family, collect(), [
            'withHref' => false,
            'iconResolver' => fn (Entity $e) => $helper->resolveIconPath($e->icon_id, '/images/entity.png'),
        ]);
        $helper->insertGraph($section, $dot);
    }

    /**
     * @param  array<int, string>  $selectedVues
     */
    private function addEntityRelationsRow(Table $table, WordHelper $helper, Entity $entity, array $selectedVues): void
    {
        $sourceRelations = $entity->sourceRelations;
        $destinationRelations = $entity->destinationRelations;

        if ($sourceRelations->isEmpty() && $destinationRelations->isEmpty()) {
            return;
        }

        $table->addRow();
        $table->addCell(2000, WordHelper::NO_SPACE)->addText(trans('cruds.entity.fields.relations'), WordHelper::FANCY_LEFT_TABLE_CELL_STYLE, WordHelper::NO_SPACE);
        $run = $table->addCell(6000)->addTextRun(WordHelper::NO_SPACE);

        $i = 0;
        $sourceCount = $sourceRelations->count();
        foreach ($sourceRelations as $relation) {
            $helper->linkOrText($run, $relation, $selectedVues);
            $run->addText(' -> ');
            if ($relation->destination !== null) {
                $helper->linkOrText($run, $relation->destination, $selectedVues);
            }
            if (++$i < $sourceCount) {
                $run->addTextBreak();
            }
        }

        if ($sourceRelations->isNotEmpty() && $destinationRelations->isNotEmpty()) {
            $run->addTextBreak();
        }

        $i = 0;
        $destinationCount = $destinationRelations->count();
        foreach ($destinationRelations as $relation) {
            if ($relation->source !== null) {
                $helper->linkOrText($run, $relation->source, $selectedVues);
            }
            $run->addText(' <- ');
            $helper->linkOrText($run, $relation, $selectedVues);
            if (++$i < $destinationCount) {
                $run->addTextBreak();
            }
        }
    }

    /**
     * @param  Collection<int, Relation>  $relations
     * @param  array<int, string>  $selectedVues
     */
    private function addRelations(Section $section, WordHelper $helper, Collection $relations, array $selectedVues): void
    {
        if ($relations->isEmpty()) {
            return;
        }

        $section->addTitle(trans('cruds.relation.title'), 2);

        foreach ($relations as $relation) {
            $helper->addBookmarkedTitle($section, $relation->getUID(), (string) $relation->name, 3);
            $table = $helper->addTable($section, (string) $relation->name);

            $helper->addTextRow($table, trans('cruds.relation.fields.type'), $relation->type);
            $helper->addTextRow($table, trans('cruds.relation.fields.attributes'), $this->formatAttributes($relation->attributes));
            $helper->addTextRow($table, trans('cruds.relation.fields.reference'), $relation->reference);
            $helper->addTextRow($table, trans('cruds.relation.fields.order_number'), $relation->order_number);
            $helper->addTextRow($table, trans('cruds.relation.fields.responsible'), $relation->responsible);

            if ($relation->source !== null) {
                $run = $helper->addTextRunRow($table, trans('cruds.relation.fields.source'));
                $helper->linkOrText($run, $relation->source, $selectedVues);
            }

            if ($relation->destination !== null) {
                $run = $helper->addTextRunRow($table, trans('cruds.relation.fields.destination'));
                $helper->linkOrText($run, $relation->destination, $selectedVues);
            }

            $helper->addHTMLRow($table, trans('cruds.relation.fields.description'), $relation->description);

            if ($relation->start_date !== null) {
                $helper->addTextRow($table, trans('cruds.relation.fields.start_date'), (string) $relation->start_date);
            }

            if ($relation->end_date !== null) {
                $helper->addTextRow($table, trans('cruds.relation.fields.end_date'), (string) $relation->end_date);
            }

            if ($relation->active) {
                $helper->addTextRow($table, trans('cruds.relation.fields.active'), trans('global.yes'));
            }

            if ($relation->importance !== null) {
                $helper->addTextRow($table, trans('cruds.relation.fields.importance'), $this->importanceLabel((int) $relation->importance));
            }

            if ($relation->values->isNotEmpty()) {
                $helper->addNestedTableRow(
                    $table,
                    trans('cruds.relation.fields.contract_title'),
                    [trans('cruds.relation.fields.date'), trans('cruds.relation.fields.value')],
                    $relation->values->map(fn (RelationValue $value) => [
                        (string) $value->date_price,
                        Number::currency((float) $value->price, 'EUR', 'fr'),
                    ])
                );
            }

            $helper->addHTMLRow($table, trans('cruds.relation.fields.comments'), $relation->comments);

            if ($relation->documents->isNotEmpty()) {
                $helper->addDocumentLinksRow($table, trans('cruds.relation.fields.documents'), $relation->documents);
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

    private function importanceLabel(int $importance): string
    {
        return match ($importance) {
            1 => trans('cruds.relation.fields.importance_level.low'),
            2 => trans('cruds.relation.fields.importance_level.medium'),
            3 => trans('cruds.relation.fields.importance_level.high'),
            4 => trans('cruds.relation.fields.importance_level.critical'),
            default => '',
        };
    }
}
