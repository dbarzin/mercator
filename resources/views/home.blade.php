@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {!! trans("panel.maturity_levels") !!}
    </div>
    <div class="card-body">
        <table>
            <tr>
                <td align="center">
                  <a href="/admin/report/maturity1">
                    <div style="width: 350px; height: 180px;">
                      <canvas id="gauge_chart1_div"></canvas>
                    </div>
                    {!! trans("panel.level_1.title_short") !!}
                  </a>
                </td>
                <td align="center">
                  <a href="/admin/report/maturity2">
                    <div style="width: 350px; height: 180px;">
                      <canvas id="gauge_chart2_div"></canvas>
                    </div>
                    {!! trans("panel.level_2.title_short") !!}
                  </a>
                <td align="center">
                  <a href="/admin/report/maturity3">
                    <div style="width: 350px; height: 180px;">
                      <canvas id="gauge_chart3_div"></canvas>
                    </div>
                    {!! trans("panel.level_3.title_short") !!}
                  </a>
                </td>
            </tr>
        </table>
    </div>
  </div>
  <br>
  <div class="card">
    <div class="card-header">
      {!! trans("panel.repartition") !!}
    </div>
    <div class="card-body">
        <div style="width: 1075px; height: 400px;">
          <canvas id="bar_chart_div"></canvas>
        </div>
    </div>
  </div>
  <br>
  <div class="card">
    <div class="card-header">
      {!! trans("panel.treemap") !!}
    </div>
    <div class="card-body">
        <div style="width: 1050px; height: 500px;">
          <canvas id="treemap_chart_div"></canvas>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
