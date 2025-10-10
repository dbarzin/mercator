<?php

namespace App\Http\Controllers\Report;

// GDPR
// ecosystem
use App\Http\Controllers\Admin\CartographyController;
use App\Http\Controllers\Controller;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Database;
use App\Models\ExternalConnectedEntity;
use App\Models\Information;
use App\Models\LogicalServer;
use App\Models\MacroProcessus;
use App\Models\MApplication;
use App\Models\Peripheral;
use App\Models\Phone;
use App\Models\PhysicalRouter;
use App\Models\PhysicalSecurityDevice;
use App\Models\PhysicalServer;
use App\Models\PhysicalSwitch;
use App\Models\Process;
use App\Models\Site;
use App\Models\StorageDevice;
use App\Models\Subnetwork;
use App\Models\Vlan;
use App\Models\WifiTerminal;
use App\Models\Workstation;
use Carbon\Carbon;
use Gate;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use Symfony\Component\HttpFoundation\Response;

// information system
// Application
// Administration
// Logique
// Physique
// Laravel
// PhpOffice
// see : https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/

class ReportController extends Controller
{

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

    // *************************************************************

    public function logicalServers()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServers = LogicalServer::All()->sortBy('name');
        $logicalServers->load('applications', 'applications.applicationBlock');

