@extends('layouts.admin')

@section('content')
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
                <div id="graph-container">
                    <div id="graph" class="graphviz"></div>
                </div>
            </div>
        </div>

        @can('network_access')
        @if ($networks->count()>0)
        <br>
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
                                        {{ trans('global.confidentiality_short') }} :
                                            @if ($network->security_need_c==0){{ trans('global.none') }}@endif
                                            @if ($network->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                            @if ($network->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                            @if ($network->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                            @if ($network->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                        &nbsp;
                                        {{ trans('global.integrity_short') }} :
                                            @if ($network->security_need_i==0){{ trans('global.none') }}@endif
                                            @if ($network->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                            @if ($network->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                            @if ($network->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                            @if ($network->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                        &nbsp;
                                        {{ trans('global.availability_short') }} :
                                            @if ($network->security_need_a==0){{ trans('global.none') }}@endif
                                            @if ($network->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                            @if ($network->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                            @if ($network->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                            @if ($network->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                        &nbsp;
                                        {{ trans('global.tracability_short') }} :
                                            @if ($network->security_need_t==0){{ trans('global.none') }}@endif
                                            @if ($network->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                            @if ($network->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                            @if ($network->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                            @if ($network->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                        @if (config('mercator-config.parameters.security_need_auth'))
                                        &nbsp;
                                        {{ trans('global.authenticity_short') }} :
                                            @if ($network->security_need_auth==0){{ trans('global.none') }}@endif
                                            @if ($network->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                            @if ($network->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                            @if ($network->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                            @if ($network->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                        @endif
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
        <br>
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
        <br>
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
                                <tbody>
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
        <br>
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
                                <tbody>
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
        <br>
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

        @can("network_switch_access")
        @if ($networkSwitches->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.networkSwitch.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.networkSwitch.description") }}</p>
                    @foreach($networkSwitches as $networkSwitch)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SW{{ $networkSwitch->id }}">
                                    <th colspan="2">
                                    <a href="/admin/network-switches/{{ $networkSwitch->id }}">{{ $networkSwitch->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">{{ trans("cruds.networkSwitch.fields.description") }}</th>
                                        <td>{!! $networkSwitch->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.networkSwitch.fields.ip") }}</th>
                                        <td>{!! $networkSwitch->ip !!}</td>
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
        <br>
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
                                <thead id="CLUSTER{{ $cluster->id }}">
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
        <br>
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
                                        <th width="20%">{{ trans("cruds.logicalServer.fields.type") }}</th>
                                        <td>{{ $logicalServer->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.logicalServer.fields.description") }}</th>
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
                                            @foreach($logicalServer->physicalServers as $server)
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

        @can('container_access')
        @if ($containers->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.container.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.container.description") }}</p>
                    @foreach($containers as $container)
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="CONT{{ $container->id }}">
                                    <th colspan="2">
                                        <a href="/admin/containers/{{ $container->id }}">{{ $container->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                      <th width="20%">{{ trans("cruds.container.fields.type") }}</th>
                                      <td>{{ $container->type }}</td>
                                    </tr>
                                    <tr>
                                      <th width="20%">{{ trans("cruds.container.fields.description") }}</th>
                                      <td>{!! $container->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.container.fields.applications') }}
                                        </th>
                                        <td>
                                            @foreach($container->applications as $application)
                                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                                    {{ $application->name }}
                                                </a>
                                                @if(!$loop->last)
                                                <br>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.container.fields.databases') }}
                                        </th>
                                        <td>
                                            @foreach($container->databases as $database)
                                                <a href="{{ route('admin.databases.show', $database->id) }}">
                                                    {{ $database->name }}
                                                </a>
                                                @if(!$loop->last)
                                                <br>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.container.fields.logical_servers') }}
                                        </th>
                                        <td>
                                            @foreach($container->logicalServers as $server)
                                                <a href="{{ route('admin.logical-servers.show', $server->id) }}">
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

        @can('workstation_access')
        @if ($workstations->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.workstation.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.workstation.description") }}</p>
                  @foreach($workstations as $workstation)
                  <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="WORKSTATION{{ $workstation->id }}">
                                <th colspan="2">
                                    <a href="/admin/workstations/{{ $workstation->id }}">{{ $workstation->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                            <tr>
                                <th width="20%">{{ trans("cruds.workstation.fields.type") }}</th>
                                <td>{{ $workstation->type }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.workstation.fields.description") }}</th>
                                <td>{!! $workstation->description !!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.address_ip") }}</th>
                                <td>{{ $workstation->address_ip }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.workstation.fields.site") }}</th>
                                <td>
                                    @if ($workstation->site!=null)
                                        <a href="{{ route('admin.sites.show', $workstation->site_id) }}">{{ $workstation->site->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.workstation.fields.building") }}</th>
                                <td>
                                    @if ($workstation->building!=null)
                                        <a href="{{ route('admin.buildings.show', $workstation->building_id) }}">{{ $workstation->building->name }}</a>
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
        @endcan

        @can('phone_access')
        @if ($phones->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.phone.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.phones.description") }}</p>
                  @foreach($phones as $phone)
                  <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="PHONE{{ $phone->id }}">
                                <th colspan="2">
                                    <a href="/admin/phones/{{ $phone->id }}">{{ $phone->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                            <tr>
                                <th width="20%">{{ trans("cruds.phone.fields.type") }}</th>
                                <td>{{ $phone->type }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.phone.fields.description") }}</th>
                                <td>{!! $phone->description !!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.phone.fields.address_ip") }}</th>
                                <td>{{ $phone->address_ip }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.phone.fields.site") }}</th>
                                <td>
                                    @if ($phone->site_id!==null)
                                        <a href="{{ route('admin.sites.show', $phone->site_id) }}">{{ $phone->site->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.phone.fields.building") }}</th>
                                <td>
                                    @if ($phone->building!=null)
                                        <a href="{{ route('admin.buildings.show', $phone->building_id) }}">{{ $phone->building->name }}</a>
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
        @endcan

        @can('physical_security_device_access')
        @if ($physicalSecurityDevices->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.physicalSecurityDevice.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.physicalSecurityDevice.description") }}</p>
                  @foreach($physicalSecurityDevices as $physicalSecurityDevice)
                  <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="SECURITY{{ $physicalSecurityDevice->id }}">
                                <th colspan="2">
                                    <a href="/admin/physical-security-devices/{{ $physicalSecurityDevice->id }}">{{ $physicalSecurityDevice->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                            <tr>
                                <th width="20%">{{ trans("cruds.physicalSecurityDevice.fields.type") }}</th>
                                <td>{{ $physicalSecurityDevice->type }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.physicalSecurityDevice.fields.description") }}</th>
                                <td>{!! $physicalSecurityDevice->description !!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.physicalSecurityDevice.fields.address_ip") }}</th>
                                <td>{{ $physicalSecurityDevice->address_ip }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.physicalSecurityDevice.fields.site") }}</th>
                                <td>
                                    @if ($physicalSecurityDevice->site_id!==null)
                                        <a href="{{ route('admin.sites.show', $physicalSecurityDevice->site_id) }}">{{ $physicalSecurityDevice->site->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.physicalSecurityDevice.fields.building") }}</th>
                                <td>
                                    @if ($physicalSecurityDevice->building!=null)
                                        <a href="{{ route('admin.buildings.show', $physicalSecurityDevice->building_id) }}">{{ $physicalSecurityDevice->building->name }}</a>
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
        @endcan

        @can('storage_device_access')
        @if ($storageDevices->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.storageDevice.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.storageDevice.description") }}</p>
                  @foreach($storageDevices as $storageDevice)
                  <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="STOR{{ $storageDevice->id }}">
                                <th colspan="2">
                                    <a href="/admin/storage-devices/{{ $storageDevice->id }}">{{ $storageDevice->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                            <tr>
                                <th width="20%">{{ trans("cruds.storageDevice.fields.type") }}</th>
                                <td>{{ $storageDevice->type }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.storageDevice.fields.description") }}</th>
                                <td>{!! $storageDevice->description !!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.storageDevice.fields.address_ip") }}</th>
                                <td>{{ $storageDevice->address_ip }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.storageDevice.fields.site") }}</th>
                                <td>
                                    @if ($storageDevice->site_id!==null)
                                        <a href="{{ route('admin.sites.show', $storageDevice->site_id) }}">{{ $storageDevice->site->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.storageDevice.fields.building") }}</th>
                                <td>
                                    @if ($storageDevice->building!=null)
                                        <a href="{{ route('admin.buildings.show', $storageDevice->building_id) }}">{{ $storageDevice->building->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.storageDevice.fields.bay") }}</th>
                                <td>
                                    @if ($storageDevice->bay!=null)
                                        <a href="{{ route('admin.bays.show', $storageDevice->bay_id) }}">{{ $storageDevice->bay->name }}</a>
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
        @endcan

        @can('wifi_terminal_access')
        @if ($wifiTerminals->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.wifiTerminal.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.workstation.description") }}</p>
                  @foreach($wifiTerminals as $wifiTerminal)
                  <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="WIFI{{ $wifiTerminal->id }}">
                                <th colspan="2">
                                    <a href="/admin/wifi-terminals/{{ $wifiTerminal->id }}">{{ $wifiTerminal->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                            <tr>
                                <th width="20%">{{ trans("cruds.wifiTerminal.fields.type") }}</th>
                                <td>{{ $wifiTerminal->type }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.wifiTerminal.fields.description") }}</th>
                                <td>{!! $wifiTerminal->description !!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.wifiTerminal.fields.address_ip") }}</th>
                                <td>{{ $wifiTerminal->address_ip }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.wifiTerminal.fields.site") }}</th>
                                <td>
                                    @if ($wifiTerminal->site!==null)
                                        <a href="{{ route('admin.sites.show', $wifiTerminal->site_id) }}">{! $wifiTerminal->site->name !}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.wifiTerminal.fields.building") }}</th>
                                <td>
                                    @if ($wifiTerminal->building!=null)
                                        <a href="{{ route('admin.buildings.show', $wifiTerminal->building_id) }}">{! $wifiTerminal->building->name !}</a>
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
        @endcan

        @can('peripheral_access')
        @if ($peripherals->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.peripheral.title") }}
            </div>
            <div class="card-body">
                <p>{{ trans("cruds.peripheral.description") }}</p>
                  @foreach($peripherals as $peripheral)
                  <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="PERIPHERAL{{ $peripheral->id }}">
                                <th colspan="2">
                                    <a href="/admin/peripherals/{{ $peripheral->id }}">{{ $peripheral->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                            <tr>
                                <th width="20%">{{ trans("cruds.peripheral.fields.description") }}</th>
                                <td>{!! $peripheral->description !!}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.type") }}</th>
                                <td>{{ $peripheral->type }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.responsible") }}</th>
                                <td>{{ $peripheral->responsible }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.address_ip") }}</th>
                                <td>{{ $peripheral->address_ip }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.site") }}</th>
                                <td>
                                    @if ($peripheral->site!=null)
                                        <a href="{{ route('admin.sites.show', $peripheral->site_id) }}">{{ $peripheral->site->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.building") }}</th>
                                <td>
                                    @if ($peripheral->building!=null)
                                        <a href="{{ route('admin.buildings.show', $peripheral->building_id) }}">{{ $peripheral->building->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans("cruds.peripheral.fields.bay") }}</th>
                                <td>
                                    @if ($peripheral->bay!=null)
                                        <a href="{{ route('admin.bays.show', $peripheral->bay_id) }}">{{ $peripheral->bay->name }}</a>
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
        @endcan

        @can('dhcp_server_access')
        @if ($dhcpServers->count()>0)
        <br>
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
        <br>
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
        <br>
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
        <br>
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
                                <th width="20%">{{ trans("cruds.vlan.fields.vlan_id") }}</th>
                                <td>{{ $vlan->vlan_id }}</td>
                            </tr>
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
@endsection

@section('scripts')
@vite(['resources/js/d3-viz.js'])
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
        LOGICAL_SERVER{{ $logicalServer->id }} [label="{{ $logicalServer->name }} {{ Session::get('show_ip') ? chr(13) . $logicalServer->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($logicalServer->address_ip!=null) ? '1.5' :'1.1' }} image="/images/lserver.png" href="#LOGICAL_SERVER{{$logicalServer->id}}"]
        @if ($logicalServer->address_ip!=null)
            @foreach($subnetworks as $subnetwork)
                @foreach(explode(',',$logicalServer->address_ip) as $address)
                    @if ($subnetwork->contains($address))
                        SUBNET{{ $subnetwork->id }} -> LOGICAL_SERVER{{ $logicalServer->id }}
                        @break
                    @endif
                @endforeach
            @endforeach
            @can('cluster_access')
            @if ($logicalServer->cluster_id!=null)
                LOGICAL_SERVER{{ $logicalServer->id }} -> CLUSTER{{ $logicalServer->cluster_id }}
            @endif
            @endcan
        @endif
        @foreach($logicalServer->certificates as $certificate)
            LOGICAL_SERVER{{ $logicalServer->id }} -> CERT{{ $certificate->id }}
        @endforeach
    @endforeach
    @endcan

    @can('dhcp_server_access')
    @foreach($dhcpServers as $dhcpServer)
        DHCP_SERVER{{ $dhcpServer->id }} [label="{{ $dhcpServer->name }} {{ Session::get('show_ip') ? chr(13) . $dhcpServer->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($dhcpServer->address_ip!=null) ? '1.5' :'1.1' }} image="/images/lserver.png" href="#DHCP_SERVER{{$dhcpServer->id}}"]
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
        DNS_SERVER{{ $dnsserver->id }} [label="{{ $dnsserver->name }} {{ Session::get('show_ip') ? chr(13) . $dnsserver->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($dnsserver->address_ip!=null) ? '1.5' :'1.1' }} image="/images/lserver.png" href="#DNS_SERVER{{$dnsserver->id}}"]
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
    @endcan

    @can('container_access')
    @foreach($containers as $container)
        @if ($container->logicalServers->count()>0)
            CONT{{ $container->id }} [label="{{ $container->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $container->icon_id === null ? '/images/container.png' : route('admin.documents.show', $container->icon_id) }}" href="#CONT{{$container->id}}"]
            @foreach($container->logicalServers as $logicalServer)
                LOGICAL_SERVER{{ $logicalServer->id }} -> CONT{{ $container->id }}
            @endforeach
        @endif
    @endforeach
    @endcan

    @can('workstation_access')
    @foreach($workstations as $workstation)
        WS{{ $workstation->id }} [label="{{ $workstation->name }} {{ Session::get('show_ip') ? chr(13) . $workstation->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($workstation->address_ip!=null) ? '1.5' :'1.1' }} image="/images/workstation.png" href="#WORKSTATION{{$workstation->id}}"]
        @foreach(explode(',',$workstation->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> WS{{ $workstation->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('wifi_terminal_access')
    @foreach($wifiTerminals as $wifiTerminal)
        WIFI{{ $wifiTerminal->id }} [label="{{ $wifiTerminal->name }} {{ Session::get('show_ip') ? chr(13) . $wifiTerminal->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($wifiTerminal->address_ip!=null) ? '1.5' :'1.1' }} image="/images/wifi.png" href="#WIFI{{$wifiTerminal->id}}"]
        @foreach(explode(',',$wifiTerminal->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> WIFI{{ $wifiTerminal->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('phone_access')
    @foreach($phones as $phone)
        PHONE{{ $phone->id }} [label="{{ $phone->name }} {{ Session::get('show_ip') ? chr(13) . $phone->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($phone->address_ip!=null) ? '1.5' :'1.1' }} image="/images/phone.png" href="#PHONE{{$phone->id}}"]
        @foreach(explode(',',$phone->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> PHONE{{ $phone->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('physical_security_device_access')
    @foreach($physicalSecurityDevices as $physicalSecurityDevice)
        SECURITY{{ $physicalSecurityDevice->id }} [label="{{ $physicalSecurityDevice->name }} {{ Session::get('show_ip') ? chr(13) . $physicalSecurityDevice->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($physicalSecurityDevice->address_ip!=null) ? '1.5' :'1.1' }} image="/images/securitydevice.png" href="#SECURITY{{$physicalSecurityDevice->id}}"]
        @foreach(explode(',',$physicalSecurityDevice->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> SECURITY{{ $physicalSecurityDevice->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('peripheral_access')
    @foreach($peripherals as $peripheral)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }} {{ Session::get('show_ip') ? chr(13) . $peripheral->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($peripheral->address_ip!=null) ? '1.5' :'1.1' }} image="/images/peripheral.png" href="#PERIPHERAL{{$peripheral->id}}"]
        @foreach(explode(',',$peripheral->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> PER{{ $peripheral->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('storage_device_access')
    @foreach($storageDevices as $storageDevice)
        STOR{{ $storageDevice->id }} [label="{{ $storageDevice->name }} {{ Session::get('show_ip') ? chr(13) . $storageDevice->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($storageDevice->address_ip!=null) ? '1.5' :'1.1' }} image="/images/storagedev.png" href="#STOR{{$storageDevice->id}}"]
        @foreach(explode(',',$storageDevice->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> STOR{{ $storageDevice->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('peripheral_access')
    @foreach($peripherals as $peripheral)
        PER{{ $peripheral->id }} [label="{{ $peripheral->name }} {{ Session::get('show_ip') ? chr(13) . $peripheral->address_ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($peripheral->address_ip!=null) ? '1.5' :'1.1' }} image="/images/peripheral.png" href="#PERIPHERAL{{$peripheral->id}}"]
        @foreach(explode(',',$peripheral->address_ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> PER{{ $peripheral->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('router_access')
    @foreach($routers as $router)
        R{{ $router->id }} [label="{{ $router->name }} {{ Session::get('show_ip') ? chr(13) . $router->ip_addresses : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($router->ip_addresses!=null) ? '1.5' :'1.1' }} image="/images/router.png" href="#ROUTER{{$router->id}}"]
        @foreach(explode(',',$router->ip_addresses) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> R{{ $router->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('network_switch_access')
    @foreach($networkSwitches as $networkSwitch)
        SW{{ $networkSwitch->id }} [label="{{ $networkSwitch->name }} {{ Session::get('show_ip') ? chr(13) . $networkSwitch->ip : '' }}" shape=none labelloc="b"  width=1 height={{ Session::get('show_ip') && ($networkSwitch->ip!=null) ? '1.5' :'1.1' }} image="/images/switch.png" href="#SW{{$networkSwitch->id}}"]
        @foreach(explode(',',$networkSwitch->ip) as $address)
            @foreach($subnetworks as $subnetwork)
                @if ($subnetwork->contains($address))
                    SUBNET{{ $subnetwork->id }} -> SW{{ $networkSwitch->id }}
                @endif
            @endforeach
        @endforeach
    @endforeach
    @endcan

    @can('vlan_access')
    @foreach($vlans as $vlan)
        VLAN{{ $vlan->id }} [label="{{ $vlan->name }}" shape=none labelloc="b" width=1 height=1.1 image="/images/vlan.png" href="#VLAN{{$vlan->id}}"]
    @endforeach
    @endcan
}`;


document.addEventListener('DOMContentLoaded', () => {
d3.select("#graph").graphviz()
    .addImage("/images/cloud.png", "64px", "64px")
    .addImage("/images/network.png", "64px", "64px")
    .addImage("/images/gateway.png", "64px", "64px")
    .addImage("/images/entity.png", "64px", "64px")
    .addImage("/images/lserver.png", "64px", "64px")
    .addImage("/images/router.png", "64px", "64px")
    .addImage("/images/switch.png", "64px", "64px")
    .addImage("/images/cluster.png", "64px", "64px")
    .addImage("/images/container.png", "64px", "64px")
    .addImage("/images/certificate.png", "64px", "64px")
    .addImage("/images/workstation.png", "64px", "64px")
    .addImage("/images/phone.png", "64px", "64px")
    .addImage("/images/securitydevice.png", "64px", "64px")
    .addImage("/images/storagedev.png", "64px", "64px")
    .addImage("/images/peripheral.png", "64px", "64px")
    .addImage("/images/wifi.png", "64px", "64px")
    .addImage("/images/vlan.png", "64px", "64px")
    @foreach($containers as $container)
       @if ($container->icon_id!==null)
       .addImage("{{ route('admin.documents.show', $container->icon_id) }}", "64px", "64px")
       @endif
    @endforeach
    .renderDot(dotSrc);
});
</script>
@parent
@endsection
