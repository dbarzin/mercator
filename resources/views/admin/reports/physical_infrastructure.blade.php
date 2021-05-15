@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Vue de l'infrastructure physique
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

        <div class="col-sm-4">
            <form action="/admin/report/physical_infrastructure">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>
                                Site :
                                <select name="site" onchange="this.form.building.value=-1;this.form.submit()">
                                    <option value="-1">-- All sites --</option>
                                    @foreach ($all_sites as $site)
                                        <option value="{{$site->id}}" {{ Session::get('site')==$site->id ? "selected" : "" }}>{{ $site->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                Building :
                                <select name="building" onchange="this.form.submit()">
                                    <option value="-1">-- All buildings --</option>
                                    @if ($all_buildings!=null)
                                        @foreach ($all_buildings as $building)
                                            <option value="{{$building->id}}" {{ Session::get('building')==$building->id ? "selected" : "" }}>{{ $building->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
                <div id="graph"></div>
                </div>
            </div>

            @if ($sites->count()>0)
            <div class="card">
                <div class="card-header">
                    Sites
                </div>
                <div class="card-body">
                    <p>Emplacement géographique rassemblant un ensemble de personnes et/ou de ressources.</p>
                        @foreach($sites as $site)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SITE{{ $site->id }}">
                                <th colspan="2">
                                <a href="/admin/sites/{{ $site->id }}/edit">{{ $site->name }}</a><br>
                                </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">
                                            Description
                                        </th>                          
                                    <td>
                                        {!! $site->description !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Buildings
                                    </th>
                                    <td>
                                        @foreach($site->siteBuildings as $building) 
                                            <a href="#BUILDING{{$building->id}}">{{$building->name}}</a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>                                
                            </tbody>
                    </table>
                </div>
            </div>
            @endforeach

                </div>
            </div>
            @endif

            @if ($buildings->count()>0)
            <div class="card">
                <div class="card-header">
                    Bâtiments / Salles
                </div>
                <div class="card-body">
                    <p>Localisation des personnes ou ressources à l’intérieur d’un site.</p>                    
                        @foreach($buildings as $building)
                      <div class="row">
                        <div class="col-sm-6">
                            <table id="BUILDING{{ $building->id }}" class="table table-bordered table-striped table-hover">                                
                                <thead >
                                <th colspan="2">
                                    <a href="/admin/buildings/{{ $building->id }}/edit">{{ $building->name }}</a><br>
                                </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">Description</th>
                                        <td>{!! $building->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Baies</th>
                                    <td>                                
                                        @foreach($building->roomBays as $bay) 
                                            <a href="#BAY{{$bay->id}}">{{$bay->name}}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>                        
                                </tbody>
                        </table>                        
                    </div>
                </div>
                @endforeach                    
                </div>
            </div>
            @endif

            @if ($bays->count()>0)
            <div class="card">
                <div class="card-header">
                    Baies
                </div>
                <div class="card-body">
                    <p>Armoire technique rassemblant des équipements de réseau informatique ou de téléphonie.</p>
                        @foreach($bays as $bay)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="BAY{{ $bay->id }}">
                                    <th colspan="2">
                                    <a href="/admin/bays/{{ $bay->id }}/edit">{{ $bay->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $bay->description !!}</td>
                                </tr>
                                @if ($bay->bayPhysicalServers->count()>0)
                                <tr>
                                    <th>Serveurs physique</th>
                                    <td>
                                    @foreach($bay->bayPhysicalServers as $physicalServer) 
                                        <a href="#PSERVER{{$physicalServer->id}}">{{ $physicalServer->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach                                        
                                    </td>
                                </tr>
                                @endif
                                @if ($bay->bayPhysicalRouters->count()>0)
                                <tr>
                                    <th>Routeurs</th>
                                    <td>
                                    @foreach($bay->bayPhysicalRouters as $physicalRouter) 
                                        <a href="#ROUTER{{$physicalRouter->id}}">{{ $physicalRouter->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach                                        
                                    </td>
                                </tr>
                                @endif

                                @if ($bay->bayPhysicalSwitches->count()>0)
                                <tr>
                                    <th>Switch</th>
                                    <td>
                                    @foreach($bay->bayPhysicalSwitches as $bayPhysicalSwitch) 
                                        <a href="#SWITCH{{$bayPhysicalSwitch->id}}">{{ $bayPhysicalSwitch->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach                                        
                                    </td>
                                </tr>
                                @endif

                                @if ($bay->bayStorageDevices->count()>0)
                                <tr>
                                    <th>Dispositif de stockage</th>
                                    <td>
                                    @foreach($bay->bayStorageDevices as $bayStorageDevice) 
                                        <a href="#STORAGEDEVICE{{$bayStorageDevice->id}}">{{ $bayStorageDevice->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach                                        
                                    </td>
                                </tr>
                                @endif

                            </tbody>
                        </table>                                
                    </div>
                </div>
                 @endforeach
                </div>
            </div>
            @endif

            @if ($physicalServers->count()>0)
            <div class="card">
                <div class="card-header">
                    Serveurs physiques
                </div>
                <div class="card-body">
                    <p>Machine physique exécutant un ensemble de services informatiques.</p>
                        @foreach($physicalServers as $pserver)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="PSERVER{{ $pserver->id }}">
                                    <th colspan="2">
                                        <a href="/admin/physical-servers/{{ $pserver->id }}/edit">{{ $pserver->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $pserver->description !!}</td>
                                </tr>
                                <tr>
                                    <th>Configuration</th>
                                    <td>{!! $pserver->configuration !!}</td>
                                </tr>
                                <tr>
                                    <th>Site</th>
                                    <td>
                                        @if ($pserver->site!=null)
                                            <a href="#SITE{{$pserver->site->id}}">{{ $pserver->site->name }}</a> 
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td>                                        
                                        @if ($pserver->building!=null)
                                            <a href="#BUILDING{{ $pserver->building->id }}">{{ $pserver->building->name }}</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Baie</th>
                                    <td>
                                        @if ($pserver->bay!=null)
                                            <a href="#BAY{{ $pserver->bay->id }}">{{ $pserver->bay->name }}</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Responsable</th>
                                    <td>{{ $pserver->responsible }}</td>
                                </tr>
                                <tr>
                                    <th>Serveurs Logiques</th>
                                    <td>
                                        @foreach($physicalServer->serversLogicalServers as $logicalServer)
                                            <a href="/admin/report/logical_infrastructure#LOGICAL_SERVER{{ $logicalServer->id }}">{{ $logicalServer->name }}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
            @endif

            @if ((auth()->user()->granularity>=2)&&($workstations->count()>0))
            <div class="card">
                <div class="card-header">
                    Postes de travail
                </div>
                <div class="card-body">
                    <p>Machine physique permettant à un utilisateur d’accéder au système d’information</p>
                      @foreach($workstations as $workstation)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="WORKSTATION{{ $workstation->id }}">
                                    <th colspan="2">
                                        <a href="/admin/workstations/{{ $workstation->id }}/edit">{{ $workstation->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $workstation->description !!}</td>
                                </tr>
                                <tr>
                                    <th>Configuration</th>
                                    <td>{!! $workstation->configuration !!}</td>
                                </tr>
                                <tr>
                                    <th>Site</th>
                                    <td>
                                        @if ($workstation->site!=null)
                                            <a href="#SITE{{$workstation->site->id}}">{{ $workstation->site->name }}</a> 
                                        @endif
                                    </td>
                                </tr>
                                <tr>   
                                    <th>Building</th>
                                    <td>
                                        @if ($workstation->building!=null)
                                            <a href="#BUILDING{{ $workstation->building->id }}">{{ $workstation->building->name }}</a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
            @endif

            @if ((auth()->user()->granularity>=2)&&($storageDevices->count()>0)))
            <div class="card">
                <div class="card-header">
                    Infrastructure de stockage
                </div>
                <div class="card-body">
                    <p>Support physique ou réseau de stockage de données : serveur de stockage en réseau (NAS), réseau de stockage (SAN), disque dur…</p>
                      @foreach($storageDevices as $storageDevice)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="STORAGEDEVICE{{ $storageDevice->id }}">
                                    <th colspan="2">
                                        <a href="/admin/storage-devices/{{ $storageDevice->id }}/edit">{{ $storageDevice->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $storageDevice->description !!}</td>
                                </tr>
                                <tr>
                                    <th>Site</th>
                                    <td>
                                        @if ($storageDevice->site!=null)
                                            <a href="#SITE{{$storageDevice->site->id}}">{{ $storageDevice->site->name }}</a> 
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Building</th>
                                    <td>
                                        @if ($storageDevice->building!=null)
                                            <a href="#BUILDING{{ $storageDevice->building->id }}">{{ $storageDevice->building->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Baie</th>
                                    <td>
                                        @if ($storageDevice->bay!=null)
                                            <a href="#BAY{{ $storageDevice->bay->id }}">{{ $storageDevice->bay->name }}</a><br>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
            @endif

            @if ((auth()->user()->granularity>=2)&&($peripherals->count()>0))
            <div class="card">
                <div class="card-header">
                    Périphériques
                </div>
                <div class="card-body">
                    <p>Composant physique connecté à un poste de travail aﬁn d’ajouter de nouvelles fonctionnalités (ex. : clavier, souris, imprimante, scanner, etc.)</p>
                      @foreach($peripherals as $peripheral)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="PERIPHERAL{{ $peripheral->id }}">
                                    <th colspan="2">
                                        <a href="/admin/peripherals/{{ $peripheral->id }}/edit">{{ $peripheral->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $peripheral->description !!}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $peripheral->type }}</td>
                                </tr>
                                <tr>
                                    <td>Responsable</td>
                                    <td>{{ $peripheral->responsible }}</td>                                    
                                </tr>
                                <tr>
                                    <td>Site</td>
                                    <td>
                                        @if ($peripheral->site!=null)
                                            <a href="#SITE{{ $peripheral->site->id }}">{{ $peripheral->site->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Building</td>
                                    <td>
                                        @if ($peripheral->building!=null)
                                            <a href="#BUILDING{{ $peripheral->building->id }}">{{ $peripheral->building->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Baie</td>
                                    <td>
                                        @if ($peripheral->bay!=null)
                                            <a href="#BAY{{ $peripheral->bay->id }}">{{ $peripheral->bay->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            @endif

            @if ((auth()->user()->granularity>=2)&&($phones->count()>0))
            <div class="card">
                <div class="card-header">
                    Téléphones
                </div>
                <div class="card-body">
                    <p>Téléphone ﬁxe ou portable appartenant à l’organisation</p>
                      @foreach($phones as $phone)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="PHONE{{ $phone->id }}">
                                    <th colspan="2">
                                        <a href="/admin/phones/{{ $phone->id }}/edit">{{ $phone->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $phone->description !!}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $phone->type }}</td>
                                </tr>
                                <tr>
                                    <td>Site</td>
                                    <td>
                                        @if ($phone->site!=null)
                                            <a href="#SITE{{ $phone->site->id }}">{{ $phone->site->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Building</td>
                                    <td>
                                        @if ($phone->building!=null)
                                            <a href="#BUILDING{{ $phone->building->id }}">{{ $phone->building->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            @endif

            @if ($physicalSwitches->count()>0)
            <div class="card">
                <div class="card-header">
                    Commutateur (switch)
                </div>
                <div class="card-body">
                    <p>Composant gérant les connexions entre les différents serveurs au sein d’un réseau.</p>
                      @foreach($physicalSwitches as $switch)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SWITCH{{ $switch->id }}">
                                    <th colspan="2">
                                        <a href="/admin/physical-switches/{{ $switch->id }}/edit">{{ $switch->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $switch->description !!}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $switch->type }}</td>
                                </tr>
                                <tr>
                                    <td>Site</td>
                                    <td>
                                        @if ($switch->site!=null)
                                            <a href="#SITE{{ $switch->site->id }}">{{ $switch->site->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Building</td>
                                    <td>
                                        @if ($switch->building!=null)
                                            <a href="#BUILDING{{ $switch->building->id }}">{{ $switch->building->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Baie</td>
                                    <td>
                                        @if ($switch->bay!=null)
                                            <a href="#BAY{{ $switch->bay->id }}">{{ $switch->bay->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            @endif

            @if ($physicalRouters->count()>0)
            <div class="card">
                <div class="card-header">
                    Routeur Physique
                </div>
                <div class="card-body">
                    <p>Composant gérant les connexions entre différents réseaux.</p>
                      @foreach($physicalRouters as $router)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="ROUTER{{ $router->id }}">
                                    <th colspan="2">
                                        <a href="/admin/physical-routers/{{ $router->id }}/edit">{{ $router->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $router->description !!}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{ $router->type }}</td>
                                </tr>
                                <tr>
                                    <td>Site</td>
                                    <td>
                                        @if ($router->site!=null)
                                            <a href="#SITE{{ $router->site->id }}">{{ $router->site->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Building</td>
                                    <td>
                                        @if ($router->building!=null)
                                            <a href="#BUILDING{{ $router->building->id }}">{{ $router->building->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>Baie</td>
                                    <td>
                                        @if ($router->bay!=null)
                                            <a href="#BAY{{ $router->bay->id }}">{{ $router->bay->name }}</a><br>
                                        @endif                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            @endif

            <!-- Wifi Termonals -->

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
    .renderDot("digraph  {\
            <?php  $i=0; ?>\
            @foreach($sites as $site) \
                S{{ $site->id }} [label=\"{{ $site->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/site.png\" href=\"#SITE{{$site->id}}\"]\
            @endforEach\
            @foreach($buildings as $building) \
                B{{ $building->id }} [label=\"{{ $building->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/building.png\" href=\"#BUILDING{{$building->id}}\"]\
                S{{ $building->site_id }} -> B{{ $building->id }} \
                @foreach($building->roomBays as $bay) \
                    B{{ $building->id }} -> BAY{{ $bay->id }}\
                @endforeach\
            @endforEach\
            @foreach($bays as $bay) \
                BAY{{ $bay->id }} [label=\"{{ $bay->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/bay.png\" href=\"#BAY{{$bay->id}}\"]\
            @endforeach\
            @foreach($physicalServers as $pServer) \
                PSERVER{{ $pServer->id }} [label=\"{{ $pServer->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/server.png\" href=\"#PSERVER{{$pServer->id}}\"]\
                @if ($pServer->bay!=null)\
                     BAY{{ $pServer->bay->id }}\ -> PSERVER{{ $pServer->id }}\
                @elseif ($pServer->building!=null)\
                     B{{ $pServer->building->id }}\ -> PSERVER{{ $pServer->id }}\
                @elseif ($pServer->site!=null)\
                     S{{ $pServer->site->id }}\ -> PSERVER{{ $pServer->id }}\
                @endif\
            @endforeach\
            @foreach($workstations as $workstation) \
                W{{ $workstation->id }} [label=\"{{ $workstation->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/workstation.png\" href=\"#WORKSTATION{{$workstation->id}}\"]\
                @if ($workstation->building!=null)\
                     B{{ $workstation->building->id }}\ -> W{{ $workstation->id }}\
                @elseif ($workstation->site!=null)\
                     S{{ $workstation->building->id }}\ -> W{{ $workstation->id }}\
                @endif\
            @endforeach\
            @foreach($storageDevices as $storageDevice) \
                SD{{ $storageDevice->id }} [label=\"{{ $storageDevice->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/storage.png\" href=\"#STORAGEDEVICE{{$storageDevice->id}}\"]\
                @if ($storageDevice->bay!=null)\
                     BAY{{ $storageDevice->bay->id }}\ -> SD{{ $storageDevice->id }}\
                @elseif ($storageDevice->building!=null)\
                     B{{ $storageDevice->building->id }}\ -> SD{{ $storageDevice->id }}\
                @elseif ($storageDevice->site!=null)\
                     S{{ $storageDevice->site->id }}\ -> SD{{ $storageDevice->id }}\
                @endif\
            @endforeach\
            @foreach($peripherals as $peripheral) \
                PER{{ $peripheral->id }} [label=\"{{ $peripheral->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/peripheral.png\" href=\"#PERIPHERAL{{$peripheral->id}}\"]\
                @if ($peripheral->bay!=null)\
                     BAY{{ $peripheral->bay->id }}\ -> PER{{ $peripheral->id }}\
                @elseif ($peripheral->building!=null)\
                     B{{ $peripheral->building->id }}\ -> PER{{ $peripheral->id }}\
                @elseif ($peripheral->site!=null)\
                     S{{ $peripheral->site->id }}\ -> PER{{ $peripheral->id }}\
                @endif\
            @endforeach\
            @foreach($phones as $phone) \
                PHONE{{ $phone->id }} [label=\"{{ $phone->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/phone.png\" href=\"#PHONE{{$phone->id}}\"]\
                @if ($phone->building!=null)\
                     B{{ $phone->building->id }}\ -> PHONE{{ $phone->id }}\
                @elseif ($phone->site!=null)\
                     S{{ $phone->site->id }}\ -> PHONE{{ $phone->id }}\
                @endif\
            @endforeach\
            @foreach($physicalSwitches as $switch) \
                SWITCH{{ $switch->id }} [label=\"{{ $switch->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/switch.png\" href=\"#SWITCH{{$switch->id}}\"]\
                @if ($switch->bay!=null)\
                     BAY{{ $switch->bay->id }}\ -> SWITCH{{ $switch->id }}\
                @elseif ($switch->building!=null)\
                     B{{ $switch->building->id }}\ -> SWITCH{{ $switch->id }}\
                @elseif ($switch->site!=null)\
                     S{{ $switch->site->id }}\ -> SWITCH{{ $switch->id }}\
                @endif\
            @endforeach\
            @foreach($physicalRouters as $router) \
                ROUTER{{ $router->id }} [label=\"{{ $router->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/router.png\" href=\"#ROUTER{{$router->id}}\"]\
                @if ($router->bay!=null)\
                     BAY{{ $router->bay->id }}\ -> ROUTER{{ $router->id }}\
                @elseif ($router->building!=null)\
                     B{{ $router->building->id }}\ -> ROUTER{{ $router->id }}\
                @elseif ($router->site!=null)\
                     S{{ $router->site->id }}\ -> ROUTER{{ $router->id }}\
                @endif\
            @endforeach\
        }");

</script>
@parent
@endsection