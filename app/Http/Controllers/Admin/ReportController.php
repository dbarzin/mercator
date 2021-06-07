<?php

namespace App\Http\Controllers\Admin;

Use \Carbon\Carbon;

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
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\Table;


use PhpOffice\PhpWord\Element\Line;
// use PhpOffice\PhpWord\Element\Section;

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
        // for filtering
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
        if ($applicationBlock==null)
            $all_applications=null;
        else {
            $all_applications = MApplication::All()->sortBy("name")
                ->filter(function($item) use($applicationBlock) {
                    return $item->application_block_id == $applicationBlock;
                });
            // for filtering
            $all_application_ids = [];
            foreach ($all_applications as $app) {
                array_push($all_application_ids,$app->id);
                }
            }

        // get flows
        // TODO : improve filtering on module, services and databases
        $flows = Flux::All()->sortBy("name");
        if ($applicationBlock!=null) {
            $flows = $flows
                ->filter(function($item) use($all_application_ids) {
                    return 
                        in_array($item->application_source_id,$all_application_ids)||
                        in_array($item->application_dest_id,$all_application_ids);
                });
        } else if ($application!=null) {
            $flows = $flows
                ->filter(function($item) use($application) {
                    return $item->application_source_id=$application->id || $item->application_dest_id;
                });
        }

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

        // return
        return view('admin/reports/application_flows')
            ->with("all_applicationBlocks",$all_applicationBlocks)
            ->with("all_applications",$all_applications)
            ->with("flows",$flows)
            ->with("applications",$applications)
            ->with("applicationServices",$applicationServices)
            ->with("applicationModules",$applicationModules)
            ->with("databases",$databases);
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
                ->filter(function($item) use($site, $building) {
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
            $physicalSecurityDevices = physicalSecurityDevices::All()->sortBy("name");
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
                "External",
                "Confidentiality",
                "Integrity",
                "Availability",
                "Tracability",
                "Documentation",
                "Logical servers",
                "Databases",
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

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
                $sheet->setCellValue("M{$row}", $application->security_need_i);
                $sheet->setCellValue("N{$row}", $application->security_need_a);
                $sheet->setCellValue("O{$row}", $application->security_need_t);

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
            'description',
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

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($logicalServers as $logicalServer) {

                $sheet->setCellValue("A{$row}", $logicalServer->name);
                $sheet->setCellValue("B{$row}", $html->toRichTextObject($logicalServer->description));
                $sheet->setCellValue("C{$row}", $logicalServer->operating_system);
                $sheet->setCellValue("D{$row}", $logicalServer->address_ip);
                $sheet->setCellValue("E{$row}", $logicalServer->cpu);
                $sheet->setCellValue("F{$row}", $logicalServer->memory);
                $sheet->setCellValue("G{$row}", $logicalServer->disk);
                $sheet->setCellValue("H{$row}", $logicalServer->environment);
                $sheet->setCellValue("I{$row}", $logicalServer->net_services);
                $sheet->setCellValue("J{$row}", $html->toRichTextObject($logicalServer->configuration));
                $sheet->setCellValue("K{$row}", $logicalServer->applications->implode('name', ', '));
                $sheet->setCellValue("L{$row}", $logicalServer->servers->implode('name', ', '));

                $row++;
            
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }


    private function addToInventory(array &$inventory, Site $site, Building $building = NULL, Bay $bay = NULL) {
        
        // PhysicalServer
        if ($bay!=NULL) 
            $physicalServers = PhysicalServer::All()->where("bay_id","=",$bay->id)->sortBy("name");        
        else if ($building!=NULL)
            $physicalServers = PhysicalServer::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $physicalServers = PhysicalServer::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $physicalServers = PhysicalServer::All()->sortBy("name");

        foreach ($physicalServers as $physicalServer) {
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "type" => "Server",
                    "name" => $physicalServer->name,
                    "description" => $physicalServer->descrition,
                ));
        }
        
        // Workstation;
        if ($building!=NULL)
            $workstations = Workstation::All()->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $workstations = Workstation::All()->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $workstations = Workstation::All()->sortBy("name");

        foreach ($workstations as $workstation) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => "",
                    "type" => "Workstation",
                    "name" => $workstation->name,
                    "description" => $workstation->description,
                ));
        }
        
        // StorageDevice;
        if ($bay!=NULL) 
            $storageDevices = StorageDevice::All()->where("bay_id","=",$bay->id)->sortBy("name");        
        else if ($building!=NULL)
            $storageDevices = StorageDevice::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $storageDevices = StorageDevice::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $storageDevices = StorageDevice::All()->sortBy("name");

        foreach ($storageDevices as $storageDevice) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "type" => "Storage",
                    "name" => $storageDevice->name,
                    "description" => $storageDevice->description,
                ));
        }

        // Peripheral
        if ($bay!=NULL) 
            $peripherals = Peripheral::All()->where("bay_id","=",$bay->id)->sortBy("name");        
        else if ($building!=NULL)
            $peripherals = Peripheral::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $peripherals = Peripheral::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $peripherals = Peripheral::All()->sortBy("name");

        foreach ($peripherals as $peripheral) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "type" => "Peripheral",
                    "name" => $peripheral->name,
                    "description" => $peripheral->description,
                ));
        }

        // Phone;
        if ($building!=NULL)
            $phones = Phone::All()->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $phones = Phone::All()->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $phones = Phone::All()->sortBy("name");

        foreach ($phones as $phone) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => "",
                    "type" => "Phone",
                    "name" => $phone->name,
                    "description" => $phone->description,
                ));
        }
    
        // PhysicalSwitch;
        if ($bay!=NULL) 
            $physicalSwitches = PhysicalSwitch::All()->where("bay_id","=",$bay->id)->sortBy("name");        
        else if ($building!=NULL)
            $physicalSwitches = PhysicalSwitch::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $physicalSwitches = PhysicalSwitch::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $physicalSwitches = PhysicalSwitch::All()->sortBy("name");

        foreach ($physicalSwitches as $physicalSwitch) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "type" => "Switch",
                    "name" => $physicalSwitch->name,
                    "description" => $physicalSwitch->description,
                ));
        }

        // PhysicalRouter
        if ($bay!=NULL) 
            $physicalRouters = PhysicalRouter::All()->where("bay_id","=",$bay->id)->sortBy("name");        
        else if ($building!=NULL)
            $physicalRouters = PhysicalRouter::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $physicalRouters = PhysicalRouter::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $physicalRouters = PhysicalRouter::All()->sortBy("name");

        foreach ($physicalRouters as $physicalRouter) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "type" => "Router",
                    "name" => $physicalRouter->name,
                    "description" => $physicalRouter->description,
                ));
        }

        // WifiTerminal
        if ($building!=NULL)
            $wifiTerminals = WifiTerminal::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $wifiTerminals = WifiTerminal::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $wifiTerminals = WifiTerminal::All()->sortBy("name");

        foreach ($wifiTerminals as $wifiTerminal) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => "",
                    "type" => "Wifi",
                    "name" => $wifiTerminal->name,
                    "description" => $wifiTerminal->description,
                ));
        }

        // Physical Security Devices
        if ($bay!=NULL) 
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->where("bay_id","=",$bay->id)->sortBy("name");        
        else if ($building!=NULL)
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->where("bay_id","=",null)->where("building_id","=",$building->id)->sortBy("name");
        else if ($site!=NULL)
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->where("bay_id","=",null)->where("building_id","=",null)->where("site_id","=",$site->id)->sortBy("name");
        else
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy("name");

        foreach ($physicalSecurityDevices as $physicalSecurityDevice) {            
            array_push($inventory,
                array(
                    "site" => $site->name ?? "",
                    "room" => $building->name ?? "",
                    "bay" => $bay->name ?? "",
                    "type" => "Sécurité",
                    "name" => $physicalSecurityDevice->name,
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

    private function setRedCell(Worksheet $sheet, string $cell) {
        $sheet->getStyle($cell)
                ->getFill()
                ->setFillType("solid")
                ->getStartColor()
                ->setRGB('FF0000');
    }

    private function addLine(Worksheet $sheet, int $row, 
                MacroProcessus $macroprocess, Process $process = null, MApplication $application = null, 
                Database $database = null, Information $information = null) {

        // Macroprocessus
        $sheet->setCellValue("A{$row}", $macroprocess->name);
        $sheet->setCellValue("B{$row}", $macroprocess->security_need_c);
        $sheet->setCellValue("C{$row}", $macroprocess->security_need_i);
        $sheet->setCellValue("D{$row}", $macroprocess->security_need_a);
        $sheet->setCellValue("E{$row}", $macroprocess->security_need_t);
        if ($process!=null) {
            // Processus
            $sheet->setCellValue("F{$row}", $process->identifiant);
            $sheet->setCellValue("G{$row}", $process->security_need_c);
            $sheet->setCellValue("H{$row}", $process->security_need_i);
            $sheet->setCellValue("I{$row}", $process->security_need_a);
            $sheet->setCellValue("J{$row}", $process->security_need_t);
            // Check 
            if ($process->security_need_c>$macroprocess->security_need_c)
                $this->setRedCell($sheet, "B{$row}");
            if ($process->security_need_i>$macroprocess->security_need_i)
                $this->setRedCell($sheet, "C{$row}");
            if ($process->security_need_a>$macroprocess->security_need_a)
                $this->setRedCell($sheet, "D{$row}");
            if ($process->security_need_t>$macroprocess->security_need_t)
                $this->setRedCell($sheet, "E{$row}");

            if ($application!=null) {
                // Application
                $sheet->setCellValue("K{$row}", $application->name);
                $sheet->setCellValue("L{$row}", $application->security_need_c);
                $sheet->setCellValue("M{$row}", $application->security_need_i);
                $sheet->setCellValue("N{$row}", $application->security_need_a);
                $sheet->setCellValue("O{$row}", $application->security_need_t);

                // Check 
                if ($application->security_need_c>$process->security_need_c)
                    $this->setRedCell($sheet, "G{$row}");
                if ($application->security_need_i>$process->security_need_i)
                    $this->setRedCell($sheet, "H{$row}");
                if ($application->security_need_a>$process->security_need_a)
                    $this->setRedCell($sheet, "I{$row}");
                if ($application->security_need_t>$process->security_need_t)
                    $this->setRedCell($sheet, "J{$row}");

                
                if ($database!=null) {
                    // Database
                    $sheet->setCellValue("P{$row}", $database->name);
                    $sheet->setCellValue("Q{$row}", $database->security_need_c);
                    $sheet->setCellValue("R{$row}", $database->security_need_i);
                    $sheet->setCellValue("S{$row}", $database->security_need_a);
                    $sheet->setCellValue("T{$row}", $database->security_need_t);

                    // Check 
                    if ($database->security_need_c>$application->security_need_c)
                        $this->setRedCell($sheet, "L{$row}");
                    if ($database->security_need_i>$application->security_need_i)
                        $this->setRedCell($sheet, "M{$row}");
                    if ($database->security_need_a>$application->security_need_a)
                        $this->setRedCell($sheet, "N{$row}");
                    if ($database->security_need_t>$application->security_need_t)
                        $this->setRedCell($sheet, "O{$row}");

                    if ($information!=null) {
                        // Information
                        $sheet->setCellValue("U{$row}", $information->name);
                        $sheet->setCellValue("V{$row}", $information->security_need_c);
                        $sheet->setCellValue("W{$row}", $information->security_need_i);
                        $sheet->setCellValue("X{$row}", $information->security_need_a);
                        $sheet->setCellValue("Y{$row}", $information->security_need_t);

                        // Check 
                        if ($information->security_need_c>$database->security_need_c)
                            $this->setRedCell($sheet, "Q{$row}");
                        if ($information->security_need_i>$database->security_need_i)
                            $this->setRedCell($sheet, "R{$row}");
                        if ($information->security_need_a>$database->security_need_a)
                            $this->setRedCell($sheet, "S{$row}");
                        if ($information->security_need_t>$database->security_need_t)
                            $this->setRedCell($sheet, "T{$row}");

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
            $buildings = Building::All()->where("site_id","=",$site->id)->sortBy("name");
            foreach ($buildings as $building) {

                $this->addToInventory($inventory, $site, $building);

                // for all bays
                $bays = Bay::All()->where("room_id","=",$building->id)->sortBy("name");
                foreach ($bays as $bay) {

                    $this->addToInventory($inventory, $site, $building, $bay);
                }
            }
            
        }

        $header = array(
            'Site',
            'Room',
            'Bay',
            'Type',
            'Name',
            'Description',
            );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], NULL, 'A1');

        // converter 
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;

        // create the sheet
        foreach ($inventory as $item) {

                $sheet->setCellValue("A{$row}", $item["site"]);
                $sheet->setCellValue("B{$row}", $item["room"]);
                $sheet->setCellValue("C{$row}", $item["bay"]);
                $sheet->setCellValue("D{$row}", $item["type"]);
                $sheet->setCellValue("E{$row}", $item["name"]);
                $sheet->setCellValue("F{$row}", $html->toRichTextObject($item["description"]));

                $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

}

