@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
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
              <div class="card">
                <div class="card-header">
                  {!! trans("panel.repartition") !!}
                </div>
                <div class="card-body">
                    <div style="width: 1000px; height: 400px;">
                      <canvas id="bar_chart_div"></canvas>
                    </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  {!! trans("panel.treemap") !!}
                </div>
                <div class="card-body">
                    <div style="width: 1000px; height: 500px;">
                      <canvas id="treemap_chart_div"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script src="/js/Chart.bundle.js"></script>
<script src="/js/chartjs-gauge.min.js"></script>
<script src="/js/chartjs-plugin-colorschemes.js"></script>
<script src="/js/chartjs-chart-treemap.js"></script>
<script src="/js/chartjs-plugin-datalabels.js"></script>

<script type="text/javascript">

      var ctx1 = document.getElementById('gauge_chart1_div').getContext('2d');

      var cnf1 = {
        type: 'gauge',
        data: {
          datasets: [{
            value: {{ $maturity1 }},
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
              return  {{ $maturity1 }} + '%';
            },
            color: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(0, 0, 0, 1)',
            borderRadius: 5,
            padding: {
              top: 10,
              bottom: 10
            }
          },
          plugins: {
            datalabels: {
              formatter: function(value, context) {
                return null;
              }
            }
          }          
        }
      };      

      var ctx2 = document.getElementById('gauge_chart2_div').getContext('2d');

      var cnf2 = {
        type: 'gauge',
        data: {
          datasets: [{
            value: {{ $maturity2 }},
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
              return  {{ $maturity2 }} + '%';
            },
            color: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(0, 0, 0, 1)',
            borderRadius: 5,
            padding: {
              top: 10,
              bottom: 10
            }
          },
          plugins: {
            datalabels: {
              formatter: function(value, context) {
                return null;
              }
            }
          }
        }
      };

      var ctx3 = document.getElementById('gauge_chart3_div').getContext('2d');

      var cnf3 = {
        type: 'gauge',
        data: {
          datasets: [{
            value: {{ $maturity3 }},
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
              return  {{ $maturity3 }} + '%';
            },
            color: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(0, 0, 0, 1)',
            borderRadius: 5,
            padding: {
              top: 10,
              bottom: 10
            }
          },
          plugins: {
            datalabels: {
              formatter: function(value, context) {
                return null;
              }
            }
          }          
        }
      };    

    var ctx4 = document.getElementById('bar_chart_div').getContext('2d');

    var cnf4 = {
      type: 'bar',
      plugins: [ChartDataLabels],
      data: {
        mode: 'single',
        labels: [
            "{!! trans('cruds.menu.ecosystem.title_short') !!}", 
            "{!! trans('cruds.menu.metier.title_short') !!}", 
            "{!! trans('cruds.menu.application.title_short') !!}", 
            "{!! trans('cruds.menu.administration.title_short') !!}", 
            "{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", 
            "{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", 
            ],
        datasets: [{
          label: "{!! trans('cruds.entity.title') !!}",
          data: [{!! $entities !!}, 0, 0, 0, 0, 0],
          value: {!! $entities !!},
          url: "/admin/entities"
        }, {
          label: "{!! trans('cruds.relation.title') !!}",
          data: [{!! $relations !!}, 0, 0, 0, 0, 0],
          value: {!! $relations !!},
          url: "/admin/relations"
        }, {
          label: "{!! trans('cruds.macroProcessus.title') !!}",
          data: [0, {!! $macroProcessuses !!}, 0, 0, 0, 0],
          value: {!! $macroProcessuses !!},
          url: "/admin/macro-processuses"
        }, {
          label: "{!! trans('cruds.process.title') !!}",
          data: [0, {!! $processes !!}, 0, 0, 0, 0],
          value: {!! $processes !!},
          url: "/admin/processes"
        }, {
          label: "{!! trans('cruds.activity.title') !!}",
          data: [0, {!! $activities !!}, 0, 0, 0, 0],
          value: {!! $activities !!},
          url: "/admin/activities"
        }, {
          label: "{!! trans('cruds.operation.title') !!}",
          data: [0, {!! $operations !!}, 0, 0, 0, 0],
          value: {!! $operations !!},
          url: "/admin/operations"
        }, {
          label: "{!! trans('cruds.task.title') !!}",
          data: [0, {!! $tasks !!}, 0, 0, 0, 0],
          value: {!! $tasks !!},
          url: "admin/tasks"
        }, {
          label: "{!! trans('cruds.actor.title') !!}",
          data: [0, {!! $actors !!}, 0, 0, 0, 0],
          value: {!! $actors !!},
          url: "/admin/actors"
        }, {
          label: "{!! trans('cruds.information.title') !!}",
          data: [0, {!! $informations !!}, 0, 0, 0, 0],
          value: {!! $informations !!},
          url: "/admin/information"
        }, {
          label: "{!! trans('cruds.applicationBlock.title') !!}",
          data: [0, 0, {!! $applicationBlocks !!}, 0, 0, 0],
          value: {!! $applicationBlocks !!},
          url: "/admin/application-blocks"
        }, {
          label: "{!! trans('cruds.application.title') !!}",
          data: [0, 0, {!! $applications !!}, 0, 0, 0],
          value: {!! $applications !!},
          url: "/admin/applications"
        }, {
          label: "{!! trans('cruds.applicationService.title_short') !!}",
          data: [0, 0, {!! $applicationServices !!}, 0, 0, 0],
          value: {!! $applicationServices !!},
          url: "/admin/application-services"
        }, {
          label: "{!! trans('cruds.applicationModule.title_short') !!}",
          data: [0, 0, {!! $applicationModules !!}, 0, 0, 0],
          value: {!! $applicationModules !!},
          url: "/admin/application-modules"
        }, {
          label: "{!! trans('cruds.database.title') !!}",
          data: [0, 0, {!! $databases !!}, 0, 0, 0],
          value: {!! $databases !!},
          url: "/admin/databases"
        }, {
          label: "{!! trans('cruds.flux.title') !!}",
          data: [0, 0, {!! $fluxes !!}, 0, 0, 0],
          value: {!! $fluxes !!},
          url: "/admin/fluxes",
        }, {
          label: "{!! trans('cruds.zoneAdmin.title_short') !!}",
          data: [0, 0, 0, {!!$zones!!}, 0, 0],
          value: {!!$zones!!},
          url: "/admin/zone-admins"
        }, {
          label: "{!! trans('cruds.annuaire.title_short') !!}",
          data: [0, 0, 0, {!!$annuaires!!}, 0, 0],
          value: {!!$annuaires!!},
          url: "/admin/annuaires"
        }, {
          label: "{!! trans('cruds.forestAd.title_short') !!}",
          data: [0, 0, 0, {!!$forests!!}, 0, 0],
          value: {!!$forests!!},
          url: "/admin/forest-ads"
        }, {
          label: "{!! trans('cruds.domaineAd.title_short') !!}",
          data: [0, 0, 0, {!!$domaines!!}, 0, 0],
          value:  {!!$domaines!!},
          url: "/admin/domaine-ads"
        }, {
          label: "{!! trans('cruds.network.title_short') !!}",
          data: [0, 0, 0, 0, {!! $networks !!}, 0],
          value: {!! $networks !!},
          url: "/admin/networks"
        }, {
          label: "{!! trans('cruds.subnetwork.title_short') !!}",
          data: [0, 0, 0, 0, {!! $subnetworks !!}, 0],
          value: {!! $subnetworks !!},
          url: "/admin/subnetworks"
        }, {
          label: "{!! trans('cruds.gateway.title_short') !!}",
          data: [0, 0, 0, 0, {!! $gateways !!}, 0],
          value: {!! $gateways !!},
          url: "/admin/gateways"
        }, {
          label: "{!! trans('cruds.externalConnectedEntity.title_short') !!}",
          data: [0, 0, 0, 0, {!! $externalConnectedEntities !!}, 0],
          value: {!! $externalConnectedEntities !!},
          url: "/admin/external-connected-entities"
        }, {
          label: "{!! trans('cruds.networkSwitch.title_short') !!}",
          data: [0, 0, 0, 0, {!! $switches !!}, 0],
          value: {!! $switches !!},
          url: "/admin/network-switches"
        }, {
          label: "{!! trans('cruds.router.title_short') !!}",
          data: [0, 0, 0, 0, {!! $routers !!}, 0],
          value: {!! $routers !!},
          url: "/admin/routers"
        }, {
          label: "{!! trans('cruds.securityDevice.title_short') !!}",
          data: [0, 0, 0, 0, {!! $securityDevices !!}, 0],
          value: {!! $securityDevices !!},
          url: "/admin/security-devices"
        }, {
          label: "{!! trans('cruds.logicalServer.title_short') !!}",
          data: [0, 0, 0, 0, {!! $logicalServers !!}, 0],
          value: {!! $logicalServers !!},
          url: "/admin/logical-servers"
        }, {
          label: "{!! trans('cruds.certificate.title_short') !!}",
          data: [0, 0, 0, 0, {!! $certificates !!}, 0],
          value: {!! $certificates !!},
          url: "/admin/certificates"
        }, {
          label: "{!! trans('cruds.site.title') !!}",
          data: [0, 0, 0, 0, 0, {!! $sites !!}],
          value: {!! $sites !!},
          url: "/admin/sites"
        }, {
          label: "{!! trans('cruds.building.title') !!}",
          data: [0, 0, 0, 0, 0, {!! $buildings !!}],
          value: {!! $buildings !!},
          url: "/admin/buildings"
        }, {
          label: "{!! trans('cruds.bay.title') !!}",
          data: [0, 0, 0, 0, 0, {!! $bays !!}],
          value: {!! $bays !!},
          url: "/admin/bays"
        }, {
          label: "{!! trans('cruds.physicalServer.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $physicalServers !!}],
          value: {!! $physicalServers !!},
          url: "/admin/physical-servers"          
        }, {
          label: "{!! trans('cruds.workstation.title') !!}",
          data: [0, 0, 0, 0, 0, {!! $workstations !!}],
          value: {!! $workstations !!},
          url: "/admin/workstations"
        }, {
          label: "{!! trans('cruds.storageDevice.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $storageDevices !!}],
          value: {!! $storageDevices !!},
          url: "/admin/storage-devices"
        }, {
          label: "{!! trans('cruds.physicalSwitch.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $physicalSwitchs !!}],
          value: {!! $physicalSwitchs !!},
          url: "/admin/physical-switches"
        }, {
          label: "{!! trans('cruds.physicalRouter.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $physicalRouters !!}],
          value: {!! $physicalRouters !!},
          url: "/admin/physical-routers"
        }, {
          label: "{!! trans('cruds.wifiTerminal.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $wifiTerminals !!}],
          value: {!! $wifiTerminals !!},
          url: "/admin/wifi-terminals"
        }, {
          label: "{!! trans('cruds.physicalSecurityDevice.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $securityDevices !!}],
          value: {!! $securityDevices !!},
          url: "/admin/physical-security-devices"
        }, {
          label: "{!! trans('cruds.wan.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $wans !!}],
          value: {!! $wans !!},
          url: "/admin/wans"
        }, {
          label: "{!! trans('cruds.man.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $mans !!}],
          value: {!! $mans !!},
          url: "/admin/mans"
        }, {
          label: "{!! trans('cruds.lan.title_short') !!}",
          data: [0, 0, 0, 0, 0, {!! $lans !!}],
          value: {!! $lans !!},
          url: "/admin/lans"
        }, {
          label: "{!! trans('cruds.vlan.title_short') !!}",
          data: [0, 0, 0, 0, {!! $vlans !!}, 0], 
          value: {!! $vlans !!},
          url: "/admin/vlans"
        }
      ]},
      options: {
        hover: {
            onHover: function(e, el) {
              $("#bar_chart_div").css("cursor", el[0] ? "pointer" : "default");
              }
            },

        tooltips: {
          callbacks: {
            title: function(tooltipItem, data) {
               return null;
            },
            label: function(tooltipItem, data) {
               var dataset = data['datasets'];
                return dataset[tooltipItem['datasetIndex']]['label']+': '+dataset[tooltipItem['datasetIndex']]['value'];
            },
            // afterLabel: function(tooltipItem, data) {
            //    var dataset = data['datasets'];
            //    var percent = 
            //    return '(' + percent + '%)';
            //    return dataset[tooltipItem['datasetIndex']]['value'];              
            // }
          },
        },            
        scales: {
            yAxes: [{ barPercentage: 1.0 }],
          },
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 600,
        },
        plugins: {
          colorschemes: {
            scheme: 'tableau.Tableau20'            
          },
          datalabels: {
            color: 'white',
            font: {
               weight: 'bold'
            },
            formatter: function(value, context) {
              return value>1 ? context['dataset']['label']: '';
            }
          }    
        },
        legend : {
          display : false,
        },
        scales: {
          xAxes: [{
            stacked: true,
          }],
          yAxes: [{
            stacked: true,
          }]
        },
        onClick: function (event, array){
           var active = window.barchart.getElementAtEvent(event);
           if (active[0]==null) return;
           var elementIndex = active[0]._datasetIndex;
           window.location=cnf4["data"]["datasets"][elementIndex]["url"];
          },
        onComplete: function () {
          var ctx = this.chart.ctx;
          ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
          ctx.textAlign = 'left';
          ctx.textBaseline = 'bottom';
          }
        }
    };


    // Normalize data (%)
    for (let i = 0; i < 6; i++) {
      var sum=0;
      for (let j = 0; j < cnf4.data.datasets.length; j++) 
        sum += cnf4.data.datasets[j].data[i];
      if (sum>0)
      for (let j = 0; j < cnf4.data.datasets.length; j++) 
        cnf4.data.datasets[j].data[i] =
          Math.floor(cnf4.data.datasets[j].data[i]*1000/sum)/10;
        }


    </script>

    <script type="text/javascript">

  var topTags = [
     {group:"{!! trans('cruds.menu.ecosystem.title_short') !!}", tag:"{!! trans('cruds.entity.title') !!}", num:{!! $entities !!}, url: "/admin/entities" },
     {group:"{!! trans('cruds.menu.ecosystem.title_short') !!}", tag:"{!! trans('cruds.relation.title') !!}", num:{!! $relations !!}, url: "/admin/relations" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.macroProcessus.title') !!}", num: {!! $macroProcessuses !!}, url: "/admin/macro-processuses" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.process.title') !!}", num:{!! $processes !!}, url: "/admin/processes" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.activity.title') !!}", num:{!! $activities !!}, url: "/admin/activities" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.operation.title') !!}", num:{!! $operations !!}, url: "/admin/operations" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.task.title') !!}", num:{!! $tasks !!}, url: "/admin/tasks" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.actor.title') !!}", num:{!! $actors !!}, url: "/admin/actors" },
     {group:"{!! trans('cruds.menu.metier.title_short') !!}", tag:"{!! trans('cruds.information.title') !!}", num:{!! $informations !!}, url: "/admin/information" },
     {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.applicationBlock.title') !!}" , num:{!! $applicationBlocks !!}, url: "/admin/application-blocks" },
     {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.application.title') !!}", num:{!! $applications !!}, url: "/admin/applications" },
     {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.applicationService.title_short') !!}" , num:{!! $applicationServices !!}, url: "/admin/application-services" },
     {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.applicationModule.title_short') !!}" , num:{!! $applicationModules !!}, url: "/admin/application-modules" },
     {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.database.title') !!}" , num:{!! $databases !!}, url: "/admin/databases" },
     {group:"{!! trans('cruds.menu.application.title') !!}", tag:"{!! trans('cruds.flux.title') !!}" , num:{!! $fluxes !!}, url: "/admin/fluxes" },
     {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.zoneAdmin.title_short') !!}" , num:{!!$zones!!}, url: "/admin/zone-admins" },
     {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.annuaire.title_short') !!}" , num:{!!$annuaires!!}, url: "/admin/annuaires" },
     {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.forestAd.title_short') !!}" , num:{!!$forests!!}, url: "/admin/forest-ads" },
     {group:"{!! trans('cruds.menu.administration.title_short') !!}", tag:"{!! trans('cruds.domaineAd.title_short') !!}" , num:{!!$domaines!!}, url: "/admin/domaine-ads" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.network.title') !!}" , num:{!! $networks !!}, url: "/admin/networks" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.subnetwork.title_short') !!}" , num:{!! $subnetworks !!}, url: "/admin/subnetworks" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.gateway.title_short') !!}" , num:{!! $gateways !!}, url: "/admin/gateways" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.externalConnectedEntity.title_short') !!}" , num:{!! $externalConnectedEntities !!}, url: "/admin/external-connected-entities" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.networkSwitch.title_short') !!}" , num:{!! $switches !!}, url: "/admin/network-switches" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.router.title_short') !!}" , num:{!! $routers !!}, url: "/admin/routers" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.securityDevice.title_short') !!}" , num:{!! $securityDevices !!}, url: "/admin/security-devices" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.logicalServer.title_short') !!}" , num:{!! $logicalServers !!}, url: "/admin/logical-servers" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.vlan.title_short') !!}" , num:{!! $vlans !!}, url: "/admin/vlans" },
     {group:"{!! trans('cruds.menu.logical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.certificate.title_short') !!}" , num:{!! $certificates !!}, url: "/admin/certificates" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.site.title') !!}" , num: {!! $sites !!}, url: "/admin/sites" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.building.title') !!}" , num:{!! $buildings !!}, url: "/admin/buildings" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.bay.title') !!}" , num:{!! $bays !!}, url: "/admin/bays" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.physicalServer.title_short') !!}", num:{!! $physicalServers !!}, url: "/admin/physical-servers" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.workstation.title') !!}" , num:{!! $workstations !!}, url: "/admin/workstations" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.storageDevice.title_short') !!}" , num:{!! $storageDevices !!}, url: "/admin/storage-devices" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.physicalSwitch.title_short') !!}" , num:{!! $physicalSwitchs !!}, url: "/admin/physical-switches" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.physicalRouter.title_short') !!}" , num:{!! $physicalRouters !!}, url: "/admin/physical-routers" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.wan.title_short') !!}" , num:{!! $wans !!}, url: "/admin/wans" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.man.title_short') !!}" , num:{!! $mans !!}, url: "/admin/mans" },
     {group:"{!! trans('cruds.menu.physical_infrastructure.title_short') !!}", tag:"{!! trans('cruds.lan.title_short') !!}" , num:{!! $lans !!}, url: "/admin/lans" },
  ];

