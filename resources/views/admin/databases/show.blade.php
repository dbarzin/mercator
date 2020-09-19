@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.database.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.databases.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.name') }}
                        </th>
                        <td>
                            {{ $database->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.description') }}
                        </th>
                        <td>
                            {!! $database->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.entities') }}
                        </th>
                        <td>
                            @foreach($database->entities as $key => $entities)
                                <span class="label label-info">{{ $entities->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.entity_resp') }}
                        </th>
                        <td>
                            {{ $database->entity_resp->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.responsible') }}
                        </th>
                        <td>
                            {{ $database->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.informations') }}
                        </th>
                        <td>
                            @foreach($database->informations as $key => $informations)
                                <span class="label label-info">{{ $informations->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.type') }}
                        </th>
                        <td>
                            {{ $database->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.security_need') }}
                        </th>
                        <td>
                            @if ($database->security_need==1) 
                                Public
                            @elseif ($database->security_need==2)
                                Internal
                            @elseif ($database->security_need==3)
                                Confidential
                            @elseif ($database->security_need==4)
                                Secret
                            @endif                            
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.database.fields.external') }}
                        </th>
                        <td>
                            {{ $database->external }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.databases.index') }}">
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
            <a class="nav-link" href="#database_source_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#database_dest_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#databases_m_applications" role="tab" data-toggle="tab">
                {{ trans('cruds.application.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="database_source_fluxes">
            @includeIf('admin.databases.relationships.databaseSourceFluxes', ['fluxes' => $database->databaseSourceFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="database_dest_fluxes">
            @includeIf('admin.databases.relationships.databaseDestFluxes', ['fluxes' => $database->databaseDestFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="databases_m_applications">
            @includeIf('admin.databases.relationships.databasesMApplications', ['mApplications' => $database->databasesMApplications])
        </div>
    </div>
</div>

@endsection