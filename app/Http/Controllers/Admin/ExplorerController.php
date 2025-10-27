<?php


namespace App\Http\Controllers\Admin;

// Models
use App\Http\Controllers\Controller;
use App\Models\LogicalFlow;
use App\Models\PhysicalLink;
use App\Models\Subnetwork;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExplorerController extends Controller
{
    public function explore(Request $request)
    {
        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        [$nodes, $edges] = $this->getData();

        return view('admin/reports/explore', compact('nodes', 'edges'));
    }

    // TODO : return a JSON in place of nodes[] and edges[]
    // TODO : split me in several private functions by views
    // TODO : check user rights
    public function getData(): array
    {
        $nodes = [];
        $edges = [];

        // Get all subnetworks
        $subnetworks = Subnetwork::all();

        // ---------------------------------------------------
        // Physical view - 6
        // ---------------------------------------------------
        // SITES
        $sites = DB::table('sites')->select('id', 'name', 'icon_id')->whereNull('deleted_at')->get();
        foreach ($sites as $site) {
            $this->addNode(
                $nodes,
                6,
                $this->formatId('SITE_', $site->id),
                $site->name,
                $site->icon_id === null ? '/images/site.png' : "/admin/documents/{$site->icon_id}",
                'sites'
            );
            // link to build
        }
        // BUILDINGS
        $buildings = DB::table('buildings')->select('id', 'name', 'building_id', 'site_id', 'icon_id')->whereNull('deleted_at')->get();
        foreach ($buildings as $building) {
            $this->addNode(
                $nodes,
                6,
                $this->formatId('BUILDING_', $building->id),
                $building->name,
                $building->icon_id === null ? '/images/building.png' : "/admin/documents/{$building->icon_id}",
                'buildings'
            );
            if ($building->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('BUILDING_', $building->id), $this->formatId('BUILDING_', $building->building_id));
            } elseif ($building->site_id !== null) {
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
        $physicalServers = DB::table('physical_servers')->select('id', 'name', 'icon_id', 'bay_id')->whereNull('deleted_at')->get();
        foreach ($physicalServers as $physicalServer) {
            $this->addNode(
                $nodes,
                6,
                $this->formatId('PSERVER_', $physicalServer->id),
                $physicalServer->name,
                $physicalServer->icon_id === null ? '/images/server.png' : "/admin/documents/{$physicalServer->icon_id}",
                'physical-servers'
            );
            if ($physicalServer->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PSERVER_', $physicalServer->id), $this->formatId('BAY_', $physicalServer->bay_id));
            }
        }
        // Workstation
        $workstations = DB::table('workstations')->select('id', 'name', 'icon_id', 'address_ip', 'building_id', 'site_id')->whereNull('deleted_at')->get();
        foreach ($workstations as $workstation) {
            $this->addNode(
                $nodes,
                6,
                $this->formatId('WORK_', $workstation->id),
                $workstation->name,
                $workstation->icon_id === null ? '/images/workstation.png' : "/admin/documents/{$workstation->icon_id}",
                'workstations',
                $workstation->address_ip
            );
            if ($workstation->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WORK_', $workstation->id), $this->formatId('BUILDING_', $workstation->building_id));
            } elseif ($workstation->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WORK_', $workstation->id), $this->formatId('SITE_', $workstation->site_id));
            }
            foreach ($subnetworks as $subnetwork) {
                foreach (explode(',', $workstation->address_ip) as $address) {
                    if ($subnetwork->contains($address)) {
                        $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('WORK_', $workstation->id));
                        break;
                    }
                }
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

        // network_switch_physical_switch
        $joins = DB::table('network_switch_physical_switch')->select('network_switch_id', 'physical_switch_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('LSWITCH_', $join->network_switch_id), $this->formatId('SWITCH_', $join->physical_switch_id));
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
        $securityDevices = DB::table('physical_security_devices')->select('id', 'name', 'icon_id', 'address_ip', 'bay_id', 'site_id', 'building_id')->whereNull('deleted_at')->get();
        foreach ($securityDevices as $securityDevice) {
            $this->addNode($nodes,
                6,
                $this->formatId('PSECURITY_', $securityDevice->id),
                $securityDevice->name,
                $securityDevice->icon_id === null ? '/images/security.png' : "/admin/documents/{$securityDevice->icon_id}",
                'physical-security-devices',
                $securityDevice->address_ip);
            if ($securityDevice->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PSECURITY_', $securityDevice->id), $this->formatId('BAY_', $securityDevice->bay_id));
            } elseif ($securityDevice->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PSECURITY_', $securityDevice->id), $this->formatId('BUILDING_', $securityDevice->building_id));
            } elseif ($securityDevice->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PSECURITY_', $securityDevice->id), $this->formatId('SITE_', $securityDevice->site_id));
            }
            foreach ($subnetworks as $subnetwork) {
                foreach (explode(',', $securityDevice->address_ip) as $address) {
                    if ($subnetwork->contains($address)) {
                        $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('PSECURITY_', $securityDevice->id));
                        break;
                    }
                }
            }
        }

        // peripherals
        $peripherals = DB::table('peripherals')->select('id', 'name', 'icon_id', 'address_ip', 'bay_id', 'site_id', 'building_id', 'provider_id')->whereNull('deleted_at')->get();
        foreach ($peripherals as $peripheral) {
            $this->addNode(
                $nodes,
                6,
                $this->formatId('PERIF_', $peripheral->id),
                $peripheral->name,
                $peripheral->icon_id === null ? '/images/peripheral.png' : "/admin/documents/{$peripheral->icon_id}",
                'peripherals',
                $peripheral->address_ip
            );
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
            foreach ($subnetworks as $subnetwork) {
                foreach (explode(',', $peripheral->address_ip) as $address) {
                    if ($subnetwork->contains($address)) {
                        $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('PERIF_', $peripheral->id));
                        break;
                    }
                }
            }
        }

        // m_application_peripheral
        $joins = DB::table('m_application_peripheral')->select('m_application_id', 'peripheral_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('PERIF_', $join->peripheral_id));
        }

        // Phones
        $phones = DB::table('phones')->select('id', 'name', 'address_ip', 'building_id')->whereNull('deleted_at')->get();
        foreach ($phones as $phone) {
            $this->addNode($nodes, 6, $this->formatId('PHONE_', $phone->id), $phone->name, '/images/phone.png', 'phones', $phone->address_ip);
            if ($phone->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('PHONE_', $phone->id), $this->formatId('BUILDING_', $phone->building_id));
            }
            foreach ($subnetworks as $subnetwork) {
                foreach (explode(',', $phone->address_ip) as $address) {
                    if ($subnetwork->contains($address)) {
                        $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('PHONE_', $phone->id));
                        break;
                    }
                }
            }
        }

        // Storage devices
        $storageDevices = DB::table('storage_devices')->select('id', 'name', 'bay_id', 'address_ip')->whereNull('deleted_at')->get();
        foreach ($storageDevices as $storageDevice) {
            $this->addNode($nodes, 6, $this->formatId('STORAGE_', $storageDevice->id), $storageDevice->name, '/images/storagedev.png', 'storage-devices', $storageDevice->address_ip);
            if ($storageDevice->bay_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('STORAGE_', $storageDevice->id), $this->formatId('BAY_', $storageDevice->bay_id));
            }
            foreach ($subnetworks as $subnetwork) {
                foreach (explode(',', $storageDevice->address_ip) as $address) {
                    if ($subnetwork->contains($address)) {
                        $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('STORAGE_', $storageDevice->id));
                        break;
                    }
                }
            }
        }

        // Wifi terminals
        $wifiTerminals = DB::table('wifi_terminals')->select('id', 'name', 'address_ip', 'site_id', 'building_id')->whereNull('deleted_at')->get();
        foreach ($wifiTerminals as $wifiTerminal) {
            $this->addNode($nodes, 6, $this->formatId('WIFI_', $wifiTerminal->id), $wifiTerminal->name, '/images/wifi.png', 'wifi-terminals', $wifiTerminal->address_ip);
            if ($wifiTerminal->building_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WIFI_', $wifiTerminal->id), $this->formatId('BUILDING_', $wifiTerminal->building_id));
            } elseif ($wifiTerminal->site_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('WIFI_', $wifiTerminal->id), $this->formatId('SITE_', $wifiTerminal->site_id));
            }
            foreach ($subnetworks as $subnetwork) {
                foreach (explode(',', $wifiTerminal->address_ip) as $address) {
                    if ($subnetwork->contains($address)) {
                        $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('WIFI_', $wifiTerminal->id));
                        break;
                    }
                }
            }
        }

        // PhysicalLink
        $links = PhysicalLink::All();
        foreach ($links as $link) {
            // Get Source
            $src_id = $link->sourceId();
            if ($src_id === null) {
                continue;
            }
            // Get Destination
            $dest_id = $link->destinationId();
            if ($dest_id === null) {
                continue;
            }
            // Add link
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

        // External connected entities
        $externals = DB::table('external_connected_entities')->select('id', 'name', 'network_id', 'entity_id')->whereNull('deleted_at')->get();
        foreach ($externals as $external) {
            $this->addNode($nodes, 5, $this->formatId('EXT_', $external->id), $external->name, '/images/entity.png', 'external-connected-entities');
            $this->addLinkEdge($edges, $this->formatId('EXT_', $external->id), $this->formatId('NETWORK_', $external->network_id));
            $this->addLinkEdge($edges, $this->formatId('EXT_', $external->id), $this->formatId('ENTITY_', $external->entity_id));
        }

        // Gateways
        $gateways = DB::table('gateways')->select('id', 'name', 'ip')->whereNull('deleted_at')->get();
        foreach ($gateways as $gateway) {
            $this->addNode($nodes, 5, $this->formatId('GW_', $gateway->id), $gateway->name, '/images/gateway.png', 'gateways', $gateway->ip);
        }

        // Subnetworks
        foreach ($subnetworks as $subnetwork) {
            $this->addNode($nodes, 5, $this->formatId('SUBNETWORK_', $subnetwork->id), $subnetwork->name, '/images/network.png', 'subnetworks', $subnetwork->address);
            if ($subnetwork->network_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('NETWORK_', $subnetwork->network_id));
            }
            if ($subnetwork->vlan_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('VLAN_', $subnetwork->vlan_id));
            }
            if ($subnetwork->gateway_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('GW_', $subnetwork->gateway_id));
            }
            /*
             * TODO : fixme
            if ($subnetwork->address_ip !== null) {
                foreach ($logicalServers as $logicalServer) {
                    foreach (explode(',', $logicalServer->address_ip) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('LSERVER_', $logicalServer->id));
                            break;
                        }
                    }
                }
            }
            */
        }

        // Logical Routers
        $logicalRouters = DB::table('routers')->select('id', 'name', 'ip_addresses')->get();
        foreach ($logicalRouters as $logicalRouter) {
            $this->addNode($nodes, 5, $this->formatId('ROUTER_', $logicalRouter->id), $logicalRouter->name, '/images/router.png', 'routers');
            if ($logicalRouter->ip_addresses !== null) {
                foreach ($subnetworks as $subnetwork) {
                    foreach (explode(',', $logicalRouter->ip_addresses) as $address) {
                        if ($subnetwork->contains($address)) {
                            $this->addLinkEdge($edges, $this->formatId('SUBNETWORK_', $subnetwork->id), $this->formatId('ROUTER_', $logicalRouter->id));
                            break;
                        }
                    }
                }
            }
        }

        // physical_router_router
        $joins = DB::table('physical_router_router')->select('router_id', 'physical_router_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('ROUTER_', $join->router_id), $this->formatId('PROUTER_', $join->physical_router_id));
        }

        // Logical Switches
        $networkSwitches = DB::table('network_switches')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($networkSwitches as $networkSwitch) {
            $this->addNode($nodes, 5, $this->formatId('LSWITCH_', $networkSwitch->id), $networkSwitch->name, '/images/switch.png', 'switches');
        }

        // Logical Security Devices
        $logical_security_devices = DB::table('security_devices')->select('id', 'name','icon_id')->whereNull('deleted_at')->get();
        foreach ($logical_security_devices as $securityDevice) {
            $this->addNode(
                $nodes,
                5, $this->formatId('LSECURITY_', $securityDevice->id),
                $securityDevice->name,
                $securityDevice->icon_id === null ? '/images/securitydevice.png' : "/admin/documents/{$securityDevice->icon_id}",
                'security-devices');
        }

        // Logical Security Devices - Physical Security Device
        $joins = DB::table('physical_security_device_security_device')->select('security_device_id', 'physical_security_device_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('LSECURITY_', $join->security_device_id), $this->formatId('PSECURITY_', $join->physical_security_device_id));
        }

        // DHCP Servers
        $dhcp_servers = DB::table('dhcp_servers')->select('id', 'name', 'address_ip')->whereNull('deleted_at')->get();
        foreach ($dhcp_servers as $dhcp_server) {
            $this->addNode($nodes, 5, $this->formatId('DHCPS_', $dhcp_server->id), $dhcp_server->name, '/images/lserver.png', 'dhcp-servers', $dhcp_server->address_ip);
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
            $this->addNode($nodes, 5, $this->formatId('DNSS_', $dns_server->id), $dns_server->name, '/images/lserver.png', 'dnsservers', $dns_server->address_ip);
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
        $clusters = DB::table('clusters')->select('id', 'name', 'icon_id')->whereNull('deleted_at')->get();
        foreach ($clusters as $cluster) {
            $this->addNode(
                $nodes,
                5,
                $this->formatId('CLUSTER_', $cluster->id),
                $cluster->name,
                $cluster->icon_id === null ? '/images/cluster.png' : "/admin/documents/{$cluster->icon_id}",
                'clusters'
            );
        }

        // Cluster - Logical Servers
        $joins = DB::table('cluster_logical_server')->select('cluster_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('CLUSTER_', $join->cluster_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }

        // Cluster - Logical Servers
        $joins = DB::table('cluster_physical_server')->select('cluster_id', 'physical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('CLUSTER_', $join->cluster_id), $this->formatId('PSERVER_', $join->physical_server_id));
        }

        // Cluster - Routers
        $joins = DB::table('cluster_router')->select('cluster_id', 'router_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('CLUSTER_', $join->cluster_id), $this->formatId('ROUTER_', $join->router_id));
        }

        // Containers
        $containers = DB::table('containers')->select('id', 'name', 'icon_id')->whereNull('deleted_at')->get();
        foreach ($containers as $container) {
            $this->addNode(
                $nodes,
                5,
                $this->formatId('CONT_', $container->id),
                $container->name,
                $container->icon_id === null ? '/images/container.png' : "/admin/documents/{$container->icon_id}",
                'containers'
            );
        }

        // Container - Logical Servers
        $joins = DB::table('container_logical_server')->select('container_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('CONT_', $join->container_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }

        // Container - Applications
        $joins = DB::table('container_m_application')->select('container_id', 'm_application_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('CONT_', $join->container_id), $this->formatId('APP_', $join->m_application_id));
        }

        // Container - Databases
        $joins = DB::table('container_database')->select('container_id', 'database_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('CONT_', $join->container_id), $this->formatId('DATABASE_', $join->database_id));
        }

        // Logical Servers
        $logicalServers = DB::table('logical_servers')->select('id', 'name', 'icon_id', 'address_ip', 'domain_id')->get();
        foreach ($logicalServers as $logicalServer) {
            $this->addNode(
                $nodes,
                5,
                $this->formatId('LSERVER_', $logicalServer->id),
                $logicalServer->name,
                $logicalServer->icon_id === null ? '/images/lserver.png' : "/admin/documents/{$logicalServer->icon_id}",
                'logical-servers'
            );
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
            if ($logicalServer->domain_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('LSERVER_', $logicalServer->id), $this->formatId('DOMAIN_', $logicalServer->domain_id));
            }
        }

        // Logical Servers - Physical Servers
        $joins = DB::table('logical_server_physical_server')->select('physical_server_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('PSERVER_', $join->physical_server_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }

        // Logical Flows
        $flows = LogicalFlow::All();
        foreach ($flows as $flow) {
            // \Log::Debug('flow: '.$flow->name);
            // Get sources
            $sources = [];
            if ($flow->source_ip_range !== null) {
                foreach ($logicalServers as $server) {
                    foreach (explode(',', $server->address_ip) as $ip) {
                        if ($flow->isSource($ip)) {
                            array_push($sources, $this->formatId('LSERVER_', $server->id));
                        }
                    }
                }
                foreach ($workstations as $workstation) {
                    if ($flow->isSource($workstation->address_ip)) {
                        array_push($sources, $this->formatId('WORK_', $workstation->id));
                    }
                }
                foreach ($peripherals as $peripheral) {
                    if ($flow->isSource($peripheral->address_ip)) {
                        array_push($sources, $this->formatId('PERIF_', $peripheral->id));
                    }
                }
                // TODO: other objects
            } elseif ($flow->sourceId() !== null) {
                array_push($sources, $flow->sourceId());
            }

            // Get destinations
            $destinations = [];
            if ($flow->dest_ip_range !== null) {
                foreach ($logicalServers as $server) {
                    foreach (explode(',', $server->address_ip) as $ip) {
                        if ($flow->isDestination($ip)) {
                            array_push($destinations, $this->formatId('LSERVER_', $server->id));
                        }
                    }
                }
                foreach ($workstations as $workstation) {
                    if ($flow->isDestination($workstation->address_ip)) {
                        array_push($destinations, $this->formatId('WORK_', $workstation->id));
                    }
                }
                foreach ($peripherals as $peripheral) {
                    if ($flow->isDestination($peripheral->address_ip)) {
                        array_push($sources, $this->formatId('PERIF_', $peripheral->id));
                    }
                }
                // TODO: other objects
            } elseif ($flow->destinationId() !== null) {
                array_push($destinations, $flow->destinationId());
            }

            // Add source <-> destination flows
            foreach ($sources as $source) {
                foreach ($destinations as $destination) {
                    // \Log::Debug($source . ' -> ' . $destination);
                    $this->addFluxEdge($edges, $flow->name, false, $source, $destination);
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
            $this->addLinkEdge($edges, $this->formatId('CERT_', $join->certificate_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }

        // ---------------------------------------------------
        // Administration view - 4
        // ---------------------------------------------------
        // Zones
        $zoneAdmins = DB::table('zone_admins')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($zoneAdmins as $zone) {
            $this->addNode($nodes, 4, $this->formatId('ZONE_', $zone->id), $zone->name, '/images/zoneadmin.png', 'zone-admins');
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
            $this->addNode($nodes, 4, $this->formatId('FOREST_', $forest->id), $forest->name, '/images/ldap.png', 'forest-ads');
            $this->addLinkEdge($edges, $this->formatId('FOREST_', $forest->id), $this->formatId('ZONE_', $forest->zone_admin_id));
        }
        // Domain
        $domains = DB::table('domaine_ads')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($domains as $domain) {
            $this->addNode($nodes, 4, $this->formatId('DOMAIN_', $domain->id), $domain->name, '/images/domain.png', 'domaine-ads');
        }
        // domaine_ad_forest_ad
        $joins = DB::table('domaine_ad_forest_ad')->select('forest_ad_id', 'domaine_ad_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('FOREST_', $join->forest_ad_id), $this->formatId('DOMAIN_', $join->domaine_ad_id));
        }
        // AdminUsers
        $adminUsers = DB::table('admin_users')->select('id', 'user_id', 'domain_id')->whereNull('deleted_at')->get();
        foreach ($adminUsers as $adminUser) {
            $this->addNode($nodes, 4, $this->formatId('USER_', $adminUser->id), $adminUser->user_id, '/images/user.png', 'admin-users');
            if ($adminUser->domain_id !== null) {
                $this->addLinkEdge($edges, $this->formatId('USER_', $adminUser->id), $this->formatId('DOMAIN_', $adminUser->domain_id));
            }
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
        $applications = DB::table('m_applications')->select('id', 'name', 'icon_id', 'application_block_id')->whereNull('deleted_at')->get();
        foreach ($applications as $application) {
            $this->addNode(
                $nodes,
                3,
                $this->formatId('APP_', $application->id),
                $application->name,
                $application->icon_id === null ? '/images/application.png' : "/admin/documents/{$application->icon_id}",
                'applications'
            );
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
        // activity_m_application
        $joins = DB::table('activity_m_application')->select('m_application_id', 'activity_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('ACTIVITY_', $join->activity_id));
        }
        // logical_server_m_application
        $joins = DB::table('logical_server_m_application')->select('m_application_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('LSERVER_', $join->logical_server_id));
        }
        // m_application_security_device
        $joins = DB::table('m_application_security_device')->select('m_application_id', 'security_device_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('LSECURITY_', $join->security_device_id));
        }

        // Application Services
        $services = DB::table('application_services')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($services as $service) {
            $this->addNode($nodes, 3, $this->formatId('SERV_', $service->id), $service->name, '/images/applicationservice.png', 'application-services');
        }
        // application_service_m_application
        $joins = DB::table('application_service_m_application')->select('m_application_id', 'application_service_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge($edges, $this->formatId('APP_', $join->m_application_id), $this->formatId('SERV_', $join->application_service_id));
        }
        // Application Modules
        $modules = DB::table('application_modules')->select('id', 'name')->whereNull('deleted_at')->get();
        foreach ($modules as $module) {
            $this->addNode($nodes, 3, $this->formatId('MOD_', $module->id), $module->name, '/images/applicationmodule.png', 'application-modules');
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
                $src_id = 'APP_'.$flux->application_source_id;
            } elseif ($flux->service_source_id !== null) {
                $src_id = 'SERV_'.$flux->service_source_id;
            } elseif ($flux->module_source_id !== null) {
                $src_id = 'MOD_'.$flux->module_source_id;
            } elseif ($flux->database_source_id !== null) {
                $src_id = 'DATABASE_'.$flux->database_source_id;
            } else {
                continue;
            }

            if ($flux->application_dest_id !== null) {
                $dest_id = 'APP_'.$flux->application_dest_id;
            } elseif ($flux->service_dest_id !== null) {
                $dest_id = 'SERV_'.$flux->service_dest_id;
            } elseif ($flux->module_dest_id !== null) {
                $dest_id = 'MOD_'.$flux->module_dest_id;
            } elseif ($flux->database_dest_id !== null) {
                $dest_id = 'DATABASE_'.$flux->database_dest_id;
            } else {
                continue;
            }

            $this->addFluxEdge($edges, $flux->nature, $flux->bidirectional, $src_id, $dest_id);
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
        $processes = DB::table('processes')->select('id', 'name', 'icon_id', 'macroprocess_id')->whereNull('deleted_at')->get();
        foreach ($processes as $process) {
            $this->addNode(
                $nodes,
                2,
                $this->formatId('PROCESS_', $process->id),
                $process->name,
                $process->icon_id === null ? '/images/process.png' : "/admin/documents/{$process->icon_id}",
                'processes'
            );
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
        $entities = DB::table('entities')->select('id', 'name', 'icon_id', 'parent_entity_id')->whereNull('deleted_at')->get();
        foreach ($entities as $entity) {
            $this->addNode(
                $nodes,
                1,
                $this->formatId('ENTITY_', $entity->id),
                $entity->name,
                $entity->icon_id === null ? '/images/entity.png' : "/admin/documents/{$entity->icon_id}",
                'entities'
            );
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

        return [$nodes, $edges];
    }

    private function addNode(&$nodes, $vue, $id, $label, $image, $type, $title = null): void
    {
        $data = ['vue' => $vue, 'id' => $id,  'label' => $label, 'image' => $image, 'type' => $type];
        if ($title !== null) {
            $data['title'] = $title;
        }
        array_push($nodes, $data);
    }

    private function addLinkEdge(&$edges, $from, $to): void
    {
        $this->addEdge($edges, null, false, $from, $to, 'LINK');
    }

    private function addPhysicalLinkEdge(&$edges, $from, $to): void
    {
        $this->addEdge($edges, null, false, $from, $to, 'CABLE');
    }

    private function addFluxEdge(&$edges, $name, $bidir, $from, $to): void
    {
        $this->addEdge($edges, $name, $bidir, $from, $to, 'FLUX');
    }

    private function addEdge(&$edges, $name, $bidir, $from, $to, $type): void
    {
        if ($from !== null && $to !== null) {
            array_push($edges, ['name' => $name, 'bidirectional' => $bidir, 'from' => $from, 'to' => $to, 'type' => $type]);
        }
    }

    private function formatId($prefix, $id)
    {
        if ($id !== null) {
            return $prefix.$id;
        }

        return null;
    }
}