        $header = [
            trans('cruds.logicalServer.title_singular'),
            trans('cruds.logicalServer.fields.type'),
            trans('cruds.application.title_singular'),
            trans('cruds.application.fields.entities'),
            trans('cruds.application.fields.entity_resp'),
            trans('cruds.application.fields.responsible'),
            trans('cruds.applicationBlock.title_singular'),
            trans('cruds.applicationBlock.fields.responsible'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
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
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        // converter
        // $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($logicalServers as $logicalServer) {
            foreach ($logicalServer->applications as $application) {
                $sheet->setCellValue("A{$row}", $logicalServer->name);
                $sheet->setCellValue("B{$row}", $logicalServer->type);
                $sheet->setCellValue("C{$row}", $application->name);

                $entities = $application->entities()->get();
                $l = null;
                foreach ($entities as $entity) {
                    if ($l === null) {
                        $l = $entity->name;
                    } else {
                        $l .= ', '.$entity->name;
                    }
                }
                $sheet->setCellValue("D{$row}", $l);

                $l = $application->entityResp()->get();
                if ($l->count() > 0) {
                    $sheet->setCellValue("E{$row}", $l[0]->name);
                }

                $sheet->setCellValue("F{$row}", $application->responsible);
                $sheet->setCellValue("G{$row}", $application->application_block->name ?? '');
                $sheet->setCellValue("H{$row}", $application->application_block->responsible ?? '');

                $row++;
            }
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/logicalServers-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/logicalServers-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function externalAccess()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accesses = ExternalConnectedEntity::All()->sortBy('name');
        $accesses->load('entity', 'network');

        $header = [
            trans('cruds.externalConnectedEntity.fields.name'),
            trans('cruds.externalConnectedEntity.fields.type'),
            trans('cruds.entity.fields.name'),
            trans('cruds.entity.fields.description'),
            trans('cruds.entity.fields.contact_point'),
            trans('cruds.externalConnectedEntity.fields.description'),
            trans('cruds.externalConnectedEntity.fields.contacts'),
            trans('cruds.externalConnectedEntity.fields.network'),
            trans('cruds.externalConnectedEntity.fields.src'),
            trans('cruds.externalConnectedEntity.fields.src'),
            trans('cruds.externalConnectedEntity.fields.dest'),
            trans('cruds.externalConnectedEntity.fields.dest'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(150, 'pt'); // description
        $sheet->getColumnDimension('E')->setWidth(150, 'pt'); // contact point
        $sheet->getColumnDimension('F')->setWidth(150, 'pt'); // reason
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the Timesheet
        $row = 2;
        foreach ($accesses as $access) {
            $sheet->setCellValue("A{$row}", $access->name);
            $sheet->setCellValue("B{$row}", $access->type);
            $sheet->setCellValue("C{$row}", $access->entity ? $access->entity->name : '');
            $sheet->setCellValue("D{$row}", $access->entity ? $html->toRichTextObject($access->entity->description) : '');
            $sheet->setCellValue("E{$row}", $access->entity ? $html->toRichTextObject($access->entity->contact_point) : '');
            $sheet->setCellValue("F{$row}", $html->toRichTextObject($access->description));
            $sheet->setCellValue("G{$row}", $access->contacts);
            $sheet->setCellValue("H{$row}", $access->network ? $access->network->name : '');
            $sheet->setCellValue("I{$row}", $access->src_desc);
            $sheet->setCellValue("J{$row}", $access->src);
            $sheet->setCellValue("K{$row}", $access->dest_desc);
            $sheet->setCellValue("L{$row}", $access->dest);

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/externalAccess-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/externalAccess-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);

    }

    public function logicalServerConfigs()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServers = LogicalServer::All()->sortBy('name');
        $logicalServers->load('applications', 'physicalServers');

        $header = [
            trans('cruds.logicalServer.fields.name'),               // A
            trans('cruds.logicalServer.fields.operating_system'),   // B
            trans('cruds.logicalServer.fields.environment'),        // C
            trans('cruds.logicalServer.fields.install_date'),       // D
            trans('cruds.logicalServer.fields.update_date'),        // E
            trans('cruds.logicalServer.fields.cpu'),                // F
            trans('cruds.logicalServer.fields.memory'),             // G
            trans('cruds.logicalServer.fields.disk'),               // H
            trans('cruds.logicalServer.fields.net_services'),       // I
            trans('cruds.logicalServer.fields.address_ip'),         // J
            trans('cruds.logicalServer.fields.configuration'),      // K
            trans('cruds.logicalServer.fields.applications'),       // L
            trans('cruds.application.fields.application_block'),    // M
            trans('cruds.logicalServer.fields.cluster'),            // N
            trans('cruds.logicalServer.fields.servers'),            // O
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);

        // center (CPU, Men, Disk)
        $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the Timesheet
        $row = 2;
        foreach ($logicalServers as $logicalServer) {
            $sheet->setCellValue("A{$row}", $logicalServer->name);
            $sheet->setCellValue("B{$row}", $logicalServer->operating_system);
            $sheet->setCellValue("C{$row}", $logicalServer->environment);
            $sheet->setCellValue("D{$row}", $logicalServer->install_date);
            $sheet->setCellValue("E{$row}", $logicalServer->update_date);
            $sheet->setCellValue("F{$row}", $logicalServer->cpu);
            $sheet->setCellValue("G{$row}", $logicalServer->memory);
            $sheet->setCellValue("H{$row}", $logicalServer->disk);
            $sheet->setCellValue("I{$row}", $logicalServer->net_services);
            $sheet->setCellValue("J{$row}", $logicalServer->address_ip);
            $sheet->setCellValue("K{$row}", $html->toRichTextObject($logicalServer->configuration));
            $sheet->setCellValue("L{$row}", $logicalServer->applications->implode('name', ', '));
            $sheet->setCellValue("M{$row}", $logicalServer->applications->first() !== null ?
                ($logicalServer->applications->first()->application_block_id !== null ? $logicalServer->applications->first()->applicationBlock->name : '') : '');
            $sheet->setCellValue("N{$row}", $logicalServer->cluster->name ?? '');
            $sheet->setCellValue("O{$row}", $logicalServer->physicalServers->implode('name', ', '));

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/logicalServers-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/logicalServers-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function securityNeeds()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // macroprocess - process - application - base de données - information
        $header = [];
        array_push(
            $header,
            trans('cruds.macroProcessus.title'),
            trans('global.confidentiality_short'),
            trans('global.integrity_short'),
            trans('global.availability_short'),
            trans('global.tracability_short')
        );
        if (config('mercator-config.parameters.security_need_auth')) {
            array_push($header, trans('global.authenticity_short'));
        }
        array_push(
            $header,
            trans('cruds.process.title'),
            trans('global.confidentiality_short'),
            trans('global.integrity_short'),
            trans('global.availability_short'),
            trans('global.tracability_short')
        );
        if (config('mercator-config.parameters.security_need_auth')) {
            array_push($header, trans('global.authenticity_short'));
        }
        array_push(
            $header,
            trans('cruds.application.title'),
            trans('global.confidentiality_short'),
            trans('global.integrity_short'),
            trans('global.availability_short'),
            trans('global.tracability_short')
        );
        if (config('mercator-config.parameters.security_need_auth')) {
            array_push($header, trans('global.authenticity_short'));
        }
        array_push(
            $header,
            trans('cruds.database.title'),
            trans('global.confidentiality_short'),
            trans('global.integrity_short'),
            trans('global.availability_short'),
            trans('global.tracability_short')
        );
        if (config('mercator-config.parameters.security_need_auth')) {
            array_push($header, trans('global.authenticity_short'));
        }
        array_push(
            $header,
            trans('cruds.information.title'),
            trans('global.confidentiality_short'),
            trans('global.integrity_short'),
            trans('global.availability_short'),
            trans('global.tracability_short')
        );
        if (config('mercator-config.parameters.security_need_auth')) {
            array_push($header, trans('global.authenticity_short'));
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // Widths
        $i = 0;
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        }
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        }
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        }
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        }
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getColumnDimension(self::col($i++))->setWidth(12, 'pt');
        }

