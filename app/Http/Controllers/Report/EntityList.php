<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Services\ExcelExportHelper;
use Carbon\Carbon;
use Gate;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class EntityList extends Controller
{
    /**
     * @throws Exception
     */
    public function generateExcel()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::All()->sortBy('name');

        $header = [
            trans('cruds.entity.fields.name'),
            trans('cruds.entity.fields.description'),
            trans('cruds.entity.fields.type'),
            trans('cruds.entity.fields.security_level'),
            trans('cruds.entity.fields.contact_point'),
            trans('cruds.entity.fields.applications_resp'),
        ];

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        // converter
        $html = new Html;

        $excelExportHelper = new ExcelExportHelper;

        // Populate the Timesheet
        $row = 2;
        foreach ($entities as $entity) {
            $sheet->setCellValue("A{$row}", $entity->name);

            $sheet->setCellValue("B{$row}", $excelExportHelper->prepareRichText($entity->description));
            $sheet->getStyle("B{$row}")->getAlignment()->setWrapText(true);

            $sheet->setCellValue("C{$row}", $entity->type);
            $sheet->setCellValue("D{$row}", $html->toRichTextObject($entity->security_level));
            $sheet->setCellValue("E{$row}", $html->toRichTextObject($entity->contact_point));
            $txt = '';
            foreach ($entity->applications as $application) {
                $txt .= $application->name;
                if ($entity->applications->last() !== $application) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("F{$row}", $txt);

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new Xlsx($spreadsheet);

        // $path = storage_path('app/entities-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/entities-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
