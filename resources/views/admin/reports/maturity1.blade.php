@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("panel.maturity_level_1") }}
                </div>
                <div class="card-body">
                    <div style="width: 350px; height: 180px;">
                      <canvas id="gauge_chart1_div"></canvas>
                    </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                    {{ trans("panel.maturity_levels") }}
                </div>
                <div class="card-body">

  <div class="row">
    <div class="col-sm-6">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <th>{{ trans("cruds.ecosystem.title_short") }}</th>
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
                <th>{{ trans("cruds.metier.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>{{ ($processes+$operations+$informations)>0 ?
                        number_format(($processes_lvl1+$operations_lvl1+$informations_lvl1)*100/
                            ($processes+$operations+$informations),0) : 0 }} %</center></th>
            </thead>
            <tbody>
                <tr>
                    <td><a href="/admin/processes">{{ trans("cruds.process.title") }}</a></td>
                    <td><center>{{ $processes }}</center></td>
                    <td><center>{{ $processes_lvl1 }}</center></td>
                    <td><center>{{ $processes>0 ? number_format($processes_lvl1*100/$processes,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/operations">{{ trans("cruds.operation.title") }}</a></td>
                    <td><center>{{ $operations }}</center></td>
                    <td><center>{{ $operations_lvl1 }}</center></td>
                    <td><center>{{ $operations>0 ? number_format($operations_lvl1*100/$operations,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/information">{{ trans("cruds.information.title") }}</a></td>
                    <td><center>{{ $informations }}</center></td>
                    <td><center>{{ $informations_lvl1 }}</center></td>
                    <td><center>{{ $informations>0 ? number_format($informations_lvl1*100/$informations,0):0 }}%</center></td>
                </tr>
            </tbody>

            <thead>
                <th>{{ trans("cruds.application_view.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>
                    {{ ($applications+$databases+$fluxes)>0 ?
                       number_format(($applications_lvl1+$databases_lvl1+$fluxes_lvl1) *100 / ($applications+$databases+$fluxes),0) : 0 }}%                    
                </center></th>
            </thead>

            <tbody>
                <tr>
                    <td><a href="/admin/applications">{{ trans("cruds.application.title") }}</a></td>
                    <td><center>{{ $applications }}</center></td>
                    <td><center>{{ $applications_lvl1 }}</center></td>
                    <td><center>{{ $applications>0 ? number_format($applications_lvl1*100/$applications,0):0 }}%</center></td>
                </tr>
            
                <tr>
                    <td><a href="/admin/databases">{{ trans("cruds.database.title") }}</a></td>
                    <td><center>{{ $databases }}</center></td>
                    <td><center>{{ $databases_lvl1 }}</center></td>
                    <td><center>{{ $databases>0 ? number_format($databases_lvl1*100/$databases,0):0 }}%</center></td>
                </tr>
                <tr>
                    <td><a href="/admin/fluxes">{{ trans("cruds.flux.title") }}</a></td>
                    <td><center>{{ $fluxes }}</center></td>
                    <td><center>{{ $fluxes_lvl1 }}</center></td>
                    <td><center>{{ $fluxes>0 ? number_format($fluxes_lvl1*100/$fluxes,0):0 }}%</center></td>
                </tr>
            </tbody>


            <thead>
                <th>{{ trans("cruds.administration.title_short") }}</th>
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
                <th>{{ trans("cruds.logical_infrastructure.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th>
                    <center>
                    {{
                        ($networks+$subnetworks+$gateways+$switches+$routers+$securityDevices+$logicalServers)>0
                        ?
                        number_format(
                        ($networks_lvl1+$subnetworks_lvl1+$gateways_lvl1+$switches_lvl1+$routers_lvl1+$securityDevices_lvl1+$logicalServers_lvl1) 
                        *100 /
                        ($networks+$subnetworks+$gateways+$switches+$routers+$securityDevices+$logicalServers),0)
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
                    <td><a href="/admin/subnetwords">{{ trans("cruds.subnetwork.title") }}</a></td>
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
                    <td><a href="/admin/logical-servers">{{ trans("cruds.logicalServer.title") }}</a></td>
                    <td><center>{{ $logicalServers }}</center></td>
                    <td><center>{{ $logicalServers_lvl1 }}</center></td>
                    <td><center>{{ $logicalServers>0 ? number_format($logicalServers_lvl1*100/$logicalServers,0):0 }}%</center></td>
                </tr>
            </tbody>


            <thead>
                <th>{{ trans("cruds.physical_infrastructure.title_short") }}</th>
                <th><center>#</center></th>
                <th><center>{{ trans("global.mature") }}</center></th>
                <th><center>
                        {{                             
                            ($sites + $buildings + $bays + $physicalServers +
                            $phones + $physicalRouters + $physicalSwitchs + 
                            $physicalSecurityDevices +
                            $wans + $mans + $lans + $vlans)>0
                            ?
                            number_format (
                            ($sites_lvl1 + $buildings_lvl1 + $bays_lvl1 + $physicalServers_lvl1 +
                             $phones + $physicalRouters_lvl1 + $physicalSwitchs_lvl1 + 
                             $physicalSecurityDevices_lvl1 +
                            $wans_lvl1 + $mans_lvl1 + $lans_lvl1 + $vlans_lvl1) * 100 /
                            ($sites + $buildings + $bays + $physicalServers +
                             $phones + $physicalRouters + $physicalSwitchs + 
                            $wifiTerminals + $physicalSecurityDevices +
                            $wans + $mans + $lans + $vlans) 
                            ,0):0
                        }} 

                    %
                    </center>
                </td>
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

  var maturity1 = {{
            (
            $entities+$relations+
            $processes+$operations+$informations+
            $applications+$databases+$fluxes+
            $zones+$annuaires+$forests+$domaines+
            $networks+$subnetworks+$gateways+$switches+$routers+$securityDevices+$logicalServers+
            $sites + $buildings + $bays + $physicalServers + $physicalRouters + $physicalSwitchs +  $physicalSecurityDevices + $wans + $mans + $lans + $vlans 
            ) > 0 ?
            number_format ( 
            (
            $entities_lvl1+$relations_lvl1+
            $processes_lvl1+$operations_lvl1+$informations_lvl1+
            $applications_lvl1+$databases_lvl1+$fluxes_lvl1+
            $zones_lvl1+$annuaires_lvl1+$forests_lvl1+$domaines_lvl1+
            $networks_lvl1+$subnetworks_lvl1+$gateways_lvl1+$switches_lvl1+$routers_lvl1+$securityDevices_lvl1+$logicalServers_lvl1+
            $sites_lvl1 + $buildings_lvl1 + $bays_lvl1 + $physicalServers_lvl1 + $physicalRouters_lvl1 + $physicalSwitchs_lvl1 +  $physicalSecurityDevices_lvl1 + $wans_lvl1 + $mans_lvl1 + $lans_lvl1 + $vlans_lvl1 
            ) * 100 /
            (
            $entities+$relations+
            $processes+$operations+$informations+
            $applications+$databases+$fluxes+
            $zones+$annuaires+$forests+$domaines+
            $networks+$subnetworks+$gateways+$switches+$routers+$securityDevices+$logicalServers+
            $sites + $buildings + $bays + $physicalServers + $physicalRouters + $physicalSwitchs +  $physicalSecurityDevices + $wans + $mans + $lans + $vlans 
            )
            ,0) : 0 }}; 

    window.onload = function() {

      var ctx1 = document.getElementById('gauge_chart1_div').getContext('2d');

      var chart1 = new Chart(ctx1, {
        type: 'gauge',
        data: {
          datasets: [{
            value: maturity1,
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
              return  maturity1 + '%';
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
