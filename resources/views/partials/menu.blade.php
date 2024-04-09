<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li>
                <form id="search-form" action="/admin/global-search" method="GET">
                <div class="input-group">
                  <div class="custom-file">
                        <input name="search" type="text" class="form-control" style="padding: 0px 10px;" placeholder="Search..." id="search-field" tabindex="-1" value="{{ request()->get("search") }}">
                  </div>
                  <div class="input-group-prepend">
                     <button class="btn btn-outline-secondary" type="submit" id="search-button">
                         <i class="fa-fw fas fa-align-justify fa-search">
                         </i>
                     </button>
                  </div>
                </div>
            </form>
            </li>
            <li class="nav-item">
                <a href='{{ route("admin.home") }}' class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>

            @can('gdpr_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle open" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.menu.gdpr.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('data_processing_register_access')
                            <li class="nav-item">
                                <a href='{{ route("admin.data-processings.index") }}' class="nav-link {{ request()->is('admin/data-processings*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.dataProcessing.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('security_controls_access')
                            <li class="nav-item">
                                <a href='{{ route("admin.security-controls.index") }}' class="nav-link {{ request()->is('admin/security-controls*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.securityControl.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan


            @can('ecosystem_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.menu.ecosystem.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('entity_access')
                            <li class="nav-item">
                                <a href='{{ route("admin.entities.index") }}' class="nav-link {{ request()->is('admin/entities*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.entity.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('relation_access')
                            <li class="nav-item">
                                <a href='{{ route("admin.relations.index") }}' class="nav-link {{ request()->is('admin/relations*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.relation.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('metier_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.menu.metier.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @if (auth()->user()->granularity>=2)
                        @can('macro_processus_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.macro-processuses.index") }}" class="nav-link {{ request()->is('admin/macro-processuses*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.macroProcessus.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('process_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.processes.index") }}" class="nav-link {{ request()->is('admin/processes*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.process.title') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=3)
                        @can('activity_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.activities.index") }}" class="nav-link {{ request()->is('admin/activities*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.activity.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('operation_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.operations.index") }}" class="nav-link {{ request()->is('admin/operations*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.operation.title') }}
                                </a>
                            </li>
                        @endcan

                        @if (auth()->user()->granularity>=3)
                        @can('task_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.tasks.index") }}" class="nav-link {{ request()->is('admin/tasks*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.task.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif

                        @if (auth()->user()->granularity>=2)
                        @can('actor_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.actors.index") }}" class="nav-link {{ request()->is('admin/actors*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.actor.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('information_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.information.index") }}" class="nav-link {{ request()->is('admin/information*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.information.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('application_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.menu.application.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @if (auth()->user()->granularity>=2)
                        @can('application_block_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-blocks.index") }}" class="nav-link {{ request()->is('admin/application-blocks*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.applicationBlock.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('m_application_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.applications.index") }}" class="nav-link {{ request()->is('admin/applications*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.application.title') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=2)
                        @can('application_service_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-services.index") }}" class="nav-link {{ request()->is('admin/application-services*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.applicationService.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('application_module_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.application-modules.index") }}" class="nav-link {{ request()->is('admin/application-modules*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.applicationModule.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('database_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.databases.index") }}" class="nav-link {{ request()->is('admin/databases*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.database.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('flux_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.fluxes.index") }}" class="nav-link {{ request()->is('admin/fluxes*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.flux.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('administration_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.administration.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('zone_admin_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.zone-admins.index") }}" class="nav-link {{ request()->is('admin/zone-admins*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.zoneAdmin.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('annuaire_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.annuaires.index") }}" class="nav-link {{ request()->is('admin/annuaires*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.annuaire.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('forest_ad_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.forest-ads.index") }}" class="nav-link {{ request()->is('admin/forest-ads*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.forestAd.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('domaine_ad_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.domaine-ads.index") }}" class="nav-link {{ request()->is('admin/domaine-ads*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.domaineAd.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('infrastructure_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.menu.logical_infrastructure.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('network_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.networks.index") }}" class="nav-link {{ request()->is('admin/networks*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.network.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('subnetwork_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.subnetworks.index") }}" class="nav-link {{ request()->is('admin/subnetworks*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.subnetwork.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('gateway_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.gateways.index") }}" class="nav-link {{ request()->is('admin/gateways*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.gateway.title') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=2)
                        @can('external_connected_entity_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.external-connected-entities.index") }}" class="nav-link {{ request()->is('admin/external-connected-entities*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.externalConnectedEntity.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('network_switch_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.network-switches.index") }}" class="nav-link {{ request()->is('admin/network-switches*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.networkSwitch.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('router_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.routers.index") }}" class="nav-link {{ request()->is('admin/routers*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.router.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('security_device_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.security-devices.index") }}" class="nav-link {{ request()->is('admin/security-devices*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.securityDevice.title') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=2)
                        @can('dhcp_server_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.dhcp-servers.index") }}" class="nav-link {{ request()->is('admin/dhcp-servers*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.dhcpServer.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('dnsserver_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.dnsservers.index") }}" class="nav-link {{ request()->is('admin/dnsservers*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.dnsserver.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('cluster_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.clusters.index") }}" class="nav-link {{ request()->is('admin/clusters*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.cluster.title_short') }}
                                </a>
                            </li>
                        @endcan
                        @can('logical_server_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.logical-servers.index") }}" class="nav-link {{ request()->is('admin/logical-servers*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.logicalServer.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('logical_flow_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.logical-flows.index") }}" class="nav-link {{ request()->is('admin/logical-flows*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.logicalFlow.title_short') }}
                                </a>
                            </li>
                        @endcan
                        @can('vlan_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.vlans.index") }}" class="nav-link {{ request()->is('admin/vlans*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.vlan.title_short') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=2)
                        @can('certificate_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.certificates.index") }}" class="nav-link {{ request()->is('admin/certificatess*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.certificate.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                    </ul>
                </li>
            @endcan
            @can('physicalinfrastructure_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-folder nav-icon">

                        </i>
                        {{ trans('cruds.menu.physical_infrastructure.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('site_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.sites.index") }}" class="nav-link {{ request()->is('admin/sites*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.site.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('building_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.buildings.index") }}" class="nav-link {{ request()->is('admin/buildings*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.building.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('bay_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.bays.index") }}" class="nav-link {{ request()->is('admin/bays*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.bay.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('physical_server_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.physical-servers.index") }}" class="nav-link {{ request()->is('admin/physical-servers*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.physicalServer.title') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=2)
                        @can('workstation_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.workstations.index") }}" class="nav-link {{ request()->is('admin/workstations*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.workstation.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('storage_device_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.storage-devices.index") }}" class="nav-link {{ request()->is('admin/storage-devices*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.storageDevice.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('peripheral_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.peripherals.index") }}" class="nav-link {{ request()->is('admin/peripherals*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.peripheral.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('phone_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.phones.index") }}" class="nav-link {{ request()->is('admin/phones*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.phone.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('physical_switch_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.physical-switches.index") }}" class="nav-link {{ request()->is('admin/physical-switches*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.physicalSwitch.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('physical_router_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.physical-routers.index") }}" class="nav-link {{ request()->is('admin/physical-routers*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.physicalRouter.title') }}
                                </a>
                            </li>
                        @endcan
                        @if (auth()->user()->granularity>=2)
                        @can('wifi_terminal_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.wifi-terminals.index") }}" class="nav-link {{ request()->is('admin/wifi-terminals*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.wifiTerminal.title') }}
                                </a>
                            </li>
                        @endcan
                        @endif
                        @can('physical_security_device_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.physical-security-devices.index") }}" class="nav-link {{ request()->is('admin/physical-security-devices*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.physicalSecurityDevice.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('physical_link_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.links.index") }}" class="nav-link {{ request()->is('admin/links*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.physicalLink.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('wan_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.wans.index") }}" class="nav-link {{ request()->is('admin/wans*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.wan.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('man_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.mans.index") }}" class="nav-link {{ request()->is('admin/mans*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.man.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('lan_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.lans.index") }}" class="nav-link {{ request()->is('admin/lans*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-align-justify nav-icon">

                                    </i>
                                    {{ trans('cruds.lan.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('configure')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-gear nav-icon">

                        </i>
                        {{ trans('cruds.menu.configuration.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('user_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user nav-icon">

                                    </i>
                                    {{ trans('cruds.user.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-group nav-icon">

                                    </i>
                                    {{ trans('cruds.role.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('configure')
                            <li class="nav-item">
                                <a href="{{ route("admin.config.cert") }}" class="nav-link {{ request()->is('admin/config/certificate*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-lock nav-icon">

                                    </i>
                                    {{ trans('cruds.configuration.certificate.title_short') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.config.cve") }}" class="nav-link" class="nav-link {{ request()->is('admin/config/cve*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-bug nav-icon">

                                    </i>
                                    {{ trans('cruds.configuration.cve.title_short') }}
                                </a>
                            </li>
                        @endcan
                            <li class="nav-item">
                                <a href="{{ route("admin.config.documents") }}" class="nav-link" class="nav-link {{ request()->is('admin/config/documents*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-file-text nav-icon">

                                    </i>
                                    {{ trans('cruds.configuration.documents.title_short') }}
                                </a>
                            </li>
                        @can('audit_log_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-files-o nav-icon">

                                    </i>
                                    {{ trans('cruds.auditLog.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
