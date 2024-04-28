<?php

namespace App\Http\Controllers\Admin;

// GDPR
use App\Activity;
use App\Actor;
// ecosystem
use App\Annuaire;
use App\ApplicationBlock;
// information system
use App\ApplicationModule;
use App\ApplicationService;
use App\Bay;
use App\Building;
use App\Certificate;
use App\Cluster;
// Applications
use App\Database;
use App\DataProcessing;
use App\DhcpServer;
use App\Dnsserver;
use App\DomaineAd;
use App\Entity;
// Administration
use App\ExternalConnectedEntity;
use App\Flux;
use App\ForestAd;
// Logique
use App\Gateway;
use App\Http\Controllers\Controller;
use App\Information;
use App\Lan;
use App\LogicalServer;
use App\MacroProcessus;
use App\Man;
use App\MApplication;
use App\Network;
use App\NetworkSwitch;
use App\Operation;
// Physique
use App\Peripheral;
use App\Phone;
use App\PhysicalRouter;
use App\PhysicalSecurityDevice;
use App\PhysicalServer;
use App\PhysicalSwitch;
use App\Process;
use App\Relation;
use App\Router;
use App\SecurityControl;
use App\SecurityDevice;
use App\Site;
use App\StorageDevice;
use App\Subnetwork;
use App\Task;
use App\Vlan;
use App\Wan;
use App\WifiTerminal;
use App\Workstation;
use App\ZoneAdmin;

