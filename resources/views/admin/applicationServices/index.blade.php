@extends('layouts.admin')
@section('content')
@can('application_service_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route("admin.application-services.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.applicationService.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.applicationService.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.applicationService.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.applicationService.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.applicationService.fields.exposition') }}
                        </th>
                        <th>
                            {{ trans('cruds.applicationService.fields.applications') }}
                        </th>
                        <th>
                            {{ trans('cruds.applicationService.fields.modules') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applicationServices as $key => $applicationService)
                        <tr data-entry-id="{{ $applicationService->id }}"

                        @if (
                                ($applicationService->description==null)||
                                ($applicationService->servicesApplications->count()==0)
                            )
                                class="table-warning"
                        @endif

                            >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.application-services.show', $applicationService->id) }}">
                                {{ $applicationService->name }}
                                </a>
                            </td>
                            <td>
                                {!! $applicationService->description !!}
                            </td>
                            <td>
                                {{ $applicationService->exposition ?? '' }}
                            </td>
                            <td>
                                @foreach($applicationService->servicesApplications as $application)
                                    <a href="{{ route('admin.applications.show', $application->id) }}">
                                    {{ $application->name }}
                                    </a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($applicationService->modules as $module)
                                    <a href="{{ route('admin.application-modules.show', $module->id) }}">
                                    {{ $module->name }}
                                    </a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('application_service_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.application-services.show', $applicationService->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('application_service_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.application-services.edit', $applicationService->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('application_service_delete')
                                    <form action="{{ route('admin.application-services.destroy', $applicationService->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.applicationService.title_singular"),
    'URL' => route('admin.application-services.massDestroy'),
    'canDelete' => auth()->user()->can('application_service_delete') ? true : false
));
</script>
@endsection
