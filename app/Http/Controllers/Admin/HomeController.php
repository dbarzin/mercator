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
use App\Subnetwork;
use App\Gateway;
use App\ExternalConnectedEntity;
use App\DhcpServer;
use App\Dnsserver;
use App\NetworkSwitch;
use App\Router;
use App\SecurityDevice;
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
use App\Wan;
use App\Man;
use App\Lan;
use App\Vlan;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
 {
    private function compute_maturity(View $view)
    {

        return $view
            // ecosystem
            ->with("entities", Entity::count())
            ->with("relations",Relation::count())

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
            ->with('macroProcessuses', MacroProcessus::count())
            ->with('macroProcessuses_lvl2', MacroProcessus
                    ::where('description', '<>', null)
                    ->where('io_elements', '<>', null)
                    ->where('security_need_c', '<>', null)                    
                    ->where('security_need_i', '<>', null)                    
                    ->where('security_need_a', '<>', null)                    
                    ->where('security_need_t', '<>', null)                    
                    ->count())

            ->with('macroProcessuses_lvl3', MacroProcessus
                    ::where('description', '<>', null)
                    ->where('io_elements', '<>', null)
                    ->where('security_need_c', '<>', null)                    
                    ->where('security_need_i', '<>', null)                    
                    ->where('security_need_a', '<>', null)                    
                    ->where('security_need_t', '<>', null)                    
                    ->where('owner', '<>', null)
                    ->count())

            ->with("processes", Process::count())
            ->with("processes_lvl1", Process
                    ::where('identifiant', '<>', null)
                    ->where('description', '<>', null)
                    ->where('in_out', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('macroprocess_id', '<>', null)                    
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
                    ->count())

            ->with("processes_lvl2", Process
                    ::where('identifiant', '<>', null)
                    ->where('description', '<>', null)
                    ->where('in_out', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('macroprocess_id', '<>', null)                    
                    ->where('security_need_c', '<>', null)                    
                    ->where('security_need_i', '<>', null)                    
                    ->where('security_need_a', '<>', null)                    
                    ->where('security_need_t', '<>', null)                    
                    ->count())


            ->with("activities", Activity::count())
            ->with("activities_lvl3", Activity
                    ::where('description', '<>', null)
                    // activity must have one operation
                    ->whereExists(function ($query) {
                        $query->select("activity_operation.activity_id")
                            ->from("activity_operation")
                            ->whereRaw("activity_operation.activity_id = activities.id");
                    })
                    ->count())

            ->with("operations", Operation::count())
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

            ->with("tasks", Task::count())
            ->with("tasks_lvl3", Task
                    ::where('description', '<>', null)
                    // task must have one operation
                    ->whereExists(function ($query) {
                        $query->select("operation_task.task_id")
                            ->from("operation_task")
                            ->whereRaw("operation_task.task_id = tasks.id");
                    })
                    ->count())

            ->with("actors", Actor::count())
            ->with("actors_lvl2", Actor
                    ::where('contact', '<>', null)
                    ->where('nature', '<>', null)
                    ->where('type', '<>', null)
                    ->count())

            ->with("informations", Information::count())
            ->with("informations_lvl1", Information
                    ::where('descrition', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('administrator', '<>', null)
                    ->where('storage', '<>', null)
                    ->count())

            ->with("informations_lvl2", Information
                    ::where('descrition', '<>', null)
                    ->where('owner', '<>', null)
                    ->where('administrator', '<>', null)
                    ->where('storage', '<>', null)
                    ->where('security_need_c', '<>', null)                    
                    ->where('security_need_i', '<>', null)                    
                    ->where('security_need_a', '<>', null)                    
                    ->where('security_need_t', '<>', null)                    
                    ->where('sensitivity', '<>', null)
                    ->count())

            // Application vue
            ->with("applicationBlocks", ApplicationBlock::count())
            ->with("applicationBlocks_lvl2", ApplicationBlock
                    ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    // applicationBlock must have one application
                    ->whereExists(function ($query) {
                        $query->select("m_applications.id")
                            ->from("m_applications")
                            ->whereRaw("m_applications.application_block_id = application_blocks.id");
                    })
                    ->count())

            ->with("applications", MApplication::count())
            ->with("applications_lvl1", MApplication                
                    ::where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('technology', '<>', null)
                    ->where('type', '<>', null)
                    ->where('users', '<>', null)
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
                    ->where('security_need_c', '<>', null)                    
                    ->where('security_need_i', '<>', null)                    
                    ->where('security_need_a', '<>', null)                    
                    ->where('security_need_t', '<>', null)                    
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
		    // NO - services of external applications are not documented
		    /*
                    ->whereExists(function ($query) {
                       $query->select("application_service_m_application.m_application_id")
                            ->from("application_service_m_application")
                            ->whereRaw("application_service_m_application.m_application_id = m_applications.id");
		    })
		     */
                    ->count())

            ->with("applicationServices", ApplicationService::count())
            ->with("applicationServices_lvl2", ApplicationService
                    ::where('description', '<>', null)
                    // applicationService must have one application
                    ->whereExists(function ($query) {
                        $query->select("application_service_m_application.m_application_id")
                            ->from("application_service_m_application")
                            ->whereRaw("application_service_m_application.application_service_id = application_services.id");
                    })
                    ->count())

            ->with("applicationModules", ApplicationModule::count())
            ->with("applicationModules_lvl2", ApplicationModule
                    ::where('description', '<>', null)
                    ->count())

            ->with("databases", Database::count())
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
                    ->where('security_need_c', '<>', null)                    
                    ->where('security_need_i', '<>', null)                    
                    ->where('security_need_a', '<>', null)                    
                    ->where('security_need_t', '<>', null)                    
                    // ->where('external', '<>', null) //lvl2
                    ->count())

            ->with("fluxes", Flux::count())
            ->with("fluxes_lvl1", Flux
	    ::where('description', '<>', null)
            	->orWhere(function($query) {
               		$query->where('application_source_id', '<>', null)
               		->where('module_source_id', '<>', null)
               		->where('database_source_id', '<>', null);
            	})
            	->orWhere(function($query) {
               		$query->where('application_dest_id', '<>', null)
               		->where('module_dest_id', '<>', null)
               		->where('database_dest_id', '<>', null);
            	})
                    ->where('crypted', '<>', null)
                    ->count())

            ->with("zones", ZoneAdmin::count())
            ->with("zones_lvl1", ZoneAdmin
                    ::where('description', '<>', null)
                    ->count())

            ->with("annuaires", Annuaire::count())
            ->with("annuaires_lvl1", Annuaire
                    ::where('description', '<>', null)
                    ->where('solution', '<>', null)
                    ->where('zone_admin_id', '<>', null)
                    ->count())

            ->with("forests", ForestAd::count())
            ->with("forests_lvl1", ForestAd
                    ::where('description', '<>', null)
                    ->where('zone_admin_id', '<>', null)
                    ->count())

            ->with("domaines", DomaineAd::count())
            ->with("domaines_lvl1", DomaineAd
                    ::where('description', '<>', null)
                    ->where('domain_ctrl_cnt', '<>', null)
                    ->where('user_count', '<>', null)
                    ->where('machine_count', '<>', null)
                    ->where('relation_inter_domaine', '<>', null)
                    ->count())

            // Logique
            ->with("networks", Network::count())
            ->with("networks_lvl1", Network
                    ::where('description', '<>', null)
                    ->where('protocol_type', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('responsible_sec', '<>', null)
                    ->where('security_need', '<>', null)
                    ->count())

            ->with("subnetworks", Subnetwork::count())
            ->with("subnetworks_lvl1", Subnetwork
                    ::where('description', '<>', null)
                    ->where('address', '<>', null)
                    ->where('default_gateway', '<>', null)
                    ->where('ip_allocation_type', '<>', null)
                    ->where('responsible_exp', '<>', null)
                    ->where('dmz', '<>', null)
                    ->where('wifi', '<>', null)
                    ->where('vlan_id', '<>', null)
                    ->count())

            ->with("gateways", Gateway::count())
            ->with("gateways_lvl1", Gateway
                    ::where('description', '<>', null)
                    ->where('authentification', '<>', null)
                    ->where('ip', '<>', null)
                    ->count())

            ->with("externalConnectedEntities", ExternalConnectedEntity::count())
            ->with("externalConnectedEntities_lvl2", ExternalConnectedEntity
                    ::where('responsible_sec', '<>', null)
                    ->where('contacts', '<>', null)
                    ->count())

            ->with("switches", NetworkSwitch::count())
            ->with("switches_lvl1", NetworkSwitch
                    ::where('description', '<>', null)
                    // IP not mandatory on a switch
                    // ->where('ip', '<>', null)
                    ->count())

            ->with("routers", Router::count())
            ->with("routers_lvl1", Router
                    ::where('description', '<>', null)
                    ->count())

            ->with("securityDevices", SecurityDevice::count())
            ->with("securityDevices_lvl1", SecurityDevice
                    ::where('description', '<>', null)
                    ->count())

            ->with("DHCPServers", DhcpServer::count())
            ->with("DHCPServers_lvl2", DhcpServer
                    ::where('description', '<>', null)
                    ->count())

            ->with("DNSServers", DnsServer::count())
            ->with("DNSServers_lvl2", DnsServer
                    ::where('description', '<>', null)
                    ->count())

            ->with("logicalServers", LogicalServer::count())
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

            ->with("certificates", Certificate::count())
            ->with("certificates_lvl2", Certificate
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('start_validity', '<>', null)
                    ->where('end_validity', '<>', null)
                    // certificate must be on a logical server
                    ->whereExists(function ($query) {
                        $query->select("certificate_logical_server.logical_server_id")
                            ->from("certificate_logical_server")
                            ->whereRaw("certificate_logical_server.certificate_id = certificates.id");
                    })->count())

            // Physical
            ->with("sites", Site::count())
            ->with("sites_lvl1", Site
            		::where('description', '<>', null)
                    ->count())

            ->with("buildings", Building::count())
            ->with("buildings_lvl1", Building
            		::where('description', '<>', null)
                    ->count())

            ->with("bays", Bay::count())
            ->with("bays_lvl1", Bay
            		::where('description', '<>', null)
                    ->count())
            
            ->with("physicalServers", PhysicalServer::count())
            ->with("physicalServers_lvl1",  PhysicalServer
            		::where('descrition', '<>', null)
                    ->where('configuration', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // a server is not necessary in a bay
                    // ->where('bay_id', '<>', null)
                    ->where('responsible', '<>', null)
                    ->count())
            
            ->with("workstations", Workstation::count())
            ->with("workstations_lvl1", Workstation
                    ::where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    ->count())

            ->with("storageDevices", StorageDevice::count())
            ->with("storageDevices_lvl1", StorageDevice
                    ::where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay                    
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("peripherals", Peripheral::count())
            ->with("peripherals_lvl1", Peripheral
                    ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('responsible', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay                    
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("phones", Phone::count())
            ->with("phones_lvl1", Phone
                    ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("physicalSwitchs", PhysicalSwitch::count())
            ->with("physicalSwitchs_lvl1", PhysicalSwitch
                    ::where('type', '<>', null)
                    ->where('description', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("physicalRouters", PhysicalRouter::count())
            ->with("physicalRouters_lvl1", PhysicalRouter
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("wifiTerminals", WifiTerminal::count())
            ->with("wifiTerminals_lvl1", WifiTerminal
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    ->count())

            ->with("physicalSecurityDevices", PhysicalSecurityDevice::count())
            ->with("physicalSecurityDevices_lvl1", PhysicalSecurityDevice
                    ::where('description', '<>', null)
                    ->where('type', '<>', null)
                    ->where('site_id', '<>', null)
                    ->where('building_id', '<>', null)
                    // not always in a bay
                    // ->where('bay_id', '<>', null)
                    ->count())

            ->with("wans", Wan::count())
            ->with("wans_lvl1", Wan::count())

            ->with("mans", Man::count())
            ->with("mans_lvl1", Man::count())

            ->with("lans", Lan::count())
            ->with("lans_lvl1", Lan
            		::where('description', '<>', null)
                    ->count())

            ->with("vlans", Vlan::count())
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
