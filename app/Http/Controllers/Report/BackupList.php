<?php

namespace App\Http\Controllers\Report;

use Gate;
use App\Models\Backup;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\Response;

class BackupList extends ReportController
{
    /**
     * @throws Exception
     */
    public function generateExcel(): Response
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backups = Backup::query()
            ->with('logicalServer', 'storageDevice')
            ->get()
            ->sortBy([
                fn ($a, $b) => strcmp($a->logicalServer->name ?? '', $b->logicalServer->name ?? ''),
                fn ($a, $b) => strcmp($a->storageDevice->name ?? '', $b->storageDevice->name ?? ''),
            ]);
        
        $header = [
            trans('cruds.logicalServer.title_short'),
            trans('cruds.storageDevice.title_short'),
            trans('cruds.backup.frequency'),
            trans('cruds.backup.cycle'),
            trans('cruds.backup.retention'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(50, 'pt');
        $sheet->getColumnDimension('C')->setWidth(50, 'pt');
        $sheet->getColumnDimension('D')->setWidth(50, 'pt');

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate
        $row = 2;
        foreach ($backups as $backup) {
            $sheet->setCellValue("A{$row}", $backup->logicalServer->name);
            $sheet->setCellValue("B{$row}", $backup->storageDevice->name);

            $sheet->setCellValue("C{$row}", $backup->backup_frequency ? trans("cruds.backup.frequencies.{$backup->backup_frequency}") : '');
            $sheet->setCellValue("D{$row}", $backup->backup_cycle ? trans("cruds.backup.cycles.{$backup->backup_cycle}") : '');
            $sheet->setCellValue("E{$row}", $backup->backup_retention);

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/register-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/backups-' . now()->format('Ymd') . '.xlsx');

        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);

    }
}
