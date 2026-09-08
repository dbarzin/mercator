<?php

namespace App\Services\Graph;

use App\Models\Bay;
use App\Models\Building;
use App\Models\Cartographer;
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
use Illuminate\Support\Collection;

class PhysicalInfrastructureGraphBuilder
{
    /**
     * @param  array{withHref?: bool, iconResolver?: callable(?int, string): string}  $options
     */
    public function buildLocationDot(
        Collection $sites,
        Collection $buildings,
        Collection $bays,
        Collection $physicalServers,
        Collection $workstations,
        Collection $storageDevices,
        Collection $peripherals,
        Collection $phones,
        Collection $physicalSwitches,
        Collection $physicalRouters,
        Collection $wifiTerminals,
        Collection $physicalSecurityDevices,
        bool $buildingSelected = false,
        array $options = []
    ): string {
        $withHref = $options['withHref'] ?? true;
        $iconResolver = $options['iconResolver'] ?? fn (?int $iconId, string $fallback) => $iconId === null
            ? $fallback
            : route('admin.documents.show', $iconId);

        $lines = ['digraph  {'];

        if (Cartographer::canAccess(Site::class) && ! $buildingSelected) {
            foreach ($sites as $site) {
                $image = $iconResolver($site->icon_id, '/images/site.png');
                $lines[] = DotNode::withImage('S'.$site->id, $image, [e($site->name)], $this->href($site, $withHref));
            }
        }

        if (Cartographer::canAccess(Building::class)) {
            foreach ($buildings as $building) {
                $image = $iconResolver($building->icon_id, '/images/building.png');
                $lines[] = DotNode::withImage('B'.$building->id, $image, [e($building->name)], $this->href($building, $withHref));

                if ($building->building_id !== null) {
                    if ($buildings->contains('id', $building->building_id)) {
                        $lines[] = 'B'.$building->building_id.' -> B'.$building->id;
                    }
                } elseif (! $buildingSelected && $building->site_id !== null && $sites->contains('id', $building->site_id)) {
                    $lines[] = 'S'.$building->site_id.' -> B'.$building->id;
                }

                foreach ($building->bays as $bay) {
                    if ($bays->contains('id', $bay->id)) {
                        $lines[] = 'B'.$building->id.' -> BAY'.$bay->id;
                    }
                }

                if (Cartographer::canAccess(Workstation::class)) {
                    if ($building->workstations()->count() >= 5) {
                        $groupWorkstation = $building->workstations()->first();
                        $lines[] = DotNode::withImage('WG'.$groupWorkstation->id, $iconResolver(null, '/images/workstation.png'), [$building->workstations()->count().' '.e(trans('cruds.workstation.title'))], $this->href($groupWorkstation, $withHref));
                        $lines[] = 'B'.$building->id.' -> WG'.$groupWorkstation->id;
                    } else {
                        foreach ($building->workstations as $workstation) {
                            if ($workstations->contains('id', $workstation->id)) {
                                $image = $iconResolver($workstation->icon_id, '/images/workstation.png');
                                $lines[] = DotNode::withImage('W'.$workstation->id, $image, [e($workstation->name)], $this->href($workstation, $withHref));
                                $lines[] = 'B'.$building->id.' -> W'.$workstation->id;
                            }
                        }
                    }
                }
            }
        }

        if (Cartographer::canAccess(Workstation::class)) {
            foreach ($workstations as $workstation) {
                if ($workstation->building_id === null && $workstation->site_id !== null && $sites->contains('id', $workstation->site_id)) {
                    $image = $iconResolver($workstation->icon_id, '/images/workstation.png');
                    $lines[] = DotNode::withImage('W'.$workstation->id, $image, [e($workstation->name)], $this->href($workstation, $withHref));
                    $lines[] = 'S'.$workstation->site_id.' -> W'.$workstation->id;
                }
            }
        }

        if (Cartographer::canAccess(Bay::class)) {
            foreach ($bays as $bay) {
                $lines[] = DotNode::withImage('BAY'.$bay->id, $iconResolver(null, '/images/bay.png'), [e($bay->name)], $this->href($bay, $withHref));

                if ($bay->building_id === null && $bay->site_id !== null && $sites->contains('id', $bay->site_id)) {
                    $lines[] = 'S'.$bay->site_id.' -> BAY'.$bay->id;
                }
            }
        }

        if (Cartographer::canAccess(PhysicalServer::class)) {
            foreach ($physicalServers as $pServer) {
                $lines[] = DotNode::withImage('PSERVER'.$pServer->id, $iconResolver(null, '/images/server.png'), [e($pServer->name)], $this->href($pServer, $withHref));
                $lines[] = $this->attachToLocation('PSERVER'.$pServer->id, $pServer->bay, $pServer->building, $pServer->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(StorageDevice::class)) {
            foreach ($storageDevices as $storageDevice) {
                $image = $iconResolver($storageDevice->icon_id, '/images/storage.png');
                $lines[] = DotNode::withImage('SD'.$storageDevice->id, $image, [e($storageDevice->name)], $this->href($storageDevice, $withHref));
                $lines[] = $this->attachToLocation('SD'.$storageDevice->id, $storageDevice->bay, $storageDevice->building, $storageDevice->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(Peripheral::class)) {
            foreach ($peripherals as $peripheral) {
                $image = $iconResolver($peripheral->icon_id, '/images/peripheral.png');
                $lines[] = DotNode::withImage('PER'.$peripheral->id, $image, [e($peripheral->name)], $this->href($peripheral, $withHref));
                $lines[] = $this->attachToLocation('PER'.$peripheral->id, $peripheral->bay, $peripheral->building, $peripheral->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(Phone::class)) {
            foreach ($phones as $phone) {
                $lines[] = DotNode::withImage('PHONE'.$phone->id, $iconResolver(null, '/images/phone.png'), [e($phone->name)], $this->href($phone, $withHref));
                $lines[] = $this->attachToLocation('PHONE'.$phone->id, null, $phone->building, $phone->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(PhysicalSwitch::class)) {
            foreach ($physicalSwitches as $switch) {
                $image = $iconResolver($switch->icon_id, '/images/switch.png');
                $lines[] = DotNode::withImage('SWITCH'.$switch->id, $image, [e($switch->name)], $this->href($switch, $withHref));
                $lines[] = $this->attachToLocation('SWITCH'.$switch->id, $switch->bay, $switch->building, $switch->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(PhysicalRouter::class)) {
            foreach ($physicalRouters as $router) {
                $lines[] = DotNode::withImage('ROUTER'.$router->id, $iconResolver(null, '/images/router.png'), [e($router->name)], $this->href($router, $withHref));
                $lines[] = $this->attachToLocation('ROUTER'.$router->id, $router->bay, $router->building, $router->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(WifiTerminal::class)) {
            foreach ($wifiTerminals as $wifiTerminal) {
                $lines[] = DotNode::withImage('WIFI'.$wifiTerminal->id, $iconResolver(null, '/images/wifi.png'), [e($wifiTerminal->name)], $this->href($wifiTerminal, $withHref));
                $lines[] = $this->attachToLocation('WIFI'.$wifiTerminal->id, null, $wifiTerminal->building, $wifiTerminal->site, $bays, $buildings, $sites);
            }
        }

        if (Cartographer::canAccess(PhysicalSecurityDevice::class)) {
            foreach ($physicalSecurityDevices as $physicalSecurityDevice) {
                $image = $iconResolver($physicalSecurityDevice->icon_id, '/images/security.png');
                $lines[] = DotNode::withImage('PSD'.$physicalSecurityDevice->id, $image, [e($physicalSecurityDevice->name)], $this->href($physicalSecurityDevice, $withHref));
                $lines[] = $this->attachToLocation('PSD'.$physicalSecurityDevice->id, $physicalSecurityDevice->bay, $physicalSecurityDevice->building, $physicalSecurityDevice->site, $bays, $buildings, $sites);
            }
        }

        $lines = array_filter($lines, fn ($line) => $line !== '');
        $lines[] = '}';

        return implode("\n", $lines);
    }

    /**
     * @return array<int, array{path: string, width: string, height: string}>
     */
    public function locationImageManifest(
        Collection $sites,
        Collection $buildings,
        Collection $workstations,
        Collection $storageDevices,
        Collection $peripherals,
        Collection $physicalSwitches,
        Collection $physicalSecurityDevices
    ): array {
        $manifest = [
            ['path' => '/images/site.png', 'width' => '64px', 'height' => '64px'],
        ];
        $this->appendCustomIcons($manifest, $sites);

        $manifest[] = ['path' => '/images/building.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $buildings);

        $manifest[] = ['path' => '/images/bay.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/server.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/workstation.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $workstations);

        $manifest[] = ['path' => '/images/storage.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $storageDevices);

        $manifest[] = ['path' => '/images/peripheral.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $peripherals);

        $manifest[] = ['path' => '/images/phone.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/switch.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $physicalSwitches);

        $manifest[] = ['path' => '/images/router.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/wifi.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/security.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $physicalSecurityDevices);

        return $manifest;
    }

    private function attachToLocation(string $nodeId, ?Bay $bay, ?Building $building, ?Site $site, Collection $bays, Collection $buildings, Collection $sites): string
    {
        if ($bay !== null && $bays->contains('id', $bay->id)) {
            return 'BAY'.$bay->id.' -> '.$nodeId;
        }
        if ($building !== null && $buildings->contains('id', $building->id)) {
            return 'B'.$building->id.' -> '.$nodeId;
        }
        if ($site !== null && $sites->contains('id', $site->id)) {
            return 'S'.$site->id.' -> '.$nodeId;
        }

        return '';
    }

    private function appendCustomIcons(array &$manifest, Collection $models): void
    {
        foreach ($models as $model) {
            if ($model->icon_id !== null) {
                $manifest[] = ['path' => route('admin.documents.show', $model->icon_id), 'width' => '64px', 'height' => '64px'];
            }
        }
    }

    private function href(mixed $model, bool $withHref): string
    {
        return $withHref ? ' href="#'.$model->getUID().'"' : '';
    }

    private const TABLEAU20 = [
        '#99cbed', '#dfe9f6', '#ffcc9f', '#ffe4c9',
        '#9fe59f', '#d6f2d0', '#efa8a8', '#ffd6d5',
        '#d4c2e5', '#e8dfee', '#d3b9d6', '#e7d7d4',
        '#f4c9e7', '#fce2ed', '#cccccc', '#e9e9e9',
        '#eded9e', '#f1f1d1', '#9aecf4', '#d8f0f5',
    ];

    private int $idColor = 0;

    /**
     * @param  array{iconResolver?: callable(?int, string): string}  $options
     */
    public function buildConnectivityDot(
        Collection $sites,
        Collection $buildings,
        Collection $bays,
        Collection $physicalServers,
        Collection $workstations,
        Collection $storageDevices,
        Collection $peripherals,
        Collection $phones,
        Collection $physicalSwitches,
        Collection $physicalRouters,
        Collection $wifiTerminals,
        Collection $physicalSecurityDevices,
        Collection $physicalLinks,
        bool $showPorts = false,
        array $options = []
    ): string {
        $this->idColor = 0;
        $iconResolver = $options['iconResolver'] ?? fn (?int $iconId, string $fallback) => $iconId === null
            ? $fallback
            : route('admin.documents.show', $iconId);

        $lines = ['digraph  {', 'fontcolor=black;', ''];

        foreach ($sites as $site) {
            $lines[] = 'subgraph SITE_'.$site->id.'  {';
            $lines[] = 'cluster=true;';
            $lines[] = 'label = "'.e($site->name).'"';
            $lines[] = 'bgcolor = "'.$this->nextColor().'"';

            $siteBuildings = $buildings->where('site_id', $site->id);

            foreach ($siteBuildings->whereNull('building_id') as $rootBuilding) {
                $lines[] = $this->buildBuildingCluster($rootBuilding, $siteBuildings, [], $physicalServers, $workstations, $storageDevices, $peripherals, $phones, $physicalSwitches, $physicalRouters, $wifiTerminals, $physicalSecurityDevices, $iconResolver);
            }

            foreach ($bays->where('site_id', $site->id)->whereNull('building_id') as $bay) {
                $lines[] = 'subgraph BAY_'.$bay->id.' {';
                $lines[] = 'cluster=true;';
                $lines[] = 'label="'.e($bay->name).'"';
                $lines[] = 'bgcolor = "'.$this->nextColor().'"';

                foreach ($physicalServers->where('bay_id', $bay->id) as $pServer) {
                    $image = $iconResolver($pServer->icon_id, '/images/server.png');
                    $lines[] = DotNode::withImage('PSERVER'.$pServer->id, $image, [e($pServer->name)], $this->href($pServer, true));
                }
                foreach ($storageDevices->where('bay_id', $bay->id) as $storageDevice) {
                    $image = $iconResolver($storageDevice->icon_id, '/images/storage.png');
                    $lines[] = DotNode::withImage('SD'.$storageDevice->id, $image, [e($storageDevice->name)], $this->href($storageDevice, true));
                }
                foreach ($peripherals->where('bay_id', $bay->id) as $peripheral) {
                    $image = $iconResolver($peripheral->icon_id, '/images/peripheral.png');
                    $lines[] = DotNode::withImage('PER'.$peripheral->id, $image, [e($peripheral->name)], $this->href($peripheral, true));
                }
                foreach ($physicalSwitches->where('bay_id', $bay->id) as $switch) {
                    $image = $iconResolver($switch->icon_id, '/images/switch.png');
                    $lines[] = DotNode::withImage('SWITCH'.$switch->id, $image, [e($switch->name)], $this->href($switch, true));
                }
                foreach ($physicalRouters->where('bay_id', $bay->id) as $router) {
                    $lines[] = DotNode::withImage('ROUTER'.$router->id, $iconResolver(null, '/images/router.png'), [e($router->name)], $this->href($router, true));
                }
                foreach ($physicalSecurityDevices->where('bay_id', $bay->id) as $physicalSecurityDevice) {
                    $image = $iconResolver($physicalSecurityDevice->icon_id, '/images/security.png');
                    $lines[] = DotNode::withImage('PSD'.$physicalSecurityDevice->id, $image, [e($physicalSecurityDevice->name)], $this->href($physicalSecurityDevice, true));
                }

                $lines[] = '}';
            }

            foreach ($physicalServers->where('site_id', $site->id)->whereNull('building_id')->whereNull('bay_id') as $pServer) {
                $image = $iconResolver($pServer->icon_id, '/images/server.png');
                $lines[] = DotNode::withImage('PSERVER'.$pServer->id, $image, [e($pServer->name)], $this->href($pServer, true));
            }
            foreach ($workstations->where('site_id', $site->id)->whereNull('building_id') as $workstation) {
                $image = $iconResolver($workstation->icon_id, '/images/workstation.png');
                $lines[] = DotNode::withImage('WORK'.$workstation->id, $image, [e($workstation->name)], $this->href($workstation, true));
            }
            foreach ($storageDevices->where('site_id', $site->id)->whereNull('building_id')->whereNull('bay_id') as $storageDevice) {
                $image = $iconResolver($storageDevice->icon_id, '/images/storage.png');
                $lines[] = DotNode::withImage('SD'.$storageDevice->id, $image, [e($storageDevice->name)], $this->href($storageDevice, true));
            }
            foreach ($peripherals->where('site_id', $site->id)->whereNull('building_id')->whereNull('bay_id') as $peripheral) {
                $image = $iconResolver($peripheral->icon_id, '/images/peripheral.png');
                $lines[] = DotNode::withImage('PER'.$peripheral->id, $image, [e($peripheral->name)], $this->href($peripheral, true));
            }
            foreach ($phones->where('site_id', $site->id)->whereNull('building_id') as $phone) {
                $lines[] = DotNode::withImage('PHONE'.$phone->id, $iconResolver(null, '/images/phone.png'), [e($phone->name)], $this->href($phone, true));
            }
            foreach ($physicalSwitches->where('site_id', $site->id)->whereNull('building_id')->whereNull('bay_id') as $switch) {
                $image = $iconResolver($switch->icon_id, '/images/switch.png');
                $lines[] = DotNode::withImage('SWITCH'.$switch->id, $image, [e($switch->name)], $this->href($switch, true));
            }
            foreach ($physicalRouters->where('site_id', $site->id)->whereNull('building_id')->whereNull('bay_id') as $router) {
                $lines[] = DotNode::withImage('ROUTER'.$router->id, $iconResolver(null, '/images/router.png'), [e($router->name)], $this->href($router, true));
            }
            foreach ($wifiTerminals->where('site_id', $site->id)->whereNull('building_id') as $wifiTerminal) {
                $lines[] = DotNode::withImage('WIFI'.$wifiTerminal->id, $iconResolver(null, '/images/wifi.png'), [e($wifiTerminal->name)], $this->href($wifiTerminal, true));
            }
            foreach ($physicalSecurityDevices->where('site_id', $site->id)->whereNull('building_id')->whereNull('bay_id') as $physicalSecurityDevice) {
                $image = $iconResolver($physicalSecurityDevice->icon_id, '/images/security.png');
                $lines[] = DotNode::withImage('PSD'.$physicalSecurityDevice->id, $image, [e($physicalSecurityDevice->name)], $this->href($physicalSecurityDevice, true));
            }

            $lines[] = '}';
        }

        // Node ids actually written above: buildBuildingCluster() draws a building/bay's own
        // devices straight from the model's relations (e.g. $building->physicalServers), not from
        // the $physicalServers collection passed into this method — so a device can be present in
        // that collection (and therefore "resolve" as a valid link endpoint below) without a
        // corresponding node ever having been declared here, e.g. when its site/building falls
        // outside the $sites/$buildings passed in. Checking against the collections alone isn't
        // enough to guarantee the edge points at a real node; checking what was actually declared
        // is.
        $declaredNodeIds = $this->extractDeclaredNodeIds($lines);

        foreach ($physicalLinks as $link) {
            $srcNode = $this->resolveLinkEndpoint($link, 'src', $peripherals, $physicalRouters, $phones, $physicalSecurityDevices, $physicalServers, $physicalSwitches, $storageDevices, $wifiTerminals, $workstations);
            $destNode = $this->resolveLinkEndpoint($link, 'dest', $peripherals, $physicalRouters, $phones, $physicalSecurityDevices, $physicalServers, $physicalSwitches, $storageDevices, $wifiTerminals, $workstations);

            $isPhysicalLink = $link->router_src_id === null
                && $link->router_dest_id === null
                && $link->logical_server_src_id === null
                && $link->logical_server_dest_id === null
                && $link->network_switch_src_id === null
                && $link->network_switch_dest_id === null;

            if ($isPhysicalLink && $srcNode !== null && $destNode !== null
                && isset($declaredNodeIds[$srcNode]) && isset($declaredNodeIds[$destNode])) {
                $edge = $srcNode.' -> '.$destNode.' [color="'.($link->color ?? 'grey').'", penwidth=2, arrowhead=none,';
                if ($showPorts) {
                    $edge .= ' taillabel="'.addslashes($link->src_port).'" headlabel="'.addslashes($link->dest_port).'",';
                }
                $edge .= ' href="'.route('admin.physical-links.show', $link->id).'"];';
                $lines[] = $edge;
            }
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    /**
     * @return array<int, array{path: string, width: string, height: string}>
     */
    public function connectivityImageManifest(
        Collection $physicalServers,
        Collection $workstations,
        Collection $storageDevices,
        Collection $peripherals,
        Collection $physicalSwitches,
        Collection $physicalSecurityDevices
    ): array {
        $manifest = [
            ['path' => '/images/site.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/building.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/bay.png', 'width' => '64px', 'height' => '64px'],
            ['path' => '/images/server.png', 'width' => '64px', 'height' => '64px'],
        ];
        $this->appendCustomIcons($manifest, $physicalServers);

        $manifest[] = ['path' => '/images/workstation.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $workstations);

        $manifest[] = ['path' => '/images/storage.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $storageDevices);

        $manifest[] = ['path' => '/images/peripheral.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $peripherals);

        $manifest[] = ['path' => '/images/phone.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/switch.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $physicalSwitches);

        $manifest[] = ['path' => '/images/router.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/wifi.png', 'width' => '64px', 'height' => '64px'];
        $manifest[] = ['path' => '/images/security.png', 'width' => '64px', 'height' => '64px'];
        $this->appendCustomIcons($manifest, $physicalSecurityDevices);

        return $manifest;
    }

    /**
     * @param  array<int, int>  $visited
     * @param  callable(?int, string): string  $iconResolver
     */
    private function buildBuildingCluster(
        Building $building,
        Collection $siteBuildings,
        array $visited,
        Collection $physicalServers,
        Collection $workstations,
        Collection $storageDevices,
        Collection $peripherals,
        Collection $phones,
        Collection $physicalSwitches,
        Collection $physicalRouters,
        Collection $wifiTerminals,
        Collection $physicalSecurityDevices,
        callable $iconResolver
    ): string {
        if (in_array($building->id, $visited, true)) {
            return '';
        }
        $visited[] = $building->id;

        $lines = [];
        $lines[] = 'subgraph ROOM_'.$building->id.' {';
        $lines[] = 'cluster=true;';
        $lines[] = 'label="'.e($building->name).'"';
        $lines[] = 'bgcolor="'.$this->nextColor().'"';

        foreach ($building->phones as $phone) {
            $lines[] = DotNode::withImage('PHONE'.$phone->id, $iconResolver(null, '/images/phone.png'), [e($phone->name)], $this->href($phone, true));
        }
        foreach ($building->workstations as $workstation) {
            $image = $iconResolver($workstation->icon_id, '/images/workstation.png');
            $lines[] = DotNode::withImage('WORK'.$workstation->id, $image, [e($workstation->name)], $this->href($workstation, true));
        }
        foreach ($building->wifiTerminals as $wifiTerminal) {
            $lines[] = DotNode::withImage('WIFI'.$wifiTerminal->id, $iconResolver(null, '/images/wifi.png'), [e($wifiTerminal->name)], $this->href($wifiTerminal, true));
        }
        foreach ($building->physicalSwitches as $switch) {
            if ($switch->bay_id === null) {
                $image = $iconResolver($switch->icon_id, '/images/switch.png');
                $lines[] = DotNode::withImage('SWITCH'.$switch->id, $image, [e($switch->name)], $this->href($switch, true));
            }
        }
        foreach ($building->physicalRouters as $router) {
            if ($router->bay_id === null) {
                $lines[] = DotNode::withImage('ROUTER'.$router->id, $iconResolver(null, '/images/router.png'), [e($router->name)], $this->href($router, true));
            }
        }
        foreach ($building->peripherals as $peripheral) {
            if ($peripheral->bay_id === null) {
                $image = $iconResolver($peripheral->icon_id, '/images/peripheral.png');
                $lines[] = DotNode::withImage('PER'.$peripheral->id, $image, [e($peripheral->name)], $this->href($peripheral, true));
            }
        }
        foreach ($building->physicalServers as $pServer) {
            if ($pServer->bay_id === null) {
                $image = $iconResolver($pServer->icon_id, '/images/server.png');
                $lines[] = DotNode::withImage('PSERVER'.$pServer->id, $image, [e($pServer->name)], $this->href($pServer, true));
            }
        }
        foreach ($building->storageDevices as $storageDevice) {
            if ($storageDevice->bay_id === null) {
                $image = $iconResolver($storageDevice->icon_id, '/images/storage.png');
                $lines[] = DotNode::withImage('SD'.$storageDevice->id, $image, [e($storageDevice->name)], $this->href($storageDevice, true));
            }
        }

        foreach ($building->bays as $bay) {
            $lines[] = 'subgraph BAY_'.$bay->id.' {';
            $lines[] = 'cluster=true;';
            $lines[] = 'label="'.e($bay->name).'"';
            $lines[] = 'bgcolor="'.$this->nextColor().'"';

            foreach ($bay->physicalServers as $pServer) {
                $image = $iconResolver($pServer->icon_id, '/images/server.png');
                $lines[] = DotNode::withImage('PSERVER'.$pServer->id, $image, [e($pServer->name)], $this->href($pServer, true));
            }
            foreach ($bay->storageDevices as $storageDevice) {
                $image = $iconResolver($storageDevice->icon_id, '/images/storage.png');
                $lines[] = DotNode::withImage('SD'.$storageDevice->id, $image, [e($storageDevice->name)], $this->href($storageDevice, true));
            }
            foreach ($bay->physicalSwitches as $switch) {
                $lines[] = DotNode::withImage('SWITCH'.$switch->id, $iconResolver(null, '/images/switch.png'), [e($switch->name)], $this->href($switch, true));
            }
            foreach ($bay->physicalSecurityDevices as $physicalSecurityDevice) {
                $image = $iconResolver($physicalSecurityDevice->icon_id, '/images/security.png');
                $lines[] = DotNode::withImage('PSD'.$physicalSecurityDevice->id, $image, [e($physicalSecurityDevice->name)], $this->href($physicalSecurityDevice, true));
            }
            foreach ($bay->physicalRouters as $router) {
                $lines[] = DotNode::withImage('ROUTER'.$router->id, $iconResolver(null, '/images/router.png'), [e($router->name)], $this->href($router, true));
            }
            foreach ($bay->peripherals as $peripheral) {
                $image = $iconResolver($peripheral->icon_id, '/images/peripheral.png');
                $lines[] = DotNode::withImage('PER'.$peripheral->id, $image, [e($peripheral->name)], $this->href($peripheral, true));
            }

            $lines[] = '}';
        }

        foreach ($siteBuildings->where('building_id', $building->id) as $childBuilding) {
            $lines[] = $this->buildBuildingCluster($childBuilding, $siteBuildings, $visited, $physicalServers, $workstations, $storageDevices, $peripherals, $phones, $physicalSwitches, $physicalRouters, $wifiTerminals, $physicalSecurityDevices, $iconResolver);
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    private function resolveLinkEndpoint(
        PhysicalLink $link,
        string $side,
        Collection $peripherals,
        Collection $physicalRouters,
        Collection $phones,
        Collection $physicalSecurityDevices,
        Collection $physicalServers,
        Collection $physicalSwitches,
        Collection $storageDevices,
        Collection $wifiTerminals,
        Collection $workstations
    ): ?string {
        $checks = [
            ['peripheral_'.$side.'_id', 'PER', $peripherals],
            ['physical_router_'.$side.'_id', 'ROUTER', $physicalRouters],
            ['phone_'.$side.'_id', 'PHONE', $phones],
            ['physical_security_device_'.$side.'_id', 'PSD', $physicalSecurityDevices],
            ['physical_server_'.$side.'_id', 'PSERVER', $physicalServers],
            ['physical_switch_'.$side.'_id', 'SWITCH', $physicalSwitches],
            ['storage_device_'.$side.'_id', 'SD', $storageDevices],
            ['wifi_terminal_'.$side.'_id', 'WIFI', $wifiTerminals],
            ['workstation_'.$side.'_id', 'WORK', $workstations],
        ];

        foreach ($checks as [$field, $prefix, $collection]) {
            $id = $link->{$field};
            if ($id !== null && $collection->contains('id', $id)) {
                return $prefix.$id;
            }
        }

        return null;
    }

    private function nextColor(): string
    {
        return self::TABLEAU20[$this->idColor++ % 20];
    }

    /**
     * Scans already-built DOT lines for node declarations ("ID [attrs]"), excluding edges (which
     * always contain "->" in this builder) and subgraph/cluster statements (which use "{", not
     * "["). Mirrors WordHelper::countGraphNodes()'s line-shape check, but keeps the ids themselves
     * rather than just a count.
     *
     * $lines entries aren't necessarily one DOT line each: buildBuildingCluster() returns a whole
     * multi-line subgraph (nested bays included) as a single string, pushed as one array element —
     * flattening through implode+explode first ensures every actual line gets checked, not just
     * the first line of each element.
     *
     * @param  array<int, string>  $lines
     * @return array<string, true>
     */
    private function extractDeclaredNodeIds(array $lines): array
    {
        $ids = [];
        foreach (explode("\n", implode("\n", $lines)) as $line) {
            $line = trim($line);
            if ($line === '' || str_contains($line, '->')) {
                continue;
            }
            if (preg_match('/^([A-Za-z_][A-Za-z0-9_]*)\s*\[/', $line, $matches)) {
                $ids[$matches[1]] = true;
            }
        }

        return $ids;
    }
}
