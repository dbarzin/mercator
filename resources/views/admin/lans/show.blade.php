@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.lans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @can('lan_edit')
        <a class="btn btn-info" href="{{ route('admin.lans.edit', $lan->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('lan_delete')
        <form action="{{ route('admin.lans.destroy', $lan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.lan.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width='10%'>
                        {{ trans('cruds.lan.fields.name') }}
                    </th>
                    <td>
                        {{ $lan->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.lan.fields.description') }}
                    </th>
                    <td>
                        {{ $lan->description }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $lan->created_at ? $lan->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $lan->updated_at ? $lan->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.lans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
