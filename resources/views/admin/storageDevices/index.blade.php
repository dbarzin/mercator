@extends('layouts.admin')
@section('content')
@can('storage_device_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.storage-devices.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.storageDevice.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.storageDevice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.address_ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.bay') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storageDevices as $key => $storageDevice)
                        <tr data-entry-id="{{ $storageDevice->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">
                                    {{ $storageDevice->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $storageDevice->type ?? '' !!}
                            </td>
                            <td>
                                {!! $storageDevice->address_ip ?? '' !!}
                            </td>
                            <td>
                                @if ($storageDevice->site!==null)
                                    <a href="{{ route('admin.sites.show', $storageDevice->site_id) }}">{{ $storageDevice->site->name }}</a>
                                @endif
                            </td>
                            <td>
                                @if ($storageDevice->building!==null)
                                    <a href="{{ route('admin.buildings.show', $storageDevice->building_id) }}">{{ $storageDevice->building->name }}</a>
                                @endif
                            </td>
                            <td>
                                @if ($storageDevice->bay!==null)
                                    <a href="{{ route('admin.bays.show', $storageDevice->bay_id) }}">{{ $storageDevice->bay->name }}</a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('storage_device_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('storage_device_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.storage-devices.edit', $storageDevice->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('storage_device_delete')
                                    <form action="{{ route('admin.storage-devices.destroy', $storageDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.storageDevice.title_singular"),
    'URL' => route('admin.storage-devices.massDestroy'),
    'canDelete' => auth()->user()->can('storage_device_delete') ? true : false
));
</script>
@endsection
