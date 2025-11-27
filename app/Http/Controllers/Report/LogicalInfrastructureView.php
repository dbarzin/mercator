<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Mercator\Core\Models\Certificate;
use Mercator\Core\Models\Cluster;
use Mercator\Core\Models\Container;
use Mercator\Core\Models\DhcpServer;
use Mercator\Core\Models\Dnsserver;
use Mercator\Core\Models\ExternalConnectedEntity;
use Mercator\Core\Models\Gateway;
use Mercator\Core\Models\LogicalServer;
use Mercator\Core\Models\Network;
use Mercator\Core\Models\NetworkSwitch;
use Mercator\Core\Models\Peripheral;
use Mercator\Core\Models\Phone;
use Mercator\Core\Models\PhysicalSecurityDevice;
use Mercator\Core\Models\Router;
use Mercator\Core\Models\SecurityDevice;
use Mercator\Core\Models\StorageDevice;
use Mercator\Core\Models\Subnetwork;
use Mercator\Core\Models\Vlan;
use Mercator\Core\Models\WifiTerminal;
use Mercator\Core\Models\Workstation;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LogicalInfrastructureView extends Controller
{
    /**
     * Generates data for the logical infrastructure report and returns the report view.
     *
     * Prepares networks, subnetworks and related resource collections filtered by the
     * selected network/subnetwork (from request or session). Persists 'network', 'subnetwork'
     * and 'show_ip' selections to session and applies VLAN-aware filtering to network switches
     * when a specific network is selected.
     *
     */
    public function generate(Request $request): View|RedirectResponse
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->network == null) {
            $request->session()->put('network', null);
            $network = null;
            $request->session()->put('subnetwork', null);
            $subnetwork = null;
        } else {
            if ($request->network != null) {
                $network = intval($request->network);
                $request->session()->put('network', $network);
            } else {
                $network = $request->session()->get('network');
            }

            if ($request->subnetwork == null) {
                $request->session()->put('subnetwork', null);
                $subnetwork = null;
            } elseif ($request->subnetwork != null) {
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

        $all_networks = Network::query()->orderBy('name')->pluck('name', 'id');
        if ($network !== null) {
            $all_subnetworks = Subnetwork::query()->orderBy('name')
                ->where('network_id', '=', $network)->pluck('name', 'id');

            $networks = Network::query()->orderBy('name')->where('id', '=', $network)->get();

            $externalConnectedEntities = ExternalConnectedEntity::where('network_id', '=', $network)
                ->orderBy('name')->get();

            if ($subnetwork === null) {
                $subnetworks = Subnetwork::query()->orderBy('name')
                    ->where('network_id', '=', $network)->get();
            } else {
                $root = Subnetwork::find($subnetwork);
                if ($root !== null) {
                    $subnetworks = collect();

                    // Get children
                    $frontier = collect([$root]);
                    while ($frontier->isNotEmpty()) {
                        $next = collect();
                        foreach ($frontier as $node) {
                            if (! $subnetworks->contains('id', $node->id)) {
                                $subnetworks->push($node);
                                $next = $next->merge($node->subnetworks);
                            }
                        }
                        $frontier = $next;
                    }
                } else {
                    return redirect()->back()->with('error', 'Subnetwork not found');
                }
            }

            // Get Gateways
            $gateways = Gateway::query()->orderBy('name')->get()
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->gateway_id === $item->id) {
                            return true;
                        }
                    }

                    return false;
                });

            // Get VLANS
            $vlans = Vlan::query()->orderBy('name')->get()
                ->filter(function ($item) use ($subnetworks) {
                    return $subnetworks->pluck('vlan_id')->contains($item->id);
                });

            // Get NetworkSwitches
            $networkSwitches = NetworkSwitch::query()->orderBy('name')->get()
                ->filter(function ($item) use ($subnetworks, $vlans) {
                    if ($item->vlans()->count() > 0) {
                        foreach ($item->vlans as $v) {
                            if ($vlans->pluck('id')->contains($v->id)) {
                                return true;
                            }
                        }
                    } else {
                        foreach (explode(',', $item->ip) as $ip) {
                            foreach ($subnetworks as $subnetwork) {
                                if ($subnetwork->contains($ip)) {
                                    return true;
                                }
                            }
                        }
                    }

                    return false;
                });

            // Get Workstations
            $workstations = Workstation::query()->orderBy('name')->get()
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
            $wifiTerminals = WifiTerminal::query()->orderBy('name')->get()
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
            $phones = Phone::query()->orderBy('name')->get()
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
            $peripherals = Peripheral::query()->orderBy('name')->get()
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
            $physicalSecurityDevices = PhysicalSecurityDevice::query()->orderBy('name')->get()
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
            $routers = Router::query()->orderBy('name')->get()
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
            $securityDevices = PhysicalSecurityDevice::query()->orderBy('name')->get()
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->contains($item->address_ip)) {
                            return true;
                        }
                    }

                    return false;
                });

            // Get StorageDevices
            $storageDevices = StorageDevice::query()->orderBy('name')->get()
                ->filter(function ($item) use ($subnetworks) {
                    foreach ($subnetworks as $subnetwork) {
                        if ($subnetwork->contains($item->address_ip)) {
                            return true;
                        }
                    }

                    return false;
                });

            // Get DHCP Servers
            $dhcpServers = DhcpServer::query()->orderBy('name')->get()
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
            $dnsservers = Dnsserver::query()->orderBy('name')->get()
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

            $clusters = Cluster::query()->orderBy('name')->get();

            // Get Logical serveurs
            $logicalServers = LogicalServer::query()->orderBy('name')->get()
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
            $certificates = Certificate::query()->with('logical_servers')->orderBy('name')->get()
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
            $containers = Container::query()->with('logicalServers')->orderBy('name')->get()
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

        } else {
            $all_subnetworks = Subnetwork::query()->orderBy('name')->pluck('name', 'id');

            // all
            $networks = Network::query()->orderBy('name')->get();
            $subnetworks = Subnetwork::query()->orderBy('name')->get();
            $gateways = Gateway::query()->orderBy('name')->get();
            $externalConnectedEntities = ExternalConnectedEntity::query()->orderBy('name')->get();
            $networkSwitches = NetworkSwitch::query()->orderBy('name')->get();
            $workstations = Workstation::query()->orderBy('name')->with('site','building')->get();
            $wifiTerminals = WifiTerminal::query()->orderBy('name')->with('site','building')->get();
            $phones = Phone::query()->orderBy('name')->with('site','building')->get();
            $physicalSecurityDevices = PhysicalSecurityDevice::query()->orderBy('name')->with('site','building')->get();
            $peripherals = Peripheral::query()->orderBy('name')->with('site','building','bay')->get();
            $routers = Router::query()->orderBy('name')->get();
            $securityDevices = SecurityDevice::query()->orderBy('name')->get();
            $storageDevices = StorageDevice::query()->orderBy('name')->with('site','building','bay')->get();
            $dhcpServers = DhcpServer::query()->orderBy('name')->get();
            $dnsservers = Dnsserver::query()->orderBy('name')->get();
            $clusters = Cluster::query()->orderBy('name')->get();
            $logicalServers = LogicalServer::query()->orderBy('name')->get();
            $containers = Container::query()->orderBy('name')->get();
            $certificates = Certificate::query()->orderBy('name')->get();
            $vlans = Vlan::query()->orderBy('name')->with('subnetworks')->get();

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
