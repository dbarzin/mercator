<?php

use App\Http\Controllers\API;
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

Route::middleware('auth:api')->group(function () {
    Route::resource('data-processings', API\DataProcessingController::class);
    Route::resource('security-controls', API\SecurityControlController::class);

    Route::resource('entities', API\EntityController::class);
    Route::resource('relations', API\RelationController::class);

    Route::resource('macro-processuses', API\MacroProcessusController::class);
    Route::resource('processes', API\ProcessController::class);
    Route::resource('operations', API\OperationController::class);
    Route::resource('actors', API\ActorController::class);
    Route::resource('activities', API\ActivityController::class);
    Route::resource('tasks', API\TaskController::class);
    Route::resource('information', API\InformationController::class);

    Route::resource('applications', API\ApplicationController::class);
    Route::resource('application-blocks', API\ApplicationBlockController::class);
    Route::resource('application-modules', API\ApplicationModuleController::class);
    Route::resource('application-services', API\ApplicationServiceController::class);
    Route::resource('databases', API\DatabaseController::class);
    Route::resource('fluxes', API\FluxController::class);

    Route::resource('zone-admins', API\ZoneAdminController::class);
    Route::resource('annuaires', API\AnnuaireController::class);
    Route::resource('forest-ads', API\ForestAdController::class);
    Route::resource('domaine-ads', API\DomaineAdController::class);

    Route::resource('networks', API\NetworkController::class);
    Route::resource('subnetworks', API\SubnetworkController::class);
    Route::resource('gateways', API\GatewayController::class);
    Route::resource('external-connected-entities', API\ExternalConnectedEntityController::class);
    Route::resource('network-switches', API\NetworkSwitchController::class);
    Route::resource('routers', API\RouterController::class);
    Route::resource('security-devices', API\SecurityDeviceController::class);
    Route::resource('dhcp-servers', API\DhcpServerController::class);
    Route::resource('dnsservers', API\DnsserverController::class);
    Route::resource('logical-servers', API\LogicalServerController::class);
    Route::resource('certificates', API\CertificateController::class);

    Route::resource('sites', API\SiteController::class);
    Route::resource('buildings', API\BuildingController::class);
    Route::resource('bays', API\BayController::class);
    Route::resource('physical-servers', API\PhysicalServerController::class);
    Route::resource('workstations', API\WorkstationController::class);
    Route::resource('storage-devices', API\StorageDeviceController::class);
    Route::resource('peripherals', API\PeripheralController::class);
    Route::resource('phones', API\PhoneController::class);
    Route::resource('physical-switches', API\PhysicalSwitchController::class);
    Route::resource('physical-routers', API\PhysicalRouterController::class);
    Route::resource('wifi-terminals', API\WifiTerminalController::class);
    Route::resource('physical-security-devices', API\PhysicalSecurityDeviceController::class);
    Route::resource('wans', API\WanController::class);
    Route::resource('mans', API\ManController::class);
    Route::resource('lans', API\LanController::class);
    Route::resource('vlans', API\VlanController::class);

    Route::resource('users', API\UserController::class);
    Route::resource('permission', API\PermissionController::class);
    Route::resource('role', API\RoleController::class);
});
