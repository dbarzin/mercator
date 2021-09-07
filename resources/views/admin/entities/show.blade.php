@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.entity.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped ">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.entity.fields.name') }}
                        </th>
                        <td>
                            {{ $entity->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.description') }}
                        </th>
                        <td>
                            {!! $entity->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.security_level') }}
                        </th>
                        <td>
                            {!! $entity->security_level !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.entity.fields.contact_point') }}
                        </th>
                        <td>
                            {!! $entity->contact_point !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.entities.index') }}">
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
            <a class="nav-link" href="#entity_resp_databases" role="tab" data-toggle="tab">
                {{ trans('cruds.database.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#entity_resp_m_applications" role="tab" data-toggle="tab">
                {{ trans('cruds.application.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#source_relations" role="tab" data-toggle="tab">
                {{ trans('cruds.relation.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#destination_relations" role="tab" data-toggle="tab">
                {{ trans('cruds.relation.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#entities_m_applications" role="tab" data-toggle="tab">
                {{ trans('cruds.application.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#entities_processes" role="tab" data-toggle="tab">
                {{ trans('cruds.process.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="entity_resp_databases">
            @includeIf('admin.entities.relationships.databases', ['databases' => $entity->entityRespDatabases])
        </div>
        <div class="tab-pane" role="tabpanel" id="entity_resp_m_applications">
            @includeIf('admin.entities.relationships.applications', ['mApplications' => $entity->entityRespMApplications])
        </div>
        <div class="tab-pane" role="tabpanel" id="source_relations">
            @includeIf('admin.entities.relationships.sourceRelations', ['relations' => $entity->sourceRelations])
        </div>
        <div class="tab-pane" role="tabpanel" id="destination_relations">
            @includeIf('admin.entities.relationships.destinationRelations', ['relations' => $entity->destinationRelations])
        </div>
        <div class="tab-pane" role="tabpanel" id="entities_m_applications">
            @includeIf('admin.entities.relationships.entitiesMApplications', ['mApplications' => $entity->entitiesMApplications])
        </div>
        <div class="tab-pane" role="tabpanel" id="entities_processes">
            @includeIf('admin.entities.relationships.entitiesProcesses', ['processes' => $entity->entitiesProcesses])
        </div>
    </div>
</div>

@endsection