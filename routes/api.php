<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\API;
use App\Http\Controllers\Report;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [API\AuthController::class, 'login']);

Route::middleware('auth:api')->group(function (): void {

    // Data Processing & Security
    Route::delete('data-processings/mass-destroy', [API\DataProcessingController::class, 'massDestroy'])->name('data-processings.mass-destroy');
    Route::resource('data-processings', API\DataProcessingController::class);

    // Security Controls
    Route::delete('security-controls/mass-destroy', [API\SecurityControlController::class, 'massDestroy'])->name('security-controls.mass-destroy');
    Route::resource('security-controls', API\SecurityControlController::class);

    // Entities
    Route::delete('entities/mass-destroy', [API\EntityController::class, 'massDestroy'])->name('entities.mass-destroy');
    Route::resource('entities', API\EntityController::class);

    // Relations
    Route::delete('relations/mass-destroy', [API\RelationController::class, 'massDestroy'])->name('relations.mass-destroy');
    Route::resource('relations', API\RelationController::class);

    // Macro-Processuses
    Route::delete('macro-processuses/mass-destroy', [API\MacroProcessusController::class, 'massDestroy'])->name('macro-processuses.mass-destroy');
    Route::resource('macro-processuses', API\MacroProcessusController::class);

    // Processes
    Route::delete('processes/mass-destroy', [API\ProcessController::class, 'massDestroy'])->name('processes.mass-destroy');
    Route::resource('processes', API\ProcessController::class);

    // Operations
    Route::delete('operations/mass-destroy', [API\OperationController::class, 'massDestroy'])->name('operations.mass-destroy');
    Route::resource('operations', API\OperationController::class);

    // Actors
    Route::delete('actors/mass-destroy', [API\ActorController::class, 'massDestroy'])->name('actors.mass-destroy');
    Route::resource('actors', API\ActorController::class);

    // Activities
    Route::post('activities/mass-store', [API\ActivityController::class, 'massStore'])->name('activities.mass-store');
    Route::put('activities/mass-update', [API\ActivityController::class, 'massUpdate'])->name('activities.mass-update');
    Route::delete('activities/mass-destroy', [API\ActivityController::class, 'massDestroy'])->name('activities.mass-destroy');
    Route::resource('activities', API\ActivityController::class);

    // Tasks
    Route::delete('tasks/mass-destroy', [API\TaskController::class, 'massDestroy'])->name('tasks.mass-destroy');
    Route::resource('tasks', API\TaskController::class);

    // Information
    Route::delete('information/mass-destroy', [API\InformationController::class, 'massDestroy'])->name('information.mass-destroy');
    Route::resource('information', API\InformationController::class);

    // Applications
    Route::delete('applications/mass-destroy', [API\ApplicationController::class, 'massDestroy'])->name('applications.mass-destroy');
    Route::resource('applications', API\ApplicationController::class);

    // Application Blocks
    Route::delete('application-blocks/mass-destroy', [API\ApplicationBlockController::class, 'massDestroy'])->name('application-blocks.mass-destroy');
    Route::resource('application-blocks', API\ApplicationBlockController::class);

    // Application Modules
    Route::delete('application-modules/mass-destroy', [API\ApplicationModuleController::class, 'massDestroy'])->name('application-modules.mass-destroy');
    Route::resource('application-modules', API\ApplicationModuleController::class);

    // Application Services
    Route::delete('application-services/mass-destroy', [API\ApplicationServiceController::class, 'massDestroy'])->name('application-services.mass-destroy');
    Route::resource('application-services', API\ApplicationServiceController::class);

    // Databases
    Route::delete('databases/mass-destroy', [API\DatabaseController::class, 'massDestroy'])->name('databases.mass-destroy');
    Route::resource('databases', API\DatabaseController::class);

    // Fluxes
    Route::delete('fluxes/mass-destroy', [API\FluxController::class, 'massDestroy'])->name('fluxes.mass-destroy');
    Route::resource('fluxes', API\FluxController::class);

    // Zone Admins
    Route::delete('zone-admins/mass-destroy', [API\ZoneAdminController::class, 'massDestroy'])->name('zone-admins.mass-destroy');
    Route::resource('zone-admins', API\ZoneAdminController::class);

    // Annuaires
    Route::delete('annuaires/mass-destroy', [API\AnnuaireController::class, 'massDestroy'])->name('annuaires.mass-destroy');
    Route::resource('annuaires', API\AnnuaireController::class);

    // Forest Ads
    Route::delete('forest-ads/mass-destroy', [API\ForestAdController::class, 'massDestroy'])->name('forest-ads.mass-destroy');
    Route::resource('forest-ads', API\ForestAdController::class);

    // Domaine Ads
    Route::delete('domaine-ads/mass-destroy', [API\DomaineAdController::class, 'massDestroy'])->name('domaine-ads.mass-destroy');
    Route::resource('domaine-ads', API\DomaineAdController::class);

    // Admin User
    Route::delete('admin-users/mass-destroy', [API\AdminUserController::class, 'massDestroy'])->name('admin-users.mass-destroy');
    Route::resource('admin-users', API\AdminUserController::class);

    // Networks
    Route::delete('networks/mass-destroy', [API\NetworkController::class, 'massDestroy'])->name('networks.mass-destroy');
    Route::resource('networks', API\NetworkController::class);

    // Subnetworks
    Route::delete('subnetworks/mass-destroy', [API\SubnetworkController::class, 'massDestroy'])->name('subnetworks.mass-destroy');
    Route::resource('subnetworks', API\SubnetworkController::class);

    // Gateways
    Route::delete('gateways/mass-destroy', [API\GatewayController::class, 'massDestroy'])->name('gateways.mass-destroy');
    Route::resource('gateways', API\GatewayController::class);

    // External-Connected Entities
    Route::delete('external-connected-entities/mass-destroy', [API\ExternalConnectedEntityController::class, 'massDestroy'])->name('external-connected-entities.mass-destroy');
    Route::resource('external-connected-entities', API\ExternalConnectedEntityController::class);

    // Network Switches
    Route::delete('network-switches/mass-destroy', [API\NetworkSwitchController::class, 'massDestroy'])->name('network-switches.mass-destroy');
    Route::resource('network-switches', API\NetworkSwitchController::class);

    // Routers
    Route::delete('routers/mass-destroy', [API\RouterController::class, 'massDestroy'])->name('routers.mass-destroy');
    Route::resource('routers', API\RouterController::class);

    // Security Devices
    Route::delete('security-devices/mass-destroy', [API\SecurityDeviceController::class, 'massDestroy'])->name('security-devices.mass-destroy');
    Route::resource('security-devices', API\SecurityDeviceController::class);

    // DHCP Servers
    Route::delete('dhcp-servers/mass-destroy', [API\DhcpServerController::class, 'massDestroy'])->name('dhcp-servers.mass-destroy');
    Route::resource('dhcp-servers', API\DhcpServerController::class);

    // DNS Servers
    Route::delete('dnsservers/mass-destroy', [API\DnsserverController::class, 'massDestroy'])->name('dnsservers.mass-destroy');
    Route::resource('dnsservers', API\DnsserverController::class);

    // Clusters
    Route::delete('clusters/mass-destroy', [API\ClusterController::class, 'massDestroy'])->name('clusters.mass-destroy');
    Route::resource('clusters', API\ClusterController::class);

    // Logical Servers
    Route::delete('logical-servers/mass-destroy', [API\LogicalServerController::class, 'massDestroy'])->name('logical-servers.mass-destroy');
    Route::resource('logical-servers', API\LogicalServerController::class);

    // Logical Flows
    Route::delete('logical-flows/mass-destroy', [API\LogicalFlowController::class, 'massDestroy'])->name('logical-flows.mass-destroy');
    Route::resource('logical-flows', API\LogicalFlowController::class);

    // Certificates
    Route::delete('certificates/mass-destroy', [API\CertificateController::class, 'massDestroy'])->name('certificates.mass-destroy');
    Route::resource('certificates', API\CertificateController::class);

    // Sites
    Route::delete('sites/mass-destroy', [API\SiteController::class, 'massDestroy'])->name('sites.mass-destroy');
    Route::resource('sites', API\SiteController::class);

    // Buildings
    Route::delete('buildings/mass-destroy', [API\BuildingController::class, 'massDestroy'])->name('buildings.mass-destroy');
    Route::resource('buildings', API\BuildingController::class);

    // Bays
    Route::delete('bays/mass-destroy', [API\BayController::class, 'massDestroy'])->name('bays.mass-destroy');
    Route::resource('bays', API\BayController::class);

    // Physical Servers
    Route::delete('physical-servers/mass-destroy', [API\PhysicalServerController::class, 'massDestroy'])->name('physical-servers.mass-destroy');
    Route::resource('physical-servers', API\PhysicalServerController::class);

    // Workstations
    Route::delete('workstations/mass-destroy', [API\WorkstationController::class, 'massDestroy'])->name('workstations.mass-destroy');
    Route::resource('workstations', API\WorkstationController::class);

    // Storage Devices
    Route::delete('storage-devices/mass-destroy', [API\StorageDeviceController::class, 'massDestroy'])->name('storage-devices.mass-destroy');
    Route::resource('storage-devices', API\StorageDeviceController::class);

    // Peripherals
    Route::delete('peripherals/mass-destroy', [API\PeripheralController::class, 'massDestroy'])->name('peripherals.mass-destroy');
    Route::resource('peripherals', API\PeripheralController::class);

    // Phones
    Route::delete('phones/mass-destroy', [API\PhoneController::class, 'massDestroy'])->name('phones.mass-destroy');
    Route::resource('phones', API\PhoneController::class);

    // Physical Switches
    Route::delete('physical-switches/mass-destroy', [API\PhysicalSwitchController::class, 'massDestroy'])->name('physical-switches.mass-destroy');
    Route::resource('physical-switches', API\PhysicalSwitchController::class);

    // Physical Routers
    Route::delete('physical-routers/mass-destroy', [API\PhysicalRouterController::class, 'massDestroy'])->name('physical-routers.mass-destroy');
    Route::resource('physical-routers', API\PhysicalRouterController::class);

    // Wifi Terminals
    Route::delete('wifi-terminals/mass-destroy', [API\WifiTerminalController::class, 'massDestroy'])->name('wifi-terminals.mass-destroy');
    Route::resource('wifi-terminals', API\WifiTerminalController::class);

    // Physical Security Devices
    Route::delete('physical-security-devices/mass-destroy', [API\PhysicalSecurityDeviceController::class, 'massDestroy'])->name('physical-security-devices.mass-destroy');
    Route::resource('physical-security-devices', API\PhysicalSecurityDeviceController::class);

    // Wans
    Route::delete('wans/mass-destroy', [API\WanController::class, 'massDestroy'])->name('wans.mass-destroy');
    Route::resource('wans', API\WanController::class);

    // Mans
    Route::delete('mans/mass-destroy', [API\ManController::class, 'massDestroy'])->name('mans.mass-destroy');
    Route::resource('mans', API\ManController::class);

    // Lans
    Route::delete('lans/mass-destroy', [API\LanController::class, 'massDestroy'])->name('lans.mass-destroy');
    Route::resource('lans', API\LanController::class);

    // VLANs
    Route::delete('vlans/mass-destroy', [API\VlanController::class, 'massDestroy'])->name('vlans.mass-destroy');
    Route::resource('vlans', API\VlanController::class);

    // Physical Links
    Route::delete('physical-links/mass-destroy', [API\PhysicalLinkController::class, 'massDestroy'])->name('physical-links.mass-destroy');
    Route::resource('physical-links', API\PhysicalLinkController::class);

    // Users
    Route::delete('users/mass-destroy', [API\UserController::class, 'massDestroy'])->name('users.mass-destroy');
    Route::resource('users', API\UserController::class);

    // Permissions
    Route::resource('permission', API\PermissionController::class);

    // Roles
    Route::resource('role', API\RoleController::class);

    // =======================================
    // Reports
    // =======================================
    Route::get('report/cartography', [Admin\CartographyController::class, 'cartography']);
    Route::get('report/entities', [Report\EntityList::class, 'generateExcel']);
    Route::get('report/applicationsByBlocks', [Report\ApplicationList::class, 'generateExcel']);
    Route::get('report/directory', [Report\Directory::class, 'generateDocx']);
    Route::get('report/logicalServers', [Report\LogicalServers::class, 'generateExcel']);
    Route::get('report/securityNeeds', [Report\SecurityNeeds::class, 'generateExcel']);
    Route::get('report/logicalServerConfigs', [Report\LogicalServerConfigs::class, 'generateExcel']);
    Route::get('report/externalAccess', [Report\ExternalAccess::class, 'generateExcel']);
    Route::get('report/physicalInventory', [Report\PhysicalInventory::class, 'generateExcel']);
    Route::get('report/vlans', [Report\VLANList::class, 'generateExcel']);
    Route::get('report/workstations', [Report\WorkstationList::class, 'generateExcel']);
    Route::get('report/cve', [Admin\CVEController::class, 'list']);

    // GDPR
    Route::get('report/activityList', [Report\ActivityList::class, 'generateExcel'])->name('report.activityList');
    Route::get('report/activityReport', [Report\ActivityReport::class, 'generateDocx'])->name('report.activityReport');

    Route::get('report/impacts', [Report\ImpactList::class, 'generateExcel'])->name('report.view.impacts');
    Route::get('report/rto', [Report\RTO::class, 'generateExcel'])->name('report.view.rto');

});