window.chartData = {
maturity1: {{ $maturity1  }},
maturity2: {{ $maturity2  }},
maturity3: {{ $maturity3  }},
barChart: {
  mode: 'single',
  labels: [
      "{!! trans('cruds.menu.gdpr.title_short') !!}",
      "{!! trans('cruds.menu.ecosystem.title_short') !!}",
      "{!! trans('cruds.menu.metier.title_short') !!}",
      "{!! trans('cruds.menu.application.title_short') !!}",
      "{!! trans('cruds.menu.administration.title_short') !!}",
      "{!! trans('cruds.menu.logical_infrastructure.title_short') !!}",
      "{!! trans('cruds.menu.physical_infrastructure.title_short') !!}",
      ],
  datasets: [
  @can('data_processing_register_access')
  {
    label: "{!! trans('cruds.dataProcessing.title') !!}",
    data: [ {!! $data_processing !!}, 0, 0, 0, 0, 0, 0],
    value: {!! $data_processing !!},
    url: "/admin/data-processings"
  },
  @endcan
  @can('security_controls_access')
  {
    label: "{!! trans('cruds.securityControl.title_short') !!}",
    data: [{!! $security_controls !!}, 0, 0, 0, 0, 0, 0],
    value: {!! $security_controls !!},
    url: "/admin/security-controls"
  },
  @endcan
  @can('entity_access')
  {
    label: "{!! trans('cruds.entity.title') !!}",
    data: [ 0, {!! $entities !!}, 0, 0, 0, 0, 0],
    value: {!! $entities !!},
    url: "/admin/entities"
  } ,
  @endcan
  @can('relation_access')
  {
    label: "{!! trans('cruds.relation.title') !!}",
    data: [0, {!! $relations !!}, 0, 0, 0, 0, 0],
    value: {!! $relations !!},
    url: "/admin/relations"
  },
  @endcan
  @can('macro_processus_access')
  {
    label: "{!! trans('cruds.macroProcessus.title') !!}",
    data: [ 0, 0, {!! $macroProcessuses !!}, 0, 0, 0, 0],
    value: {!! $macroProcessuses !!},
    url: "/admin/macro-processuses"
  },
  @endcan
  @can('process_access')
  {
    label: "{!! trans('cruds.process.title') !!}",
    data: [ 0, 0, {!! $processes !!}, 0, 0, 0, 0],
    value: {!! $processes !!},
    url: "/admin/processes"
  },
  @endcan
  @can('activity_access')
  {
    label: "{!! trans('cruds.activity.title') !!}",
    data: [ 0, 0, {!! $activities !!}, 0, 0, 0, 0],
    value: {!! $activities !!},
    url: "/admin/activities"
  },
  @endcan
  @can('operation_access')
  {
    label: "{!! trans('cruds.operation.title') !!}",
    data: [ 0, 0, {!! $operations !!}, 0, 0, 0, 0],
    value: {!! $operations !!},
    url: "/admin/operations"
  },
  @endcan
  @can('task_access')
  {
    label: "{!! trans('cruds.task.title') !!}",
    data: [ 0, 0, {!! $tasks !!}, 0, 0, 0, 0],
    value: {!! $tasks !!},
    url: "admin/tasks"
  },
  @endcan
  @can('actor_access')
  {
    label: "{!! trans('cruds.actor.title') !!}",
    data: [ 0, 0, {!! $actors !!}, 0, 0, 0, 0],
    value: {!! $actors !!},
    url: "/admin/actors"
  },
  @endcan
  @can('information_access')
  {
    label: "{!! trans('cruds.information.title') !!}",
    data: [ 0, 0, {!! $informations !!}, 0, 0, 0, 0],
    value: {!! $informations !!},
    url: "/admin/information"
  },
  @endcan
  @can('application_block_access')
  {
    label: "{!! trans('cruds.applicationBlock.title') !!}",
    data: [ 0, 0, 0, {!! $applicationBlocks !!}, 0, 0, 0],
    value: {!! $applicationBlocks !!},
    url: "/admin/application-blocks"
  },
  @endcan
  @can('application_access')
  {
    label: "{!! trans('cruds.application.title') !!}",
    data: [ 0, 0, 0, {!! $applications !!}, 0, 0, 0],
    value: {!! $applications !!},
    url: "/admin/applications"
  },
  @endcan
  @can('application_service_access')
  {
    label: "{!! trans('cruds.applicationService.title_short') !!}",
    data: [0, 0, 0, {!! $applicationServices !!}, 0, 0, 0],
    value: {!! $applicationServices !!},
    url: "/admin/application-services"
  },
  @endcan
  @can('application_module_access')
  {
    label: "{!! trans('cruds.applicationModule.title_short') !!}",
    data: [0, 0, 0, {!! $applicationModules !!}, 0, 0, 0],
    value: {!! $applicationModules !!},
    url: "/admin/application-modules"
  },
  @endcan
  @can('database_access')
  {
    label: "{!! trans('cruds.database.title') !!}",
    data: [0, 0, 0, {!! $databases !!}, 0, 0, 0],
    value: {!! $databases !!},
    url: "/admin/databases"
  },
  @endcan
  @can('flux_access')
  {
    label: "{!! trans('cruds.flux.title') !!}",
    data: [ 0, 0, 0, {!! $fluxes !!}, 0, 0, 0],
    value: {!! $fluxes !!},
    url: "/admin/fluxes",
  },
  @endcan
  @can('zone_admin_access')
  {
    label: "{!! trans('cruds.zoneAdmin.title_short') !!}",
    data: [ 0, 0, 0, 0, {!!$zones!!}, 0, 0],
    value: {!!$zones!!},
    url: "/admin/zone-admins"
  },
  @endcan
  @can('annuaire_access')
  {
    label: "{!! trans('cruds.annuaire.title_short') !!}",
    data: [ 0, 0, 0, 0, {!!$annuaires!!}, 0, 0],
    value: {!!$annuaires!!},
    url: "/admin/annuaires"
  },
  @endcan
  @can('forest_ad_access')
  {
    label: "{!! trans('cruds.forestAd.title_short') !!}",
    data: [ 0, 0, 0, 0, {!!$forests!!}, 0, 0],
    value: {!!$forests!!},
    url: "/admin/forest-ads"
  },
  @endcan
  @can('domaine_ad_access')
  {
    label: "{!! trans('cruds.domaineAd.title_short') !!}",
    data: [ 0, 0, 0, 0, {!!$domaines!!}, 0, 0],
    value:  {!!$domaines!!},
    url: "/admin/domaine-ads"
  },
  @endcan
  @can('network_access')
  {
    label: "{!! trans('cruds.network.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $networks !!}, 0],
    value: {!! $networks !!},
    url: "/admin/networks"
  },
  @endcan
  @can('subnetwork_access')
  {
    label: "{!! trans('cruds.subnetwork.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $subnetworks !!}, 0],
    value: {!! $subnetworks !!},
    url: "/admin/subnetworks"
  },
  @endcan
  @can('gateway_access')
  {
    label: "{!! trans('cruds.gateway.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $gateways !!}, 0],
    value: {!! $gateways !!},
    url: "/admin/gateways"
  },
  @endcan
  @can('external_connected_entity_access')
  {
    label: "{!! trans('cruds.externalConnectedEntity.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $externalConnectedEntities !!}, 0],
    value: {!! $externalConnectedEntities !!},
    url: "/admin/external-connected-entities"
  },
  @endcan
  @can('network_switch_access')
  {
    label: "{!! trans('cruds.networkSwitch.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $switches !!}, 0],
    value: {!! $switches !!},
    url: "/admin/network-switches"
  },
  @endcan
  @can('router_access')
  {
    label: "{!! trans('cruds.router.title_short') !!}",
    data: [0, 0, 0, 0, 0, {!! $routers !!}, 0],
    value: {!! $routers !!},
    url: "/admin/routers"
  },
  @endcan
  @can('security_device_access')
  {
    label: "{!! trans('cruds.securityDevice.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $securityDevices !!}, 0],
    value: {!! $securityDevices !!},
    url: "/admin/security-devices"
  },
  @endcan
  @can('cluster_access')
  {
  label: "{!! trans('cruds.cluster.title_short') !!}",
  data: [0, 0, 0, 0, 0, {!! $clusters !!}, 0],
  value: {!! $clusters !!},
  url: "/admin/clusters"
  },
  @endcan
  @can('logical_server_access')
  {
    label: "{!! trans('cruds.logicalServer.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $logicalServers !!}, 0],
    value: {!! $logicalServers !!},
    url: "/admin/logical-servers"
  },
  @endcan
  @can('container_access')
  {
    label: "{!! trans('cruds.container.title') !!}",
    data: [0, 0, 0, 0, 0, {!! $containers !!}, 0],
    value: {!! $containers !!},
    url: "/admin/containers"
  },
  @endcan
  @can('certificate_access')
  {
    label: "{!! trans('cruds.certificate.title_short') !!}",
    data: [0, 0, 0, 0, 0, {!! $certificates !!}, 0],
    value: {!! $certificates !!},
    url: "/admin/certificates"
  },
  @endcan
  @can('site_access')
  {
    label: "{!! trans('cruds.site.title') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $sites !!}],
    value: {!! $sites !!},
    url: "/admin/sites"
  },
  @endcan
  @can('building_access')
  {
    label: "{!! trans('cruds.building.title') !!}",
    data: [ 0, 0, 0, 0, 0, 0, {!! $buildings !!}],
    value: {!! $buildings !!},
    url: "/admin/buildings"
  },
  @endcan
  @can('bay_access')
  {
    label: "{!! trans('cruds.bay.title') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $bays !!}],
    value: {!! $bays !!},
    url: "/admin/bays"
  },
  @endcan
  @can('physical_server_access')
  {
    label: "{!! trans('cruds.physicalServer.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $physicalServers !!}],
    value: {!! $physicalServers !!},
    url: "/admin/physical-servers"
  },
  @endcan
  @can('workstation_access')
  {
    label: "{!! trans('cruds.workstation.title') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $workstations !!}],
    value: {!! $workstations !!},
    url: "/admin/workstations"
  },
  @endcan
  @can('peripheral_access')
  {
    label: "{!! trans('cruds.peripheral.title') !!}",
    data: [ 0, 0, 0, 0, 0, 0, {!! $peripherals !!}],
    value: {!! $peripherals !!},
    url: "/admin/peripherals"
  },
  @endcan
  @can('storage_device_access')
  {
    label: "{!! trans('cruds.storageDevice.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $storageDevices !!}],
    value: {!! $storageDevices !!},
    url: "/admin/storage-devices"
  },
  @endcan
  @can('physical_switch_access')
  {
    label: "{!! trans('cruds.physicalSwitch.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $physicalSwitchs !!}],
    value: {!! $physicalSwitchs !!},
    url: "/admin/physical-switches"
  },
  @endcan
  @can('physical_router_access')
  {
    label: "{!! trans('cruds.physicalRouter.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $physicalRouters !!}],
    value: {!! $physicalRouters !!},
    url: "/admin/physical-routers"
  },
  @endcan
  @can('phone_access')
  {
    label: "{!! trans('cruds.phone.title') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $phones !!}],
    value: {!! $phones !!},
    url: "/admin/phones"
  },
  @endcan
  @can('wifi_terminal_access')
  {
    label: "{!! trans('cruds.wifiTerminal.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $wifiTerminals !!}],
    value: {!! $wifiTerminals !!},
    url: "/admin/wifi-terminals"
  },
  @endcan
  @can('physical_security_device_access')
  {
    label: "{!! trans('cruds.physicalSecurityDevice.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $securityDevices !!}],
    value: {!! $securityDevices !!},
    url: "/admin/physical-security-devices"
  },
  @endcan
  @can('wan_access')
  {
    label: "{!! trans('cruds.wan.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $wans !!}],
    value: {!! $wans !!},
    url: "/admin/wans"
  },
  @endcan
  @can('man_access')
  {
    label: "{!! trans('cruds.man.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $mans !!}],
    value: {!! $mans !!},
    url: "/admin/mans"
  },
  @endcan
  @can('lan_access')
  {
    label: "{!! trans('cruds.lan.title_short') !!}",
    data: [0, 0, 0, 0, 0, 0, {!! $lans !!}],
    value: {!! $lans !!},
    url: "/admin/lans"
  },
  @endcan
  @can('vlan_access')
  {
    label: "{!! trans('cruds.vlan.title_short') !!}",
    data: [ 0, 0, 0, 0, 0, {!! $vlans !!}, 0],
    value: {!! $vlans !!},
    url: "/admin/vlans"
  }
  @endcan
]},
treemap: {}
}

