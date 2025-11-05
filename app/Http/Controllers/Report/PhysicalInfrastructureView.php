<?php


namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Peripheral;
use App\Models\Phone;
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

class PhysicalInfrastructureView extends Controller
{
    public function generate(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->site == null) {
            $request->session()->put('site', null);
            $site = null;
            $request->session()->put('building', null);
            $buildingId = null;
        } else {
            if ($request->site != null) {
                $site = intval($request->site);
                $request->session()->put('site', $site);
            } else {
                $site = $request->session()->get('site');
            }

            if ($request->building == null) {
                $request->session()->put('building', null);
                $buildingId = null;
            } elseif ($request->building != null) {
                $buildingId = intval($request->building);
                $request->session()->put('building', $buildingId);
            } else {
                $buildingId = $request->session()->get('building');
            }
        }

        $all_sites = Site::All()->sortBy('name')->pluck('name', 'id');

        if ($site !== null) {
            $sites = Site::All()->sortBy('name')->where('id', '=', $site);

            $all_buildings = Building::All()->sortBy('name')
                ->where('site_id', '=', $site)->pluck('name', 'id');
            if ($buildingId === null) {
                $buildings = Building::query()->where('site_id', '=', $site)->orderBy('name')->get();
            } else {
                $root = Building::find($buildingId);
                if ($root !== null) {
                    $buildings = collect();
                    // Get children
                    $frontier = collect([$root]);

                    while ($frontier->isNotEmpty()) {
                        $next = collect();

                        foreach ($frontier as $node) {
                            if (! $buildings->contains('id', $node->id)) {
                                $buildings->push($node);
                                $next = $next->merge($node->buildings);
                            }
                        }

                        $frontier = $next;
                    }
                } else
                    return redirect()->back()->with('error', 'Building not found');;
            }

            // Get all bays
            $bays = Bay::All()->sortBy('name')
                ->filter(function ($item) use ($buildings) {
                    foreach ($buildings as $building) {
                        if ($item->room_id === $building->id) {
                            return true;
                        }
                    }

                    return false;
                });

            $physicalServers = PhysicalServer::All()->sortBy('name')
                ->filter(function ($item) use ($site, $buildings, $bays) {
                    if (($buildings == null) && ($item->site_id == $site)) {
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
        }

        return view('admin/reports/physical_infrastructure')
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
            ->with('physicalSecurityDevices', $physicalSecurityDevices);
    }
}
