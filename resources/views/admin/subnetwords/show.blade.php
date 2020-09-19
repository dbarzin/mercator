@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.subnetwork.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subnetwords.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.id') }}
                        </th>
                        <td>
                            {{ $subnetword->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.name') }}
                        </th>
                        <td>
                            {{ $subnetword->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.description') }}
                        </th>
                        <td>
                            {!! $subnetword->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.address') }}
                        </th>
                        <td>
                            {{ $subnetword->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.ip_range') }}
                        </th>
                        <td>
                            {{ $subnetword->ip_range }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.ip_allocation_type') }}
                        </th>
                        <td>
                            {{ $subnetword->ip_allocation_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.responsible_exp') }}
                        </th>
                        <td>
                            {{ $subnetword->responsible_exp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.dmz') }}
                        </th>
                        <td>
                            {{ $subnetword->dmz }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.wifi') }}
                        </th>
                        <td>
                            {{ $subnetword->wifi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.connected_subnets') }}
                        </th>
                        <td>
                            {{ $subnetword->connected_subnets->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.gateway') }}
                        </th>
                        <td>
                            {{ $subnetword->gateway->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subnetwords.index') }}">
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
            <a class="nav-link" href="#connected_subnets_subnetwords" role="tab" data-toggle="tab">
                {{ trans('cruds.subnetwork.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#subnetworks_networks" role="tab" data-toggle="tab">
                {{ trans('cruds.network.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="connected_subnets_subnetwords">
            @includeIf('admin.subnetwords.relationships.connectedSubnetsSubnetwords', ['subnetwords' => $subnetword->connectedSubnetsSubnetwords])
        </div>
        <div class="tab-pane" role="tabpanel" id="subnetworks_networks">
            @includeIf('admin.subnetwords.relationships.subnetworksNetworks', ['networks' => $subnetword->subnetworksNetworks])
        </div>
    </div>
</div>

@endsection