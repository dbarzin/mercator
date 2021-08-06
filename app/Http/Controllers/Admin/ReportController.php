<?php

namespace App\Http\Controllers\Admin;

use \Carbon\Carbon;

// ecosystem
use App\Entity;
use App\Relation;

// information system
use App\MacroProcessus;
use App\Process;
use App\Activity;
use App\Operation;
use App\Task;
use App\Actor;
use App\Information;

// Applications
use App\ApplicationBlock;
use App\MApplication;
use App\ApplicationService;
use App\ApplicationModule;
use App\Database;
use App\Flux;

// Administration
use App\ZoneAdmin;
use App\Annuaire;
use App\ForestAd;
use App\DomaineAd;

// Logique
use App\Network;
use App\Subnetwork;
use App\Gateway;
use App\ExternalConnectedEntity;
use App\NetworkSwitch;
use App\Router;
use App\SecurityDevice;
use App\DhcpServer;
use App\Dnsserver;
use App\LogicalServer;
use App\Certificate;

// Physique
use App\Site;
use App\Building;
use App\Bay;
use App\PhysicalServer;
use App\Workstation;
use App\StorageDevice;
use App\Peripheral;
use App\Phone;
use App\PhysicalSwitch;
use App\PhysicalRouter;
use App\WifiTerminal;
use App\PhysicalSecurityDevice;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// PhpOffice 
// see : https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Line;

class ReportController extends Controller
{
    public function ecosystem(Request $request)
    {
        $entities = Entity::All()->sortBy("name");
        $relations = Relation::All()->sortBy("name");

        return view('admin/reports/ecosystem')
            ->with("entities",$entities)
            ->with("relations",$relations);

    }

    public function informationSystem(Request $request)
    {
        if ((int)($request->macroprocess)==-1) {
            $request->session()->put("macroprocess",null);
            $macroprocess=null;
            $request->session()->put("process",null);
            $process=null;
        }
        else {
            if ($request->macroprocess!=null) {
                    $request->session()->put("macroprocess",$request->macroprocess);
                    $macroprocess=$request->macroprocess;
                }
            else {
                $macroprocess=$request->session()->get("macroprocess");
            }

            if ((int)($request->process)==-1) {
                $request->session()->put("process",null);
                $process=null;
            }
            else 
            if ($request->process!=null) {
                    $request->session()->put("process",$request->process);
                    $process=$request->process;
                }
            else {
                $process=$request->session()->get("process");
            }
        }

        $all_macroprocess = MacroProcessus::All()->sortBy("name");

        if ($macroprocess!=null) {
            $macroProcessuses = MacroProcessus::All()->sortBy("name")
                ->filter(function($item) use($macroprocess) {
                    return $item->id == $macroprocess;
                });

            $processes = Process::All()->sortBy("identifiant")
                ->filter(function($item) use($macroProcessuses, $process) {
                    if($process!=null)
                        return $item->id==$process;
                    foreach($macroProcessuses as $macroprocess) 
                        foreach($macroprocess->processes as $process) 
                            if ($item->id == $process->id)
                                return true;
                    return false;
                });

            $all_process = Process::All()->sortBy("identifiant")
                ->filter(function($item) use($macroProcessuses, $process) {
                    foreach($macroProcessuses as $macroprocess) 
                        foreach($macroprocess->processes as $process) 
                            if ($item->id == $process->id)
                                return true;
                    return false;
                });


            $activities = Activity::All()->sortBy("name")
                ->filter(function($item) use($processes) {
                    foreach($item->activitiesProcesses as $p)
                        foreach($processes as $process) 
                            if ($p->id == $process->id) 
                                return true;
                    return false;
                });

            $operations = Operation::All()->sortBy("name")
                ->filter(function($item) use($activities) {
                    foreach($item->operationsActivities as $o)
                        foreach($activities as $activity) 
                            if ($o->id == $activity->id) 
                                return true;
                    return false;
                });

            $tasks = Task::All()->sortBy("nom")
                ->filter(function($item) use($operations) {
                    foreach($operations as $operation)
                        foreach($operation->tasks as $task)
                            if ($item->id == $task->id) 
                                return true;                    
                    return false;
                });

            $actors = Actor::All()->sortBy("name")
                ->filter(function($item) use($operations) {
                    foreach($operations as $operation) {
                        foreach($operation->actors as $actor)
                            if ($item->id == $actor->id) 
                                return true;                        
                    }
                    return false;
                });
            $informations = Information::All()->sortBy("name")
                ->filter(function($item) use($processes) {
                    foreach($processes as $process) 
                        foreach($process->processInformation as $information)
                            if ($item->id == $information->id) 
                                return true;                        
                    return false;
                });
        }
        else 
        {
            $macroProcessuses = MacroProcessus::All()->sortBy("name");
            $processes = Process::All()->sortBy("identifiant");
            $activities = Activity::All()->sortBy("name");
            $operations = Operation::All()->sortBy("name");
            $tasks = Task::All()->sortBy("name");
            $actors = Actor::All()->sortBy("name");
            $informations = Information::All()->sortBy("name");
            $all_process = null;
        }

        return view('admin/reports/information_system')
            ->with("all_macroprocess",$all_macroprocess)
            ->with("macroProcessuses",$macroProcessuses)
            ->with("processes",$processes)
            ->with("all_process",$all_process)
            ->with("activities",$activities)
            ->with("operations",$operations)
            ->with("tasks",$tasks)
            ->with("actors",$actors)
            ->with("informations",$informations);
    }

