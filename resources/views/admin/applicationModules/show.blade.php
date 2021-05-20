@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationModule.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-modules.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.applicationModule.fields.name') }}
                        </th>
                        <td>
                            {{ $applicationModule->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationModule.fields.description') }}
                        </th>
                        <td>
                            {!! $applicationModule->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-modules.index') }}">
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
            <a class="nav-link" href="#module_source_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#module_dest_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#modules_application_services" role="tab" data-toggle="tab">
                {{ trans('cruds.applicationService.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="module_source_fluxes">
            @includeIf('admin.applicationModules.relationships.moduleSourceFluxes', ['fluxes' => $applicationModule->moduleSourceFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="module_dest_fluxes">
            @includeIf('admin.applicationModules.relationships.moduleDestFluxes', ['fluxes' => $applicationModule->moduleDestFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="modules_application_services">
            @includeIf('admin.applicationModules.relationships.modulesApplicationServices', ['applicationServices' => $applicationModule->modulesApplicationServices])
        </div>
    </div>
</div>

@endsection