class HomeController extends Controller
{
    /**
     * Show maturity level 1.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function maturity1()
    {
        $view = view('admin/reports/maturity1');
        foreach ($this->computeMaturity() as $maturity => $level) {
            $view = $view->with($maturity, $level);
        }
        return $view;
    }

    /**
     * Show maturity level 2.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function maturity2()
    {
        $view = view('admin/reports/maturity2');
        foreach ($this->computeMaturity() as $maturity => $level) {
            $view = $view->with($maturity, $level);
        }
        return $view;
    }

    /**
     * Show maturity level 3.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function maturity3()
    {
        $view = view('admin/reports/maturity3');
        foreach ($this->computeMaturity() as $maturity => $level) {
            $view = $view->with($maturity, $level);
        }
        return $view;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $view = view('home');
        foreach ($this->computeMaturity() as $maturity => $level) {
            $view = $view->with($maturity, $level);
        }
        return $view;
    }

    /**
     * Compute maturity levels
     *
     * @return array
     */
    protected function computeMaturity()
    {
        $levels = [
            // GDPR
            'data_processing' => DataProcessing::count(),
            'security_controls' => SecurityControl::count(),

            // ecosystem
            'entities' => Entity::count(),
            'relations' => Relation::count(),

            'entities_lvl1' => Entity
                ::where('description', '<>', null)
                    ->where('security_level', '<>', null)
                    ->where('contact_point', '<>', 'null')
                // entity must support at least one process
                    ->whereExists(function ($query) {
                        $query->select('entity_process.entity_id')
                            ->from('entity_process')
                            ->whereRaw('entity_process.entity_id = entities.id');
                    })
                    ->count(),

            'relations_lvl1' => Relation
                ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->count(),

            'relations_lvl2' => Relation
                ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('importance', '>', 0)
                    ->count(),

            // information system
            'macroProcessuses' => MacroProcessus::count(),
            'macroProcessuses_lvl2' => MacroProcessus
                ::where('description', '<>', null)
                    ->where('io_elements', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                    ->count(),

            'macroProcessuses_lvl3' => MacroProcessus
                ::where('description', '<>', null)
                    ->where('io_elements', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                    ->where('owner', '<>', null)
                    ->count(),

            'processes' => Process::count(),
            'processes_lvl1' => Process
                    ::where('description', '<>', null)
                        ->where('in_out', '<>', null)
                        ->where('owner', '<>', null)
                /*
                // process must have one activity
                ->whereExists(function ($query) {
                    $query->select("activity_process.process_id")
                        ->from("activity_process")
                        ->whereRaw("activity_process.process_id = processes.id");
                })
                // process must have one entity
                ->whereExists(function ($query) {
                    $query->select("entity_process.process_id")
                        ->from("entity_process")
                        ->whereRaw("entity_process.process_id = processes.id");
                })
                // process must be supported by one application
                ->whereExists(function ($query) {
                    $query->select("m_application_process.process_id")
                        ->from("m_application_process")
                        ->whereRaw("m_application_process.process_id = processes.id");
                })
                */
                        ->count(),

            'processes_lvl2' => Process
                ::where('name', '<>', null)
                    ->where('description', '<>', null)
                    ->where('in_out', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('macroprocess_id', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                    ->count(),

            'activities' => Activity::count(),
            'activities_lvl2' => Activity
                ::where('description', '<>', null)
                    // activity must have one process
                    /*
                    ->whereExists(function ($query) {
                        $query->select('activity_operation.activity_id')
                            ->from('activity_operation')
                            ->whereRaw('activity_operation.activity_id = activities.id');
                    })
                    */
                    ->count(),

            'operations' => Operation::count(),
            'operations_lvl1' => Operation
                ::where('description', '<>', null)
                    ->count(),

            'operations_lvl2' => Operation
                ::where('description', '<>', null)
                // must have at least one actor
                    ->whereExists(function ($query) {
                        $query->select('actor_operation.operation_id')
                            ->from('actor_operation')
                            ->whereRaw('actor_operation.operation_id = operations.id');
                    })
                    ->count(),

            'operations_lvl3' => Operation
                ::where('description', '<>', null)
                // must have at least one actor
                    ->whereExists(function ($query) {
                        $query->select('actor_operation.operation_id')
                            ->from('actor_operation')
                            ->whereRaw('actor_operation.operation_id = operations.id');
                    })
                // must have at least one task
                    ->whereExists(function ($query) {
                        $query->select('operation_task.operation_id')
                            ->from('operation_task')
                            ->whereRaw('operation_task.operation_id = operations.id');
                    })
                    ->count(),

            'tasks' => Task::count(),
            'tasks_lvl3' => Task
                ::where('description', '<>', null)
                // task must have one operation
                    ->whereExists(function ($query) {
                        $query->select('operation_task.task_id')
                            ->from('operation_task')
                            ->whereRaw('operation_task.task_id = tasks.id');
                    })
                    ->count(),

            'actors' => Actor::count(),
            'actors_lvl2' => Actor
                ::where('contact', '<>', null)
                    ->where('nature', '<>', null)
                    ->where('type', '<>', null)
                    ->count(),

            'informations' => Information::count(),
            'informations_lvl1' => Information
                ::where('description', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('administrator', '<>', null)
                    ->where('storage', '<>', null)
                    ->count(),

            'informations_lvl2' => Information
                ::where('description', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('administrator', '<>', null)
                    ->where('storage', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                    ->where('sensitivity', '<>', null)
                    ->count(),

            // Application vue
            'applicationBlocks' => ApplicationBlock::count(),
            'applicationBlocks_lvl2' => ApplicationBlock
                ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                // applicationBlock must have one application
                    ->whereExists(function ($query) {
                        $query->select('m_applications.id')
                            ->from('m_applications')
                            ->whereRaw('m_applications.application_block_id = application_blocks.id');
                    })
                    ->count(),

            'applications' => MApplication::count(),
            'applications_lvl1' => MApplication
                ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('technology', '<>', null)
                    ->where('type', '<>', null)
                    ->where('users', '<>', null)
                // application must have one process
                    ->whereExists(function ($query) {
                        $query->select('m_application_process.m_application_id')
                            ->from('m_application_process')
                            ->whereRaw('m_application_process.m_application_id = m_applications.id');
                    })
                // application must have one logical server
                /* No - fat client application does not have a logical server
                ->whereExists(function ($query) {
                    $query->select("logical_server_m_application.m_application_id")
                        ->from("logical_server_m_application")
                        ->whereRaw("logical_server_m_application.m_application_id = m_applications.id");
                })
                */
                    ->count(),

            'applications_lvl2' => MApplication
                ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('technology', '<>', null)
                    ->where('type', '<>', null)
                    ->where('users', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                // application must have one process
                    ->whereExists(function ($query) {
                        $query->select('m_application_process.m_application_id')
                            ->from('m_application_process')
                            ->whereRaw('m_application_process.m_application_id = m_applications.id');
                    })
                // application must have one logical server
                /* No - fat client application does not have a logical server
                ->whereExists(function ($query) {
                    $query->select("logical_server_m_application.m_application_id")
                        ->from("logical_server_m_application")
                        ->whereRaw("logical_server_m_application.m_application_id = m_applications.id");
                })
                */

                // application must have one application service
                // NO - services of external applications are not documented
                /*
                ->whereExists(function ($query) {
                   $query->select("application_service_m_application.m_application_id")
                        ->from("application_service_m_application")
                        ->whereRaw("application_service_m_application.m_application_id = m_applications.id");
                })
                */
                    ->count(),

            'applications_lvl3' => MApplication
                ::where('description', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('technology', '<>', null)
                    ->where('type', '<>', null)
                    ->where('users', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                // application must have one process
                    ->whereExists(function ($query) {
                        $query->select('m_application_process.m_application_id')
                            ->from('m_application_process')
                            ->whereRaw('m_application_process.m_application_id = m_applications.id');
                    })
                // CPE must be given
                //    ->where('vendor', '<>', null)
                //    ->where('product', '<>', null)
                //    ->where('version', '<>', null)
                    ->count(),

            'applicationServices' => ApplicationService::count(),
            'applicationServices_lvl2' => ApplicationService
                ::where('description', '<>', null)
                // applicationService must have one application
                    ->whereExists(function ($query) {
                        $query->select('application_service_m_application.m_application_id')
                            ->from('application_service_m_application')
                            ->whereRaw('application_service_m_application.application_service_id = application_services.id');
                    })
                    ->count(),

            'applicationModules' => ApplicationModule::count(),
            'applicationModules_lvl2' => ApplicationModule
                ::where('description', '<>', null)
                    ->count(),

            'databases' => Database::count(),
            'databases_lvl1' => Database
                ::where('description', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('type', '<>', null)
                    ->count(),

            'databases_lvl2' => Database
                ::where('description', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('type', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                // ->where('external', '<>', null) //lvl2
                    ->count(),

            'fluxes' => Flux::count(),
            'fluxes_lvl1' => Flux
                ::where('description', '<>', null)
                    ->orWhere(function ($query) {
                        $query->where('application_source_id', '<>', null)
                            ->where('module_source_id', '<>', null)
                            ->where('database_source_id', '<>', null);
                    })
                    ->orWhere(function ($query) {
                        $query->where('application_dest_id', '<>', null)
                            ->where('module_dest_id', '<>', null)
                            ->where('database_dest_id', '<>', null);
                    })
                    ->where('crypted', '<>', null)
                    ->count(),

            // Administration
            'zones' => ZoneAdmin::count(),
            'zones_lvl1' => ZoneAdmin
                ::where('description', '<>', null)
                    ->count(),

            'annuaires' => Annuaire::count(),
            'annuaires_lvl1' => Annuaire
                ::where('description', '<>', null)
                    ->where('solution', '<>', null)
                    ->where('zone_admin_id', '<>', null)
                    ->count(),

            'forests' => ForestAd::count(),
            'forests_lvl1' => ForestAd
                ::where('description', '<>', null)
                    ->where('zone_admin_id', '<>', null)
                    ->count(),

            'domaines' => DomaineAd::count(),
            'domaines_lvl1' => DomaineAd
                ::where('description', '<>', null)
                    ->where('domain_ctrl_cnt', '<>', null)
                    ->where('user_count', '<>', null)
                    ->where('machine_count', '<>', null)
                    ->where('relation_inter_domaine', '<>', null)
                    ->count(),

            // Logique
            'networks' => Network::count(),
            'networks_lvl1' => Network
                ::where('description', '<>', null)
                    ->where('protocol_type', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('responsible_sec', '<>', null)
                    ->where('security_need_c', '<>', null)
                    ->where('security_need_i', '<>', null)
                    ->where('security_need_a', '<>', null)
                    ->where('security_need_t', '<>', null)
                    ->count(),

            'subnetworks' => Subnetwork::count(),
            'subnetworks_lvl1' => Subnetwork
                ::where('description', '<>', null)
                    ->where('address', '<>', null)
                    ->where('default_gateway', '<>', null)
                    ->where('ip_allocation_type', '<>', null)
                    ->where('responsible_exp', '<>', null)
                    ->where('dmz', '<>', null)
                    ->where('wifi', '<>', null)
                    ->where('vlan_id', '<>', null)
                    ->count(),

            'gateways' => Gateway::count(),
            'gateways_lvl1' => Gateway
                ::where('description', '<>', null)
                    ->where('authentification', '<>', null)
                    ->where('ip', '<>', null)
                    ->count(),

            'externalConnectedEntities' => ExternalConnectedEntity::count(),
            'externalConnectedEntities_lvl2' => ExternalConnectedEntity
                ::where('type', '<>', null)
                    ->where('contacts', '<>', null)
                    ->count(),

            'switches' => NetworkSwitch::count(),
            'switches_lvl1' => NetworkSwitch
                ::where('description', '<>', null)
                // IP not mandatory on a switch
                // ->where('ip', '<>', null)
                    ->count(),

            'routers' => Router::count(),
            'routers_lvl1' => Router
                ::where('description', '<>', null)
                    ->count(),

            'securityDevices' => SecurityDevice::count(),
            'securityDevices_lvl1' => SecurityDevice
                ::where('description', '<>', null)
                    ->count(),

            'DHCPServers' => DhcpServer::count(),
            'DHCPServers_lvl2' => DhcpServer
                ::where('description', '<>', null)
                    ->count(),

            'DNSServers' => Dnsserver::count(),
            'DNSServers_lvl2' => Dnsserver
                ::where('description', '<>', null)
                    ->count(),

            'clusters' => Cluster::count(),
            'clusters_lvl1' => Cluster
                ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->count(),

            'logicalServers' => LogicalServer::count(),
            'logicalServers_lvl1' => LogicalServer
                ::where('description', '<>', null)
                    ->where('operating_system', '<>', null)
                    ->where('environment', '<>', null)
                    ->where('address_ip', '<>', null)
                // logicalServer must have one application
                    ->whereExists(function ($query) {
                        $query->select('logical_server_m_application.logical_server_id')
                            ->from('logical_server_m_application')
                            ->whereRaw('logical_server_m_application.logical_server_id = logical_servers.id');
                    })
                // logicalServer must be installed on a pysical server
                    ->whereExists(function ($query) {
                        $query->select('logical_server_physical_server.logical_server_id')
                            ->from('logical_server_physical_server')
                            ->whereRaw(
                                'logical_server_physical_server.logical_server_id = logical_servers.id'
                            )
                            ->orWhereRaw(
                                'logical_servers.cluster_id is not null'
                            );
                    })
                    ->count(),

            'certificates' => Certificate::count(),
            'certificates_lvl2' => Certificate
                ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('start_validity', '<>', null)
                    ->where('end_validity', '<>', null)
                // certificate must be on a logical server
                    ->whereExists(function ($query) {
                        $query->select('certificate_logical_server.logical_server_id')
                            ->from('certificate_logical_server')
                            ->whereRaw(
                                'certificate_logical_server.certificate_id = certificates.id'
                            );
                    })
                    ->count(),

            // Physical
            'sites' => Site::count(),
            'sites_lvl1' => Site
                ::where('description', '<>', null)
                    ->count(),

            'buildings' => Building::count(),
            'buildings_lvl1' => Building
                ::where('description', '<>', null)
                    ->count(),

            'bays' => Bay::count(),
            'bays_lvl1' => Bay
                ::where('description', '<>', null)
                    ->count(),

            'physicalServers' => PhysicalServer::count(),
            'physicalServers_lvl1' => PhysicalServer
                ::where('description', '<>', null)
                    ->where('configuration', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // a server is not necessary in a bay
                // ->where('bay_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->count(),

            'workstations' => Workstation::count(),
            'workstations_lvl1' => Workstation
                ::where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    ->count(),

            'storageDevices' => StorageDevice::count(),
            'storageDevices_lvl1' => StorageDevice
                ::where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // not always in a bay
                // ->where('bay_id', '<>', null)
                    ->count(),

            'peripherals' => Peripheral::count(),
            'peripherals_lvl1' => Peripheral
                ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // not always in a bay
                // ->where('bay_id', '<>', null)
                    ->count(),

            'phones' => Phone::count(),
            'phones_lvl1' => Phone
                ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // ->where('bay_id', '<>', null)
                    ->count(),

            'physicalSwitchs' => PhysicalSwitch::count(),
            'physicalSwitchs_lvl1' => PhysicalSwitch
                ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // not always in a bay
                // ->where('bay_id', '<>', null)
                    ->count(),

            'physicalRouters' => PhysicalRouter::count(),
            'physicalRouters_lvl1' => PhysicalRouter
                ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // not always in a bay
                // ->where('bay_id', '<>', null)
                    ->count(),

            'wifiTerminals' => WifiTerminal::count(),
            'wifiTerminals_lvl1' => WifiTerminal
                ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    ->count(),

            'physicalSecurityDevices' => PhysicalSecurityDevice::count(),
            'physicalSecurityDevices_lvl1' => PhysicalSecurityDevice
                ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                // not always in a bay
                // ->where('bay_id', '<>', null)
                    ->count(),

            // Too many links...
            // 'links' => PhysicalLink::count(),

            'wans' => ($wan_count = Wan::count()),
            'wans_lvl1' => $wan_count,

            'mans' => ($man_count = Man::count()),
            'mans_lvl1' => $man_count,

            'lans' => Lan::count(),
            'lans_lvl1' => Lan
                ::where('description', '<>', null)
                    ->count(),

            'vlans' => Vlan::count(),
            'vlans_lvl1' => Vlan
                ::where('description', '<>', null)
                    ->count(),
        ]
        ;

        // Maturity Level 1
        $denominator =
            $levels['entities'] + $levels['relations'] +
            $levels['processes'] + $levels['operations'] + $levels['informations'] +
            $levels['applications'] + $levels['databases'] + $levels['fluxes'] +
            $levels['zones'] + $levels['annuaires'] + $levels['forests'] + $levels['domaines'] +
            $levels['networks'] + $levels['subnetworks'] + $levels['gateways'] + $levels['switches'] + $levels['routers'] + $levels['securityDevices'] + $levels['clusters'] + $levels['logicalServers'] +
            $levels['sites'] + $levels['buildings'] + $levels['bays'] + $levels['physicalServers'] + $levels['physicalRouters'] + $levels['physicalSwitchs'] + $levels['physicalSecurityDevices'] +
            $levels['wans'] + $levels['mans'] + $levels['lans'] + $levels['vlans'];

        $levels['maturity1'] =
            $denominator > 0 ?
            number_format(
                ($levels['entities_lvl1'] + $levels['relations_lvl1'] +
            $levels['processes_lvl1'] + $levels['operations_lvl1'] + $levels['informations_lvl1'] +
            $levels['applications_lvl1'] + $levels['databases_lvl1'] + $levels['fluxes_lvl1'] +
            $levels['zones_lvl1'] + $levels['annuaires_lvl1'] + $levels['forests_lvl1'] + $levels['domaines_lvl1'] +
            $levels['networks_lvl1'] + $levels['subnetworks_lvl1'] + $levels['gateways_lvl1'] + $levels['switches_lvl1'] + $levels['routers_lvl1'] + $levels['securityDevices_lvl1'] + $levels['clusters_lvl1'] + $levels['logicalServers_lvl1'] +
            $levels['sites_lvl1'] + $levels['buildings_lvl1'] + $levels['bays_lvl1'] + $levels['physicalServers_lvl1'] + $levels['physicalRouters_lvl1'] + $levels['physicalSwitchs_lvl1'] + $levels['physicalSecurityDevices_lvl1'] +
            $levels['wans_lvl1'] + $levels['mans_lvl1'] + $levels['lans_lvl1'] + $levels['vlans_lvl1']) * 100 / $denominator,
                0
            ) : 0;

        // Maturity Level 2
        $denominator =
            $levels['entities'] + $levels['relations'] +
            $levels['macroProcessuses'] + $levels['processes'] + $levels['activities'] + $levels['operations'] + $levels['actors'] + $levels['informations'] +
            $levels['applicationBlocks'] + $levels['applications'] + $levels['applicationServices'] + $levels['applicationModules'] + $levels['databases'] + $levels['fluxes'] +
            $levels['zones'] + $levels['annuaires'] + $levels['forests'] + $levels['domaines'] +
            $levels['networks'] + $levels['subnetworks'] + $levels['gateways'] + $levels['externalConnectedEntities'] + $levels['switches'] + $levels['routers'] + $levels['securityDevices'] + $levels['DHCPServers'] + $levels['DNSServers'] + $levels['clusters'] + $levels['logicalServers'] + $levels['certificates'] +
            $levels['sites'] + $levels['buildings'] + $levels['bays'] + $levels['physicalServers'] + $levels['physicalRouters'] + $levels['physicalSwitchs'] + $levels['physicalSecurityDevices'] +
            $levels['wans'] + $levels['mans'] + $levels['lans'] + $levels['vlans'];

        $levels['maturity2'] =
            $denominator > 0 ?
            number_format(
                ($levels['entities_lvl1'] + $levels['relations_lvl2'] +
            $levels['macroProcessuses_lvl2'] + $levels['processes_lvl2'] + $levels['activities_lvl2'] + $levels['operations_lvl2'] + $levels['actors_lvl2'] + $levels['informations_lvl2'] +
            $levels['applicationBlocks_lvl2'] + $levels['applications_lvl2'] + $levels['applicationServices_lvl2'] + $levels['applicationModules_lvl2'] + $levels['databases_lvl2'] + $levels['fluxes_lvl1'] +
            $levels['zones_lvl1'] + $levels['annuaires_lvl1'] + $levels['forests_lvl1'] + $levels['domaines_lvl1'] +
            $levels['networks_lvl1'] + $levels['subnetworks_lvl1'] + $levels['gateways_lvl1'] + $levels['externalConnectedEntities_lvl2'] + $levels['switches_lvl1'] + $levels['routers_lvl1'] + $levels['securityDevices_lvl1'] + $levels['DHCPServers_lvl2'] + $levels['DNSServers_lvl2'] + $levels['clusters_lvl1'] + $levels['logicalServers_lvl1'] + $levels['certificates_lvl2'] +
            $levels['sites_lvl1'] + $levels['buildings_lvl1'] + $levels['bays_lvl1'] + $levels['physicalServers_lvl1'] + $levels['physicalRouters_lvl1'] + $levels['physicalSwitchs_lvl1'] + $levels['physicalSecurityDevices_lvl1'] +
            $levels['wans_lvl1'] + $levels['mans_lvl1'] + $levels['lans_lvl1'] + $levels['vlans_lvl1']) * 100 / $denominator,
                0
            ) : 0;

        // Maturity Level 3
        $denominator =
            $levels['entities'] + $levels['relations'] +
            $levels['macroProcessuses'] + $levels['processes'] + $levels['activities'] + $levels['tasks'] + $levels['operations'] + $levels['actors'] + $levels['informations'] +
            $levels['applicationBlocks'] + $levels['applications'] + $levels['applicationServices'] + $levels['applicationModules'] + $levels['databases'] + $levels['fluxes'] +
            $levels['zones'] + $levels['annuaires'] + $levels['forests'] + $levels['domaines'] +
            $levels['networks'] + $levels['subnetworks'] + $levels['gateways'] + $levels['externalConnectedEntities'] + $levels['switches'] + $levels['routers'] + $levels['securityDevices'] + $levels['DHCPServers'] + $levels['DNSServers'] + $levels['clusters'] + $levels['logicalServers'] + $levels['certificates'] +
            $levels['sites'] + $levels['buildings'] + $levels['bays'] + $levels['physicalServers'] + $levels['physicalRouters'] + $levels['physicalSwitchs'] + $levels['physicalSecurityDevices']
            + $levels['wans'] + $levels['mans'] + $levels['lans'] + $levels['vlans'];

        $levels['maturity3'] =
            $denominator > 0 ?
            number_format(
                ($levels['entities_lvl1'] + $levels['relations_lvl2'] +
            $levels['macroProcessuses_lvl3'] + $levels['processes_lvl2'] + $levels['activities_lvl2'] + $levels['tasks_lvl3'] + $levels['operations_lvl2'] + $levels['actors_lvl2'] + $levels['informations_lvl2'] +
            $levels['applicationBlocks_lvl2'] + $levels['applications_lvl3'] + $levels['applicationServices_lvl2'] + $levels['applicationModules_lvl2'] + $levels['databases_lvl2'] + $levels['fluxes_lvl1'] +
            $levels['zones_lvl1'] + $levels['annuaires_lvl1'] + $levels['forests_lvl1'] + $levels['domaines_lvl1'] +
            $levels['networks_lvl1'] + $levels['subnetworks_lvl1'] + $levels['gateways_lvl1'] + $levels['externalConnectedEntities_lvl2'] + $levels['switches_lvl1'] + $levels['routers_lvl1'] + $levels['securityDevices_lvl1'] + $levels['DHCPServers_lvl2'] + $levels['DNSServers_lvl2'] + $levels['clusters_lvl1'] + $levels['logicalServers_lvl1'] + $levels['certificates_lvl2'] +
            $levels['sites_lvl1'] + $levels['buildings_lvl1'] + $levels['bays_lvl1'] + $levels['physicalServers_lvl1'] + $levels['physicalRouters_lvl1'] + $levels['physicalSwitchs_lvl1'] + $levels['physicalSecurityDevices_lvl1'] + $levels['wans_lvl1'] + $levels['mans_lvl1'] + $levels['lans_lvl1'] + $levels['vlans_lvl1']) * 100 / $denominator,
                0
            ) : 0;

        return $levels;
    }
}
