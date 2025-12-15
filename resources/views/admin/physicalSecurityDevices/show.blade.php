@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.physical-security-devices.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success"
           href="{{ route('admin.report.explore') }}?node=PSECURITY_{{$physicalSecurityDevice->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('physical_security_device_edit')
            <a class="btn btn-info"
               href="{{ route('admin.physical-security-devices.edit', $physicalSecurityDevice->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('physical_security_device_delete')
            <form action="{{ route('admin.physical-security-devices.destroy', $physicalSecurityDevice->id) }}"
                  method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                  style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.physicalSecurityDevice.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.physicalSecurityDevice.fields.name') }}
                        </th>
                        <td colspan='2'>
                            {{ $physicalSecurityDevice->name }}
                        </td>
                    </tr>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.physicalSecurityDevice.fields.type') }}
                        </th>
                        <td colspan='2'>
                            {{ $physicalSecurityDevice->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.attributes') }}
                        </th>
                        <td colspan="2">
                            @foreach(explode(" ",$physicalSecurityDevice->attributes) as $attribute)
                                <span class="badge badge-info">{{ $attribute }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.description') }}
                        </th>
                        <td>
                            {!! $physicalSecurityDevice->description !!}
                        </td>
                        <td width="10%">
                            @if ($physicalSecurityDevice->icon_id === null)
                                <img src='/images/securitydevice.png' width='120' height='120'>
                            @else
                                <img src='{{ route('admin.documents.show', $physicalSecurityDevice->icon_id) }}'
                                     width='120'
                                     height='120'>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.address_ip') }}
                        </th>
                        <td colspan="2">
                            {{ $physicalSecurityDevice->address_ip }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.security_devices') }}
                        </th>
                        <td colspan="2">
                            @foreach($physicalSecurityDevice->securityDevices as $device)
                                <a href="{{ route('admin.security-devices.show', $device->id) }}">{{ $device->name }}</a>
                                @if(!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.physicalSecurityDevice.fields.site') }}
                        </th>
                        <td colspan="2">
                            @if($physicalSecurityDevice->site!=null)
                                <a href="{{ route('admin.sites.show', $physicalSecurityDevice->site->id) }}">
                                    {{ $physicalSecurityDevice->site->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.physicalSecurityDevice.fields.building') }}
                        </th>
                        <td colspan="2">
                            @if($physicalSecurityDevice->building!=null)
                                <a href="{{ route('admin.buildings.show', $physicalSecurityDevice->building->id) }}">
                                    {{ $physicalSecurityDevice->building->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.physicalSecurityDevice.fields.bay') }}
                        </th>
                        <td colspan="2">
                            @if($physicalSecurityDevice->bay!=null)
                                <a href="{{ route('admin.bays.show', $physicalSecurityDevice->bay->id) }}">
                                    {{ $physicalSecurityDevice->bay->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $physicalSecurityDevice->created_at ? $physicalSecurityDevice->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $physicalSecurityDevice->updated_at ? $physicalSecurityDevice->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.physical-security-devices.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
