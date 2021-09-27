@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Vue de l'infrastructure logique
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-sm-4">
                        <form action="/admin/report/logical_infrastructure">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>
                                        Network :
                                        <select name="network" onchange="this.form.subnetwork.value=-1;this.form.submit()">
                                            <option value="-1">-- All networks --</option>
                                            @foreach($all_networks as $id => $name)
                                                <option value="{{$id}}" {{ Session::get('network')==$id ? "selected" : "" }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        Subnetwork :
                                        <select name="subnetwork" onchange="this.form.submit()">
                                            <option value="-1">-- All subnetworks --</option>
                                            @if ($all_subnetworks!=null)
                                                @foreach($all_subnetworks as $id => $name)
                                                    <option value="{{$id}}" {{ Session::get('subnetwork')==$id ? "selected" : "" }}>{{ $name }}</option>
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

            @if ($networks->count()>0)
            <div class="card">
                <div class="card-header">
                    Réseaux
                </div>

                <div class="card-body">
                    <p>Ensemble d’équipements reliés logiquement entre eux et qui échangent des informations.</p>

                      @foreach($networks as $network)
                      <div class="row">
                        <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="NETWORK{{ $network->id }}">
                                    <th colspan="2">
                                    <a href="/admin/networks/{{ $network->id }}/edit">{{ $network->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">Description</th>
                                        <td>{!! $network->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Type de protocol</th>
                                        <td>{{ $network->protocol_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.network.fields.security_need') }}</th>
                                        <td>
                                            {{ trans('global.confidentiality') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$network->security_need_c] ?? "" }}
                                            <br>
                                            {{ trans('global.integrity') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$network->security_need_i] ?? "" }}
                                            <br>
                                            {{ trans('global.availability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$network->security_need_a] ?? "" }}
                                            <br>
                                            {{ trans('global.tracability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$network->security_need_t] ?? "" }} 
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <th>{{ trans('cruds.network.fields.responsible') }}</th>
                                        <td>{{ $network->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.network.fields.responsible_sec') }}</th>
                                        <td>{{ $network->responsible_sec }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.network.fields.subnetworks') }}</th>
                                        <td>
                                            @foreach($network->subnetworks as $subnetwork) 
                                                <a href="#SUBNET{{$subnetwork->id}}">{{$subnetwork->name}}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.network.fields.externalConnectedEntities') }}</th>
                                        <td>
                                            @foreach($network->externalConnectedEntities as $entity)
                                                <a href="#EXTENTITY{{$entity->id}}">{{$entity->name}}</a>
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

            @if ($subnetworks->count()>0)
            <div class="card">
                <div class="card-header">
                    Sous-Réseaux
                </div>
                <div class="card-body">
                    <p>Subdivision logique d’un réseau de taille plus importante.</p>

                      @foreach($subnetworks as $subnetwork)
                      <div class="row">
                        <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SUBNET{{ $subnetwork->id }}">
                                    <th colspan="2">
                                    <a href="/admin/subnetworks/{{ $subnetwork->id }}/edit">{{ $subnetwork->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">Description</th>
                                        <td>{!! $subnetwork->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Adresse/Masque - Passerelle par défaut</th>
                                        <td>
                                            {{ $subnetwork->address }} 
                                            ( {{ $subnetwork->ipRange() }} )
                                            -
                                            {{ $subnetwork->default_gateway }} 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>VLAN</th>
                                        <td>
                                            @if ($subnetwork->vlan!=null)
                                                <a href="#VLAN{{ $subnetwork->vlan_id }}">{{ $subnetwork->vlan->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Zone</th>
                                        <td>
                                            {{ $subnetwork->zone }} 
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <th>Passerelle</th>
                                        <td>
                                        @if ($subnetwork->gateway!=null) 
                                            <a href="#GATEWAY{{$subnetwork->gateway->id}}">{{ $subnetwork->gateway->name }}</a>
                                        @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Méthode d’attribution des IP</th>
                                        <td>{{ $subnetwork->ip_allocation_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Responsable d’exploitation</th>
                                        <td>{{ $subnetwork->responsible_exp }}</td>
                                    </tr>
                                    <tr>
                                        <th>DMZ</th>
                                        <td>{{ $subnetwork->dmz }}</td>
                                    </tr>
                                    <tr>
                                        <th>Accès WiFi</th>
                                        <td>{{ $subnetwork->wifi }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach                    
                </div>
            </div>
            @endif

            @if ($gateways->count()>0)
            <div class="card">
                <div class="card-header">
                    Passerelle d’entrée depuis l’extérieur
                </div>
                <div class="card-body">
                    <p>Composants permettant de relier un réseau local avec l’extérieur.</p>
                        @foreach($gateways as $gateway)
                          <div class="row">
                            <div class="col-sm-6">                        
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="GATEWAY{{ $gateway->id }}">
                                        <th colspan="2">
                                        <a href="/admin/gateways/{{ $gateway->id }}/edit">{{ $gateway->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tr>
                                        <th width="20%">Caractéristiques techniques</th>
                                        <td>{!! $gateway->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Type d'authentification</th>
                                        <td>{{ $gateway->authentification }}</td>
                                    </tr>
                                    <tr>
                                        <th>IP publique et privée</th>
                                        <td>{{ $gateway->ip }}</td>
                                    </tr>
                                    <tr>
                                        <th>Réseaux ratachés</th>
                                        <td>
                                            @foreach($gateway->gatewaySubnetworks as $subnetwork) 
                                                <a href="#SUBNET{{$subnetwork->id}}">{{$subnetwork->name}}</a>
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

            @if ($externalConnectedEntities->count()>0)
            <div class="card">
                <div class="card-header">
                    Entités extérieurs connectées
                </div>
                <div class="card-body">
                    <p>Entités externes connectées au réseau</p>
                        @foreach($externalConnectedEntities as $entity)
                          <div class="row">
                            <div class="col-sm-6">                        
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="EXTENTITY{{ $entity->id }}">
                                        <th colspan="2">
                                        <a href="/admin/external-connected-entities/{{ $entity->id }}/edit">{{ $entity->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tr>
                                        <th width="20%">Responsable SSI</th>
                                        <td>{{ $entity->responsible_sec }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contacts SI</th>
                                        <td>{{ $entity->contacts }}</td>
                                    </tr>
                                    <tr>
                                        <th>Réseaux connectés</th>
                                        <td>
                                            @foreach($entity->connected_networks as $subnetwork) 
                                                <a href="#SUBNET{{$subnetwork->id}}">{{$subnetwork->name}}</a>
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

            @if ($routers->count()>0)
            <div class="card">
                <div class="card-header">
                    Routeurs
                </div>
                <div class="card-body">
                    <p>Composant gérant les connexions entre différents réseaux.</p>
                        @foreach($routers as $router)
                          <div class="row">
                            <div class="col-sm-6">                        
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="ROUTER{{ $router->id }}">
                                        <th colspan="2">
                                        <a href="/admin/routers/{{ $router->id }}/edit">{{ $router->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">Description</th>
                                            <td>{!! $router->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th width="20%">Adresses IP</th>
                                            <td>{!! $router->ip_addresses !!}</td>
                                        </tr>
                                        <tr>
                                            <th width="20%">Rules</th>
                                            <td>{!! $router->rules !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endif

            @if ($logicalServers->count()>0)
            <div class="card">
                <div class="card-header">
                    Serveurs logiques
                </div>
                <div class="card-body">
                    <p>Découpage logique d’un serveur physique.</p>
                        @foreach($logicalServers as $logicalServer)
                          <div class="row">
                            <div class="col-sm-6">                        
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="LOGICAL_SERVER{{ $logicalServer->id }}">
                                        <th colspan="2">
                                        <a href="/admin/logical-servers/{{ $logicalServer->id }}/edit">{{ $logicalServer->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">Description</th>
                                            <td>{!! $logicalServer-> description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.operating_system') }}</th>
                                            <td>{{ $logicalServer->operating_system }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.address_ip') }}</th>
                                            <td>{{ $logicalServer->address_ip }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.cpu') }}</th>
                                            <td>{{ $logicalServer->cpu }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.memory') }}</th>
                                            <td>{{ $logicalServer->memory }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.disk') }}</th>
                                            <td>{{ $logicalServer->disk }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.environment') }}</th>
                                            <td>{{ $logicalServer->environment }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.net_services') }}</th>
                                            <td>{{ $logicalServer->net_services }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.logicalServer.fields.configuration') }}</th>
                                            <td>{!! $logicalServer->configuration !!}</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.logicalServer.fields.applications') }}
                                            </th>
                                            <td>
                                                @foreach($logicalServer->applications as $application)
                                                    <a href="/admin/report/applications#APPLICATION{{ $application->id}}">{{ $application->name }}</a>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                {{ trans('cruds.logicalServer.fields.servers') }}
                                            </th>
                                            <td>
                                                @foreach($logicalServer->servers as $server)
                                                    <a href="/admin/report/physical_infrastructure#PSERVER{{ $server->id}}">{{ $server->name }}</a>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                {{ trans('cruds.logicalServer.fields.certificates') }}
                                            </th>
                                            <td>
                                                @foreach($logicalServer->certificates as $certificate)
                                                    <a href="/admin/certificates/{{ $certificate->id}}/edit">{{ $certificate->name }}</a>
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


            @if ($certificates->count()>0)
            <div class="card">
                <div class="card-header">
                    Certificats
                </div>
                <div class="card-body">
                    <p>Un certificat électronique (aussi appelé certificat numérique ou certificat de clé publique) peut être vu comme une carte d'identité numérique. Il est utilisé principalement pour identifier et authentifier une personne physique ou morale, mais aussi pour chiffrer des échanges.</p>
                        @foreach($certificates as $certificate)
                          <div class="row">
                            <div class="col-sm-6">                        
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="CERT{{ $certificate->id }}">
                                        <th colspan="2">
                                        <a href="/admin/certificates/{{ $certificate->id }}/edit">{{ $certificate->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">{{ trans('cruds.certificate.fields.type') }}</th>
                                            <td>{!! $certificate->type !!}</td>
                                        </tr>
                                        <tr>
                                            <th width="20%">Description</th>
                                            <td>{!! $certificate->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.certificate.fields.start_validity') }}</th>
                                            <td>{{ $certificate->start_validity }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.certificate.fields.end_validity') }}</th>
                                            <td>{{ $certificate->end_validity }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ trans('cruds.certificate.fields.logical_servers') }}</th>
                                            <td>
                                                @if ($certificate->logical_servers!=null)
                                                    @foreach($certificate->logical_servers as $logical_server)
                                                    <a href="/admin/report/logical_infrastructure#LOGICAL_SERVER{{ $logical_server->id}}">{{ $logical_server->name }}</a>
                                                        @if (!$loop->last)
                                                        ,
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>{{ trans('cruds.certificate.fields.applications') }}</th>
                                            <td>
                                                @if ($certificate->applications!=null)
                                                    @foreach($certificate->applications as $application)
                                                    <a href="/admin/report/applications#APPLICATION{{ $application->id}}">{{ $application->name }}</a>
                                                        @if (!$loop->last)
                                                        ,
                                                        @endif
                                                    @endforeach
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


            @if ($vlans->count()>0)
            <div class="card">
                <div class="card-header">
                    Réseaux virtuels
                </div>
                <div class="card-body">
                    <p>Réseau local (LAN) virtuel permettant de regrouper logiquement des équipements en s’affranchissant des contraintes physiques.</p>
                      @foreach($vlans as $vlan)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="VLAN{{ $vlan->id }}">
                                    <th colspan="2">
                                        <a href="/admin/vlans/{{ $vlan->id }}/edit">{{ $vlan->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">Description</th>
                                    <td>{!! $vlan->description !!}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Sous-réseaux</th>
                                    <td>
                                        @foreach($vlan->subnetworks as $subnetwork) 
                                            <a href="/admin/report/logical_infrastructure#SUBNET{{$subnetwork->id}}">{{$subnetwork->name}}</a>
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
    .addImage("/images/cloud.png", "64px", "64px")
    .addImage("/images/network.png", "64px", "64px")
    .addImage("/images/gateway.png", "64px", "64px")
    .addImage("/images/entity.png", "64px", "64px")
    .addImage("/images/server.png", "64px", "64px")
    .addImage("/images/router.png", "64px", "64px")
    .addImage("/images/certificate.png", "64px", "64px")    
    .addImage("/images/vlan.png", "64px", "64px")    
    .renderDot("digraph  {\
            <?php  $i=0; ?>\
            @foreach($networks as $network) \
                NET{{ $network->id }} [label=\"{{ $network->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/cloud.png\" href=\"#NETWORK{{$network->id}}\"]\
            @endforeach\
            @foreach($gateways as $gateway) \
                GATEWAY{{ $gateway->id }} [label=\"{{ $gateway->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/gateway.png\" href=\"#GATEWAY{{$gateway->id}}\"]\
            @endforeach\
            @foreach($subnetworks as $subnetwork) \
                SUBNET{{ $subnetwork->id }} [label=\"{{ $subnetwork->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/network.png\" href=\"#SUBNET{{$subnetwork->id}}\"]\
                @if ($subnetwork->vlan_id!=null) \
                    SUBNET{{ $subnetwork->id }} -> VLAN{{ $subnetwork->vlan_id }}\
                @endif\
                @if ($subnetwork->network_id!=null) \
                    NET{{ $subnetwork->network_id }} -> SUBNET{{ $subnetwork->id }}\
                @endif\
                @if ($subnetwork->gateway_id!=null) \
                    SUBNET{{ $subnetwork->id }} -> GATEWAY{{ $subnetwork->gateway_id }}\
                @endif\
            @endforeach\
            @foreach($externalConnectedEntities as $entity) \
                E{{ $entity->id }} [label=\"{{ $entity->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/entity.png\" href=\"#EXTENTITY{{$entity->id}}\"]\
                @foreach($entity->connected_networks as $network)\
                    E{{ $entity->id }} -> NET{{ $network->id }} \
                @endforeach\
            @endforeach\
            @foreach($logicalServers as $logicalServer) \
                LOGICAL_SERVER{{ $logicalServer->id }} [label=\"{{ $logicalServer->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/server.png\" href=\"#LOGICAL_SERVER{{$logicalServer->id}}\"]\
                @if ($logicalServer->address_ip!=null)\
                    @foreach($subnetworks as $subnetwork) \
                        @foreach(explode(',',$logicalServer->address_ip) as $address) \
                            @if ($subnetwork->contains($address))\
                                SUBNET{{ $subnetwork->id }} -> LOGICAL_SERVER{{ $logicalServer->id }} \
                                @break\
                            @endif\
                        @endforeach\
                    @endforeach\
                @endif\
                @foreach($logicalServer->certificates as $certificate)\
                    LOGICAL_SERVER{{ $logicalServer->id }} -> CERT{{ $certificate->id }}\
                @endforeach\
            @endforeach\
            @foreach($certificates as $certificate) \
                @if ($certificate->logical_servers->count()>0)\
                    CERT{{ $certificate->id }} [label=\"{{ $certificate->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/certificate.png\" href=\"#CERT{{$certificate->id}}\"]\
                @endif\
            @endforeach\
            @foreach($routers as $router) \
                R{{ $router->id }} [label=\"{{ $router->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/router.png\" href=\"#ROUTER{{$router->id}}\"]\
                @foreach($subnetworks as $subnetwork) \
                    @if (($router->ip_addresses!=null)&&($subnetwork->address!=null))\
                        @foreach(explode(',',$router->ip_addresses) as $address) \
                            @if ($subnetwork->contains($address))\
                                SUBNET{{ $subnetwork->id }} -> R{{ $router->id }}\
                            @endif\
                        @endforeach\
                    @endif\
                @endforeach\
            @endforeach\
            @foreach($vlans as $vlan) \
                VLAN{{ $vlan->id }} [label=\"{{ $vlan->name }}\" shape=none labelloc=\"b\" width=1 height=1.1 image=\"/images/vlan.png\" href=\"#VLAN{{$vlan->id}}\"]\
            @endforeach\
        }");

</script>
@parent
@endsection
