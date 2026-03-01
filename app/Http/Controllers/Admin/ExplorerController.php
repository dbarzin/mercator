<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mercator\Core\Models\Activity;
use Mercator\Core\Models\Actor;
use Mercator\Core\Models\Annuaire;
use Mercator\Core\Models\ApplicationBlock;
use Mercator\Core\Models\ApplicationModule;
use Mercator\Core\Models\ApplicationService;
use Mercator\Core\Models\Bay;
use Mercator\Core\Models\Building;
use Mercator\Core\Models\Certificate;
use Mercator\Core\Models\Cluster;
use Mercator\Core\Models\Container;
use Mercator\Core\Models\Database;
use Mercator\Core\Models\DomaineAd;
use Mercator\Core\Models\Entity;
use Mercator\Core\Models\ExternalConnectedEntity;
use Mercator\Core\Models\ForestAd;
use Mercator\Core\Models\Gateway;
use Mercator\Core\Models\Information;
use Mercator\Core\Models\Lan;
use Mercator\Core\Models\LogicalFlow;
use Mercator\Core\Models\LogicalServer;
use Mercator\Core\Models\MacroProcessus;
use Mercator\Core\Models\Man;
use Mercator\Core\Models\MApplication;
use Mercator\Core\Models\Network;
use Mercator\Core\Models\NetworkSwitch;
use Mercator\Core\Models\Operation;
use Mercator\Core\Models\Peripheral;
use Mercator\Core\Models\Phone;
use Mercator\Core\Models\PhysicalLink;
use Mercator\Core\Models\PhysicalRouter;
use Mercator\Core\Models\PhysicalSecurityDevice;
use Mercator\Core\Models\PhysicalServer;
use Mercator\Core\Models\PhysicalSwitch;
use Mercator\Core\Models\Process;
use Mercator\Core\Models\Relation;
use Mercator\Core\Models\Router;
use Mercator\Core\Models\Site;
use Mercator\Core\Models\StorageDevice;
use Mercator\Core\Models\Subnetwork;
use Mercator\Core\Models\Task;
use Mercator\Core\Models\Vlan;
use Mercator\Core\Models\Wan;
use Mercator\Core\Models\WifiTerminal;
use Mercator\Core\Models\Workstation;
use Mercator\Core\Models\ZoneAdmin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExplorerController extends Controller
{
    private array $nodes = [];
    private array $edges = [];

    // Shared objects
    private Collection $subnetworks;
    private Collection $logicalServers;
    private Collection $workstations;
    private Collection $peripherals;

    public function explore(Request $request)
    {
        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Vue optimisée - pas de données, chargement AJAX
        return view('admin.reports.explore');
    }

    /**
     * API endpoint pour récupérer les données du graphe en JSON
     */
    public function getGraphData(Request $request)
    {
        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        [$nodes, $edges] = $this->getData();

        return response()->json([
            'nodes' => $nodes,
            'edges' => $edges,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getAttributes(): \Illuminate\Http\JsonResponse
    {
        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tables = [
            'm_applications', 'logical_servers', 'clusters', 'entities',
            'buildings', 'physical_security_devices', 'relations', 'security_devices', 'fluxes',
        ];
        $allAttributes = collect();
        foreach ($tables as $table) {
            try {
                $rows = DB::table($table)
                    ->select('attributes')
                    ->whereNull('deleted_at')
                    ->whereNotNull('attributes')
                    ->where('attributes', '!=', '')
                    ->get();
                $allAttributes = $allAttributes->merge(
                    collect($rows)->flatMap(fn($r) => array_map('trim', explode(' ', $r->attributes)))->filter()
                );
            } catch (\Exception $e) {}
        }
        $result = $allAttributes->unique()->sort()->values();

        return response()->json($result);
    }

    /**
     * Build node and edge collections representing the system graph across all views.
     *
     * @return array [nodes, edges]
     */
    public function getData(): array
    {
        $this->nodes = [];
        $this->edges = [];
        $this->subnetworks = Subnetwork::all();

        $this->buildPhysicalView();
        $this->buildLogicalView();
        $this->buildApplicationView();
        $this->buildAdministrativeView();
        $this->buildProcessView();
        $this->buildEcosystemView();

        // Sort elements by name
        usort($this->nodes, fn($a, $b) => strcmp($a['label'], $b['label']));

        return [$this->nodes, $this->edges];
    }

    /**
     * Build Physical view (view 6) - Infrastructure physique
     */
    private function buildPhysicalView(): void
    {
        $this->buildSites();
        $this->buildBuildings();
        $this->buildBays();
        $this->buildPhysicalServers();
        $this->buildPhones();
        $this->buildStorageDevices();
        $this->buildWorkstations();
        $this->buildPeripherals();
        $this->buildPhysicalSwitches();
        $this->buildPhysicalRouters();
        $this->buildPhysicalSecurityDevices();
        $this->buildWifiTerminals();
        $this->buildWANs();
        $this->buildMAN();
        $this->buildLAN();
        $this->buildVLAN();
        $this->buildPhysicalLinks();
    }

    private function buildSites(): void
    {
        $sites = DB::table('sites')
            ->select('id', 'name', 'icon_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($sites as $site) {
            $this->addNode(
                6,
                $this->formatId(Site::$prefix, $site->id),
                $site->name,
                $this->getIcon($site->icon_id, '/images/site.png'),
                'sites',
                600
            );
        }
    }

    private function buildBuildings(): void
    {
        $buildings = DB::table('buildings')
            ->select('id', 'name', 'building_id', 'site_id', 'icon_id', 'attributes')
            ->whereNull('deleted_at')
            ->get();

        foreach ($buildings as $building) {
            $this->addNode(
                6,
                $this->formatId(Building::$prefix, $building->id),
                $building->name,
                $this->getIcon($building->icon_id, '/images/building.png'),
                'buildings',
                605,
                null,
                $building->attributes
            );

            if ($building->building_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Building::$prefix, $building->id),
                    $this->formatId(Building::$prefix, $building->building_id)
                );
            } elseif ($building->site_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Building::$prefix, $building->id),
                    $this->formatId(Site::$prefix, $building->site_id)
                );
            }
        }
    }

    private function buildBays(): void
    {
        $bays = DB::table('bays')
            ->select('id', 'name', 'room_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($bays as $bay) {
            $this->addNode(6,
                $this->formatId(Bay::$prefix, $bay->id),
                $bay->name,
                '/images/bay.png',
                'bays',
                610);

            if ($bay->room_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Bay::$prefix, $bay->id),
                    $this->formatId(Building::$prefix, $bay->room_id)
                );
            }
        }
    }

    private function buildPhysicalServers(): void
    {
        $servers = DB::table('physical_servers')
            ->select('id', 'name', 'icon_id', 'bay_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($servers as $server) {
            $this->addNode(
                6,
                $this->formatId(PhysicalServer::$prefix, $server->id),
                $server->name,
                $this->getIcon($server->icon_id, '/images/server.png'),
                'physical-servers',
                615
            );

            if ($server->bay_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(PhysicalServer::$prefix, $server->id),
                    $this->formatId(Bay::$prefix, $server->bay_id)
                );
            }
        }
    }

    private function buildWorkstations(): void
    {
        $this->workstations = DB::table('workstations')
            ->select('id', 'name', 'icon_id', 'address_ip', 'building_id', 'site_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($this->workstations as $workstation) {
            $this->addNode(
                6,
                $this->formatId(Workstation::$prefix, $workstation->id),
                $workstation->name,
                $this->getIcon($workstation->icon_id, '/images/workstation.png'),
                'workstations',
                620,
                $workstation->address_ip
            );

            $this->linkToLocationOrSite(
                $this->formatId(Workstation::$prefix, $workstation->id),
                $workstation->building_id,
                $workstation->site_id
            );

            foreach (explode(',', $workstation->address_ip ?? '') as $ip)
                $this->linkDeviceToSubnetworks(
                    $ip,
                    $this->formatId(Workstation::$prefix, $workstation->id));
        }
    }

    private function buildPhones(): void
    {
        $phones = DB::table('phones')
            ->select('id', 'name', 'address_ip', 'building_id', 'site_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($phones as $phone) {
            $this->addNode(
                6,
                $this->formatId(Phone::$prefix, $phone->id),
                $phone->name,
                $this->getIcon(null, '/images/phone.png'),
                'phones',
                625,
                $phone->address_ip
            );

            $this->linkToLocationOrSite(
                $this->formatId(Phone::$prefix, $phone->id),
                $phone->building_id,
                $phone->site_id
            );

            $this->linkDeviceToSubnetworks($phone->address_ip,
                $this->formatId(Phone::$prefix, $phone->id));

        }
    }

    private function buildPeripherals(): void {
        $this->peripherals = DB::table('peripherals')
            ->select('id', 'name', 'icon_id', 'address_ip', 'bay_id', 'site_id', 'building_id', 'provider_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($this->peripherals as $peripheral) {
            $this->addNode(
                6,
                $this->formatId(Peripheral::$prefix, $peripheral->id),
                $peripheral->name,
                $peripheral->icon_id === null ? '/images/peripheral.png' : "/admin/documents/{$peripheral->icon_id}",
                'peripherals',
                630,
                $peripheral->address_ip
            );
            if ($peripheral->bay_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Peripheral::$prefix, $peripheral->id),
                    $this->formatId(Bay::$prefix, $peripheral->bay_id));
            } elseif ($peripheral->building_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Peripheral::$prefix, $peripheral->id),
                    $this->formatId(Building::$prefix, $peripheral->building_id));
            } elseif ($peripheral->site_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Peripheral::$prefix, $peripheral->id),
                    $this->formatId(Site::$prefix, $peripheral->site_id));
            }
            if ($peripheral->provider_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Peripheral::$prefix, $peripheral->id),
                    $this->formatId(Entity::$prefix, $peripheral->provider_id));
            }

            foreach (explode(',', $peripheral->address_ip ?? '') as $address) {
                $this->linkDeviceToSubnetworks(
                    $address,
                    $this->formatId(Peripheral::$prefix, $peripheral->id));

            }
        }

        // m_application_peripheral
        $joins = DB::table('m_application_peripheral')
            ->select('m_application_id', 'peripheral_id')
            ->get();

        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(MApplication::$prefix, $join->m_application_id),
                $this->formatId(Peripheral::$prefix, $join->peripheral_id));
        }

    }
    private function buildStorageDevices(): void {
        // Storage devices
        $storageDevices = DB::table('storage_devices')
            ->select('id', 'name', 'bay_id', 'address_ip')
            ->whereNull('deleted_at')
            ->get();

        foreach ($storageDevices as $storageDevice) {
            $this->addNode(
                6,
                $this->formatId(StorageDevice::$prefix, $storageDevice->id),
                $storageDevice->name,
                '/images/storagedev.png',
                'storage-devices',
                635,
                $storageDevice->address_ip);

            if ($storageDevice->bay_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(StorageDevice::$prefix, $storageDevice->id),
                    $this->formatId(Bay::$prefix, $storageDevice->bay_id));
            }

            foreach (explode(',', $storageDevice->address_ip ?? '') as $address) {
                $this->linkDeviceToSubnetworks(
                    $address,
                    $this->formatId(StorageDevice::$prefix, $storageDevice->id));
            }
        }
    }

    private function buildPhysicalSwitches(): void
    {
        $switches = DB::table('physical_switches')
            ->select('id', 'name', 'bay_id', 'building_id', 'site_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($switches as $switch) {
            $this->addNode(
                6,
                $this->formatId(PhysicalSwitch::$prefix, $switch->id),
                $switch->name,
                '/images/switch.png',
                'physical-switches',
                640
            );

            $this->linkToLocationOrSite(
                $this->formatId(PhysicalSwitch::$prefix, $switch->id),
                $switch->building_id,
                $switch->site_id,
                $switch->bay_id
            );
        }

        $this->linkJoinTable('network_switch_physical_switch', NetworkSwitch::$prefix, PhysicalSwitch::$prefix, 'network_switch_id', 'physical_switch_id');
    }

    private function buildPhysicalRouters(): void
    {
        $routers = DB::table('physical_routers')
            ->select('id', 'name', 'bay_id', 'building_id', 'site_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($routers as $router) {
            $this->addNode(
                6,
                $this->formatId(PhysicalRouter::$prefix, $router->id),
                $router->name,
                '/images/router.png',
                'physical-routers',
                650
            );

            $this->linkToLocationOrSite(
                $this->formatId(PhysicalRouter::$prefix, $router->id),
                $router->building_id,
                $router->site_id,
                $router->bay_id
            );
        }
    }

    private function buildWifiTerminals(): void {
        $wifiTerminals = DB::table('wifi_terminals')
            ->select('id', 'name', 'address_ip', 'site_id', 'building_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($wifiTerminals as $wifiTerminal) {
            $this->addNode(
                6,
                $this->formatId(WifiTerminal::$prefix, $wifiTerminal->id),
                $wifiTerminal->name, '/images/wifi.png',
                'wifi-terminals',
                655,
                $wifiTerminal->address_ip);

            if ($wifiTerminal->building_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(WifiTerminal::$prefix, $wifiTerminal->id),
                    $this->formatId(Building::$prefix, $wifiTerminal->building_id));
            } elseif ($wifiTerminal->site_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(WifiTerminal::$prefix, $wifiTerminal->id),
                    $this->formatId(Site::$prefix, $wifiTerminal->site_id));
            }

            foreach (explode(',', $wifiTerminal->address_ip ?? '') as $address) {
                $this->linkDeviceToSubnetworks(
                    $address,
                    $this->formatId(WifiTerminal::$prefix, $wifiTerminal->id));
            }
        }
    }


    private function buildPhysicalSecurityDevices(): void
    {
        $devices = DB::table('physical_security_devices')
            ->select('id', 'name', 'icon_id', 'address_ip', 'bay_id', 'site_id', 'building_id', 'attributes')
            ->whereNull('deleted_at')
            ->get();

        foreach ($devices as $device) {
            $this->addNode(
                6,
                $this->formatId(PhysicalSecurityDevice::$prefix, $device->id),
                $device->name,
                $this->getIcon($device->icon_id, '/images/security.png'),
                'physical-security-devices',
                660,
                $device->address_ip,
                $device->attributes
            );

            $this->linkToLocationOrSite(
                $this->formatId(PhysicalSecurityDevice::$prefix, $device->id),
                $device->building_id,
                $device->site_id,
                $device->bay_id
            );

            $this->linkDeviceToSubnetworks(
                $device->address_ip,
                $this->formatId(PhysicalSecurityDevice::$prefix, $device->id));
        }
    }

    private function buildWANs(): void
    {
        $wans = DB::table('wans')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($wans as $wan) {
            $this->addNode(
                6,
                $this->formatId(Wan::$prefix, $wan->id),
                $wan->name,
                '/images/vlan.png',
                'wans',
                670
            );
        }

        $this->linkJoinTable('man_wan', Man::$prefix, Wan::$prefix, 'man_id', 'wan_id');
    }

    private function buildMAN(): void
    {
        $mans = DB::table('mans')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($mans as $man) {
            $this->addNode(
                6,
                $this->formatId(Man::$prefix, $man->id),
                $man->name,
                '/images/vlan.png',
                'mans',
                680
            );
        }

        $this->linkJoinTable('lan_man', Lan::$prefix, Man::$prefix, 'lan_id', 'man_id');
    }

    private function buildLAN(): void
    {
        $lans = DB::table('lans')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($lans as $lan) {
            $this->addNode(
                6,
                $this->formatId(Lan::$prefix, $lan->id),
                $lan->name,
                '/images/vlan.png',
                'lans',
                690
            );
        }

        $this->linkJoinTable('lan_wan',
            Lan::$prefix, Wan::$prefix,
            'lan_id', 'wan_id');
    }

    private function buildVLAN(): void
    {
        $vlans = DB::table('vlans')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($vlans as $vlan) {
            $this->addNode(
                5,
                $this->formatId(Vlan::$prefix, $vlan->id),
                $vlan->name,
                '/images/vlan.png',
                'vlans',
                520
            );
        }
    }

    private function buildPhysicalLinks(): void
    {
        $links = PhysicalLink::all();

        foreach ($links as $link) {
            $src = $link->sourceId();
            $dest = $link->destinationId();
            if ($src !== null && $dest !== null) {
                $this->addPhysicalLinkEdge(
                    $src,
                    $dest,
                    $link->color
                );
            }
        }
    }

    /**
     * Build Logical view (view 5) - Infrastructure logique
     */
    private function buildLogicalView(): void
    {
        $this->buildNetworks();
        $this->buildNetworkSwitches();
        $this->buildSubnetworks();
        $this->buildGateways();
        $this->buildExternalConnectedEntities();
        $this->buildLogicalServers();
        $this->buildCertificates();
        $this->buildLogicalFlows();
        $this->buildContainers();
        $this->buildClusters();
    }

    private function buildNetworks(): void {
        $networks = DB::table('networks')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($networks as $network) {
            $this->addNode(
                5,
                $this->formatId(Network::$prefix, $network->id),
                $network->name,
                '/images/cloud.png',
                'networks', 500);
        }
    }

    private function buildNetworkSwitches(): void
    {
        $switches = DB::table('network_switches')
            ->select('id', 'name', 'ip')
            ->whereNull('deleted_at')
            ->get();

        foreach ($switches as $switch) {
            $this->addNode(
                5,
                $this->formatId(NetworkSwitch::$prefix, $switch->id),
                $switch->name,
                '/images/switch.png',
                'network-switches', 510,
                $switch->ip
            );

            if ($switch->ip!=null)
                $this->linkDeviceToSubnetworks(
                    $switch->ip,
                    $this->formatId(NetworkSwitch::$prefix, $switch->id));

        }
    }

    private function buildSubnetworks(): void
    {
        foreach ($this->subnetworks as $subnetwork) {
            $this->addNode(
                5,
                $this->formatId(Subnetwork::$prefix, $subnetwork->id),
                $subnetwork->name,
                '/images/network.png',
                'subnetworks', 515,
                $subnetwork->address
            );

            if ($subnetwork->subnetwork_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Subnetwork::$prefix, $subnetwork->id),
                    $this->formatId(Subnetwork::$prefix, $subnetwork->subnetwork_id));
            } elseif ($subnetwork->network_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Subnetwork::$prefix, $subnetwork->id),
                    $this->formatId(Network::$prefix, $subnetwork->network_id));
            }

            if ($subnetwork->vlan_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Subnetwork::$prefix, $subnetwork->id),
                    $this->formatId(Vlan::$prefix, $subnetwork->vlan_id)
                );
            }

            if ($subnetwork->gateway_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Subnetwork::$prefix, $subnetwork->id),
                    $this->formatId(Gateway::$prefix, $subnetwork->gateway_id)
                );
            }



        }
    }

    private function buildGateways(): void
    {
        $gateways = DB::table('gateways')
            ->select('id', 'name', 'ip')
            ->whereNull('deleted_at')
            ->get();

        foreach ($gateways as $gateway) {
            $this->addNode(
                5,
                $this->formatId(Gateway::$prefix, $gateway->id),
                $gateway->name,
                '/images/gateway.png',
                'gateways',530,
                $gateway->ip
            );
        }
    }

    private function buildExternalConnectedEntities(): void
    {
        $entities = DB::table('external_connected_entities')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($entities as $entity) {
            $this->addNode(
                5,
                $this->formatId(ExternalConnectedEntity::$prefix, $entity->id),
                $entity->name,
                '/images/entity.png',
                'external-connected-entities', 540,
            );
        }
    }

    private function buildContainers(): void
    {
        $containers = DB::table('containers')
            ->select('id', 'name', 'icon_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($containers as $container) {
            $this->addNode(
                5,
                $this->formatId(Container::$prefix, $container->id),
                $container->name,
                $container->icon_id === null ? '/images/container.png' : "/admin/documents/{$container->icon_id}",
                'containers', 550
            );
        }

        // Container - Logical Servers
        $joins = DB::table('container_logical_server')
            ->select('container_id', 'logical_server_id')
            ->get();
        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(Container::$prefix, $join->container_id),
                $this->formatId(LogicalServer::$prefix, $join->logical_server_id));
        }

        // Container - Applications
        $joins = DB::table('container_m_application')
            ->select('container_id', 'm_application_id')
            ->get();
        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(Container::$prefix, $join->container_id),
                $this->formatId(MApplication::$prefix, $join->m_application_id));
        }

        // Container - Databases
        $joins = DB::table('container_database')
            ->select('container_id', 'database_id')
            ->get();
        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(Container::$prefix, $join->container_id),
                $this->formatId(Database::$prefix, $join->database_id));
        }
    }

    private function buildClusters(): void {
        // Clusters
        $clusters = DB::table('clusters')
            ->select('id', 'name', 'icon_id', 'address_ip', 'attributes')
            ->whereNull('deleted_at')->get();
        foreach ($clusters as $cluster) {
            $this->addNode(
                5,
                $this->formatId(Cluster::$prefix, $cluster->id),
                $cluster->name,
                $cluster->icon_id === null ? '/images/cluster.png' : "/admin/documents/{$cluster->icon_id}",
                'clusters', 580,
                $cluster->address_ip,
                $cluster->attributes
            );
        }

        // Cluster - Logical Servers
        $joins = DB::table('cluster_logical_server')->select('cluster_id', 'logical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(Cluster::$prefix, $join->cluster_id),
                $this->formatId(LogicalServer::$prefix, $join->logical_server_id));
        }

        // Cluster - Logical Servers
        $joins = DB::table('cluster_physical_server')->select('cluster_id', 'physical_server_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(Cluster::$prefix, $join->cluster_id),
                $this->formatId(PhysicalServer::$prefix, $join->physical_server_id));
        }

        // Cluster - Routers
        $joins = DB::table('cluster_router')->select('cluster_id', 'router_id')->get();
        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId(Cluster::$prefix, $join->cluster_id),
                $this->formatId(Router::$prefix, $join->router_id));
        }

    }

    private function buildLogicalServers(): void
    {
        $this->logicalServers = DB::table('logical_servers')
            ->select('id', 'name', 'icon_id', 'address_ip', 'attributes')
            ->whereNull('deleted_at')
            ->get();

        foreach ($this->logicalServers as $server) {
            $this->addNode(
                5,
                $this->formatId(LogicalServer::$prefix, $server->id),
                $server->name,
                $this->getIcon($server->icon_id, '/images/lserver.png'),
                'logical-servers', 560,
                $server->address_ip,
                $server->attributes
            );

            $this->linkDeviceToSubnetworks(
                $server->address_ip,
                $this->formatId(LogicalServer::$prefix, $server->id));
        }

        $this->linkJoinTable('logical_server_physical_server', LogicalServer::$prefix, PhysicalServer::$prefix, 'logical_server_id', 'physical_server_id');
    }

    private function buildCertificates(): void
    {
        $certificates = DB::table('certificates')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($certificates as $certificate) {
            $this->addNode(
                5,
                $this->formatId(Certificate::$prefix, $certificate->id),
                $certificate->name,
                '/images/certificate.png',
                'certificates', 570
            );
        }

        $this->linkJoinTable('certificate_logical_server', Certificate::$prefix, LogicalServer::$prefix, 'certificate_id', 'logical_server_id');
    }

    private function buildLogicalFlows(): void
    {
        $flows = LogicalFlow::All();

        foreach ($flows as $flow) {
            // \Log::Debug('flow: '.$flow->name);
            // Get sources
            $sources = [];
            if ($flow->source_ip_range !== null) {
                foreach ($this->logicalServers as $server) {
                    foreach (explode(',', $server->address_ip ?? '') as $ip) {
                        if ($flow->isSource($ip)) {
                            array_push($sources, $this->formatId('LSERVER_', $server->id));
                        }
                    }
                }
                foreach ($this->workstations as $workstation) {
                    if ($flow->isSource($workstation->address_ip)) {
                        array_push($sources, $this->formatId('WORK_', $workstation->id));
                    }
                }
                foreach ($this->peripherals as $peripheral) {
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
                foreach ($this->logicalServers as $server) {
                    foreach (explode(',', $server->address_ip ?? '') as $ip) {
                        if ($flow->isDestination($ip)) {
                            array_push($destinations, $this->formatId('LSERVER_', $server->id));
                        }
                    }
                }
                foreach ($this->workstations as $workstation) {
                    if ($flow->isDestination($workstation->address_ip)) {
                        array_push($destinations, $this->formatId('WORK_', $workstation->id));
                    }
                }
                foreach ($this->peripherals as $peripheral) {
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
                    $this->addFluxEdge($flow->name, false, $source, $destination);
                }
            }
        }

    }

    /**
     * Build Application view (view 4) - Applications et services
     */
    private function buildApplicationView(): void
    {
        $this->buildApplications();
        $this->buildApplicationBlocks();
        $this->buildApplicationServices();
        $this->buildApplicationModules();
        $this->buildDatabases();
        $this->buildApplicationFlows();
    }

    private function buildApplicationFlows() : void {
        // Fluxes
        $fluxes = DB::table('fluxes')->whereNull('deleted_at')->get();
        foreach ($fluxes as $flux) {
            if ($flux->application_source_id !== null) {
                $src_id = MApplication::$prefix . $flux->application_source_id;
            } elseif ($flux->service_source_id !== null) {
                $src_id = ApplicationService::$prefix . $flux->service_source_id;
            } elseif ($flux->module_source_id !== null) {
                $src_id = ApplicationModule::$prefix . $flux->module_source_id;
            } elseif ($flux->database_source_id !== null) {
                $src_id = Database::$prefix . $flux->database_source_id;
            } else {
                continue;
            }

            if ($flux->application_dest_id !== null) {
                $dest_id = MApplication::$prefix . $flux->application_dest_id;
            } elseif ($flux->service_dest_id !== null) {
                $dest_id = ApplicationService::$prefix . $flux->service_dest_id;
            } elseif ($flux->module_dest_id !== null) {
                $dest_id = ApplicationModule::$prefix . $flux->module_dest_id;
            } elseif ($flux->database_dest_id !== null) {
                $dest_id =  Database::$prefix  . $flux->database_dest_id;
            } else {
                continue;
            }

            $this->addFluxEdge($flux->nature, $flux->bidirectional ?? false, $src_id, $dest_id);
        }
    }

    private function buildApplicationBlocks(): void
    {
        $blocks = DB::table('application_blocks')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($blocks as $block) {
            $this->addNode(
                3,
                $this->formatId(ApplicationBlock::$prefix, $block->id),
                $block->name,
                '/images/applicationblock.png',
                'application-blocks', 300
            );
        }
    }

    private function buildApplications(): void
    {
        $applications = DB::table('m_applications')
            ->select('id', 'name', 'icon_id', 'application_block_id', 'attributes')
            ->whereNull('deleted_at')
            ->get();

        foreach ($applications as $app) {
            $this->addNode(
                3,
                $this->formatId(MApplication::$prefix, $app->id),
                $app->name,
                $this->getIcon($app->icon_id, '/images/application.png'),
                'applications', 310,
                null,
                $app->attributes
            );

            if ($app->application_block_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(MApplication::$prefix, $app->id),
                    $this->formatId(ApplicationBlock::$prefix, $app->application_block_id)
                );
            }
        }

        $this->linkJoinTable('application_service_m_application',
            ApplicationService::$prefix, MApplication::$prefix,
            'application_service_id', 'm_application_id');
        $this->linkJoinTable('logical_server_m_application',
            LogicalServer::$prefix, MApplication::$prefix,
            'logical_server_id', 'm_application_id');
    }


    private function buildApplicationServices(): void
    {
        $services = DB::table('application_services')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($services as $service) {
            $this->addNode(
                3,
                $this->formatId(ApplicationService::$prefix, $service->id),
                $service->name,
                '/images/applicationservice.png',
                'application-services', 320
            );
        }

        $this->linkJoinTable('application_module_application_service',
            ApplicationModule::$prefix, ApplicationService::$prefix,
            'application_module_id', 'application_service_id');

    }

    private function buildApplicationModules(): void
    {
        $modules = DB::table('application_modules')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($modules as $module) {
            $this->addNode(
                3,
                $this->formatId(ApplicationModule::$prefix, $module->id),
                $module->name,
                '/images/module.png',
                'application-modules', 330
            );
        }
    }

    private function buildDatabases(): void
    {
        $databases = DB::table('databases')
            ->select('id', 'name', 'icon_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($databases as $database) {
            $this->addNode(
                3,
                $this->formatId(Database::$prefix, $database->id),
                $database->name,
                $this->getIcon($database->icon_id, '/images/database.png'),
                'databases', 340
            );
        }

        $this->linkJoinTable('database_logical_server',
            Database::$prefix, LogicalServer::$prefix,
            'database_id', 'logical_server_id');
        $this->linkJoinTable('database_m_application',
            Database::$prefix, MApplication::$prefix,
            'database_id', 'm_application_id');
    }

    /**
     * Build Administrative view (view 3) - Annuaires et zones
     */
    private function buildAdministrativeView(): void
    {
        $this->buildZoneAdmins();
        $this->buildAnnuaires();
        $this->buildForests();
        $this->buildDomains();
    }

    private function buildZoneAdmins(): void
    {
        $zones = DB::table('zone_admins')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($zones as $zone) {
            $this->addNode(
                4,
                $this->formatId(ZoneAdmin::$prefix, $zone->id),
                $zone->name,
                '/images/zoneadmin.png',
                'zone-admins', 400
            );
        }
    }

    private function buildAnnuaires(): void
    {
        $annuaires = DB::table('annuaires')
            ->select('id', 'name', 'zone_admin_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($annuaires as $annuaire) {
            $this->addNode(
                4,
                $this->formatId(Annuaire::$prefix, $annuaire->id),
                $annuaire->name,
                '/images/annuaire.png',
                'annuaires', 420
            );

            if ($annuaire->zone_admin_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Annuaire::$prefix, $annuaire->id),
                    $this->formatId(ZoneAdmin::$prefix, $annuaire->zone_admin_id)
                );
            }
        }
    }


    private function buildForests(): void
    {
        $forests = DB::table('forest_ads')
            ->select('id', 'name', 'zone_admin_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($forests as $forest) {
            $this->addNode(
                4,
                $this->formatId(ForestAd::$prefix, $forest->id),
                $forest->name,
                '/images/ldap.png',
                'forest-ads', 440
            );

            if ($forest->zone_admin_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(ForestAd::$prefix, $forest->id),
                    $this->formatId(ZoneAdmin::$prefix, $forest->zone_admin_id)
                );
            }
        }
    }

    private function buildDomains(): void
    {
        $domains = DB::table('domaine_ads')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($domains as $domain) {
            $this->addNode(
                4,
                $this->formatId(DomaineAd::$prefix, $domain->id),
                $domain->name,
                '/images/domain.png',
                'domaine-ads', 460
            );
        }

        $this->linkJoinTable('domaine_ad_forest_ad',
            DomaineAd::$prefix, ForestAd::$prefix,
            'domaine_ad_id', 'forest_ad_id');
    }

    /**
     * Build Process view (view 2) - Processus et informations
     */
    private function buildProcessView(): void
    {
        $this->buildInformation();
        $this->buildProcesses();
        $this->buildMacroProcesses();
        $this->buildActivities();
        $this->buildOperations();
        $this->buildTasks();
        $this->buildActors();
    }

    private function buildInformation(): void
    {
        $information = DB::table('information')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($information as $info) {
            $this->addNode(
                2,
                $this->formatId(Information::$prefix, $info->id),
                $info->name,
                '/images/information.png',
                'information', 260
            );
        }

        $this->linkJoinTable('database_information',
            Information::$prefix, Database::$prefix,
            'information_id', 'database_id');
    }

    private function buildProcesses(): void
    {
        $processes = DB::table('processes')
            ->select('id', 'name', 'icon_id', 'macroprocess_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($processes as $process) {
            $this->addNode(
                2,
                $this->formatId(Process::$prefix, $process->id),
                $process->name,
                $this->getIcon($process->icon_id, '/images/process.png'),
                'processes', 210
            );

            if ($process->macroprocess_id !== null) {
                $this->addLinkEdge(
                    $this->formatId(Process::$prefix, $process->id),
                    $this->formatId(MacroProcessus::$prefix, $process->macroprocess_id)
                );
            }
        }

        $this->linkJoinTable('information_process', Information::$prefix, Process::$prefix, 'information_id', 'process_id');
    }

    private function buildMacroProcesses(): void
    {
        $macroProcesses = DB::table('macro_processuses')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($macroProcesses as $macroProcess) {
            $this->addNode(
                2,
                $this->formatId(MacroProcessus::$prefix, $macroProcess->id),
                $macroProcess->name,
                '/images/macroprocess.png',
                'macro-processuses', 200
            );
        }
    }

    private function buildActivities(): void
    {
        $activities = DB::table('activities')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($activities as $activity) {
            $this->addNode(
                2,
                $this->formatId(Activity::$prefix, $activity->id),
                $activity->name,
                '/images/activity.png',
                'activities', 220
            );
        }

        $this->linkJoinTable('activity_process',
            Activity::$prefix, Process::$prefix,
            'activity_id', 'process_id');

        $this->linkJoinTable('activity_m_application',
            Activity::$prefix, MApplication::$prefix,
            'activity_id', 'm_application_id');
    }

    private function buildOperations(): void
    {
        $operations = DB::table('operations')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($operations as $operation) {
            $this->addNode(
                2,
                $this->formatId(Operation::$prefix, $operation->id),
                $operation->name,
                '/images/operation.png',
                    'operations', 230
            );
        }

        $this->linkJoinTable('activity_operation',
            Activity::$prefix, Operation::$prefix,
            'activity_id', 'operation_id');
    }

    private function buildTasks(): void
    {
        $tasks = DB::table('tasks')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($tasks as $task) {
            $this->addNode(
                2,
                $this->formatId(Task::$prefix, $task->id),
                $task->name,
                '/images/task.png',
                'tasks', 240
            );
        }

        $this->linkJoinTable('operation_task',
            Operation::$prefix, Task::$prefix,
            'operation_id', 'task_id');
    }

    private function buildActors(): void
    {
        $actors = DB::table('actors')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();

        foreach ($actors as $actor) {
            $this->addNode(
                2,
                $this->formatId(Actor::$prefix, $actor->id),
                $actor->name,
                '/images/actor.png',
                'actors', 250
            );
        }

        $this->linkJoinTable('actor_operation', Actor::$prefix, Operation::$prefix, 'actor_id', 'operation_id');
    }

    /**
     * Build Ecosystem view (view 1) - Entités et relations
     */
    private function buildEcosystemView(): void
    {
        $this->buildEntities();
        $this->buildRelations();
    }

    private function buildEntities(): void
    {
        $entities = DB::table('entities')
            ->select('id', 'name', 'icon_id', 'parent_entity_id', 'attributes')
            ->whereNull('deleted_at')
            ->get();

        foreach ($entities as $entity) {
            $this->addNode(
                1,
                $this->formatId(Entity::$prefix, $entity->id),
                $entity->name,
                $this->getIcon($entity->icon_id, '/images/entity.png'),
                'entities', 150,
                null,
                $entity->attributes
            );

            if ($entity->parent_entity_id !== null) {
                $this->addFluxEdge(
                    null,
                    false,
                    $this->formatId(Entity::$prefix, $entity->id),
                    $this->formatId(Entity::$prefix, $entity->parent_entity_id)
                );
            }
        }

        $this->linkJoinTable('entity_process', Entity::$prefix, Process::$prefix, 'entity_id', 'process_id');
        $this->linkJoinTable('entity_m_application', Entity::$prefix, MApplication::$prefix, 'entity_id', 'm_application_id');
    }

    private function buildRelations(): void
    {
        $relations = DB::table('relations')
            ->select('id', 'name', 'source_id', 'destination_id', 'attributes')
            ->whereNull('deleted_at')
            ->get();

        foreach ($relations as $relation) {
            $this->addNode(
                1,
                $this->formatId(Relation::$prefix, $relation->id),
                $relation->name,
                '/images/relation.png',
                'relations', 100,
                null,
                $relation->attributes
            );

            $this->addFluxEdge(null, false,
                $this->formatId(Entity::$prefix, $relation->source_id),
                $this->formatId(Relation::$prefix, $relation->id)
            );

            $this->addFluxEdge(null, false,
                $this->formatId(Relation::$prefix, $relation->id),
                $this->formatId(Entity::$prefix, $relation->destination_id)
            );
        }
    }

    /**
     * Helper methods
     */
    private function addNode(int $vue, string $id, string $label, string $image, string $type, int $order, ?string $title = null, ?string $attributes = null): void
    {
        $this->nodes[] = [
            'vue' => $vue,
            'id' => $id,
            'label' => $label,
            'image' => $image,
            'type' => $type,
            'order' => $order,
            'title' => $title,
            'attributes' => $attributes,
        ];
    }

    private function addEdge(?string $name, bool $bidirectional,
                             string $from, string $to,
                             string $type, ?string $color): void
    {
        $edge = [
            'name' => $name,
            'bidirectional' => $bidirectional,
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'color' => $color
        ];
        if ($color !== null)
            $edge['color'] = $color;
        $this->edges[] = $edge;
    }

    private function addLinkEdge(string $from, string $to): void
    {
        $this->addEdge(null, false, $from, $to, 'LINK', null);
    }

    private function addPhysicalLinkEdge(string $from, string $to, ?string $color): void
    {
        $this->addEdge(null, false, $from, $to, 'CABLE', $color);
    }

    private function addFluxEdge(?string $name, bool $bidirectional, string $from, string $to): void
    {
        $this->addEdge($name, $bidirectional, $from, $to, 'FLUX', null);
    }

    private function formatId(string $prefix, $id): ?string
    {
        return $id !== null ? $prefix . $id : null;
    }

    private function getIcon(?int $iconId, string $defaultIcon): string
    {
        return $iconId === null ? $defaultIcon : "/admin/documents/{$iconId}";
    }

    private function linkToLocationOrSite(string $nodeId, $buildingId = null, $siteId = null, $bayId = null): void
    {
        if ($bayId !== null) {
            $this->addLinkEdge($nodeId, $this->formatId(Bay::$prefix, $bayId));
        } elseif ($buildingId !== null) {
            $this->addLinkEdge($nodeId, $this->formatId(Building::$prefix, $buildingId));
        } elseif ($siteId !== null) {
            $this->addLinkEdge($nodeId, $this->formatId(Site::$prefix, $siteId));
        }
    }


    private function linkDeviceToSubnetworks(?string $addressIp, string $deviceId): void
    {
        if ($addressIp === null) {
            return;
        }

        $addresses = explode(',', $addressIp);
        $smallestSubnetwork = null;
        $smallestSize = PHP_INT_MAX;

        foreach ($this->subnetworks as $subnetwork) {
            foreach ($addresses as $address) {
                if ($subnetwork->contains(trim($address))) {
                    $size = $subnetwork->getMaskLength();
                    if ($size < $smallestSize) {
                        $smallestSize = $size;
                        $smallestSubnetwork = $subnetwork;
                    }
                    break;
                }
            }
        }

        if ($smallestSubnetwork !== null) {
            $this->addLinkEdge(
                $this->formatId(Subnetwork::$prefix, $smallestSubnetwork->id),
                $deviceId
            );
        }
    }
    private function linkJoinTable(string $table, string $fromPrefix, string $toPrefix, string $fromColumn, string $toColumn): void
    {
        $joins = DB::table($table)->select($fromColumn, $toColumn)->get();

        foreach ($joins as $join) {
            $this->addLinkEdge(
                $this->formatId($fromPrefix, $join->{$fromColumn}),
                $this->formatId($toPrefix, $join->{$toColumn})
            );
        }
    }
}
