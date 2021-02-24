<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});


// Test page
Route::get('/test', function () {
    return view('test');
});

Auth::routes(['register' => false]);

// Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    // Dashboard
    Route::get('/', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Entities
    Route::delete('entities/destroy', 'EntityController@massDestroy')->name('entities.massDestroy');
    Route::resource('entities', 'EntityController');

    // Relations
    Route::delete('relations/destroy', 'RelationController@massDestroy')->name('relations.massDestroy');
    Route::resource('relations', 'RelationController');

    // Processes
    Route::delete('processes/destroy', 'ProcessController@massDestroy')->name('processes.massDestroy');
    Route::resource('processes', 'ProcessController');

    // Operations
    Route::delete('operations/destroy', 'OperationController@massDestroy')->name('operations.massDestroy');
    Route::resource('operations', 'OperationController');

    // Actors
    Route::delete('actors/destroy', 'ActorController@massDestroy')->name('actors.massDestroy');
    Route::resource('actors', 'ActorController');

    // Activities
    Route::delete('activities/destroy', 'ActivityController@massDestroy')->name('activities.massDestroy');
    Route::resource('activities', 'ActivityController');

    // Tasks
    Route::delete('tasks/destroy', 'TaskController@massDestroy')->name('tasks.massDestroy');
    Route::resource('tasks', 'TaskController');

    // Information
    Route::delete('information/destroy', 'InformationController@massDestroy')->name('information.massDestroy');
    Route::resource('information', 'InformationController');

    // Application Blocks
    Route::delete('application-blocks/destroy', 'ApplicationBlockController@massDestroy')->name('application-blocks.massDestroy');
    Route::resource('application-blocks', 'ApplicationBlockController');

    // Applications
    Route::delete('applications/destroy', 'MApplicationController@massDestroy')->name('applications.massDestroy');
    Route::resource('applications', 'MApplicationController');

    // Application Services
    Route::delete('application-services/destroy', 'ApplicationServiceController@massDestroy')->name('application-services.massDestroy');
    Route::resource('application-services', 'ApplicationServiceController');

    // Databases
    Route::delete('databases/destroy', 'DatabaseController@massDestroy')->name('databases.massDestroy');
    Route::resource('databases', 'DatabaseController');

    // Fluxes
    Route::delete('fluxes/destroy', 'FluxController@massDestroy')->name('fluxes.massDestroy');
    Route::resource('fluxes', 'FluxController');

    // Zone Admins
    Route::delete('zone-admins/destroy', 'ZoneAdminController@massDestroy')->name('zone-admins.massDestroy');
    Route::resource('zone-admins', 'ZoneAdminController');

    // Annuaires
    Route::delete('annuaires/destroy', 'AnnuaireController@massDestroy')->name('annuaires.massDestroy');
    Route::resource('annuaires', 'AnnuaireController');

    // Forest Ads
    Route::delete('forest-ads/destroy', 'ForestAdController@massDestroy')->name('forest-ads.massDestroy');
    Route::resource('forest-ads', 'ForestAdController');

    // Domaine Ads
    Route::delete('domaine-ads/destroy', 'DomaineAdController@massDestroy')->name('domaine-ads.massDestroy');
    Route::resource('domaine-ads', 'DomaineAdController');

    // Networks
    Route::delete('networks/destroy', 'NetworkController@massDestroy')->name('networks.massDestroy');
    Route::resource('networks', 'NetworkController');

    // Subnetworks
    Route::delete('subnetworks/destroy', 'SubnetwordController@massDestroy')->name('subnetworks.massDestroy');
    Route::resource('subnetworks', 'SubnetwordController');

    // Gateways
    Route::delete('gateways/destroy', 'GatewayController@massDestroy')->name('gateways.massDestroy');
    Route::resource('gateways', 'GatewayController');

    // External Connected Entities
    Route::delete('external-connected-entities/destroy', 'ExternalConnectedEntityController@massDestroy')->name('external-connected-entities.massDestroy');
    Route::resource('external-connected-entities', 'ExternalConnectedEntityController');

    // Network Switches
    Route::delete('network-switches/destroy', 'NetworkSwitchController@massDestroy')->name('network-switches.massDestroy');
    Route::resource('network-switches', 'NetworkSwitchController');

    // Routers
    Route::delete('routers/destroy', 'RouterController@massDestroy')->name('routers.massDestroy');
    Route::resource('routers', 'RouterController');

    // Security Devices
    Route::delete('security-devices/destroy', 'SecurityDeviceController@massDestroy')->name('security-devices.massDestroy');
    Route::resource('security-devices', 'SecurityDeviceController');

    // Dhcp Servers
    Route::delete('dhcp-servers/destroy', 'DhcpServerController@massDestroy')->name('dhcp-servers.massDestroy');
    Route::resource('dhcp-servers', 'DhcpServerController');

    // Dnsservers
    Route::delete('dnsservers/destroy', 'DnsserverController@massDestroy')->name('dnsservers.massDestroy');
    Route::resource('dnsservers', 'DnsserverController');

    // Logical Servers
    Route::delete('logical-servers/destroy', 'LogicalServerController@massDestroy')->name('logical-servers.massDestroy');
    Route::resource('logical-servers', 'LogicalServerController');

    // Sites
    Route::delete('sites/destroy', 'SiteController@massDestroy')->name('sites.massDestroy');
    Route::resource('sites', 'SiteController');

    // Buildings
    Route::delete('buildings/destroy', 'BuildingController@massDestroy')->name('buildings.massDestroy');
    Route::resource('buildings', 'BuildingController');

    // Bays
    Route::delete('bays/destroy', 'BayController@massDestroy')->name('bays.massDestroy');
    Route::resource('bays', 'BayController');

    // Physical Servers
    Route::delete('physical-servers/destroy', 'PhysicalServerController@massDestroy')->name('physical-servers.massDestroy');
    Route::resource('physical-servers', 'PhysicalServerController');

    // Workstations
    Route::delete('workstations/destroy', 'WorkstationController@massDestroy')->name('workstations.massDestroy');
    Route::resource('workstations', 'WorkstationController');

    // Storage Devices
    Route::delete('storage-devices/destroy', 'StorageDeviceController@massDestroy')->name('storage-devices.massDestroy');
    Route::resource('storage-devices', 'StorageDeviceController');

    // Peripherals
    Route::delete('peripherals/destroy', 'PeripheralController@massDestroy')->name('peripherals.massDestroy');
    Route::resource('peripherals', 'PeripheralController');

    // Phones
    Route::delete('phones/destroy', 'PhoneController@massDestroy')->name('phones.massDestroy');
    Route::resource('phones', 'PhoneController');

    // Physical Switches
    Route::delete('physical-switches/destroy', 'PhysicalSwitchController@massDestroy')->name('physical-switches.massDestroy');
    Route::resource('physical-switches', 'PhysicalSwitchController');

    // Physical Routers
    Route::delete('physical-routers/destroy', 'PhysicalRouterController@massDestroy')->name('physical-routers.massDestroy');
    Route::resource('physical-routers', 'PhysicalRouterController');

    // Wifi Terminals
    Route::delete('wifi-terminals/destroy', 'WifiTerminalController@massDestroy')->name('wifi-terminals.massDestroy');
    Route::resource('wifi-terminals', 'WifiTerminalController');

    // Physical Security Devices
    Route::delete('physical-security-devices/destroy', 'PhysicalSecurityDeviceController@massDestroy')->name('physical-security-devices.massDestroy');
    Route::resource('physical-security-devices', 'PhysicalSecurityDeviceController');

    // Wans
    Route::delete('wans/destroy', 'WanController@massDestroy')->name('wans.massDestroy');
    Route::resource('wans', 'WanController');

    // Men
    Route::delete('men/destroy', 'ManController@massDestroy')->name('men.massDestroy');
    Route::resource('men', 'ManController');

    // Lans
    Route::delete('lans/destroy', 'LanController@massDestroy')->name('lans.massDestroy');
    Route::resource('lans', 'LanController');

    // Vlans
    Route::delete('vlans/destroy', 'VlanController@massDestroy')->name('vlans.massDestroy');
    Route::resource('vlans', 'VlanController');

    // Application Modules
    Route::delete('application-modules/destroy', 'ApplicationModuleController@massDestroy')->name('application-modules.massDestroy');
    Route::resource('application-modules', 'ApplicationModuleController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Macro Processuses
    Route::delete('macro-processuses/destroy', 'MacroProcessusController@massDestroy')->name('macro-processuses.massDestroy');
    Route::resource('macro-processuses', 'MacroProcessusController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    // Reports
    Route::get('report/ecosystem', 'ReportController@ecosystem');
    Route::get('report/information_system', 'ReportController@informationSystem');
    Route::get('report/administration', 'ReportController@administration');
    Route::get('report/applications', 'ReportController@applications');
    Route::get('report/application_flows', 'ReportController@applicationFlows');
    Route::get('report/logical_infrastructure', 'ReportController@logicalInfrastructure');
    Route::get('report/physical_infrastructure', 'ReportController@physicalInfrastructure');

    // Maturity levels
    Route::get('report/maturity1', 'HomeController@maturity1');
    Route::get('report/maturity2', 'HomeController@maturity2');
    Route::get('report/maturity3', 'HomeController@maturity3');

    // Reporting
    Route::get('report/applicationsByBlocks', 'ReportController@applicationsByBlocks');
    Route::get('report/logicalServerConfigs', 'ReportController@logicalServerConfigs');
    Route::get('report/physicalInventory', 'ReportController@physicalInventory');
    Route::put('report/cartography', 'ReportController@cartography');

    // Reporting
    Route::get('doc/report', function () {
        return view('doc/report');
        });

    // Doc
    Route::get('doc/schema', function () {
        return view('doc/schema');
        });
    Route::get('doc/maturity', function () {
        return view('doc/maturity');
        });
    Route::get('doc/guide', function () {
        return view('doc/guide');
        });

    Route::get('doc/about', function () {
        return view('doc/about');
        });
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::get('preferences', 'ProfileController@showProfile');
        Route::post('preferences', 'ProfileController@saveProfile');
    }
});
