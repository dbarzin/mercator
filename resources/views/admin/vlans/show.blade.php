@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.vlan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.vlans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.vlan.fields.name') }}
                        </th>
                        <td>
                            {{ $vlan->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vlan.fields.description') }}
                        </th>
                        <td>
                            {!! $vlan->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vlan.fields.address') }}
                        </th>
                        <td>
                            {{ $vlan->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vlan.fields.mask') }}
                        </th>
                        <td>
                            {{ $vlan->mask }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vlan.fields.gateway') }}
                        </th>
                        <td>
                            {{ $vlan->gateway }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.vlans.index') }}">
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
            <a class="nav-link" href="#vlan_physical_routers" role="tab" data-toggle="tab">
                {{ trans('cruds.physicalRouter.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="vlan_physical_routers">
            @includeIf('admin.vlans.relationships.vlanPhysicalRouters', ['physicalRouters' => $vlan->vlanPhysicalRouters])
        </div>
    </div>
</div>

@endsection