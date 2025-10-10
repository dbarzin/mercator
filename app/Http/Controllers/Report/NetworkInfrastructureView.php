<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Peripheral;
use App\Models\Phone;
use App\Models\PhysicalLink;
use App\Models\PhysicalRouter;
use App\Models\PhysicalSecurityDevice;
use App\Models\PhysicalServer;
use App\Models\PhysicalSwitch;
use App\Models\Site;
use App\Models\StorageDevice;
use App\Models\WifiTerminal;
use App\Models\Workstation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkInfrastructureView extends Controller
{
    public function generate(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->site === null) {
            $request->session()->put('site', null);
            $siteId = null;
            $request->session()->put('building', null);
            $building = null;
        } else {
            if ($request->site !== null) {
                $siteId = intval($request->site);
                $request->session()->put('site', $siteId);
            } else {
                $siteId = $request->session()->get('site');
            }

            if ($request->buildings === null) {
                $request->session()->put('buildings', null);
                $buildingIds = null;
            } elseif ($request->buildings !== null) {
                $buildingIds = $request->buildings;
                $request->session()->put('buildings', $buildingIds);
            } else {
                $buildingIds = $request->session()->get('buildings');
            }
        }

        $all_sites = Site::All()->sortBy('name')->pluck('name', 'id');

        if ($siteId !== null) {
            $sites = Site::where('id', '=', $siteId)->get();
            $site = $sites->first();

            $all_buildings = Building::query()
                ->where('site_id',$siteId)
                ->pluck('name', 'id');

            if ($buildingIds === null || (count($buildingIds) === 0)) {
                $buildings = Building::where('site_id', '=', $site->id)
                    ->orderBy('name')->get();
            } else {
                // ----------------------------------------------------
                /*
                $roots = Building::whereIn('id', $buildingIds)
                    ->with('allChildren')
                    ->get();

                $buildings = collect();
                $seen = [];

                $flatten = function ($node) use (&$flatten, &$buildings, &$seen) {
                    if (isset($seen[$node->id])) {
                        return;
                    }
                    $seen[$node->id] = true;
                    $buildings->push($node);
                    foreach ($node->allChildren as $child) {
                        $flatten($child);
                    }
                };

                foreach ($roots as $root) {
                    $flatten($root);
                }

                $buildings = $buildings->unique('id')->values();
                */

                $buildings = collect();  // résultat final (Collection<Building>)
                $seen = [];              // set d'IDs déjà vus (évite doublons)

                $roots = Building::with('buildings')->findMany($buildingIds);

// 2) Descendants (BFS) pour toutes les racines
                $frontier = collect($roots);

                while ($frontier->isNotEmpty()) {
                    $next = collect();

                    foreach ($frontier as $node) {
                        if (!isset($seen[$node->id])) {
                            $seen[$node->id] = true;
                            $buildings->push($node);

                            // enfants directs (relation hasMany 'buildings')
                            // si tu as une relation récursive 'childrenRecursive', tu peux l'utiliser
                            $next = $next->merge($node->buildings);
                        }
                    }

                    $frontier = $next;
                }

// 3) Parents (par paliers) jusqu'à ce que building_id soit null
                $pending = $buildings
                    ->pluck('building_id')   // parents directs des nœuds déjà collectés
                    ->filter()
                    ->unique()
                    ->values();

                while ($pending->isNotEmpty()) {
                    // éviter de recharger des parents déjà vus
                    $toFetch = $pending->reject(fn ($id) => isset($seen[$id]))->values();
                    if ($toFetch->isEmpty()) {
                        break;
                    }

                    // Charger en une requête les parents du palier courant
                    $parents = Building::whereIn('id', $toFetch)->get();

                    $nextIds = [];

                    foreach ($parents as $parent) {
                        if (!isset($seen[$parent->id])) {
                            $seen[$parent->id] = true;
                            $buildings->push($parent);

                            if (!is_null($parent->building_id)) {
                                $nextIds[] = $parent->building_id; // remonte d'un cran
                            }
                        }
                    }

                    $pending = collect($nextIds)->filter()->unique()->values();
                }

                $buildings = $buildings->values();

                // ----------------------------------------------------
            }

            $buildingIds = $buildings->pluck('id');

            $bays = Bay::query()
                ->whereIn('room_id', $buildingIds)
                ->orderBy('name')
                ->get();
            $bayIds = $bays->pluck('id');

            /*
            $bays = Bay::All()->sortBy('name')
            ->filter(function ($item) use ($buildings) {
                foreach ($buildings as $building) {
                    if ($item->room_id === $building->id) {
                        return true;
                    }
                }

                return false;
            });
            */
            $physicalServers = PhysicalServer::query()
                ->where(function ($q) use ($buildingIds, $bayIds) {
                    $q->where(function ($q) use ($buildingIds) {
                        $q->whereNull('bay_id')
                            ->whereIn('building_id', $buildingIds);
                    });
                    if ($bayIds->isNotEmpty()) {
                        $q->orWhereIn('bay_id', $bayIds);
                    }
                })
                ->orderBy('name')
                ->get();
            /*
            $physicalServers = PhysicalServer::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($buildings === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });
            */
            $workstations = Workstation::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings) {
                    if (($item->building_id === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    foreach ($buildings as $building) {
                        if ($item->building_id === $building->id) {
                            return true;
                        }
                    }

                    return false;
                });

            $storageDevices = StorageDevice::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $physicalSwitches = PhysicalSwitch::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $peripherals = Peripheral::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $phones = Phone::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings) {
                    if (($item->building_id === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    foreach ($buildings as $building) {
                        if ($item->building_id === $building->id) {
                            return true;
                        }
                    }

                    return false;
                });

            $physicalRouters = PhysicalRouter::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $wifiTerminals = WifiTerminal::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings) {
                    if (($item->building_id === null) && ($item->site_id === $site)) {
                        return true;
                    }
                    foreach ($buildings as $building) {
                        if ($item->building_id === $building->id) {
                            return true;
                        }
                    }

                    return false;
                });

            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($item->bay_id === null) &&
                        ($item->building_id === null) &&
                        ($item->site_id === $site)) {
                        return true;
                    }
                    if ($item->bay_id === null) {
                        foreach ($buildings as $building) {
                            if ($item->building_id === $building->id) {
                                return true;
                            }
                        }
                    } else {
                        foreach ($bays as $bay) {
                            if ($item->bay_id === $bay->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // Filter physicalLinks on selected objects
            $physicalLinks = PhysicalLink::All()->sortBy('name')
                ->filter(function ($item) use (
                    $physicalRouters,
                    $physicalServers,
                    $workstations,
                    $storageDevices,
                    $physicalSwitches,
                    $peripherals,
                    $wifiTerminals,
                    $phones
                ) {
                    // Routers
                    if ($item->physical_router_src_id !== null) {
                        $found = false;
                        foreach ($physicalRouters as $router) {
                            if ($item->physical_router_src_id === $router->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->physical_router_dest_id !== null) {
                        $found = false;
                        foreach ($physicalRouters as $router) {
                            if ($item->physical_router_dest_id === $router->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Switches
                    if ($item->physical_switch_src_id !== null) {
                        $found = false;
                        foreach ($physicalSwitches as $physicalSwitch) {
                            if ($item->physical_switch_src_id === $physicalSwitch->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->physical_switch_dest_id !== null) {
                        $found = false;
                        foreach ($physicalSwitches as $physicalSwitch) {
                            if ($item->physical_switch_dest_id === $physicalSwitch->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Servers
                    if ($item->physical_server_src_id !== null) {
                        $found = false;
                        foreach ($physicalServers as $server) {
                            if ($item->physical_server_src_id === $server->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->physical_server_dest_id !== null) {
                        $found = false;
                        foreach ($physicalServers as $server) {
                            if ($item->physical_server_dest_id === $server->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Workstations
                    if ($item->workstation_src_id !== null) {
                        $found = false;
                        foreach ($workstations as $workstation) {
                            if ($item->workstation_src_id === $workstation->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->workstation_dest_id !== null) {
                        $found = false;
                        foreach ($workstations as $workstation) {
                            if ($item->workstation_dest_id === $workstation->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Peripheral
                    if ($item->peripheral_src_id !== null) {
                        $found = false;
                        foreach ($peripherals as $peripheral) {
                            if ($item->peripheral_src_id === $peripheral->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->peripheral_dest_id !== null) {
                        $found = false;
                        foreach ($peripherals as $peripheral) {
                            if ($item->peripheral_dest_id === $peripheral->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Storage
                    if ($item->storage_device_src_id !== null) {
                        $found = false;
                        foreach ($storageDevices as $storageDevice) {
                            if ($item->storage_device_src_id === $storageDevice->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->storage_device_dest_id !== null) {
                        $found = false;
                        foreach ($storageDevices as $storageDevice) {
                            if ($item->storage_device_dest_id === $storageDevice->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Wifi
                    if ($item->wifi_terminal_src_id !== null) {
                        $found = false;
                        foreach ($wifiTerminals as $wifiTerminal) {
                            if ($item->wifi_terminal_src_id === $wifiTerminal->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->wifi_terminal_dest_id !== null) {
                        $found = false;
                        foreach ($wifiTerminals as $wifiTerminal) {
                            if ($item->wifi_terminal_dest_id === $wifiTerminal->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    // Phones
                    if ($item->phone_src_id !== null) {
                        $found = false;
                        foreach ($phones as $phone) {
                            if ($item->phone_src_id === $phone->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }
                    if ($item->phone_dest_id !== null) {
                        $found = false;
                        foreach ($phones as $phone) {
                            if ($item->phone_dest_id === $phone->id) {
                                $found = true;
                                break;
                            }
                        }
                        if (! $found) {
                            return false;
                        }
                    }

                    return true;
                });
        } else {
            $sites = Site::All()->sortBy('name');
            $buildings = Building::All()->sortBy('name');
            $all_buildings = null;
            $bays = Bay::All()->sortBy('name');
            $physicalServers = PhysicalServer::All()->sortBy('name');
            $workstations = Workstation::All()->sortBy('name');
            $storageDevices = StorageDevice::All()->sortBy('name');
            $peripherals = Peripheral::All()->sortBy('name');
            $phones = Phone::All()->sortBy('name');
            $physicalSwitches = PhysicalSwitch::All()->sortBy('name');
            $physicalRouters = PhysicalRouter::All()->sortBy('name');
            $wifiTerminals = WifiTerminal::All()->sortBy('name');
            $physicalSecurityDevices = PhysicalSecurityDevice::All()->sortBy('name');
            $physicalLinks = PhysicalLink::All()->sortBy('name');
        }

        return view('admin/reports/network_infrastructure')
            ->with('all_sites', $all_sites)
            ->with('sites', $sites)
            ->with('all_buildings', $all_buildings)
            ->with('buildings', $buildings)
            ->with('bays', $bays)
            ->with('physicalServers', $physicalServers)
            ->with('workstations', $workstations)
            ->with('storageDevices', $storageDevices)
            ->with('peripherals', $peripherals)
            ->with('phones', $phones)
            ->with('physicalSwitches', $physicalSwitches)
            ->with('physicalRouters', $physicalRouters)
            ->with('wifiTerminals', $wifiTerminals)
            ->with('physicalSecurityDevices', $physicalSecurityDevices)
            ->with('physicalLinks', $physicalLinks);
    }

}
