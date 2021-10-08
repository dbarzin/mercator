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

                @can('application_module_edit')
                    <a class="btn btn-info" href="{{ route('admin.application-modules.edit', $applicationModule->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('application_module_delete')
                    <form action="{{ route('admin.application-modules.destroy', $applicationModule->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan
                
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

@endsection