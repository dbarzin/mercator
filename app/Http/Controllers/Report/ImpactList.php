<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityImpact;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImpactList extends Controller
{
    public function generateExcel()
    {
        // 1. Récupérer toutes les activités et impacts
        $activities = Activity::query()
            ->with('impacts')
            ->get();

        // 2. Extraire tous les types d'impact existants
        $impactTypes = ActivityImpact::query()
            ->select('impact_type')->distinct()->pluck('impact_type')->sort()->values()->toArray();

        // Style de l'en-tête
        $boldFont = ['font' => ['bold' => true]];
        $centered = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]];

        // 3. Créer une nouvelle feuille Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 4. Écrire l'en-tête (ligne 1)
        $sheet->setCellValue('A1', 'Activité');
        $sheet->getStyle('A1')->applyFromArray($boldFont);
        foreach ($impactTypes as $index => $type) {
            $cell = chr(66 + $index).'1';
            $sheet->setCellValue($cell, ucfirst($type));
            $sheet->getStyle($cell)->applyFromArray($boldFont);
            $sheet->getStyle($cell)->applyFromArray($centered);
        }

        // 5. Remplir les lignes
        foreach ($activities as $rowIndex => $activity) {
            $row = $rowIndex + 2;
            $sheet->setCellValue('A'.$row, $activity->name);

            foreach ($impactTypes as $colIndex => $type) {
                $severity = $activity->impacts
                    ->firstWhere('impact_type', $type)
                    ->severity ?? null;

                if ($severity !== null) {
                    $col = chr(66 + $colIndex);
                    $cell = $col.$row;
                    $sheet->setCellValue($cell, $severity);
                    $this->addSecurityNeedColor($sheet, $cell, (int) $severity);
                    $sheet->getStyle($cell)->applyFromArray($centered);
                }
            }
        }

        // 6. Générer le fichier Excel en téléchargement
        $writer = new Xlsx($spreadsheet);
        $filename = 'impacts-'.Carbon::today()->format('Ymd').'.xlsx';

        // 7. Envoyer en réponse HTTP
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
