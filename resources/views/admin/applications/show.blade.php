@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.application.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <td>
                            {{ $application->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.description') }}
                        </th>
                        <td>
                            {!! $application->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.entities') }}
                        </th>
                        <td>
                            @foreach($application->entities as $key => $entities)
                                <span class="label label-info">{{ $entities->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.entity_resp') }}
                        </th>
                        <td>
                            {{ $application->entity_resp->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.responsible') }}
                        </th>
                        <td>
                            {{ $application->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.technology') }}
                        </th>
                        <td>
                            {{ $application->technology }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.type') }}
                        </th>
                        <td>
                            {{ $application->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.users') }}
                        </th>
                        <td>
                            {{ $application->users }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.security_need') }}
                        </th>
                        <td>
                            {{ trans('global.confidentiality') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$application->security_need_t] ?? "" }} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.external') }}
                        </th>
                        <td>
                            {{ $application->external }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.processes') }}
                        </th>
                        <td>
                            @foreach($application->processes as $key => $processes)
                                <span class="label label-info">{{ $processes->identifiant }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.services') }}
                        </th>
                        <td>
                            @foreach($application->services as $key => $services)
                                <span class="label label-info">{{ $services->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.databases') }}
                        </th>
                        <td>
                            @foreach($application->databases as $key => $databases)
                                <span class="label label-info">{{ $databases->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.logical_servers') }}
                        </th>
                        <td>
                            @foreach($application->logical_servers as $key => $logical_servers)
                                <span class="label label-info">{{ $logical_servers->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.application.fields.application_block') }}
                        </th>
                        <td>
                            {{ $application->application_block->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
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
            <a class="nav-link" href="#application_source_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#application_dest_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="application_source_fluxes">
            @includeIf('admin.mapplications.relationships.applicationSourceFluxes', ['fluxes' => $application->applicationSourceFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="application_dest_fluxes">
            @includeIf('admin.mapplications.relationships.applicationDestFluxes', ['fluxes' => $application->applicationDestFluxes])
        </div>
    </div>
</div>

@endsection