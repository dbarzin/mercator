@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.menu.logical_infrastructure.title") }}
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
                                        {{ trans("cruds.network.title_singular") }} :
                                        <select name="network" onchange="this.form.subnetwork.value='';this.form.submit()">
                                            <option value="">-- All networks --</option>
                                            @foreach($all_networks as $id => $name)
                                                <option value="{{$id}}" {{ Session::get('network')==$id ? "selected" : "" }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ trans("cruds.subnetwork.title_singular") }} :
                                        <select name="subnetwork" onchange="this.form.submit()">
                                            <option value="">-- All subnetworks --</option>
                                            @if ($all_subnetworks!=null)
                                                @foreach($all_subnetworks as $id => $name)
                                                    <option value="{{$id}}" {{ Session::get('subnetwork')==$id ? "selected" : "" }}>{{ $name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        <div class="col-sm-8">
                            <input name="show_ip" id='show_ip' type="checkbox" value="1" class="form-check-input" {{ Session::get('show_ip') ? 'checked' : '' }} onchange="this.form.subnetwork.value='';this.form.submit()">
                            <label for="is_external">Afficher les adresses IP</label>
                        </div>
                        </form>
                    </div>
                    <div id="graph"></div>
                </div>
            </div>

            @can('network_access')
            @if ($networks->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.network.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.network.description") }}</p>

                      @foreach($networks as $network)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="NETWORK{{ $network->id }}">
                                    <th colspan="2">
                                    <a href="/admin/networks/{{ $network->id }}">{{ $network->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">{{ trans("cruds.network.fields.description") }}</th>
                                        <td>{!! $network->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.network.fields.protocol_type") }}</th>
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
            @endcan

            @can('subnetwork_access')
            @if ($subnetworks->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.subnetwork.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.subnetwork.description") }}</p>

                      @foreach($subnetworks as $subnetwork)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SUBNET{{ $subnetwork->id }}">
                                    <th colspan="2">
                                    <a href="/admin/subnetworks/{{ $subnetwork->id }}">{{ $subnetwork->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">{{ trans("cruds.subnetwork.fields.description") }}</th>
                                        <td>{!! $subnetwork->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans("cruds.subnetwork.fields.address") }}
                                            -
                                            {{ trans("cruds.subnetwork.fields.default_gateway") }}
                                        </th>
                                        <td>
                                            {{ $subnetwork->address }}
                                            ( {{ $subnetwork->ipRange() }} )
                                            -
                                            {{ $subnetwork->default_gateway }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.vlan") }}</th>
                                        <td>
                                            @if ($subnetwork->vlan!=null)
                                                <a href="#VLAN{{ $subnetwork->vlan_id }}">{{ $subnetwork->vlan->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.zone") }}</th>
                                        <td>
                                            {{ $subnetwork->zone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.gateway") }}</th>
                                        <td>
                                        @if ($subnetwork->gateway!=null)
                                            <a href="#GATEWAY{{$subnetwork->gateway->id}}">{{ $subnetwork->gateway->name }}</a>
                                        @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.ip_allocation_type") }}</th>
                                        <td>{{ $subnetwork->ip_allocation_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.responsible_exp") }}</th>
                                        <td>{{ $subnetwork->responsible_exp }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.dmz") }}</th>
                                        <td>{{ $subnetwork->dmz }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.subnetwork.fields.wifi") }}</th>
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
            @endcan

            @can("gateway_access")
            @if ($gateways->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.gateway.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.gateway.description") }}</p>
                        @foreach($gateways as $gateway)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="GATEWAY{{ $gateway->id }}">
                                        <th colspan="2">
                                        <a href="/admin/gateways/{{ $gateway->id }}">{{ $gateway->name }}</a>
                                        </th>
                                    </thead>
                                    <tr>
                                        <th width="20%">{{ trans("cruds.gateway.fields.description") }}</th>
                                        <td>{!! $gateway->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.gateway.fields.authentification") }}</th>
                                        <td>{{ $gateway->authentification }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.gateway.fields.ip") }}</th>
                                        <td>{{ $gateway->ip }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.gateway.fields.subnetworks") }}</th>
                                        <td>
                                            @foreach($gateway->subnetworks as $subnetwork)
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
            @endcan

            @can('external_connected_entity_access')
            @if ($externalConnectedEntities->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.externalConnectedEntity.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.externalConnectedEntity.description") }}</p>
                        @foreach($externalConnectedEntities as $entity)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="EXTENTITY{{ $entity->id }}">
                                        <th colspan="2">
                                        <a href="/admin/external-connected-entities/{{ $entity->id }}">{{ $entity->name }}</a>
                                        </th>
                                    </thead>
                                    <tr>
                                        <th>{{ trans("cruds.externalConnectedEntity.fields.entity") }}</th>
                                        <td>
                                            @if($entity->entity!=null)
                                                <a href="#ENTITY{{$entity->entity->id}}">{{$entity->entity->name}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="20%">{{ trans("cruds.externalConnectedEntity.fields.type") }}</th>
                                        <td>{{ $entity->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.externalConnectedEntity.fields.contacts") }}</th>
                                        <td>{{ $entity->contacts }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.externalConnectedEntity.fields.network") }}</th>
                                        <td>
                                            @if($entity->network!=null)
                                                <a href="#NETWORK{{$entity->network->id}}">{{$entity->network->name}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.externalConnectedEntity.fields.src") }}</th>
                                        <td>{{ $entity->src }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.externalConnectedEntity.fields.dest") }}</th>
                                        <td>{{ $entity->dest }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endcan

            @can("router_access")
            @if ($routers->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.router.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.router.description") }}</p>
                        @foreach($routers as $router)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="ROUTER{{ $router->id }}">
                                        <th colspan="2">
                                        <a href="/admin/routers/{{ $router->id }}">{{ $router->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">{{ trans("cruds.router.fields.description") }}</th>
                                            <td>{!! $router->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans("cruds.router.fields.ip_addresses") }}</th>
                                            <td>{!! $router->ip_addresses !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans("cruds.router.fields.rules") }}</th>
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
            @endcan

            @can('cluster_access')
            @if ($clusters->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.cluster.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.cluster.description") }}</p>
                        @foreach($clusters as $cluster)
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="LOGICAL_SERVER{{ $cluster->id }}">
                                        <th colspan="2">
                                            <a href="/admin/clusters/{{ $cluster->id }}">{{ $cluster->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <th width="20%">{{ trans("cruds.cluster.fields.description") }}</th>
                                          <td>{!! $cluster->description !!}</td>
                                        </tr>
                                        <tr>
                                          <th width="20%">{{ trans("cruds.cluster.fields.type") }}</th>
                                          <td>{{ $cluster->type }}</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                {{ trans('cruds.cluster.fields.logical_servers') }}
                                            </th>
                                            <td>
                                                @foreach($cluster->logicalServers as $server)
                                                    <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                                                        {{ $server->name }}
                                                    </a>
                                                    @if(!$loop->last)
                                                    <br>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                {{ trans('cruds.cluster.fields.physical_servers') }}
                                            </th>
                                            <td>
                                                @foreach($cluster->physicalServers as $server)
                                                    <a href="{{ route('admin.physical-servers.show', $server->id) }}">
                                                        {{ $server->name }}
                                                    </a>
                                                    @if(!$loop->last)
                                                    <br>
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
            @endcan

            @can('logical_server_access')
            @if ($logicalServers->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.logicalServer.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.logicalServer.description") }}</p>
                        @foreach($logicalServers as $logicalServer)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="LOGICAL_SERVER{{ $logicalServer->id }}">
                                        <th colspan="2">
                                        <a href="/admin/logical-servers/{{ $logicalServer->id }}">{{ $logicalServer->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">{{ trans("cruds.logicalServer.fields.description") }}</th>
                                            <td>{!! $logicalServer->description !!}</td>
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
                                                {{ trans('cruds.logicalServer.fields.databases') }}
                                            </th>
                                            <td colspan="10">
                                                @foreach($logicalServer->databases as $database)
                                                    <a href="/admin/report/applications#DATABASE{{ $database->id}}">
                                                        {{ $database->name }}
                                                    </a>
                                                    @if(!$loop->last)
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
					@if ($logicalServer->certificates->count()>0)
                                        <tr>
                                            <th>
                                                {{ trans('cruds.logicalServer.fields.certificates') }}
                                            </th>
                                            <td>
                                                @foreach($logicalServer->certificates as $certificate)
                                                    <a href="/admin/report/logical_infrastructure#CERT{{ $certificate->id}}">{{ $certificate->name }}</a>
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
            @endcan

            @can('dhcp_server_access')
            @if ($dhcpServers->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.dhcpServer.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.dhcpServer.description") }}</p>
                        @foreach($dhcpServers as $dhcpServer)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="DHCP_SERVER{{ $dhcpServer->id }}">
                                        <th colspan="2">
                                        <a href="/admin/dhcp-servers/{{ $dhcpServer->id }}">{{ $dhcpServer->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">{{ trans("cruds.dhcpServer.fields.description") }}</th>
                                            <td>{!! $dhcpServer->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.dhcpServer.fields.address_ip') }}</th>
                                            <td>{{ $dhcpServer->address_ip }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endcan

            @can('dnsserver_access')
            @if ($dnsservers->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.dnsserver.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.dnsserver.description") }}</p>
                        @foreach($dnsservers as $dnsserver)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="DNS_SERVER{{ $dnsserver->id }}">
                                        <th colspan="2">
                                        <a href="/admin/dnsservers/{{ $dnsserver->id }}">{{ $dnsserver->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">{{ trans("cruds.dnsserver.fields.description") }}</th>
                                            <td>{!! $dnsserver->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.dnsserver.fields.address_ip') }}</th>
                                            <td>{{ $dnsserver->address_ip }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endcan

            @can('certificate_access')
            @if ($certificates->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.certificate.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.certificate.description") }}</p>
                        @foreach($certificates as $certificate)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="CERT{{ $certificate->id }}">
                                        <th colspan="2">
                                        <a href="/admin/certificates/{{ $certificate->id }}">{{ $certificate->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">{{ trans('cruds.certificate.fields.type') }}</th>
                                            <td>{!! $certificate->type !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.certificate.fields.description') }}</th>
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
                                        @if($certificate->logical_servers->count()>0)
                                        <tr>
                                            <th>{{ trans('cruds.certificate.fields.logical_servers') }}</th>
                                            <td>
                                                @foreach($certificate->logical_servers as $logical_server)
                                                <a href="/admin/report/logical_infrastructure#LOGICAL_SERVER{{ $logical_server->id}}">{{ $logical_server->name }}</a>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endif
                                        @if($certificate->applications->count()>0)
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
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endcan


            @can('vlan_access')
            @if ($vlans->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.vlan.title") }}
                </div>
                <div class="card-body">
                    <p>{{ trans("cruds.vlan.description") }}</p>
                      @foreach($vlans as $vlan)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="VLAN{{ $vlan->id }}">
                                    <th colspan="2">
                                        <a href="/admin/vlans/{{ $vlan->id }}">{{ $vlan->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                <tr>
                                    <th width="20%">{{ trans("cruds.vlan.fields.description") }}</th>
                                    <td>{!! $vlan->description !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans("cruds.vlan.fields.subnetworks") }}</th>
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
            @endcan
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
let dotSrc=`
digraph  {
    @can('network_access')
    @foreach($networks as $network)
        NET{{ $network->id }} [label="{{ $network->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/cloud.png" href="#NETWORK{{$network->id}}"]
    @endforeach
    @endcan
    @can('gateway_access')
    @foreach($gateways as $gateway)
        GATEWAY{{ $gateway->id }} [label="{{ $gateway->name }} {{ Session::get('show_ip') ? chr(13) . $gateway->ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip')&&($gateway->ip!=null) ? '1.5' :'1.1' }} image="/images/gateway.png" href="#GATEWAY{{$gateway->id}}"]
    @endforeach
    @endcan
    @can('subnetwork_access')
    @foreach($subnetworks as $subnetwork)
        SUBNET{{ $subnetwork->id }} [label="{{ $subnetwork->name }} {{ Session::get('show_ip') ? chr(13) . $subnetwork->address : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip')&&($subnetwork->address!=null) ? '1.5' :'1.1' }} image="/images/network.png" href="#SUBNET{{$subnetwork->id}}"]
        @if ($subnetwork->vlan_id!=null)
            SUBNET{{ $subnetwork->id }} -> VLAN{{ $subnetwork->vlan_id }}
        @endif
        @if ($subnetwork->network_id!=null)
            NET{{ $subnetwork->network_id }} -> SUBNET{{ $subnetwork->id }}
        @endif
        @if ($subnetwork->gateway_id!=null)
            SUBNET{{ $subnetwork->id }} -> GATEWAY{{ $subnetwork->gateway_id }}
        @endif
    @endforeach
    @endcan
    @can('external_connected_entity_access')
    @foreach($externalConnectedEntities as $entity)
        E{{ $entity->id }} [label="{{ $entity->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/entity.png" href="#EXTENTITY{{$entity->id}}"]
        @if($entity->network_id!=null)
            E{{ $entity->id }} -> NET{{ $entity->network_id }}
        @endif
    @endforeach
    @endcan
    @can('cluster_access')
    @php
        $usedClusterIds = array();
    @endphp
    @foreach($logicalServers as $logicalServer)
        @if (($logicalServer->cluster_id!==null) && (!in_array($logicalServer->cluster_id, $usedClusterIds)))
            @php
                array_push($usedClusterIds, $logicalServer->cluster_id);
            @endphp
        @endif
    @endforeach
    @foreach($clusters as $cluster)
        @if (in_array($cluster->id,$usedClusterIds))
            CLUSTER{{ $cluster->id}} [label="{{ $cluster->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/cluster.png" href="#CLUSTER{{$cluster->id}}"]
        @endif
    @endforeach
    @endcan
    @can('logical_server_access')
    @foreach($logicalServers as $logicalServer)
        LOGICAL_SERVER{{ $logicalServer->id }} [label="{{ $logicalServer->name }} {{ Session::get('show_ip') ? chr(13) . $logicalServer->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($logicalServer->address_ip!=null) ? '1.5' :'1.1' }} image="/images/server.png" href="#LOGICAL_SERVER{{$logicalServer->id}}"]
        @if ($logicalServer->address_ip!=null)
            @foreach($subnetworks as $subnetwork)
                @foreach(explode(',',$logicalServer->address_ip) as $address)
                    @if ($subnetwork->contains($address))
                        SUBNET{{ $subnetwork->id }} -> LOGICAL_SERVER{{ $logicalServer->id }}
                        @break
                    @endif
                @endforeach
            @endforeach
            @if ($logicalServer->cluster_id!=null)
                LOGICAL_SERVER{{ $logicalServer->id }} -> CLUSTER{{ $logicalServer->cluster_id }}
            @endif
        @endif
        @foreach($logicalServer->certificates as $certificate)
            LOGICAL_SERVER{{ $logicalServer->id }} -> CERT{{ $certificate->id }}
        @endforeach
    @endforeach
    @endcan
    @can('dhcp_server_access')
    @foreach($dhcpServers as $dhcpServer)
        DHCP_SERVER{{ $dhcpServer->id }} [label="{{ $dhcpServer->name }} {{ Session::get('show_ip') ? chr(13) . $dhcpServer->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($dhcpServer->address_ip!=null) ? '1.5' :'1.1' }} image="/images/server.png" href="#DHCP_SERVER{{$dhcpServer->id}}"]
        @if ($dhcpServer->address_ip!=null)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($dhcpServer->address_ip))
                    SUBNET{{ $subnetwork->id }} -> DHCP_SERVER{{ $dhcpServer->id }}
                    @break
                @endif
            @endforeach
        @endif
    @endforeach
    @endcan
    @can('dnsserver_access')
    @foreach($dnsservers as $dnsserver)
        DNS_SERVER{{ $dnsserver->id }} [label="{{ $dnsserver->name }} {{ Session::get('show_ip') ? chr(13) . $dnsserver->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($dnsserver->address_ip!=null) ? '1.5' :'1.1' }} image="/images/server.png" href="#DNS_SERVER{{$dnsserver->id}}"]
        @if ($dnsserver->address_ip!=null)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($dnsserver->address_ip))
                    SUBNET{{ $subnetwork->id }} -> DNS_SERVER{{ $dnsserver->id }}
                    @break
                @endif
            @endforeach
        @endif
    @endforeach
    @endcan
    @can('certificate_access')
    @foreach($certificates as $certificate)
        @if ($certificate->logical_servers->count()>0)
            CERT{{ $certificate->id }} [label="{{ $certificate->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/certificate.png" href="#CERT{{$certificate->id}}"]
        @endif
    @endforeach
    @foreach($routers as $router)
        R{{ $router->id }} [label="{{ $router->name }} {{ Session::get('show_ip') ? chr(13) . $router->ip_addresses : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($router->ip_addresses!=null) ? '1.5' :'1.1' }} image="/images/router.png" href="#ROUTER{{$router->id}}"]
        @foreach($subnetworks as $subnetwork)
            @if (($router->ip_addresses!=null)&&($subnetwork->address!=null))
                @foreach(explode(',',$router->ip_addresses) as $address)
                    @if ($subnetwork->contains($address))
                        SUBNET{{ $subnetwork->id }} -> R{{ $router->id }}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endforeach
    @endcan
    @can('vlan_access')
    @foreach($vlans as $vlan)
        VLAN{{ $vlan->id }} [label="{{ $vlan->name }}" shape=none labelloc="b" width=1 height=1.1 image="/images/vlan.png" href="#VLAN{{$vlan->id}}"]
    @endforeach
    @endcan
}`;

d3.select("#graph").graphviz()
    .addImage("/images/cloud.png", "64px", "64px")
    .addImage("/images/network.png", "64px", "64px")
    .addImage("/images/gateway.png", "64px", "64px")
    .addImage("/images/entity.png", "64px", "64px")
    .addImage("/images/server.png", "64px", "64px")
    .addImage("/images/router.png", "64px", "64px")
    .addImage("/images/cluster.png", "64px", "64px")
    .addImage("/images/certificate.png", "64px", "64px")
    .addImage("/images/vlan.png", "64px", "64px")
    .renderDot(dotSrc);

</script>
@parent
@endsection
