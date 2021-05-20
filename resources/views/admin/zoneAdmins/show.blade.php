@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.zoneAdmin.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zone-admins.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.zoneAdmin.fields.name') }}
                        </th>
                        <td>
                            {{ $zoneAdmin->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.description') }}
                        </th>
                        <td>
                            {!! $zoneAdmin->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.zone-admins.index') }}">
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
            <a class="nav-link" href="#zone_admin_annuaires" role="tab" data-toggle="tab">
                {{ trans('cruds.annuaire.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#zone_admin_forest_ads" role="tab" data-toggle="tab">
                {{ trans('cruds.forestAd.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="zone_admin_annuaires">
            @includeIf('admin.zoneAdmins.relationships.zoneAdminAnnuaires', ['annuaires' => $zoneAdmin->zoneAdminAnnuaires])
        </div>
        <div class="tab-pane" role="tabpanel" id="zone_admin_forest_ads">
            @includeIf('admin.zoneAdmins.relationships.zoneAdminForestAds', ['forestAds' => $zoneAdmin->zoneAdminForestAds])
        </div>
    </div>
</div>

@endsection