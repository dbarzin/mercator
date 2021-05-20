@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.network.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.networks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.network.fields.name') }}
                        </th>
                        <td>
                            {{ $network->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.description') }}
                        </th>
                        <td>
                            {!! $network->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.protocol_type') }}
                        </th>
                        <td>
                            {{ $network->protocol_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.responsible') }}
                        </th>
                        <td>
                            {{ $network->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.responsible_sec') }}
                        </th>
                        <td>
                            {{ $network->responsible_sec }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.security_need') }}
                        </th>
                        <td>
                            @if ($network->security_need==1) 
                                Public
                            @elseif ($network->security_need==2)
                                Internal
                            @elseif ($network->security_need==3)
                                Confidential
                            @elseif ($network->security_need==4)
                                Secret
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.subnetworks') }}
                        </th>
                        <td>
                            @foreach($network->subnetworks as $key => $subnetworks)
                                <span class="label label-info">{{ $subnetworks->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.networks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#connected_networks_external_connected_entities" role="tab" data-toggle="tab">
                {{ trans('cruds.externalConnectedEntity.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="connected_networks_external_connected_entities">
            @includeIf('admin.networks.relationships.connectedNetworksExternalConnectedEntities', ['externalConnectedEntities' => $network->connectedNetworksExternalConnectedEntities])
        </div>
    </div>
</div>

@endsection