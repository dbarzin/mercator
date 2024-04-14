<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LogicalFlow;
use App\Router;
use App\Subnetwork;
use Illuminate\Support\Facades\DB;

class ExplorerController extends Controller
{
    public function explore()
    {
        $nodes = [];
        $edges = [];

        // ---------------------------------------------------
        // Physical view - 6
        // ---------------------------------------------------
        // SITES
        $sites = DB::table('sites')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($sites as $site) {
            $this->addNode($nodes, 6, $this->formatId('SITE_', $site->id), $site->name, '/images/site.png', 'sites');
            // link to build
        }
        // BUILDINGS
        $buildings = DB::table('buildings')->select('id', 'name', 'site_id')->whereNull('deleted_at')->get();
        foreach ($buildings as $building) {
            $this->addNode($nodes, 6, $this->formatId('BUILDING_', $building->id), $building->name, '/images/building.png', 'buildings');
            if ($building->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('BUILDING_', $building->id), $this->formatId('SITE_', $building->site_id));
            }
        }
        // Bay
        $bays = DB::table('bays')->select('id', 'name', 'room_id')->whereNull('deleted_at')->get();
        foreach ($bays as $bay) {
            $this->addNode($nodes, 6, $this->formatId('BAY_', $bay->id), $bay->name, '/images/bay.png', 'bays');
            if ($bay->room_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('BAY_', $bay->id), $this->formatId('BUILDING_', $bay->room_id));
            }
        }
        // Physical Server
        $physicalServers = DB::table('physical_servers')->select('id', 'name', 'bay_id', 'cluster_id')->whereNull('deleted_at')->get();
        foreach ($physicalServers as $physicalServer) {
            $this->addNode($nodes, 6, $this->formatId('PSERVER_', $physicalServer->id), $physicalServer->name, '/images/server.png', 'physical-servers');
            if ($physicalServer->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PSERVER_', $physicalServer->id), $this->formatId('BAY_', $physicalServer->bay_id));
            }
            if ($physicalServer->cluster_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PSERVER_', $physicalServer->id), $this->formatId('CLUSTER_', $physicalServer->cluster_id));
            }
        }
        // Workstation
        $workstations = DB::table('workstations')->select('id', 'name', 'building_id', 'site_id')->whereNull('deleted_at')->get();
        foreach ($workstations as $workstation) {
            $this->addNode($nodes, 6, $this->formatId('WORK_', $workstation->id), $workstation->name, '/images/workstation.png', 'workstations');
            if ($workstation->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WORK_', $workstation->id), $this->formatId('BUILDING_', $workstation->building_id));
            } elseif ($workstation->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WORK_', $workstation->id), $this->formatId('SITE_', $workstation->site_id));
            }
        }
        // physical_switches
        $switches = DB::table('physical_switches')->select('id', 'name', 'bay_id', 'building_id', 'site_id')->whereNull('deleted_at')->get();
        foreach ($switches as $switch) {
            $this->addNode($nodes, 6, $this->formatId('SWITCH_', $switch->id), $switch->name, '/images/switch.png', 'physical-switches');
            if ($switch->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SWITCH_', $switch->id), $this->formatId('BAY_', $switch->bay_id));
            } elseif ($switch->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SWITCH_', $switch->id), $this->formatId('BUILDING_', $switch->building_id));
            } elseif ($switch->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SWITCH_', $switch->id), $this->formatId('SITE_', $switch->site_id));
            }
        }

        // Physical routers
        $routers = DB::table('physical_routers')->select('id', 'name', 'bay_id', 'building_id', 'site_id')->whereNull('deleted_at')->get();
        foreach ($routers as $router) {
            $this->addNode($nodes, 6, $this->formatId('PROUTER_', $router->id), $router->name, '/images/router.png', 'physical-routers');
            if ($router->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PROUTER_', $router->id), $this->formatId('BAY_', $router->bay_id));
            } elseif ($router->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PROUTER_', $router->id), $this->formatId('BUILDING_', $router->building_id));
            } elseif ($router->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PROUTER_', $router->id), $this->formatId('SITE_', $router->site_id));
            }
        }
        // Physical security devices
        $securityDevices = DB::table('physical_security_devices')->select('id', 'name', 'bay_id', 'site_id', 'building_id')->whereNull('deleted_at')->get();
        foreach ($securityDevices as $securityDevice) {
            $this->addNode($nodes, 6, $this->formatId('SECURITY_', $securityDevice->id), $securityDevice->name, '/images/security.png', 'physical-security-devices');
            if ($securityDevice->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SECURITY_', $securityDevice->id), $this->formatId('BAY_', $securityDevice->bay_id));
            } elseif ($securityDevice->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SECURITY_', $securityDevice->id), $this->formatId('BUILDING_', $securityDevice->building_id));
            } elseif ($securityDevice->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SECURITY_', $securityDevice->id), $this->formatId('SITE_', $securityDevice->site_id));
            }
        }

        // peripherals
        $peripherals = DB::table('peripherals')->select('id', 'name', 'bay_id', 'site_id', 'building_id', 'provider_id')->whereNull('deleted_at')->get();
        foreach ($peripherals as $peripheral) {
            $this->addNode($nodes, 6, $this->formatId('PERIF_', $peripheral->id), $peripheral->name, '/images/peripheral.png', 'peripherals');
            if ($peripheral->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PERIF_', $peripheral->id), $this->formatId('BAY_', $peripheral->bay_id));
            } elseif ($peripheral->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PERIF_', $peripheral->id), $this->formatId('BUILDING_', $peripheral->building_id));
            } elseif ($peripheral->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PERIF_', $peripheral->id), $this->formatId('SITE_', $peripheral->site_id));
            }
            if ($peripheral->provider_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PERIF_', $peripheral->id), $this->formatId('ENTITY_', $peripheral->provider_id));
            }
        }

        // m_application_peripheral
        $joins = DB::table('m_application_peripheral')->select('m_application_id', 'peripheral_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('PERIF_', $join->peripheral_id));
        }

        // Storage devices
        $storageDevices = DB::table('storage_devices')->select('id', 'name', 'bay_id', 'physical_switch_id')->whereNull('deleted_at')->get();
        foreach ($storageDevices as $storageDevice) {
            $this->addNode($nodes, 6, $this->formatId('STORAGE_', $storageDevice->id), $storageDevice->name, '/images/storagedev.png', 'storage-devices');
            if ($storageDevice->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('STORAGE_', $storageDevice->id), $this->formatId('BAY_', $storageDevice->bay_id));
            }
        }

        // Wifi terminals
        $wifiTerminals = DB::table('wifi_terminals')->select('id', 'name', 'site_id', 'building_id')->whereNull('deleted_at')->get();
        foreach ($wifiTerminals as $wifiTerminal) {
            $this->addNode($nodes, 6, $this->formatId('WIFI_', $wifiTerminal->id), $wifiTerminal->name, '/images/wifi.png', 'wifi-terminals');
            if ($wifiTerminal->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WIFI_', $wifiTerminal->id), $this->formatId('BUILDING_', $wifiTerminal->building_id));
            } elseif ($wifiTerminal->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WIFI_', $wifiTerminal->id), $this->formatId('SITE_', $wifiTerminal->site_id));
            }
        }

        // PhysicalLink
        $links = DB::table('physical_links')->whereNull('deleted_at')->get();
        foreach ($links as $link) {
            if ($link->peripheral_src_id !== null) {
                $src_id = 'PERIF_' . $link->peripheral_src_id;
            } elseif ($link->phone_src_id !== null) {
                $src_id = 'PHONE_' . $link->phone_src_id;
            } elseif ($link->physical_router_src_id !== null) {
                $src_id = 'PROUTER_' . $link->physical_router_src_id;
            } elseif ($link->physical_security_device_src_id !== null) {
                $src_id = 'SECURITY_' . $link->physical_security_device_src_id;
            } elseif ($link->physical_server_src_id !== null) {
                $src_id = 'PSERVER_' . $link->physical_server_src_id;
            } elseif ($link->physical_switch_src_id !== null) {
                $src_id = 'SWITCH_' . $link->physical_switch_src_id;
            } elseif ($link->storage_device_src_id !== null) {
                $src_id = 'STORAGE_' . $link->storage_device_src_id;
            } elseif ($link->wifi_terminal_src_id !== null) {
                $src_id = 'WIFI_' . $link->wifi_terminal_src_id;
            } elseif ($link->workstation_src_id !== null) {
                $src_id = 'WORK_' . $link->workstation_src_id;
            } elseif ($link->logical_server_src_id !== null) {
                $src_id = 'LSERVER_' . $link->logical_server_src_id;
            } elseif ($link->network_switch_src_id !== null) {
                $src_id = 'LSWITCH_' . $link->network_switch_src_id;
            } elseif ($link->router_src_id !== null) {
                $src_id = 'ROUTER_' . $link->router_src_id;
            } else {
                continue;
            }

            if ($link->peripheral_dest_id !== null) {
                $dest_id = 'PERIF_' . $link->peripheral_dest_id;
            } elseif ($link->phone_dest_id !== null) {
                $dest_id = 'PHONE_' . $link->phone_dest_id;
            } elseif ($link->physical_router_dest_id !== null) {
                $dest_id = 'PROUTER_' . $link->physical_router_dest_id;
            } elseif ($link->physical_security_device_dest_id !== null) {
                $dest_id = 'SECURITY_' . $link->physical_security_device_dest_id;
            } elseif ($link->physical_server_dest_id !== null) {
                $dest_id = 'PSERVER_' . $link->physical_server_dest_id;
            } elseif ($link->physical_switch_dest_id !== null) {
                $dest_id = 'SWITCH_' . $link->physical_switch_dest_id;
            } elseif ($link->storage_device_dest_id !== null) {
                $dest_id = 'STORAGE_' . $link->storage_device_dest_id;
            } elseif ($link->wifi_terminal_dest_id !== null) {
                $dest_id = 'WIFI_' . $link->wifi_terminal_dest_id;
            } elseif ($link->workstation_dest_id !== null) {
                $dest_id = 'WORK_' . $link->workstation_dest_id;
            } elseif ($link->logical_server_dest_id !== null) {
                $dest_id = 'LSERVER_' . $link->logical_server_dest_id;
            } elseif ($link->network_switch_dest_id !== null) {
                $dest_id = 'LSWITCH_' . $link->network_switch_dest_id;
            } elseif ($link->router_dest_id !== null) {
                $dest_id = 'ROUTER_' . $link->router_dest_id;
            } else {
                continue;
            }

            $this->addPhysicalLinkEdge($edges, $src_id, $dest_id);
        }

        // ---------------------------------------------------
        // Logical view - 5
        // ---------------------------------------------------
        // networks
        $networks = DB::table('networks')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($networks as $network) {
            $this->addNode($nodes, 5, $this->formatId('NETWORK_', $network->id), $network->name, '/images/cloud.png', 'networks');
        }

        // lans
        $lans = DB::table('lans')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($lans as $lan) {
            $this->addNode($nodes, 5, $this->formatId('LAN_', $lan->id), $lan->name, '/images/vlan.png', 'lans');
        }

        // mans
        $mans = DB::table('mans')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($mans as $man) {
            $this->addNode($nodes, 5, $this->formatId('MAN_', $man->id), $man->name, '/images/vlan.png', 'mans');
        }

        // wans
        $wans = DB::table('wans')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($wans as $wan) {
            $this->addNode($nodes, 5, $this->formatId('WAN_', $wan->id), $wan->name, '/images/vlan.png', 'wans');
        }

        // vlans
        $vlans = DB::table('vlans')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($vlans as $vlan) {
            $this->addNode($nodes, 5, $this->formatId('VLAN_', $vlan->id), $vlan->name, '/images/vlan.png', 'vlans');
        }

        // man_wan
        $joins = DB::table('man_wan')->select('man_id', 'wan_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('MAN_', $join->man_id), $this->formatId('WAN_', $join->wan_id));
        }

        // lan_man
        $joins = DB::table('lan_man')->select('lan_id', 'man_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('LAN_', $join->lan_id), $this->formatId('MAN_', $join->man_id));
        }

        // lan_wan
        $joins = DB::table('lan_wan')->select('lan_id', 'wan_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('LAN_', $join->lan_id), $this->formatId('WAN_', $join->wan_id));
        }

        // Subnetworks
        $subnetworks = Subnetwork::all();
        foreach ($subnetworks as $subnetwork) {
            $this->addNode($nodes, 5, $this->formatId('SUBNETWORK_', $subnetwork->id), $subnetwork->name, '/images/network.png', 'subnetworks', $subnetwork->address);
            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('NETWORK_', $subnetwork->network_id));
            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('VLAN_', $subnetwork->vlan_id));
            if ($subnetwork->address_ip !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $logicalServer->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('LSERVER_', $logicalServer->id));
                            break;
                        }
                    }
                }
            }
        }

        // Logical Routers
        $logicalRouters = Router::All();
        foreach ($logicalRouters as $logicalRouter) {
            $this->addNode($nodes, 5, $this->formatId('ROUTER_', $logicalRouter->id), $logicalRouter->name, '/images/router.png', 'routers');
            if ($logicalRouter->getAttribute('ip_addresses') !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $logicalRouter->getAttribute('ip_addresses')) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('ROUTER_', $logicalRouter->id));
                            break;
                        }
                    }
                }
            }
        }

        // DHCP Servers
        $dhcp_servers = DB::table('dhcp_servers')->select('id', 'name', 'address_ip')->whereNull('deleted_at')->get();
        foreach ($dhcp_servers as $dhcp_server) {
            $this->addNode($nodes, 5, $this->formatId('DHCPS_', $dhcp_server->id), $dhcp_server->name, '/images/lserver.png', 'dhcp-servers');
            if ($dhcp_server->address_ip !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $dhcp_server->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('DHCPS_', $dhcp_server->id));
                            break;
                        }
                    }
                }
            }
        }

        // DNS Servers
        $dns_servers = DB::table('dnsservers')->select('id', 'name', 'address_ip')->whereNull('deleted_at')->get();
        foreach ($dns_servers as $dns_server) {
            $this->addNode($nodes, 5, $this->formatId('DNSS_', $dns_server->id), $dns_server->name, '/images/lserver.png', 'dnsservers');
            if ($dns_server->address_ip !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $dns_server->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('DNSS_', $dns_server->id));
                            break;
                        }
                    }
                }
            }
        }

        // Clusters
        $clusters = DB::table('clusters')->select('id', 'name')->get();
        foreach ($clusters as $cluster) {
            $this->addNode($nodes, 5, $this->formatId('CLUSTER_', $cluster->id), $cluster->name, '/images/cluster.png', 'clusters');
        }

        // Logical Servers
        $logicalServers = DB::table('logical_servers')->select('id', 'name', 'address_ip', 'cluster_id')->get();
        foreach ($logicalServers as $logicalServer) {
            $this->addNode($nodes, 5, $this->formatId('LSERVER_', $logicalServer->id), $logicalServer->name, '/images/lserver.png', 'logical-servers');
            if ($logicalServer->cluster_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('LSERVER_', $logicalServer->id), $this->formatId('CLUSTER_', $logicalServer->cluster_id));
            }
            if ($logicalServer->address_ip !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $logicalServer->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('LSERVER_', $logicalServer->id));
                            break;
                        }
                    }
                }
            }
        }

        // Logical Servers - Physical Servers
        $joins = DB::table('logical_server_physical_server')->select('physical_server_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('PSERVER_', $join->physical_server_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }

        // Logical Flows xxxxxxxxxxxxxxxxxxxxxxx
        $flows = LogicalFlow::All();
        foreach ($flows as $flow) {
            // Get sources
            $sources = [];
            foreach ($logicalServers as $server) {
                foreach (explode(',', $server->address_ip) as $ip) {
                    if ($flow->isSource($ip)) {
                        array_push($sources, $server->id);
                    }
                }
            }
            // Get destinations
            $destinations = [];
            foreach ($logicalServers as $server) {
                foreach (explode(',', $server->address_ip) as $ip) {
                    if ($flow->isDestination($ip)) {
                        array_push($destinations, $server->id);
                    }
                }
            }

            // Add source <-> destination flows
            foreach ($sources as $source) {
                foreach ($destinations as $destination) {
                    $this->addFluxEdge($edges, $flow->name, false, $this->formatId('LSERVER_', $source), $this->formatId('LSERVER_', $destination));
                }
            }
        }

        // Certificates
        $certificates = DB::table('certificates')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($certificates as $certificate) {
            $this->addNode($nodes, 5, $this->formatId('CERT_', $certificate->id), $certificate->name, '/images/certificate.png', 'certificates');
        }
        // certificate_logical_server
        $joins = DB::table('certificate_logical_server')->select('certificate_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, 5, $this->formatId('CERT_', $join->certificate_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }

        // ---------------------------------------------------
        // Administration view - 4
        // ---------------------------------------------------
        // Zones
        $zoneAdmins = DB::table('zone_admins')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($zoneAdmins as $zone) {
            $this->addNode($nodes, 4, $this->formatId('ZONE_', $zone->id), $zone->name, '/images/zoneadmin.png', 'zone_admins');
        }
        // Annuaires
        $annuaires = DB::table('annuaires')->select('id', 'name', 'zone_admin_id')->whereNull('deleted_at')->get();
        foreach ($annuaires as $annuaire) {
            $this->addNode($nodes, 4, $this->formatId('ANNUAIRE_', $annuaire->id), $annuaire->name, '/images/annuaire.png', 'annuaires');
            $this->addLinkEdge($edges, $this->formatId('ANNUAIRE_', $annuaire->id), $this->formatId('ZONE_', $annuaire->zone_admin_id));
        }
        // Forest
        $forests = DB::table('forest_ads')->select('id', 'name', 'zone_admin_id')->whereNull('deleted_at')->get();
        foreach ($forests as $forest) {
            $this->addNode($nodes, 4, $this->formatId('FOREST_', $forest->id), $forest->name, '/images/ldap.png', 'forests_ads');
            $this->addLinkEdge($edges, $this->formatId('FOREST_', $forest->id), $this->formatId('ZONE_', $forest->zone_admin_id));
        }
        // Domain
        $domains = DB::table('domaine_ads')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($domains as $domain) {
            $this->addNode($nodes, 4, $this->formatId('DOMAIN_', $domain->id), $domain->name, '/images/domain.png', 'domaine_ads');
        }
        // domaine_ad_forest_ad
        $joins = DB::table('domaine_ad_forest_ad')->select('forest_ad_id', 'domaine_ad_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('FOREST_', $join->forest_ad_id), $this->formatId('DOMAIN_', $join->domaine_ad_id));
        }

        // ---------------------------------------------------
        // Application view - 3
        // ---------------------------------------------------
        // Application Blocks
        $applicationBlocks = DB::table('application_blocks')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($applicationBlocks as $applicationBlock) {
            $this->addNode($nodes, 3, $this->formatId('BLOCK_', $applicationBlock->id), $applicationBlock->name, '/images/applicationblock.png', 'application-blocks');
        }
        // Applications
        $applications = DB::table('m_applications')->select('id', 'name', 'application_block_id')->whereNull('deleted_at')->get();
        foreach ($applications as $application) {
            $this->addNode($nodes, 3, $this->formatId('APP_', $application->id), $application->name, '/images/application.png', 'applications');
            $this->addLinkEdge($edges, $this->formatId('BLOCK_', $application->application_block_id), $this->formatId('APP_', $application->id));
        }
        // m_application_physical_server
        $joins = DB::table('m_application_physical_server')->select('m_application_id', 'physical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('PSERVER_', $join->physical_server_id));
        }
        // m_application_workstation
        $joins = DB::table('m_application_workstation')->select('m_application_id', 'workstation_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('WORK_', $join->workstation_id));
        }
        // m_application_process
        $joins = DB::table('m_application_process')->select('m_application_id', 'process_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('PROCESS_', $join->process_id));
        }
        // logical_server_m_application
        $joins = DB::table('logical_server_m_application')->select('m_application_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }
        // Application Services
        $services = DB::table('application_services')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($services as $service) {
            $this->addNode($nodes, 3, $this->formatId('SERV_', $service->id), $service->name, '/images/service.png', 'application-services');
        }
        // application_service_m_application
        $joins = DB::table('application_service_m_application')->select('m_application_id', 'application_service_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('SERV_', $join->application_service_id));
        }
        // Application Modules
        $modules = DB::table('application_modules')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($modules as $module) {
            $this->addNode($nodes, 3, $this->formatId('MOD_', $module->id), $module->name, '/images/applicationmodule.png', 'modules');
        }
        // application_module_application_service
        $joins = DB::table('application_module_application_service')->select('application_module_id', 'application_service_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('MOD_', $join->application_module_id), $this->formatId('SERV_', $join->application_service_id));
        }

        // certificate_m_application
        $joins = DB::table('certificate_m_application')->select('m_application_id', 'certificate_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('CERT_', $join->certificate_id));
        }
        // Databases
        $databases = DB::table('databases')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($databases as $database) {
            $this->addNode($nodes, 3, $this->formatId('DATABASE_', $database->id), $database->name, '/images/database.png', 'databases');
        }
        // database_m_application
        $joins = DB::table('database_m_application')->select('m_application_id', 'database_id')->get();
        foreach ($joins as $join) {
            $this->addFluxEdge($edges, null, false, $this->formatId('APP_', $join->m_application_id), $this->formatId('DATABASE_', $join->database_id));
        }

        // database_logical_server
        $joins = DB::table('database_logical_server')->select('database_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('LSERVER_', $join->logical_server_id), $this->formatId('DATABASE_', $join->database_id));
        }

        // Fluxes
        $fluxes = DB::table('fluxes')->whereNull('deleted_at')->get();
        foreach ($fluxes as $flux) {
            if ($flux->application_source_id !== null) {
                $src_id = 'APP_' . $flux->application_source_id;
            } elseif ($flux->service_source_id !== null) {
                $src_id = 'SERV_' . $flux->service_source_id;
            } elseif ($flux->module_source_id !== null) {
                $src_id = 'MOD_' . $flux->module_source_id;
            } elseif ($flux->database_source_id !== null) {
                $src_id = 'DATABASE_' . $flux->database_source_id;
            } else {
                continue;
            }

            if ($flux->application_dest_id !== null) {
                $dest_id = 'APP_' . $flux->application_dest_id;
            } elseif ($flux->service_dest_id !== null) {
                $dest_id = 'SERV_' . $flux->service_dest_id;
            } elseif ($flux->module_dest_id !== null) {
                $dest_id = 'MOD_' . $flux->module_dest_id;
            } elseif ($flux->database_dest_id !== null) {
                $dest_id = 'DATABASE_' . $flux->database_dest_id;
            } else {
                continue;
            }

            $this->addFluxEdge($edges, $flux->name, $flux->bidirectional, $src_id, $dest_id);
        }

        // ---------------------------------------------------
        // Information System - 2
        // ---------------------------------------------------
        // Information
        $informations = DB::table('information')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($informations as $information) {
            $this->addNode($nodes, 2, $this->formatId('INFO_', $information->id), $information->name, '/images/information.png', 'information');
        }
        // database_information
        $joins = DB::table('database_information')->select('information_id', 'database_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('INFO_', $join->information_id), $this->formatId('DATABASE_', $join->database_id));
        }
        // process
        $processes = DB::table('processes')->select('id', 'identifiant', 'macroprocess_id')->whereNull('deleted_at')->get();
        foreach ($processes as $process) {
            $this->addNode($nodes, 2, $this->formatId('PROCESS_', $process->id), $process->identifiant, '/images/process.png', 'processes');
            $this->addLinkEdge($edges, $this->formatId('PROCESS_', $process->id), $this->formatId('MACROPROCESS_', $process->macroprocess_id));
        }
        // information_process
        $joins = DB::table('information_process')->select('information_id', 'process_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('INFO_', $join->information_id), $this->formatId('PROCESS_', $join->process_id));
        }
        // macro_processuses
        $macro_processuses = DB::table('macro_processuses')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($macro_processuses as $macro_process) {
            $this->addNode($nodes, 2, $this->formatId('MACROPROCESS_', $macro_process->id), $macro_process->name, '/images/macroprocess.png', 'macro-processuses');
        }

        // Activities
        $activities = DB::table('activities')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($activities as $activity) {
            $this->addNode($nodes, 2, $this->formatId('ACTIVITY_', $activity->id), $activity->name, '/images/activity.png', 'activities');
        }
        // activity_process
        $joins = DB::table('activity_process')->select('activity_id', 'process_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('ACTIVITY_', $join->activity_id), $this->formatId('PROCESS_', $join->process_id));
        }

        // Operations
        $operations = DB::table('operations')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($operations as $operation) {
            $this->addNode($nodes, 2, $this->formatId('OPERATION_', $operation->id), $operation->name, '/images/operation.png', 'operations');
        }

        // activity_operation
        $joins = DB::table('activity_operation')->select('activity_id', 'operation_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('ACTIVITY_', $join->activity_id), $this->formatId('OPERATION_', $join->operation_id));
        }

        // Tasks
        $tasks = DB::table('tasks')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($tasks as $task) {
            $this->addNode($nodes, 2, $this->formatId('TASK_', $task->id), $task->name, '/images/task.png', 'tasks');
        }

        // operation_task
        $joins = DB::table('operation_task')->select('operation_id', 'task_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('OPERATION_', $join->operation_id), $this->formatId('TASK_', $join->task_id));
        }

        // Actors
        $actors = DB::table('actors')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($actors as $actor) {
            $this->addNode($nodes, 2, $this->formatId('ACTOR_', $actor->id), $actor->name, '/images/actor.png', 'actors');
        }

        // actor_operation
        $joins = DB::table('actor_operation')->select('actor_id', 'operation_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('ACTOR_', $join->actor_id), $this->formatId('OPERATION_', $join->operation_id));
        }
        // ---------------------------------------------------
        // Ecosystem - 1
        // ---------------------------------------------------
        // Entities
        $entities = DB::table('entities')->select('id', 'name', 'parent_entity_id')->whereNull('deleted_at')->get();
        foreach ($entities as $entity) {
            $this->addNode($nodes, 1, $this->formatId('ENTITY_', $entity->id), $entity->name, '/images/entity.png', 'entities');
            if ($entity->parent_entity_id !== null) {
                $this->addFluxEdge($edges, null, false, $this->formatId('ENTITY_', $entity->id), $this->formatId('ENTITY_', $entity->parent_entity_id));
            }
        }

        // Relations
        $relations = DB::table('relations')->select('id', 'name', 'source_id', 'destination_id')->whereNull('deleted_at')->get();
        foreach ($relations as $relation) {
            $this->addNode($nodes, 1, $this->formatId('REL_', $relation->id), $relation->name, '/images/relation.png', 'relations');
            $this->addLinkEdge($edges, $this->formatId('REL_', $relation->id), $this->formatId('ENTITY_', $relation->source_id));
            $this->addLinkEdge($edges, $this->formatId('REL_', $relation->id), $this->formatId('ENTITY_', $relation->destination_id));
        }
        // entity_process
        $joins = DB::table('entity_process')->select('entity_id', 'process_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('ENTITY_', $join->entity_id), $this->formatId('PROCESS_', $join->process_id));
        }
        // entity_m_application
        $joins = DB::table('entity_m_application')->select('entity_id', 'm_application_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('ENTITY_', $join->entity_id), $this->formatId('APP_', $join->m_application_id));
        }

        return view('admin/reports/explore', compact('nodes', 'edges'));
    }

    private function addNode(&$nodes, $vue, $id, $label, $image, $type, $title = null)
    {
        $data = [ 'vue' => $vue, 'id' => $id,  'label' => $label, 'image' => $image, 'type' => $type];
        if ($title !== null) {
            $data['title'] = $title;
        }
        array_push($nodes, $data);
    }

    private function addLinkEdge(&$edges, $from, $to)
    {
        $this->addEdge($edges, null, false, $from, $to, 'LINK');
    }

    private function addPhysicalLinkEdge(&$edges, $from, $to)
    {
        $this->addEdge($edges, null, false, $from, $to, 'CABLE');
    }

    private function addFluxEdge(&$edges, $name, $bidir, $from, $to)
    {
        $this->addEdge($edges, $name, $bidir, $from, $to, 'FLUX');
    }

    private function addEdge(&$edges, $name, $bidir, $from, $to, $type)
    {
        if ($from !== null && $to !== null) {
            array_push($edges, [ 'name' => $name, 'bidirectional' => $bidir, 'from' => $from, 'to' => $to, 'type' => $type ]);
        }
    }

    private function formatId($prefix, $id)
    {
        if ($id !== null) {
            return $prefix . $id;
        }
        return null;
    }
}
