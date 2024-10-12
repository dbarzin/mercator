@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.security-devices.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.security-devices.edit', $securityDevice->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.security-devices.destroy', $securityDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.securityDevice.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.securityDevice.fields.name') }}
                    </th>
                    <td>
                        {{ $securityDevice->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.securityDevice.fields.description') }}
                    </th>
                    <td>
                        {!! $securityDevice->description !!}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $securityDevice->created_at ? $securityDevice->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $securityDevice->updated_at ? $securityDevice->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.security-devices.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
