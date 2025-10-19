<?php

declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Models\DataProcessing;
use Carbon\Carbon;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ActivityList extends ReportController
{
    public function generateDocx()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // get template
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);
        $section = $phpWord->addSection();

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
            ['spaceAfter' => 100, 'spaceBefore' => 100, 'numStyle' => 'hNum', 'numLevel' => 0]
        );
        $phpWord->addTitleStyle(
            2,
            ['size' => 14, 'bold' => true],
            ['spaceAfter' => 100, 'spaceBefore' => 100, 'numStyle' => 'hNum', 'numLevel' => 1]
        );
        $phpWord->addTitleStyle(
            3,
            ['size' => 12, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 2]
        );

        // Title
        $section->addTitle(trans('cruds.dataProcessing.report_title'), 0);
        $section->addTextBreak(1);

        // TOC
        $toc = $section->addTOC(['spaceAfter' => 50, 'size' => 10]);
        $toc->setMinDepth(1);
        $toc->setMaxDepth(1);
        $section->addTextBreak(1);

        // page break
        $section->addPageBreak();

        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $register = DataProcessing::orderBy('name')->get();
        foreach ($register as $dataProcessing) {
            // schema
            $section->addTitle($dataProcessing->name, 1);

            $section->addTitle(trans('cruds.dataProcessing.fields.legal_basis'), 2);
            $this->addText($section, $dataProcessing->legal_basis);

            $section->addTitle(trans('cruds.dataProcessing.fields.description'), 2);
            $this->addText($section, $dataProcessing->description);

            $section->addTitle(trans('cruds.dataProcessing.fields.responsible'), 2);
            $this->addText($section, $dataProcessing->responsible);

            $section->addTitle(trans('cruds.dataProcessing.fields.purpose'), 2);
            $this->addText($section, $dataProcessing->purpose);

            $section->addTitle(trans('cruds.dataProcessing.fields.categories'), 2);
            $this->addText($section, $dataProcessing->categories);

            $section->addTitle(trans('cruds.dataProcessing.fields.recipients'), 2);
            $this->addText($section, $dataProcessing->recipients);

            $section->addTitle(trans('cruds.dataProcessing.fields.transfert'), 2);
            $this->addText($section, $dataProcessing->transfert);

            $section->addTitle(trans('cruds.dataProcessing.fields.retention'), 2);
            $this->addText($section, $dataProcessing->retention);

            // Processes
            $section->addTitle(trans('cruds.dataProcessing.fields.processes'), 2);
            $txt = '<ul>';
            foreach ($dataProcessing->processes as $p) {
                $txt .= '<li>'.$p->name.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);

            // Applications
            $section->addTitle(trans('cruds.dataProcessing.fields.applications'), 2);
            $txt = '<ul>';
            foreach ($dataProcessing->applications as $ap) {
                $txt .= '<li>'.$ap->name.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);

            // Informations
            $section->addTitle(trans('cruds.dataProcessing.fields.information'), 2);
            $txt = '<ul>';
            foreach ($dataProcessing->informations as $inf) {
                $txt .= '<li>'.$inf->name.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);

            // Security Controls
            $section->addTitle(trans('cruds.dataProcessing.fields.security_controls'), 2);
            // TODO : improve me
            $allControls = Collect();
            foreach ($dataProcessing->processes as $process) {
                foreach ($process->securityControls as $sc) {
                    $allControls->push($sc->name);
                }
            }
            foreach ($dataProcessing->applications as $app) {
                foreach ($app->securityControls as $sc) {
                    $allControls->push($sc->name);
                }
            }
            $allControls->unique();
            $txt = '<ul>';
            foreach ($allControls as $control) {
                $txt .= '<li>'.$control.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);
        }

        // Filename
        $filepath = storage_path('app/reports/register-'.Carbon::today()->format('Ymd').'.docx');

        // Saving the document as Word2007 file.
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $objWriter->save($filepath);

        // return
        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}
