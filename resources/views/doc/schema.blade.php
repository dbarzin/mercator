@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Schéma
                </div>
                <div class="card-body">

                    <div id="graph" style="text-align: center;"></div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- //d3js.org/d3.v5.min.js -->
<script src="/js/d3.v5.min.js"></script>
<!-- https://unpkg.com/@hpcc-js/wasm@0.3.11/dist/index.min.js -->
<script src="/js/index.min.js"></script>
<!-- https://unpkg.com/d3-graphviz@3.0.5/build/d3-graphviz.js -->
<script src="/js/d3-graphviz.js"></script>

<script>
d3.select("#graph").graphviz()
    .addImage("/images/entity.png", "64px", "64px")
    .addImage("/images/relation.png", "64px", "64px")

    .addImage("/images/macroprocess.png", "64px", "64px")
    .addImage("/images/process.png", "64px", "64px")
    .addImage("/images/activity.png", "64px", "64px")
    .addImage("/images/operation.png", "64px", "64px")
    .addImage("/images/task.png", "64px", "64px")
    .addImage("/images/actor.png", "64px", "64px")
    .addImage("/images/information.png", "64px", "64px")

    .addImage("/images/applicationblock.png", "64px", "64px")
    .addImage("/images/application.png", "64px", "64px")
    .addImage("/images/applicationservice.png", "64px", "64px")
    .addImage("/images/applicationmodule.png", "64px", "64px")
    .addImage("/images/database.png", "64px", "64px")

    .addImage("/images/cloud.png", "64px", "64px")
    .addImage("/images/network.png", "64px", "64px")
    .addImage("/images/router.png", "64px", "64px")

    .addImage("/images/site.png", "64px", "64px")
    .addImage("/images/building.png", "64px", "64px")
    .addImage("/images/bay.png", "64px", "64px")
    .addImage("/images/server.png", "64px", "64px")
    .addImage("/images/workstation.png", "64px", "64px")
    .addImage("/images/storage.png", "64px", "64px")
    .addImage("/images/peripheral.png", "64px", "64px")
    .addImage("/images/phone.png", "64px", "64px")
    .addImage("/images/switch.png", "64px", "64px")
    .addImage("/images/router.png", "64px", "64px")
    .addImage("/images/wifi.png", "64px", "64px")

    .renderDot("digraph  {\
            <?php  $i=0; ?>\
                  ENTITY -> RELATION  [label=\"  0-n\"]\
                  ENTITY [label=\"Entité\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/entity.png\" href=\"/admin/entities\"]\
                  RELATION [label=\"Relation\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/relation.png\" href=\"/admin/relations\"]\
                  \
                  MPROCESS [label=\"Macro-Processus\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/macroprocess.png\" href=\"/admin/macro-processuses\"]\
                  PROCESS [label=\"Processus\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/process.png\" href=\"/admin/processes\"]\
                  ACTIVITY [label=\"Activité\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/activity.png\" href=\"/admin/activities\"]\
                  OPERATION [label=\"Opération\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/operation.png\" href=\"/admin/operations\"]\
                  TASK [label=\"Tâche\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/task.png\" href=\"/admin/tasks\"]\
                  ACTOR [label=\"Acteur\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/actor.png\" href=\"/admin/actors\"]\
                  INFORMATION [label=\"Information\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/information.png\" href=\"/admin/information\"]\
                  \
                  MPROCESS -> PROCESS  [label=\"  0-n\"]\
                  PROCESS -> ACTIVITY  [label=\"  0-n\"]\
                  PROCESS -> ENTITY  [label=\"  0-n\"]\
                  PROCESS -> APPLICATION  [label=\"  n-m\"]\
                  PROCESS -> INFORMATION  [label=\"  0-n\"]\
                  ACTIVITY -> OPERATION  [label=\"  0-n\"]\
                  OPERATION -> TASK  [label=\"  0-n\"]\
                  OPERATION -> ACTOR  [label=\"  0-n\"]\
                  \
                  APPLICBLOCK [label=\"Bloc applicatif\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/applicationblock.png\" href=\"/admin/application-blocks\"]\
                  APPLICATION [label=\"Application\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/application.png\" href=\"/admin/m-applications\"]\
                  APPLICSERV [label=\"Service Applicatif\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/applicationservice.png\" href=\"/admin/application-services\"]\
                  APPLICMODULE [label=\"Module Applicatif\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/applicationmodule.png\" href=\"/admin/application-modules\"]\
                  DATABASE [label=\"Base de données\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/database.png\" href=\"/admin/databases\"]\
                  \
                  APPLICBLOCK -> APPLICATION  [label=\"  0-n\"]\
                  APPLICATION-> ENTITY  [label=\"  0-1\"]\
                  APPLICATION-> APPLICSERV  [label=\"  0-n\"]\
                  APPLICATION -> DATABASE   [label=\"  0-n\"]\
                  APPLICSERV-> APPLICMODULE  [label=\"  0-n\"]\
                  DATABASE -> INFORMATION [label=\"  0-n\"]\
                  DATABASE -> ENTITY [label=\"  0-n\"]\
                  \
                  NETWORK [label=\"Réseau\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/cloud.png\" href=\"/admin/networks\"]\
                  SUBNETWORK [label=\"Sous-réseau\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/network.png\" href=\"/admin/subnetwords\"]\
                  LOGICALSERVER [label=\"Serveur Logique\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/server.png\" href=\"/admin/logical-servers\"]\
                  \
                  NETWORK -> SUBNETWORK [label=\"  0-n\"]\
                  SUBNETWORK -> LOGICALSERVER [label=\"  0-n\"]\
                  LOGICALSERVER -> PHYSICALSERVER [label=\"  n-m\"]\
                  APPLICATION -> LOGICALSERVER [label=\"  0-n\"]\
                  \
                  SITE [label=\"Site\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/site.png\" href=\"/admin/sites\"]\
                  BUILDING [label=\"Local\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/building.png\" href=\"/admin/buildings\"]\
                  BAY [label=\"Baie\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/bay.png\" href=\"/admin/bays\"]\
                  PHYSICALSERVER [label=\"Serveur physique\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/server.png\" href=\"/admin/physical-servers\"]\
                  WORKSTATION [label=\"Poste de travail\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/workstation.png\" href=\"/admin/workstations\"]\
                  PERIPHERAL [label=\"Périphérique\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/peripheral.png\" href=\"/admin/peripherals\"]\
                  WIFI [label=\"Borne Wifi\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/wifi.png\" href=\"/admin/wifi-terminals\"]\
                  PHONE [label=\"Téléphone\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/phone.png\" href=\"/admin/phones\"]\
                  SWITCH [label=\"Switch\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/switch.png\" href=\"/admin/physical-switches\"]\
                  STORAGE [label=\"Stockage\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/storage.png\" href=\"/admin/storage-devices\"]\
                  ROUTER [label=\"Router\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/router.png\" href=\"/admin/physical-routers\"]\
                  \
                  SITE -> BUILDING [label=\"  0-n\"]\
                  BUILDING -> BAY [label=\"  0-n\"]\
                  BUILDING -> WORKSTATION [label=\"  0-n\"]\
                  BUILDING -> PERIPHERAL [label=\"  0-n\"]\
                  BUILDING -> PHONE [label=\"  0-n\"]\
                  BAY -> PHYSICALSERVER [label=\"  0-n\"]\
                  BAY -> STORAGE [label=\"  0-n\"]\
                  BAY -> SWITCH [label=\"  0-n\"]\
                  BAY -> ROUTER [label=\"  0-n\"]\
                  BUILDING -> WIFI [label=\"  0-n\"]\
                  \
        }");

</script>
<!--
-->                  
@parent
@endsection
