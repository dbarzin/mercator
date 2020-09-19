@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.storageDevice.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.storage-devices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.storageDevice.fields.id') }}
                        </th>
                        <td>
                            {{ $storageDevice->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
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
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.storage-devices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection