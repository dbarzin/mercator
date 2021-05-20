@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationService.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-services.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.applicationService.fields.name') }}
                        </th>
                        <td>
                            {{ $applicationService->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationService.fields.description') }}
                        </th>
                        <td>
                            {!! $applicationService->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationService.fields.exposition') }}
                        </th>
                        <td>
                            {{ $applicationService->exposition }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationService.fields.modules') }}
                        </th>
                        <td>
                            @foreach($applicationService->modules as $key => $modules)
                                <span class="label label-info">{{ $modules->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-services.index') }}">
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
            <a class="nav-link" href="#service_source_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#service_dest_fluxes" role="tab" data-toggle="tab">
                {{ trans('cruds.flux.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#services_m_applications" role="tab" data-toggle="tab">
                {{ trans('cruds.application.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="service_source_fluxes">
            @includeIf('admin.applicationServices.relationships.serviceSourceFluxes', ['fluxes' => $applicationService->serviceSourceFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="service_dest_fluxes">
            @includeIf('admin.applicationServices.relationships.serviceDestFluxes', ['fluxes' => $applicationService->serviceDestFluxes])
        </div>
        <div class="tab-pane" role="tabpanel" id="services_m_applications">
            @includeIf('admin.applicationServices.relationships.servicesMApplications', ['mApplications' => $applicationService->servicesMApplications])
        </div>
    </div>
</div>

@endsection