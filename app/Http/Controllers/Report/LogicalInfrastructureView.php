<?php

declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
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
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogicalInfrastructureView extends Controller
{
    public function generate(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
}
