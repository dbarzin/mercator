<?php

namespace App\Http\Controllers\Admin;

// ecosystem
// information system
// Applications
// Administration
use App\Http\Controllers\Controller;
// Logique
// Physique
use App\Subnetwork;
use Illuminate\Support\Facades\DB;

class ExplorerController extends Controller
{
    public function explore()
    {
        $nodes = [];
        $edges = [];

        // ---------------------------------------------------
        // Physical view
        // ---------------------------------------------------
        // SITES
        $sites = DB::table('sites')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($sites as $site) {
            array_push($nodes, [ 'id' => 'SITE_'.$site->id, 'label' => $site->name, 'image' => '/images/site.png' ]);
            // link to build
        }
        // BUILDINGS
        $buildings = DB::table('buildings')->select('id', 'name', 'site_id')->whereNull('deleted_at')->get();
        foreach ($buildings as $building) {
            array_push($nodes, [ 'id' => 'BUILDING_' . $building->id, 'label' => $building->name, 'image' => '/images/building.png' ]);
            array_push($edges, [ 'from' => 'BUILDING_' . $building->id, 'to' => 'SITE_' . $building->site_id ]);
        }
        // Bay
        $bays = DB::table('bays')->select('id', 'name', 'room_id')->whereNull('deleted_at')->get();
        foreach ($bays as $bay) {
            array_push($nodes, [ 'id' => 'BAY_' . $bay->id, 'label' => $bay->name, 'image' => '/images/bay.png' ]);
            array_push($edges, [ 'from' => 'BAY_' . $bay->id, 'to' => 'BUILDING_' . $bay->room_id ]);
        }
        // Physical Server
        $physicalServers = DB::table('physical_servers')->select('id', 'name', 'bay_id')->whereNull('deleted_at')->get();
        foreach ($physicalServers as $physicalServer) {
            array_push($nodes, [ 'id' => 'PSERVER_' . $physicalServer->id, 'label' => $physicalServer->name, 'image' => '/images/server.png' ]);
            array_push($edges, [ 'from' => 'PSERVER_' . $physicalServer->id, 'to' => 'BAY_' . $physicalServer->bay_id ]);
        }
        // Workstation
        $workstations = DB::table('workstations')->select('id', 'name', 'building_id')->whereNull('deleted_at')->get();
        foreach ($workstations as $workstation) {
            array_push($nodes, [ 'id' => 'WORK_' . $workstation->id, 'label' => $workstation->name, 'image' => '/images/workstation.png' ]);
            if ($workstation->building_id !== null) {
                array_push($edges, [ 'from' => 'WORK_' . $workstation->id, 'to' => 'BUILDING_' . $workstation->building_id ]);
            }
        }
        // physical_switches
        $switches = DB::table('physical_switches')->select('id', 'name', 'bay_id')->whereNull('deleted_at')->get();
        foreach ($switches as $switch) {
            array_push($nodes, [ 'id' => 'SWITCH_' . $switch->id, 'label' => $switch->name, 'image' => '/images/switch.png' ]);
            if ($switch->bay_id !== null) {
                array_push($edges, [ 'from' => 'SWITCH_' . $switch->id, 'to' => 'BAY_' . $switch->bay_id ]);
            }
        }

        // ---------------------------------------------------
        // Logical view
        // ---------------------------------------------------
        // networks
        $networks = DB::table('networks')->select('id', 'name')->whereNull('deleted_at')->whereNull('deleted_at')->get();
        foreach ($networks as $network) {
            array_push($nodes, [ 'id' => 'NETWORK_' . $network->id, 'label' => $network->name, 'image' => '/images/cloud.png' ]);
        }

        // vlans
        $vlans = DB::table('vlans')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($vlans as $vlan) {
            array_push($nodes, [ 'id' => 'VLAN_' . $vlan->id, 'label' => $vlan->name, 'image' => '/images/vlan.png' ]);
        }

        // Subnetworks
        // $subnetworks = DB::table("subnetworks")->select("id","name","network_id","vlan_id")->whereNull('deleted_at')->get();
        $subnetworks = Subnetwork::all();
        foreach ($subnetworks as $subnetwork) {
            array_push($nodes, [
                'id' => 'SUBNETWORK_' . $subnetwork->id,
                'label' => $subnetwork->name,
                'title' => $subnetwork->address,
                'image' => '/images/network.png',
            ]);
            if ($subnetwork->network_id !== null) {
                array_push($edges, [ 'from' => 'SUBNETWORK_' . $subnetwork->id, 'to' => 'NETWORK_' . $subnetwork->network_id ]);
            }
            if ($subnetwork->vlan_id !== null) {
                array_push($edges, [ 'from' => 'SUBNETWORK_' . $subnetwork->id, 'to' => 'VLAN_' . $subnetwork->vlan_id ]);
            }
        }

        // Logical Servers
        $logicalServers = DB::table('logical_servers')->select('id', 'name', 'address_ip')->get();
        foreach ($logicalServers as $logicalServer) {
            array_push($nodes, [ 'id' => 'LSERVER_' . $logicalServer->id, 'label' => $logicalServer->name, 'image' => '/images/lserver.png' ]);

            if ($logicalServer->address_ip !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $logicalServer->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            array_push($edges, [ 'from' => 'SUBNETWORK_' . $subnetwork->id, 'to' => 'LSERVER_' . $logicalServer->id ]);
                            break;
                        }
                    }
                }
            }
        }

        // Logical Servers - Physical Servers
        $joins = DB::table('logical_server_physical_server')->select('physical_server_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'PSERVER_' . $join->physical_server_id, 'to' => 'LSERVER_' . $join->logical_server_id ]);
        }
        // Certificates
        $certificates = DB::table('certificates')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($certificates as $certificate) {
            array_push($nodes, [ 'id' => 'CERT_' . $certificate->id, 'label' => $certificate->name, 'image' => '/images/certificate.png' ]);
        }
        // certificate_logical_server
        $joins = DB::table('certificate_logical_server')->select('certificate_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'CERT_' . $join->certificate_id, 'to' => 'LSERVER_' . $join->logical_server_id ]);
        }

        // ---------------------------------------------------
        // Administration view
        // ---------------------------------------------------
        // Zones
        $zoneAdmins = DB::table('zone_admins')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($zoneAdmins as $zone) {
            array_push($nodes, [ 'id' => 'ZONE_' . $zone->id, 'label' => $zone->name, 'image' => '/images/zoneadmin.png' ]);
        }
        // Annuaires
        $annuaires = DB::table('annuaires')->select('id', 'name', 'zone_admin_id')->whereNull('deleted_at')->get();
        foreach ($annuaires as $annuaire) {
            array_push($nodes, [ 'id' => 'ANNUAIRE_' . $annuaire->id, 'label' => $annuaire->name, 'image' => '/images/annuaire.png' ]);
            if ($annuaire->zone_admin_id !== null) {
                array_push($edges, [ 'from' => 'ANNUAIRE_' . $annuaire->id, 'to' => 'ZONE_' . $annuaire->zone_admin_id ]);
            }
        }
        // Forest
        $forests = DB::table('forest_ads')->select('id', 'name', 'zone_admin_id')->whereNull('deleted_at')->get();
        foreach ($forests as $forest) {
            array_push($nodes, [ 'id' => 'FOREST_' . $forest->id, 'label' => $forest->name, 'image' => '/images/ldap.png' ]);
            if ($annuaire->zone_admin_id !== null) {
                array_push($edges, [ 'from' => 'FOREST_' . $forest->id, 'to' => 'ZONE_' . $forest->zone_admin_id ]);
            }
        }
        // Domain
        $domains = DB::table('domaine_ads')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($domains as $domain) {
            array_push($nodes, [ 'id' => 'DOMAIN_' . $domain->id, 'label' => $domain->name, 'image' => '/images/domain.png' ]);
        }
        // domaine_ad_forest_ad
        $joins = DB::table('domaine_ad_forest_ad')->select('forest_ad_id', 'domaine_ad_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'FOREST_' . $join->forest_ad_id, 'to' => 'DOMAIN_' . $join->domaine_ad_id ]);
        }

        // ---------------------------------------------------
        // Application view
        // ---------------------------------------------------
        // Application Blocks
        $applicationBlocks = DB::table('application_blocks')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($applicationBlocks as $applicationBlock) {
            array_push($nodes, [ 'id' => 'BLOCK_' . $applicationBlock->id, 'label' => $applicationBlock->name, 'image' => '/images/applicationblock.png' ]);
        }
        // Applications
        $applications = DB::table('m_applications')->select('id', 'name', 'application_block_id')->whereNull('deleted_at')->get();
        foreach ($applications as $application) {
            array_push($nodes, [ 'id' => 'APP_' . $application->id, 'label' => $application->name, 'image' => '/images/application.png' ]);
            if ($application->application_block_id !== null) {
                array_push($edges, [ 'from' => 'BLOCK_' . $application->application_block_id, 'to' => 'APP_' . $application->id ]);
            }
        }
        // logical_server_m_application
        $joins = DB::table('logical_server_m_application')->select('m_application_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'APP_' . $join->m_application_id, 'to' => 'LSERVER_' . $join->logical_server_id ]);
        }
        // Application Services
        $services = DB::table('application_services')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($services as $service) {
            array_push($nodes, [ 'id' => 'SERV_' . $service->id, 'label' => $service->name, 'image' => '/images/service.png' ]);
        }
        // application_service_m_application
        $joins = DB::table('application_service_m_application')->select('m_application_id', 'application_service_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'APP_' . $join->m_application_id, 'to' => 'SERV_' . $join->application_service_id ]);
        }
        // Application Modules
        $modules = DB::table('application_modules')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($modules as $module) {
            array_push($nodes, [ 'id' => 'MOD_' . $module->id, 'label' => $module->name, 'image' => '/images/applicationmodule.png' ]);
        }
        // application_module_application_service
        $joins = DB::table('application_module_application_service')->select('application_module_id', 'application_service_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'MOD_' . $join->application_module_id, 'to' => 'SERV_' . $join->application_service_id ]);
        }

        // certificate_m_application
        $joins = DB::table('certificate_m_application')->select('m_application_id', 'certificate_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'APP_' . $join->m_application_id, 'to' => 'CERT_' . $join->certificate_id ]);
        }
        // Databases
        $databases = DB::table('databases')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($databases as $database) {
            array_push($nodes, [ 'id' => 'DATABASE_' . $database->id, 'label' => $database->name, 'image' => '/images/database.png' ]);
        }
        // database_m_application
        $joins = DB::table('database_m_application')->select('m_application_id', 'database_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'APP_' . $join->m_application_id, 'to' => 'DATABASE_' . $join->database_id ]);
        }

        // ---------------------------------------------------
        // Information System
        // ---------------------------------------------------
        // Information
        $informations = DB::table('information')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($informations as $information) {
            array_push($nodes, [ 'id' => 'INFO_' . $information->id, 'label' => $information->name, 'image' => '/images/information.png' ]);
        }
        // database_information
        $joins = DB::table('database_information')->select('information_id', 'database_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'INFO_' . $join->information_id, 'to' => 'DATABASE_' . $join->database_id ]);
        }
        // process
        $processes = DB::table('processes')->select('id', 'identifiant', 'macroprocess_id')->get();
        foreach ($processes as $process) {
            array_push($nodes, [ 'id' => 'PROCESS_' . $process->id, 'label' => $process->identifiant, 'image' => '/images/process.png' ]);
            if ($process->macroprocess_id !== null) {
                array_push($edges, [ 'from' => 'PROCESS_' . $process->id, 'to' => 'MACROPROCESS_' . $process->macroprocess_id ]);
            }
        }
        // information_process
        $joins = DB::table('information_process')->select('information_id', 'process_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'INFO_' . $join->information_id, 'to' => 'PROCESS_' . $join->process_id ]);
        }
        // macro_processuses
        $macro_processuses = DB::table('macro_processuses')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($macro_processuses as $macro_process) {
            array_push($nodes, [ 'id' => 'MACROPROCESS_' . $macro_process->id, 'label' => $macro_process->name, 'image' => '/images/macroprocess.png' ]);
        }

        // Activities
        $activities = DB::table('activities')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($activities as $activity) {
            array_push($nodes, [ 'id' => 'ACTIVITY_' . $activity->id, 'label' => $activity->name, 'image' => '/images/activity.png' ]);
        }
        // activity_process
        $joins = DB::table('activity_process')->select('activity_id', 'process_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'ACTIVITY_' . $join->activity_id, 'to' => 'PROCESS_' . $join->process_id ]);
        }

        // Operations
        $operations = DB::table('operations')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($operations as $operation) {
            array_push($nodes, [ 'id' => 'OPERATION_' . $operation->id, 'label' => $operation->name, 'image' => '/images/operation.png' ]);
        }

        // activity_operation
        $joins = DB::table('activity_operation')->select('activity_id', 'operation_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'ACTIVITY_' . $join->activity_id, 'to' => 'OPERATION_' . $join->operation_id ]);
        }

        // Tasks
        $operations = DB::table('operations')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($operations as $operation) {
            array_push($nodes, [ 'id' => 'OPERATION_' . $operation->id, 'label' => $operation->name, 'image' => '/images/operation.png' ]);
        }

        // Actors
        $actors = DB::table('actors')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($actors as $actor) {
            array_push($nodes, [ 'id' => 'ACTOR_' . $actor->id, 'label' => $actor->name, 'image' => '/images/actor.png' ]);
        }

        // actor_operation
        $joins = DB::table('actor_operation')->select('actor_id', 'operation_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'ACTOR_' . $join->actor_id, 'to' => 'OPERATION_' . $join->operation_id ]);
        }
        // ---------------------------------------------------
        // Ecosystem
        // ---------------------------------------------------
        // Entities
        $entities = DB::table('entities')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($entities as $entity) {
            array_push($nodes, [ 'id' => 'ENTITY_' . $entity->id, 'label' => $entity->name, 'image' => '/images/entity.png' ]);
        }

        // Relations
        $relations = DB::table('relations')->select('id', 'name', 'source_id', 'destination_id')->whereNull('deleted_at')->get();
        foreach ($relations as $relation) {
            array_push($nodes, [ 'id' => 'REL_' . $relation->id, 'label' => $relation->name, 'image' => '/images/relation.png' ]);
            if ($relation->source_id !== null) {
                array_push($edges, [ 'from' => 'REL_' . $relation->id, 'to' => 'ENTITY_' . $relation->source_id ]);
            }
            if ($relation->destination_id !== null) {
                array_push($edges, [ 'from' => 'REL_' . $relation->id, 'to' => 'ENTITY_' . $relation->destination_id ]);
            }
        }
        // entity_process
        $joins = DB::table('entity_process')->select('entity_id', 'process_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'ENTITY_' . $join->entity_id, 'to' => 'PROCESS_' . $join->process_id ]);
        }
        // entity_m_application
        $joins = DB::table('entity_m_application')->select('entity_id', 'm_application_id')->get();
        foreach ($joins as $join) {
            array_push($edges, [ 'from' => 'ENTITY_' . $join->entity_id, 'to' => 'APP_' . $join->m_application_id ]);
        }

        return view('admin/reports/explore', compact('nodes', 'edges'));
    }
}
