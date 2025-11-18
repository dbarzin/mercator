<nav id="sidebar" class="sidebar">
    <div class="search-box">
        <form id="search-form" action="/admin/global-search" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Rechercher...">
        </form>
    </div>
    <div class="flex-grow-1">
        <a href="{{ route('admin.home') }}"><i class="bi bi-speedometer2"></i><span
                    class="menu-text">{{ trans('global.dashboard') }}</span></a>
        @can('gdpr_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu1" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span class="menu-text">{{ trans('cruds.menu.gdpr.title') }}</span>
            </a>
            <div id="submenu1" class="collapse {{
            (
                request()->is('admin/data-processing*')||
                request()->is('admin/security*')
            ) ? 'show' : '' }}">
                @can('data_processing_access')
                    <a href="{{ route('admin.data-processings.index') }}"
                       class="ps-4 {{ request()->is('admin/data-processings*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.dataProcessing.title') }}</span>
                    </a>
                @endcan
                @can('security_control_access')
                    <a href="{{ route('admin.security-controls.index') }}"
                       class="ps-4 {{ request()->is('admin/security*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.securityControl.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('ecosystem_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu2" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span
                        class="menu-text">{{ trans('cruds.menu.ecosystem.title') }}</span>
            </a>
            <div id="submenu2" class="collapse {{
            (
                request()->is('admin/entities*')||
                request()->is('admin/relations*')
            ) ? 'show' : '' }}">
                @can('entity_access')
                    <a href="{{ route('admin.entities.index') }}"
                       class="ps-4 {{ request()->is('admin/entities*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.entity.title') }}</span>
                    </a>
                @endcan
                @can('relation_access')
                    <a href="{{ route('admin.relations.index') }}"
                       class="ps-4 {{ request()->is('admin/relations*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.relation.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('metier_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu3" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span class="menu-text">{{ trans('cruds.menu.metier.title') }}</span>
            </a>
            <div id="submenu3" class="collapse {{
            (
                request()->is('admin/macro-processuses*')||
                request()->is('admin/processes*')||
                request()->is('admin/activities*')||
                request()->is('admin/operations*')||
                request()->is('admin/tasks*')||
                request()->is('admin/actors*')||
                request()->is('admin/information*')
            ) ? 'show' : '' }}">
                @can('macro_processus_access')
                    <a href="{{ route('admin.macro-processuses.index') }}"
                       class="ps-4 {{ request()->is('admin/macro-processuses*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.macroProcessus.title') }}</span>
                    </a>
                @endcan
                @can('process_access')
                    <a href="{{ route('admin.processes.index') }}"
                       class="ps-4 {{ request()->is('admin/processes*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.process.title') }}</span>
                    </a>
                @endcan
                @can('activity_access')
                    <a href="{{ route('admin.activities.index') }}"
                       class="ps-4 {{ request()->is('admin/activities*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.activity.title') }}</span>
                    </a>
                @endcan
                @can('operation_access')
                    <a href="{{ route('admin.operations.index') }}"
                       class="ps-4 {{ request()->is('admin/operations*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.operation.title') }}</span>
                    </a>
                @endcan
                @can('task_access')
                    <a href="{{ route('admin.tasks.index') }}"
                       class="ps-4 {{ request()->is('admin/tasks*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.task.title') }}</span>
                    </a>
                @endcan
                @can('actor_access')
                    <a href="{{ route('admin.actors.index') }}"
                       class="ps-4 {{ request()->is('admin/actors*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.actor.title') }}</span>
                    </a>
                @endcan
                @can('information_access')
                    <a href="{{ route('admin.information.index') }}"
                       class="ps-4 {{ request()->is('admin/information*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.information.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('application_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu4" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span
                        class="menu-text">{{ trans('cruds.menu.application.title') }}</span>
            </a>
            <div id="submenu4" class="collapse {{
            (
            request()->is('admin/application-blocks*')||
            request()->is('admin/applications*')||
            request()->is('admin/application-services*')||
            request()->is('admin/application-modules*')||
            request()->is('admin/databases*')||
            request()->is('admin/fluxes*')
            ) ? 'show' : '' }}">
                @can('application_block_access')
                    <a href="{{ route('admin.application-blocks.index') }}"
                       class="ps-4 {{ request()->is('admin/application-blocks*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.applicationBlock.title') }}</span>
                    </a>
                @endcan
                @can('m_application_access')
                    <a href="{{ route('admin.applications.index') }}"
                       class="ps-4 {{ request()->is('admin/applications*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.application.title') }}</span>
                    </a>
                @endcan
                @can('application_service_access')
                    <a href="{{ route('admin.application-services.index') }}"
                       class="ps-4 {{ request()->is('admin/application-services*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.applicationService.title') }}</span>
                    </a>
                @endcan
                @can('application_module_access')
                    <a href="{{ route('admin.application-modules.index') }}"
                       class="ps-4 {{ request()->is('admin/application-modules*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.applicationModule.title') }}</span>
                    </a>
                @endcan
                @can('database_access')
                    <a href="{{ route('admin.databases.index') }}"
                       class="ps-4 {{ request()->is('admin/databases*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.database.title') }}</span>
                    </a>
                @endcan
                @can('flux_access')
                    <a href="{{ route('admin.fluxes.index') }}"
                       class="ps-4 {{ request()->is('admin/fluxes*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.flux.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('administration_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu5" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span
                        class="menu-text">{{ trans('cruds.administration.title') }}</span>
            </a>
            <div id="submenu5" class="collapse {{ (
            request()->is('admin/zone-admins*')||
            request()->is('admin/annuaires*')||
            request()->is('admin/forest-ads*')||
            request()->is('admin/domaine-ads*')||
            request()->is('admin/admin-users*')
            ) ? 'show' : '' }}">
                @can('zone_admin_access')
                    <a href="{{ route('admin.zone-admins.index') }}"
                       class="ps-4 {{ request()->is('admin/zone-admins*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.zoneAdmin.title') }}</span>
                    </a>
                @endcan
                @can('annuaire_access')
                    <a href="{{ route('admin.annuaires.index') }}"
                       class="ps-4 {{ request()->is('admin/annuaires*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.annuaire.title') }}</span>
                    </a>
                @endcan
                @can('forest_ad_access')
                    <a href="{{ route('admin.forest-ads.index') }}"
                       class="ps-4 {{ request()->is('admin/forest-ads*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.forestAd.title') }}</span>
                    </a>
                @endcan
                @can('domaine_ad_access')
                    <a href="{{ route('admin.domaine-ads.index') }}"
                       class="ps-4 {{ request()->is('admin/domaine-ads*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.domaineAd.title') }}</span>
                    </a>
                @endcan
                @can('admin_user_access')
                    <a href="{{ route('admin.admin-users.index') }}"
                       class="ps-4 {{ request()->is('admin/admin-users*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.adminUser.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('infrastructure_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu6" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span
                        class="menu-text">{{ trans('cruds.menu.logical_infrastructure.title') }}</span>
            </a>
            <div id="submenu6" class="collapse {{
            (
            request()->is('admin/networks*')||
            request()->is('admin/subnetworks*')||
            request()->is('admin/gateways*')||
            request()->is('admin/external-connected*')||
            request()->is('admin/routers*')||
            request()->is('admin/network-switches*')||
            request()->is('admin/security-devices*')||
            request()->is('admin/dnsservers*')||
            request()->is('admin/dhcp-servers*')||
            request()->is('admin/clusters*')||
            request()->is('admin/logical-servers*')||
            request()->is('admin/containers*')||
            request()->is('admin/logical-flows*')||
            request()->is('admin/vlans*')||
            request()->is('admin/certificates*')
            ) ? 'show' : '' }}">
                @can('network_access')
                    <a href="{{ route('admin.networks.index') }}"
                       class="ps-4 {{ request()->is('admin/networks*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.network.title') }}</span>
                    </a>
                @endcan
                @can('subnetwork_access')
                    <a href="{{ route('admin.subnetworks.index') }}"
                       class="ps-4 {{ request()->is('admin/subnetworks*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.subnetwork.title') }}</span>
                    </a>
                @endcan
                @can('gateway_access')
                    <a href="{{ route('admin.gateways.index') }}"
                       class="ps-4 {{ request()->is('admin/gateways*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.gateway.title_short') }}</span>
                    </a>
                @endcan
                @can('external_connected_entity_access')
                    <a href="{{ route('admin.external-connected-entities.index') }}"
                       class="ps-4 {{ request()->is('admin/external-connected*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.externalConnectedEntity.title') }}</span>
                    </a>
                @endcan
                @can('router_access')
                    <a href="{{ route('admin.routers.index') }}"
                       class="ps-4 {{ request()->is('admin/routers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.router.title') }}</span>
                    </a>
                @endcan
                @can('network_switch_access')
                    <a href="{{ route('admin.network-switches.index') }}"
                       class="ps-4 {{ request()->is('admin/network-switches*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.networkSwitch.title') }}</span>
                    </a>
                @endcan
                @can('security_device_access')
                    <a href="{{ route('admin.security-devices.index') }}"
                       class="ps-4 {{ request()->is('admin/security-devices*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.securityDevice.title') }}</span>
                    </a>
                @endcan
                @can('dhcp_server_access')
                    <a href="{{ route('admin.dhcp-servers.index') }}"
                       class="ps-4 {{ request()->is('admin/dhcp-servers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.dhcpServer.title') }}</span>
                    </a>
                @endcan
                @can('dnsserver_access')
                    <a href="{{ route('admin.dnsservers.index') }}"
                       class="ps-4 {{ request()->is('admin/dnsservers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.dnsserver.title') }}</span>
                    </a>
                @endcan
                @can('cluster_access')
                    <a href="{{ route('admin.clusters.index') }}"
                       class="ps-4 {{ request()->is('admin/clusters*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.cluster.title_short') }}</span>
                    </a>
                @endcan
                @can('logical_server_access')
                    <a href="{{ route('admin.logical-servers.index') }}"
                       class="ps-4 {{ request()->is('admin/logical-servers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.logicalServer.title') }}</span>
                    </a>
                @endcan
                @can('container_access')
                    <a href="{{ route('admin.containers.index') }}"
                       class="ps-4 {{ request()->is('admin/containers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.container.title') }}</span>
                    </a>
                @endcan
                @can('logical_flow_access')
                    <a href="{{ route('admin.logical-flows.index') }}"
                       class="ps-4 {{ request()->is('admin/logical-flows*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.logicalFlow.title_short') }}</span>
                    </a>
                @endcan
                @can('vlan_access')
                    <a href="{{ route('admin.vlans.index') }}"
                       class="ps-4 {{ request()->is('admin/vlans*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.vlan.title_short') }}</span>
                    </a>
                @endcan
                @can('certificate_access')
                    <a href="{{ route('admin.certificates.index') }}"
                       class="ps-4 {{ request()->is('admin/certificates*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.certificate.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('physicalinfrastructure_access')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu7" role="button" aria-expanded="false">
                <i class="bi bi-folder-fill"></i><span
                        class="menu-text">{{ trans('cruds.menu.physical_infrastructure.title') }}</span>
            </a>
            <div id="submenu7" class="collapse {{ (
            request()->is('admin/sites*')||
            request()->is('admin/buildings*')||
            request()->is('admin/bays*')||
            request()->is('admin/physical*')||
            request()->is('admin/workstations*')||
            request()->is('admin/storage-devices*')||
            request()->is('admin/peripherals*')||
            request()->is('admin/phones*')||
            request()->is('admin/wifi-terminals*')||
            request()->is('admin/links*')||
            request()->is('admin/wans*')||
            request()->is('admin/mans*')||
            request()->is('admin/lans*')
            ) ? 'show' : '' }}">
                @can('site_access')
                    <a href="{{ route('admin.sites.index') }}"
                       class="ps-4 {{ request()->is('admin/sites*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.site.title') }}</span>
                    </a>
                @endcan
                @can('building_access')
                    <a href="{{ route('admin.buildings.index') }}"
                       class="ps-4 {{ request()->is('admin/buildings*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.building.title') }}</span>
                    </a>
                @endcan
                @can('bay_access')
                    <a href="{{ route('admin.bays.index') }}"
                       class="ps-4 {{ request()->is('admin/bays*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.bay.title') }}</span>
                    </a>
                @endcan
                @can('physical_server_access')
                    <a href="{{ route('admin.physical-servers.index') }}"
                       class="ps-4 {{ request()->is('admin/physical-servers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.physicalServer.title') }}</span>
                    </a>
                @endcan
                @can('workstation_access')
                    <a href="{{ route('admin.workstations.index') }}"
                       class="ps-4 {{ request()->is('admin/workstations*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.workstation.title') }}</span>
                    </a>
                @endcan
                @can('storage_device_access')
                    <a href="{{ route('admin.storage-devices.index') }}"
                       class="ps-4 {{ request()->is('admin/storage-devices*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.storageDevice.title') }}</span>
                    </a>
                @endcan
                @can('peripheral_access')
                    <a href="{{ route('admin.peripherals.index') }}"
                       class="ps-4 {{ request()->is('admin/peripherals*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.peripheral.title') }}</span>
                    </a>
                @endcan
                @can('phone_access')
                    <a href="{{ route('admin.phones.index') }}"
                       class="ps-4 {{ request()->is('admin/phones*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.phone.title') }}</span>
                    </a>
                @endcan
                @can('physical_switch_access')
                    <a href="{{ route('admin.physical-switches.index') }}"
                       class="ps-4 {{ request()->is('admin/physical-switches*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.physicalSwitch.title') }}</span>
                    </a>
                @endcan
                @can('physical_router_access')
                    <a href="{{ route('admin.physical-routers.index') }}"
                       class="ps-4 {{ request()->is('admin/physical-routers*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.physicalRouter.title') }}</span>
                    </a>
                @endcan
                @can('wifi_terminal_access')
                    <a href="{{ route('admin.wifi-terminals.index') }}"
                       class="ps-4 {{ request()->is('admin/wifi-terminals*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.wifiTerminal.title') }}</span>
                    </a>
                @endcan
                @can('physical_security_device_access')
                    <a href="{{ route('admin.physical-security-devices.index') }}"
                       class="ps-4 {{ request()->is('admin/physical-security-devices*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.physicalSecurityDevice.title') }}</span>
                    </a>
                @endcan
                @can('physical_link_access')
                    <a href="{{ route('admin.links.index') }}"
                       class="ps-4 {{ request()->is('admin/links*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span
                                class="menu-text">{{ trans('cruds.physicalLink.title') }}</span>
                    </a>
                @endcan
                @can('wan_access')
                    <a href="{{ route('admin.wans.index') }}"
                       class="ps-4 {{ request()->is('admin/wans*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.wan.title') }}</span>
                    </a>
                @endcan
                @can('man_access')
                    <a href="{{ route('admin.mans.index') }}"
                       class="ps-4 {{ request()->is('admin/mans*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.man.title') }}</span>
                    </a>
                @endcan
                @can('lan_access')
                    <a href="{{ route('admin.lans.index') }}"
                       class="ps-4 {{ request()->is('admin/lans*') ? 'active' : '' }}">
                        <i class="bi bi-list-columns"></i><span class="menu-text">{{ trans('cruds.lan.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        @can('configure')
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#submenu9" role="button" aria-expanded="false">
                <i class="bi bi-gear-fill"></i><span
                        class="menu-text">{{ trans('cruds.menu.configuration.title') }}</span>
            </a>
            <div id="submenu9" class="collapse {{ (
            request()->is('admin/users*')||
            request()->is('admin/roles*')||
            request()->is('admin/cve*')||
            request()->is('admin/config*')||
            request()->is('admin/documents*')||
            request()->is('admin/audit-logs*')||
            request()->is('admin/history*')
            ) ? 'show' : '' }}">
                @can('user_access')
                    <a href="{{ route("admin.users.index") }}"
                       class="ps-4 {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="bi bi-person-fill"></i><span class="menu-text">{{ trans('cruds.user.title') }}</span>
                    </a>
                @endcan
                @can('role_access')
                    <a href="{{ route("admin.roles.index") }}"
                       class="ps-4 {{ request()->is('admin/roles*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i><span
                                class="menu-text">{{ trans('cruds.role.title') }}</span>
                    </a>
                @endcan
                @can('configure')
                    <a href="{{ route("admin.config.parameters") }}"
                       class="ps-4 {{ request()->is('admin/config/parameters*') ? 'active' : '' }}">
                        <i class="bi bi-wrench-adjustable"></i><span
                                class="menu-text">{{ trans('cruds.configuration.parameters.title_short') }}</span>
                    </a>
                    <a href="{{ route("admin.config.cert") }}"
                       class="ps-4 {{ request()->is('admin/config/cert*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock-fill"></i><span
                                class="menu-text">{{ trans('cruds.configuration.certificate.title_short') }}</span>
                    </a>
                    <a href="{{ route("admin.config.cve") }}"
                       class="ps-4 {{ request()->is('admin/config/cve*') ? 'active' : '' }}">
                        <i class="bi bi-bug-fill"></i><span
                                class="menu-text">{{ trans('cruds.configuration.cve.title_short') }}</span>
                    </a>
                    <a href="{{ route("admin.config.import") }}"
                       class="ps-4 {{ request()->is('admin/config/import') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-arrow-up-fill"></i><span
                                class="menu-text">{{ trans("cruds.configuration.import.title_short") }}</span>
                    </a>
                @endcan
                <a href="{{ route("admin.config.documents") }}"
                   class="ps-4 {{ request()->is('admin/config/documents*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i><span
                            class="menu-text">{{ trans('cruds.configuration.documents.title_short') }}</span>
                </a>
                @can('audit_log_access')
                    <a href="{{ route("admin.audit-logs.index") }}"
                       class="ps-4 {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                        <i class="bi bi-archive-fill"></i><span
                                class="menu-text">{{ trans('cruds.auditLog.title') }}</span>
                    </a>
                @endcan
            </div>
        @endcan
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="bi bi-box-arrow-right"></i><span class="menu-text">{{ trans('global.logout') }}</span>
        </a>
    </div>
    <div class="sidebar-footer">
        @if (config('app.licence'))
            Enterprise
        @else
            Open Source
        @endif
        <br>
        Version {{ $appVersion }}
    </div>
</nav>
