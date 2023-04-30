@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.securityControl.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.security-controls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                @can('security_controls_edit')
                    <a class="btn btn-info" href="{{ route('admin.security-controls.edit', $securityControl->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('security_controls_delete')
                    <form action="{{ route('admin.security-controls.destroy', $securityControl->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.securityControl.fields.name') }}
                        </th>
                        <td>
                            {{ $securityControl->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.securityControl.fields.description') }}
                        </th>
                        <td>
                            {{ $securityControl->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.security-controls.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $securityControl->created_at ? $securityControl->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $securityControl->updated_at ? $securityControl->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
@endsection