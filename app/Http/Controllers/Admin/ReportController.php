<?php

namespace App\Http\Controllers\Admin;

// GDPR
// ecosystem
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityImpact;
use App\Models\Actor;
use App\Models\AdminUser;
use App\Models\Annuaire;
use App\Models\ApplicationBlock;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Certificate;
use App\Models\Cluster;
use App\Models\Container;
use App\Models\Database;
use App\Models\DataProcessing;
use App\Models\DhcpServer;
use App\Models\Dnsserver;
use App\Models\DomaineAd;
use App\Models\Entity;
use App\Models\ExternalConnectedEntity;
use App\Models\Flux;
use App\Models\ForestAd;
use App\Models\Gateway;
use App\Models\Information;
use App\Models\LogicalServer;
use App\Models\MacroProcessus;
use App\Models\MApplication;
use App\Models\Network;
use App\Models\NetworkSwitch;
use App\Models\Operation;
use App\Models\Peripheral;
use App\Models\Phone;
use App\Models\PhysicalLink;
use App\Models\PhysicalRouter;
use App\Models\PhysicalSecurityDevice;
use App\Models\PhysicalServer;
use App\Models\PhysicalSwitch;
use App\Models\Process;
use App\Models\Relation;
use App\Models\Router;
use App\Models\SecurityDevice;
use App\Models\Site;
use App\Models\StorageDevice;
use App\Models\Subnetwork;
use App\Models\Task;
use App\Models\Vlan;
use App\Models\WifiTerminal;
use App\Models\Workstation;
use App\Models\ZoneAdmin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

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
    public const ALLOWED_PERIMETERS = ['All', 'Internes', 'Externes'];

    public const SANITIZED_PERIMETER = 'All';

    /*
    * GDPR
    */
    public function gdpr(Request $request)
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

        // All macroprocess with process having a data_processing
        $all_macroprocess = MacroProcessus::orderBy('name')
            ->whereExists(function ($query) {
                $query->select('processes.id')
                    ->from('processes')
                    ->join('data_processing_process', 'processes.id', '=', 'data_processing_process.process_id')
                    ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                    ->whereNull('data_processing.deleted_at')
                    ->whereRaw('macro_processuses.id = processes.macroprocess_id');
            })
            ->get();

        if ($macroprocess !== null) {
            $macroProcessuses = MacroProcessus::where('id', $macroprocess)
                ->get();

            $all_process = Process::orderBy('name')
                ->where('macroprocess_id', $macroprocess)
                ->whereExists(function ($query) {
                    $query->select('data_processing_process.process_id')
                        ->from('data_processing_process')
                        ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                        ->whereNull('data_processing.deleted_at')
                        ->whereRaw('data_processing_process.process_id = processes.id');
                })
                ->get();

            if ($process !== null) {
                // Data processing of this process
                $dataProcessings = DataProcessing::orderBy('name')
                    ->whereExists(function ($query) use ($process) {
                        $query->select('data_processing_id')
                            ->from('data_processing_process')
                            ->where('data_processing_process.process_id', $process)
                            ->whereRaw('data_processing_process.data_processing_id = data_processing.id');
                    })
                    ->get();

                $processes = Process::where('id', $process)->get();
            } else {
                // Data processing for this macroprocess
                $dataProcessings = DataProcessing::orderBy('name')
                    ->whereExists(function ($query) use ($macroprocess) {
                        $query->select('data_processing_id')
                            ->from('data_processing_process')
                            ->join('processes', 'processes.id', 'data_processing_process.process_id')
                            ->where('processes.macroprocess_id', $macroprocess)
                            ->whereRaw('data_processing_process.data_processing_id = data_processing.id');
                    })
                    ->get();
                $processes = $all_process;
            }
        } else {
            // only macroProcesses with data processisng
            $macroProcessuses = MacroProcessus::orderBy('name')
                ->whereExists(function ($query) {
                    $query->select('processes.id')
                        ->from('processes')
                        ->join('data_processing_process', 'data_processing_process.process_id', '=', 'processes.id')
                        ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                        ->whereNull('data_processing.deleted_at')
                        ->whereRaw('processes.macroprocess_id = macro_processuses.id');
                })
                ->get();

            // only process with data processisng
            $processes = Process::orderBy('name')
                ->whereExists(function ($query) {
                    $query->select('data_processing_id')
                        ->from('data_processing_process')
                        ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                        ->whereNull('data_processing.deleted_at')
                        ->whereRaw('data_processing_process.process_id = processes.id');
                })
                ->get();

            $dataProcessings = DataProcessing::orderBy('name')->get();

            $all_process = Process::orderBy('name')
                ->where('macroprocess_id', $macroprocess)
                ->whereExists(function ($query) {
                    $query->select('data_processing_process.process_id')
                        ->from('data_processing_process')
                        ->whereRaw('data_processing_process.process_id = processes.id');
                })
                ->get();
        }

        // Select applications
        $applications = MApplication::join('data_processing_m_application', 'm_application_id', 'm_applications.id')
            ->wherein('data_processing_id', $dataProcessings->pluck('id')->all())->get();

        return view('admin/reports/gdpr')
            ->with('all_macroprocess', $all_macroprocess)
            ->with('macroProcessuses', $macroProcessuses)
            ->with('processes', $processes)
            ->with('all_process', $all_process)
            ->with('dataProcessings', $dataProcessings)
            ->with('applications', $applications);
    }

    /*
    * Ecosystem View
    */
    public function ecosystem(Request $request)
    {
        $perimeter = in_array($request->perimeter, $this::ALLOWED_PERIMETERS) ?
                   $request->perimeter : $this::SANITIZED_PERIMETER;
        $typefilter = $request->entity_type ??= 'All';

        $entitiesGroups = Entity::All()->groupBy('entity_type');
        $entities = collect([]);
        $entityTypes = collect([]);
        $isTypeExists = false; /* sanitize entity_type: si type inconnu pas d'entités */
        foreach ($entitiesGroups as $entity_type => $entOfGroup) {
            $entities = $entities->concat($entOfGroup);
            if ($entity_type !== null) {
                $isTypeExists = $isTypeExists || ($entity_type === $typefilter);
                $entityTypes->push($entity_type);
            }
        }

        $has_filter = false;
        if ($typefilter !== 'All') {
            $has_filter = true;
            $entities = $isTypeExists ? $entitiesGroups[$typefilter] : collect([]);
        }

        if ($perimeter !== 'All') {
            $has_filter = true;
            $entities = $entities
                ->filter(function ($item) use ($perimeter) {
                    return $perimeter === 'Externes' ?
                                       $item->is_external : ! $item->is_external;
                });
        }

        $relations = Relation::All()->sortBy('name');
        if ($has_filter) {
            /**
             * Le "group by" semble résoudre les entités on doit travailler avec les ids ..
             */
            $ids = $entities->map(function ($item) {
                return $item->id;
            });
            $relations = $relations
                ->filter(function ($item) use ($ids) {
                    return $ids->contains($item->source_id) &&
                        $ids->contains($item->destination_id);
                });
        }

        $request->session()->put('perimeter', $perimeter);
        $request->session()->put('entity_type', $typefilter);

        return view('admin/reports/ecosystem')
            ->with('entityTypes', $entityTypes)
            ->with('entities', $entities)
            ->with('relations', $relations);
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
            $macroProcessuses = MacroProcessus::where('macro_processuses.id', $macroprocess)->get();

            // TODO : improve me
            $processes = Process::All()->sortBy('name')
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
            $all_process = Process::All()->sortBy('name')
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
                    foreach ($item->processes as $p) {
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
                    foreach ($item->activities as $o) {
                        foreach ($activities as $activity) {
                            if ($o->id === $activity->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $tasks = Task::All()->sortBy('name')
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
                        foreach ($process->information as $information) {
                            if ($item->id === $information->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });
        } else {
            $macroProcessuses = MacroProcessus::All()->sortBy('name');
            $processes = Process::All()->sortBy('name');
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
            $applicationBlocks = ApplicationBlock::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->id === $applicationBlock;
                });

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

            $applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application === null) {
                        return $item->application_block_id === $applicationBlock;
                    }

                    return $item->id === $application;
                });

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
            ->with('fluxes', $fluxes);
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

        // Databases
        if ($request->databases === null) {
            $request->session()->put('databases', []);
            $databases = [];
        } else {
            if ($request->databases !== null) {
                $databases = $request->databases;
                $request->session()->put('databases', $databases);
            } else {
                $databases = $request->session()->get('databases');
            }
        }

        // Get assets
        $application_ids = DB::table('m_applications')
            ->whereIn('application_block_id', $applicationBlocks)
            ->whereNull('deleted_at')
            ->orWhereIn('id', $applications)
            ->pluck('id');

        $applicationservice_ids = DB::table('m_applications')
            ->join('application_service_m_application', 'm_applications.id', '=', 'application_service_m_application.m_application_id')
            ->whereIn('application_block_id', $applicationBlocks)
            ->whereNull('deleted_at')
            ->pluck('application_service_id')
            ->unique();

        $applicationmodule_ids = DB::table('m_applications')
            ->join('application_service_m_application', 'm_applications.id', '=', 'application_service_m_application.m_application_id')
            ->join('application_module_application_service', 'application_service_m_application.application_service_id', '=', 'application_module_application_service.application_service_id')
            ->whereNull('deleted_at')
            ->whereIn('application_block_id', $applicationBlocks)
            ->pluck('application_module_id')
            ->unique();

        $database_ids = collect($databases);

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
        $application_ids = collect();
        $service_ids = collect();
        $module_ids = collect();

        // loop on flows
        foreach ($flows as $flux) {
            // applications
            if (($flux->application_source_id !== null) &&
               (! $application_ids->contains($flux->application_source_id))) {
                $application_ids->push($flux->application_source_id);
            }
            if (($flux->application_dest_id !== null) &&
               (! $application_ids->contains($flux->application_dest_id))) {
                $application_ids->push($flux->application_dest_id);
            }

            // services
            if (($flux->service_source_id !== null) &&
               (! $service_ids->contains($flux->service_source_id))) {
                $service_ids->push($flux->service_source_id);
            }
            if (($flux->service_dest_id !== null) &&
               (! $service_ids->contains($flux->service_dest_id))) {
                $service_ids->push($flux->service_dest_id);
            }

            // modules
            if (($flux->module_source_id !== null) &&
               (! $module_ids->contains($flux->module_source_id))) {
                $module_ids->push($flux->module_source_id);
            }
            if (($flux->module_dest_id !== null) &&
               (! $module_ids->contains($flux->module_dest_id))) {
                $module_ids->push($flux->module_dest_id);
            }

            // databases
            if (($flux->database_source_id !== null) &&
               (! $database_ids->contains($flux->database_source_id))) {
                $database_ids->push($flux->database_source_id);
            }
            if (($flux->database_dest_id !== null) &&
               (! $database_ids->contains($flux->database_dest_id))) {
                $database_ids->push($flux->database_dest_id);
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
        $all_databases = Database::All()->sortBy('name')->pluck('name', 'id');

        // return
        return view('admin/reports/application_flows')
            ->with('all_applicationBlocks', $all_applicationBlocks)
            ->with('all_applications', $all_applications)
            ->with('all_databases', $all_databases)
            ->with('applications', $applications)
            ->with('applicationServices', $applicationServices)
            ->with('applicationModules', $applicationModules)
            ->with('databases', $databases)
            ->with('flows', $flows);
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
        if ($request->has('show_ip')) {
            $request->session()->put('show_ip', true);
        } else {
            $request->session()->put('show_ip', null);
        }

        $all_networks = Network::All()->sortBy('name')->pluck('name', 'id');
        if ($network !== null) {
            $all_subnetworks = Subnetwork::All()->sortBy('name')
                ->where('network_id', '=', $network)->pluck('name', 'id');

            $networks = Network::All()->sortBy('name')->where('id', '=', $network);

            $externalConnectedEntities = ExternalConnectedEntity::where('network_id', '=', $network)
                ->orderBy('name')->get();

            if ($subnetwork !== null) {
                $subnetworks = Subnetwork::All()->sortBy('name')
                    ->where('id', '=', $subnetwork);
            } else {
                $subnetworks = Subnetwork::All()->sortBy('name')
                    ->where('network_id', '=', $network);
            }

            // Get Gateways
            $gateways = Gateway::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->gateway_id === $item->id) {
                            return true;
                        }
                    }

                    return false;
                });

            // Get NetworkSwitches
            $networkSwitches = NetworkSwitch::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->ip) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get Workstations
            $workstations = Workstation::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->address_ip) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get WifiTerminals
            $wifiTerminals = WifiTerminal::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->address_ip) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get Phones
            $phones = Phone::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->address_ip) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get peripherals
            $peripherals = Peripheral::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->address_ip) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get Physical Security Devices
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->address_ip) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get routers
            $routers = Router::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->ip_addresses) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get Security Devices
            $securityDevices = SecurityDevice::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->ip_addresses) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get StorageDevices
            $storageDevices = StorageDevice::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach (explode(',', $item->ip_addresses) as $ip) {
                        foreach ($subnetworks as $subnetwork) {
                            if ($subnetwork->contains($ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get DHCP Servers
            $dhcpServers = DhcpServer::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        foreach (explode(',', $item->address_ip) as $address) {
                            if ($subnetwork->contains($item->address_ip)) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get DNS Servers
            $dnsservers = Dnsserver::All()->sortBy('name')
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        // foreach (explode(',', $item->address_ip) as $address) {
                        if ($subnetwork->contains($item->address_ip)) {
                            return true;
                        }
                        // }
                    }

                    return false;
                });

            $clusters = Cluster::All()->sortBy('name');

            // Get Logical serveurs
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

            // Get Certificates
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

            // Get Containers
            $containers = Container::All()->load('logicalServers')->sortBy('name')
                ->filter(function ($item) use ($logicalServers) {
                    foreach ($logicalServers as $logical_server) {
                        foreach ($logical_server->containers as $container) {
                            if ($container->id === $item->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Get VLANS
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
            $workstations = Workstation::All()->sortBy('name');
            $wifiTerminals = WifiTerminal::All()->sortBy('name');
            $phones = Phone::All()->sortBy('name');
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name');
            $peripherals = Peripheral::All()->sortBy('name');
            $routers = Router::All()->sortBy('name');
            $securityDevices = SecurityDevice::All()->sortBy('name');
            $storageDevices = StorageDevice::All()->sortBy('name');
            $dhcpServers = DhcpServer::All()->sortBy('name');
            $dnsservers = Dnsserver::All()->sortBy('name');
            $clusters = Cluster::All()->sortBy('name');
            $logicalServers = LogicalServer::All()->sortBy('name');
            $containers = Container::All()->sortBy('name');
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
                'workstations',
                'phones',
                'physicalSecurityDevices',
                'peripherals',
                'wifiTerminals',
                'routers',
                'securityDevices',
                'storageDevices',
                'dhcpServers',
                'dnsservers',
                'clusters',
                'logicalServers',
                'certificates',
                'containers',
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

            // Get all bays
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
            ->with('physicalSecurityDevices', $physicalSecurityDevices);
    }

    public function networkInfrastructure(Request $request)
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

            if ($request->buildings === null) {
                $request->session()->put('buildings', null);
                $buildings = null;
            } elseif ($request->buildings !== null) {
                $buildings = $request->buildings;
                $request->session()->put('buildings', $buildings);
            } else {
                $buildings = $request->session()->get('buildings');
            }
        }

        $all_sites = Site::All()->sortBy('name')->pluck('name', 'id');

        if ($site !== null) {
            $sites = Site::All()->sortBy('name')->where('id', '=', $site);

            $all_buildings = Building::All()->sortBy('name')
                ->where('site_id', '=', $site)->pluck('name', 'id');
            if ($buildings === null || (count($buildings) === 0)) {
                $buildings = Building::All()
                    ->sortBy('name')
                    ->where('site_id', '=', $site);
            } else {
                $buildings = Building::All()->sortBy('name')->whereIn('id', $buildings);
            }

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

            // Filter physicalLinks on selected objects
            $physicalLinks = PhysicalLink::All()->sortBy('name')
                ->filter(function ($item) use (
                    $physicalRouters,
                    $physicalServers,
                    $workstations,
                    $storageDevices,
                    $physicalSwitches,
                    $peripherals,
                    $wifiTerminals,
                    $phones
                ) {
                    // Routers
                    if ($item->physical_router_src_id !== null) {
                        $found = false;
                        foreach ($physicalRouters as $router) {
                            if ($item->physical_router_src_id === $router->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->physical_router_dest_id !== null) {
                        $found = false;
                        foreach ($physicalRouters as $router) {
                            if ($item->physical_router_dest_id === $router->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Switches
                    if ($item->physical_switch_src_id !== null) {
                        $found = false;
                        foreach ($physicalSwitches as $physicalSwitch) {
                            if ($item->physical_switch_src_id === $physicalSwitch->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->physical_switch_dest_id !== null) {
                        $found = false;
                        foreach ($physicalSwitches as $physicalSwitch) {
                            if ($item->physical_switch_dest_id === $physicalSwitch->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Servers
                    if ($item->physical_server_src_id !== null) {
                        $found = false;
                        foreach ($physicalServers as $server) {
                            if ($item->physical_server_src_id === $server->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->physical_server_dest_id !== null) {
                        $found = false;
                        foreach ($physicalServers as $server) {
                            if ($item->physical_server_dest_id === $server->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Workstations
                    if ($item->workstation_src_id !== null) {
                        $found = false;
                        foreach ($workstations as $workstation) {
                            if ($item->workstation_src_id === $workstation->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->workstation_dest_id !== null) {
                        $found = false;
                        foreach ($workstations as $workstation) {
                            if ($item->workstation_dest_id === $workstation->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Peripheral
                    if ($item->peripheral_src_id !== null) {
                        $found = false;
                        foreach ($peripherals as $peripheral) {
                            if ($item->peripheral_src_id === $peripheral->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->peripheral_dest_id !== null) {
                        $found = false;
                        foreach ($peripherals as $peripheral) {
                            if ($item->peripheral_dest_id === $peripheral->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Storage
                    if ($item->storage_device_src_id !== null) {
                        $found = false;
                        foreach ($storageDevices as $storageDevice) {
                            if ($item->storage_device_src_id === $storageDevice->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->storage_device_dest_id !== null) {
                        $found = false;
                        foreach ($storageDevices as $storageDevice) {
                            if ($item->storage_device_dest_id === $storageDevice->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Wifi
                    if ($item->wifi_terminal_src_id !== null) {
                        $found = false;
                        foreach ($wifiTerminals as $wifiTerminal) {
                            if ($item->wifi_terminal_src_id === $wifiTerminal->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->wifi_terminal_dest_id !== null) {
                        $found = false;
                        foreach ($wifiTerminals as $wifiTerminal) {
                            if ($item->wifi_terminal_dest_id === $wifiTerminal->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Phones
                    if ($item->phone_src_id !== null) {
                        $found = false;
                        foreach ($phones as $phone) {
                            if ($item->phone_src_id === $phone->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->phone_dest_id !== null) {
                        $found = false;
                        foreach ($phones as $phone) {
                            if ($item->phone_dest_id === $phone->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }

                    return true;
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
            $physicalLinks = PhysicalLink::All()->sortBy('name');
        }

        return view('admin/reports/network_infrastructure')
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
            ->with('physicalLinks', $physicalLinks);
    }

    public function administration()
    {
        $zones = ZoneAdmin::All();
        $annuaires = Annuaire::All();
        $forests = ForestAd::All();
        $domains = DomaineAd::All();
        $adminUsers = AdminUser::All();

        return view('admin/reports/administration')
            ->with('zones', $zones)
            ->with('annuaires', $annuaires)
            ->with('forests', $forests)
            ->with('domains', $domains)
            ->with('adminUsers', $adminUsers);
    }

    public function entities()
    {
        $entities = Entity::All()->sortBy('name');

        $header = [
            trans('cruds.entity.fields.name'),
            trans('cruds.entity.fields.description'),
            trans('cruds.entity.fields.is_external'),
            trans('cruds.entity.fields.entity_type'),
            trans('cruds.entity.fields.security_level'),
            trans('cruds.entity.fields.contact_point'),
            trans('cruds.entity.fields.applications_resp'),
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

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the Timesheet
        $row = 2;
        foreach ($entities as $entity) {
            $sheet->setCellValue("A{$row}", $entity->name);
            $sheet->setCellValue("B{$row}", $html->toRichTextObject($entity->description));
            $sheet->setCellValue("C{$row}", $entity->is_external ? trans('global.yes') : trans('global.no'));
            $sheet->setCellValue("D{$row}", $entity->entity_type);
            $sheet->setCellValue("E{$row}", $html->toRichTextObject($entity->security_level));
            $sheet->setCellValue("F{$row}", $html->toRichTextObject($entity->contact_point));
            $txt = '';
            foreach ($entity->applications as $application) {
                $txt .= $application->name;
                if ($entity->applications->last() !== $application) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("G{$row}", $txt);

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/entities-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/entities-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function activityReport()
    {
        // get template
        $phpWord = new \PhpOffice\PhpWord\PhpWord;
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);
        $section = $phpWord->addSection();

        // Numbering Style
        $phpWord->addNumberingStyle(
            'hNum',
            ['type' => 'multilevel', 'levels' => [
                ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.'],
                ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2.'],
                ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3.'],
            ],
            ]
        );
        $phpWord->addTitleStyle(
            0,
            ['size' => 28, 'bold' => true],
            ['align' => 'center']
        );
        $phpWord->addTitleStyle(
            1,
            ['size' => 16, 'bold' => true],
            ['spaceAfter' => 100, 'spaceBefore' => 100, 'numStyle' => 'hNum', 'numLevel' => 0]
        );
        $phpWord->addTitleStyle(
            2,
            ['size' => 14, 'bold' => true],
            ['spaceAfter' => 100, 'spaceBefore' => 100, 'numStyle' => 'hNum', 'numLevel' => 1]
        );
        $phpWord->addTitleStyle(
            3,
            ['size' => 12, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 2]
        );

        // Title
        $section->addTitle(trans('cruds.dataProcessing.report_title'), 0);
        $section->addTextBreak(1);

        // TOC
        $toc = $section->addTOC(['spaceAfter' => 50, 'size' => 10]);
        $toc->setMinDepth(1);
        $toc->setMaxDepth(1);
        $section->addTextBreak(1);

        // page break
        $section->addPageBreak();

        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $register = DataProcessing::orderBy('name')->get();
        foreach ($register as $dataProcessing) {
            // schema
            $section->addTitle($dataProcessing->name, 1);

            $section->addTitle(trans('cruds.dataProcessing.fields.legal_basis'), 2);
            $this->addText($section, $dataProcessing->legal_basis);

            $section->addTitle(trans('cruds.dataProcessing.fields.description'), 2);
            $this->addText($section, $dataProcessing->description);

            $section->addTitle(trans('cruds.dataProcessing.fields.responsible'), 2);
            $this->addText($section, $dataProcessing->responsible);

            $section->addTitle(trans('cruds.dataProcessing.fields.purpose'), 2);
            $this->addText($section, $dataProcessing->purpose);

            $section->addTitle(trans('cruds.dataProcessing.fields.categories'), 2);
            $this->addText($section, $dataProcessing->categories);

            $section->addTitle(trans('cruds.dataProcessing.fields.recipients'), 2);
            $this->addText($section, $dataProcessing->recipients);

            $section->addTitle(trans('cruds.dataProcessing.fields.transfert'), 2);
            $this->addText($section, $dataProcessing->transfert);

            $section->addTitle(trans('cruds.dataProcessing.fields.retention'), 2);
            $this->addText($section, $dataProcessing->retention);

            // Processes
            $section->addTitle(trans('cruds.dataProcessing.fields.processes'), 2);
            $txt = '<ul>';
            foreach ($dataProcessing->processes as $p) {
                $txt .= '<li>'.$p->name.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);

            // Applications
            $section->addTitle(trans('cruds.dataProcessing.fields.applications'), 2);
            $txt = '<ul>';
            foreach ($dataProcessing->applications as $ap) {
                $txt .= '<li>'.$ap->name.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);

            // Informations
            $section->addTitle(trans('cruds.dataProcessing.fields.information'), 2);
            $txt = '<ul>';
            foreach ($dataProcessing->informations as $inf) {
                $txt .= '<li>'.$inf->name.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);

            // Security Controls
            $section->addTitle(trans('cruds.dataProcessing.fields.security_controls'), 2);
            // TODO : improve me
            $allControls = Collect();
            foreach ($dataProcessing->processes as $process) {
                foreach ($process->securityControls as $sc) {
                    $allControls->push($sc->name);
                }
            }
            foreach ($dataProcessing->applications as $app) {
                foreach ($app->securityControls as $sc) {
                    $allControls->push($sc->name);
                }
            }
            $allControls->unique();
            $txt = '<ul>';
            foreach ($allControls as $control) {
                $txt .= '<li>'.$control.'</li>';
            }
            $txt .= '</ul>';
            $this->addText($section, $txt);
        }

        // Finename
        $filepath = storage_path('app/reports/register-'.Carbon::today()->format('Ymd').'.docx');

        // Saving the document as Word2007 file.
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $objWriter->save($filepath);

        // return
        return response()->download($filepath);
    }

    public function activityList()
    {
        $register = DataProcessing::All()->sortBy('name');

        $header = [
            trans('cruds.dataProcessing.fields.name'),
            trans('cruds.dataProcessing.fields.legal_basis'),
            trans('cruds.dataProcessing.fields.description'),
            trans('cruds.dataProcessing.fields.responsible'),
            trans('cruds.dataProcessing.fields.purpose'),
            trans('cruds.dataProcessing.fields.categories'),
            trans('cruds.dataProcessing.fields.recipients'),
            trans('cruds.dataProcessing.fields.transfert'),
            trans('cruds.dataProcessing.fields.retention'),
            trans('cruds.dataProcessing.fields.processes'),
            trans('cruds.dataProcessing.fields.applications'),
            trans('cruds.dataProcessing.fields.information'),
            trans('cruds.dataProcessing.fields.security_controls'),
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getStyle('A:I')->getAlignment()->setWrapText(true);

        // column size
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(150, 'pt');
        $sheet->getColumnDimension('C')->setWidth(350, 'pt');
        $sheet->getColumnDimension('D')->setWidth(350, 'pt');
        $sheet->getColumnDimension('E')->setWidth(350, 'pt');
        $sheet->getColumnDimension('F')->setWidth(350, 'pt');
        $sheet->getColumnDimension('G')->setWidth(350, 'pt');
        $sheet->getColumnDimension('H')->setWidth(350, 'pt');
        $sheet->getColumnDimension('I')->setWidth(350, 'pt');
        $sheet->getColumnDimension('J')->setWidth(350, 'pt');
        $sheet->getColumnDimension('K')->setAutoSize(true);

        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate
        $row = 2;
        foreach ($register as $dataProcessing) {
            $sheet->setCellValue("A{$row}", $dataProcessing->name);
            $sheet->setCellValue("B{$row}", $html->toRichTextObject($dataProcessing->legal_basis));
            $sheet->setCellValue("C{$row}", $html->toRichTextObject($dataProcessing->description));

            $sheet->setCellValue("D{$row}", $html->toRichTextObject($dataProcessing->responsible));
            $sheet->setCellValue("E{$row}", $html->toRichTextObject($dataProcessing->purpose));
            $sheet->setCellValue("F{$row}", $html->toRichTextObject($dataProcessing->categories));
            $sheet->setCellValue("G{$row}", $html->toRichTextObject($dataProcessing->recipients));
            $sheet->setCellValue("H{$row}", $html->toRichTextObject($dataProcessing->transfert));
            $sheet->setCellValue("I{$row}", $html->toRichTextObject($dataProcessing->retention));

            // processes
            $txt = '';
            foreach ($dataProcessing->processes as $p) {
                $txt .= $p->name;
                if ($dataProcessing->processes->last() !== $p) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("J{$row}", $txt);

            // Applications
            $txt = '';
            foreach ($dataProcessing->applications as $application) {
                $txt .= $application->name;
                if ($dataProcessing->applications->last() !== $application) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("K{$row}", $txt);

            // Informations
            $txt = '';
            foreach ($dataProcessing->informations as $information) {
                $txt .= $information->name;
                if ($dataProcessing->informations->last() !== $information) {
                    $txt .= ', ';
                }
            }
            $sheet->setCellValue("L{$row}", $txt);

            // TODO : improve me using union
            // https://laravel.com/docs/10.x/queries#unions
            $allControls = Collect();
            foreach ($dataProcessing->processes as $process) {
                foreach ($process->securityControls as $sc) {
                    $allControls->push($sc->name);
                }
            }
            foreach ($dataProcessing->applications as $app) {
                foreach ($app->securityControls as $sc) {
                    $allControls->push($sc->name);
                }
            }

            $allControls->unique();
            $txt = implode(', ', $allControls->toArray());

            $sheet->setCellValue("M{$row}", $txt);

            $row++;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/register-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/register-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    public function applicationsByBlocks()
    {
        $applicationBlocks = ApplicationBlock::All()->sortBy('name');
        $applicationBlocks->load('applications');

        $header = [
            trans('cruds.application.fields.application_block'),
            trans('cruds.application.fields.name'),
            trans('cruds.application.fields.description'),
            'CPE',
            trans('cruds.application.fields.entity_resp'),
            trans('cruds.application.fields.entities'),
            trans('cruds.application.fields.responsible'),
            trans('cruds.application.fields.processes'),
            trans('cruds.application.fields.activities'),
            trans('cruds.application.fields.editor'),
            trans('cruds.application.fields.technology'),
            trans('cruds.application.fields.type'),
            trans('cruds.application.fields.users'),
            trans('cruds.application.fields.external'),
            trans('global.confidentiality_short'),
            trans('global.integrity_short'),
            trans('global.availability_short'),
            trans('global.tracability_short'),
        ];
        if (config('mercator-config.parameters.security_need_auth')) {
            array_push(
                $header,
                trans('global.authenticity_short')
            );
        }
        array_push(
            $header,
            trans('cruds.application.fields.RTO'),
            trans('cruds.application.fields.RPO'),
            trans('cruds.application.fields.documentation'),
            trans('cruds.application.fields.logical_servers'),
            trans('cruds.physicalServer.title'),
            trans('cruds.workstation.title'),
            trans('cruds.database.title'),
        );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        $i = 0;
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // block
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // name
        $sheet->getColumnDimension(self::col($i++))->setWidth(60, 'pt'); // description
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // CPE
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // entity_resp
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // entities
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // resp
        $sheet->getColumnDimension(self::col($i++))->setWidth(60, 'pt'); // process
        $sheet->getColumnDimension(self::col($i++))->setWidth(60, 'pt'); // activities
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // editor
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // tech
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // type
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // users
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);  // external
        // CIAT
        $sheet->getColumnDimension(self::col($i))->setWidth(10, 'pt');
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension(self::col($i))->setWidth(10, 'pt');
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension(self::col($i))->setWidth(10, 'pt');
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension(self::col($i))->setWidth(10, 'pt');
        $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->getColumnDimension(self::col($i))->setWidth(10, 'pt');
            $sheet->getStyle(self::col($i++))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        // RTO - RPO
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);

        $sheet->getColumnDimension(self::col($i++))->setAutoSize(true);
        $sheet->getColumnDimension(self::col($i++))->setWidth(200, 'pt');  // logical servers
        $sheet->getColumnDimension(self::col($i++))->setWidth(200, 'pt');  // physical serveurs
        $sheet->getColumnDimension(self::col($i++))->setWidth(200, 'pt');  // workstations
        $sheet->getColumnDimension(self::col($i++))->setWidth(200, 'pt');  // databases

        // bold title
        $sheet->getStyle('1')->getFont()->setBold(true);

        // converter
        $html = new \PhpOffice\PhpSpreadsheet\Helper\Html;

        // Populate the sheet
        $row = 2;
        foreach ($applicationBlocks as $applicationBlock) {
            foreach ($applicationBlock->applications as $application) {
                $i = 0;
                $sheet->setCellValue(self::col($i++).$row, $applicationBlock->name);
                $sheet->setCellValue(self::col($i++).$row, $application->name);
                $sheet->setCellValue(self::col($i++).$row, $html->toRichTextObject($application->description));
                $sheet->setCellValue(self::col($i++).$row, $application->vendor.':'.$application->product.':'.$application->version);
                $sheet->setCellValue(self::col($i++).$row, $application->entity_resp ? $application->entity_resp->name : '');
                $sheet->setCellValue(self::col($i++).$row, $application->entities->implode('name', ', '));
                $sheet->setCellValue(self::col($i++).$row, $application->responsible);
                $sheet->setCellValue(self::col($i++).$row, $application->processes->implode('name', ', '));
                $sheet->setCellValue(self::col($i++).$row, $application->activities->implode('name', ', '));
                $sheet->setCellValue(self::col($i++).$row, $application->editor);
                $sheet->setCellValue(self::col($i++).$row, $application->technology);
                $sheet->setCellValue(self::col($i++).$row, $application->type);
                $sheet->setCellValue(self::col($i++).$row, $application->users);
                $sheet->setCellValue(self::col($i++).$row, $application->external);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_c);
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_c);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_i);
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_i);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_a);
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_a);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_t);
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_t);

                if (config('mercator-config.parameters.security_need_auth')) {
                    $sheet->setCellValue(self::col($i).$row, $application->security_need_auth);
                    $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_auth);
                }

                $sheet->setCellValue(self::col($i++).$row, $application->rto);
                $sheet->setCellValue(self::col($i++).$row, $application->rpo);

                $sheet->setCellValue(self::col($i++).$row, $application->documentation);
                $sheet->setCellValue(self::col($i++).$row, $application->logicalServers->implode('name', ', '));
                $res = null;

                // Done: request improved
                $res = DB::Table('physical_servers')
                    ->distinct()
                    ->select('physical_servers.name')
                    ->join(
                        'logical_server_physical_server',
                        'physical_servers.id',
                        '=',
                        'logical_server_physical_server.physical_server_id'
                    )
                    ->join(
                        'logical_servers',
                        'logical_servers.id',
                        '=',
                        'logical_server_physical_server.logical_server_id'
                    )
                    ->join(
                        'logical_server_m_application',
                        'logical_server_m_application.logical_server_id',
                        '=',
                        'logical_servers.id'
                    )

                    ->leftJoin(
                        'm_application_physical_server',
                        'm_application_physical_server.physical_server_id',
                        '=',
                        'physical_servers.id',
                    )
                    ->whereNull('logical_servers.deleted_at')
                    ->whereNull('physical_servers.deleted_at')
                    ->where('logical_server_m_application.m_application_id', '=', $application->id)
                    ->orWhere('m_application_physical_server.m_application_id', '=', $application->id)
                    ->orderBy('physical_servers.name')
                    ->get()
                    ->implode('name', ', ');

                $sheet->setCellValue(self::col($i++).$row, $res);
                $sheet->setCellValue(self::col($i++).$row, $application->workstations->implode('name', ', '));
                $sheet->setCellValue(self::col($i++).$row, $application->databases->implode('name', ', '));

                $row++;
            }
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // $path = storage_path('app/applications-'. Carbon::today()->format('Ymd') .'.ods');
        $path = storage_path('app/applications-'.Carbon::today()->format('Ymd').'.xlsx');

        $writer->save($path);

        return response()->download($path);
    }

    // *************************************************************

    public function directory()
    {
        $today = Carbon::today()->toDateString();

        $applications =
            MApplication::where(function ($query) {
                $query->where('m_applications.security_need_c', '>=', 3)
                    ->orWhere('m_applications.security_need_i', '>=', 3)
                    ->orWhere('m_applications.security_need_a', '>=', 3)
                    ->orWhere('m_applications.security_need_t', '>=', 3)
                    ->orWhere('m_applications.security_need_auth', '>=', 3);
            })
                ->whereNull('m_applications.deleted_at')
                ->leftJoin('entities', function ($join) {
                    $join->on(function ($on) {
                        $on->on('m_applications.entity_resp_id', '=', 'entities.id');
                    })
                        ->whereNull('entities.deleted_at');
                })
                ->whereNull('entities.deleted_at')
                ->leftJoin('relations', function ($join) use ($today) {
                    /*
               $join->on(function ($on) {
                        $on->on('entities.id', '=', 'relations.source_id')
                           ->orOn('entities.id', '=', 'relations.destination_id');
                    })
                */
                    $join->on('entities.id', '=', 'relations.source_id')
                        ->where('relations.active', '=', 1)
                        ->where('relations.start_date', '<=', $today)
                        ->where('relations.end_date', '>=', $today)
                        ->whereNull('relations.deleted_at');
                })
                ->select(
                    'm_applications.id as application_id',
                    'm_applications.name',
                    'm_applications.description',
                    'm_applications.responsible',
                    'm_applications.security_need_c',
                    'm_applications.security_need_i',
                    'm_applications.security_need_a',
                    'm_applications.security_need_t',
                    'm_applications.rto',
                    'm_applications.rpo',

                    'entities.name as entity_name',
                    'entities.description as entity_description',
                    'entities.contact_point as entity_contact_point',

                    'relations.name as relation_name',
                    'relations.type as relation_type',
                    'relations.description as relation_description',
                    'relations.importance as relation_importance',
                    'relations.start_date as relation_start_date',
                    'relations.end_date as relation_end_date'

                )
                ->get();

        // --- Styles de base
        $phpWord = new PhpWord;
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true); // !!!!
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);

        $phpWord->addTitleStyle(1, ['size' => 20, 'bold' => true]);
        $phpWord->addTitleStyle(2, ['size' => 16, 'bold' => true]);
        $labelStyle = ['bold' => true];
        $textStyle = [];

        $tableKVStyle = [
            'borderColor' => 'cccccc',
            'borderSize' => 6,
            'cellMargin' => 80,
            'width' => 100 * 50, // 100% (fiftieths of a percent)
        ];
        $tableKVFirstCol = ['bgColor' => 'f3f3f3', 'valign' => 'center', 'width' => 40 * 50];
        $tableKVSecondCol = ['valign' => 'center', 'width' => 60 * 50];

        $tableListStyle = [
            'borderColor' => 'cccccc',
            'borderSize' => 6,
            'cellMargin' => 80,
            'width' => 100 * 50,
        ];
        $tableHeaderCell = ['bgColor' => 'eaeaea'];
        $paraTight = ['spaceAfter' => 60];

        // Numbering Style
        $phpWord->addNumberingStyle(
            'hNum',
            ['type' => 'multilevel', 'levels' => [
                ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1.'],
                ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2.'],
                ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3.'],
            ],
            ]
        );
        $phpWord->addTitleStyle(
            0,
            ['size' => 28, 'bold' => true],
            ['align' => 'center']
        );
        $phpWord->addTitleStyle(
            1,
            ['size' => 16, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 0]
        );
        $phpWord->addTitleStyle(
            2,
            ['size' => 14, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 1]
        );
        $phpWord->addTitleStyle(
            3,
            ['size' => 12, 'bold' => true],
            ['numStyle' => 'hNum', 'numLevel' => 2]
        );

        // Title
        $section = $phpWord->addSection();
        $section->addTitle(trans('cruds.report.lists.directory'), 0);
        $section->addTextBreak(1);

        // TOC
        $toc = $section->addTOC(['spaceAfter' => 50, 'size' => 10]);
        $toc->setMinDepth(0);
        $toc->setMaxDepth(1);
        $section->addTextBreak(1);

        // page break
        $section->addPageBreak();

        // Add footer
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}', ['size' => 8], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // --- Regrouper: une page par application, relations listées
        $grouped = $applications->groupBy('name');

        foreach ($grouped as $appName => $rows) {            // Info d’application = première ligne du groupe
            $first = $rows->first();

            $section = $phpWord->addSection([
                'breakType' => 'continuous',
                'pageSizeW' => 11906,
                'pageSizeH' => 16838,
                'marginTop' => 1000,
                'marginBottom' => 1000,
                'marginLeft' => 1000,
                'marginRight' => 1000,
            ]);

            // Titre page
            $section->addTitle($appName, 1);

            // =========================
            // Section 1: Application
            // =========================
            $section->addTitle('Application', 2);

            $t1 = $section->addTable($tableKVStyle);
            $this->addHTMLRow($t1, trans('cruds.application.fields.description'), $first->description);
            $this->addTextRow($t1, 'Processus', $this->getApplicationProcessesNames($first->application_id));
            $this->addTextRow($t1, 'Responsable', $first->responsible);
            $this->addTextRow($t1, 'C-I-A-T', (
                $first->security_need_c.' - '.
                $first->security_need_i.' - '.
                $first->security_need_a.' - '.
                $first->security_need_t));
            $this->addTextRow($t1, 'RTO', (MApplication::formatDelay($first->rto)));
            $this->addTextRow($t1, 'RPO', (MApplication::formatDelay($first->rpo)));

            $section->addTextBreak(1);

            // =========================
            // Section 2: Entité responsable
            // =========================
            $section->addTitle('Entité responsable', 2);
            $t2 = $section->addTable($tableKVStyle);
            $this->addTextRow($t2, 'Nom', ($first->entity_name));
            $this->addHTMLRow($t2, 'Point de contact', ($first->entity_contact_point));
            $this->addHTMLRow($t2, 'Description', ($first->entity_description));

            $section->addTextBreak(1);

            // =========================
            // Section 3: Relations actives à la date du jour
            // =========================
            $section->addTitle('Relations', 2);
            $t3 = $section->addTable($tableListStyle);
            // En-têtes
            $t3h = $t3->addRow();
            foreach (['Nom', 'Type', 'Importance', 'Début', 'Fin'] as $head) {
                $cell = $t3h->addCell(null, $tableHeaderCell);
                $cell->addText($head, ['bold' => true], $paraTight);
            }

            $hasRelation = false;
            foreach ($rows as $row) {
                // si aucune relation liée, les colonnes seront null → affichera "—"
                if ($row->relation_name || $row->relation_type || $row->relation_importance || $row->relation_start_date || $row->relation_end_date) {
                    $hasRelation = true;
                }
                $r = $t3->addRow();
                $r->addCell()->addText(str_replace('&', ' ', $row->relation_name), [], $paraTight);
                $r->addCell()->addText(str_replace('&', ' ', $row->relation_type), [], $paraTight);
                $r->addCell()->addText(($row->relation_importance), [], $paraTight);
                $r->addCell()->addText((optional($row->relation_start_date)->format('Y-m-d') ?? $row->relation_start_date), [], $paraTight);
                $r->addCell()->addText((optional($row->relation_end_date)->format('Y-m-d') ?? $row->relation_end_date), [], $paraTight);
                $this->addHTMLRow($t3, 'Description', ($first->relation_description));
            }

            if (! $hasRelation) {
                $r = $t3->addRow();
                $c = $r->addCell(null, ['gridSpan' => 5]);
                $c->addText('Aucune relation active pour cette application à la date du jour.', [], $paraTight);
            }

            // Nouvelle page pour l’application suivante
            $section->addPageBreak();
        }

        // --- Sauvegarde du fichier
        // Finename
        $filepath = storage_path('app/reports/directory-'.Carbon::today()->format('Ymd').'.docx');

        // Saving the document as Word2007 file.
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filepath);

        // return
        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    private static function getApplicationProcessesNames(int $applicationId): string
    {
        $names = DB::table('m_application_process')
            ->join('processes', 'm_application_process.process_id', '=', 'processes.id')
            ->where('m_application_process.m_application_id', $applicationId)
            ->pluck('processes.name');

        return $names->implode(', ');
    }

    private static function addTable(Section $section, ?string $title = null)
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

    private static function addTextRow(Table $table, string $title, ?string $value = null)
    {
        $table->addRow();
        $table->addCell(2000, CartographyController::NOSPACE)->addText($title, CartographyController::FANCYLEFTTABLECELLSTYLE, CartographyController::NOSPACE);
        $table->addCell(6000, CartographyController::NOSPACE)->addText($value, CartographyController::FANCYRIGHTTABLECELLSTYLE, CartographyController::NOSPACE);
    }

    private static function addHTMLRow(Table $table, string $title, ?string $value = null)
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
        $subnetworks = Subnetwork::All()->sortBy('zone, address');

        return view('admin/reports/zones', compact('subnetworks'));
    }

    public function impacts()
    {
        // 1. Récupérer toutes les activités et impacts
        $activities = Activity::with('impacts')->get();

        // 2. Extraire tous les types d'impact existants
        $impactTypes = ActivityImpact::select('impact_type')->distinct()->pluck('impact_type')->sort()->values()->toArray();

        // Style de l'en-tête
        $boldFont = ['font' => ['bold' => true]];
        $centered = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]];

        // 3. Créer une nouvelle feuille Excel
        $spreadsheet = new Spreadsheet;
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

    public function rto()
    {
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

    private static function addText(Section $section, ?string $value = null)
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

    private function addSecurityNeedColor(Worksheet $sheet, string $cell, ?int $i)
    {
        static $colors = [-1 => 'FFFFFF', 0 => 'FFFFFF', 1 => '8CD17D', 2 => 'F1CE63', 3 => 'F28E2B', 4 => 'E15759'];
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
        $i = 0;
        $sheet->setCellValue(self::col($i++).$row, $macroprocess->name);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_c >= 0 ? $macroprocess->security_need_c : '');
        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_c);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_i >= 0 ? $macroprocess->security_need_i : '');
        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_i);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_a >= 0 ? $macroprocess->security_need_a : '');
        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_a);

        $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_t >= 0 ? $macroprocess->security_need_t : '');
        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_t);

        if (config('mercator-config.parameters.security_need_auth')) {
            $sheet->setCellValue(self::col($i).$row, $macroprocess->security_need_auth >= 0 ? $macroprocess->security_need_auth : '');
            $this->addSecurityNeedColor($sheet, self::col($i++).$row, $macroprocess->security_need_auth);
        }

        if ($process !== null) {
            // Processus
            $sheet->setCellValue(self::col($i++).$row, $process->name);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_c >= 0 ? $process->security_need_c : '');
            $this->addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_c);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_i >= 0 ? $process->security_need_i : '');
            $this->addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_i);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_a >= 0 ? $process->security_need_a : '');
            $this->addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_a);

            $sheet->setCellValue(self::col($i).$row, $process->security_need_t >= 0 ? $process->security_need_t : '');
            $this->addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_t);

            if (config('mercator-config.parameters.security_need_auth')) {
                $sheet->setCellValue(self::col($i).$row, $process->security_need_auth >= 0 ? $process->security_need_auth : '');
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $process->security_need_auth);
            }

            if ($application !== null) {
                // Application
                $sheet->setCellValue(self::col($i++).$row, $application->name);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_c >= 0 ? $application->security_need_c : '');
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_c);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_i >= 0 ? $application->security_need_i : '');
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_i);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_a >= 0 ? $application->security_need_a : '');
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_a);

                $sheet->setCellValue(self::col($i).$row, $application->security_need_t >= 0 ? $application->security_need_t : '');
                $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_t);

                if (config('mercator-config.parameters.security_need_auth')) {
                    $sheet->setCellValue(self::col($i).$row, $application->security_need_auth >= 0 ? $application->security_need_auth : '');
                    $this->addSecurityNeedColor($sheet, self::col($i++).$row, $application->security_need_auth);
                }

                if ($database !== null) {
                    // Database
                    $sheet->setCellValue(self::col($i++).$row, $database->name);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_c >= 0 ? $database->security_need_c : '');
                    $this->addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_c);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_i >= 0 ? $database->security_need_i : '');
                    $this->addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_i);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_a >= 0 ? $database->security_need_a : '');
                    $this->addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_a);

                    $sheet->setCellValue(self::col($i).$row, $database->security_need_t >= 0 ? $database->security_need_t : '');
                    $this->addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_t);

                    if (config('mercator-config.parameters.security_need_auth')) {
                        $sheet->setCellValue(self::col($i).$row, $database->security_need_auth >= 0 ? $database->security_need_auth : '');
                        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $database->security_need_auth);
                    }

                    if ($information !== null) {
                        // Information
                        $sheet->setCellValue(self::col($i++).$row, $information->name);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_c >= 0 ? $information->security_need_c : '');
                        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_c);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_i >= 0 ? $information->security_need_i : '');
                        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_i);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_a >= 0 ? $information->security_need_a : '');
                        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_a);

                        $sheet->setCellValue(self::col($i).$row, $information->security_need_t >= 0 ? $information->security_need_t : '');
                        $this->addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_t);

                        if (config('mercator-config.parameters.security_need_auth')) {
                            $sheet->setCellValue(self::col($i).$row, $information->security_need_auth >= 0 ? $information->security_need_auth : '');
                            $this->addSecurityNeedColor($sheet, self::col($i++).$row, $information->security_need_auth);
                        }
                    }
                }
            }
        }
    }

    // Return the Excel column index from 0 to 52
    private static function col(int $i)
    {
        if ($i < 26) {
            return chr(ord('A') + $i);
        }

        return 'A'.chr(ord('A') + $i - 26);
    }
}
