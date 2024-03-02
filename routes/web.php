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

// Cytoscape tests
Route::get('/cytoscape1', function () {
    return view('cytoscape1');
});
Route::get('/cytoscape2', function () {
    return view('cytoscape2');
});

Auth::routes();
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class,'logout']);

// Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    // Dashboard
    Route::get('/', [Admin\HomeController::class,'index'])->name('home');

    // Roles
    Route::resource('roles', Admin\RolesController::class);
    Route::delete('roles-destroy', [Admin\RolesController::class,'massDestroy'])->name('roles.massDestroy');

    // Users
    Route::resource('users', Admin\UsersController::class);
    Route::delete('users-destroy', [Admin\UsersController::class,'massDestroy'])->name('users.massDestroy');

    // DataProcessing
    Route::resource('data-processings', Admin\DataProcessingController::class);
    Route::delete('data-processings-destroy', [Admin\DataProcessingController::class,'massDestroy'])->name('data-processings.massDestroy');

    // SecurityControls
    Route::resource('security-controls', Admin\SecurityControlController::class);
    Route::delete('security-controls-destroy', [Admin\SecurityControlController::class,'massDestroy'])->name('security-controls.massDestroy');
    Route::get('security-controls-assign', [Admin\SecurityControlController::class,'assign'])->name('security-controls.assign');
    Route::get('security-controls-list', [Admin\SecurityControlController::class,'list'])->name('security-controls.list');
    Route::put('security-controls-associate', [Admin\SecurityControlController::class,'associate'])->name('security-controls-associate');

    // Entities
    Route::resource('entities', Admin\EntityController::class);
    Route::delete('entities-destroy', [Admin\EntityController::class,'massDestroy'])->name('entities.massDestroy');

    // Relations
    Route::resource('relations', Admin\RelationController::class);
    Route::delete('relations-destroy', [Admin\RelationController::class,'massDestroy'])->name('relations.massDestroy');

    // Processes
    Route::resource('processes', Admin\ProcessController::class);
    Route::delete('processes-destroy', [Admin\ProcessController::class,'massDestroy'])->name('processes.massDestroy');

    // Operations
    Route::resource('operations', Admin\OperationController::class);
    Route::delete('operations-destroy', [Admin\OperationController::class,'massDestroy'])->name('operations.massDestroy');

    // Actors
    Route::resource('actors', Admin\ActorController::class);
    Route::delete('actors-destroy', [Admin\ActorController::class,'massDestroy'])->name('actors.massDestroy');

    // Activities
    Route::resource('activities', Admin\ActivityController::class);
    Route::delete('activities-destroy', [Admin\ActivityController::class,'massDestroy'])->name('activities.massDestroy');

    // Tasks
    Route::resource('tasks', Admin\TaskController::class);
    Route::delete('tasks-destroy', [Admin\TaskController::class,'massDestroy'])->name('tasks.massDestroy');

    // Information
    Route::resource('information', Admin\InformationController::class);
    Route::delete('information-destroy', [Admin\InformationController::class,'massDestroy'])->name('information.massDestroy');

    // Application Blocks
    Route::resource('application-blocks', Admin\ApplicationBlockController::class);
    Route::delete('application-blocks-destroy', [Admin\ApplicationBlockController::class,'massDestroy'])->name('application-blocks.massDestroy');

    // Applications
    Route::resource('applications', Admin\MApplicationController::class);
    Route::delete('applications-destroy', [Admin\MApplicationController::class,'massDestroy'])->name('applications.massDestroy');

    // Application Services
    Route::resource('application-services', Admin\ApplicationServiceController::class);
    Route::delete('application-services-destroy', [Admin\ApplicationServiceController::class,'massDestroy'])->name('application-services.massDestroy');

    // Application Events
    Route::resource('application-events', Admin\MApplicationEventController::class);

    // Databases
    Route::resource('databases', Admin\DatabaseController::class);
    Route::delete('databases-destroy', [Admin\DatabaseController::class,'massDestroy'])->name('databases.massDestroy');

    // Fluxes
    Route::resource('fluxes', Admin\FluxController::class);
    Route::delete('fluxes-destroy', [Admin\FluxController::class,'massDestroy'])->name('fluxes.massDestroy');

    // Zone Admins
    Route::resource('zone-admins', Admin\ZoneAdminController::class);
    Route::delete('zone-admins-destroy', [Admin\ZoneAdminController::class,'massDestroy'])->name('zone-admins.massDestroy');

    // Annuaires
    Route::resource('annuaires', Admin\AnnuaireController::class);
    Route::delete('annuaires-destroy', [Admin\AnnuaireController::class,'massDestroy'])->name('annuaires.massDestroy');

    // Forest Ads
    Route::resource('forest-ads', Admin\ForestAdController::class);
    Route::delete('forest-ads-destroy', [Admin\ForestAdController::class,'massDestroy'])->name('forest-ads.massDestroy');

    // Domaine Ads
    Route::resource('domaine-ads', Admin\DomaineAdController::class);
    Route::delete('domaine-ads-destroy', [Admin\DomaineAdController::class,'massDestroy'])->name('domaine-ads.massDestroy');

    // Networks
    Route::resource('networks', Admin\NetworkController::class);
    Route::delete('networks-destroy', [Admin\NetworkController::class,'massDestroy'])->name('networks.massDestroy');

    // Subnetworks
    Route::resource('subnetworks', Admin\SubnetworkController::class);
    Route::delete('subnetworks-destroy', [Admin\SubnetworkController::class,'massDestroy'])->name('subnetworks.massDestroy');

    // Gateways
    Route::resource('gateways', Admin\GatewayController::class);
    Route::delete('gateways-destroy', [Admin\GatewayController::class,'massDestroy'])->name('gateways.massDestroy');

    // External Connected Entities
    Route::resource('external-connected-entities', Admin\ExternalConnectedEntityController::class);
    Route::delete('external-connected-entities-destroy', [Admin\ExternalConnectedEntityController::class,'massDestroy'])->name('external-connected-entities.massDestroy');

    // Network Switches
    Route::resource('network-switches', Admin\NetworkSwitchController::class);
    Route::delete('network-switches-destroy', [Admin\NetworkSwitchController::class,'massDestroy'])->name('network-switches.massDestroy');

    // Routers
    Route::resource('routers', Admin\RouterController::class);
    Route::delete('routers-destroy', [Admin\RouterController::class,'massDestroy'])->name('routers.massDestroy');

    // Security Devices
    Route::resource('security-devices', Admin\SecurityDeviceController::class);
    Route::delete('security-devices-destroy', [Admin\SecurityDeviceController::class,'massDestroy'])->name('security-devices.massDestroy');

    // Dhcp Servers
    Route::resource('dhcp-servers', Admin\DhcpServerController::class);
    Route::delete('dhcp-servers-destroy', [Admin\DhcpServerController::class,'massDestroy'])->name('dhcp-servers.massDestroy');

    // Dnsservers
    Route::resource('dnsservers', Admin\DnsserverController::class);
    Route::delete('dnsservers-destroy', [Admin\DnsserverController::class,'massDestroy'])->name('dnsservers.massDestroy');

    // Clusters
    Route::resource('clusters', Admin\ClusterController::class);
    Route::delete('clusters-destroy', [Admin\ClusterController::class,'massDestroy'])->name('clusters.massDestroy');

    // Logical Servers
    Route::resource('logical-servers', Admin\LogicalServerController::class);
    Route::delete('logical-servers-destroy', [Admin\LogicalServerController::class,'massDestroy'])->name('logical-servers.massDestroy');

    // Certificates
    Route::resource('certificates', Admin\CertificateController::class);
    Route::delete('certificates-destroy', [Admin\CertificateController::class,'massDestroy'])->name('certificates.massDestroy');

    // Sites
    Route::resource('sites', Admin\SiteController::class);
    Route::delete('sites-destroy', [Admin\SiteController::class,'massDestroy'])->name('sites.massDestroy');

    // Buildings
    Route::resource('buildings', Admin\BuildingController::class);
    Route::delete('buildings-destroy', [Admin\BuildingController::class,'massDestroy'])->name('buildings.massDestroy');

    // Bays
    Route::resource('bays', Admin\BayController::class);
    Route::delete('bays-destroy', [Admin\BayController::class,'massDestroy'])->name('bays.massDestroy');

    // Physical Servers
    Route::resource('physical-servers', Admin\PhysicalServerController::class);
    Route::delete('physical-servers-destroy', [Admin\PhysicalServerController::class,'massDestroy'])->name('physical-servers.massDestroy');

    // Workstations
    Route::resource('workstations', Admin\WorkstationController::class);
    Route::delete('workstations-destroy', [Admin\WorkstationController::class,'massDestroy'])->name('workstations.massDestroy');

    // Storage Devices
    Route::resource('storage-devices', Admin\StorageDeviceController::class);
    Route::delete('storage-devices-destroy', [Admin\StorageDeviceController::class,'massDestroy'])->name('storage-devices.massDestroy');

    // Peripherals
    Route::resource('peripherals', Admin\PeripheralController::class);
    Route::delete('peripherals-destroy', [Admin\PeripheralController::class,'massDestroy'])->name('peripherals.massDestroy');

    // Phones
    Route::resource('phones', Admin\PhoneController::class);
    Route::delete('phones-destroy', [Admin\PhoneController::class,'massDestroy'])->name('phones.massDestroy');

    // Physical Switches
    Route::resource('physical-switches', Admin\PhysicalSwitchController::class);
    Route::delete('physical-switches-destroy', [Admin\PhysicalSwitchController::class,'massDestroy'])->name('physical-switches.massDestroy');

    // Physical Routers
    Route::resource('physical-routers', Admin\PhysicalRouterController::class);
    Route::delete('physical-routers-destroy', [Admin\PhysicalRouterController::class,'massDestroy'])->name('physical-routers.massDestroy');

    // Wifi Terminals
    Route::resource('wifi-terminals', Admin\WifiTerminalController::class);
    Route::delete('wifi-terminals-destroy', [Admin\WifiTerminalController::class,'massDestroy'])->name('wifi-terminals.massDestroy');

    // Physical Security Devices
    Route::resource('physical-security-devices', Admin\PhysicalSecurityDeviceController::class);
    Route::delete('physical-security-devices-destroy', [Admin\PhysicalSecurityDeviceController::class,'massDestroy'])->name('physical-security-devices.massDestroy');

    // Physical Links
    // TODO : check why it is not working with physical-links as resource name
    Route::resource('links', Admin\PhysicalLinkController::class);
    Route::delete('links-destroy', [Admin\PhysicalLinkController::class,'massDestroy'])->name('links.massDestroy');

    // WANs
    Route::resource('wans', Admin\WanController::class);
    Route::delete('wans-destroy', [Admin\WanController::class,'massDestroy'])->name('wans.massDestroy');

    // MANs
    Route::resource('mans', Admin\ManController::class);
    Route::delete('mans-destroy', [Admin\ManController::class,'massDestroy'])->name('mans.massDestroy');

    // LANs
    Route::resource('lans', Admin\LanController::class);
    Route::delete('lans-destroy', [Admin\LanController::class,'massDestroy'])->name('lans.massDestroy');

    // VLANs
    Route::resource('vlans', Admin\VlanController::class);
    Route::delete('vlans-destroy', [Admin\VlanController::class,'massDestroy'])->name('vlans.massDestroy');

    // Application Modules
    Route::resource('application-modules', Admin\ApplicationModuleController::class);
    Route::delete('application-modules-destroy', [Admin\ApplicationModuleController::class,'massDestroy'])->name('application-modules.massDestroy');

    // Audit Logs
    Route::resource('audit-logs', Admin\AuditLogsController::class, ['except' => ['create', 'store', 'update', 'destroy']]);

    // Macro Processuses
    Route::resource('macro-processuses', Admin\MacroProcessusController::class);
    Route::delete('macro-processuses-destroy', [Admin\MacroProcessusController::class,'massDestroy'])->name('macro-processuses.massDestroy');

    // Global Search engine !
    Route::get('global-search', [Admin\GlobalSearchController::class,'search'])->name('globalSearch');

    // Certificate Configuration page
    Route::get('config/cert', [Admin\ConfigurationController::class,'getCertConfig'])->name('config.cert');
    Route::put('config/cert/save', [Admin\ConfigurationController::class,'saveCertConfig'])->name('config.cert.save');

    // CVE Configuration page
    Route::get('config/cve', [Admin\ConfigurationController::class,'getCVEConfig'])->name('config.cve');
    Route::put('config/cve/save', [Admin\ConfigurationController::class,'saveCVEConfig'])->name('config.cve.save');

    // Views
    // TODO : create a new class ViewController to reduce size of ReportController
    Route::get('report/gdpr', [Admin\ReportController::class, 'gdpr'])->name('report.gdpr');
    Route::get('report/ecosystem', [Admin\ReportController::class,'ecosystem'])->name('report.view.ecosystem');
    Route::get('report/information_system', [Admin\ReportController::class,'informationSystem'])->name('report.view.informaton-system');
    Route::get('report/administration', [Admin\ReportController::class,'administration'])->name('report.view.administration');
    Route::get('report/applications', [Admin\ReportController::class,'applications'])->name('report.view.applications');
    Route::get('report/application_flows', [Admin\ReportController::class,'applicationFlows'])->name('report.view.application-flows');
    Route::get('report/logical_infrastructure', [Admin\ReportController::class,'logicalInfrastructure'])->name('report.view.logical-infrastructure');
    Route::get('report/physical_infrastructure', [Admin\ReportController::class,'physicalInfrastructure'])->name('report.view.physical-infrastructure');
    Route::get('report/network_infrastructure', [Admin\ReportController::class,'networkInfrastructure'])->name('report.view.network-infrastructure');

    // Experimental views
    Route::get('report/zones', [Admin\ReportController::class,'zones'])->name('report.view.zones');
    Route::get('report/explore', [Admin\ExplorerController::class,'explore'])->name('report.explore');

    // Maturity levels
    Route::get('report/maturity1', [Admin\HomeController::class,'maturity1'])->name('report.maturity1');
    Route::get('report/maturity2', [Admin\HomeController::class,'maturity2'])->name('report.maturity2');
    Route::get('report/maturity3', [Admin\HomeController::class,'maturity3'])->name('report.maturity3');

    // Reporting
    Route::put('report/cartography', [Admin\CartographyController::class, 'cartography'])->name('report.cartography');
    Route::get('report/entities', [Admin\ReportController::class,'entities'])->name('report.entities');
    Route::get('report/applicationsByBlocks', [Admin\ReportController::class,'applicationsByBlocks'])->name('report.applicationsByBlocks');
    Route::get('report/logicalServers', [Admin\ReportController::class,'logicalServers'])->name('report.logicalServers');
    Route::get('report/securityNeeds', [Admin\ReportController::class, 'securityNeeds'])->name('report.securityNeeds');
    Route::get('report/logicalServerConfigs', [Admin\ReportController::class, 'logicalServerConfigs'])->name('report.logicalServerConfigs');
    Route::get('report/externalAccess', [Admin\ReportController::class,'externalAccess'])->name('report.externalAccess');
    Route::get('report/physicalInventory', [Admin\ReportController::class, 'physicalInventory'])->name('report.physicalInventory');
    Route::get('report/vlans', [Admin\ReportController::class,'vlans'])->name('report.vlans');
    Route::get('report/workstations', [Admin\ReportController::class,'workstations'])->name('report.workstations');
    Route::get('report/cve', [Admin\ReportController::class,'cve'])->name('report.view.cve');

    // GDPR
    Route::get('report/activityList', [Admin\ReportController::class,'activityList'])->name('report.activityList');
    Route::get('report/activityReport', [Admin\ReportController::class,'activityReport'])->name('report.activityReport');

    // CPE
    Route::get('/cpe/search/vendors', [Admin\CPEController::class,'vendors']);
    Route::get('/cpe/search/products', [Admin\CPEController::class,'products']);
    Route::get('/cpe/search/versions', [Admin\CPEController::class,'versions']);
    Route::get('/cpe/search/guess', [Admin\CPEController::class,'guess']);

    // Patching
    Route::get('/patching/index', [Admin\PatchingController::class,'index'])->name('patching.index');
    Route::get('/patching/edit/server/{id}', [Admin\PatchingController::class,'editServer'])->name('patching.edit.server');
    Route::put('/patching/server', [Admin\PatchingController::class,'updateServer'])->name('patching.server');
    Route::get('/patching/edit/application/{id}', [Admin\PatchingController::class,'editApplication'])->name('patching.edit.application');
    Route::put('/patching/application', [Admin\PatchingController::class,'updateApplication'])->name('patching.application');
    Route::get('/patching/dashboard', [Admin\PatchingController::class,'dashboard'])->name('patching.dashboard');

    // Auditing
    Route::get('audit/maturity', [Admin\AuditController::class,'maturity'])->name('audit.maturity');
    Route::get('audit/changes', [Admin\AuditController::class,'changes'])->name('audit.changes');

    // Documents
    Route::post('/documents/store', [Admin\DocumentController::class,'store'])->name('documents.store');
    Route::get('/documents/delete/{id}', [Admin\DocumentController::class,'delete'])->name('documents.delete');
    Route::get('/documents/show/{id}', [Admin\DocumentController::class,'get'])->name('documents.show');
    Route::get('/config/documents', [Admin\DocumentController::class,'stats'])->name('config.documents');
    Route::get('/config/documents/check', [Admin\DocumentController::class,'check'])->name('config.documents.check');

    // Monarc
    Route::get('monarc', [Admin\MonarcController::class,'index'])->name('monarc');

    // Reporting
    Route::get('doc/report', function () {
        return view('doc/report');
    })->name('doc.report');

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
    Route::get('password', [Controllers\Auth\ChangePasswordController::class, 'edit'])->name('password.edit');
    Route::post('password', [Controllers\Auth\ChangePasswordController::class, 'update'])->name('password.update');
    Route::get('preferences', [Controllers\Auth\ProfileController::class, 'showProfile']);
    Route::post('preferences', [Controllers\Auth\ProfileController::class, 'saveProfile']);
});