var ctx5 = document.getElementById("treemap_chart_div").getContext("2d");

var cnf5 = {
  type: "treemap",
  data: {
    datasets: [{
      //label: '',
      tree: topTags,
      key: "num",
      groups: ['group','tag'],
      spacing: 0.5,
      borderWidth: 1.5,
      // fontColor: "white",
      fontColor: function (ctx) {
        var item = ctx.dataset.data[ctx.dataIndex];
        switch (item.l) {
          case 0:return "#222";
          case 1:return "#FFF";
          default:return "#000";
        }
      },
      // fontStyle: "Bold",
      fontStyle: function (ctx) {
        var item = ctx.dataset.data[ctx.dataIndex];
        if (item.l==1)
          return "bold";
        else
          return null;
      },
      borderColor: "grey",
      // backgroundColor: function(ctx) {
      //   return Chart.colorschemes.tableau.Tableau20[ctx.dataIndex%20];
      //}
      backgroundColor: function (ctx) {
        var item = ctx.dataset.data[ctx.dataIndex];
        if (!item) return;
        switch (item.l) {
          case 0: return "#FFF";
          case 1: return Chart.colorschemes.tableau.Tableau20[ctx.dataIndex%20];
          default: return "#000";
          }
        },
    }]
  },
  options: {
    maintainAspectRatio: false,
    legend: { display: false },
    tooltips: { enabled: false },
    animation: { duration: 600 },
    
    onClick: function (event, active) {
      var chart = this;
      for (let i = 0; i < active.length; i++) {
        const item = active[i];
        var data = chart.data.datasets[item._datasetIndex].data[item._index];
        console.log(data);
        if (data._data.children.length === 1) {
          window.location=data._data.children[0].url;
        }
      }
    },

    hover: {
        onHover: function(e, el) {
          $("#treemap_chart_div").css("cursor", el[0] ? "pointer" : "default");
          }
        }    
    }
  };

    window.onload = function() {
      // unregister ChartDataLabels
      Chart.plugins.unregister(ChartDataLabels);
      // Gauges
      new Chart(ctx1, cnf1);
      new Chart(ctx2, cnf2);
      new Chart(ctx3, cnf3);      
      //
      window.barchart=new Chart(ctx4, cnf4);      
      window.treemap=new Chart(ctx5, cnf5);
    };


</script>
@parent
@endsection