    public function applications(Request $request)
    {
        if ((int)($request->applicationBlock)==-1) {
            $request->session()->put("applicationBlock",null);
            $applicationBlock=null;
            $request->session()->put("application",null);
            $application=null;
        }
        else {
            if ($request->applicationBlock!=null) {
                    $request->session()->put("applicationBlock",$request->applicationBlock);
                    $applicationBlock=$request->applicationBlock;
                }
            else {
                $applicationBlock=$request->session()->get("applicationBlock");
            }

            if ((int)($request->application)==-1) {
                $request->session()->put("application",null);
                $application=null;
            }
            else 
            if ($request->application!=null) {
                    $request->session()->put("application",$request->application);
                    $application=$request->application;
                }
            else {
                $application=$request->session()->get("application");
            }
        }

        $all_applicationBlocks = ApplicationBlock::All()->sortBy("name");

        if ($applicationBlock!=null) {
            $applicationBlocks = ApplicationBlock::All()->sortBy("name")
                ->filter(function($item) use($applicationBlock) {
                    return $item->id == $applicationBlock;
                });

            $applications = MApplication::All()->sortBy("name")
                ->filter(function($item) use($applicationBlock, $application) {
                    if($application!=null)
                        return $item->id==$application;
                    else
                        return $item->application_block_id = $applicationBlock;
                });

            $all_applications = MApplication::All()->sortBy("name")
                ->filter(function($item) use($applicationBlock) {
                    return $item->application_block_id == $applicationBlock;
                });

            $applications = MApplication::All()->sortBy("name")
                ->filter(function($item) use($applicationBlock, $application) {
                    if ($application==null)
                        return $item->application_block_id == $applicationBlock;
                    else
                        return $item->id == $application;
                });

            $applicationServices = ApplicationService::All()->sortBy("name")
                ->filter(function($item) use($applications) {
                    foreach($applications as $application)
                        foreach($application->services as $service)
                            if ($item->id == $service->id)
                                return true;
                    return false;
                });

            $applicationModules = ApplicationModule::All()->sortBy("name")
                ->filter(function($item) use($applicationServices) {
                    foreach($applicationServices as $service)
                        foreach($service->modules as $module)
                            if ($item->id == $module->id)
                                return true;
                    return false;
                });

            $databases = Database::All()->sortBy("name")
                ->filter(function($item) use($applications) {
                    foreach($applications as $application)
                        foreach($application->databases as $database)
                            if ($item->id == $database->id)
                                return true;
                    return false;
                });

            $fluxes = Flux::All()->sortBy("name")
                ->filter(function($item) use($applications,$applicationModules,$databases) {
                    foreach($applications as $application) {                        
                        if ($item->application_source_id == $application->id)
                                return true;
                        if ($item->application_dest_id == $application->id)
                                return true;
                        }
                    foreach($applicationModules as $module) {                        
                        if ($item->module_source_id == $module->id)
                                return true;
                        if ($item->module_dest_id == $module->id)
                                return true;
                        }
                    foreach($databases as $database) {                        
                        if ($item->database_source_id == $database->id)
                                return true;
                        if ($item->database_dest_id == $database->id)
                                return true;
                        }
                    return false;
                });
            
            }
        else {
            $applicationBlocks = ApplicationBlock::All()->sortBy("name");
            $applications = MApplication::All()->sortBy("name");
            $applicationServices = ApplicationService::All()->sortBy("name");
            $applicationModules = ApplicationModule::All()->sortBy("name");
            $databases = Database::All()->sortBy("name");
            $fluxes = Flux::All()->sortBy("name");            
            $all_applications=null;
        }
        return view('admin/reports/applications')
            ->with('all_applicationBlocks',$all_applicationBlocks)
            ->with('all_applications',$all_applications)
            ->with("applicationBlocks",$applicationBlocks)
            ->with("applications",$applications)
            ->with("applicationServices",$applicationServices)
            ->with("applicationModules",$applicationModules)
            ->with("databases",$databases)
            ->with("fluxes",$fluxes)
            ;

    }

