<?php

use App\Http\Controllers\Admin\HomeController;

use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;

use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\RelationController;
use App\Http\Controllers\Admin\ProcessController;
use App\Http\Controllers\Admin\OperationController;
use App\Http\Controllers\Admin\ActorController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\ApplicationBlockController;
use App\Http\Controllers\Admin\MApplicationController;
use App\Http\Controllers\Admin\ApplicationServiceController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\FluxController;
use App\Http\Controllers\Admin\ZoneAdminController;
use App\Http\Controllers\Admin\AnnuaireController;
use App\Http\Controllers\Admin\ForestAdController;
use App\Http\Controllers\Admin\DomaineAdController;
use App\Http\Controllers\Admin\NetworkController;
use App\Http\Controllers\Admin\SubnetworkController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\ExternalConnectedEntityController;
use App\Http\Controllers\Admin\NetworkSwitchController;
use App\Http\Controllers\Admin\RouterController;
use App\Http\Controllers\Admin\SecurityDeviceController;
use App\Http\Controllers\Admin\DhcpServerController;
use App\Http\Controllers\Admin\DnsserverController;
use App\Http\Controllers\Admin\LogicalServerController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\BayController;
use App\Http\Controllers\Admin\PhysicalServerController;
use App\Http\Controllers\Admin\WorkstationController;
use App\Http\Controllers\Admin\StorageDeviceController;
use App\Http\Controllers\Admin\PeripheralController;
use App\Http\Controllers\Admin\PhoneController;
use App\Http\Controllers\Admin\PhysicalSwitchController;
use App\Http\Controllers\Admin\PhysicalRouterController;
use App\Http\Controllers\Admin\WifiTerminalController;
use App\Http\Controllers\Admin\PhysicalSecurityDeviceController;
use App\Http\Controllers\Admin\WanController;
use App\Http\Controllers\Admin\ManController;
use App\Http\Controllers\Admin\LanController;
use App\Http\Controllers\Admin\VlanController;
use App\Http\Controllers\Admin\ApplicationModuleController;

