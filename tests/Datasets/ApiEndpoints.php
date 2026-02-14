<?php

use Mercator\Core\Models\Activity;
use Mercator\Core\Models\Actor;
use Mercator\Core\Models\AdminUser;
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
use Mercator\Core\Models\DataProcessing;
use Mercator\Core\Models\DomaineAd;
use Mercator\Core\Models\Entity;
use Mercator\Core\Models\ExternalConnectedEntity;
use Mercator\Core\Models\Flux;
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
use Mercator\Core\Models\SecurityControl;
use Mercator\Core\Models\SecurityDevice;
use Mercator\Core\Models\Site;
use Mercator\Core\Models\StorageDevice;
use Mercator\Core\Models\Subnetwork;
use Mercator\Core\Models\Task;
use Mercator\Core\Models\Vlan;
use Mercator\Core\Models\Wan;
use Mercator\Core\Models\WifiTerminal;
use Mercator\Core\Models\Workstation;
use Mercator\Core\Models\ZoneAdmin;

dataset('api_endpoints', [
    'data-processings' => [
        [
            'route' => 'data-processings',
            'model' => DataProcessing::class,
        ]
    ],
    'security-controls' => [
        [
            'route' => 'security-controls',
            'model' => SecurityControl::class,
        ]
    ],
    'entities' => [
        [
            'route' => 'entities',
            'model' => Entity::class,
        ]
    ],
    'relations' => [
        [
            'route' => 'relations',
            'model' => Relation::class,
        ]
    ],
    'macro-processuses' => [
        [
            'route' => 'macro-processuses',
            'model' => MacroProcessus::class,
        ]
    ],
    'processes' => [
        [
            'route' => 'processes',
            'model' => Process::class,
        ]
    ],
    'operations' => [
        [
            'route' => 'operations',
            'model' => Operation::class,
        ]
    ],
    'actors' => [
        [
            'route' => 'actors',
            'model' => Actor::class,
        ]
    ],
    'activities' => [
        [
            'route' => 'activities',
            'model' => Activity::class,
        ]
    ],
    'tasks' => [
        [
            'route' => 'tasks',
            'model' => Task::class,
        ]
    ],
    'information' => [
        [
            'route' => 'information',
            'model' => Information::class,
        ]
    ],
    'applications' => [
        [
            'route' => 'applications',
            'model' => MApplication::class,
        ]
    ],
    'application-blocks' => [
        [
            'route' => 'application-blocks',
            'model' => ApplicationBlock::class,
        ]
    ],
    'application-modules' => [
        [
            'route' => 'application-modules',
            'model' => ApplicationModule::class,
        ]
    ],
    'application-services' => [
        [
            'route' => 'application-services',
            'model' => ApplicationService::class,
        ]
    ],
    'databases' => [
        [
            'route' => 'databases',
            'model' => Database::class,
        ]
    ],
    'fluxes' => [
        [
            'route' => 'fluxes',
            'model' => Flux::class,
        ]
    ],
    'zone-admins' => [
        [
            'route' => 'zone-admins',
            'model' => ZoneAdmin::class,
        ]
    ],
    'annuaires' => [
        [
            'route' => 'annuaires',
            'model' => Annuaire::class,
        ]
    ],
    'forest-ads' => [
        [
            'route' => 'forest-ads',
            'model' => ForestAd::class,
        ]
    ],
    'domaine-ads' => [
        [
            'route' => 'domaine-ads',
            'model' => DomaineAd::class,
        ]
    ],
    'admin-users' => [
        [
            'route' => 'admin-users',
            'model' => AdminUser::class,
        ]
    ],
    'networks' => [
        [
            'route' => 'networks',
            'model' => Network::class,
        ]
    ],
    'subnetworks' => [
        [
            'route' => 'subnetworks',
            'model' => Subnetwork::class,
        ]
    ],
    'gateways' => [
        [
            'route' => 'gateways',
            'model' => Gateway::class,
        ]
    ],
    'external-connected-entities' => [
        [
            'route' => 'external-connected-entities',
            'model' => ExternalConnectedEntity::class,
        ]
    ],
    'network-switches' => [
        [
            'route' => 'network-switches',
            'model' => NetworkSwitch::class,
        ]
    ],
    'routers' => [
        [
            'route' => 'routers',
            'model' => Router::class,
        ]
    ],
    'security-devices' => [
        [
            'route' => 'security-devices',
            'model' => SecurityDevice::class,
        ]
    ],
    'clusters' => [
        [
            'route' => 'clusters',
            'model' => Cluster::class,
        ]
    ],
    'containers' => [
        [
            'route' => 'containers',
            'model' => Container::class,
        ]
    ],
    'logical-servers' => [
        [
            'route' => 'logical-servers',
            'model' => LogicalServer::class,
        ]
    ],
    'logical-flows' => [
        [
            'route' => 'logical-flows',
            'model' => LogicalFlow::class,
        ]
    ],
    'certificates' => [
        [
            'route' => 'certificates',
            'model' => Certificate::class,
        ]
    ],
    'sites' => [
        [
            'route' => 'sites',
            'model' => Site::class,
        ]
    ],
    'buildings' => [
        [
            'route' => 'buildings',
            'model' => Building::class,
        ]
    ],
    'bays' => [
        [
            'route' => 'bays',
            'model' => Bay::class,
        ]
    ],
    'physical-servers' => [
        [
            'route' => 'physical-servers',
            'model' => PhysicalServer::class,
        ]
    ],
    'workstations' => [
        [
            'route' => 'workstations',
            'model' => Workstation::class,
        ]
    ],
    'storage-devices' => [
        [
            'route' => 'storage-devices',
            'model' => StorageDevice::class,
        ]
    ],
    'peripherals' => [
        [
            'route' => 'peripherals',
            'model' => Peripheral::class,
        ]
    ],
    'phones' => [
        [
            'route' => 'phones',
            'model' => Phone::class,
        ]
    ],
    'physical-switches' => [
        [
            'route' => 'physical-switches',
            'model' => PhysicalSwitch::class,
        ]
    ],
    'physical-routers' => [
        [
            'route' => 'physical-routers',
            'model' => PhysicalRouter::class,
        ]
    ],
    'wifi-terminals' => [
        [
            'route' => 'wifi-terminals',
            'model' => WifiTerminal::class,
        ]
    ],
    'physical-security-devices' => [
        [
            'route' => 'physical-security-devices',
            'model' => PhysicalSecurityDevice::class,
        ]
    ],
    'wans' => [
        [
            'route' => 'wans',
            'model' => Wan::class,
        ]
    ],
    'mans' => [
        [
            'route' => 'mans',
            'model' => Man::class,
        ]
    ],
    'lans' => [
        [
            'route' => 'lans',
            'model' => Lan::class,
        ]
    ],
    'vlans' => [
        [
            'route' => 'vlans',
            'model' => Vlan::class,
        ]
    ],
    'physical-links' => [
        [
            'route' => 'physical-links',
            'model' => PhysicalLink::class,
        ]
    ],
]);