    public function applicationFlows(Request $request) {

        //update list application blocks
        $all_applicationBlocks = ApplicationBlock::All()->sortBy("name");

        // if application block change
        // update selected applications - services - modules - database
        // else
        // get active applicatons - services - modules - databases

        // get all flux
	$flows = Flux::All()->sortBy("name");
	/*
        if ($application_id!=null) {
            $flows = $flows
                ->filter(function($item) use($application_id) {
                    return 
                        $item->application_source_id=$application_id || 
                        $item->application_dest_id=$application_id;
                });
	}
	/*	
	else if ($applicationBlock!=null) {
                $app_applicationservice_ids = Mapplication::where('id','=',$application_id);
                if (!empty($app_applicationservice_ids))
                    $app_applicationservice_ids = $app_applicationservice_ids
                                                ->first()->services()->pluck("id");
            // dd($app_applicationservice_ids);
            $flows = $flows
                ->filter(function($item) use($application_id,$app_applicationservice_ids) {
                    return 
                        $item->application_source_id=$application_id || 
                        $item->application_dest_id=$application_id ||
                        in_array($item->module_source_id,$app_applicationservice_ids) ||
                        in_array($item->module_dest_id,$app_applicationservice_ids);
                });
            }
        else {
            // no filters
	}*/

        // get linked objects
        $application_ids = [];
        $service_ids = [];
        $module_ids = [];
        $database_ids = [];
        
        // loop on flows
        foreach ($flows as $flux) {
            // applications
            if (($flux->application_source_id!=null)&&
               (!in_array($flux->application_source_id, $application_ids)))
                array_push($application_ids,$flux->application_source_id);
            if (($flux->application_dest_id!=null)&&
               (!in_array($flux->application_dest_id, $application_ids)))
                array_push($application_ids,$flux->application_dest_id);

            // services
            if (($flux->service_source_id!=null)&&
               (!in_array($flux->service_source_id, $service_ids)))
                array_push($service_ids,$flux->service_source_id);
            if (($flux->service_dest_id!=null)&&
               (!in_array($flux->service_dest_id, $service_ids)))
                array_push($service_ids,$flux->service_dest_id);

            // modules
            if (($flux->module_source_id!=null)&&
               (!in_array($flux->module_source_id, $module_ids)))
                array_push($module_ids,$flux->module_source_id);
            if (($flux->module_dest_id!=null)&&
               (!in_array($flux->module_dest_id, $module_ids)))
                array_push($module_ids,$flux->module_dest_id);

            // databases
            if (($flux->database_source_id!=null)&&
               (!in_array($flux->database_source_id, $database_ids)))
                array_push($database_ids,$flux->database_source_id);
            if (($flux->database_dest_id!=null)&&
               (!in_array($flux->database_dest_id, $database_ids)))
                array_push($database_ids,$flux->database_dest_id);
            }

        // get objects
        $applications = MApplication::All()
            ->whereIn('id', $application_ids)
            ->sortBy("name");
        $applicationServices = ApplicationService::All()
            ->whereIn('id', $service_ids)
            ->sortBy("name");
        $applicationModules = ApplicationModule::All()
            ->whereIn('id', $module_ids)
            ->sortBy("name");
        $databases = Database::All()
            ->whereIn('id', $database_ids)
            ->sortBy("name");        

        // update lists
        $all_applications = MApplication::All()->sortBy("name")->pluck("name","id");
        $all_applicationServices = ApplicationService::All()->sortBy("name")->pluck("name","id");
        $all_applicationModules = ApplicationModule::All()->sortBy("name")->pluck("name","id");
        $all_databases = Database::All()->sortBy("name")->pluck("name","id");

        // return
        return view('admin/reports/application_flows')
            ->with('all_applicationBlocks',$all_applicationBlocks)
            ->with("all_applications",$all_applications)
            ->with("all_applicationModules",$all_applicationModules)
            ->with("all_applicationServices",$all_applicationServices)
            ->with("all_databases",$all_databases)
            ->with("applications",$applications)
            ->with("applicationServices",$applicationServices)
            ->with("applicationModules",$applicationModules)
            ->with("databases",$databases)
            ->with("flows",$flows)
            ;
    }

    public function logicalInfrastructure(Request $request) {            
        $networks = Network::All()->sortBy("name");
        $subnetworks = Subnetwork::All()->sortBy("name");
        $gateways = Gateway::All()->sortBy("name");
        $externalConnectedEntities = ExternalConnectedEntity::All()->sortBy("name");
        $networkSwitches = NetworkSwitch::All()->sortBy("name");
        $routers = Router::All()->sortBy("name");
        $securityDevices = SecurityDevice::All()->sortBy("name");
        $dhcpServers = DhcpServer::All()->sortBy("name");
        $dnsservers = Dnsserver::All()->sortBy("name");
        $logicalServers = LogicalServer::All()->sortBy("name");
        $certificates = Certificate::All()->sortBy("name");

        return view('admin/reports/logical_infrastructure')
            ->with("networks",$networks)
            ->with("subnetworks",$subnetworks)
            ->with("gateways",$gateways)
            ->with("externalConnectedEntities",$externalConnectedEntities)
            ->with("networkSwitches",$networkSwitches)
            ->with("routers",$routers)
            ->with("securityDevices",$securityDevices)
            ->with("dhcpServers",$dhcpServers)
            ->with("dnsservers",$dnsservers)
            ->with("logicalServers",$logicalServers)
            ->with("certificates",$certificates)
            ;
    }

