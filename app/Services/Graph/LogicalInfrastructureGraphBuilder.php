<?php

namespace App\Services\Graph;

use App\Models\Cartographer;
use App\Models\Certificate;
use App\Models\Cluster;
use App\Models\Container;
use App\Models\DhcpServer;
use App\Models\Dnsserver;
use App\Models\ExternalConnectedEntity;
use App\Models\Gateway;
use App\Models\LogicalServer;
use App\Models\Network;
use App\Models\NetworkSwitch;
use App\Models\Peripheral;
use App\Models\Phone;
use App\Models\PhysicalSecurityDevice;
use App\Models\Router;
use App\Models\SecurityDevice;
use App\Models\StorageDevice;
use App\Models\Subnetwork;
use App\Models\Vlan;
use App\Models\WifiTerminal;
use App\Models\Workstation;
use Illuminate\Support\Collection;

class LogicalInfrastructureGraphBuilder
{
    /**
     * @param  array{withHref?: bool, iconResolver?: callable(?int, string): string}  $options
     */
    public function buildDot(
        Collection $networks,
        Collection $subnetworks,
        Collection $gateways,
        Collection $externalConnectedEntities,
        Collection $vlans,
        Collection $networkSwitches,
        Collection $clusters,
        Collection $logicalServers,
        Collection $dhcpServers,
        Collection $dnsservers,
        Collection $certificates,
        Collection $containers,
        Collection $routers,
        Collection $securityDevices,
        Collection $workstations,
        Collection $wifiTerminals,
        Collection $phones,
        Collection $peripherals,
        Collection $physicalSecurityDevices,
        Collection $storageDevices,
        bool $showIp = false,
        array $options = []
    ): string {
        $withHref = $options['withHref'] ?? true;
        $iconResolver = $options['iconResolver'] ?? fn (?int $iconId, string $fallback) => $iconId === null
            ? $fallback
            : route('admin.documents.show', $iconId);

        $lines = ['digraph  {'];

        if (Cartographer::canAccess(Network::class)) {
            foreach ($networks as $network) {
                $lines[] = DotNode::withImage('NET'.$network->id, $iconResolver(null, '/images/cloud.png'), [e($network->name)], $this->href($network, $withHref));
            }
        }

        if (Cartographer::canAccess(Gateway::class)) {
            foreach ($gateways as $gateway) {
                $lines[] = $this->nodeWithIp('GATEWAY', $gateway->id, $gateway->name, $gateway->ip, $iconResolver(null, '/images/gateway.png'), $gateway->getUID(), $showIp, $withHref);
            }
        }

        if (Cartographer::canAccess(Subnetwork::class)) {
            foreach ($subnetworks as $subnetwork) {
                $lines[] = $this->nodeWithIp('SUBNET', $subnetwork->id, $subnetwork->name, $subnetwork->address, $iconResolver(null, '/images/network.png'), $subnetwork->getUID(), $showIp, $withHref);

                if ($subnetwork->vlan_id !== null && $vlans->contains('id', $subnetwork->vlan_id)) {
                    $lines[] = 'SUBNET'.$subnetwork->id.' -> VLAN'.$subnetwork->vlan_id;
                }

                if ($subnetwork->subnetwork_id !== null) {
                    if ($subnetworks->contains('id', $subnetwork->subnetwork_id)) {
                        $lines[] = 'SUBNET'.$subnetwork->subnetwork_id.' -> SUBNET'.$subnetwork->id;
                    } elseif ($subnetwork->network_id !== null && $networks->contains('id', $subnetwork->network_id)) {
                        // Parent subnetwork isn't in scope: fall back to linking to its network
                        // directly, but only if that network is actually drawn — this branch was
                        // missing that check entirely, unlike the sibling elseif below, and could
                        // emit a dangling "NET -> SUBNET" edge (even "NET -> SUBNET" with no id at
                        // all when network_id was null) pointing at a node that was never declared.
                        $lines[] = 'NET'.$subnetwork->network_id.' -> SUBNET'.$subnetwork->id;
                    }
                } elseif ($subnetwork->network_id !== null) {
                    if ($networks->contains('id', $subnetwork->network_id)) {
                        $lines[] = 'NET'.$subnetwork->network_id.' -> SUBNET'.$subnetwork->id;
                    }
                }

                if ($subnetwork->gateway_id !== null && $gateways->contains('id', $subnetwork->gateway_id)) {
                    $lines[] = 'SUBNET'.$subnetwork->id.' -> GATEWAY'.$subnetwork->gateway_id;
                }
            }
        }

        if (Cartographer::canAccess(ExternalConnectedEntity::class)) {
            foreach ($externalConnectedEntities as $entity) {
                $lines[] = DotNode::withImage('E'.$entity->id, $iconResolver(null, '/images/entity.png'), [e($entity->name)], $this->href($entity, $withHref));

                if ($entity->network_id !== null && $networks->contains('id', $entity->network_id)) {
                    $lines[] = 'E'.$entity->id.' -> NET'.$entity->network_id;
                }
            }
        }

        if (Cartographer::canAccess(Cluster::class)) {
            $usedClusterIds = $logicalServers
                ->flatMap(fn ($logicalServer) => $logicalServer->clusters->pluck('id'))
                ->unique()
                ->toArray();

            foreach ($clusters as $cluster) {
                if (! in_array($cluster->id, $usedClusterIds, true)) {
                    continue;
                }

                $lines[] = $this->nodeWithIp('CLUSTER', $cluster->id, $cluster->name, $cluster->address_ip, $iconResolver(null, '/images/cluster.png'), $cluster->getUID(), $showIp, $withHref);

                if (Cartographer::canAccess(LogicalServer::class)) {
                    foreach ($cluster->logicalServers as $logicalServer) {
                        if ($logicalServers->contains('id', $logicalServer->id)) {
                            $lines[] = 'LOGICAL_SERVER'.$logicalServer->id.' -> CLUSTER'.$cluster->id;
                        }
                    }
                }
            }
        }

        if (Cartographer::canAccess(LogicalServer::class)) {
            foreach ($logicalServers as $logicalServer) {
                $image = $iconResolver($logicalServer->icon_id, '/images/lserver.png');
                $lines[] = $this->nodeWithIp('LOGICAL_SERVER', $logicalServer->id, $logicalServer->name, $logicalServer->address_ip, $image, $logicalServer->getUID(), $showIp, $withHref);

                if ($logicalServer->address_ip !== null) {
                    $edge = $this->firstSubnetworkOuterMatch($subnetworks, $logicalServer->address_ip, 'LOGICAL_SERVER'.$logicalServer->id, true);
                    if ($edge !== null) {
                        $lines[] = $edge;
                    }
                }

                if (Cartographer::canAccess(Cluster::class)) {
                    if ($logicalServer->cluster_id !== null && $clusters->contains('id', $logicalServer->cluster_id)) {
                        $lines[] = 'LOGICAL_SERVER'.$logicalServer->id.' -> CLUSTER'.$logicalServer->cluster_id;
                    }
                }

                if (Cartographer::canAccess(Certificate::class)) {
                    foreach ($logicalServer->certificates as $certificate) {
                        if ($certificates->contains('id', $certificate->id)) {
                            $lines[] = 'LOGICAL_SERVER'.$logicalServer->id.' -> CERT'.$certificate->id;
                        }
                    }
                }
            }
        }

        if (Cartographer::canAccess(DhcpServer::class)) {
            foreach ($dhcpServers as $dhcpServer) {
                $lines[] = $this->nodeWithIp('DHCP_SERVER', $dhcpServer->id, $dhcpServer->name, $dhcpServer->address_ip, $iconResolver(null, '/images/lserver.png'), $dhcpServer->getUID(), $showIp, $withHref);

                if ($dhcpServer->address_ip !== null) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->contains($dhcpServer->address_ip)) {
                            $lines[] = 'SUBNET'.$subnetwork->id.' -> DHCP_SERVER'.$dhcpServer->id;
                            break;
                        }
                    }
                }
            }
        }

        if (Cartographer::canAccess(Dnsserver::class)) {
            foreach ($dnsservers as $dnsserver) {
                $lines[] = $this->nodeWithIp('DNS_SERVER', $dnsserver->id, $dnsserver->name, $dnsserver->address_ip, $iconResolver(null, '/images/lserver.png'), $dnsserver->getUID(), $showIp, $withHref);

                if ($dnsserver->address_ip !== null) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->contains($dnsserver->address_ip)) {
                            $lines[] = 'SUBNET'.$subnetwork->id.' -> DNS_SERVER'.$dnsserver->id;
                            break;
                        }
                    }
                }
            }
        }

        if (Cartographer::canAccess(Certificate::class)) {
            foreach ($certificates as $certificate) {
                if ($certificate->logicalServers->count() > 0) {
                    $lines[] = DotNode::withImage('CERT'.$certificate->id, $iconResolver(null, '/images/certificate.png'), [e($certificate->name)], $this->href($certificate, $withHref));
                }
            }
        }

        if (Cartographer::canAccess(Container::class)) {
            foreach ($containers as $container) {
                if ($container->logicalServers->count() > 0) {
                    $image = $iconResolver($container->icon_id, '/images/container.png');
                    $lines[] = DotNode::withImage('CONT'.$container->id, $image, [e($container->name)], $this->href($container, $withHref));

                    foreach ($container->logicalServers as $logicalServer) {
                        if ($logicalServers->contains('id', $logicalServer->id)) {
                            $lines[] = 'LOGICAL_SERVER'.$logicalServer->id.' -> CONT'.$container->id;
                        }
                    }
                }
            }
        }

        if (Cartographer::canAccess(Workstation::class)) {
            foreach ($workstations as $workstation) {
                $image = $iconResolver($workstation->icon_id, '/images/workstation.png');
                $lines[] = $this->nodeWithIp('WS', $workstation->id, $workstation->name, $workstation->address_ip, $image, $workstation->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $workstation->address_ip, 'WS'.$workstation->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(WifiTerminal::class)) {
            foreach ($wifiTerminals as $wifiTerminal) {
                $lines[] = $this->nodeWithIp('WIFI', $wifiTerminal->id, $wifiTerminal->name, $wifiTerminal->address_ip, $iconResolver(null, '/images/wifi.png'), $wifiTerminal->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $wifiTerminal->address_ip, 'WIFI'.$wifiTerminal->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(Phone::class)) {
            foreach ($phones as $phone) {
                $lines[] = $this->nodeWithIp('PHONE', $phone->id, $phone->name, $phone->address_ip, $iconResolver(null, '/images/phone.png'), $phone->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $phone->address_ip, 'PHONE'.$phone->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(PhysicalSecurityDevice::class)) {
            foreach ($physicalSecurityDevices as $physicalSecurityDevice) {
                if ($physicalSecurityDevice->address_ip !== null) {
                    $image = $iconResolver($physicalSecurityDevice->icon_id, '/images/securitydevice.png');
                    $lines[] = $this->nodeWithIp('PSECURITY', $physicalSecurityDevice->id, $physicalSecurityDevice->name, $physicalSecurityDevice->address_ip, $image, $physicalSecurityDevice->getUID(), $showIp, $withHref);

                    $edge = $this->firstAddressOuterMatch($subnetworks, $physicalSecurityDevice->address_ip, 'PSECURITY'.$physicalSecurityDevice->id);
                    if ($edge !== null) {
                        $lines[] = $edge;
                    }
                }
            }
        }

        if (Cartographer::canAccess(SecurityDevice::class)) {
            foreach ($securityDevices as $securityDevice) {
                $image = $iconResolver($securityDevice->icon_id, '/images/securitydevice.png');
                $lines[] = $this->nodeWithIp('SECURITY', $securityDevice->id, $securityDevice->name, $securityDevice->address_ip, $image, $securityDevice->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $securityDevice->address_ip, 'SECURITY'.$securityDevice->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(Peripheral::class)) {
            foreach ($peripherals as $peripheral) {
                $image = $iconResolver($peripheral->icon_id, '/images/peripheral.png');
                $lines[] = $this->nodeWithIp('PER', $peripheral->id, $peripheral->name, $peripheral->address_ip, $image, $peripheral->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $peripheral->address_ip, 'PER'.$peripheral->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(StorageDevice::class)) {
            foreach ($storageDevices as $storageDevice) {
                $image = $iconResolver($storageDevice->icon_id, '/images/storagedev.png');
                $lines[] = $this->nodeWithIp('STOR', $storageDevice->id, $storageDevice->name, $storageDevice->address_ip, $image, $storageDevice->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $storageDevice->address_ip, 'STOR'.$storageDevice->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(Router::class)) {
            foreach ($routers as $router) {
                $lines[] = $this->nodeWithIp('R', $router->id, $router->name, $router->ip_addresses, $iconResolver(null, '/images/router.png'), $router->getUID(), $showIp, $withHref);

                $edge = $this->firstAddressOuterMatch($subnetworks, $router->ip_addresses, 'R'.$router->id);
                if ($edge !== null) {
                    $lines[] = $edge;
                }
            }
        }

        if (Cartographer::canAccess(NetworkSwitch::class)) {
            foreach ($networkSwitches as $networkSwitch) {
                $lines[] = $this->nodeWithIp('SW', $networkSwitch->id, $networkSwitch->name, $networkSwitch->ip, $iconResolver(null, '/images/switch.png'), $networkSwitch->getUID(), $showIp, $withHref);

                if ($networkSwitch->vlans->count() > 0) {
                    foreach ($networkSwitch->vlans as $vlan) {
                        $lines[] = 'VLAN'.$vlan->id.' -> SW'.$networkSwitch->id;
                    }
                } else {
                    $edge = $this->firstAddressOuterMatch($subnetworks, $networkSwitch->ip, 'SW'.$networkSwitch->id);
                    if ($edge !== null) {
                        $lines[] = $edge;
                    }
                }
            }
        }

        if (Cartographer::canAccess(Vlan::class)) {
            foreach ($vlans as $vlan) {
                $lines[] = DotNode::withImage('VLAN'.$vlan->id, $iconResolver(null, '/images/vlan.png'), [e($vlan->name)], $this->href($vlan, $withHref));
            }
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    /**
     * @return array<int, array{path: string, width: string, height: string}>
     */
    public function imageManifest(
        Collection $containers,
        Collection $logicalServers,
        Collection $securityDevices,
        Collection $physicalSecurityDevices,
        Collection $peripherals,
        Collection $workstations,
        Collection $storageDevices
    ): array {
        $manifest = [
            ['path' => '/images/cloud.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/network.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/gateway.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/entity.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/lserver.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/router.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/switch.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/cluster.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/container.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/certificate.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/workstation.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/phone.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/securitydevice.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/storagedev.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/peripheral.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/wifi.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/vlan.png', 'width' => '64px', 'height' => '64px'],
        ];

        foreach ([$containers, $logicalServers, $securityDevices, $physicalSecurityDevices, $peripherals, $workstations, $storageDevices] as $collection) {
            foreach ($collection as $item) {
                if ($item->icon_id !== null) {
                    $manifest[] = ['path' => route('admin.documents.show', $item->icon_id), 'width' => '64px', 'height' => '64px'];
                }
            }
        }

        return $manifest;
    }

    private function nodeWithIp(string $prefix, int $id, ?string $name, ?string $ip, string $image, string $uid, bool $showIp, bool $withHref): string
    {
        $labelLines = [e($name ?? '')];
        if ($showIp && $ip !== null) {
            $labelLines[] = e($ip);
        }

        return DotNode::withImage($prefix.$id, $image, $labelLines, $withHref ? ' href="#'.$uid.'"' : '');
    }

    /**
     * Mirrors the subnetwork-outer / address-inner loop used for LogicalServer in the original template.
     */
    private function firstSubnetworkOuterMatch(Collection $subnetworks, ?string $addressList, string $nodeId, bool $edgeIntoNode): ?string
    {
        foreach ($subnetworks as $subnetwork) {
            foreach (explode(',', $addressList ?? '') as $address) {
                if ($subnetwork->contains($address)) {
                    return 'SUBNET'.$subnetwork->id.' -> '.$nodeId;
                }
            }
        }

        return null;
    }

    /**
     * Mirrors the address-outer / subnetwork-inner loop used for most device types in the original template.
     */
    private function firstAddressOuterMatch(Collection $subnetworks, ?string $addressList, string $nodeId): ?string
    {
        foreach (explode(',', $addressList ?? '') as $address) {
            foreach ($subnetworks as $subnetwork) {
                if ($subnetwork->contains($address)) {
                    return 'SUBNET'.$subnetwork->id.' -> '.$nodeId;
                }
            }
        }

        return null;
    }

    private function href(mixed $model, bool $withHref): string
    {
        return $withHref ? ' href="#'.$model->getUID().'"' : '';
    }
}