use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\MacroProcessusController;
use App\Http\Controllers\Admin\GlobalSearchController;

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CartographyController;

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ProfileController;

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
    Route::get('/', [HomeController::Class,'index'])->name('home');

    // Permissions
    Route::resource('permissions', PermissionsController::Class);
    Route::delete('permissions/destroy', [PermissionsController::Class,'massDestroy'])->name('permissions.massDestroy');

    // Roles
    Route::resource('roles', RolesController::Class);
    Route::delete('roles/destroy', [RolesController::Class,'massDestroy'])->name('roles.massDestroy');

    // Users
    Route::resource('users', UsersController::Class);
    Route::delete('users/destroy', [UsersController::Class,'massDestroy'])->name('users.massDestroy');

    // Entities
    Route::resource('entities', EntityController::Class);
    Route::delete('entities/destroy', [EntityController::Class,'massDestroy'])->name('entities.massDestroy');

    // Relations
    Route::resource('relations', RelationController::Class);
    Route::delete('relations/destroy', [RelationController::Class,'massDestroy'])->name('relations.massDestroy');

    // Processes
    Route::resource('processes', ProcessController::Class);
    Route::delete('processes/destroy', [ProcessController::Class,'massDestroy'])->name('processes.massDestroy');

    // Operations
    Route::resource('operations', OperationController::Class);
    Route::delete('operations/destroy', [OperationController::Class,'massDestroy'])->name('operations.massDestroy');

    // Actors
    Route::resource('actors', ActorController::Class);
    Route::delete('actors/destroy', [ActorController::Class,'massDestroy'])->name('actors.massDestroy');

    // Activities
    Route::resource('activities', ActivityController::Class);
    Route::delete('activities/destroy', [ActivityController::Class,'massDestroy'])->name('activities.massDestroy');

    // Tasks
    Route::resource('tasks', TaskController::Class);
    Route::delete('tasks/destroy', [TaskController::Class,'massDestroy'])->name('tasks.massDestroy');

    // Information
    Route::resource('information', InformationController::Class);
    Route::delete('information/destroy', [InformationController::Class,'massDestroy'])->name('information.massDestroy');

    // Application Blocks
    Route::resource('application-blocks', ApplicationBlockController::Class);
    Route::delete('application-blocks/destroy', [ApplicationBlockController::Class,'massDestroy'])->name('application-blocks.massDestroy');

    // Applications
    Route::resource('applications', MApplicationController::Class);
    Route::delete('applications/destroy', [MApplicationController::Class,'massDestroy'])->name('applications.massDestroy');

    // Application Services
    Route::resource('application-services', ApplicationServiceController::Class);
    Route::delete('application-services/destroy', [ApplicationServiceController::Class,'massDestroy'])->name('application-services.massDestroy');

    // Databases
    Route::resource('databases', DatabaseController::Class);
    Route::delete('databases/destroy', [DatabaseController::Class,'massDestroy'])->name('databases.massDestroy');

    // Fluxes
    Route::resource('fluxes', FluxController::Class);
    Route::delete('fluxes/destroy', [FluxController::Class,'massDestroy'])->name('fluxes.massDestroy');

    // Zone Admins
    Route::resource('zone-admins', ZoneAdminController::Class);
    Route::delete('zone-admins/destroy', [ZoneAdminController::Class,'massDestroy'])->name('zone-admins.massDestroy');

    // Annuaires
    Route::resource('annuaires', AnnuaireController::Class);
    Route::delete('annuaires/destroy', [AnnuaireController::Class,'massDestroy'])->name('annuaires.massDestroy');

    // Forest Ads
    Route::resource('forest-ads', ForestAdController::Class);
    Route::delete('forest-ads/destroy', [ForestAdController::Class,'massDestroy'])->name('forest-ads.massDestroy');

    // Domaine Ads
    Route::resource('domaine-ads', DomaineAdController::Class);
    Route::delete('domaine-ads/destroy', [DomaineAdController::Class,'massDestroy'])->name('domaine-ads.massDestroy');

    // Networks
    Route::resource('networks', NetworkController::Class);
    Route::delete('networks/destroy', [NetworkController::Class,'massDestroy'])->name('networks.massDestroy');

    // Subnetworks
    Route::resource('subnetworks', SubnetworkController::Class);
    Route::delete('subnetworks/destroy', [SubnetworkController::Class,'massDestroy'])->name('subnetworks.massDestroy');

    // Gateways
    Route::resource('gateways', GatewayController::Class);
    Route::delete('gateways/destroy', [GatewayController::Class,'massDestroy'])->name('gateways.massDestroy');

    // External Connected Entities
    Route::resource('external-connected-entities', ExternalConnectedEntityController::Class);
    Route::delete('external-connected-entities/destroy', [ExternalConnectedEntityController::Class,'massDestroy'])->name('external-connected-entities.massDestroy');

    // Network Switches
    Route::resource('network-switches', NetworkSwitchController::Class);
    Route::delete('network-switches/destroy', [NetworkSwitchController::Class,'massDestroy'])->name('network-switches.massDestroy');

    // Routers
    Route::resource('routers', RouterController::Class);
    Route::delete('routers/destroy', [RouterController::Class,'massDestroy'])->name('routers.massDestroy');

    // Security Devices
    Route::resource('security-devices', SecurityDeviceController::Class);
    Route::delete('security-devices/destroy', [SecurityDeviceController::Class,'massDestroy'])->name('security-devices.massDestroy');

    // Dhcp Servers
    Route::resource('dhcp-servers', DhcpServerController::Class);
    Route::delete('dhcp-servers/destroy', [DhcpServerController::Class,'massDestroy'])->name('dhcp-servers.massDestroy');

    // Dnsservers
    Route::resource('dnsservers', DnsserverController::Class);
    Route::delete('dnsservers/destroy', [DnsserverController::Class,'massDestroy'])->name('dnsservers.massDestroy');

    // Logical Servers
    Route::resource('logical-servers', LogicalServerController::Class);
    Route::delete('logical-servers/destroy', [LogicalServerController::Class,'massDestroy'])->name('logical-servers.massDestroy');

    // Sites
    Route::resource('sites', SiteController::Class);
    Route::delete('sites/destroy', [SiteController::Class,'massDestroy'])->name('sites.massDestroy');

    // Buildings
    Route::resource('buildings', BuildingController::Class);
    Route::delete('buildings/destroy', [BuildingController::Class,'massDestroy'])->name('buildings.massDestroy');

    // Bays
    Route::resource('bays', BayController::Class);
    Route::delete('bays/destroy', [BayController::Class,'massDestroy'])->name('bays.massDestroy');

    // Physical Servers
    Route::resource('physical-servers', PhysicalServerController::Class);
    Route::delete('physical-servers/destroy', [PhysicalServerController::Class,'massDestroy'])->name('physical-servers.massDestroy');

    // Workstations
    Route::resource('workstations', WorkstationController::Class);
    Route::delete('workstations/destroy', [WorkstationController::Class,'massDestroy'])->name('workstations.massDestroy');

    // Storage Devices
    Route::resource('storage-devices', StorageDeviceController::Class);
    Route::delete('storage-devices/destroy', [StorageDeviceController::Class,'massDestroy'])->name('storage-devices.massDestroy');

    // Peripherals
    Route::resource('peripherals', PeripheralController::Class);
    Route::delete('peripherals/destroy', [PeripheralController::Class,'massDestroy'])->name('peripherals.massDestroy');

    // Phones
    Route::resource('phones', PhoneController::Class);
    Route::delete('phones/destroy', [PhoneController::Class,'massDestroy'])->name('phones.massDestroy');

    // Physical Switches
    Route::resource('physical-switches', PhysicalSwitchController::Class);
    Route::delete('physical-switches/destroy', [PhysicalSwitchController::Class,'massDestroy'])->name('physical-switches.massDestroy');

    // Physical Routers
    Route::resource('physical-routers', PhysicalRouterController::Class);
    Route::delete('physical-routers/destroy', [PhysicalRouterController::Class,'massDestroy'])->name('physical-routers.massDestroy');

    // Wifi Terminals
    Route::resource('wifi-terminals', WifiTerminalController::Class);
    Route::delete('wifi-terminals/destroy', [WifiTerminalController::Class,'massDestroy'])->name('wifi-terminals.massDestroy');

    // Physical Security Devices
    Route::resource('physical-security-devices', PhysicalSecurityDeviceController::Class);
    Route::delete('physical-security-devices/destroy', [PhysicalSecurityDeviceController::Class,'massDestroy'])->name('physical-security-devices.massDestroy');

    // WANs
    Route::resource('wans', WanController::Class);
    Route::delete('wans/destroy', [WanController::Class,'massDestroy'])->name('wans.massDestroy');

    // MANs
    Route::resource('mans', ManController::Class);
    Route::delete('mans/destroy', [ManController::Class,'massDestroy'])->name('mans.massDestroy');

    // LANs
    Route::resource('lans', LanController::Class);
    Route::delete('lans/destroy', [LanController::Class,'massDestroy'])->name('lans.massDestroy');

    // VLANs
    Route::resource('vlans', VlanController::Class);
    Route::delete('vlans/destroy', [VlanController::Class,'massDestroy'])->name('vlans.massDestroy');

    // Application Modules
    Route::resource('application-modules', ApplicationModuleController::Class);
    Route::delete('application-modules/destroy', [ApplicationModuleController::Class,'massDestroy'])->name('application-modules.massDestroy');

    // Audit Logs
    Route::resource('audit-logs', AuditLogsController::Class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Macro Processuses
    Route::resource('macro-processuses', MacroProcessusController::Class);
    Route::delete('macro-processuses/destroy', [MacroProcessusController::Class,'massDestroy'])->name('macro-processuses.massDestroy');

    Route::get('global-search', [GlobalSearchController::Class,'search'])->name('globalSearch');

    // Views
    Route::get('report/ecosystem', [ReportController::Class,'ecosystem'])->name('report.view.ecosystem');
    Route::get('report/information_system', [ReportController::Class,'informationSystem'])->name('report.view.informaton-system');
    Route::get('report/administration', [ReportController::Class,'administration'])->name('report.view.administration');
    Route::get('report/applications', [ReportController::Class,'applications'])->name('report.view.applications');
    Route::get('report/application_flows', [ReportController::Class,'applicationFlows'])->name('report.view.application-flows');
    Route::get('report/logical_infrastructure', [ReportController::Class,'logicalInfrastructure'])->name('report.view.logical-infrastructure');
    Route::get('report/physical_infrastructure', [ReportController::Class,'physicalInfrastructure'])->name('report.view.physical-infrastructure');
    
    // Maturity levels
    Route::get('report/maturity1', [HomeController::Class,'maturity1'])->name("report.maturity1");
    Route::get('report/maturity2', [HomeController::Class,'maturity2'])->name("report.maturity2");
    Route::get('report/maturity3', [HomeController::Class,'maturity3'])->name("report.maturity3");

    // Reporting
    Route::get('report/entities', [ReportController::Class,'entities']);
    Route::get('report/applicationsByBlocks', [ReportController::Class,'applicationsByBlocks']);
    Route::get('report/logicalServerConfigs', [ReportController::Class, 'logicalServerConfigs']);
    Route::get('report/physicalInventory', [ReportController::Class, 'physicalInventory']);
    Route::get('report/securityNeeds', [ReportController::Class, 'securityNeeds']);
    Route::put('report/cartography', [CartographyController::Class, 'cartography']);
    Route::get('report/logicalServerResp', [ReportController::Class,'logicalServerResp'])->name('report.view.logical-server-responsible');

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
    Route::get('password', [ChangePasswordController::Class, 'edit'])->name('password.edit');
    Route::post('password', [ChangePasswordController::Class, 'update'])->name('password.update');
    Route::get('preferences', [ProfileController::Class, 'showProfile']);
    Route::post('preferences', [ProfileController::Class, 'saveProfile']);
});

    
