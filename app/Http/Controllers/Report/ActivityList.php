<?php

namespace App\Http\Controllers\Report;

use Mercator\Core\Models\DataProcessing;
use Carbon\Carbon;
use Gate;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\Response;

class ActivityList extends ReportController
{
    /**
     * @throws Exception
     */
    public function generateExcel(): Response
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $register = DataProcessing::query()
            ->orderBy('name')
            ->get();

        $header = [
            trans('cruds.dataProcessing.fields.name'),
            trans('cruds.dataProcessing.fields.description'),
            trans('cruds.dataProcessing.fields.responsible'),
            trans('cruds.dataProcessing.fields.purpose'),
            trans('cruds.dataProcessing.fields.lawfulness'),
            trans('cruds.dataProcessing.fields.categories'),
            trans('cruds.dataProcessing.fields.recipients'),
            trans('cruds.dataProcessing.fields.transfert'),
            trans('cruds.dataProcessing.fields.retention'),
            trans('cruds.dataProcessing.fields.processes'),
            trans('cruds.dataProcessing.fields.applications'),
            trans('cruds.dataProcessing.fields.information'),
            trans('cruds.dataProcessing.fields.security_controls'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getStyle('A:N')->getAlignment()->setWrapText(true);

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(350, 'pt');
        $sheet->getColumnDimension('C')->setWidth(350, 'pt');
        $sheet->getColumnDimension('D')->setWidth(350, 'pt');
        $sheet->getColumnDimension('E')->setWidth(350, 'pt');
        $sheet->getColumnDimension('F')->setWidth(350, 'pt');
        $sheet->getColumnDimension('G')->setWidth(350, 'pt');
        $sheet->getColumnDimension('H')->setWidth(350, 'pt');
        $sheet->getColumnDimension('I')->setWidth(350, 'pt');
        $sheet->getColumnDimension('J')->setWidth(350, 'pt');

        $sheet->getColumnDimension('K')->setWidth(350, 'pt');
        $sheet->getColumnDimension('L')->setWidth(350, 'pt');
        $sheet->getColumnDimension('M')->setWidth(350, 'pt');
        $sheet->getColumnDimension('N')->setWidth(350, 'pt');

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate
        $row = 2;
        foreach ($register as $dataProcessing) {
            $sheet->setCellValue("A{$row}", $dataProcessing->name);
            $sheet->setCellValue("B{$row}", $html->toRichTextObject($dataProcessing->description));
            $sheet->setCellValue("C{$row}", $html->toRichTextObject($dataProcessing->responsible));
            $sheet->setCellValue("D{$row}", $html->toRichTextObject($dataProcessing->purpose));
            $sheet->setCellValue("E{$row}", $html->toRichTextObject($dataProcessing->lawfulness));
            $sheet->setCellValue("F{$row}", $html->toRichTextObject($dataProcessing->categories));
            $sheet->setCellValue("G{$row}", $html->toRichTextObject($dataProcessing->recipients));
            $sheet->setCellValue("H{$row}", $html->toRichTextObject($dataProcessing->transfert));
            $sheet->setCellValue("I{$row}", $html->toRichTextObject($dataProcessing->retention));

            // processes
            $txt = '';
            foreach ($dataProcessing->processes as $p) {
                $txt .= $p->name;
                if ($dataProcessing->processes->last() !== $p) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("J{$row}", $txt);

            // Applications
            $txt = '';
            foreach ($dataProcessing->applications as $application) {
                $txt .= $application->name;
                if ($dataProcessing->applications->last() !== $application) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("K{$row}", $txt);

            // Informations
            $txt = '';
            foreach ($dataProcessing->informations as $information) {
                $txt .= $information->name;
                if ($dataProcessing->informations->last() !== $information) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("L{$row}", $txt);

            // TODO : improve me using union
            // https://laravel.com/docs/10.x/queries#unions
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
            $txt = implode(', ', $allControls->toArray());

            $sheet->setCellValue("M{$row}", $txt);

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/register-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/register-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);

    }
}
