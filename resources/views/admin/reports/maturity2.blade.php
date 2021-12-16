@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("panel.level_2.title_long") }}
                </div>
                <div class="card-body">
                    <div style="width: 350px; height: 180px;">
                      <canvas id="gauge_chart2_div"></canvas>
                    </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                    {{ trans("panel.level_2.description") }}
                </div>
                <div class="card-body">

  <div class="row">
    <div class="col-sm-6">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <th>{{ trans("cruds.menu.ecosystem.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>{{ number_format( ($entities+$relations)>0 
                        ? ($entities_lvl1+$relations_lvl1) * 100 / ($entities+$relations) : 0, 0) }} %</center></th>
            </thead>
            <tbody>
                <tr>
                    <td><a href="/admin/entities">{{ trans("cruds.entity.title") }}</a></td>
                    <td><center>{{ $entities }}</center></td>
                    <td><center>{{ $entities_lvl1 }}</center></td>
                    <td><center>{{ number_format($entities>0 ? $entities_lvl1 * 100 / $entities : 0, 0) }} % </center></td>
                </tr>
                <tr>
                    <td><a href="/admin/relations">{{ trans("cruds.relation.title") }}</a></td>
                    <td><center>{{ $relations }}</center></td>
                    <td><center>{{ $relations_lvl1 }}</center></td>
                    <td><center>{{ number_format($relations_lvl1>0 ? $relations_lvl1 *100 / $relations : 0, 0) }} % </center></td>
                </tr>
            </tbody>
            <thead>
                <th>{{ trans("cruds.menu.metier.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>{{ ($macroProcessuses+$processes+$operations+$actors+$informations)>0 ?
                        number_format(($macroProcessuses_lvl2+$processes_lvl2+$operations_lvl2+$actors_lvl2+$informations_lvl2)*100/
                            ($macroProcessuses+$processes+$operations+$actors+$informations),0) : 0 }} %</center></th>
            </thead>
            <tbody>
               <tr>
                    <td><a href="/admin/macro-processuses">{{ trans("cruds.macroProcessus.title") }}</a></td>
                    <td><center>{{ $macroProcessuses }}</center></td>
                    <td><center>{{ $macroProcessuses_lvl2 }}</center></td>
                    <td><center>{{ $macroProcessuses>0 ? number_format($macroProcessuses_lvl2*100/$macroProcessuses,0):0 }}%</center></td>
                </tr>

                <tr>
                    <td><a href="/admin/processes">{{ trans("cruds.process.title") }}</a></td>
                    <td><center>{{ $processes }}</center></td>
                    <td><center>{{ $processes_lvl2 }}</center></td>
                    <td><center>{{ $processes>0 ? number_format($processes_lvl2*100/$processes,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/operations">{{ trans("cruds.operation.title") }}</a></td>
                    <td><center>{{ $operations }}</center></td>
                    <td><center>{{ $operations_lvl2 }}</center></td>
                    <td><center>{{ $operations>0 ? number_format($operations_lvl2*100/$operations,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/actors">{{ trans("cruds.actor.title") }}</a></td>
                    <td><center>{{ $actors }}</center></td>
                    <td><center>{{ $actors_lvl2 }}</center></td>
                    <td><center>{{ $actors>0 ? number_format($actors_lvl2*100/$actors,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/information">{{ trans("cruds.information.title") }}</a></td>
                    <td><center>{{ $informations }}</center></td>
                    <td><center>{{ $informations_lvl2 }}</center></td>
                    <td><center>{{ $informations>0 ? number_format($informations_lvl2*100/$informations,0):0 }}%</center></td>
                </tr>
            </tbody>

            <thead>
                <th>{{ trans("cruds.menu.application.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>
                    {{ ($applicationBlocks+$applications+$applicationServices+$applicationModules+$databases+$fluxes)>0 ?
                       number_format(($applicationBlocks_lvl2+$applications_lvl2+$applicationServices_lvl2+$applicationModules_lvl2+$databases_lvl2+$fluxes_lvl1) *100 / ($applicationBlocks+$applications+$applicationServices+$applicationModules+$databases+$fluxes),0) : 0 }}%
                </center></th>
            </thead>

            <tbody>
                <tr>
                    <td><a href="/admin/application-blocks">{{ trans("cruds.applicationBlock.title") }}</a></td>
                    <td><center>{{ $applicationBlocks }}</center></td>
                    <td><center>{{ $applicationBlocks_lvl2 }}</center></td>
                    <td><center>{{ $applicationBlocks>0 ? number_format($applicationBlocks_lvl2*100/$applicationBlocks,0):0 }}%</center></td>
                </tr>            
                <tr>
                    <td><a href="/admin/applications">{{ trans("cruds.application.title") }}</a></td>
                    <td><center>{{ $applications }}</center></td>
                    <td><center>{{ $applications_lvl2 }}</center></td>
                    <td><center>{{ $applications>0 ? number_format($applications_lvl2*100/$applications,0):0 }}%</center></td>
                </tr>            
                <tr>
                    <td><a href="/admin/application-services">{{ trans("cruds.applicationService.title") }}</a></td>
                    <td><center>{{ $applicationServices }}</center></td>
                    <td><center>{{ $applicationServices_lvl2 }}</center></td>
                    <td><center>{{ $applicationServices>0 ? number_format($applicationServices_lvl2*100/$applicationServices,0):0 }}%</center></td>
                </tr>            
                <tr>
                    <td><a href="/admin/application-modules">{{ trans("cruds.applicationModule.title") }}</a></td>
                    <td><center>{{ $applicationModules }}</center></td>
                    <td><center>{{ $applicationModules_lvl2 }}</center></td>
                    <td><center>{{ $applicationModules>0 ? number_format($applicationModules_lvl2*100/$applicationModules,0):0 }}%</center></td>
                </tr>            
                <tr>
                    <td><a href="/admin/databases">{{ trans("cruds.database.title") }}</a></td>
                    <td><center>{{ $databases }}</center></td>
                    <td><center>{{ $databases_lvl2 }}</center></td>
                    <td><center>{{ $databases>0 ? number_format($databases_lvl2*100/$databases,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/fluxes">{{ trans("cruds.flux.title") }}</a></td>
                    <td><center>{{ $fluxes }}</center></td>
                    <td><center>{{ $fluxes_lvl1 }}</center></td>
                    <td><center>{{ $fluxes>0 ? number_format($fluxes_lvl1*100/$fluxes,0):0 }}%</center></td>
                </tr>
            </tbody>


            <thead>
                <th>{{ trans("cruds.menu.administration.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>
                    {{ ($zones+$annuaires+$forests+$domaines)>0 ?
                        number_format(($zones_lvl1+$annuaires_lvl1+$forests_lvl1+$domaines_lvl1)*100/($zones+$annuaires+$forests+$domaines),0) : 0 }}%
                </center></th>
            </thead>

            <tbody>
                <tr>
                    <td><a href="/admin/zone-admins">{{ trans("cruds.zoneAdmin.title") }}</a></td>
                    <td><center>{{ $zones }}</center></td>
                    <td><center>{{ $zones_lvl1 }}</center></td>
                    <td><center>{{ $zones>0 ? number_format($zones_lvl1*100/$zones,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/annuaires">{{ trans("cruds.annuaire.title") }}</a></td>
                    <td><center>{{ $annuaires }}</center></td>
                    <td><center>{{ $annuaires_lvl1 }}</center></td>
                    <td><center>{{ $annuaires>0 ? number_format($annuaires_lvl1*100/$annuaires,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/forest-ads">{{ trans("cruds.forestAd.title") }}</a></td>
                    <td><center>{{ $forests }}</center></td>
                    <td><center>{{ $forests_lvl1 }}</center></td>
                    <td><center>{{ $forests>0 ? number_format($forests_lvl1*100/$forests,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/domaine-ads">{{ trans("cruds.domaineAd.title") }}</a></td>
                    <td><center>{{ $domaines }}</center></td>
                    <td><center>{{ $domaines_lvl1 }}</center></td>
                    <td><center>{{ $domaines>0 ? number_format($domaines_lvl1*100/$domaines,0):0 }}%</center></td>
                </tr>
            </tbody>

            <thead>
                <th>{{ trans("cruds.menu.logical_infrastructure.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th>
                    <center>
                    {{
                        ($networks+$subnetworks+$gateways+$externalConnectedEntities+$switches+$routers+$securityDevices+$DHCPServers+$DNSServers+$logicalServers+$certificates)>0
                        ?
                        number_format(
                        ($networks_lvl1+$subnetworks_lvl1+$gateways_lvl1+$externalConnectedEntities_lvl2+$DHCPServers_lvl2+$DNSServers_lvl2+$switches_lvl1+$routers_lvl1+$securityDevices_lvl1+$logicalServers_lvl1+$certificates_lvl2) 
                        *100 /
                        ($networks+$subnetworks+$gateways+$externalConnectedEntities+$DHCPServers+$DNSServers+$switches+$routers+$securityDevices+$logicalServers+$certificates),0)
                        : 0
                    }}
                    %
                    </center>
                </th>
            </thead>

            <tbody>
                <tr>
                    <td><a href="/admin/networks">{{ trans("cruds.network.title") }}</a></td>
                    <td><center>{{ $networks }}</center></td>
                    <td><center>{{ $networks_lvl1 }}</center></td>
                    <td><center>{{ $networks>0 ? number_format($networks_lvl1*100/$networks,0):0 }}%</center></td>
                </tr>

                <tr>
                    <td><a href="/admin/subnetworks">{{ trans("cruds.subnetwork.title") }}</a></td>
                    <td><center>{{ $subnetworks }}</center></td>
                    <td><center>{{ $subnetworks_lvl1 }}</center></td>
                    <td><center>{{ $subnetworks>0 ? number_format($subnetworks_lvl1*100/$subnetworks,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/gateways">{{ trans("cruds.gateway.title") }}</a></td>
                    <td><center>{{ $gateways }}</center></td>
                    <td><center>{{ $gateways_lvl1 }}</center></td>
                    <td><center>{{ $gateways>0 ? number_format($gateways_lvl1*100/$gateways,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/external-connected-entities">{{ trans("cruds.externalConnectedEntity.title") }}</a></td>
                    <td><center>{{ $externalConnectedEntities }}</center></td>
                    <td><center>{{ $externalConnectedEntities_lvl2 }}</center></td>
                    <td><center>{{ $externalConnectedEntities>0 ? number_format($externalConnectedEntities_lvl2*100/$externalConnectedEntities,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/network-switches">{{ trans("cruds.networkSwitch.title") }}</a></td>
                    <td><center>{{ $switches }}</center></td>
                    <td><center>{{ $switches_lvl1 }}</center></td>
                    <td><center>{{ $switches>0 ? number_format($switches_lvl1*100/$switches,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/routers">{{ trans("cruds.router.title") }}</a></td>
                    <td><center>{{ $routers }}</center></td>
                    <td><center>{{ $routers_lvl1 }}</center></td>
                    <td><center>{{ $routers>0 ? number_format($routers_lvl1*100/$routers,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/security-devices">{{ trans("cruds.securityDevice.title") }}</a></td>
                    <td><center>{{ $securityDevices }}</center></td>
                    <td><center>{{ $securityDevices_lvl1 }}</center></td>
                    <td><center>{{ $securityDevices>0 ? number_format($securityDevices_lvl1*100/$securityDevices,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/dhcp-servers">{{ trans("cruds.dhcpServer.title") }}</a></td>
                    <td><center>{{ $DHCPServers }}</center></td>
                    <td><center>{{ $DHCPServers_lvl2 }}</center></td>
                    <td><center>{{ $DHCPServers>0 ? number_format($DHCPServers_lvl2*100/$DHCPServers,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/dnsservers">{{ trans("cruds.dnsserver.title") }}</a></td>
                    <td><center>{{ $DNSServers }}</center></td>
                    <td><center>{{ $DNSServers_lvl2 }}</center></td>
                    <td><center>{{ $DNSServers>0 ? number_format($DNSServers_lvl2*100/$DNSServers,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/logical-servers">{{ trans("cruds.logicalServer.title") }}</a></td>
                    <td><center>{{ $logicalServers }}</center></td>
                    <td><center>{{ $logicalServers_lvl1 }}</center></td>
                    <td><center>{{ $logicalServers>0 ? number_format($logicalServers_lvl1*100/$logicalServers,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/certificates">{{ trans("cruds.certificate.title") }}</a></td>
                    <td><center>{{ $certificates }}</center></td>
                    <td><center>{{ $certificates_lvl2 }}</center></td>
                    <td><center>{{ $certificates>0 ? number_format($certificates_lvl2*100/$certificates,0):0 }}%</center></td>
                </tr>
            </tbody>

            <thead>
                <th>{{ trans("cruds.menu.physical_infrastructure.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>
                        {{                             
                            ($sites + $buildings + $bays + $physicalServers + $workstations + $storageDevices +
                            $peripherals + $phones + $physicalRouters + $physicalSwitchs + 
                            $wifiTerminals + $physicalSecurityDevices +
                            $wans + $mans + $lans + $vlans)>0
                            ?
                            number_format (
                            ($sites_lvl1 + $buildings_lvl1 + $bays_lvl1 + $physicalServers_lvl1 + $workstations_lvl1 + $storageDevices_lvl1 +
                            $peripherals_lvl1 + $phones + $physicalRouters_lvl1 + $physicalSwitchs_lvl1 + 
                            $wifiTerminals_lvl1 + $physicalSecurityDevices_lvl1 +
                            $wans_lvl1 + $mans_lvl1 + $lans_lvl1 + $vlans_lvl1) * 100 /
                            ($sites + $buildings + $bays + $physicalServers + $workstations + $storageDevices +
                            $peripherals + $phones + $physicalRouters + $physicalSwitchs + 
                            $wifiTerminals + $physicalSecurityDevices +
                            $wans + $mans + $lans + $vlans) 
                            ,0):0
                        }} 

                    %
                    </center>
                </th>
            </thead>
            <tbody>
            <tr>
                <td><a href="/admin/sites">{{ trans("cruds.site.title") }}</a></td>
                <td><center>{{ $sites }}</center></td>
                <td><center>{{ $sites_lvl1 }}</center></td>
                <td><center>{{ $sites>0 ? number_format($sites_lvl1*100/$sites,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/buildings">{{ trans("cruds.building.title") }}</a></td>
                <td><center>{{ $buildings }}</center></td>
                <td><center>{{ $buildings_lvl1 }}</center></td>
                <td><center>{{ $buildings>0 ? number_format($buildings_lvl1*100/$buildings,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/bays">{{ trans("cruds.bay.title") }}</a></td>
                <td><center>{{ $bays }}</center></td>
                <td><center>{{ $bays_lvl1 }}</center></td>
                <td><center>{{ $bays>0 ? number_format($bays_lvl1*100/$bays,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/physical-servers">{{ trans("cruds.physicalServer.title") }}</a></td>
                <td><center>{{ $physicalServers }}</center></td>
                <td><center>{{ $physicalServers_lvl1 }}</center></td>
                <td><center>{{ $physicalServers>0 ? number_format($physicalServers_lvl1*100/$physicalServers,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/workstations">{{ trans("cruds.workstation.title") }}</a></td>
                <td><center>{{ $workstations }}</center></td>
                <td><center>{{ $workstations_lvl1 }}</center></td>
                <td><center>{{ $workstations>0 ? number_format($workstations_lvl1*100/$workstations,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/storage-devices">{{ trans("cruds.storageDevice.title") }}</a></td>
                <td><center>{{ $storageDevices }}</center></td>
                <td><center>{{ $storageDevices_lvl1 }}</center></td>
                <td><center>{{ $storageDevices>0 ? number_format($storageDevices_lvl1*100/$storageDevices,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/peripherals">{{ trans("cruds.peripheral.title") }}</a></td>
                <td><center>{{ $peripherals }}</center></td>
                <td><center>{{ $peripherals_lvl1 }}</center></td>
                <td><center>{{ $peripherals>0 ? number_format($peripherals_lvl1*100/$peripherals,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/phones">{{ trans("cruds.phone.title") }}</a></td>
                <td><center>{{ $phones }}</center></td>
                <td><center>{{ $phones_lvl1 }}</center></td>
                <td><center>{{ $phones>0 ? number_format($phones_lvl1*100/$phones,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/physical-routers">{{ trans("cruds.physicalRouter.title") }}</a></td>
                <td><center>{{ $physicalRouters }}</center></td>
                <td><center>{{ $physicalRouters_lvl1 }}</center></td>
                <td><center>{{ $physicalRouters>0 ? number_format($physicalRouters_lvl1*100/$physicalRouters,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/physical-switches">{{ trans("cruds.physicalSwitch.title") }}</a></td>
                <td><center>{{ $physicalSwitchs }}</center></td>
                <td><center>{{ $physicalSwitchs_lvl1 }}</center></td>
                <td><center>{{ $physicalSwitchs>0 ? number_format($physicalSwitchs_lvl1*100/$physicalSwitchs,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/wifi-terminals">{{ trans("cruds.wifiTerminal.title") }}</a></td>
                <td><center>{{ $wifiTerminals }}</center></td>
                <td><center>{{ $wifiTerminals_lvl1 }}</center></td>
                <td><center>{{ $wifiTerminals>0 ? number_format($wifiTerminals_lvl1*100/$wifiTerminals,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/physical-security-devices">{{ trans("cruds.physicalSecurityDevice.title") }}</a></td>
                <td><center>{{ $physicalSecurityDevices }}</center></td>
                <td><center>{{ $physicalSecurityDevices_lvl1 }}</center></td>
                <td><center>{{ $physicalSecurityDevices>0 ? number_format($physicalSecurityDevices_lvl1*100/$physicalSecurityDevices,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/wans">{{ trans("cruds.wan.title") }}</a></td>
                <td><center>{{ $wans }}</center></td>
                <td><center>{{ $wans_lvl1 }}</center></td>
                <td><center>{{ $wans>0 ? number_format($wans_lvl1*100/$wans,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/mans">{{ trans("cruds.man.title") }}</a></td>
                <td><center>{{ $mans }}</center></td>
                <td><center>{{ $mans_lvl1 }}</center></td>
                <td><center>{{ $mans>0 ? number_format($mans_lvl1*100/$mans,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/lans">{{ trans("cruds.lan.title") }}</a></td>
                <td><center>{{ $lans }}</center></td>
                <td><center>{{ $lans_lvl1 }}</center></td>
                <td><center>{{ $lans>0 ? number_format($lans_lvl1*100/$lans,0):0 }}%</center></td>
            </tr>
            <tr>
                <td><a href="/admin/vlans">{{ trans("cruds.vlan.title") }}</a></td>
                <td><center>{{ $vlans }}</center></td>
                <td><center>{{ $vlans_lvl1 }}</center></td>
                <td><center>{{ $vlans>0 ? number_format($vlans_lvl1*100/$vlans,0):0 }}%</center></td>
            </tr>
            <tbody>
        </table>
                </div>
              </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
  <script src="/js/Chart.bundle.js"></script>
  <script src="/js/chartjs-gauge.min.js"></script>

  <script type="text/javascript">

  var maturity2 = {{
            (
            $entities+$relations+
            $macroProcessuses+$processes+$operations+$actors+$informations+
            $applicationBlocks+$applications+$applicationServices+$applicationModules+$databases+$fluxes+
            $zones+$annuaires+$forests+$domaines+
            $networks+$subnetworks+$gateways+$externalConnectedEntities+$switches+$routers+$securityDevices+$DHCPServers+$DNSServers+$logicalServers+
            $sites + $buildings + $bays + $physicalServers + $physicalRouters + $physicalSwitchs +  $physicalSecurityDevices + $wans + $mans + $lans + $vlans 
            ) > 0 ?
            number_format ( 
            (
            $entities_lvl1+$relations_lvl2+
            $macroProcessuses_lvl2+$processes_lvl2+$operations_lvl2+$actors_lvl2+$informations_lvl2+
            $applicationBlocks_lvl2+$applications_lvl2+$applicationServices_lvl2+$applicationModules_lvl2+$databases_lvl2+$fluxes_lvl1+
            $zones_lvl1+$annuaires_lvl1+$forests_lvl1+$domaines_lvl1+
            $networks_lvl1+$subnetworks_lvl1+$gateways_lvl1+$externalConnectedEntities_lvl2+$switches_lvl1+$routers_lvl1+$securityDevices_lvl1+$DHCPServers_lvl2+$DNSServers_lvl2+$logicalServers_lvl1+
            $sites_lvl1 + $buildings_lvl1 + $bays_lvl1 + $physicalServers_lvl1 + $physicalRouters_lvl1 + $physicalSwitchs_lvl1 +  $physicalSecurityDevices_lvl1 + $wans_lvl1 + $mans_lvl1 + $lans_lvl1 + $vlans_lvl1 
            ) * 100 /
            (
            $entities+$relations+
            $macroProcessuses+$processes+$operations+$actors+$informations+
            $applicationBlocks+$applications+$applicationServices+$applicationModules+$databases+$fluxes+
            $zones+$annuaires+$forests+$domaines+
            $networks+$subnetworks+$gateways+$externalConnectedEntities+$switches+$routers+$securityDevices+$DHCPServers+$DNSServers+$logicalServers+
            $sites + $buildings + $bays + $physicalServers + $physicalRouters + $physicalSwitchs +  $physicalSecurityDevices + $wans + $mans + $lans + $vlans 
            )
            ,0) : 0 }}; 

    window.onload = function() {

      var ctx2 = document.getElementById('gauge_chart2_div').getContext('2d');

      var chart2 = new Chart(ctx2, {
        type: 'gauge',
        data: {
          datasets: [{
            value: maturity2,
            data: [40, 80,100],
            backgroundColor: ['#E15759', '#F28E2B', '#59A14F'],
          }]
        },
        options: {
          needle: {
            radiusPercentage: 2,
            widthPercentage: 3.2,
            lengthPercentage: 80,
            color: 'rgba(0, 0, 0, 1)'
          },
          valueLabel: {
            display: true,
            formatter: (value) => {
              return  maturity2 + '%';
            },
            color: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(0, 0, 0, 1)',
            borderRadius: 5,
            padding: {
              top: 10,
              bottom: 10
            }
          }
        }
      });
  }

    </script>
@endsection
