@extends('layouts.admin')

@section('title')
    {{ $storageDevice->name }}
@endsection

@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.storage-devices.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$storageDevice->getUID()}}">
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
        @can('backup_show')
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.backup.title") }}
        </div>
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">
            @if ($storageDevice->backups->count()>0)
            <div class="row">
                <div class="col-8">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th width="30%">{{ trans('cruds.storageDevice.title_singular') }}</th>
                                <th width="20%">{{ trans('cruds.backup.frequency') }}</th>
                                <th width="30%">{{ trans('cruds.backup.cycle') }}</th>
                                <th width="20%">{{ trans('cruds.backup.retention') }}</th>
                            </tr>
                            @foreach($storageDevice->backups as $backup)
                            <tr>
                                <td>
                                @if ($backup->logical_server_id!==null)
                                    <a href="{{ route('admin.logical-servers.show', $backup->logical_server_id) }}">
                                        {{ $backup->logicalServer->name }}
                                    </a>
                                @endif
                                </td>
                                <td>{{ $backup->backup_frequency ? trans("cruds.backup.frequencies.{$backup->backup_frequency}") : '' }}</td>
                                <td>{{ $backup->backup_cycle ? trans("cruds.backup.cycles.{$backup->backup_cycle}") : '' }}</td>
                                <td>{{ $backup->backup_retention ? $backup->backup_retention . ' ' . trans("cruds.backup.retention_unit") : '' }}</td>
                             </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        @endcan
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
