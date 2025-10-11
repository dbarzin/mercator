<?php

namespace App\Http\Controllers\Report;

use App\Models\MApplication;
use Carbon\Carbon;
use Gate;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\Response;

class Directory extends ReportController
{
    public function generateDocx()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $today = Carbon::today()->toDateString();

        $applications =
            MApplication::query()
                ->where(function ($query) {
                    $query->where('m_applications.security_need_c', '>=', 3)
                        ->orWhere('m_applications.security_need_i', '>=', 3)
                        ->orWhere('m_applications.security_need_a', '>=', 3)
                        ->orWhere('m_applications.security_need_t', '>=', 3)
                        ->orWhere('m_applications.security_need_auth', '>=', 3);
                })
                ->whereNull('m_applications.deleted_at')
                ->leftJoin('entities', function ($join) {
                    $join->on(function ($on) {
                        $on->on('m_applications.entity_resp_id', '=', 'entities.id');
                    })
                        ->whereNull('entities.deleted_at');
                })
                ->whereNull('entities.deleted_at')
                ->leftJoin('relations', function ($join) use ($today) {
                    /*
               $join->on(function ($on) {
                    $on->on('entities.id', '=', 'relations.source_id')
                       ->orOn('entities.id', '=', 'relations.destination_id');
                })
                */
                    $join->on('entities.id', '=', 'relations.source_id')
                        ->where('relations.active', '=', 1)
                        ->where('relations.start_date', '<=', $today)
                        ->where('relations.end_date', '>=', $today)
                        ->whereNull('relations.deleted_at');
                })
                ->select(
                    'm_applications.id as application_id',
                    'm_applications.name',
                    'm_applications.description',
                    'm_applications.responsible',
                    'm_applications.security_need_c',
                    'm_applications.security_need_i',
                    'm_applications.security_need_a',
                    'm_applications.security_need_t',
                    'm_applications.rto',
                    'm_applications.rpo',
                    'entities.name as entity_name',
                    'entities.description as entity_description',
                    'entities.contact_point as entity_contact_point',
                    'relations.name as relation_name',
                    'relations.type as relation_type',
                    'relations.description as relation_description',
                    'relations.importance as relation_importance',
                    'relations.start_date as relation_start_date',
                    'relations.end_date as relation_end_date'
                )
                ->get();

