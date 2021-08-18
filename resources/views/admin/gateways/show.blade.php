@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.gateway.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gateways.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.gateway.fields.id') }}
                        </th>
                        <td>
                            {{ $gateway->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.name') }}
                        </th>
                        <td>
                            {{ $gateway->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.description') }}
                        </th>
                        <td>
                            {!! $gateway->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.authentification') }}
                        </th>
                        <td>
                            {{ $gateway->authentification }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ip') }}
                        </th>
                        <td>
                            {{ $gateway->ip }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gateways.index') }}">
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
            <a class="nav-link" href="#gateway_subnetworks" role="tab" data-toggle="tab">
                {{ trans('cruds.subnetwork.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="gateway_subnetworks">
            @includeIf('admin.gateways.relationships.gatewaySubnetwords', ['subnetworks' => $gateway->gatewaySubnetworks])
        </div>
    </div>
</div>

@endsection
