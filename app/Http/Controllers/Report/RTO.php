<?php


namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\MApplication;
use Gate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class RTO extends Controller
{
    public function generateExcel()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rows = [];

        // Charger les applications avec leurs activités
        $applications = MApplication::with('activities')->get();

        foreach ($applications as $app) {
            foreach ($app->activities as $activity) {
                $rows[] = [
                    'application' => $app->name,
                    'rto' => implode(' ', array_filter([
                        ($d = intdiv($app->rto, 1440)) ? $d.' '.trans('global.'.($d > 1 ? 'days' : 'day')) : null,
                        ($h = intdiv($app->rto, 60) % 24) ? $h.' '.trans('global.'.($h > 1 ? 'hours' : 'hour')) : null,
                        ($m = $app->rto % 60) ? $m.' '.trans('global.'.($m > 1 ? 'minutes' : 'minute')) : null,
                    ])),
                    'rpo' => implode(' ', array_filter([
                        ($d = intdiv($app->ro, 1440)) ? $d.' '.trans('global.'.($d > 1 ? 'days' : 'day')) : null,
                        ($h = intdiv($app->rto, 60) % 24) ? $h.' '.trans('global.'.($h > 1 ? 'hours' : 'hour')) : null,
                        ($m = $app->rto % 60) ? $m.' '.trans('global.'.($m > 1 ? 'minutes' : 'minute')) : null,
                    ])),

                    'activity' => $activity->name,
                    'maximum_tolerable_downtime' => implode(' ', array_filter([
                        ($d = intdiv($activity->maximum_tolerable_downtime, 1440)) ? $d.' '.trans('global.'.($d > 1 ? 'days' : 'day')) : null,
                        ($h = intdiv($activity->maximum_tolerable_downtime, 60) % 24) ? $h.' '.trans('global.'.($h > 1 ? 'hours' : 'hour')) : null,
                        ($m = $activity->maximum_tolerable_downtime % 60) ? $m.' '.trans('global.'.($m > 1 ? 'minutes' : 'minute')) : null,
                    ])),
                    'recovery_time_objective' => implode(' ', array_filter([
                        ($d = intdiv($activity->recovery_time_objective, 1440)) ? $d.' '.trans('global.'.($d > 1 ? 'days' : 'day')) : null,
                        ($h = intdiv($activity->recovery_time_objective, 60) % 24) ? $h.' '.trans('global.'.($h > 1 ? 'hours' : 'hour')) : null,
                        ($m = $activity->recovery_time_objective % 60) ? $m.' '.trans('global.'.($m > 1 ? 'minutes' : 'minute')) : null,
                    ])),
                    'maximum_tolerable_data_loss' => implode(' ', array_filter([
                        ($d = intdiv($activity->maximum_tolerable_data_loss, 1440)) ? $d.' '.trans('global.'.($d > 1 ? 'days' : 'day')) : null,
                        ($h = intdiv($activity->maximum_tolerable_data_loss, 60) % 24) ? $h.' '.trans('global.'.($h > 1 ? 'hours' : 'hour')) : null,
                        ($m = $activity->maximum_tolerable_data_loss % 60) ? $m.' '.trans('global.'.($m > 1 ? 'minutes' : 'minute')) : null,
                    ])),
                    'recovery_point_objective' => implode(' ', array_filter([
                        ($d = intdiv($activity->recovery_point_objective, 1440)) ? $d.' '.trans('global.'.($d > 1 ? 'days' : 'day')) : null,
                        ($h = intdiv($activity->recovery_point_objective, 60) % 24) ? $h.' '.trans('global.'.($h > 1 ? 'hours' : 'hour')) : null,
                        ($m = $activity->recovery_point_objective % 60) ? $m.' '.trans('global.'.($m > 1 ? 'minutes' : 'minute')) : null,
                    ])),
                ];
            }
        }

        // Tri par RTO si dispo, sinon par MTD app
        usort($rows, function ($a, $b) {
            $aTime = $a['recovery_time_objective'] ?: $a['maximum_tolerable_downtime'];
            $bTime = $b['recovery_time_objective'] ?: $b['maximum_tolerable_downtime'];

            return strcmp($aTime, $bTime);
        });

        // Création Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            trans('cruds.application.title'),
            'RTO',
            'RPO',
            trans('cruds.activity.title'),
            trans('cruds.activity.fields.maximum_tolerable_downtime_short'),
            trans('cruds.activity.fields.recovery_time_objective_short'),
            trans('cruds.activity.fields.maximum_tolerable_data_loss_short'),
            trans('cruds.activity.fields.recovery_point_objective_short'),
        ];

        // Écrire l'en-tête
        foreach ($headers as $colIndex => $title) {
            $cell = chr(65 + $colIndex).'1';
            $sheet->setCellValue($cell, $title);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Écrire les lignes
        $rowNum = 2;
        foreach ($rows as $data) {
            $sheet->setCellValue("A{$rowNum}", $data['application']);
            $sheet->setCellValue("B{$rowNum}", $data['rto']);
            $sheet->setCellValue("C{$rowNum}", $data['rpo']);
            $sheet->setCellValue("D{$rowNum}", $data['activity']);
            $sheet->setCellValue("E{$rowNum}", $data['maximum_tolerable_downtime']);
            $sheet->setCellValue("F{$rowNum}", $data['recovery_time_objective']);
            $sheet->setCellValue("G{$rowNum}", $data['maximum_tolerable_data_loss']);
            $sheet->setCellValue("H{$rowNum}", $data['recovery_point_objective']);

            foreach (range('A', 'H') as $col) {
                $sheet->getStyle("{$col}{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $rowNum++;
        }

        // Export
        $writer = new Xlsx($spreadsheet);

        // $path = storage_path('app/securityNeeds-' . Carbon::today()->format('Ymd') . '.xlsx');
        $path = storage_path('applications_by_activity.xlsx');

        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
