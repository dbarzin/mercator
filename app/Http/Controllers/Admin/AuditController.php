<?php

namespace App\Http\Controllers\Admin;

// ecosystem
use App\Activity;
use App\Actor;
// information system
use App\Annuaire;
use App\ApplicationBlock;
use App\ApplicationModule;
use App\ApplicationService;
use App\Bay;
use App\Building;
use App\Certificate;
// Applications
use App\Database;
use App\DhcpServer;
use App\Dnsserver;
use App\DomaineAd;
use App\Entity;
use App\ExternalConnectedEntity;
// Administration
use App\Flux;
use App\ForestAd;
use App\Gateway;
use App\Http\Controllers\Controller;
// Logique
use App\Information;
use App\LogicalServer;
use App\MacroProcessus;
use App\MApplication;
use App\Network;
use App\NetworkSwitch;
use App\Operation;
use App\Peripheral;
use App\Phone;
use App\PhysicalRouter;
use App\PhysicalSecurityDevice;
// Physique
use App\PhysicalServer;
use App\PhysicalSwitch;
use App\Process;
use App\Relation;
use App\Router;
use App\SecurityDevice;
use App\Site;
use App\StorageDevice;
use App\Subnetwork;
use App\Task;
use App\Vlan;
use App\WifiTerminal;
use App\Workstation;
use App\ZoneAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// PhpOffice
// see : https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AuditController extends HomeController
{
    
    public function maturity() {
        $levels = $this->computeMaturity();

        $path = storage_path('app/levels.xlsx');

        $header = [
            'Objet',
            '#',
            'Maturity1',
            '#',
            'Maturity2',
            '#',
            'Maturity3',
            ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        // center cells
        $sheet->getStyle('B:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // percentage
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

        // Initialise row count
        $row = 2;

        // ============
        // Ecosystem
        // ============
        $sheet->setCellValue("A{$row}", trans("cruds.menu.ecosystem.title_short"));
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        // L1
        $denominator = $levels['entities']+$levels['relations'];
        $sheet->setCellValue("B{$row}", $denominator); 
        $sheet->setCellValue("C{$row}", 
            $denominator>0 ? 
            ($levels['entities_lvl1']+$levels['relations_lvl1']) / $denominator 
            : 0 );
        // L2
        $denominator = $levels['entities']+$levels['relations'];
        $sheet->setCellValue("D{$row}", $denominator); 
        $sheet->setCellValue("E{$row}", 
            $denominator>0 ? 
            ($levels['entities_lvl1']+$levels['relations_lvl1']) / $denominator 
            : 0 );
        // L3
        $denominator = $levels['entities']+$levels['relations'];
        $sheet->setCellValue("F{$row}", $denominator); 
        $sheet->setCellValue("G{$row}", 
            $denominator>0 ? 
            ($levels['entities_lvl1']+$levels['relations_lvl1']) / $denominator 
            : 0 );
        $row++;

        // Entities
        $sheet->setCellValue("A{$row}", trans("cruds.entity.title"));
        $sheet->setCellValue("B{$row}", $levels['entities']);
        $sheet->setCellValue("C{$row}", ($levels['entities']>0 ? $levels['entities_lvl1']  / $levels['entities'] : 0));
        $sheet->setCellValue("D{$row}", $levels['entities']);
        $sheet->setCellValue("F{$row}", ($levels['entities']>0 ? $levels['entities_lvl1']  / $levels['entities'] : 0));
        $sheet->setCellValue("E{$row}", $levels['entities']);
        $sheet->setCellValue("G{$row}", ($levels['entities']>0 ? $levels['entities_lvl1']  / $levels['entities'] : 0));
        $row++;

        // Relations
        $sheet->setCellValue("A{$row}", trans("cruds.relation.title"));
        $sheet->setCellValue("B{$row}", $levels['relations']);
        $sheet->setCellValue("C{$row}", $levels['relations']>0 ? $levels['relations_lvl1'] / $levels['relations'] : 0);
        $sheet->setCellValue("D{$row}", $levels['relations']);
        $sheet->setCellValue("E{$row}", $levels['relations']>0 ? $levels['relations_lvl1'] / $levels['relations'] : 0);
        $sheet->setCellValue("F{$row}", $levels['relations']);
        $sheet->setCellValue("G{$row}", $levels['relations']>0 ? $levels['relations_lvl1'] / $levels['relations'] : 0);
        $row++;

        // ============
        // Metier
        // ============
        $sheet->setCellValue("A{$row}", trans("cruds.menu.metier.title_short"));
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);

        // L1
        $denominator = $levels['processes']+$levels['operations']+$levels['informations'];
        $sheet->setCellValue("B{$row}", $denominator);
        $sheet->setCellValue("C{$row}", 
            $denominator>0 ? 
            (
                $levels['processes_lvl1']+$levels['operations_lvl1']+$levels['informations_lvl1']
            ) / $denominator 
            : 0 );

        // L2
        $denominator = $levels['macroProcessuses']+$levels['processes']+$levels['operations']+$levels['actors']+$levels['informations'];
        $sheet->setCellValue("D{$row}", $denominator);
        $sheet->setCellValue("E{$row}", 
            $denominator>0 ? 
            (
                $levels['macroProcessuses_lvl2']+$levels['processes_lvl2']+$levels['operations_lvl2']+$levels['actors_lvl2']+$levels['informations_lvl2']
            ) / $denominator 
            : 0 );

        // L3
        $denominator = $levels['macroProcessuses']+$levels['processes']+$levels['activities']+$levels['tasks']+$levels['operations']+$levels['actors']+$levels['informations'];
        $sheet->setCellValue("F{$row}", $denominator);
        $sheet->setCellValue("G{$row}", 
            $denominator>0 ? 
            (
                $levels['macroProcessuses_lvl3']+$levels['processes_lvl2']+$levels['activities_lvl3']+$levels['tasks_lvl3']+$levels['operations_lvl2']+$levels['actors_lvl2']+$levels['informations_lvl2']
            ) / $denominator 
            : 0 );
        $row++;


        // MacroProcessus
        $sheet->setCellValue("A{$row}", trans("cruds.macroProcessus.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", $levels['macroProcessuses']);
        $sheet->setCellValue("E{$row}", $levels['macroProcessuses']>0 ? $levels['macroProcessuses_lvl2']  / $levels['macroProcessuses'] : 0);
        $sheet->setCellValue("F{$row}", $levels['macroProcessuses']);
        $sheet->setCellValue("G{$row}", $levels['macroProcessuses']>0 ? $levels['macroProcessuses_lvl2']  / $levels['macroProcessuses'] : 0);
        $row++;

        // Process
        $sheet->setCellValue("A{$row}", trans("cruds.process.title"));
        $sheet->setCellValue("B{$row}", $levels['processes']);
        $sheet->setCellValue("C{$row}", $levels['processes']>0 ? $levels['processes_lvl1']  / $levels['processes'] : 0);
        $sheet->setCellValue("D{$row}", $levels['processes']);
        $sheet->setCellValue("E{$row}", $levels['processes']>0 ? $levels['processes_lvl2']  / $levels['processes'] : 0);
        $sheet->setCellValue("F{$row}", $levels['processes']);
        $sheet->setCellValue("G{$row}", $levels['processes']>0 ? $levels['processes_lvl2']  / $levels['processes'] : 0);
        $row++;

        // Activity
        $sheet->setCellValue("A{$row}", trans("cruds.activity.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", "");
        $sheet->setCellValue("E{$row}", "");
        $sheet->setCellValue("F{$row}", $levels['activities']);
        $sheet->setCellValue("G{$row}", $levels['activities']>0 ? $levels['activities_lvl3']  / $levels['activities'] : 0);
        $row++;

        // Operation
        $sheet->setCellValue("A{$row}", trans("cruds.operation.title"));
        $sheet->setCellValue("B{$row}", $levels['operations']);
        $sheet->setCellValue("C{$row}", $levels['operations']>0 ? $levels['operations_lvl1']  / $levels['operations'] : 0);
        $sheet->setCellValue("D{$row}", $levels['operations']);
        $sheet->setCellValue("E{$row}", $levels['operations']>0 ? $levels['operations_lvl2']  / $levels['operations'] : 0);
        $sheet->setCellValue("F{$row}", $levels['operations']);
        $sheet->setCellValue("G{$row}", $levels['operations']>0 ? $levels['operations_lvl3']  / $levels['operations'] : 0);
        $row++;

        // Tâche
        $sheet->setCellValue("A{$row}", trans("cruds.task.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", "");
        $sheet->setCellValue("E{$row}", "");
        $sheet->setCellValue("F{$row}", $levels['tasks']);
        $sheet->setCellValue("G{$row}", $levels['tasks']>0 ? $levels['tasks_lvl3']  / $levels['tasks'] : 0);
        $row++;

        // Acteur
        $sheet->setCellValue("A{$row}", trans("cruds.actor.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", $levels['actors']);
        $sheet->setCellValue("E{$row}", $levels['actors']>0 ? $levels['actors_lvl2']  / $levels['actors'] : 0);
        $sheet->setCellValue("F{$row}", $levels['actors']);
        $sheet->setCellValue("G{$row}", $levels['actors']>0 ? $levels['actors_lvl2']  / $levels['actors'] : 0);
        $row++;

        // Information
        $sheet->setCellValue("A{$row}", trans("cruds.information.title"));
        $sheet->setCellValue("B{$row}", $levels['informations']);
        $sheet->setCellValue("C{$row}", $levels['informations']>0 ? $levels['informations_lvl1']  / $levels['informations'] : 0);
        $sheet->setCellValue("D{$row}", $levels['informations']);
        $sheet->setCellValue("E{$row}", $levels['informations']>0 ? $levels['informations_lvl2']  / $levels['informations'] : 0);
        $sheet->setCellValue("F{$row}", $levels['informations']);
        $sheet->setCellValue("G{$row}", $levels['informations']>0 ? $levels['informations_lvl2']  / $levels['informations'] : 0);
        $row++;

        // ============
        // Application
        // ============
        $sheet->setCellValue("A{$row}", trans("cruds.menu.application.title_short"));
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);

        // L1
        $denominator = $levels['applications']+$levels['databases']+$levels['databases'];
        $sheet->setCellValue("B{$row}", $denominator);
        $sheet->setCellValue("C{$row}", 
            $denominator>0 ? 
            (
                $levels['applications_lvl1']+$levels['databases_lvl1']+$levels['fluxes_lvl1']
            ) / $denominator 
            : 0 );

        // L2
        $denominator = $levels['applicationBlocks']+$levels['applications']+$levels['applicationServices']+$levels['applicationModules']+$levels['databases']+$levels['fluxes'];
        $sheet->setCellValue("D{$row}", $denominator);
        $sheet->setCellValue("E{$row}", 
            $denominator>0 ? 
            (  
                $levels['applicationBlocks_lvl2']+$levels['applications_lvl2']+$levels['applicationServices_lvl2']+$levels['applicationModules_lvl2']+$levels['databases_lvl2']+$levels['fluxes_lvl1']
            ) / $denominator 
            : 0 );

        // L3
        $denominator = $levels['applicationBlocks']+$levels['applications']+$levels['applicationServices']+$levels['applicationModules']+$levels['databases']+$levels['fluxes'];
        $sheet->setCellValue("F{$row}", $denominator);
        $sheet->setCellValue("G{$row}", 
            $denominator>0 ? 
            (
                $levels['applicationBlocks_lvl2']+$levels['applications_lvl2']+$levels['applicationServices_lvl2']+$levels['applicationModules_lvl2']+$levels['databases_lvl2']+$levels['fluxes_lvl1']
            ) / $denominator 
            : 0 );
        $row++;


        // Block applicatif
        $sheet->setCellValue("A{$row}", trans("cruds.applicationBlock.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", $levels['applicationBlocks']);
        $sheet->setCellValue("E{$row}", $levels['applicationBlocks']>0 ? $levels['applicationBlocks_lvl2']  / $levels['applicationBlocks'] : 0);
        $sheet->setCellValue("F{$row}", $levels['applicationBlocks']);
        $sheet->setCellValue("G{$row}", $levels['applicationBlocks']>0 ? $levels['applicationBlocks_lvl2']  / $levels['applicationBlocks'] : 0);
        $row++;

        // Applications
        $sheet->setCellValue("A{$row}", trans("cruds.application.title"));
        $sheet->setCellValue("B{$row}", $levels['applications']);
        $sheet->setCellValue("C{$row}", $levels['applications']>0 ? $levels['applications_lvl1']  / $levels['applications'] : 0);
        $sheet->setCellValue("D{$row}", $levels['applications']);
        $sheet->setCellValue("E{$row}", $levels['applications']>0 ? $levels['applications_lvl2']  / $levels['applications'] : 0);
        $sheet->setCellValue("F{$row}", $levels['applications']);
        $sheet->setCellValue("G{$row}", $levels['applications']>0 ? $levels['applications_lvl2']  / $levels['applications'] : 0);
        $row++;

        // applicationService
        $sheet->setCellValue("A{$row}", trans("cruds.applicationService.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", $levels['applicationServices']);
        $sheet->setCellValue("E{$row}", $levels['applicationServices']>0 ? $levels['applicationServices_lvl2']  / $levels['applicationServices'] : 0);
        $sheet->setCellValue("F{$row}", $levels['applicationServices']);
        $sheet->setCellValue("G{$row}", $levels['applicationServices']>0 ? $levels['applicationServices_lvl2']  / $levels['applicationServices'] : 0);
        $row++;

        // applicationModule
        $sheet->setCellValue("A{$row}", trans("cruds.applicationModule.title"));
        $sheet->setCellValue("B{$row}", "");
        $sheet->setCellValue("C{$row}", "");
        $sheet->setCellValue("D{$row}", $levels['applicationModules']);
        $sheet->setCellValue("E{$row}", $levels['applicationModules']>0 ? $levels['applicationModules_lvl2']  / $levels['applicationModules'] : 0);
        $sheet->setCellValue("F{$row}", $levels['applicationModules']);
        $sheet->setCellValue("G{$row}", $levels['applicationModules']>0 ? $levels['applicationModules_lvl2']  / $levels['applicationModules'] : 0);
        $row++;

        // database
        $sheet->setCellValue("A{$row}", trans("cruds.database.title"));
        $sheet->setCellValue("B{$row}", $levels['databases']);
        $sheet->setCellValue("C{$row}", $levels['databases']>0 ? $levels['databases_lvl1']  / $levels['databases'] : 0);
        $sheet->setCellValue("D{$row}", $levels['databases']);
        $sheet->setCellValue("E{$row}", $levels['databases']>0 ? $levels['databases_lvl2']  / $levels['databases'] : 0);
        $sheet->setCellValue("F{$row}", $levels['databases']);
        $sheet->setCellValue("G{$row}", $levels['databases']>0 ? $levels['databases_lvl2']  / $levels['databases'] : 0);
        $row++;

        // flux
        $sheet->setCellValue("A{$row}", trans("cruds.flux.title"));
        $sheet->setCellValue("B{$row}", $levels['fluxes']);
        $sheet->setCellValue("C{$row}", $levels['fluxes']>0 ? $levels['fluxes_lvl1']  / $levels['fluxes'] : 0);
        $sheet->setCellValue("D{$row}", $levels['fluxes']);
        $sheet->setCellValue("E{$row}", $levels['fluxes']>0 ? $levels['fluxes_lvl1']  / $levels['fluxes'] : 0);
        $sheet->setCellValue("F{$row}", $levels['fluxes']);
        $sheet->setCellValue("G{$row}", $levels['fluxes']>0 ? $levels['fluxes_lvl1']  / $levels['fluxes'] : 0);
        $row++;


        // Save sheet        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        // Return
        return response()->download($path);

    }

    public function changes() {
		$current = Carbon::now();

        $path = storage_path('app/changes.xlsx');

    	/*
    	select subject_type, description, YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count 
    	from audit_logs
    	where created_at >= now() - INTERVAL 12 month
    	group by subject_type, description, YEAR(created_at), MONTH(created_at);
		*/

        $auditLogs = DB::table('audit_logs')
             ->select(DB::raw('subject_type, description, YEAR(created_at), MONTH(created_at), count(*) as count'))
             ->where('created_at', '>=', Carbon::now()->startOfMonth()->addMonth(-12))
             // ->where('subject_type','=','App\\Relation')
             ->groupBy('subject_type', 'description', 'YEAR(created_at)', 'MONTH(created_at)')
             ->get();

        //dd($auditLogs);

        $header = [
            trans('Objet'),
            trans('Action'),
            Carbon::now()->startOfMonth()->addMonth(-12)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-11)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-10)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-9)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-8)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-7)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-6)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-5)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-4)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-3)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-2)->format('m/Y'),
            Carbon::now()->startOfMonth()->addMonth(-1)->format('m/Y'),
            Carbon::now()->startOfMonth()->format('m/Y'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // background color
        $sheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F77BE');
        $sheet->getStyle('A2:O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
        $sheet->getStyle('A9:O9')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
        $sheet->getStyle('A31:O31')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
        $sheet->getStyle('A50:O50')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
        $sheet->getStyle('A63:O63')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');
        $sheet->getStyle('A93:O93')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAEC7E8');

        // column size
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

        // Total months
        $tMonths = Carbon::now()->year*12+Carbon::now()->month;

        // App\xxx -> index, title
        $rows = array(
            'Ecosystem' => ['index'=>2, 'title' => trans("cruds.menu.ecosystem.title_short")],
            'App\\Entity' => ['index' => 3, 'title' => trans("cruds.entity.title")],
            'App\\Relation' => ['index' => 6, 'title' => trans("cruds.relation.title")],

            'Metier' => ['index'=>9, 'title' => trans("cruds.menu.metier.title_short")], 
            'App\\MacroProcessus' => ['index' => 10, 'title' => trans("cruds.macroProcessus.title")],
            'App\\Process' => ['index' => 13, 'title' => trans("cruds.process.title")],
            'App\\Activity' => ['index' => 16, 'title' => trans("cruds.activity.title")],
            'App\\Operation' => ['index' => 19, 'title' => trans("cruds.operation.title")],
            'App\\Task' => ['index' => 22, 'title' => trans("cruds.task.title")],
            'App\\Actor' => ['index' => 25, 'title' => trans("cruds.actor.title")],
            'App\\Information' => ['index' => 28, 'title' => trans("cruds.information.title")],

            'Applications' => ['index' => 31, 'title' => trans("cruds.menu.application.title_short")],
            'App\\ApplicationBlock' => ['index' => 32, 'title' => trans("cruds.applicationBlock.title")],
            'App\\MApplication' => ['index' => 35, 'title' => trans("cruds.application.title")],
            'App\\ApplicationService' => ['index' => 38, 'title' => trans("cruds.applicationService.title")],
            'App\\ApplicationModule' => ['index' => 41, 'title' => trans("cruds.applicationModule.title")],
            'App\\Database' => ['index' => 44, 'title' => trans("cruds.database.title")],
            'App\\Flux' => ['index' => 47, 'title' => trans("cruds.flux.title")],

            'Administration' => ['index' => 50, 'title' => trans("cruds.menu.administration.title_short")],
            'App\\ZoneAdmin' => ['index' => 51, 'title' => trans("cruds.zoneAdmin.title")],
            'App\\Annuaire' => ['index' => 54, 'title' => trans("cruds.annuaire.title")],
            'App\\ForestAd' => ['index' => 57, 'title' => trans("cruds.forestAd.title")],
            'App\\DomaineAd' => ['index' => 60, 'title' => trans("cruds.domaineAd.title")],

            'LogicalInfrastructure' => ['index' => 63, 'title' => trans("cruds.menu.logical_infrastructure.title_short")],
            'App\\Network' => ['index' => 64, 'title' => trans("cruds.network.title")],
            'App\\Subnetwork' => ['index' => 67, 'title' => trans("cruds.subnetwork.title")],
            'App\\Gateway' => ['index' => 70, 'title' => trans("cruds.gateway.title")],
            'App\\ExternalConnectedEntity' => ['index' => 73, 'title' => trans("cruds.externalConnectedEntity.title")],
            'App\\NetworkSwitch' => ['index' => 76, 'title' => trans("cruds.networkSwitch.title")],
            'App\\Router' => ['index' => 79, 'title' => trans("cruds.router.title")],
            'App\\SecurityDevice' => ['index' => 81, 'title' => trans("cruds.securityDevice.title")],
            'App\\DhcpServer' => ['index' => 84, 'title' => trans("cruds.dhcpServer.title")],
            'App\\LogicalServer' => ['index' => 87, 'title' => trans("cruds.logicalServer.title")],
            'App\\Certificate' => ['index' => 90, 'title' => trans("cruds.certificate.title")],

            'PhysicalInfrastructure' => ['index' => 93, 'title' => trans("cruds.menu.physical_infrastructure.title_short")],
            'App\\Site' => ['index' => 94, 'title' => trans("cruds.site.title")],
            'App\\Building' => ['index' => 97, 'title' => trans("cruds.building.title")],
            'App\\Bay' => ['index' => 100, 'title' => trans("cruds.bay.title")],
            'App\\PhysicalServer' => ['index' => 103, 'title' => trans("cruds.physicalServer.title")],
            'App\\Workstation' => ['index' => 106, 'title' => trans("cruds.workstation.title")],
            'App\\StorageDevice' => ['index' => 109, 'title' => trans("cruds.storageDevice.title")],
            'App\\Peripheral' => ['index' => 112, 'title' => trans("cruds.peripheral.title")],
            'App\\Phone' => ['index' => 115, 'title' => trans("cruds.phone.title")],
            'App\\PhysicalRouter' => ['index' => 118, 'title' => trans("cruds.physicalRouter.title")],
            'App\\PhysicalSwitch' => ['index' => 121, 'title' => trans("cruds.physicalSwitch.title")],
            'App\\WifiTerminal' => ['index' => 124, 'title' => trans("cruds.wifiTerminal.title")],
            'App\\PhysicalSecurityDevice' => ['index' => 127, 'title' => trans("cruds.physicalSecurityDevice.title")],
            'App\\Wan' => ['index' => 130, 'title' => trans("cruds.wan.title")],
            'App\\Man' => ['index' => 133, 'title' => trans("cruds.man.title")],
            'App\\Lan' => ['index' => 136, 'title' => trans("cruds.lan.title")],
            'App\\Vlan' => ['index' => 139, 'title' => trans("cruds.vlan.title")],
        );

        // Fill sheet
        foreach($rows as $key => $row) {
            $idx = $row['index'];
            $sheet->setCellValue("A{$idx}", $row['title']);
            if (str_starts_with($key,'App\\')) {
                $sheet->setCellValue("B{$idx}", 'created');
                $sheet->getStyle("B{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF59A14F');
                $idx++;
                $sheet->setCellValue("B{$idx}", 'updated');
                $sheet->getStyle("B{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF28E2B');
                $idx++;
                $sheet->setCellValue("B{$idx}", 'deleted');
                $sheet->getStyle("B{$idx}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE15759');

                // backgroundColor: ['#E15759', '#F28E2B', '#59A14F'],

            }
            else {
                $sheet->getStyle("A{$idx}")->getFont()->setBold(true);                
            }
        }

        // Populate the Timesheet
        foreach ($auditLogs as $auditLog) {
            if(isset($rows[$auditLog->subject_type])) {
                // get row
                $row = $rows[$auditLog->subject_type]['index'];

                // add action index
                if ($auditLog->description=='updated')
                    $row=$row+1;
                else if ($auditLog->description=='deleted')
                    $row=$row+2;

                // get year / month
                $year=$auditLog->{'YEAR(created_at)'};
                $month=$auditLog->{'MONTH(created_at)'};

                // compute column
                $delta=14-($tMonths-($year*12+$month));
                $column = chr(ord('A') + $delta);

                // Place value
                $sheet->setCellValue("{$column}{$row}", $auditLog->count);

                \Log::info("{$column}{$row}" . " -> ". $auditLog->subject_type. ", " . $year . ", " . $month . ", " . $auditLog->count);
            }
        }

        // Write speansheet
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        // Return
        return response()->download($path);
    }

}

