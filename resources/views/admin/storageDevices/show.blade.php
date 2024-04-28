@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.storage-devices.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=STORAGE_{{$storageDevice->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('storage_device_edit')
        <a class="btn btn-info" href="{{ route('admin.storage-devices.edit', $storageDevice->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('storage_device_delete')
        <form action="{{ route('admin.storage-devices.destroy', $storageDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.storageDevice.title') }}
    </div>

    <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.storageDevice.fields.name') }}
                        </th>
                        <td>
                            {{ $storageDevice->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.description') }}
                        </th>
                        <td>
                            {!! $storageDevice->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.site') }}
                        </th>
                        <td>
                            {{ $storageDevice->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.building') }}
                        </th>
                        <td>
                            {{ $storageDevice->building->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.bay') }}
                        </th>
                        <td>
                            {{ $storageDevice->bay->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $storageDevice->created_at ? $storageDevice->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $storageDevice->updated_at ? $storageDevice->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.storage-devices.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
