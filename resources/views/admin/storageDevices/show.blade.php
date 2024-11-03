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
                        <td width="40%" colspan="2">
                            {{ $storageDevice->name }}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.storageDevice.fields.type') }}
                        </th>
                        <td width="40%" colspan="2">
                            {{ $storageDevice->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.description') }}
                        </th>
                        <td colspan='5'>
                            {!! $storageDevice->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.address_ip') }}
                        </th>
                        <td colspan="5">
                            {{ $storageDevice->address_ip }}
                        </td>
                    </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.storageDevice.fields.site') }}
                    </th>
                    <td width="22%">
                        @if ($storageDevice->site!=null)
                            <a href="{{ route('admin.sites.show', $storageDevice->site->id) }}">
                            {{ $storageDevice->site->name ?? '' }}
                            </a>
                        @endif
                    </td>
                    <th width="10%">
                        {{ trans('cruds.storageDevice.fields.building') }}
                    </th>
                    <td width="22%">
                        @if ($storageDevice->building!=null)
                            <a href="{{ route('admin.buildings.show', $storageDevice->building->id) }}">
                            {{ $storageDevice->building->name ?? '' }}
                            </a>
                        @endif
                    </td>
                    <th width="10%">
                        {{ trans('cruds.storageDevice.fields.bay') }}
                    </th>
                    <td width="22%">
                        @if ($storageDevice->bay!=null)
                            <a href="{{ route('admin.bays.show', $storageDevice->bay->id) }}">
                            {{ $storageDevice->bay->name ?? '' }}
                            </a>
                        @endif
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
