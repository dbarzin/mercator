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
                    <div id="graph"></div>
                </div>
            </div>

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
                                <thead id="APPLICATIONBLOCK{{ $network->id }}">
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
                                        <th>Responsable d'exploitation</th>
                                        <td>{{ $network->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>Responsable SSI</th>
                                        <td>{{ $network->responsible_sec }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sous-réseaux ratachés</th>
                                        <td>
                                            @foreach($network->subnetworks as $subnetwork) 
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
                                    <a href="/admin/subnetwords/{{ $subnetwork->id }}/edit">{{ $subnetwork->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">Description</th>
                                        <td>{!! $subnetwork->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Adresse/Masque</th>
                                        <td>{{ $subnetwork->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Passerelle</th>
                                        <td> {{ $subnetwork->gateway }}</td>
                                    </tr>
                                    <tr>
                                        <th>Plage d’adresses IP</th>
                                        <td>{{ $subnetwork->ip_range }}</td>
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
                                        <th>DMZ ou non</th>
                                        <td>{{ $subnetwork->dmz }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sous-réseaux connecté</th>
                                        <td>
                                            @if ($subnetwork->connected_subnets!=null)
                                                <a href="#SUBNET{{$subnetwork->connected_subnets->id }}">{{$subnetwork->connected_subnets->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Possibilité d’accès sans ﬁl</th>
                                        <td>{{ $subnetwork->wifi }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach                    
                </div>
            </div>


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
                                                Applications
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

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
    .addImage("/images/cloud.png", "64px", "64px")
    .addImage("/images/network.png", "64px", "64px")
    .renderDot("digraph  {\
            <?php  $i=0; ?>\
            @foreach($networks as $network) \
                NET{{ $network->id }} [label=\"{{ $network->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/cloud.png\" href=\"#NETWORK{{$network->id}}\"]\
                @foreach($network->subnetworks as $subnetwork) \
                    NET{{ $network->id }} -> SUBNET{{ $subnetwork->id }}\
                @endforeach\
            @endforeach\
            @foreach($subnetworks as $subnetwork) \
                SUBNET{{ $subnetwork->id }} [label=\"{{ $subnetwork->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/network.png\" href=\"#SUBNETWORK{{$subnetwork->id}}\"]\
            @endforeach\
        }");

</script>
@parent
@endsection