    public function physicalInfrastructure(Request $request) {        

        if ((int)($request->site)==-1) {
            $request->session()->put("site",null);
            $site=null;
            $request->session()->put("building",null);
            $building=null;
        }
        else {
            if ($request->site!=null) {
                    $request->session()->put("site",$request->site);
                    $site=$request->site;
                }
            else {
                $site=$request->session()->get("site");
            }

            if ((int)($request->building)==-1) {
                $request->session()->put("building",null);
                $building=null;
            }
            else 
            if ($request->building!=null) {
                    $request->session()->put("building",$request->building);
                    $building=$request->building;                
                }
            else {
                $building=$request->session()->get("building");
            }
        }

        $all_sites = Site::All()->sortBy("name");

        if ($site!=null) {
            $sites = Site::All()->sortBy("name")
                ->filter(function($item) use($site) {
                    return $item->id == $site;
                });

            $all_buildings = Building::All()->sortBy("name")
                ->filter(function($item) use($site) {
                    return $item->site_id == $site;
                });

            $buildings=Building::All()->sortBy("name")
                ->filter(function($item) use($site, $building) {
                    if ($building==null)
                        return $item->site_id == $site;
                    else
                        return $item->id == $building;
                });

            $bays = Bay::All()->sortBy("name")
                ->filter(function($item) use($buildings) {
                    foreach($buildings as $building) 
                        if ($item->room_id == $building->id) 
                            return true;
                    return false;
                });

            $physicalServers = PhysicalServer::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings,$bays) {
                    if (($buildings==null)&&($item->site_id == $site))
                            return true;
                    else 
                        if ($item->bay_id==null) 
                            foreach($buildings as $building) {
                                if ($item->building_id == $building->id) 
                                    return true;
                            }
                        else 
                            foreach($bays as $bay) 
                                if ($item->bay_id == $bay->id) 
                                    return true;
                     return false;
                });

