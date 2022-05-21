<?php

namespace App\Http\Controllers\Admin;

// ecosystem
use App\Activity;
use App\Actor;
use App\Annuaire;
// information system
use App\ApplicationBlock;
use App\ApplicationModule;
use App\ApplicationService;
use App\Bay;
use App\Building;
use App\Certificate;
use App\Database;
// Applications
use App\DhcpServer;
use App\Dnsserver;
use App\DomaineAd;
use App\Entity;
use App\ExternalConnectedEntity;
use App\Flux;
// Administration
use App\ForestAd;
use App\Gateway;
use App\Http\Controllers\Controller;
use App\Information;
// Logique
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
use App\PhysicalServer;
// Physique
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// PhpOffice
// see : https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportController extends Controller
{
    public function ecosystem(Request $request)
    {
        if (($request->perimeter === null) || ($request->perimeter === 'all')) {
            $request->session()->put('perimeter','all');
            $entities = Entity::All()->sortBy('name');
            $relations = Relation::All()->sortBy('name');
            return view('admin/reports/ecosystem')
                ->with('entities', $entities)
            ->with('relations', $relations);
        } else {
            $perimeter = $request->perimeter;
            $entities = Entity::All()->sortBy('name')->filter(function ($item)  use ($perimeter){
                if ($perimeter == 'Externes' ) {
                    return $item->is_external == 'true';
                } else {
                    return  !($item->is_external == 'true');
                }
            });
            $relations = Relation::All()->sortBy('name') ->filter(function ($item)  use ($entities){
                return $entities->contains($item->source_id) && $entities->contains($item->destination_id);
            });
            $request->session()->put('perimeter', $request->perimeter);
            return view('admin/reports/ecosystem')
                ->with('entities',  $entities)
                ->with('relations', $relations);
	    }
    }

    public function informationSystem(Request $request)
    {
        if ($request->macroprocess === null) {
            $request->session()->put('macroprocess', null);
            $macroprocess = null;
            $request->session()->put('process', null);
            $process = null;
        } else {
            if ($request->macroprocess !== null) {
                $macroprocess = intval($request->macroprocess);
                $request->session()->put('macroprocess', $macroprocess);
            } else {
                $macroprocess = $request->session()->get('macroprocess');
            }

            if ($request->process === null) {
                $request->session()->put('process', null);
                $process = null;
            } elseif ($request->process !== null) {
                $process = intval($request->process);
                $request->session()->put('process', $process);
            } else {
                $process = $request->session()->get('process');
            }
        }

        $all_macroprocess = MacroProcessus::All()->sortBy('name');

        if ($macroprocess !== null) {
            $macroProcessuses = MacroProcessus::All()->sortBy('name')
                ->filter(function ($item) use ($macroprocess) {
                    return $item->id === $macroprocess;
                });

            // TODO : improve me
            $processes = Process::All()->sortBy('identifiant')
                ->filter(function ($item) use ($macroProcessuses, $process) {
                    if ($process !== null) {
                        return $item->id === $process;
                    }
                    foreach ($macroProcessuses as $macroprocess) {
                        foreach ($macroprocess->processes as $process) {
                            if ($item->id === $process->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $all_process = Process::All()->sortBy('identifiant')
                ->filter(function ($item) use ($macroProcessuses, $process) {
                    foreach ($macroProcessuses as $macroprocess) {
                        foreach ($macroprocess->processes as $process) {
                            if ($item->id === $process->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $activities = Activity::All()->sortBy('name')
                ->filter(function ($item) use ($processes) {
                    foreach ($item->activitiesProcesses as $p) {
                        foreach ($processes as $process) {
                            if ($p->id === $process->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $operations = Operation::All()->sortBy('name')
                ->filter(function ($item) use ($activities) {
                    foreach ($item->operationsActivities as $o) {
                        foreach ($activities as $activity) {
                            if ($o->id === $activity->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $tasks = Task::All()->sortBy('nom')
                ->filter(function ($item) use ($operations) {
                    foreach ($operations as $operation) {
                        foreach ($operation->tasks as $task) {
                            if ($item->id === $task->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $actors = Actor::All()->sortBy('name')
                ->filter(function ($item) use ($operations) {
                    foreach ($operations as $operation) {
                        foreach ($operation->actors as $actor) {
                            if ($item->id === $actor->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $informations = Information::All()->sortBy('name')
                ->filter(function ($item) use ($processes) {
                    foreach ($processes as $process) {
                        foreach ($process->processInformation as $information) {
                            if ($item->id === $information->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
        } else {
            $macroProcessuses = MacroProcessus::All()->sortBy('name');
            $processes = Process::All()->sortBy('identifiant');
            $activities = Activity::All()->sortBy('name');
            $operations = Operation::All()->sortBy('name');
            $tasks = Task::All()->sortBy('name');
            $actors = Actor::All()->sortBy('name');
            $informations = Information::All()->sortBy('name');
            $all_process = null;
        }

        return view('admin/reports/information_system')
            ->with('all_macroprocess', $all_macroprocess)
            ->with('macroProcessuses', $macroProcessuses)
            ->with('processes', $processes)
            ->with('all_process', $all_process)
            ->with('activities', $activities)
            ->with('operations', $operations)
            ->with('tasks', $tasks)
            ->with('actors', $actors)
            ->with('informations', $informations);
    }

    public function applications(Request $request)
    {
        if ($request->applicationBlock === null) {
            $request->session()->put('applicationBlock', null);
            $applicationBlock = null;
            $request->session()->put('application', null);
            $application = null;
        } else {
            if ($request->applicationBlock !== null) {
                $applicationBlock = intval($request->applicationBlock);
                $request->session()->put('applicationBlock', $applicationBlock);
            } else {
                $applicationBlock = $request->session()->get('applicationBlock');
            }

            if ($request->application === null) {
                $request->session()->put('application', null);
                $application = null;
            } elseif ($request->application !== null) {
                $application = intval($request->application);
                $request->session()->put('application', $application);
            } else {
                $application = $request->session()->get('application');
            }
        }

        $all_applicationBlocks = ApplicationBlock::All()->sortBy('name');

        if ($applicationBlock !== null) {
            // TODO : improve me
            $applicationBlocks = ApplicationBlock::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->id === $applicationBlock;
                });

            // TODO : improve me
            $applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application !== null) {
                        return $item->id === $application;
                    }
                    return $item->application_block_id = $applicationBlock;
                });

            $all_applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->application_block_id === $applicationBlock;
                });

            // TODO : improve me
            $applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application === null) {
                        return $item->application_block_id === $applicationBlock;
                    }
                    return $item->id === $application;
                });

            // TODO : improve me
            $applicationServices = ApplicationService::All()->sortBy('name')
                ->filter(function ($item) use ($applications) {
                    foreach ($applications as $application) {
                        foreach ($application->services as $service) {
                            if ($item->id === $service->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $applicationModules = ApplicationModule::All()->sortBy('name')
                ->filter(function ($item) use ($applicationServices) {
                    foreach ($applicationServices as $service) {
                        foreach ($service->modules as $module) {
                            if ($item->id === $module->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $databases = Database::All()->sortBy('name')
                ->filter(function ($item) use ($applications) {
                    foreach ($applications as $application) {
                        foreach ($application->databases as $database) {
                            if ($item->id === $database->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO : improve me
            $fluxes = Flux::All()->sortBy('name')
                ->filter(function ($item) use ($applications, $applicationModules, $databases) {
                    foreach ($applications as $application) {
                        if ($item->application_source_id === $application->id) {
                            return true;
                        }
                        if ($item->application_dest_id === $application->id) {
                            return true;
                        }
                    }
                    foreach ($applicationModules as $module) {
                        if ($item->module_source_id === $module->id) {
                            return true;
                        }
                        if ($item->module_dest_id === $module->id) {
                            return true;
                        }
                    }
                    foreach ($databases as $database) {
                        if ($item->database_source_id === $database->id) {
                            return true;
                        }
                        if ($item->database_dest_id === $database->id) {
                            return true;
                        }
                    }
                    return false;
                });
        } else {
            $applicationBlocks = ApplicationBlock::All()->sortBy('name');
            $applications = MApplication::All()->sortBy('name');
            $applicationServices = ApplicationService::All()->sortBy('name');
            $applicationModules = ApplicationModule::All()->sortBy('name');
            $databases = Database::All()->sortBy('name');
            $fluxes = Flux::All()->sortBy('name');
            $all_applications = null;
        }
        return view('admin/reports/applications')
            ->with('all_applicationBlocks', $all_applicationBlocks)
            ->with('all_applications', $all_applications)
            ->with('applicationBlocks', $applicationBlocks)
            ->with('applications', $applications)
            ->with('applicationServices', $applicationServices)
            ->with('applicationModules', $applicationModules)
            ->with('databases', $databases)
            ->with('fluxes', $fluxes)
            ;
    }

    public function applicationFlows(Request $request)
    {

        // Blocks
        if ($request->applicationBlocks === null) {
            $applicationBlocks = [];
            $request->session()->put('applicationBlocks', []);
        } else {
            if ($request->applicationBlocks !== null) {
                $applicationBlocks = $request->applicationBlocks;
                $request->session()->put('applicationBlocks', $applicationBlocks);
            } else {
                $applicationBlocks = $request->session()->get('applicationBlocks');
            }
        }

        // Applications
        if ($request->applications === null) {
            $request->session()->put('applications', []);
            $applications = [];
        } else {
            if ($request->applications !== null) {
                $applications = $request->applications;
                $request->session()->put('applications', $applications);
            } else {
                $applications = $request->session()->get('applications');
            }
        }

        // Get assets
        $application_ids =
            DB::table('m_applications')
                ->whereIn('application_block_id', $applicationBlocks)
                ->orWhereIn('id', $applications)
                ->pluck('id');

        $applicationservice_ids = DB::table('m_applications')
            ->join('application_service_m_application', 'm_applications.id', '=', 'application_service_m_application.m_application_id')
            ->whereIn('application_block_id', $applicationBlocks)
            ->pluck('application_service_id')
            ->unique();

        $applicationmodule_ids = DB::table('m_applications')
            ->join('application_service_m_application', 'm_applications.id', '=', 'application_service_m_application.m_application_id')
            ->join('application_module_application_service', 'application_service_m_application.application_service_id', '=', 'application_module_application_service.application_service_id')
            ->whereIn('application_block_id', $applicationBlocks)
            ->pluck('application_module_id')
            ->unique();

        $database_ids = DB::table('m_applications')
            ->join('database_m_application', 'm_applications.id', '=', 'database_m_application.m_application_id')
            ->whereIn('application_block_id', $applicationBlocks)
            ->pluck('database_id')
            ->unique();

        // get all flows
        $flows = Flux::All()->sortBy('name');

        // Filter Flows
        $flows = $flows
            ->filter(function ($item) use (
                $application_ids,
                $applicationservice_ids,
                $applicationmodule_ids,
                $database_ids
            ) {
                return // application
                    $application_ids->contains($item->application_source_id) ||
                    $application_ids->contains($item->application_dest_id) ||
                    // service
                    $applicationservice_ids->contains($item->service_source_id) ||
                    $applicationservice_ids->contains($item->service_dest_id) ||
                    // module
                    $applicationmodule_ids->contains($item->module_source_id) ||
                    $applicationmodule_ids->contains($item->module_dest_id) ||
                    // database
                    $database_ids->contains($item->database_source_id) ||
                    $database_ids->contains($item->database_dest_id);
            });

        // filter linked objects
        $application_ids = []; //$application_ids->toArray();
        $service_ids = [];
        $module_ids = [];
        $database_ids = [];

        // loop on flows
        foreach ($flows as $flux) {
            // applications
            if (($flux->application_source_id !== null) &&
               (! in_array($flux->application_source_id, $application_ids))) {
                array_push($application_ids, $flux->application_source_id);
            }
            if (($flux->application_dest_id !== null) &&
               (! in_array($flux->application_dest_id, $application_ids))) {
                array_push($application_ids, $flux->application_dest_id);
            }

            // services
            if (($flux->service_source_id !== null) &&
               (! in_array($flux->service_source_id, $service_ids))) {
                array_push($service_ids, $flux->service_source_id);
            }
            if (($flux->service_dest_id !== null) &&
               (! in_array($flux->service_dest_id, $service_ids))) {
                array_push($service_ids, $flux->service_dest_id);
            }

            // modules
            if (($flux->module_source_id !== null) &&
               (! in_array($flux->module_source_id, $module_ids))) {
                array_push($module_ids, $flux->module_source_id);
            }
            if (($flux->module_dest_id !== null) &&
               (! in_array($flux->module_dest_id, $module_ids))) {
                array_push($module_ids, $flux->module_dest_id);
            }

            // databases
            if (($flux->database_source_id !== null) &&
               (! in_array($flux->database_source_id, $database_ids))) {
                array_push($database_ids, $flux->database_source_id);
            }
            if (($flux->database_dest_id !== null) &&
               (! in_array($flux->database_dest_id, $database_ids))) {
                array_push($database_ids, $flux->database_dest_id);
            }
        }

        // get objects
        $applications = MApplication::All()
            ->whereIn('id', $application_ids)
            ->sortBy('name');
        $applicationServices = ApplicationService::All()
            ->whereIn('id', $service_ids)
            ->sortBy('name');
        $applicationModules = ApplicationModule::All()
            ->whereIn('id', $module_ids)
            ->sortBy('name');
        $databases = Database::All()
            ->whereIn('id', $database_ids)
            ->sortBy('name');

        // update lists
        $all_applicationBlocks = ApplicationBlock::All()->sortBy('name')->pluck('name', 'id');
        $all_applications = MApplication::All()->sortBy('name')->pluck('name', 'id');
        // $all_applicationServices = ApplicationService::All()->sortBy("name")->pluck("name","id");
        // $all_applicationModules = ApplicationModule::All()->sortBy("name")->pluck("name","id");
        // $all_databases = Database::All()->sortBy("name")->pluck("name","id");

        // return
        return view('admin/reports/application_flows')
            ->with('all_applicationBlocks', $all_applicationBlocks)
            ->with('all_applications', $all_applications)
            // ->with("all_applicationModules",$all_applicationModules)
            // ->with("all_applicationServices",$all_applicationServices)
            // ->with("all_databases",$all_databases)
            ->with('applications', $applications)
            ->with('applicationServices', $applicationServices)
            ->with('applicationModules', $applicationModules)
            ->with('databases', $databases)
            ->with('flows', $flows)
            ;
    }

    public function logicalInfrastructure(Request $request)
    {
        if ($request->network === null) {
            $request->session()->put('network', null);
            $network = null;
            $request->session()->put('subnetwork', null);
            $subnetwork = null;
        } else {
            if ($request->network !== null) {
                $network = intval($request->network);
                $request->session()->put('network', $network);
            } else {
                $network = $request->session()->get('network');
            }

            if ($request->subnetwork === null) {
                $request->session()->put('subnetwork', null);
                $subnetwork = null;
            } elseif ($request->subnetwork !== null) {
                $subnetwork = intval($request->subnetwork);
                $request->session()->put('subnetwork', $subnetwork);
            } else {
                $subnetwork = $request->session()->get('subnetwork');
            }
        }

        $all_networks = Network::All()->sortBy('name')->pluck('name', 'id');
        if ($network !== null) {
            $all_subnetworks = Subnetwork::All()->sortBy('name')
                ->where('network_id', '=', $network)->pluck('name', 'id');

            $networks = Network::All()->sortBy('name')->where('id', '=', $network);

            $externalConnectedEntities = ExternalConnectedEntity::
                join('external_connected_entity_network', 'external_connected_entities.id', '=', 'external_connected_entity_network.external_connected_entity_id')
                    ->where('external_connected_entity_network.network_id', '=', $network)
                    ->orderBy('name')->get();

            if ($subnetwork !== null) {
                $subnetworks = Subnetwork::All()->sortBy('name')
                    ->where('id', '=', $subnetwork);
            } else {
                $subnetworks = Subnetwork::All()->sortBy('name')
                    ->where('network_id', '=', $network);
            }

            // TODO: improve me
            $gateways = Gateway::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->gateway_id === $item->id) {
                            return true;
                        }
                    }
                    return false;
                });

            // TODO: improve me
            $networkSwitches = NetworkSwitch::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    return $subnetworks->pluck('id')->contains($item->subnetwork_id);
                });

            // TODO: improve me
            $routers = Router::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    return $subnetworks->pluck('id')->contains($item->subnetwork_id);
                });

            // TODO: improve me
            $securityDevices = SecurityDevice::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    return $subnetworks->pluck('id')->contains($item->subnetwork_id);
                });

            // TODO: improve me
            $dhcpServers = DhcpServer::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        // foreach (explode(',', $item->address_ip) as $address) {
                        if ($subnetwork->contains($item->address_ip)) {
                            return true;
                        }
                        //}
                    }
                    return false;
                });

            // TODO: improve me
            $dnsservers = Dnsserver::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        // foreach (explode(',', $item->address_ip) as $address) {
                        if ($subnetwork->contains($item->address_ip)) {
                            return true;
                        }
                        //}
                    }
                    return false;
                });

            // TODO: improve me
            $logicalServers = LogicalServer::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        foreach (explode(',', $item->address_ip) as $address) {
                            if ($subnetwork->contains($address)) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO: improve me
            $certificates = Certificate::All()->load('logical_servers')->sortBy('name')
                ->filter(function ($item) use ($logicalServers) {
                    foreach ($logicalServers as $logical_server) {
                        foreach ($logical_server->certificates as $cert) {
                            if ($cert->id === $item->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            // TODO: improve me
            $vlans = Vlan::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    return $subnetworks->pluck('vlan_id')->contains($item->id);
                });
        } else {
            $all_subnetworks = Subnetwork::All()->sortBy('name')->pluck('name', 'id');

            // all
            $networks = Network::All()->sortBy('name');
            $subnetworks = Subnetwork::All()->sortBy('name');
            $gateways = Gateway::All()->sortBy('name');
            $externalConnectedEntities = ExternalConnectedEntity::All()->sortBy('name');
            $networkSwitches = NetworkSwitch::All()->sortBy('name');
            $routers = Router::All()->sortBy('name');
            $securityDevices = SecurityDevice::All()->sortBy('name');
            $dhcpServers = DhcpServer::All()->sortBy('name');
            $dnsservers = Dnsserver::All()->sortBy('name');
            $logicalServers = LogicalServer::All()->sortBy('name');
            $certificates = Certificate::All()->sortBy('name');
            $vlans = Vlan::All()->sortBy('name');
        }

        return view(
            'admin/reports/logical_infrastructure',
            compact(
                'all_networks',
                'all_subnetworks',
                'networks',
                'subnetworks',
                'gateways',
                'externalConnectedEntities',
                'networkSwitches',
                'routers',
                'securityDevices',
                'dhcpServers',
                'dnsservers',
                'logicalServers',
                'certificates',
                'vlans'
            )
        );
    }

    public function physicalInfrastructure(Request $request)
    {
        if ($request->site === null) {
            $request->session()->put('site', null);
            $site = null;
            $request->session()->put('building', null);
            $building = null;
        } else {
            if ($request->site !== null) {
                $site = intval($request->site);
                $request->session()->put('site', $site);
            } else {
                $site = $request->session()->get('site');
            }

            if ($request->building === null) {
                $request->session()->put('building', null);
                $building = null;
            } elseif ($request->building !== null) {
                $building = intval($request->building);
                $request->session()->put('building', $building);
            } else {
                $building = $request->session()->get('building');
            }
        }

        $all_sites = Site::All()->sortBy('name')->pluck('name', 'id');

        if ($site !== null) {
            $sites = Site::All()->sortBy('name')->where('id', '=', $site);

            $all_buildings = Building::All()->sortBy('name')
                ->where('site_id', '=', $site)->pluck('name', 'id');

            if ($building === null) {
                $buildings = Building::All()->sortBy('name')->where('site_id', '=', $site);
            } else {
                $buildings = Building::All()->sortBy('name')->where('id', '=', $building);
            }

            // TODO: improve me
            $bays = Bay::All()->sortBy('name')
                ->filter(function ($item) use ($buildings) {
                    foreach ($buildings as $building) {
                        if ($item->room_id === $building->id) {
                            return true;
                        }
                    }
                    return false;
                });

            $physicalServers = PhysicalServer::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($buildings === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            $workstations = Workstation::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings) {
                    if (($item->building_id === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    foreach ($buildings as $building) {
                        if ($item->building_id === $building->id) {
                            return true;
                        }
                    }
                    return false;
                });

            $storageDevices = StorageDevice::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            $peripherals = Peripheral::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            $phones = Phone::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings) {
                    if (($item->building_id === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    foreach ($buildings as $building) {
                        if ($item->building_id === $building->id) {
                            return true;
                        }
                    }
                    return false;
                });

            $physicalSwitches = PhysicalSwitch::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            $physicalRouters = PhysicalRouter::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

            $wifiTerminals = WifiTerminal::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings) {
                    if (($item->building_id === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    foreach ($buildings as $building) {
                        if ($item->building_id === $building->id) {
                            return true;
                        }
                    }
                    return false;
                });

            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
        } else {
            $sites = Site::All()->sortBy('name');
            $buildings = Building::All()->sortBy('name');
            $all_buildings = null;
            $bays = Bay::All()->sortBy('name');
            $physicalServers = PhysicalServer::All()->sortBy('name');
            $workstations = Workstation::All()->sortBy('name');
            $storageDevices = StorageDevice::All()->sortBy('name');
            $peripherals = Peripheral::All()->sortBy('name');
            $phones = Phone::All()->sortBy('name');
            $physicalSwitches = PhysicalSwitch::All()->sortBy('name');
            $physicalRouters = PhysicalRouter::All()->sortBy('name');
            $wifiTerminals = WifiTerminal::All()->sortBy('name');
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name');
        }

        return view('admin/reports/physical_infrastructure')
            ->with('all_sites', $all_sites)
            ->with('sites', $sites)
            ->with('all_buildings', $all_buildings)
            ->with('buildings', $buildings)
            ->with('bays', $bays)
            ->with('physicalServers', $physicalServers)
            ->with('workstations', $workstations)
            ->with('storageDevices', $storageDevices)
            ->with('peripherals', $peripherals)
            ->with('phones', $phones)
            ->with('physicalSwitches', $physicalSwitches)
            ->with('physicalRouters', $physicalRouters)
            ->with('wifiTerminals', $wifiTerminals)
            ->with('physicalSecurityDevices', $physicalSecurityDevices)
            ;
    }

    public function administration()
    {
        $zones = ZoneAdmin::All();
        $annuaires = Annuaire::All();
        $forests = ForestAd::All();
        $domains = DomaineAd::All();

        return view('admin/reports/administration')
            ->with('zones', $zones)
            ->with('annuaires', $annuaires)
            ->with('forests', $forests)
            ->with('domains', $domains);
    }

    public function entities()
    {
        $path = storage_path('app/entities-'. Carbon::today()->format('Ymd') .'.xlsx');

        $entities = Entity::All()->sortBy('name');

        $header = [
            trans('cruds.entity.fields.name'),
            trans('cruds.entity.fields.description'),
            trans('cruds.entity.fields.is_external'),
            trans('cruds.entity.fields.security_level'),
            trans('cruds.entity.fields.contact_point'),
            trans('cruds.entity.fields.applications_resp'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(60, 'pt');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($entities as $entity) {
            $sheet->setCellValue("A{$row}", $entity->name);
            $sheet->setCellValue("B{$row}", $html->toRichTextObject($entity->description));
            $sheet->setCellValue("C{$row}", $entity->is_external);
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

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function applicationsByBlocks()
    {
        $path = storage_path('app/applications-'. Carbon::today()->format('Ymd') .'.xlsx');

        $applicationBlocks = ApplicationBlock::All()->sortBy('name');
        $applicationBlocks->load('applications');

        $header = [
            trans('cruds.application.fields.application_block'),
            trans('cruds.application.fields.name'),
            trans('cruds.application.fields.version'),
            trans('cruds.application.fields.description'),
            trans('cruds.application.fields.entity_resp'),
            trans('cruds.application.fields.entities'),
            trans('cruds.application.fields.responsible'),
            trans('cruds.application.fields.processes'),
            trans('cruds.application.fields.technology'),
            trans('cruds.application.fields.type'),
            trans('cruds.application.fields.users'),
            trans('cruds.application.fields.external'),
            'C',
            'I',
            'A',
            'T',
            trans('cruds.application.fields.documentation'),
            trans('cruds.application.fields.logical_servers'),
            trans('cruds.physicalServer.title'),
            trans('cruds.application.fields.databases'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(60, 'pt');
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setWidth(60, 'pt');
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        // CIAT
        $sheet->getColumnDimension('M')->setWidth(10, 'pt');
        $sheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('N')->setWidth(10, 'pt');
        $sheet->getStyle('N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('O')->setWidth(10, 'pt');
        $sheet->getStyle('O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('P')->setWidth(10, 'pt');
        $sheet->getStyle('P')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setWidth(200, 'pt');
        $sheet->getColumnDimension('S')->setWidth(200, 'pt');
        $sheet->getColumnDimension('T')->setWidth(200, 'pt');

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
                $sheet->setCellValue("C{$row}", $application->version);
                $sheet->setCellValue("D{$row}", $html->toRichTextObject($application->description));
                $sheet->setCellValue("E{$row}", $application->entity_resp ? $application->entity_resp->name : '');
                $sheet->setCellValue("F{$row}", $application->entities->implode('name', ', '));
                $sheet->setCellValue("G{$row}", $application->responsible);
                $sheet->setCellValue("H{$row}", $application->processes->implode('identifiant', ', '));
                $sheet->setCellValue("I{$row}", $application->technology);
                $sheet->setCellValue("J{$row}", $application->type);
                $sheet->setCellValue("K{$row}", $application->users);
                $sheet->setCellValue("L{$row}", $application->external);

                $sheet->setCellValue("M{$row}", $application->security_need_c);
                $this->addSecurityNeedColor($sheet, "M{$row}", $application->security_need_c);

                $sheet->setCellValue("N{$row}", $application->security_need_i);
                $this->addSecurityNeedColor($sheet, "N{$row}", $application->security_need_i);

                $sheet->setCellValue("O{$row}", $application->security_need_a);
                $this->addSecurityNeedColor($sheet, "O{$row}", $application->security_need_a);

                $sheet->setCellValue("P{$row}", $application->security_need_t);
                $this->addSecurityNeedColor($sheet, "P{$row}", $application->security_need_t);

                $sheet->setCellValue("Q{$row}", $application->documentation);
                $sheet->setCellValue("R{$row}", $application->logical_servers->implode('name', ', '));
                $res = null;

                // TODO: improve me with select, join and unique
                foreach ($application->logical_servers as $logical_server) {
                    foreach ($logical_server->servers as $physical_server) {
                        if ($res !== null) {
                            $res .= ', ';
                        }
                        $res .= $physical_server->name;
                    }
                }

                $sheet->setCellValue("S{$row}", $res);
                $sheet->setCellValue("T{$row}", $application->databases->implode('name', ', '));

                $row++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function logicalServerResp()
    {
        $path = storage_path('app/logicalServersResp-'. Carbon::today()->format('Ymd') .'.xlsx');

        $logicalServers = LogicalServer::All()->sortBy('name');
        $logicalServers->load('applications', 'applications.application_block');

        $header = [
            trans('cruds.logicalServer.title_singular'),
            trans('cruds.application.title_singular'),
            trans('cruds.application.fields.entities'),
            trans('cruds.application.fields.entity_resp'),
            trans('cruds.application.fields.responsible'),
            trans('cruds.applicationBlock.title_singular'),
            trans('cruds.applicationBlock.fields.responsible'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
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

        // converter
        // $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

        // Populate the Timesheet
        $row = 2;
        foreach ($logicalServers as $logicalServer) {
            foreach ($logicalServer->applications as $application) {
                $sheet->setCellValue("A{$row}", $logicalServer->name);
                $sheet->setCellValue("B{$row}", $application->name);

                $entities = $application->entities()->get();
                $l = null;
                foreach ($entities as $entity) {
                    if ($l === null) {
                        $l = $entity->name;
                    } else {
                        $l .= ', '.$entity->name;
                    }
                }
                $sheet->setCellValue("C{$row}", $l);

                $l = $application->entity_resp()->get();
                if ($l->count() > 0) {
                    $sheet->setCellValue("D{$row}", $l[0]->name);
                }

                $sheet->setCellValue("E{$row}", $application->responsible);
                $sheet->setCellValue("F{$row}", $application->application_block->name ?? '');
                $sheet->setCellValue("G{$row}", $application->application_block->responsible ?? '');

                $row++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function logicalServerConfigs()
    {
        $path = storage_path('app/logicalServers-'. Carbon::today()->format('Ymd') .'.xlsx');

        $logicalServers = LogicalServer::All()->sortBy('name');
        $logicalServers->load('applications', 'servers');

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
            trans('cruds.logicalServer.fields.servers'),            // M
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
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

        // center (CPU, Men, Disk)
        $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

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
            $sheet->setCellValue("M{$row}", $logicalServer->servers->implode('name', ', '));

            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function securityNeeds()
    {
        $path = storage_path('app/securityNeeds-'. Carbon::today()->format('Ymd') .'.xlsx');

        // macroprocess - process - application - base de données - information
        $header = [
            trans('cruds.macroProcessus.title'),
            'C','I','A','T',
            trans('cruds.process.title'),
            'C','I','A','T',
            trans('cruds.application.title'),
            'C','I','A','T',
            trans('cruds.database.title'),
            'C','I','A','T',
            trans('cruds.information.title'),
            'C','I','A','T',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // Widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(10, 'pt');
        $sheet->getColumnDimension('C')->setWidth(10, 'pt');
        $sheet->getColumnDimension('D')->setWidth(10, 'pt');
        $sheet->getColumnDimension('E')->setWidth(10, 'pt');
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setWidth(10, 'pt');
        $sheet->getColumnDimension('H')->setWidth(10, 'pt');
        $sheet->getColumnDimension('I')->setWidth(10, 'pt');
        $sheet->getColumnDimension('J')->setWidth(10, 'pt');
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setWidth(10, 'pt');
        $sheet->getColumnDimension('M')->setWidth(10, 'pt');
        $sheet->getColumnDimension('N')->setWidth(10, 'pt');
        $sheet->getColumnDimension('O')->setWidth(10, 'pt');
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setWidth(10, 'pt');
        $sheet->getColumnDimension('R')->setWidth(10, 'pt');
        $sheet->getColumnDimension('S')->setWidth(10, 'pt');
        $sheet->getColumnDimension('T')->setWidth(10, 'pt');
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setWidth(10, 'pt');
        $sheet->getColumnDimension('W')->setWidth(10, 'pt');
        $sheet->getColumnDimension('X')->setWidth(10, 'pt');
        $sheet->getColumnDimension('Y')->setWidth(10, 'pt');

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
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function physicalInventory()
    {
        $path = storage_path('app/physicalInventory-'. Carbon::today()->format('Ymd') .'.xlsx');

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

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
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
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html();

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

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path);
    }

    public function zones()
    {
        $subnetworks = Subnetwork::All()->sortBy('zone, address');

        return view('admin/reports/zones')
            ->with('subnetworks');
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

    private function addSecurityNeedColor(Worksheet $sheet, string $cell, int $i)
    {
        static $colors = [-1 => 'FFFFFF', 0 => 'FFFFFF',1 => '8CD17D',2 => 'F1CE63',3 => 'F28E2B',4 => 'E15759'];
        $sheet->getStyle($cell)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($colors[$i === null ? 0 : $i]);
    }

    private function addLine(
        Worksheet $sheet,
        int $row,
        MacroProcessus $macroprocess,
        ?Process $process = null,
        ?MApplication $application = null,
        ?Database $database = null,
        ?Information $information = null
    ) {

        // Macroprocessus
        $sheet->setCellValue("A{$row}", $macroprocess->name);

        $sheet->setCellValue("B{$row}", $macroprocess->security_need_c >= 0 ? $macroprocess->security_need_c : '');
        $this->addSecurityNeedColor($sheet, "B{$row}", $macroprocess->security_need_c);

        $sheet->setCellValue("C{$row}", $macroprocess->security_need_i >= 0 ? $macroprocess->security_need_i : '');
        $this->addSecurityNeedColor($sheet, "C{$row}", $macroprocess->security_need_i);

        $sheet->setCellValue("D{$row}", $macroprocess->security_need_a >= 0 ? $macroprocess->security_need_a : '');
        $this->addSecurityNeedColor($sheet, "D{$row}", $macroprocess->security_need_a);

        $sheet->setCellValue("E{$row}", $macroprocess->security_need_t >= 0 ? $macroprocess->security_need_t : '');
        $this->addSecurityNeedColor($sheet, "E{$row}", $macroprocess->security_need_t);

        if ($process !== null) {
            // Processus
            $sheet->setCellValue("F{$row}", $process->identifiant);
            $sheet->setCellValue("G{$row}", $process->security_need_c >= 0 ? $process->security_need_c : '');
            $this->addSecurityNeedColor($sheet, "G{$row}", $process->security_need_c);

            $sheet->setCellValue("H{$row}", $process->security_need_i >= 0 ? $process->security_need_i : '');
            $this->addSecurityNeedColor($sheet, "H{$row}", $process->security_need_i);

            $sheet->setCellValue("I{$row}", $process->security_need_a >= 0 ? $process->security_need_a : '');
            $this->addSecurityNeedColor($sheet, "I{$row}", $process->security_need_a);

            $sheet->setCellValue("J{$row}", $process->security_need_t >= 0 ? $process->security_need_t : '');
            $this->addSecurityNeedColor($sheet, "J{$row}", $process->security_need_t);

            if ($application !== null) {
                // Application
                $sheet->setCellValue("K{$row}", $application->name);

                $sheet->setCellValue("L{$row}", $application->security_need_c >= 0 ? $application->security_need_c : '');
                $this->addSecurityNeedColor($sheet, "L{$row}", $application->security_need_c);

                $sheet->setCellValue("M{$row}", $application->security_need_i >= 0 ? $application->security_need_i : '');
                $this->addSecurityNeedColor($sheet, "M{$row}", $application->security_need_i);

                $sheet->setCellValue("N{$row}", $application->security_need_a >= 0 ? $application->security_need_a : '');
                $this->addSecurityNeedColor($sheet, "N{$row}", $application->security_need_a);

                $sheet->setCellValue("O{$row}", $application->security_need_t >= 0 ? $application->security_need_t : '');
                $this->addSecurityNeedColor($sheet, "O{$row}", $application->security_need_t);

                if ($database !== null) {
                    // Database
                    $sheet->setCellValue("P{$row}", $database->name);
                    $sheet->setCellValue("Q{$row}", $database->security_need_c >= 0 ? $database->security_need_c : '');
                    $this->addSecurityNeedColor($sheet, "Q{$row}", $database->security_need_c);
                    $sheet->setCellValue("R{$row}", $database->security_need_i >= 0 ? $database->security_need_i : '');
                    $this->addSecurityNeedColor($sheet, "R{$row}", $database->security_need_i);
                    $sheet->setCellValue("S{$row}", $database->security_need_a >= 0 ? $database->security_need_a : '');
                    $this->addSecurityNeedColor($sheet, "S{$row}", $database->security_need_a);
                    $sheet->setCellValue("T{$row}", $database->security_need_t >= 0 ? $database->security_need_t : '');
                    $this->addSecurityNeedColor($sheet, "T{$row}", $database->security_need_t);

                    if ($information !== null) {
                        // Information
                        $sheet->setCellValue("U{$row}", $information->name);
                        $sheet->setCellValue("V{$row}", $information->security_need_c >= 0 ? $information->security_need_c : '');
                        $this->addSecurityNeedColor($sheet, "V{$row}", $information->security_need_c);
                        $sheet->setCellValue("W{$row}", $information->security_need_i >= 0 ? $information->security_need_i : '');
                        $this->addSecurityNeedColor($sheet, "W{$row}", $information->security_need_i);
                        $sheet->setCellValue("X{$row}", $information->security_need_a >= 0 ? $information->security_need_a : '');
                        $this->addSecurityNeedColor($sheet, "X{$row}", $information->security_need_a);
                        $sheet->setCellValue("Y{$row}", $information->security_need_t >= 0 ? $information->security_need_t : '');
                        $this->addSecurityNeedColor($sheet, "Y{$row}", $information->security_need_t);
                    }
                }
            }
        }
    }
}
