<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Entities
    Route::post('entities/media', 'EntityApiController@storeMedia')->name('entities.storeMedia');
    Route::apiResource('entities', 'EntityApiController');

    // Relations
    Route::post('relations/media', 'RelationApiController@storeMedia')->name('relations.storeMedia');
    Route::apiResource('relations', 'RelationApiController');

    // Processes
    Route::post('processes/media', 'ProcessApiController@storeMedia')->name('processes.storeMedia');
    Route::apiResource('processes', 'ProcessApiController');

    // Operations
    Route::post('operations/media', 'OperationApiController@storeMedia')->name('operations.storeMedia');
    Route::apiResource('operations', 'OperationApiController');

    // Actors
    Route::apiResource('actors', 'ActorApiController');

    // Activities
    Route::post('activities/media', 'ActivityApiController@storeMedia')->name('activities.storeMedia');
    Route::apiResource('activities', 'ActivityApiController');

    // Tasks
    Route::apiResource('tasks', 'TaskApiController');

    // Information
    Route::post('information/media', 'InformationApiController@storeMedia')->name('information.storeMedia');
    Route::apiResource('information', 'InformationApiController');

    // Application Blocks
    Route::post('application-blocks/media', 'ApplicationBlockApiController@storeMedia')->name('application-blocks.storeMedia');
    Route::apiResource('application-blocks', 'ApplicationBlockApiController');

    // Applications
    Route::apiResource('applications', 'ApplicationApiController');

    // M Applications
    Route::post('m-applications/media', 'MApplicationApiController@storeMedia')->name('m-applications.storeMedia');
    Route::apiResource('m-applications', 'MApplicationApiController');

    // Application Services
    Route::post('application-services/media', 'ApplicationServiceApiController@storeMedia')->name('application-services.storeMedia');
    Route::apiResource('application-services', 'ApplicationServiceApiController');

    // Databases
    Route::post('databases/media', 'DatabaseApiController@storeMedia')->name('databases.storeMedia');
    Route::apiResource('databases', 'DatabaseApiController');

    // Fluxes
    Route::apiResource('fluxes', 'FluxApiController');

    // Zone Admins
    Route::post('zone-admins/media', 'ZoneAdminApiController@storeMedia')->name('zone-admins.storeMedia');
    Route::apiResource('zone-admins', 'ZoneAdminApiController');

    // Annuaires
    Route::post('annuaires/media', 'AnnuaireApiController@storeMedia')->name('annuaires.storeMedia');
    Route::apiResource('annuaires', 'AnnuaireApiController');

    // Forest Ads
    Route::post('forest-ads/media', 'ForestAdApiController@storeMedia')->name('forest-ads.storeMedia');
    Route::apiResource('forest-ads', 'ForestAdApiController');

    // Domaine Ads
    Route::post('domaine-ads/media', 'DomaineAdApiController@storeMedia')->name('domaine-ads.storeMedia');
    Route::apiResource('domaine-ads', 'DomaineAdApiController');

    // Networks
    Route::post('networks/media', 'NetworkApiController@storeMedia')->name('networks.storeMedia');
    Route::apiResource('networks', 'NetworkApiController');

    // Subnetwords
    Route::post('subnetwords/media', 'SubnetwordApiController@storeMedia')->name('subnetwords.storeMedia');
    Route::apiResource('subnetwords', 'SubnetwordApiController');

    // Gateways
    Route::post('gateways/media', 'GatewayApiController@storeMedia')->name('gateways.storeMedia');
    Route::apiResource('gateways', 'GatewayApiController');

    // External Connected Entities
    Route::apiResource('external-connected-entities', 'ExternalConnectedEntityApiController');

    // Network Switches
    Route::post('network-switches/media', 'NetworkSwitchApiController@storeMedia')->name('network-switches.storeMedia');
    Route::apiResource('network-switches', 'NetworkSwitchApiController');

    // Routers
    Route::post('routers/media', 'RouterApiController@storeMedia')->name('routers.storeMedia');
    Route::apiResource('routers', 'RouterApiController');

    // Security Devices
    Route::post('security-devices/media', 'SecurityDeviceApiController@storeMedia')->name('security-devices.storeMedia');
    Route::apiResource('security-devices', 'SecurityDeviceApiController');

    // Dhcp Servers
    Route::post('dhcp-servers/media', 'DhcpServerApiController@storeMedia')->name('dhcp-servers.storeMedia');
    Route::apiResource('dhcp-servers', 'DhcpServerApiController');

    // Dnsservers
    Route::post('dnsservers/media', 'DnsserverApiController@storeMedia')->name('dnsservers.storeMedia');
    Route::apiResource('dnsservers', 'DnsserverApiController');

    // Logical Servers
    Route::post('logical-servers/media', 'LogicalServerApiController@storeMedia')->name('logical-servers.storeMedia');
    Route::apiResource('logical-servers', 'LogicalServerApiController');

    // Sites
    Route::post('sites/media', 'SiteApiController@storeMedia')->name('sites.storeMedia');
    Route::apiResource('sites', 'SiteApiController');

    // Buildings
    Route::post('buildings/media', 'BuildingApiController@storeMedia')->name('buildings.storeMedia');
    Route::apiResource('buildings', 'BuildingApiController');

    // Bays
    Route::post('bays/media', 'BayApiController@storeMedia')->name('bays.storeMedia');
    Route::apiResource('bays', 'BayApiController');

    // Physical Servers
    Route::post('physical-servers/media', 'PhysicalServerApiController@storeMedia')->name('physical-servers.storeMedia');
    Route::apiResource('physical-servers', 'PhysicalServerApiController');

    // Workstations
    Route::post('workstations/media', 'WorkstationApiController@storeMedia')->name('workstations.storeMedia');
    Route::apiResource('workstations', 'WorkstationApiController');

    // Storage Devices
    Route::post('storage-devices/media', 'StorageDeviceApiController@storeMedia')->name('storage-devices.storeMedia');
    Route::apiResource('storage-devices', 'StorageDeviceApiController');

    // Peripherals
    Route::post('peripherals/media', 'PeripheralApiController@storeMedia')->name('peripherals.storeMedia');
    Route::apiResource('peripherals', 'PeripheralApiController');

    // Phones
    Route::post('phones/media', 'PhoneApiController@storeMedia')->name('phones.storeMedia');
    Route::apiResource('phones', 'PhoneApiController');

    // Physical Switches
    Route::post('physical-switches/media', 'PhysicalSwitchApiController@storeMedia')->name('physical-switches.storeMedia');
    Route::apiResource('physical-switches', 'PhysicalSwitchApiController');

    // Physical Routers
    Route::post('physical-routers/media', 'PhysicalRouterApiController@storeMedia')->name('physical-routers.storeMedia');
    Route::apiResource('physical-routers', 'PhysicalRouterApiController');

    // Wifi Terminals
    Route::post('wifi-terminals/media', 'WifiTerminalApiController@storeMedia')->name('wifi-terminals.storeMedia');
    Route::apiResource('wifi-terminals', 'WifiTerminalApiController');

    // Physical Security Devices
    Route::post('physical-security-devices/media', 'PhysicalSecurityDeviceApiController@storeMedia')->name('physical-security-devices.storeMedia');
    Route::apiResource('physical-security-devices', 'PhysicalSecurityDeviceApiController');

    // Wans
    Route::apiResource('wans', 'WanApiController');

    // Men
    Route::apiResource('men', 'ManApiController');

    // Lans
    Route::apiResource('lans', 'LanApiController');

    // Vlans
    Route::apiResource('vlans', 'VlanApiController');

    // Application Modules
    Route::post('application-modules/media', 'ApplicationModuleApiController@storeMedia')->name('application-modules.storeMedia');
    Route::apiResource('application-modules', 'ApplicationModuleApiController');

    // Macro Processuses
    Route::post('macro-processuses/media', 'MacroProcessusApiController@storeMedia')->name('macro-processuses.storeMedia');
    Route::apiResource('macro-processuses', 'MacroProcessusApiController');
});