// Remove some dataset
@cannot("physicalinfrastructure_access")
    window.chartData.barChart.labels.splice(6, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(6, 1);
@endcan

@cannot("infrastructure_access")
    window.chartData.barChart.labels.splice(5, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(5, 1);
@endcan

@cannot("administration_access")
    window.chartData.barChart.labels.splice(4, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(4, 1);
@endcan

@cannot("application_access")
    window.chartData.barChart.labels.splice(3, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(3, 1);
@endcan

@cannot("metier_access")
    window.chartData.barChart.labels.splice(2, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(2, 1);
@endcan

@cannot("ecosystem_access")
    window.chartData.barChart.labels.splice(1, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(1, 1);
@endcan

@cannot("gdpr_access")
    window.chartData.barChart.labels.splice(0, 1);
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data.splice(0, 1);
@endcan

var topTags = [
    @can("gdpr_access")
    {group:"{!! trans('cruds.menu.gdpr.title_short') !!}", tag:"{!! trans('cruds.dataProcessing.title') !!}", num:{!! $data_processing !!}, url: "/admin/data-processings" },
    {group:"{!! trans('cruds.menu.gdpr.title_short') !!}", tag:"{!! trans('cruds.securityControl.title_short') !!}", num:{!! $security_controls !!}, url: "/admin/security-controls" },
    @endcan
    @can("ecosystem_access")
    {group:"{!! trans('cruds.menu.ecosystem.title_short') !!}", tag:"{!! trans('cruds.entity.title') !!}", num:{!! $entities !!}, url: "/admin/entities" },
    {group:"{!! trans('cruds.menu.ecosystem.title_short') !!}", tag:"{!! trans('cruds.relation.title') !!}", num:{!! $relations !!}, url: "/admin/relations" },
    @endcan
    @can("metier_access")
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.macroProcessus.title') !!}", num: {!! $macroProcessuses !!}, url: "/admin/macro-processuses" },
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.process.title') !!}", num:{!! $processes !!}, url: "/admin/processes" },
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.activity.title') !!}", num:{!! $activities !!}, url: "/admin/activities" },
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.operation.title') !!}", num:{!! $operations !!}, url: "/admin/operations" },
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.task.title') !!}", num:{!! $tasks !!}, url: "/admin/tasks" },
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.actor.title') !!}", num:{!! $actors !!}, url: "/admin/actors" },
    {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.information.title') !!}", num:{!! $informations !!}, url: "/admin/information" },
    @endcan
    @can("application_access")
    {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.applicationBlock.title') !!}" , num:{!! $applicationBlocks !!}, url: "/admin/application-blocks" },
    {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.application.title') !!}", num:{!! $applications !!}, url: "/admin/applications" },
    {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.applicationService.title_short') !!}" , num:{!! $applicationServices !!}, url: "/admin/application-services" },
    {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.applicationModule.title_short') !!}" , num:{!! $applicationModules !!}, url: "/admin/application-modules" },
    {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.database.title') !!}" , num:{!! $databases !!}, url: "/admin/databases" },
    {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.flux.title') !!}" , num:{!! $fluxes !!}, url: "/admin/fluxes" },
    @endcan
    @can("administration_access")
    {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.zoneAdmin.title_short') !!}" , num:{!!$zones!!}, url: "/admin/zone-admins" },
    {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.annuaire.title_short') !!}" , num:{!!$annuaires!!}, url: "/admin/annuaires" },
    {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.forestAd.title_short') !!}" , num:{!!$forests!!}, url: "/admin/forest-ads" },
    {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.domaineAd.title_short') !!}" , num:{!!$domaines!!}, url: "/admin/domaine-ads" },
    @endcan
    @can("infrastructure_access")
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.network.title') !!}" , num:{!! $networks !!}, url: "/admin/networks" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.subnetwork.title_short') !!}" , num:{!! $subnetworks !!}, url: "/admin/subnetworks" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.gateway.title_short') !!}" , num:{!! $gateways !!}, url: "/admin/gateways" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.externalConnectedEntity.title_short') !!}" , num:{!! $externalConnectedEntities !!}, url: "/admin/external-connected-entities" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.networkSwitch.title_short') !!}" , num:{!! $switches !!}, url: "/admin/network-switches" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.router.title_short') !!}" , num:{!! $routers !!}, url: "/admin/routers" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.securityDevice.title_short') !!}" , num:{!! $securityDevices !!}, url: "/admin/security-devices" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.cluster.title_short') !!}" , num:{!! $clusters !!}, url: "/admin/clusters" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.logicalServer.title_short') !!}" , num:{!! $logicalServers !!}, url: "/admin/logical-servers" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.container.title') !!}" , num:{!! $containers !!}, url: "/admin/containers" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.vlan.title_short') !!}" , num:{!! $vlans !!}, url: "/admin/vlans" },
    {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.certificate.title_short') !!}" , num:{!! $certificates !!}, url: "/admin/certificates" },
    @endcan
    @can("physicalinfrastructure_access")
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.site.title') !!}" , num: {!! $sites !!}, url: "/admin/sites" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.building.title') !!}" , num:{!! $buildings !!}, url: "/admin/buildings" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.bay.title') !!}" , num:{!! $bays !!}, url: "/admin/bays" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.physicalServer.title_short') !!}", num:{!! $physicalServers !!}, url: "/admin/physical-servers" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.workstation.title') !!}" , num:{!! $workstations !!}, url: "/admin/workstations" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.phone.title') !!}" , num:{!! $phones !!}, url: "/admin/phones" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.peripheral.title') !!}" , num:{!! $peripherals !!}, url: "/admin/peripherals" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.storageDevice.title_short') !!}" , num:{!! $storageDevices !!}, url: "/admin/storage-devices" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.physicalSwitch.title_short') !!}" , num:{!! $physicalSwitchs !!}, url: "/admin/physical-switches" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.physicalRouter.title_short') !!}" , num:{!! $physicalRouters !!}, url: "/admin/physical-routers" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.wan.title_short') !!}" , num:{!! $wans !!}, url: "/admin/wans" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.man.title_short') !!}" , num:{!! $mans !!}, url: "/admin/mans" },
    {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.lan.title_short') !!}" , num:{!! $lans !!}, url: "/admin/lans" },
    @endcan
  ];

</script>

@vite(['resources/js/home.js'])
@parent
@endsection
