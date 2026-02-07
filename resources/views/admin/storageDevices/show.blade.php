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
        @include('admin.storageDevices._details', [
            'storageDevice' => $storageDevice,
            'withLink' => false,
        ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $storageDevice->created_at ? $storageDevice->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $storageDevice->updated_at ? $storageDevice->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.storage-devices.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
