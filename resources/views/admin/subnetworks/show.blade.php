@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.subnetwork.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subnetworks.index') }}">
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
                            {{ $subnetwork->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.name') }}
                        </th>
                        <td>
                            {{ $subnetwork->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.description') }}
                        </th>
                        <td>
                            {!! $subnetwork->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.address') }}
                        </th>
                        <td>
                            {{ $subnetwork->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.ip_range') }}
                        </th>
                        <td>
                            {{ $subnetwork->ip_range }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.ip_allocation_type') }}
                        </th>
                        <td>
                            {{ $subnetwork->ip_allocation_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.responsible_exp') }}
                        </th>
                        <td>
                            {{ $subnetwork->responsible_exp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.dmz') }}
                        </th>
                        <td>
                            {{ $subnetwork->dmz }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.wifi') }}
                        </th>
                        <td>
                            {{ $subnetwork->wifi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.connected_subnets') }}
                        </th>
                        <td>
                            {{ $subnetwork->connected_subnets->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.subnetwork.fields.gateway') }}
                        </th>
                        <td>
                            {{ $subnetwork->gateway->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.subnetworks.index') }}">
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
            @includeIf('admin.subnetwords.relationships.connectedSubnetsSubnetwords', ['subnetwords' => $subnetwork->connectedSubnetsSubnetwords])
        </div>
        <div class="tab-pane" role="tabpanel" id="subnetworks_networks">
            @includeIf('admin.subnetwords.relationships.subnetworksNetworks', ['networks' => $subnetwork->subnetworksNetworks])
        </div>
    </div>
</div>

@endsection