<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Admin\CartographyController;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;

class ReportController extends Controller
{
    protected static function addText(Section $section, ?string $value = null)
    {
        try {
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, str_replace('<br>', '<br/>', $value));
        } catch (\Exception $e) {
            $section->addText('Invalid text');
            Log::error('CartographyController - Invalid HTML '.$value);
        }
    }

    protected static function addSecurityNeedColor(Worksheet $sheet, string $cell, ?int $i)
    {
        static $colors = [-1 => 'FFFFFF', 0 => 'FFFFFF', 1 => '8CD17D', 2 => 'F1CE63', 3 => 'F28E2B', 4 => 'E15759'];
        $sheet->getStyle($cell)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($colors[$i === null ? 0 : $i]);
    }
    
    protected static function addTable(Section $section, ?string $title = null)
    {
        $table = $section->addTable(
            [
                'borderSize' => 2,
                'borderColor' => '006699',
                'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            ]
        );
        $table->addRow();
        if ($title !== null) {
            $table->addCell(8000, ['gridSpan' => 2])
                ->addText(
                    $title,
                    CartographyController::FANCYTABLETITLESTYLE,
                    CartographyController::NOSPACE
                );
        }

        return $table;
    }

    protected static function addTextRow(Table $table, string $title, ?string $value = null)
    {
        $table->addRow();
        $table->addCell(2000, CartographyController::NOSPACE)->addText($title, CartographyController::FANCYLEFTTABLECELLSTYLE, CartographyController::NOSPACE);
        $table->addCell(6000, CartographyController::NOSPACE)->addText($value, CartographyController::FANCYRIGHTTABLECELLSTYLE, CartographyController::NOSPACE);
    }

    protected static function addHTMLRow(Table $table, string $title, ?string $value = null)
    {
        $table->addRow();
        $table->addCell(2000)->addText($title, CartographyController::FANCYLEFTTABLECELLSTYLE, CartographyController::NOSPACE);
        try {
            \PhpOffice\PhpWord\Shared\Html::addHtml($table->addCell(6000), str_replace('<br>', '<br/>', $value));
        } catch (\Exception $e) {
            Log::error('CartographyController - Invalid HTML '.$value);
        }
    }

    // Return the Excel column index from 0 to 52
    protected static function col(int $i)
    {
        if ($i < 26) {
            return chr(ord('A') + $i);
        }

        return 'A'.chr(ord('A') + $i - 26);
    }
}