        // --- Styles de base
        $phpWord = new PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true); // !!!!
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);

        $phpWord->addTitleStyle(1, ['size' => 20, 'bold' => true]);
        $phpWord->addTitleStyle(2, ['size' => 16, 'bold' => true]);
        $labelStyle = ['bold' => true];
        $textStyle = [];

        $tableKVStyle = [
            'borderColor' => 'cccccc',
            'borderSize' => 6,
            'cellMargin' => 80,
            'width' => 100 * 50, // 100% (fiftieths of a percent)
        ];
        $tableKVFirstCol = ['bgColor' => 'f3f3f3', 'valign' => 'center', 'width' => 40 * 50];

        $tableListStyle = [
            'borderColor' => 'cccccc',
            'borderSize' => 6,
            'cellMargin' => 80,
            'width' => 100 * 50,
        ];
        $tableHeaderCell = ['bgColor' => 'eaeaea'];
        $paraTight = ['spaceAfter' => 60];

        // Numbering Style
        $phpWord->addNumberingStyle(
            'hNum',
            ['type' => 'multilevel', 'levels' => [
                ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.'],
                ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2.'],
                ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3.'],
            ],
            ]
        );
        $phpWord->addTitleStyle(
            0,
            ['size' => 28, 'bold' => true],
            ['align' => 'center']
        );
        $phpWord->addTitleStyle(
            1,
            ['size' => 16, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 0]
        );
        $phpWord->addTitleStyle(
            2,
            ['size' => 14, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 1]
        );
        $phpWord->addTitleStyle(
            3,
            ['size' => 12, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 2]
        );

        // Title
        $section = $phpWord->addSection();
        $section->addTitle(trans('cruds.report.lists.directory'), 0);
        $section->addTextBreak(1);

        // TOC
        $toc = $section->addTOC(['spaceAfter' => 50, 'size' => 10]);
        $toc->setMinDepth(0);
        $toc->setMaxDepth(1);
        $section->addTextBreak(1);

        // page break
        $section->addPageBreak();

        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // --- Regrouper: une page par application, relations listées
        $grouped = $applications->groupBy('name');

        foreach ($grouped as $appName => $rows) {            // Info d’application = première ligne du groupe
            $first = $rows->first();

            $section = $phpWord->addSection([
                'breakType' => 'continuous',
                'pageSizeW' => 11906,
                'pageSizeH' => 16838,
                'marginTop' => 1000,
                'marginBottom' => 1000,
                'marginLeft' => 1000,
                'marginRight' => 1000,
            ]);

            // Titre page
            $section->addTitle($appName, 1);

            // =========================
            // Section 1: Application
            // =========================
            $section->addTitle('Application', 2);

            $t1 = $section->addTable($tableKVStyle);
            $this->addHTMLRow($t1, trans('cruds.application.fields.description'), $first->description);
            $this->addTextRow($t1, 'Processus', $this->getApplicationProcessesNames($first->application_id));
            $this->addTextRow($t1, 'Responsable', $first->responsible);
            $this->addTextRow($t1, 'C-I-A-T', ($first->security_need_c.' - '.
                $first->security_need_i.' - '.
                $first->security_need_a.' - '.
                $first->security_need_t));
            $this->addTextRow($t1, 'RTO', MApplication::formatDelay($first->rto));
            $this->addTextRow($t1, 'RPO', MApplication::formatDelay($first->rpo));

            $section->addTextBreak(1);

            // =========================
            // Section 2: Entité responsable
            // =========================
            $section->addTitle('Entité responsable', 2);
            $t2 = $section->addTable($tableKVStyle);
            $this->addTextRow($t2, 'Nom', $first->entity_name);
            $this->addHTMLRow($t2, 'Point de contact', $first->entity_contact_point);
            $this->addHTMLRow($t2, 'Description', $first->entity_description);

            $section->addTextBreak(1);

            // =========================
            // Section 3: Relations actives à la date du jour
            // =========================
            $section->addTitle('Relations', 2);
            $t3 = $section->addTable($tableListStyle);
            // En-têtes
            $t3h = $t3->addRow();
            foreach (['Nom', 'Type', 'Importance', 'Début', 'Fin'] as $head) {
                $cell = $t3h->addCell(null, $tableHeaderCell);
                $cell->addText($head, ['bold' => true], $paraTight);
            }

            $hasRelation = false;
            foreach ($rows as $row) {
                // si aucune relation liée, les colonnes seront null → affichera "—"
                if ($row->relation_name || $row->relation_type || $row->relation_importance || $row->relation_start_date || $row->relation_end_date) {
                    $hasRelation = true;
                }
                $r = $t3->addRow();
                $r->addCell()->addText(str_replace('&', ' ', $row->relation_name), [], $paraTight);
                $r->addCell()->addText(str_replace('&', ' ', $row->relation_type), [], $paraTight);
                $r->addCell()->addText($row->relation_importance, [], $paraTight);
                $r->addCell()->addText((optional($row->relation_start_date)->format('Y-m-d') ?? $row->relation_start_date), [], $paraTight);
                $r->addCell()->addText((optional($row->relation_end_date)->format('Y-m-d') ?? $row->relation_end_date), [], $paraTight);
                $this->addHTMLRow($t3, 'Description', $first->relation_description);
            }

            if (! $hasRelation) {
                $r = $t3->addRow();
                $c = $r->addCell(null, ['gridSpan' => 5]);
                $c->addText('Aucune relation active pour cette application à la date du jour.', [], $paraTight);
            }

            // Nouvelle page pour l’application suivante
            $section->addPageBreak();
        }

        // --- Sauvegarde du fichier
        // Filename
        $filepath = storage_path('app/reports/directory-'.Carbon::today()->format('Ymd').'.docx');

        // Saving the document as Word2007 file.
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filepath);

        // return
        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    private static function getApplicationProcessesNames(int $applicationId): string
    {
        $names = DB::table('m_application_process')
            ->join('processes', 'm_application_process.process_id', '=', 'processes.id')
            ->where('m_application_process.m_application_id', $applicationId)
            ->pluck('processes.name');

        return $names->implode(', ');
    }
}
