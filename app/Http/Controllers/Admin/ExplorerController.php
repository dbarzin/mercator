<?php

namespace App\Http\Controllers\Admin;

// ecosystem
use App\Activity;
use App\Actor;
use App\Task;
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
use App\Vlan;
use App\WifiTerminal;
use App\Workstation;
use App\ZoneAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExplorerController extends Controller
{

    public function explore()
    {
        $nodes = array();
        $edges = array();

        // ---------------------------------------------------
        // Physical view
        // ---------------------------------------------------
        // SITES
        $sites = DB::table("sites")->select("id","name")->get();
        foreach ($sites as $site) {
            array_push($nodes, [ "id" => "SITE_".$site->id, "label" => $site->name, "image" => "/images/site.png" ]);
            // link to build
        }
        // BUILDINGS
        $buildings = DB::table("buildings")->select("id","name","site_id")->get();
        foreach ($buildings as $building) {
            array_push($nodes, [ "id" => "BUILDING_" . $building->id, "label" => $building->name, "image" => "/images/building.png" ]);
            array_push($edges, [ "from" => "BUILDING_" . $building->id, "to" => "SITE_" . $building->site_id ]);
        }
        // Bay
        $bays = DB::table("bays")->select("id","name","room_id")->get();
        foreach ($bays as $bay) {
            array_push($nodes, [ "id" => "BAY_" . $bay->id, "label" => $bay->name, "image" => "/images/bay.png" ]);
            array_push($edges, [ "from" => "BAY_" . $bay->id, "to" => "BUILDING_" . $bay->room_id ]);
        }
        // Physical Server
        $physicalServers = DB::table("physical_servers")->select("id","name","bay_id")->get();
        foreach ($physicalServers as $physicalServer) {
            array_push($nodes, [ "id" => "PSERVER_" . $physicalServer->id, "label" => $physicalServer->name, "image" => "/images/server.png" ]);
            array_push($edges, [ "from" => "PSERVER_" . $physicalServer->id, "to" => "BAY_" . $physicalServer->bay_id ]);
        }
        // Workstation
        $workstations = DB::table("workstations")->select("id","name","building_id")->get();
        foreach ($workstations as $workstation) {
            array_push($nodes, [ "id" => "WORK_" . $workstation->id, "label" => $workstation->name, "image" => "/images/workstation.png" ]);
            if ($workstation->building_id!=null)
                array_push($edges, [ "from" => "WORK_" . $workstation->id, "to" => "BUILDING_" . $workstation->building_id ]);
        }
        // physical_switches
        $switches = DB::table("physical_switches")->select("id","name","bay_id")->get();
        foreach ($switches as $switch) {
            array_push($nodes, [ "id" => "SWITCH_" . $switch->id, "label" => $switch->name, "image" => "/images/switch.png" ]);
            if ($switch->bay_id!=null)
                array_push($edges, [ "from" => "SWITCH_" . $switch->id, "to" => "BAY_" . $switch->bay_id ]);
        }

        // ---------------------------------------------------
        // Logical view
        // ---------------------------------------------------
        // networks
        $networks = DB::table("networks")->select("id","name")->get();
        foreach ($networks as $network) {
            array_push($nodes, [ "id" => "NETWORK_" . $network->id, "label" => $network->name, "image" => "/images/cloud.png" ]);
        }

        // vlans
        $vlans = DB::table("vlans")->select("id","name")->get();
        foreach ($vlans as $vlan) {
            array_push($nodes, [ "id" => "VLAN_" . $vlan->id, "label" => $vlan->name, "image" => "/images/vlan.png" ]);
        }

        // Subnetworks
        // $subnetworks = DB::table("subnetworks")->select("id","name","network_id","vlan_id")->get();
        $subnetworks = Subnetwork::all();
        foreach ($subnetworks as $subnetwork) {
            array_push($nodes, [ "id" => "SUBNETWORK_" . $subnetwork->id, "label" => $subnetwork->name, "image" => "/images/network.png" ]);
            if ($subnetwork->network_id!=null)
                array_push($edges, [ "from" => "SUBNETWORK_" . $subnetwork->id, "to" => "NETWORK_" . $subnetwork->network_id ]);
            if ($subnetwork->vlan_id!=null)
                array_push($edges, [ "from" => "SUBNETWORK_" . $subnetwork->id, "to" => "VLAN_" . $subnetwork->vlan_id ]);
        }

        // Logical Servers
        $logicalServers = DB::table("logical_servers")->select("id","name","address_ip")->get();
        foreach ($logicalServers as $logicalServer) {
            array_push($nodes, [ "id" => "LSERVER_" . $logicalServer->id, "label" => $logicalServer->name, "image" => "/images/server.png" ]);

            if ($logicalServer->address_ip!=null) {
                foreach($subnetworks as $subnetwork) {
                    foreach(explode(',',$logicalServer->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            array_push($edges, [ "from" => "SUBNETWORK_" . $subnetwork->id, "to" => "LSERVER_" . $logicalServer->id ]);
                            break;
                            }
                    }
                }
            }
        }

        // Logical Servers - Physical Servers
        $joins = DB::table('logical_server_physical_server')->select("physical_server_id","logical_server_id")->get();
        foreach ($joins as $join) {
            array_push($edges, [ "from" => "PSERVER_" . $join->physical_server_id, "to" => "LSERVER_" . $join->logical_server_id ]);
        }
        // Certificates
        $certificates = DB::table("certificates")->select("id","name")->get();
        foreach ($certificates as $certificate) {
            array_push($nodes, [ "id" => "CERT_" . $certificate->id, "label" => $certificate->name, "image" => "/images/certificate.png" ]);
        }
        // certificate_logical_server
        $joins = DB::table('certificate_logical_server')->select("certificate_id","logical_server_id")->get();
        foreach ($joins as $join) {
            array_push($edges, [ "from" => "CERT_" . $join->certificate_id, "to" => "LSERVER_" . $join->logical_server_id ]);
        }


        // ---------------------------------------------------
        // Application view
        // ---------------------------------------------------
        // Application Blocks
        $applicationBlocks = DB::table("application_blocks")->select("id","name")->get();
        foreach ($applicationBlocks as $applicationBlock) {
            array_push($nodes, [ "id" => "BLOCK_" . $applicationBlock->id, "label" => $applicationBlock->name, "image" => "/images/applicationblock.png" ]);
        }
        // Applications
        $applications = DB::table("m_applications")->select("id","name","application_block_id")->get();
        foreach ($applications as $application) {
            array_push($nodes, [ "id" => "APP_" . $application->id, "label" => $application->name, "image" => "/images/application.png" ]);
            if ($application->application_block_id!=null)
                array_push($edges, [ "from" => "BLOCK_" . $application->application_block_id, "to" => "APP_" . $application->id ]);
        }
        // logical_server_m_application
        $joins = DB::table('logical_server_m_application')->select("m_application_id","logical_server_id")->get();
        foreach ($joins as $join) {
            array_push($edges, [ "from" => "APP_" . $join->m_application_id, "to" => "LSERVER_" . $join->logical_server_id ]);
        }
        // certificate_m_application
        $joins = DB::table('certificate_m_application')->select("m_application_id","certificate_id")->get();
        foreach ($joins as $join) {
            array_push($edges, [ "from" => "APP_" . $join->m_application_id, "to" => "CERT_" . $join->certificate_id ]);
        }
        // Databases
        $databases = DB::table("databases")->select("id","name")->get();
        foreach ($databases as $database) {
            array_push($nodes, [ "id" => "DATABASE_" . $database->id, "label" => $database->name, "image" => "/images/database.png" ]);
        }
        // database_m_application
        $joins = DB::table('database_m_application')->select("m_application_id","database_id")->get();
        foreach ($joins as $join) {
            array_push($edges, [ "from" => "APP_" . $join->m_application_id, "to" => "DATABASE_" . $join->database_id ]);
        }

    return view('admin/reports/explore', compact('nodes','edges'));
    }

}