        // Center
        $i = 1;
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        $i++;
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        $i++;
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        $i++;
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        $i++;
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Populate the Timesheet
        $row = 2;

        // loop
        $macroprocesses = MacroProcessus::with('processes')->get();
        foreach ($macroprocesses as $macroprocess) {
            if ($macroprocess->processes->count() === 0) {
                $this->addLine($sheet, $row, $macroprocess, null, null, null, null);
                $row++;
            } else {
                foreach ($macroprocess->processes as $process) {
                    if ($process->applications->count() === 0) {
                        $this->addLine($sheet, $row, $macroprocess, $process, null, null, null);
                        $row++;
                    } else {
                        foreach ($process->applications as $application) {
                            if ($application->databases->count() === 0) {
                                $this->addLine($sheet, $row, $macroprocess, $process, $application, null, null);
                                $row++;
                            } else {
                                foreach ($application->databases as $database) {
                                    if ($database->informations->count() === 0) {
                                        $this->addLine($sheet, $row, $macroprocess, $process, $application, $database, null);
                                        $row++;
                                    } else {
                                        foreach ($database->informations as $information) {
                                            $this->addLine($sheet, $row, $macroprocess, $process, $application, $database, $information);
                                            $row++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/securityNeeds-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/securityNeeds-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function physicalInventory()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory = [];

        // for all sites
        $sites = Site::All()->sortBy('name');
        foreach ($sites as $site) {
            $this->addToInventory($inventory, $site);

            // for all buildings
            $buildings = Building::where('site_id', '=', $site->id)->orderBy('name')->get();
            foreach ($buildings as $building) {
                $this->addToInventory($inventory, $site, $building);

                // for all bays
                $bays = Bay::where('room_id', '=', $building->id)->orderBy('name')->get();
                foreach ($bays as $bay) {
                    $this->addToInventory($inventory, $site, $building, $bay);
                }
            }
        }

        $header = [
            'Site',
            'Room',
            'Bay',
            'Asset',
            'Name',
            'Type',
            'Description',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the Timesheet
        $row = 2;

        // create the sheet
        foreach ($inventory as $item) {
            $sheet->setCellValue("A{$row}", $item['site']);
            $sheet->setCellValue("B{$row}", $item['room']);
            $sheet->setCellValue("C{$row}", $item['bay']);
            $sheet->setCellValue("D{$row}", $item['category']);
            $sheet->setCellValue("E{$row}", $item['name']);
            $sheet->setCellValue("F{$row}", $item['type']);
            $sheet->setCellValue("G{$row}", $html->toRichTextObject($item['description']));

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/physicalInventory-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/physicalInventory-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function workstations()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstations = Workstation::All()->sortBy('name');
        $workstations->load('applications', 'site', 'building');

        $header = [
            'Name',
            'Type',
            'Description',
            'OS',
            'IP',
            'Applications',
            'CPU',
            'RAM',
            'Disk',
            'Room',
            'Building',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(100, 'pt'); // description
        $sheet->getColumnDimension('D')->setAutoSize(true); // OS
        $sheet->getColumnDimension('E')->setAutoSize(true); // IP
        $sheet->getColumnDimension('F')->setWidth(100, 'pt'); // applications
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the Timesheet
        $row = 2;

        // create the sheet
        foreach ($workstations as $ws) {
            $sheet->setCellValue("A{$row}", $ws->name);
            $sheet->setCellValue("B{$row}", $ws->type);
            $sheet->setCellValue("C{$row}", $html->toRichTextObject($ws->description));
            $sheet->setCellValue("D{$row}", $ws->operating_system);
            $sheet->setCellValue("E{$row}", $ws->address_ip);

            $txt = '';
            foreach ($ws->applications as $application) {
                $txt .= $application->name;
                if ($ws->applications->last() !== $application) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("F{$row}", $txt);

            $sheet->setCellValue("G{$row}", $ws->cpu);
            $sheet->setCellValue("H{$row}", $ws->memory);
            $sheet->setCellValue("I{$row}", $ws->disk);
            $sheet->setCellValue("J{$row}", $ws->site ? $ws->site->name : '');
            $sheet->setCellValue("K{$row}", $ws->building ? $ws->building->name : '');

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/physicalInventory-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/physicalInventory-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function vlans()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vlans = Vlan::orderBy('vlans.name')->get();
        $vlans->load('subnetworks');

        $lservers = LogicalServer::orderBy('name')->get();
        $pservers = PhysicalServer::orderBy('name')->get();
        $switches = PhysicalSwitch::orderBy('name')->get();
        $workstations = Workstation::orderBy('name')->get();

        $header = [
            'Name',
            'VLAN-ID',
            'Description',
            'subnet name',
            'subnet address',
            'Logical Servers',
            'Physical Servers',
            'Switches',
            'Workstations',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true); // Name
        $sheet->getColumnDimension('B')->setWidth(30, 'pt'); // VLAN-ID
        $sheet->getColumnDimension('C')->setWidth(200, 'pt'); // Desc
        $sheet->getColumnDimension('D')->setWidth(100, 'pt'); // subnets name
        $sheet->getColumnDimension('E')->setWidth(100, 'pt'); // subnets address
        $sheet->getColumnDimension('F')->setWidth(200, 'pt'); // logical servers
        $sheet->getColumnDimension('G')->setWidth(200, 'pt'); // physical servers
        $sheet->getColumnDimension('H')->setWidth(200, 'pt'); // switches
        $sheet->getColumnDimension('I')->setWidth(200, 'pt'); // workstations

        // wordwrap
        $sheet->getStyle('F')->getAlignment()->setWrapText(true);
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the Timesheet
        $row = 2;

        // create the sheet
        foreach ($vlans as $vlan) {
            $sheet->setCellValue("A{$row}", $vlan->name);
            $sheet->setCellValue("B{$row}", $vlan->vlan_id);
            $sheet->setCellValue("C{$row}", $html->toRichTextObject($vlan->description));

            // Subnets
            foreach ($vlan->subnetworks as $subnet) {
                if ($vlan->subnetworks->first() !== $subnet) {
                    $sheet->setCellValue("A{$row}", $vlan->name);
                    $sheet->setCellValue("C{$row}", $html->toRichTextObject($vlan->description));
                }
                $sheet->setCellValue("D{$row}", $subnet->name);
                $sheet->setCellValue("E{$row}", $subnet->address);
                // Logical Servers
                $txt = '';
                foreach ($lservers as $server) {
                    foreach (explode(', ', $server->address_ip) as $ip) {
                        if ($subnet->contains($ip)) {
                            if (strlen($txt) > 0) {
                                $txt .= ', ';
                            }
                            $txt .= $server->name;
                        }
                    }
                }
                $sheet->setCellValue("F{$row}", $txt);
                // Physical Servers
                $txt = '';
                foreach ($pservers as $server) {
                    foreach (explode(', ', $server->address_ip) as $ip) {
                        if ($subnet->contains($ip)) {
                            if (strlen($txt) > 0) {
                                $txt .= ', ';
                            }
                            $txt .= $server->name;
                        }
                    }
                }
                $sheet->setCellValue("G{$row}", $txt);
                // Switches
                $txt = '';
                foreach ($switches as $switch) {
                    foreach (explode(', ', $switch->address_ip) as $ip) {
                        if ($subnet->contains($ip)) {
                            if (strlen($txt) > 0) {
                                $txt .= ', ';
                            }
                            $txt .= $switch->name;
                        }
                    }
                }
                $sheet->setCellValue("H{$row}", $txt);
                // Workstations
                $txt = '';
                foreach ($workstations as $workstation) {
                    foreach (explode(', ', $workstation->address_ip) as $ip) {
                        if ($subnet->contains($ip)) {
                            if (strlen($txt) > 0) {
                                $txt .= ', ';
                            }
                            $txt .= $workstation->name;
                        }
                    }
                }
                $sheet->setCellValue("I{$row}", $txt);

                if ($vlan->subnetworks->last() !== $subnet) {
                    $row++;
                }
            }

            // Workstations

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/vlans-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/vlans-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function zones()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::All()->sortBy('zone, address');

        return view('admin/reports/zones', compact('subnetworks'));
    }


    public function rto()
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
        $spreadsheet = new Spreadsheet;
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
        $filename = 'applications_by_activity.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected static function addText(Section $section, ?string $value = null)
    {
        try {
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, str_replace('<br>', '<br/>', $value));
        } catch (\Exception $e) {
            $section->addText('Invalid text');
            Log::error('CartographyController - Invalid HTML '.$value);
        }
    }

    private function addToInventory(array &$inventory, Site $site, ?Building $building = null, ?Bay $bay = null)
    {
        // PhysicalServer
        if ($bay !== null) {
            $physicalServers = PhysicalServer::where('bay_id', '=', $bay->id)->orderBy('name')->get();
        } elseif ($building !== null) {
            $physicalServers = PhysicalServer::where('bay_id', '=', null)->where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $physicalServers = PhysicalServer::where('bay_id', '=', null)->where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $physicalServers = PhysicalServer::orderBy('name')->get();
        }

        foreach ($physicalServers as $physicalServer) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => $bay->name ?? '',
                    'category' => 'Server',
                    'name' => $physicalServer->name,
                    'type' => $physicalServer->type,
                    'description' => $physicalServer->description,
                ]
            );
        }

        // Workstation;
        if ($building !== null) {
            $workstations = Workstation::where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $workstations = Workstation::where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $workstations = Workstation::orderBy('name')->get();
        }

        foreach ($workstations as $workstation) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => '',
                    'category' => 'Workstation',
                    'name' => $workstation->name,
                    'type' => $workstation->type,
                    'description' => $workstation->description,
                ]
            );
        }

        // StorageDevice;
        if ($bay !== null) {
            $storageDevices = StorageDevice::where('bay_id', '=', $bay->id)->orderBy('name')->get();
        } elseif ($building !== null) {
            $storageDevices = StorageDevice::where('bay_id', '=', null)->where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $storageDevices = StorageDevice::where('bay_id', '=', null)->where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $storageDevices = StorageDevice::orderBy('name')->get();
        }

        foreach ($storageDevices as $storageDevice) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => $bay->name ?? '',
                    'category' => 'Storage',
                    'name' => $storageDevice->name,
                    'type' => $storageDevice->name,
                    'description' => $storageDevice->description,
                ]
            );
        }

        // Peripheral
        if ($bay !== null) {
            $peripherals = Peripheral::where('bay_id', '=', $bay->id)->orderBy('name')->get();
        } elseif ($building !== null) {
            $peripherals = Peripheral::where('bay_id', '=', null)->where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $peripherals = Peripheral::where('bay_id', '=', null)->where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $peripherals = Peripheral::orderBy('name')->get();
        }

        foreach ($peripherals as $peripheral) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => $bay->name ?? '',
                    'category' => 'Peripheral',
                    'name' => $peripheral->name,
                    'type' => $peripheral->type,
                    'description' => $peripheral->description,
                ]
            );
        }

        // Phone
        if ($building !== null) {
            $phones = Phone::where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $phones = Phone::where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $phones = Phone::orderBy('name')->get();
        }

        foreach ($phones as $phone) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => '',
                    'category' => 'Phone',
                    'name' => $phone->name,
                    'type' => $phone->type,
                    'description' => $phone->description,
                ]
            );
        }

        // PhysicalSwitch
        if ($bay !== null) {
            $physicalSwitches = PhysicalSwitch::where('bay_id', '=', $bay->id)->orderBy('name')->get();
        } elseif ($building !== null) {
            $physicalSwitches = PhysicalSwitch::where('bay_id', '=', null)->where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $physicalSwitches = PhysicalSwitch::where('bay_id', '=', null)->where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $physicalSwitches = PhysicalSwitch::orderBy('name')->get();
        }

        foreach ($physicalSwitches as $physicalSwitch) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => $bay->name ?? '',
                    'category' => 'Switch',
                    'name' => $physicalSwitch->name,
                    'type' => $physicalSwitch->type,
                    'description' => $physicalSwitch->description,
                ]
            );
        }

        // PhysicalRouter
        if ($bay !== null) {
            $physicalRouters = PhysicalRouter::where('bay_id', '=', $bay->id)->orderBy('name')->get();
        } elseif ($building !== null) {
            $physicalRouters = PhysicalRouter::where('bay_id', '=', null)->where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $physicalRouters = PhysicalRouter::where('bay_id', '=', null)->where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $physicalRouters = PhysicalRouter::orderBy('name')->get();
        }

        foreach ($physicalRouters as $physicalRouter) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => $bay->name ?? '',
                    'category' => 'Router',
                    'name' => $physicalRouter->name,
                    'type' => $physicalRouter->type,
                    'description' => $physicalRouter->description,
                ]
            );
        }

        // WifiTerminal
        if ($building !== null) {
            $wifiTerminals = WifiTerminal::where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $wifiTerminals = WifiTerminal::where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $wifiTerminals = WifiTerminal::orderBy('name')->get();
        }

        foreach ($wifiTerminals as $wifiTerminal) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => '',
                    'category' => 'Wifi',
                    'name' => $wifiTerminal->name,
                    'type' => $wifiTerminal->type,
                    'description' => $wifiTerminal->description,
                ]
            );
        }

        // Physical Security Devices
        if ($bay !== null) {
            $physicalSecurityDevices = PhysicalSecurityDevice::where('bay_id', '=', $bay->id)->orderBy('name')->get();
        } elseif ($building !== null) {
            $physicalSecurityDevices = PhysicalSecurityDevice::where('bay_id', '=', null)->where('building_id', '=', $building->id)->orderBy('name')->get();
        } elseif ($site !== null) {
            $physicalSecurityDevices = PhysicalSecurityDevice::where('bay_id', '=', null)->where('building_id', '=', null)->where('site_id', '=', $site->id)->orderBy('name')->get();
        } else {
            $physicalSecurityDevices = PhysicalSecurityDevice::orderBy('name')->get();
        }

        foreach ($physicalSecurityDevices as $physicalSecurityDevice) {
            array_push(
                $inventory,
                [
                    'site' => $site->name ?? '',
                    'room' => $building->name ?? '',
                    'bay' => $bay->name ?? '',
                    'category' => 'Sécurité',
                    'name' => $physicalSecurityDevice->name,
                    'type' => $physicalSecurityDevice->type,
                    'description' => $physicalSecurityDevice->description,
                ]
            );
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

    protected static function addLine(
        Worksheet $sheet,
        int $row,
        MacroProcessus $macroprocess,
        ?Process $process = null,
        ?MApplication $application = null,
        ?Database $database = null,
        ?Information $information = null
    ) {
        // Macroprocessus
        $i = 0;
        $sheet->setCellValue(self::col($i++).$row, $macroprocess->name);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_c >= 0 ? $macroprocess->security_need_c : '');
        self::addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_c);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_i >= 0 ? $macroprocess->security_need_i : '');
        self::addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_i);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_a >= 0 ? $macroprocess->security_need_a : '');
        self::addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_a);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_t >= 0 ? $macroprocess->security_need_t : '');
        self::addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_t);

        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_auth >= 0 ? $macroprocess->security_need_auth : '');
            self::addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_auth);
        }

        if ($process !== null) {
            // Processus
            $sheet->setCellValue(self::col($i++).$row, $process->name);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_c >= 0 ? $process->security_need_c : '');
            self::addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_c);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_i >= 0 ? $process->security_need_i : '');
            self::addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_i);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_a >= 0 ? $process->security_need_a : '');
            self::addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_a);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_t >= 0 ? $process->security_need_t : '');
            self::addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_t);

            if (config('mercator-config.parameters.security_need_auth')) {
                $sheet->setCellValue(self::col($i).$row, $process->security_need_auth >= 0 ? $process->security_need_auth : '');
                self::addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_auth);
            }

            if ($application !== null) {
                // Application
                $sheet->setCellValue(self::col($i++).$row, $application->name);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_c >= 0 ? $application->security_need_c : '');
                self::addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_c);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_i >= 0 ? $application->security_need_i : '');
                self::addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_i);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_a >= 0 ? $application->security_need_a : '');
                self::addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_a);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_t >= 0 ? $application->security_need_t : '');
                self::addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_t);

                if (config('mercator-config.parameters.security_need_auth')) {
                    $sheet->setCellValue(self::col($i).$row, $application->security_need_auth >= 0 ? $application->security_need_auth : '');
                    self::addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_auth);
                }

                if ($database !== null) {
                    // Database
                    $sheet->setCellValue(self::col($i++).$row, $database->name);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_c >= 0 ? $database->security_need_c : '');
                    self::addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_c);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_i >= 0 ? $database->security_need_i : '');
                    self::addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_i);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_a >= 0 ? $database->security_need_a : '');
                    self::addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_a);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_t >= 0 ? $database->security_need_t : '');
                    self::addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_t);

                    if (config('mercator-config.parameters.security_need_auth')) {
                        $sheet->setCellValue(self::col($i).$row, $database->security_need_auth >= 0 ? $database->security_need_auth : '');
                        self::addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_auth);
                    }

                    if ($information !== null) {
                        // Information
                        $sheet->setCellValue(self::col($i++).$row, $information->name);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_c >= 0 ? $information->security_need_c : '');
                        self::addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_c);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_i >= 0 ? $information->security_need_i : '');
                        self::addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_i);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_a >= 0 ? $information->security_need_a : '');
                        self::addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_a);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_t >= 0 ? $information->security_need_t : '');
                        self::addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_t);

                        if (config('mercator-config.parameters.security_need_auth')) {
                            $sheet->setCellValue(self::col($i).$row, $information->security_need_auth >= 0 ? $information->security_need_auth : '');
                            self::addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_auth);
                        }
                    }
                }
            }
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
