<?php

namespace App\Http\Controllers\Admin;

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
use App\Subnetword;
use App\Gateway;
use App\ExternalConnectedEntity;
use App\DhcpServer;
use App\Dnsserver;
use App\NetworkSwitch;
use App\Router;
use App\SecurityDevice;
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
use App\Wan;
use App\Man;
use App\Lan;
use App\Vlan;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
 {
    private function compute_maturity(View $view)
    {

        return $view
            // ecosystem
            ->with("entities", Entity::All()->count())
            ->with("relations",Relation::All()->count())

            ->with("entities_lvl1",Entity
            		::where('description', '<>', null)
                    ->where('security_level','<>', null)
                    ->where('contact_point','<>','null')
                    // entity must support at least one process
                    ->whereExists(function ($query) {
                        $query->select("entity_process.entity_id")
                            ->from('entity_process')
                            ->whereRaw('entity_process.entity_id = entities.id');
                    })                    
                    ->count())

            ->with("relations_lvl1", Relation
            		::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->count())

            ->with("relations_lvl2", Relation
                   	::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('inportance', '>', 0)
                    ->count())

            // information system            
            ->with('macroProcessuses', MacroProcessus::All()->count())
            ->with('macroProcessuses_lvl2', MacroProcessus
                    ::where('description', '<>', null)
                    ->where('io_elements', '<>', null)
                    ->where('security_need', '<>', null)                    
                    ->count())

            ->with('macroProcessuses_lvl3', MacroProcessus
                    ::where('description', '<>', null)
                    ->where('io_elements', '<>', null)
                    ->where('security_need', '<>', null)
                    ->where('owner', '<>', null)
                    ->count())

            ->with("processes", Process::All()->count())
            ->with("processes_lvl1", Process
                    ::where('identifiant', '<>', null)
                    ->where('description', '<>', null)
                    ->where('in_out', '<>', null)
                    ->where('security_need', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('macroprocess_id', '<>', null)                    
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
                    ->count())

            ->with("activities", Activity::All()->count())
            ->with("activities_lvl3", Activity
                    ::where('description', '<>', null)
                    // activity must have one operation
                    ->whereExists(function ($query) {
                        $query->select("activity_operation.activity_id")
                            ->from("activity_operation")
                            ->whereRaw("activity_operation.activity_id = activities.id");
                    })
                    ->count())

            ->with("operations", Operation::All()->count())
            ->with("operations_lvl1", Operation                
                    ::where('description', '<>', null)
                    ->count())

            ->with("operations_lvl2", Operation                
                    ::where('description', '<>', null)
                    // must have at least one actor
                    ->whereExists(function ($query) {
                        $query->select("actor_operation.operation_id")
                            ->from("actor_operation")
                            ->whereRaw("actor_operation.operation_id = operations.id");
                    })
                    ->count())

            ->with("operations_lvl3", Operation                
                    ::where('description', '<>', null)
                    // must have at least one actor
                    ->whereExists(function ($query) {
                        $query->select("actor_operation.operation_id")
                            ->from("actor_operation")
                            ->whereRaw("actor_operation.operation_id = operations.id");
                    })
                    // must have at least one task
                    ->whereExists(function ($query) {
                        $query->select("operation_task.operation_id")
                            ->from("operation_task")
                            ->whereRaw("operation_task.operation_id = operations.id");
                    })
                    ->count())

            ->with("tasks", Task::All()->count())
            ->with("tasks_lvl3", Task
                    ::where('description', '<>', null)
                    // task must have one operation
                    ->whereExists(function ($query) {
                        $query->select("operation_task.task_id")
                            ->from("operation_task")
                            ->whereRaw("operation_task.task_id = tasks.id");
                    })
                    ->count())

            ->with("actors", Actor::All()->count())
            ->with("actors_lvl2", Actor
                    ::where('contact', '<>', null)
                    ->where('nature', '<>', null)
                    ->where('type', '<>', null)
                    ->count())

            ->with("informations", Information::All()->count())
            ->with("informations_lvl1", Information
                    ::where('descrition', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('administrator', '<>', null)
                    ->where('storage', '<>', null)
                    ->where('security_need', '<>', null)
                    ->where('sensitivity', '<>', null)
                    ->count())

            // Application vue
            ->with("applicationBlocks", ApplicationBlock::All()->count())
            ->with("applicationBlocks_lvl2", ApplicationBlock
                    ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->count())

            ->with("applications", MApplication::All()->count())
            ->with("applications_lvl1", MApplication                
                    ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('technology', '<>', null)
                    ->where('type', '<>', null)
                    ->where('users', '<>', null)
                    ->where('security_need', '<>', null)
                    // application must have one process
                    ->whereExists(function ($query) {
                        $query->select("m_application_process.m_application_id")
                            ->from("m_application_process")
                            ->whereRaw("m_application_process.m_application_id = m_applications.id");
                    })
                    // application must have one logical server
                    /* No - fat client application does not have a logical server
                    ->whereExists(function ($query) {
                        $query->select("logical_server_m_application.m_application_id")
                            ->from("logical_server_m_application")
                            ->whereRaw("logical_server_m_application.m_application_id = m_applications.id");
                    })
                    */
                    ->count())

            ->with("applications_lvl2", MApplication
                    ::where('description', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('technology', '<>', null)
                    ->where('type', '<>', null)
                    ->where('users', '<>', null)
                    ->where('security_need', '<>', null)
                    // application must have one process
                    ->whereExists(function ($query) {
                        $query->select("m_application_process.m_application_id")
                            ->from("m_application_process")
                            ->whereRaw("m_application_process.m_application_id = m_applications.id");
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
                    ->whereExists(function ($query) {
                        $query->select("application_service_m_application.m_application_id")
                            ->from("application_service_m_application")
                            ->whereRaw("application_service_m_application.m_application_id = m_applications.id");
                    })
                    ->count())

            ->with("applicationServices", ApplicationService::All()->count())
            ->with("applicationServices_lvl2", ApplicationService
                    ::where('description', '<>', null)
                    ->count())

            ->with("applicationModules", ApplicationModule::All()->count())
            ->with("applicationModules_lvl2", ApplicationModule
                    ::where('description', '<>', null)
                    ->count())

            ->with("databases", Database::All()->count())
            ->with("databases_lvl1", Database
                    ::where('description', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('type', '<>', null)
                    ->count())

            ->with("databases_lvl2", Database                
                    ::where('description', '<>', null)
                    ->where('entity_resp_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('type', '<>', null)
                    // ->where('security_need', '<>', null) //lvl2
                    // ->where('external', '<>', null) //lvl2
                    ->count())

            ->with("fluxes", Flux::All()->count())
            ->with("fluxes_lvl1", Flux
                    ::where('application_dest_id', '<>', null)
                    ->where('application_dest_id', '<>', null)
                    ->where('crypted', '<>', null)
                    ->count())

            ->with("zones", ZoneAdmin::All()->count())
            ->with("zones_lvl1", ZoneAdmin
                    ::where('description', '<>', null)
                    ->count())

            ->with("annuaires", Annuaire::All()->count())
            ->with("annuaires_lvl1", Annuaire
                    ::where('description', '<>', null)
                    ->where('solution', '<>', null)
                    ->where('zone_admin_id', '<>', null)
                    ->count())

            ->with("forests", ForestAd::All()->count())
            ->with("forests_lvl1", ForestAd
                    ::where('description', '<>', null)
                    ->where('zone_admin_id', '<>', null)
                    ->count())

            ->with("domaines", DomaineAd::All()->count())
            ->with("domaines_lvl1", DomaineAd
                    ::where('description', '<>', null)
                    ->where('domain_ctrl_cnt', '<>', null)
                    ->where('user_count', '<>', null)
                    ->where('machine_count', '<>', null)
                    ->where('relation_inter_domaine', '<>', null)
                    ->count())

            // Logique
            ->with("networks", Network::All()->count())
            ->with("networks_lvl1", Network
                    ::where('description', '<>', null)
                    ->where('protocol_type', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('responsible_sec', '<>', null)
                    ->where('security_need', '<>', null)
                    ->count())

            ->with("subnetworks", Subnetword::All()->count())
            ->with("subnetworks_lvl1", Subnetword
                    ::where('description', '<>', null)
                    ->where('address', '<>', null)
                    ->where('ip_range', '<>', null)
                    ->where('ip_allocation_type', '<>', null)
                    ->where('responsible_exp', '<>', null)
                    ->where('dmz', '<>', null)
                    ->where('wifi', '<>', null)
                    ->where('connected_subnets_id', '<>', null)
                    ->count())

            ->with("gateways", Gateway::All()->count())
            ->with("gateways_lvl1", Gateway
                    ::where('description', '<>', null)
                    ->where('authentification', '<>', null)
                    ->where('ip', '<>', null)
                    ->count())

            ->with("externalConnectedEntities", ExternalConnectedEntity::All()->count())
            ->with("externalConnectedEntities_lvl2", ExternalConnectedEntity
                    ::where('responsible_sec', '<>', null)
                    ->where('contacts', '<>', null)
                    ->count())

            ->with("switches", NetworkSwitch::All()->count())
            ->with("switches_lvl1", NetworkSwitch
                    ::where('description', '<>', null)
                    // IP not mandatory on a switch
                    // ->where('ip', '<>', null)
                    ->count())

            ->with("routers", Router::All()->count())
            ->with("routers_lvl1", Router
                    ::where('description', '<>', null)
                    ->count())

            ->with("securityDevices", SecurityDevice::All()->count())
            ->with("securityDevices_lvl1", SecurityDevice
                    ::where('description', '<>', null)
                    ->count())

            ->with("DHCPServers", DhcpServer::All()->count())
            ->with("DHCPServers_lvl2", DhcpServer
                    ::where('description', '<>', null)
                    ->count())

            ->with("DNSServers", DnsServer::All()->count())
            ->with("DNSServers_lvl2", DnsServer
                    ::where('description', '<>', null)
                    ->count())

            ->with("logicalServers", LogicalServer::All()->count())
            ->with("logicalServers_lvl1", LogicalServer
                    ::where('description', '<>', null)
                    ->where('operating_system', '<>', null)
                    ->where('environment', '<>', null)
                    ->where('address_ip', '<>', null)
                    // logicalServer must have one application
                    ->whereExists(function ($query) {
                        $query->select("logical_server_m_application.logical_server_id")
                            ->from("logical_server_m_application")
                            ->whereRaw("logical_server_m_application.logical_server_id = logical_servers.id");
                    })
                    // logicalServer must be installed on a pysical server
                    ->whereExists(function ($query) {
                        $query->select("logical_server_physical_server.logical_server_id")
                            ->from("logical_server_physical_server")
                            ->whereRaw("logical_server_physical_server.logical_server_id = logical_servers.id");
                    })
                    ->count())

            // Physical
            ->with("sites", Site::All()->count())
            ->with("sites_lvl1", Site
            		::where('description', '<>', null)
                    ->count())

            ->with("buildings", Building::All()->count())
            ->with("buildings_lvl1", Building
            		::where('description', '<>', null)
                    ->count())

            ->with("bays", Bay::All()->count())
            ->with("bays_lvl1", Bay
            		::where('description', '<>', null)
                    ->count())
            
            ->with("physicalServers", PhysicalServer::All()->count())
            ->with("physicalServers_lvl1",  PhysicalServer
            		::where('descrition', '<>', null)
                    ->where('configuration', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // a server is not necessary in a bay
                    // ->where('bay_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->count())
            
            ->with("workstations", Workstation::All()->count())
            ->with("workstations_lvl1", Workstation
                    ::where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    ->count())

            ->with("storageDevices", StorageDevice::All()->count())
            ->with("storageDevices_lvl1", StorageDevice
                    ::where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay                    
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("peripherals", Peripheral::All()->count())
            ->with("peripherals_lvl1", Peripheral
                    ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay                    
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("phones", Phone::All()->count())
            ->with("phones_lvl1", Phone
                    ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("physicalSwitchs", PhysicalSwitch::All()->count())
            ->with("physicalSwitchs_lvl1", PhysicalSwitch
                    ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("physicalRouters", PhysicalRouter::All()->count())
            ->with("physicalRouters_lvl1", PhysicalRouter
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("wifiTerminals", WifiTerminal::All()->count())
            ->with("wifiTerminals_lvl1", WifiTerminal
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    ->count())

            ->with("physicalSecurityDevices", PhysicalSecurityDevice::All()->count())
            ->with("physicalSecurityDevices_lvl1", PhysicalSecurityDevice
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("wans", Wan::All()->count())
            ->with("wans_lvl1", Wan::count())

            ->with("mans", Man::All()->count())
            ->with("mans_lvl1", Man::count())

            ->with("lans", Lan::All()->count())
            ->with("lans_lvl1", Lan
            		::where('description', '<>', null)
                    ->count())

            ->with("vlans", Vlan::All()->count())
            ->with("vlans_lvl1", Vlan
            		::where('description', '<>', null)
                    ->count())
            ;    

    }

    public function maturity1() {
        return $this->compute_maturity(view('admin/reports/maturity1'));
    }

    public function maturity2() {
        return $this->compute_maturity(view('admin/reports/maturity2'));
    }

    public function maturity3() {
        return $this->compute_maturity(view('admin/reports/maturity3'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return $this->compute_maturity(view('home'));
    }
}
