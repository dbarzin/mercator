<?php

use App\Http\Controllers;
use App\Http\Controllers\Admin;

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
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {

    // Dashboard
    Route::get('/', [Admin\HomeController::Class,'index'])->name('home');

    // Permissions
    Route::resource('permissions', Admin\PermissionsController::Class);
    Route::delete('permissions-destroy', [Admin\PermissionsController::Class,'massDestroy'])->name('permissions.massDestroy');

    // Roles
    Route::resource('roles', Admin\RolesController::Class);
    Route::delete('roles-destroy', [Admin\RolesController::Class,'massDestroy'])->name('roles.massDestroy');

    // Users
    Route::resource('users', Admin\UsersController::Class);
    Route::delete('users-destroy', [Admin\UsersController::Class,'massDestroy'])->name('users.massDestroy');

    // Entities
    Route::resource('entities', Admin\EntityController::Class);
    Route::delete('entities-destroy', [Admin\EntityController::Class,'massDestroy'])->name('entities.massDestroy');

    // Relations
    Route::resource('relations', Admin\RelationController::Class);
    Route::delete('relations-destroy', [Admin\RelationController::Class,'massDestroy'])->name('relations.massDestroy');

    // Processes
    Route::resource('processes', Admin\ProcessController::Class);
    Route::delete('processes-destroy', [Admin\ProcessController::Class,'massDestroy'])->name('processes.massDestroy');

    // Operations
    Route::resource('operations', Admin\OperationController::Class);
    Route::delete('operations-destroy', [Admin\OperationController::Class,'massDestroy'])->name('operations.massDestroy');

    // Actors
    Route::resource('actors', Admin\ActorController::Class);
    Route::delete('actors-destroy', [Admin\ActorController::Class,'massDestroy'])->name('actors.massDestroy');

    // Activities
    Route::resource('activities', Admin\ActivityController::Class);
    Route::delete('activities-destroy', [Admin\ActivityController::Class,'massDestroy'])->name('activities.massDestroy');

    // Tasks
    Route::resource('tasks', Admin\TaskController::Class);
    Route::delete('tasks-destroy', [Admin\TaskController::Class,'massDestroy'])->name('tasks.massDestroy');

    // Information
    Route::resource('information', Admin\InformationController::Class);
    Route::delete('information-destroy', [Admin\InformationController::Class,'massDestroy'])->name('information.massDestroy');

    // Application Blocks
    Route::resource('application-blocks', Admin\ApplicationBlockController::Class);
    Route::delete('application-blocks-destroy', [Admin\ApplicationBlockController::Class,'massDestroy'])->name('application-blocks.massDestroy');

    // Applications
    Route::resource('applications', Admin\MApplicationController::Class);
    Route::delete('applications-destroy', [Admin\MApplicationController::Class,'massDestroy'])->name('applications.massDestroy');

    // Application Services
    Route::resource('application-services', Admin\ApplicationServiceController::Class);
    Route::delete('application-services-destroy', [Admin\ApplicationServiceController::Class,'massDestroy'])->name('application-services.massDestroy');

    // Databases
    Route::resource('databases', Admin\DatabaseController::Class);
    Route::delete('databases-destroy', [Admin\DatabaseController::Class,'massDestroy'])->name('databases.massDestroy');

    // Fluxes
    Route::resource('fluxes', Admin\FluxController::Class);
    Route::delete('fluxes-destroy', [Admin\FluxController::Class,'massDestroy'])->name('fluxes.massDestroy');

    // Zone Admins
    Route::resource('zone-admins', Admin\ZoneAdminController::Class);
    Route::delete('zone-admins-destroy', [Admin\ZoneAdminController::Class,'massDestroy'])->name('zone-admins.massDestroy');

    // Annuaires
    Route::resource('annuaires', Admin\AnnuaireController::Class);
    Route::delete('annuaires-destroy', [Admin\AnnuaireController::Class,'massDestroy'])->name('annuaires.massDestroy');

    // Forest Ads
    Route::resource('forest-ads', Admin\ForestAdController::Class);
    Route::delete('forest-ads-destroy', [Admin\ForestAdController::Class,'massDestroy'])->name('forest-ads.massDestroy');

    // Domaine Ads
    Route::resource('domaine-ads', Admin\DomaineAdController::Class);
    Route::delete('domaine-ads-destroy', [Admin\DomaineAdController::Class,'massDestroy'])->name('domaine-ads.massDestroy');

    // Networks
    Route::resource('networks', Admin\NetworkController::Class);
    Route::delete('networks-destroy', [Admin\NetworkController::Class,'massDestroy'])->name('networks.massDestroy');

    // Subnetworks
    Route::resource('subnetworks', Admin\SubnetworkController::Class);
    Route::delete('subnetworks-destroy', [Admin\SubnetworkController::Class,'massDestroy'])->name('subnetworks.massDestroy');

    // Gateways
    Route::resource('gateways', Admin\GatewayController::Class);
    Route::delete('gateways-destroy', [Admin\GatewayController::Class,'massDestroy'])->name('gateways.massDestroy');

    // External Connected Entities
    Route::resource('external-connected-entities', Admin\ExternalConnectedEntityController::Class);
    Route::delete('external-connected-entities-destroy', [Admin\ExternalConnectedEntityController::Class,'massDestroy'])->name('external-connected-entities.massDestroy');

    // Network Switches
    Route::resource('network-switches', Admin\NetworkSwitchController::Class);
    Route::delete('network-switches-destroy', [Admin\NetworkSwitchController::Class,'massDestroy'])->name('network-switches.massDestroy');

    // Routers
    Route::resource('routers', Admin\RouterController::Class);
    Route::delete('routers-destroy', [Admin\RouterController::Class,'massDestroy'])->name('routers.massDestroy');

    // Security Devices
    Route::resource('security-devices', Admin\SecurityDeviceController::Class);
    Route::delete('security-devices-destroy', [Admin\SecurityDeviceController::Class,'massDestroy'])->name('security-devices.massDestroy');

    // Dhcp Servers
    Route::resource('dhcp-servers', Admin\DhcpServerController::Class);
    Route::delete('dhcp-servers-destroy', [Admin\DhcpServerController::Class,'massDestroy'])->name('dhcp-servers.massDestroy');

    // Dnsservers
    Route::resource('dnsservers', Admin\DnsserverController::Class);
    Route::delete('dnsservers-destroy', [Admin\DnsserverController::Class,'massDestroy'])->name('dnsservers.massDestroy');

    // Logical Servers
    Route::resource('logical-servers', Admin\LogicalServerController::Class);
    Route::delete('logical-servers-destroy', [Admin\LogicalServerController::Class,'massDestroy'])->name('logical-servers.massDestroy');

    // Certificates
    Route::resource('certificates', Admin\CertificateController::Class);
    Route::delete('certificates-destroy', [Admin\CertificateController::Class,'massDestroy'])->name('certificates.massDestroy');

    // Sites
    Route::resource('sites', Admin\SiteController::Class);
    Route::delete('sites-destroy', [Admin\SiteController::Class,'massDestroy'])->name('sites.massDestroy');

    // Buildings
    Route::resource('buildings', Admin\BuildingController::Class);
    Route::delete('buildings-destroy', [Admin\BuildingController::Class,'massDestroy'])->name('buildings.massDestroy');

    // Bays
    Route::resource('bays', Admin\BayController::Class);
    Route::delete('bays-destroy', [Admin\BayController::Class,'massDestroy'])->name('bays.massDestroy');

    // Physical Servers
    Route::resource('physical-servers', Admin\PhysicalServerController::Class);
    Route::delete('physical-servers-destroy', [Admin\PhysicalServerController::Class,'massDestroy'])->name('physical-servers.massDestroy');

    // Workstations
    Route::resource('workstations', Admin\WorkstationController::Class);
    Route::delete('workstations-destroy', [Admin\WorkstationController::Class,'massDestroy'])->name('workstations.massDestroy');

    // Storage Devices
    Route::resource('storage-devices', Admin\StorageDeviceController::Class);
    Route::delete('storage-devices-destroy', [Admin\StorageDeviceController::Class,'massDestroy'])->name('storage-devices.massDestroy');

    // Peripherals
    Route::resource('peripherals', Admin\PeripheralController::Class);
    Route::delete('peripherals-destroy', [Admin\PeripheralController::Class,'massDestroy'])->name('peripherals.massDestroy');

    // Phones
    Route::resource('phones', Admin\PhoneController::Class);
    Route::delete('phones-destroy', [Admin\PhoneController::Class,'massDestroy'])->name('phones.massDestroy');

    // Physical Switches
    Route::resource('physical-switches', Admin\PhysicalSwitchController::Class);
    Route::delete('physical-switches-destroy', [Admin\PhysicalSwitchController::Class,'massDestroy'])->name('physical-switches.massDestroy');

    // Physical Routers
    Route::resource('physical-routers', Admin\PhysicalRouterController::Class);
    Route::delete('physical-routers-destroy', [Admin\PhysicalRouterController::Class,'massDestroy'])->name('physical-routers.massDestroy');

    // Wifi Terminals
    Route::resource('wifi-terminals', Admin\WifiTerminalController::Class);
    Route::delete('wifi-terminals-destroy', [Admin\WifiTerminalController::Class,'massDestroy'])->name('wifi-terminals.massDestroy');

    // Physical Security Devices
    Route::resource('physical-security-devices', Admin\PhysicalSecurityDeviceController::Class);
    Route::delete('physical-security-devices-destroy', [Admin\PhysicalSecurityDeviceController::Class,'massDestroy'])->name('physical-security-devices.massDestroy');

    // WANs
    Route::resource('wans', Admin\WanController::Class);
    Route::delete('wans-destroy', [Admin\WanController::Class,'massDestroy'])->name('wans.massDestroy');

    // MANs
    Route::resource('mans', Admin\ManController::Class);
    Route::delete('mans-destroy', [Admin\ManController::Class,'massDestroy'])->name('mans.massDestroy');

    // LANs
    Route::resource('lans', Admin\LanController::Class);
    Route::delete('lans-destroy', [Admin\LanController::Class,'massDestroy'])->name('lans.massDestroy');

    // VLANs
    Route::resource('vlans', Admin\VlanController::Class);
    Route::delete('vlans-destroy', [Admin\VlanController::Class,'massDestroy'])->name('vlans.massDestroy');

    // Application Modules
    Route::resource('application-modules', Admin\ApplicationModuleController::Class);
    Route::delete('application-modules-destroy', [Admin\ApplicationModuleController::Class,'massDestroy'])->name('application-modules.massDestroy');

    // Audit Logs
    Route::resource('audit-logs', Admin\AuditLogsController::Class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Macro Processuses
    Route::resource('macro-processuses', Admin\MacroProcessusController::Class);
    Route::delete('macro-processuses-destroy', [Admin\MacroProcessusController::Class,'massDestroy'])->name('macro-processuses.massDestroy');

    // Global Search engine !
    Route::get('global-search', [Admin\GlobalSearchController::Class,'search'])->name('globalSearch');

    // Configuration page
    Route::get('configuration', [Admin\ConfigurationController::Class,'index'])->name('configuration');
    Route::put('configuration', [Admin\ConfigurationController::Class,'save'])->name('configuration');

    // Views
    Route::get('report/ecosystem', [Admin\ReportController::Class,'ecosystem'])->name('report.view.ecosystem');
    Route::get('report/information_system', [Admin\ReportController::Class,'informationSystem'])->name('report.view.informaton-system');
    Route::get('report/administration', [Admin\ReportController::Class,'administration'])->name('report.view.administration');
    Route::get('report/applications', [Admin\ReportController::Class,'applications'])->name('report.view.applications');
    Route::get('report/application_flows', [Admin\ReportController::Class,'applicationFlows'])->name('report.view.application-flows');
    Route::get('report/logical_infrastructure', [Admin\ReportController::Class,'logicalInfrastructure'])->name('report.view.logical-infrastructure');
    Route::get('report/physical_infrastructure', [Admin\ReportController::Class,'physicalInfrastructure'])->name('report.view.physical-infrastructure');
    
    // Maturity levels
    Route::get('report/maturity1', [Admin\HomeController::Class,'maturity1'])->name("report.maturity1");
    Route::get('report/maturity2', [Admin\HomeController::Class,'maturity2'])->name("report.maturity2");
    Route::get('report/maturity3', [Admin\HomeController::Class,'maturity3'])->name("report.maturity3");

    // Reporting
    Route::get('report/entities', [Admin\ReportController::Class,'entities']);
    Route::get('report/applicationsByBlocks', [Admin\ReportController::Class,'applicationsByBlocks']);
    Route::get('report/logicalServerConfigs', [Admin\ReportController::Class, 'logicalServerConfigs']);
    Route::get('report/physicalInventory', [Admin\ReportController::Class, 'physicalInventory']);
    Route::get('report/securityNeeds', [Admin\ReportController::Class, 'securityNeeds']);
    Route::put('report/cartography', [Admin\CartographyController::Class, 'cartography']);
    Route::get('report/logicalServerResp', [Admin\ReportController::Class,'logicalServerResp'])->name('report.view.logical-server-responsible');

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

// Profile
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    Route::get('password', [Controllers\Auth\ChangePasswordController::Class, 'edit'])->name('password.edit');
    Route::post('password', [Controllers\Auth\ChangePasswordController::Class, 'update'])->name('password.update');
    Route::get('preferences', [Controllers\Auth\ProfileController::Class, 'showProfile']);
    Route::post('preferences', [Controllers\Auth\ProfileController::Class, 'saveProfile']);
});

    
