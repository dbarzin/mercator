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

                @can('application_service_edit')
                    <a class="btn btn-info" href="{{ route('admin.application-services.edit', $applicationService->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('application_service_delete')
                    <form action="{{ route('admin.application-services.destroy', $applicationService->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan
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
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $applicationService->created_at->format(trans('global.timestamp')) }} |
        {{ trans('global.updated_at') }} {{ $applicationService->updated_at->format(trans('global.timestamp')) }} 
    </div>
</div>
@endsection