            $workstations = Workstation::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings) {
                    if (($item->building_id==null)&&($item->site_id == $site))
                            return true;
                    foreach($buildings as $building) 
                        if ($item->building_id == $building->id) 
                            return true;
                    return false;
                });

            $storageDevices = StorageDevice::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings,$bays) {
                    if (($item->bay_id==null)&&($item->building_id==null)&&($item->site_id == $site))
                            return true;
                    else 
                        if ($item->bay_id==null)
                            foreach($buildings as $building) {
                                if ($item->building_id == $building->id) 
                                    return true;
                            }
                        else 
                            foreach($bays as $bay) 
                                if ($item->bay_id == $bay->id) 
                                    return true;
                     return false;
                });

            $peripherals = Peripheral::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings,$bays) {
                    if (($item->bay_id==null)&&($item->building_id==null)&&($item->site_id == $site))
                        return true;
                    else 
                        if ($item->bay_id==null) 
                            foreach($buildings as $building) {
                                if ($item->building_id == $building->id) 
                                    return true;                                
                            }
                        else 
                            foreach($bays as $bay) 
                                if ($item->bay_id == $bay->id) 
                                    return true;
                     return false;
                });

            $phones = Phone::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings) {       
                    if (($item->building_id==null)&&($item->site_id == $site))
                        return true;
                    foreach($buildings as $building) 
                        if ($item->building_id == $building->id) 
                            return true;
                    return false;
                });

            $physicalSwitches = PhysicalSwitch::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings,$bays) {       
                    if (($item->bay_id==null)&&($item->building_id==null)&&($item->site_id == $site))
                        return true;
                    else 
                        if ($item->bay_id==null) 
                            foreach($buildings as $building) {
                                if ($item->building_id == $building->id) 
                                    return true;                                
                            }
                        else 
                            foreach($bays as $bay) 
                                if ($item->bay_id == $bay->id) 
                                    return true;
                     return false;
                });

            $physicalRouters = PhysicalRouter::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings,$bays) {       
                    if (($item->bay_id==null)&&($item->building_id==null)&&($item->site_id == $site))
                        return true;
                    else 
                        if ($item->bay_id==null) 
                            foreach($buildings as $building) {
                                if ($item->building_id == $building->id) 
                                    return true;                                
                            }
                        else 
                            foreach($bays as $bay) 
                                if ($item->bay_id == $bay->id) 
                                    return true;
                     return false;
                });

            $wifiTerminals = WifiTerminal::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings) {
                    if (($item->building_id==null)&&($item->site_id == $site))
                            return true;
                    foreach($buildings as $building) 
                        if ($item->building_id == $building->id) 
                            return true;
                    return false;
                });

            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy("name")
                ->filter(function($item) use($site,$buildings,$bays) {       
                    if (($item->bay_id==null)&&($item->building_id==null)&&($item->site_id == $site))
                        return true;
                    else 
                        if ($item->bay_id==null) 
                            foreach($buildings as $building) {
                                if ($item->building_id == $building->id) 
                                    return true;                                
                            }
                        else 
                            foreach($bays as $bay) 
                                if ($item->bay_id == $bay->id) 
                                    return true;
                     return false;
                });

        }
        else 
        {
            $sites=$all_sites;
            $buildings = Building::All()->sortBy("name");
            $all_buildings = null;
            $bays = Bay::All()->sortBy("name");
            $physicalServers = PhysicalServer::All()->sortBy("name");
            $workstations = Workstation::All()->sortBy("name");
            $storageDevices = StorageDevice::All()->sortBy("name");
            $peripherals = Peripheral::All()->sortBy("name");
            $phones = Phone::All()->sortBy("name");
            $physicalSwitches = PhysicalSwitch::All()->sortBy("name");
            $physicalRouters = PhysicalRouter::All()->sortBy("name");
            $wifiTerminals = WifiTerminal::All()->sortBy("name");
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy("name");
        }

        return view('admin/reports/physical_infrastructure')
            ->with("all_sites",$all_sites)
            ->with("sites",$sites)
            ->with("all_buildings",$all_buildings)
            ->with("buildings",$buildings)
            ->with("bays",$bays)
            ->with("physicalServers",$physicalServers)
            ->with("workstations",$workstations)
            ->with("storageDevices", $storageDevices)
            ->with("peripherals", $peripherals)
            ->with("phones", $phones)
            ->with("physicalSwitches", $physicalSwitches)
            ->with("physicalRouters", $physicalRouters)
            ->with("wifiTerminals", $wifiTerminals)
            ->with("physicalSecurityDevices", $physicalSecurityDevices)
            ;

    }

    public function administration(Request $request) {
        $zones = ZoneAdmin::All();
        $annuaires = Annuaire::All();
        $forests = ForestAd::All();
        $domains = DomaineAd::All();

        return view('admin/reports/administration')
            ->with("zones",$zones)
            ->with("annuaires",$annuaires)
            ->with("forests",$forests)
            ->with("domains",$domains);
        }

    public function entities(Request $request) {
        $path=storage_path('app/' . "entities.xlsx");

        $entities = Entity::All()->sortBy("name"); 

        $header = array(
                'Nom',
                'Description',
                'Niveau de sécurité',
                'Point de contact',
                'Applications supportées'
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(60, 'pt');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($entities as $entity) {
                $sheet->setCellValue("A{$row}", $entity->name);
                $sheet->setCellValue("B{$row}", $html->toRichTextObject($entity->description));
                $sheet->setCellValue("C{$row}", $html->toRichTextObject($entity->security_level));
                $sheet->setCellValue("D{$row}", $html->toRichTextObject($entity->contact_point));
                $txt = "";
                foreach ($entity->entityRespMApplications as $application) {
                    $txt .= $application->name;
                    if ($entity->entityRespMApplications->last() != $application)
                        $txt .= ", ";
                }
                $sheet->setCellValue("E{$row}", $txt);

                $row++;
            }        

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }
    
    public function applicationsByBlocks(Request $request) {

        $path=storage_path('app/' . "applications.xlsx");

        $applicationBlocks = ApplicationBlock::All()->sortBy("name");
        $applicationBlocks->load('applications');

        $header = array(
                "Applicaiton Block", 
                "Application",
                "Description",
                "Entity responsible",
                "Entities",
                "Responsible SSI",
                "Process supported",
                "Technology",
                "Type",
                "Users",
                "Exposition",
                "C",
                "I",
                "D",
                "T",
                "Documentation",
                "Logical servers",
                "Databases",
            );        


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(60, 'pt');
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setWidth(60, 'pt');
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        // CIAT
        $sheet->getColumnDimension('L')->setWidth(5, 'pt');
        $sheet->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('M')->setWidth(5, 'pt');
        $sheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('N')->setWidth(5, 'pt');
        $sheet->getStyle('N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('O')->setWidth(5, 'pt');
        $sheet->getStyle('O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($applicationBlocks as $applicationBlock) {
            foreach ($applicationBlock->applications as $application) {

                $sheet->setCellValue("A{$row}", $applicationBlock->name);
                $sheet->setCellValue("B{$row}", $application->name);
                $sheet->setCellValue("C{$row}", $html->toRichTextObject($application->description));
                $sheet->setCellValue("D{$row}", $application->entity_resp ? $application->entity_resp->name : "");
                $sheet->setCellValue("E{$row}", $application->entities->implode('name', ', '));
                $sheet->setCellValue("F{$row}", $application->responsible);
                $sheet->setCellValue("G{$row}", $application->processes->implode('identifiant', ', '));
                $sheet->setCellValue("H{$row}", $application->technology);
                $sheet->setCellValue("I{$row}", $application->type);
                $sheet->setCellValue("J{$row}", $application->users);
                $sheet->setCellValue("K{$row}", $application->external);

                $sheet->setCellValue("L{$row}", $application->security_need_c);
	        $this->setSecurityNeedColor($sheet,"L{$row}",$application->security_need_c);

                $sheet->setCellValue("M{$row}", $application->security_need_i);
	        $this->setSecurityNeedColor($sheet,"M{$row}",$application->security_need_i);

                $sheet->setCellValue("N{$row}", $application->security_need_a);
	        $this->setSecurityNeedColor($sheet,"N{$row}",$application->security_need_a);

                $sheet->setCellValue("O{$row}", $application->security_need_t);
	        $this->setSecurityNeedColor($sheet,"O{$row}",$application->security_need_t);

                $sheet->setCellValue("P{$row}", $application->documentation);
                $sheet->setCellValue("Q{$row}", $application->logical_servers->implode('name', ', '));
                $sheet->setCellValue("R{$row}", $application->databases->implode('name', ', '));

                $row++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function logicalServerResp(Request $request) {
        $path=storage_path('app/' . "logicalServersResp.xlsx");

        $logicalServers = LogicalServer::All()->sortBy("name");
        $logicalServers->load('applications');

        $header = array(
            'Serveur',
            'Application',
            'Entité utilisatrice',
            'Resp. Expoitation',
            'Resp. SSI',
            'Bloc applicatif',
            'Resp. applicatif'
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

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

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($logicalServers as $logicalServer) {
            foreach ($logicalServer->applications as $application) {

                $sheet->setCellValue("A{$row}", $logicalServer->name);
                $sheet->setCellValue("B{$row}", $application->name);
                //
                $entities=$application->entities()->get();
                $l=null;
                foreach ($entities as $entity) {
                    if ($l==null)
                        $l=$entity->name;
                    else $l=$l.', '.$entity->name;
                }
                $sheet->setCellValue("C{$row}", $l);
                //
                $l=$application->entity_resp()->get();
                if ($l->count()>0)
                    $sheet->setCellValue("D{$row}",$l[0]->name);
                //
                $sheet->setCellValue("E{$row}", $application->responsible);
                $sheet->setCellValue("F{$row}", $application->application_block->name);
                $sheet->setCellValue("G{$row}", $application->application_block->responsible);

                $row++;
            }
            
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }


    public function logicalServerConfigs(Request $request) {

        $path=storage_path('app/' . "logicalServers.xlsx");

        $logicalServers = LogicalServer::All()->sortBy("name");
        // $logicalServers->load('applications');

        $header = array(
            'name',
            'operating_system',
            'address_ip',
            'cpu',
            'memory',
            'disk',
            'environment',
            'net_services',
            'configuration',
            'applications',
            'physical_servers'
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

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

        // center
        $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($logicalServers as $logicalServer) {

                $sheet->setCellValue("A{$row}", $logicalServer->name);                
                $sheet->setCellValue("B{$row}", $logicalServer->operating_system);
                $sheet->setCellValue("C{$row}", $logicalServer->address_ip);
                $sheet->setCellValue("D{$row}", $logicalServer->cpu);
                $sheet->setCellValue("E{$row}", $logicalServer->memory);
                $sheet->setCellValue("F{$row}", $logicalServer->disk);
                $sheet->setCellValue("G{$row}", $logicalServer->environment);
                $sheet->setCellValue("H{$row}", $logicalServer->net_services);
                $sheet->setCellValue("I{$row}", $html->toRichTextObject($logicalServer->configuration));
                $sheet->setCellValue("J{$row}", $logicalServer->applications->implode('name', ', '));
                $sheet->setCellValue("K{$row}", $logicalServer->servers->implode('name', ', '));

                $row++;
            
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }


    private function addToInventory(array &$inventory, Site $site, Building $building = NULL, Bay $bay = NULL) {
        
        // PhysicalServer
        if ($bay!=NULL) 
            $physicalServers = PhysicalServer::where("bay_id","=",$bay->id)->orderBy("name")->get();
        else if ($building!=NULL)
            $physicalServers = PhysicalServer::where("bay_id","=",null)->where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $physicalServers = PhysicalServer::where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $physicalServers = PhysicalServer::orderBy("name")->get();

        foreach ($physicalServers as $physicalServer) {
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "category" => "Server",
                    "name" => $physicalServer->name,
                    "type" => $physicalServer->type,
                    "description" => $physicalServer->descrition,
                ));
        }
        
        // Workstation;
        if ($building!=NULL)
            $workstations = Workstation::where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $workstations = Workstation::where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $workstations = Workstation::orderBy("name")->get();

        foreach ($workstations as $workstation) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => "",
                    "category" => "Workstation",
                    "name" => $workstation->name,
                    "type" => $workstation->type,
                    "description" => $workstation->description,
                ));
        }
        
        // StorageDevice;
        if ($bay!=NULL) 
            $storageDevices = StorageDevice::where("bay_id","=",$bay->id)->orderBy("name")->get();
        else if ($building!=NULL)
            $storageDevices = StorageDevice::where("bay_id","=",null)->where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $storageDevices = StorageDevice::where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $storageDevices = StorageDevice::orderBy("name")->get();

        foreach ($storageDevices as $storageDevice) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "category" => "Storage",
                    "name" => $storageDevice->name,
                    "type" => $storageDevice->name,
                    "description" => $storageDevice->description,
                ));
        }

        // Peripheral
        if ($bay!=NULL) 
            $peripherals = Peripheral::where("bay_id","=",$bay->id)->orderBy("name")->get();
        else if ($building!=NULL)
            $peripherals = Peripheral::where("bay_id","=",null)->where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $peripherals = Peripheral::where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $peripherals = Peripheral::orderBy("name")->get();

        foreach ($peripherals as $peripheral) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "category" => "Peripheral",
                    "name" => $peripheral->name,
                    "type" => $peripheral->type,
                    "description" => $peripheral->description,
                ));
        }

        // Phone;
        if ($building!=NULL)
            $phones = Phone::where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $phones = Phone::where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $phones = Phone::orderBy("name")->get();

        foreach ($phones as $phone) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => "",
                    "category" => "Phone",
                    "name" => $phone->name,
                    "type" => $phone->type,
                    "description" => $phone->description,
                ));
        }
    
        // PhysicalSwitch;
        if ($bay!=NULL) 
            $physicalSwitches = PhysicalSwitch::where("bay_id","=",$bay->id)->orderBy("name")->get();
        else if ($building!=NULL)
            $physicalSwitches = PhysicalSwitch::where("bay_id","=",null)->where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $physicalSwitches = PhysicalSwitch::where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $physicalSwitches = PhysicalSwitch::orderBy("name")->get();

        foreach ($physicalSwitches as $physicalSwitch) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "category" => "Switch",
                    "name" => $physicalSwitch->name,
                    "type" => $physicalSwitch->type,
                    "description" => $physicalSwitch->description,
                ));
        }

        // PhysicalRouter
        if ($bay!=NULL) 
            $physicalRouters = PhysicalRouter::where("bay_id","=",$bay->id)->orderBy("name")->get();
        else if ($building!=NULL)
            $physicalRouters = PhysicalRouter::where("bay_id","=",null)->where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $physicalRouters = PhysicalRouter::where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $physicalRouters = PhysicalRouter::orderBy("name")->get();

        foreach ($physicalRouters as $physicalRouter) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "category" => "Router",
                    "name" => $physicalRouter->name,
                    "type" => $physicalRouter->type,
                    "description" => $physicalRouter->description,
                ));
        }

        // WifiTerminal
        if ($building!=NULL)
            $wifiTerminals = WifiTerminal::where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $wifiTerminals = WifiTerminal::where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $wifiTerminals = WifiTerminal::orderBy("name")->get();

        foreach ($wifiTerminals as $wifiTerminal) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => "",
                    "category" => "Wifi",
                    "name" => $wifiTerminal->name,
                    "type" => $wifiTerminal->type,
                    "description" => $wifiTerminal->description,
                ));
        }

        // Physical Security Devices
        if ($bay!=NULL) 
            $physicalSecurityDevices = PhysicalSecurityDevice::where("bay_id","=",$bay->id)->orderBy("name")->get();
        else if ($building!=NULL)
            $physicalSecurityDevices = PhysicalSecurityDevice::where("bay_id","=",null)->where("building_id","=",$building->id)->orderBy("name")->get();
        else if ($site!=NULL)
            $physicalSecurityDevices = PhysicalSecurityDevice::where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->orderBy("name")->get();
        else
            $physicalSecurityDevices = PhysicalSecurityDevice::orderBy("name")->get();

        foreach ($physicalSecurityDevices as $physicalSecurityDevice) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "category" => "Sécurité",
                    "name" => $physicalSecurityDevice->name,
                    "type" => $physicalSecurityDevice->type,
                    "description" => $physicalSecurityDevice->description,
                ));
        }

    }

    public function securityNeeds(Request $request) {
        $path=storage_path('app/' . "securityNeeds.xlsx");

        // macroprocess - process - application - base de données - information
        $header = array(
            'Macroprocess',
            'C','I','A','T',
            'Process',
            'C','I','A','T',
            'Application',
            'C','I','A','T',
            'Database',
            'C','I','A','T',
            'Information',
            'C','I','A','T'
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(5, 'pt');
        $sheet->getColumnDimension('C')->setWidth(5, 'pt');
        $sheet->getColumnDimension('D')->setWidth(5, 'pt');
        $sheet->getColumnDimension('E')->setWidth(5, 'pt');
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setWidth(5, 'pt');
        $sheet->getColumnDimension('H')->setWidth(5, 'pt');
        $sheet->getColumnDimension('I')->setWidth(5, 'pt');
        $sheet->getColumnDimension('J')->setWidth(5, 'pt');
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setWidth(5, 'pt');
        $sheet->getColumnDimension('M')->setWidth(5, 'pt');
        $sheet->getColumnDimension('N')->setWidth(5, 'pt');
        $sheet->getColumnDimension('O')->setWidth(5, 'pt');
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setWidth(5, 'pt');
        $sheet->getColumnDimension('R')->setWidth(5, 'pt');
        $sheet->getColumnDimension('S')->setWidth(5, 'pt');
        $sheet->getColumnDimension('T')->setWidth(5, 'pt');
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setWidth(5, 'pt');
        $sheet->getColumnDimension('W')->setWidth(5, 'pt');
        $sheet->getColumnDimension('X')->setWidth(5, 'pt');
        $sheet->getColumnDimension('Y')->setWidth(5, 'pt');

        // Center
        $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('Q')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('R')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('T')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('V')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('W')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('X')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Y')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;

        // loop
        // $macroprocesses = MacroProcessus::All();
        $macroprocesses = MacroProcessus::with('processes')->get();
        foreach ($macroprocesses as $macroprocess) {
            if ($macroprocess->processes->count()==0) {
                $this->addLine($sheet,$row,$macroprocess,null,null,null,null);
                $row++;
            }
            else
            foreach ($macroprocess->processes as $process) {
                if ($process->processesMApplications->count()==0){
                    $this->addLine($sheet,$row,$macroprocess,$process,null,null,null);
                    $row++;
                }
                else
                foreach ($process->processesMApplications as $application) {
                    if ($application->databases->count()==0) {
                            $this->addLine($sheet,$row,$macroprocess,$process,$application,null,null);
                            $row++;                       
                    }
                    else
                    foreach ($application->databases as $database) {
                        if ($database->informations->count()==0) {
                            $this->addLine($sheet,$row,$macroprocess,$process,$application,$database,null);
                            $row++;
                        }
                        else
                        foreach ($database->informations as $information) {
                            $this->addLine($sheet,$row,$macroprocess,$process,$application,$database,$information);
                            $row++;
                            }
                        }                         
                    }
                }
            }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);

    }

    private function setSecurityNeedColor(Worksheet $sheet, string $cell, $i) {
        static $colors = array(0=>'FFFFFF',1=>'FFFFFF',2=>'FFFA00',3=>'FF7D00',4=>'FF0000');
        $sheet->getStyle($cell)
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($colors[($i==null) ? 0 : $i]);
    }

    private function addLine(Worksheet $sheet, int $row, 
                MacroProcessus $macroprocess, Process $process = null, MApplication $application = null, 
                Database $database = null, Information $information = null) {

        // Macroprocessus
        $sheet->setCellValue("A{$row}", $macroprocess->name);

        $sheet->setCellValue("B{$row}", $macroprocess->security_need_c);
        $this->setSecurityNeedColor($sheet,"B{$row}",$macroprocess->security_need_c);

        $sheet->setCellValue("C{$row}", $macroprocess->security_need_i);
        $this->setSecurityNeedColor($sheet,"C{$row}",$macroprocess->security_need_i);

        $sheet->setCellValue("D{$row}", $macroprocess->security_need_a);
        $this->setSecurityNeedColor($sheet,"D{$row}",$macroprocess->security_need_a);

        $sheet->setCellValue("E{$row}", $macroprocess->security_need_t);
        $this->setSecurityNeedColor($sheet,"E{$row}",$macroprocess->security_need_t);

        if ($process!=null) {
            // Processus
            $sheet->setCellValue("F{$row}", $process->identifiant);
            $sheet->setCellValue("G{$row}", $process->security_need_c);
            $this->setSecurityNeedColor($sheet,"G{$row}",$process->security_need_c);

            $sheet->setCellValue("H{$row}", $process->security_need_i);
            $this->setSecurityNeedColor($sheet,"H{$row}",$process->security_need_i);

            $sheet->setCellValue("I{$row}", $process->security_need_a);
            $this->setSecurityNeedColor($sheet,"I{$row}",$process->security_need_a);

            $sheet->setCellValue("J{$row}", $process->security_need_t);
            $this->setSecurityNeedColor($sheet,"J{$row}",$process->security_need_t);

            if ($application!=null) {
                // Application
                $sheet->setCellValue("K{$row}", $application->name);

                $sheet->setCellValue("L{$row}", $application->security_need_c);
                $this->setSecurityNeedColor($sheet,"L{$row}",$application->security_need_c);

                $sheet->setCellValue("M{$row}", $application->security_need_i);
                $this->setSecurityNeedColor($sheet,"M{$row}",$application->security_need_i);

                $sheet->setCellValue("N{$row}", $application->security_need_a);
                $this->setSecurityNeedColor($sheet,"N{$row}",$application->security_need_a);

                $sheet->setCellValue("O{$row}", $application->security_need_t);
                $this->setSecurityNeedColor($sheet,"O{$row}",$application->security_need_t);
                
                if ($database!=null) {
                    // Database
                    $sheet->setCellValue("P{$row}", $database->name);
                    $sheet->setCellValue("Q{$row}", $database->security_need_c);
                    $this->setSecurityNeedColor($sheet,"Q{$row}",$database->security_need_c);                    
                    $sheet->setCellValue("R{$row}", $database->security_need_i);
                    $this->setSecurityNeedColor($sheet,"R{$row}",$database->security_need_i);
                    $sheet->setCellValue("S{$row}", $database->security_need_a);
                    $this->setSecurityNeedColor($sheet,"S{$row}",$database->security_need_a);
                    $sheet->setCellValue("T{$row}", $database->security_need_t);
                    $this->setSecurityNeedColor($sheet,"T{$row}",$database->security_need_t);

                    if ($information!=null) {
                        // Information
                        $sheet->setCellValue("U{$row}", $information->name);
                        $sheet->setCellValue("V{$row}", $information->security_need_c);
                        $this->setSecurityNeedColor($sheet,"V{$row}",$information->security_need_c);
                        $sheet->setCellValue("W{$row}", $information->security_need_i);
                        $this->setSecurityNeedColor($sheet,"W{$row}",$information->security_need_i);
                        $sheet->setCellValue("X{$row}", $information->security_need_a);
                        $this->setSecurityNeedColor($sheet,"X{$row}",$information->security_need_a);
                        $sheet->setCellValue("Y{$row}", $information->security_need_t);
                        $this->setSecurityNeedColor($sheet,"Y{$row}",$information->security_need_t);

                    }
                }
            }
        }
    }


    public function physicalInventory(Request $request) {

        $path=storage_path('app/' . "physicalInventory.xlsx");

        $inventory = array();

        // for all sites
        $sites = Site::All()->sortBy("name");
        foreach ($sites as $site) {

            $this->addToInventory($inventory, $site);

            // for all buildings
            $buildings = Building::where("site_id","=",$site->id)->orderBy("name")->get();
            foreach ($buildings as $building) {

                $this->addToInventory($inventory, $site, $building);

                // for all bays
                $bays = Bay::where("room_id","=",$building->id)->orderBy("name")->get();
                foreach ($bays as $bay) {

                    $this->addToInventory($inventory, $site, $building, $bay);
                }
            }
            
        }

        $header = array(
            'Site',
            'Room',
            'Bay',
            'Asset',
            'Name',
            'Type',
            'Description',
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

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
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;

        // create the sheet
        foreach ($inventory as $item) {

                $sheet->setCellValue("A{$row}", $item["site"]);
                $sheet->setCellValue("B{$row}", $item["room"]);
                $sheet->setCellValue("C{$row}", $item["bay"]);
                $sheet->setCellValue("D{$row}", $item["category"]);
                $sheet->setCellValue("E{$row}", $item["name"]);
                $sheet->setCellValue("F{$row}", $item["type"]);
                $sheet->setCellValue("G{$row}", $html->toRichTextObject($item["description"]));

                $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

}

